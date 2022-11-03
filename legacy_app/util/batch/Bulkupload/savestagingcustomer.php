<?php

include_once('bulkupload.php');

class SaveStagingCustomer extends BulkUpload
{

    private $customer_codes = array();

    function __construct()
    {
        parent::__construct();
        $this->customersave();
    }

    public function customersave()
    {
        $bulkuploadlist = $this->getbulkuploadlist(2, 2);
        foreach ($bulkuploadlist as $bulkupload) {
            try {
                $bulk_id = $bulkupload['bulk_upload_id'];
                $this->updateBulkUploadStatus($bulk_id, 8);
                if (!empty($bulkupload)) {
                    $this->logger->info(__CLASS__, 'Saving Bulk upload staging customer initiate bulk_id is : ' . $bulk_id . ' and total rows are ' . $bulkupload['total_rows']);
                    $file = '../../../../public/uploads/Excel/' . $bulkupload['merchant_id'] . '/staging/' . $bulkupload['system_filename'];
                    if (!file_exists($file)) {
                        $this->updateBulkUploadStatus($bulk_id, 1, 'File does not exist');
                        throw new Exception($file . ' file does not exist');
                    } else {
                        $result = $this->bulkUploadFile($file, $bulk_id);
                        if ($result['status'] == TRUE) {
                            $this->updateBulkUploadStatus($bulk_id, 3, '');
                        } else {
                            $this->updateBulkUploadStatus($bulk_id, 1, $result['error']);
                        }
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__CLASS__, '[E3]Error while uploading staging customer Bulk id: ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        }
    }

    public function bulkUploadFile($inputFile, $bulk_id, $worksheet = null, $row_number = 2, $merchant_id = null, $version = '')
    {
        try {
            if ($worksheet == null) {
                $inputFileType = PHPExcel_IOFactory::identify($inputFile);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFile);
                $subject = $objPHPExcel->getProperties()->getSubject();
                $link = $this->encrypt->decode($subject);
                $merchant_id = substr($link, 0, 10);
                $update_date = substr($link, 10, 19);
                $version = substr($link, 29, 2);
                $worksheet = $objPHPExcel->getSheet(0);

                $is_falied = 0;
            }
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            $this->customer_codes = array();
            require_once MODEL . 'merchant/CustomerModel.php';
            $customer = new CustomerModel();
            $custome_column = $customer->getCustomerBreakup($merchant_id);
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            $auto_generate = $merchant_setting['customer_auto_generate'];


            $getcolumnvalue = array();
            for ($rowno = $row_number; $rowno <= $highestRow; ++$rowno) {
                $cell = $worksheet->getCellByColumnAndRow(0, $rowno);
                $val = $cell->getFormattedValue();
                if ((string) $val != '') {
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $rowno);
                        $val = $cell->getFormattedValue();
                        $getcolumnvalue[$rowno][] = $val;
                    }
                    $post_row = array();
                    $int = 0;

                    if ($auto_generate == 0) {
                        $post_row['customer_code'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                    } else {
                        $post_row['customer_code'] = time();
                    }

                    $post_row['customer_name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['email'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['mobile'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['address'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['city'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['state'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['zipcode'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    if ($version != '' && $version == 'v2' || $version == 'v3') {
                        $post_row['country'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                    }

                    foreach ($custome_column as $row) {
                        $fval = $getcolumnvalue[$rowno][$int];

                        if ($row['column_datatype'] == 'company_name') {
                            $post_row['company_name'] = (string) $getcolumnvalue[$rowno][$int];
                        } else {
                            if ($row['column_datatype'] == 'date' && $fval != '') {
                                try {
                                    $excel_date = PHPExcel_Style_NumberFormat::toFormattedString($getcolumnvalue[$rowno][$int], 'Y-m-d');
                                    $excel_date = str_replace('/', '-', $excel_date);
                                } catch (Exception $e) {
                                    Sentry\captureException($e);
                                    $excel_date = (string) $getcolumnvalue[$rowno][$int];
                                }

                                try {
                                    $excel_date = str_replace('-', '/', $excel_date);
                                    $date = new DateTime($excel_date);
                                } catch (Exception $e) {
                                    Sentry\captureException($e);
                                    $excel_date = str_replace('/', '-', $excel_date);
                                    $date = new DateTime($excel_date);
                                }
                                $value = $date->format('d M Y');
                                $post_row['values'][] = $value;
                            } else {
                                $value = (string) $getcolumnvalue[$rowno][$int];
                                $post_row['values'][] = $value;
                            }
                            if ($row['column_datatype'] == 'password') {
                                $post_row['password'] = $value;
                            }
                            if ($row['column_datatype'] == 'gst') {
                                $post_row['GST'] = $value;
                            }
                            $post_row['ids'][] = $row['column_id'];
                            $post_row['datatypes'][] = $row['column_datatype'];
                            $post_row['column_name'][] = $row['column_name'];
                        }
                        $int = $int + 1;
                    }
                    $_POSTarray[] = $post_row;
                }
            }
            if ($inputFile == null) {
                $array['last_col'] = $int;
                $array['POSTarray'] = $_POSTarray;
                return $array;
            }
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();
                foreach ($_POSTarray as $_POST) {
                    $result = $this->validateuploadcustomer($auto_generate, $merchant_id, $customer);
                    if (!empty($result)) {
                        $is_falied = 1;
                        $result['row'] = $errorrow;
                        $errors[] = $result;
                    } else {
                    }
                    $errorrow++;
                }

                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $this->filterPost();
                        $result = $this->uploadcustomersave($bulk_id, $merchant_id, $merchant_id);
                        $count_int++;
                    }
                }
                if ($is_falied == 0) {
                    $this->logger->info(__CLASS__, 'Bulk staging customer saved sucessfully bulk id : ' . $bulk_id . ' Total customer ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($errors));
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__CLASS__, '[E6]Error while saving staging customer save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    function validateuploadcustomer($auto_generate, $merchant_id, $customer_model)
    {
        try {
            if ($auto_generate == 0) {
                $res = $customer_model->isExistCustomerCode($merchant_id, $_POST['customer_code']);
                if ($res != false) {
                    $hasErrors['customer_code'] = array('Customer code', 'Customer code already exists');
                    return $hasErrors;
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E246]Error while sending payment request Error:' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function uploadcustomersave($bulk_id, $merchant_id, $user_id)
    {
        try {
            $space_position = strpos($_POST['customer_name'], ' ');
            if ($space_position > 0) {
                $_POST['first_name'] = substr($_POST['customer_name'], 0, $space_position);
                $_POST['last_name'] = substr($_POST['customer_name'], $space_position);
            } else {
                $_POST['first_name'] = $_POST['customer_name'];
                $_POST['last_name'] = '';
            }
            $customer_code = $_POST['customer_code'];
            $addressfull = $_POST['address'];
            if (strlen($addressfull) > 250) {
                $_POST['address'] = substr($addressfull, 0, 250);
                $_POST['address2'] = substr($addressfull, 250);
            } else {
                $_POST['address2'] = '';
            }
            $_POST['password'] = isset($_POST['password']) ? $_POST['password'] : "";
            $_POST['GST'] = isset($_POST['GST']) ? $_POST['GST'] : "";
            $_POST['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : "";
            $_POST['country'] = isset($_POST['country']) ? $_POST['country'] : 'India';

            $column_id = (empty($_POST['ids'])) ? array() : $_POST['ids'];
            $column_value = (empty($_POST['values'])) ? array() : $_POST['values'];
            $result = $this->saveCustomer($bulk_id, $user_id, $merchant_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $column_id, $column_value, $_POST['password'], $_POST['GST'], $_POST['company_name'], $_POST['country']);
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E8]Error while save staging customer Error: ' . $e->getMessage());
        }
    }

    public function getState($state)
    {
        $sql = "select config_value from config where config_type='gst_state_code'";
        $this->db->exec($sql);
        $states = $this->db->resultset();
        foreach ($states as $item) {
            if (str_replace(' ', '', strtolower($item['config_value'])) == str_replace(' ', '', strtolower($state))) {
                return $item['config_value'];
            }
        }
    }

    public function saveCustomer($bulk_id, $user_id, $merchant_id, $customer_code, $first_name, $last_name, $email, $mobile, $address, $address2, $city, $state, $zipcode, $column_id, $column_value, $password = '', $gst = '', $company_name = '', $country = 'India')
    {
        try {
            $column_id = implode('~', $column_id);
            $column_value = implode('~', $column_value);
            $zipcode = ($zipcode > 0) ? $zipcode : '';
            $address = ($address === 0) ? '' : $address;
            $address2 = ($address2 === 0) ? '' : $address2;
            if (strtolower($email) == 'emailunavailable@swipez.in') {
                $email = '';
            }
            if ($mobile == '9999999999') {
                $mobile = '';
            }
            if ($country == 'India') {
                $db_state = $this->getState($state);
            } else {
                $db_state = $state;
            }

            $sql = "call `customer_staging_save`(:bulk_id,:user_id,:merchant_id,:customer_code,:first_name,:last_name,:email,:mobile,:address,:address2,:city,:state,:zipcode,:column_id,:column_value,:password,:gst,:company_name,:country);";
            $params = array(
                ':bulk_id' => $bulk_id, ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':customer_code' => $customer_code, ':first_name' => $first_name, ':last_name' => $last_name,
                ':email' => $email, ':mobile' => $mobile, ':address' => $address, ':address2' => $address2, ':city' => $city, ':state' => $db_state, ':zipcode' => $zipcode, ':column_id' => $column_id, ':column_value' => $column_value, ':password' => $password, ':gst' => $gst, ':company_name' => $company_name, ':country' => $country
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
                return TRUE;
            } else {

                SwipezLogger::error(__CLASS__, '[E9]Error while saving staging customer Error: for bulk id[' . $bulk_id . ']' . $row['Message']);
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E10]Error while saving new template Error: for user id[' . $user_id . ']' . $e->getMessage());
            return FALSE;
        }
    }
}

new SaveStagingCustomer();


include_once('savestagingfranchise.php');
include_once('savestagingtransaction.php');
include_once('savestagingtransfer.php');
include_once('savestagingvendor.php');
include_once('savestagingexpense.php');
