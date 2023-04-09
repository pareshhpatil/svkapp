<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Bulkupload;
use App\Model\Template;
use App\Model\Paymentrequest;
use Excel;
use App\Lib\Encryption;
use App\Lib\MailWrapper;
use Log;
use Exception;

class bulkuploadsave extends Command {

    private $template_model;
    private $bulk_model;
    private $payment_request_model;
    private $template_link;
    private $template_id;
    private $columns = array();
    private $rows = array();
    private $user_id = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkinvoice:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save bulk upload invoices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        $this->bulk_model = new Bulkupload();
        $this->template_model = new Template();
        $this->payment_request_model = new Paymentrequest();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            Log::info('Bulkupload save cron initiated');
            $list = $this->bulk_model->getBulklist(2);
            foreach ($list as $item) {
                $this->user_id = $item->updated_by;
                $error_list = array();
                Log::info('Bulkupload staging request save bulk_id:' . $item->bulk_upload_id);
                $this->bulk_model->updateBulkuploadStatus($item->bulk_upload_id, 8, $this->user_id);
                $success = 1;
                $post_data = $this->createPost('/public/uploads/excel/staging/' . $item->system_filename);
                $total_rows = count($post_data['data']);
                $group_suminsured = $post_data['group_suminsured'];
                Log::info('Total Rows found :' . $total_rows);
                foreach ($post_data['data'] as $row) {
                    $dependant_name = array();
                    $relationship = array();
                    $gender = array();
                    $date_of_birth = array();
                    $sum_insured = array();
                    $dob = array();
                    $sum_insured_last = 0;
                    $premium = array();
                    $service_tax = array();
                    $total_premium = array();
                    $marital_status = array();
                    $email_id = array();
                    $mobile_number = array();
                    $custom_col_id = array();
                    $custom_col_value = array();
                    foreach ($row['plan_details'] as $pd) {
                        $dependant_name[] = (isset($pd['dependant_name'])) ? $pd['dependant_name'] : '';
                        $relationship[] = (isset($pd['relationship'])) ? $pd['relationship'] : '';
                        $gender[] = (isset($pd['gender'])) ? $pd['gender'] : '';
                        $date_of_birth[] = (isset($pd['date_of_birth'])) ? $pd['date_of_birth'] : '';
                        $sum_insured[] = (isset($pd['sum_insured'])) ? $pd['sum_insured'] : 0;
                        $sum_insured_last = (isset($pd['sum_insured'])) ? $pd['sum_insured'] : 0;
                        $dob[] = (isset($pd['date_of_birth'])) ? $pd['date_of_birth'] : '';
                        $premium[] = (isset($pd['premium'])) ? $pd['premium'] : 0;
                        $service_tax[] = (isset($pd['service_tax'])) ? $pd['service_tax'] : 0;
                        $total_premium[] = (isset($pd['total_premium'])) ? $pd['total_premium'] : 0;
                    }
                    if (isset($row['custom_col'])) {
                        foreach ($row['custom_col'] as $col) {
                            $custom_col_id[] = $col['column_id'];
                            $custom_col_value[] = $col['value'];
                        }
                    }

                    if ($group_suminsured == 1) {
                        $policy_sum_insured = $sum_insured_last;
                    } else {
                        $policy_sum_insured = array_sum($sum_insured);
                    }

                    $policy_net_premium = array_sum($premium);
                    $policy_service_tax = array_sum($service_tax);
                    $policy_gross_premium = array_sum($total_premium);
                    $notify = (strtolower($row['notify']['value']) == 'yes') ? 1 : 0;
                    $result = $this->payment_request_model->savePaymentRequest(1, $item->bulk_upload_id, $item->template_id, $item->corporate_id, $item->insurance_branch_id, $item->insurance_id, $item->plan_id, $row['bill_date']['value'], $row['due_date']['value'], $row['employee_code']['value'], $row['employee_name']['value'], $row['employee_email_id']['value'], $row['employee_mobile_no']['value'], $policy_sum_insured, $policy_net_premium, $policy_service_tax, $policy_gross_premium, $row['comments']['value'], $notify, $dependant_name, $relationship, $gender, $date_of_birth, $sum_insured, $dob, $premium, $service_tax, $total_premium, $custom_col_id, $custom_col_value, $this->user_id);
                    if (!isset($result[0]->payment_request_id)) {
                        $success = 0;
                        $error_list[] = $result;
                        Log::error('Staging payment request failed Error :' . $result);
                    } else {
                        Log::info('Staging payment request saved req_id :' . $result[0]->payment_request_id);
                    }
                }
                if ($success == 1) {
                    $this->bulk_model->updateBulkuploadStatus($item->bulk_upload_id, 3, $this->user_id);
                    Log::info('Staging bulk request saved bulk_id :' . $item->bulk_upload_id);
                    $this->sendbulkuploadnotification($item->corporate_id, $this->user_id);
                } else {
                    $this->bulk_model->updateBulkuploadStatus($item->bulk_upload_id, 1, $this->user_id);
                    Log::info('Staging bulk request failed bulk_id :' . $item->bulk_upload_id . ' Error: ' . json_encode($error_list));
                    $this->bulk_model->saveBulkuploadError($item->bulk_upload_id, json_encode($error_list), $this->user_id);
                }
            }
        } catch (Exception $e) {
            Log::error('EB009 Error while staging bulk upload save Error: ' . $e->getMessage());
        }
    }

    public function createPost($file) {
        try {
            $errors = array();
            Excel::load($file, function($reader) {
                $reader->setDateFormat('Y-m-d');
                $results = $reader->toObject();
                $this->template_link = $results->getTitle();
                $results = $reader->get();
                $checkrows = $results->toArray();
                if (!empty($checkrows)) {
                    $rows = $reader->first()->toArray();
                }
                if (!empty($rows)) {
                    $this->columns = $reader->first()->keys()->toArray();
                    $this->rows = $results->toArray();
                }
            })->get();

            $template_id = Encryption::decode($this->template_link);
            if (is_numeric($template_id)) {
                $result = $this->getTemplateColumn($this->template_link);
                $template_type = $result['template_type'];
                $group_suminsured = $result['group_suminsured'];
                foreach ($result['column'] as $col) {
                    $f_col = strtolower(str_replace(" ", "_", $col));
                    $f_col = strtolower(str_replace("(", "", $f_col));
                    $f_col = strtolower(str_replace(")", "", $f_col));
                    $exp_col[] = $f_col;
                }
                $diff1 = array_diff($exp_col, $this->columns);
                $diff2 = array_diff($this->columns, $exp_col);
                if (!empty($diff1) || !empty($diff2)) {
                    $errors[0][0] = 'Invalid excel';
                    $errors[0][1] = 'Download excel from template list and re-upload with data.';
                }
                if (empty($this->rows)) {
                    $errors[0][0] = 'Empty excel';
                    $errors[0][1] = 'Uploaded excel is empty.';
                }
            } else {
                $errors[0][0] = 'Invalid excel';
                $errors[0][1] = 'Download excel from template list and re-upload with data.';
            }



            $employee_code = '';
            $sum_insured = '';
            $int = -1;
            $data = array();
            $employee_array = array();
            $employee_duplicate = array();
            if (empty($errors)) {
                foreach ($this->rows as $row) {
                    if ($employee_code == $row['employee_code'] && $row['employee_code'] != '') {
                        if ($template_type == 'simple') {
                            $data[$int]['plan_details'][] = array('sum_insured' => $row['sum_insured'], 'premium' => $row['premium'], 'service_tax' => $row['gst'], 'total_premium' => $row['total_premium']);
                        } else {
                            $data[$int]['plan_details'][] = array('dependant_name' => $row['dependant_name'], 'sum_insured' => $row['sum_insured'], 'premium' => $row['premium'], 'service_tax' => $row['gst'], 'relationship' => $row['relationship'], 'gender' => $row['gender'], 'date_of_birth' => $row['date_of_birth'], 'total_premium' => $row['total_premium']);
                        }
                    } else {
                        $int++;
                        $employee_code = $row['employee_code'];
                        $data[$int]['bill_date'] = array('value' => $row['bill_date'], 'column_id' => '');
                        $data[$int]['due_date'] = array('value' => $row['due_date'], 'column_id' => '');
                        $data[$int]['employee_code'] = array('value' => $row['employee_code'], 'column_id' => '');
                        $data[$int]['employee_name'] = array('value' => $row['employee_name'], 'column_id' => '');
                        $data[$int]['employee_email_id'] = array('value' => $row['employee_email_id'], 'column_id' => '');
                        $data[$int]['employee_mobile_no'] = array('value' => $row['employee_mobile_no'], 'column_id' => '');
                        $data[$int]['comments'] = array('value' => $row['comments'], 'column_id' => '');
                        if (isset($row['notify'])) {
                            $data[$int]['notify'] = array('value' => $row['notify'], 'column_id' => '');
                        } else {
                            $data[$int]['notify'] = array('value' => 'Yes', 'column_id' => '');
                        }
                        if ($template_type == 'simple') {
                            $data[$int]['plan_details'][] = array('sum_insured' => $row['sum_insured'], 'premium' => $row['premium'], 'service_tax' => $row['gst'], 'total_premium' => $row['total_premium']);
                        } else {
                            $data[$int]['plan_details'][] = array('dependant_name' => $row['dependant_name'], 'sum_insured' => $row['sum_insured'], 'premium' => $row['premium'], 'service_tax' => $row['gst'], 'relationship' => $row['relationship'], 'gender' => $row['gender'], 'date_of_birth' => $row['date_of_birth'], 'total_premium' => $row['total_premium']);
                        }
                        $arrow = array_values($row);
                        $cint = 0;
                        foreach ($result['column_id'] as $col_id) {
                            if ($col_id > 0) {
                                $data[$int]['custom_col'][] = array('column_name' => $result['column'][$cint], 'value' => $arrow[$cint], 'column_id' => $col_id, 'col_datatype' => $result['col_datatype'][$cint]);
                            }
                            $cint++;
                        }
                    }
                    if ($template_type == 'simple' && in_array($row['employee_code'], $employee_array)) {
                        if ($row['employee_code'] != '') {
                            $employee_duplicate[] = $row['employee_code'];
                        }
                    }
                    $employee_array[] = $row['employee_code'];
                }
                if (!empty($employee_duplicate)) {
                    $errors[0][0] = 'Duplicate Employee codes';
                    $errors[0][1] = json_encode(array_unique($employee_duplicate));
                    return array('errors' => $errors);
                }
                return array('data' => $data, 'template_id' => $template_id, 'template_type' => $template_type, 'group_suminsured' => $group_suminsured);
            } else {
                return array('errors' => $errors);
            }
        } catch (Exception $e) {
            Log::error('EB009 Error while Create bulk upload post Error: ' . $e->getMessage());
        }
    }

    public function getTemplateColumn($id) {
        $this->template_link = $id;
        $template_id = Encryption::decode($this->template_link);
        $detail = $this->template_model->getTemplateDetail($template_id);
        $metadata = $this->template_model->getTemplateMetadata($template_id);

        #bulkupload column
        $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $column_cell = array();
        foreach ($first as $s) {
            $column_cell[] = $s;
        }
        foreach ($first as $f) {
            foreach ($first as $s) {
                $column_cell[] = $f . $s;
            }
        }

        $column[] = 'Bill date';
        $datatype[] = 'date';
        $column[] = 'Due date';
        $datatype[] = 'date';
        $column[] = 'Employee code';
        $datatype[] = 'text';
        $column[] = 'Employee name';
        $datatype[] = 'text';
        $column[] = 'Employee email ID';
        $datatype[] = 'text';
        $column[] = 'Employee mobile no';
        $datatype[] = 'text';


        $column_id = array('', '', '', '', '', '');
        foreach ($metadata as $item) {
            if ($item->save_table_name == 'metadata' && $item->column_type != 'DS') {
                $column[] = $item->column_name;
                $datatype[] = $item->column_datatype;
                $column_id[] = $item->column_id;
            }
        }

        if ($detail->template_type == 'standard') {
            $column[] = 'Dependant name';
            $datatype[] = 'text';
            $column_id[] = '';
            $column[] = 'Relationship';
            $datatype[] = 'text';
            $column_id[] = '';
            $column[] = 'Gender';
            $datatype[] = 'text';
            $column_id[] = '';
            $column[] = 'Date of Birth';
            $datatype[] = 'date';
            $column_id[] = '';
        }

        $column[] = 'Sum Insured';
        $datatype[] = 'money';
        $column_id[] = '';
        $column[] = 'Premium';
        $datatype[] = 'money';
        $column_id[] = '';
        $column[] = 'GST';
        $datatype[] = 'money';
        $column_id[] = '';
        $column[] = 'Total premium';
        $datatype[] = 'money';
        $column_id[] = '';
        $column[] = 'Comments';
        $datatype[] = 'text';
        $column_id[] = '';
        $column[] = 'Notify';
        $datatype[] = 'text';
        $column_id[] = '';

        $int = 0;
        foreach ($datatype as $dtype) {
            switch ($dtype) {
                case 'money':
                    $ex_datatype = '0.00';
                    break;
                case 'date':
                    $ex_datatype = 'dd-mm-yyyy';
                    break;
                default :
                    $ex_datatype = '@';
                    break;
            }
            $col_datatype[$column_cell[$int]] = "'" . $ex_datatype . "'";
            $int++;
        }

        $row = array('column' => $column, 'datatype' => $col_datatype, 'column_id' => $column_id, 'col_datatype' => $datatype, 'template_type' => $detail->template_type, 'template_name' => $detail->template_name, 'group_suminsured' => $detail->group_suminsured);
        return $row;
    }

    public function sendbulkuploadnotification($corporate_id, $user_id) {
        try {
            $email_wrap = new MailWrapper();
            $superadminlist = $this->bulk_model->getRoleUserDetails('IA_SUPER_ADMIN', '');
            $userdetails = $this->bulk_model->getUserId($user_id);
            foreach ($superadminlist as $row) {
                $data['name'] = $row->name;
                $data['uploaded_by'] = $userdetails->name;
                $email_wrap->sendmail($row->email, 'bulknotify', $data, 'Bulk upload notification');
            }
            $superadminlist = $this->bulk_model->getRoleUserDetails('IA_ADMIN', $corporate_id);
            foreach ($superadminlist as $row) {
                $data['name'] = $row->name;
                $data['uploaded_by'] = $userdetails->name;
                $email_wrap->sendmail($row->email, 'bulknotify', $data, 'Bulk upload notification');
            }
        } catch (Exception $e) {
            Log::error('EB009 Error while Create bulk upload post Error: ' . $e->getMessage());
        }
    }

}
