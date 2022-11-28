<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Libraries\Encrypt;
use App\Libraries\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Log;
use Exception;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Model\Gst;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Gstr2AReconJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $job_id;
    protected $gstin;
    protected $expense_from;
    protected $expense_to;
    protected $gst_from;
    protected $gst_to;
    protected $user_id;
    protected $merchant_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($merchant_id, $user_id, $job_id, $gstin,  $expense_from, $expense_to, $gst_from, $gst_to)
    {
        $this->job_id = $job_id;
        $this->gstin = $gstin;
        $this->expense_from = $expense_from;
        $this->expense_to = $expense_to;
        $this->gst_from = $gst_from;
        $this->gst_to = $gst_to;
        $this->user_id = $user_id;
        $this->merchant_id = $merchant_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->getGSTData();
    }

    private function getIrisToken()
    {

        $header =  array(
            "Content-Type: application/json",
            "product:SAPPHIRE",
            "tenant: " . env('IRIS_TENANT')
        );

        $post_string = '{"email":"' .  env('IRIS_EMAIL') . '", "password":"' . env('IRIS_PASSWORD') . '"}';
        $response = Helpers::APIrequest('https://api.irisgst.com/irisgst/mgmt/login', $post_string, "POST", true, $header);
        $response = json_decode($response, 1);
        if ($response['status'] == 'SUCCESS') {
            return  $response['response'];
        } else {
            $this->updateJobStatus('error');
            $this->fail($response['message']);
        }
    }

    private function downloadGSTData($type, $from)
    {
        $response = $this->getIrisToken();

        $header2 =  array(
            "Content-Type: application/json",
            "product:SAPPHIRE",
            "X-Auth-Token: " . $response['token'],
            "companyId:" . $response['companyid']
        );

        if ($type == 'MATCHED') {
            $gst_from = explode('-', $from);
            $response = Helpers::APIrequest('https://api.irisgst.com/irisgst/sapphire/gstn/data?gstin=' . $this->gstin . '&fillingPeriod=' . $gst_from[0] . $gst_from[1] . '&returnType=GSTR2A&action=B2B&refreshData=true', [], "GET", true, $header2);
        } else {
            $response = Helpers::APIrequest('https://api.irisgst.com/irisgst/sapphire/gstn/data?gstin=' . $this->gstin . '&fillingPeriod=' . $from . '&returnType=GSTR2A&action=B2B&refreshData=true', [], "GET", true, $header2);
        }
        $response = json_decode($response, 1);
        if ($response['status'] == 'SUCCESS') {
            return  $response;
        } else {
            $this->updateJobStatus('error');
            $this->fail(new Exception($response['message']));
        }
    }

    private function uploadS3andUpdateDB($merchant_id, $gst_data_json, $expense_json)
    {
        $model = new Gst();
        //define file name
        $gst_json_file_name = $this->job_id . '_API_' . strtotime("now") . '.json';
        // define file path 
        $gst_json_file_path = 'gst-recon/' . $merchant_id . '/' . $gst_json_file_name;
        //upload to S3
        Storage::disk('s3_private')->put($gst_json_file_path, $gst_data_json);
        //update to table
        $model->updateTable('gstr2b_recon_jobs', 'id', $this->job_id, 'gst2a_input_file', 'https://swipez-private.s3.ap-south-1.amazonaws.com/' . $gst_json_file_path);

        $expense_json_file_name = $this->job_id . '_PURCHASE_' . strtotime("now") . '.json';
        $expense_json_file_path = 'gst-recon/' . $merchant_id . '/' . $expense_json_file_name;
        Storage::disk('s3_private')->put($expense_json_file_path, json_encode($expense_json));
        $model->updateTable('gstr2b_recon_jobs', 'id', $this->job_id, 'expense_input_file', 'https://swipez-private.s3.ap-south-1.amazonaws.com/' . $expense_json_file_path);

        return true;
    }

    private function getGSTData()
    {
        $model = new Gst();
        $gst_data_json = [];
        //get GST Data from API
        if ($this->gst_from == $this->gst_to) {
            $gst_data_json = $this->downloadGSTData('MATCHED', $this->gst_from);
            if ($gst_data_json["response"] != null) {
                $gst_data_json = $gst_data_json["response"]["b2b"];
            }
        } else {
            $start    = new DateTime('01-' . $this->gst_from);
            $end      = new DateTime('30-' . $this->gst_to);
            $interval = DateInterval::createFromDateString('1 month');
            $period   = new DatePeriod($start, $interval, $end);

            foreach ($period as $dt) {
                $gst_period = $dt->format("mY");
                $gst_json = $this->downloadGSTData('UNMATCHED', $gst_period);
                if ($gst_json["response"] != null) {
                    $gst_json = $gst_json["response"]["b2b"];
                    if (count($gst_json) > 0) {
                        foreach ($gst_json as $gst_data) {
                            array_push($gst_data_json, $gst_data);
                        }
                    }
                }
            }
        }

        //get expense data 
        $expense_json = $model->getExpenseData($this->merchant_id,  $this->expense_from, $this->expense_to);

        $this->uploadS3andUpdateDB($this->merchant_id, json_encode($gst_data_json), $expense_json);

        //insert GST data into table 
        if (count($gst_data_json) > 0) {
            foreach ($gst_data_json as $gst_data) {
                foreach ($gst_data["inv"] as $gst_invoice_level_data) {
                    $vendor_name = $model->getColumnValue('vendor', 'gst_number',  $gst_data["ctin"], 'vendor_name');

                    $cgst_sum = 0.00;
                    $sgst_sum = 0.00;
                    $igst_sum = 0.00;
                    $taxable_value_sum = 0.00;
                    foreach ($gst_invoice_level_data["itms"] as $gst_particular_data) {
                        if (array_key_exists("camt", $gst_particular_data["itm_det"])) {
                            $cgst_sum +=  $gst_particular_data["itm_det"]["camt"];
                        }

                        if (array_key_exists("samt", $gst_particular_data["itm_det"])) {
                            $sgst_sum +=  $gst_particular_data["itm_det"]["samt"];
                        }

                        if (array_key_exists("iamt", $gst_particular_data["itm_det"])) {
                            $igst_sum +=  $gst_particular_data["itm_det"]["iamt"];
                        }

                        $taxable_value_sum +=  $gst_particular_data["itm_det"]["txval"];
                    }

                    $gstr_2a_table_id  = $model->insertJobDetails('invoice_comparision', 'GST', $this->job_id, $gst_data, $vendor_name, $gst_invoice_level_data,  $cgst_sum, $sgst_sum, $igst_sum, $this->user_id, $taxable_value_sum);

                    foreach ($gst_invoice_level_data["itms"] as $gst_particular_data) {
                        if (array_key_exists("camt", $gst_particular_data["itm_det"])) {
                            $camt = $gst_particular_data["itm_det"]["camt"];
                        } else {
                            $camt = "0.00";
                        }

                        if (array_key_exists("samt", $gst_particular_data["itm_det"])) {
                            $samt = $gst_particular_data["itm_det"]["samt"];
                        } else {
                            $samt = "0.00";
                        }

                        if (array_key_exists("iamt", $gst_particular_data["itm_det"])) {
                            $iamt = $gst_particular_data["itm_det"]["iamt"];
                        } else {
                            $iamt = "0.00";
                        }

                        $model->insertJobDetails('invoice_comparision_detail', 'GST', $gstr_2a_table_id, $gst_particular_data, '', '',  $camt, $samt, $iamt, $this->user_id, '');
                    }
                }
            }
        }

        //insert Expense data into table 
        $expense_json = json_decode(json_encode($expense_json), true);
        foreach ($expense_json as $expense_data) {
            //get vendor name by vendor ID
            $vendor_name = $model->getColumnValue('vendor', 'vendor_id', $expense_data["vendor_id"], 'vendor_name');
            //update the data
            $affected_rows  = $model->updateJobDetails('invoice_comparision', $this->job_id, $expense_data["gst_number"], $expense_data["invoice_no"], $expense_data, $vendor_name);

            $expense_particular_json = $model->getExpenseParticularData($expense_data["expense_id"]);
            $expense_particular_json = json_decode(json_encode($expense_particular_json), true);

            if ($affected_rows > 0) {
                foreach ($expense_particular_json as $expense_particular_data) {

                    $obj = $model->checkParticularExists($this->job_id, $expense_particular_data["amount"]);

                    if (count($obj) > 0) {
                        $affected_rows  = $model->updateJobDetailsParticular('invoice_comparision_detail', $obj[0]["id"],  $expense_particular_data);
                    } else {
                        $model->insertJobDetails('invoice_comparision_detail', 'EXPENSE', $obj[0]["gstr_2a_table_id"], $expense_particular_data, '', '',  '', '', '', $this->user_id, '');
                    }
                }
            } else {
                $expense_insert_array = [
                    'job_id' => $this->job_id,
                    'vendor_gstin' => $expense_data["gst_number"],
                    'vendor_name' => $vendor_name,
                    'purch_request_id' =>  $expense_data["expense_no"],
                    'purch_request_number' => $expense_data["invoice_no"],
                    'purch_request_date' =>   $expense_data["bill_date"],
                    'purch_request_total_amount' =>  $expense_data["total_amount"],
                    'purch_request_taxable_amount' => $expense_data["tds"],
                    'purch_request_type' =>   '',
                    'purch_request_state' =>  '',
                    'purch_request_cgst' =>  $expense_data["cgst_amount"],
                    'purch_request_sgst' => $expense_data["sgst_amount"],
                    'purch_request_igst' =>  $expense_data["igst_amount"],
                    'created_by' => $this->user_id,
                    'last_update_by' => $this->user_id,
                    'created_date' => date('Y-m-d H:i:s')
                ];

                $gstr_2a_table_id  = $model->insertInvoice($expense_insert_array);
                // $gstr_2a_table_id  = $model->insertJobDetails('invoice_comparision', 'EXPENSE', $this->job_id, $expense_data, $vendor_name, $expense_data, $expense_data["cgst_amount"],  $expense_data["sgst_amount"], $expense_data["igst_amount"], $this->user_id, $expense_data["tds"]);


                foreach ($expense_particular_json as $expense_particular_data) {
                    $array_particular = [
                        'gstr_2a_table_id' =>  $gstr_2a_table_id,
                        'purch_item_number' =>  $expense_particular_data["id"],
                        'purch_item_taxable_value' =>    $expense_particular_data["amount"],
                        'purch_item_tax_rate' =>   $expense_particular_data["tax"],
                        'purch_item_cgst' =>   $expense_particular_data["cgst_amount"],
                        'purch_item_sgst' =>   $expense_particular_data["sgst_amount"],
                        'purch_item_igst' =>   $expense_particular_data["igst_amount"],
                        'created_by' => $this->user_id,
                        'last_update_by' => $this->user_id,
                        'created_date' => date('Y-m-d H:i:s')
                    ];

                    $model->insertInvoiceParticular($array_particular);
                }
            }
        }

        //comparision of the data and final verdict and set the status
        $comparision_json = json_decode(json_encode($model->getFinalComparisionData($this->job_id)), true);
        foreach ($comparision_json as $comparision_data) {
            $status = "Auto Recon";

            if (
                $comparision_data["purch_request_date"] != $comparision_data["gst_request_date"]
                || $comparision_data["purch_request_total_amount"] != $comparision_data["gst_request_total_amount"]
                || $comparision_data["purch_request_taxable_amount"] != $comparision_data["gst_request_taxable_amount"]
                || $comparision_data["purch_request_total_amount"] != $comparision_data["gst_request_total_amount"]
                || $comparision_data["purch_request_cgst"] != $comparision_data["gst_request_cgst"]
                || $comparision_data["purch_request_sgst"] != $comparision_data["gst_request_sgst"]
                || $comparision_data["purch_request_igst"] != $comparision_data["gst_request_igst"]
            ) {
                $status = "Mismatch in values";
            }

            if (
                $comparision_data["purch_request_total_amount"] == '0.00'
                && $comparision_data["purch_request_taxable_amount"] == '0.00'
                && $comparision_data["purch_request_cgst"] == '0.00'
                && $comparision_data["purch_request_sgst"] == '0.00'
                && $comparision_data["purch_request_igst"] == '0.00'
            ) {
                $status = "Missing in my data";
            }

            if (
                $comparision_data["gst_request_total_amount"] == '0.00'
                && $comparision_data["gst_request_taxable_amount"] == '0.00'
                && $comparision_data["gst_request_cgst"] == '0.00'
                && $comparision_data["gst_request_sgst"] == '0.00'
                && $comparision_data["gst_request_igst"] == '0.00'
            ) {
                $status = "Missing in vendor GST filing";
            }

            //update table
            $model->updateTable(
                'invoice_comparision',
                'id',
                $comparision_data["id"],
                'status',
                $status
            );
        }

        $this->updateJobStatus('processed');

        $this->sendCompleteJobMail();
    }

    private function sendCompleteJobMail()
    {
        $model = new Gst();
        $email = $model->getColumnValueV2('merchant_billing_profile', 'merchant_id',  $this->merchant_id, 'gst_number',  $this->gstin, 'business_email');
        if ($email != '') {
            $data['list'] = $model->getSummarybyJobID($this->job_id, 'none', '', '', '', '', '', '', true);
            $data['click_here_link'] = env('APP_URL') . '/merchant/gst/reconciliation/summary/' . Encrypt::encode($this->job_id);
            $data['gst_text'] = $this->gstin . ' & ' . $this->gst_from;
            $data['merchant_gst_number'] = '';
            $data['merchant_company_name'] = '';
            $data['is_vendor_mail'] = 'true';
            $data['gstin_number'] = '';
            $data['month_year'] = '';
            MerchantSendMail::dispatch($email, $this->gstin . ' & ' . $this->gst_from . ' - GST R2 recon complete', $data, 'MAIL_VENDOR')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
        } else {
            Log::error(__CLASS__ . __METHOD__ . 'Complete Job Email Send Failed');
        }
    }

    private function updateJobStatus($status)
    {
        $model = new Gst();
        $model->updateTable(
            'gstr2b_recon_jobs',
            'id',
            $this->job_id,
            'status',
            $status
        );
    }

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        $this->updateJobStatus('error');
    }
}
