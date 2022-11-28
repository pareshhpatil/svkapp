<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;                      
use App\Jobs\InvoiceCreationViaXway;

class InvoiceCreationXway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice creation via xway PG';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auto_invoice_request_id = '31';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $this->common_model = new ParentModel();

        InvoiceCreationViaXway::dispatch(103)->onQueue(env('SQS_INVOICE_CREATION_VIA_XWAY'));

        // if($this->auto_invoice_request_id != '') {
            
        //     $get_auto_request_details = $this->common_model->getTableRow('auto_invoice_api_request','id',$this->auto_invoice_request_id);
        //     if(!empty($get_auto_request_details)) {
        //         //call save invoice api function with merchant access key and secret key
        //         $response = $this->APIrequest(env('APP_URL').'/api/v3/merchant/invoice/save', $get_auto_request_details->api_request_json, 'POST');
        //         $response_array = json_decode($response, 1);
        //         $invoice_id = $response_array['srvrsp'][0]['invoice_id'];
        //         if ($invoice_id != '') {
        //             //update status and payment_request_id in auto_invoice_request_table
        //             DB::table('auto_invoice_api_request')->where('id', $this->auto_invoice_request_id)
        //             ->update([
        //                 'status' => 1,
        //                 'payment_request_id'=>$invoice_id
        //             ]);
                    
        //             // //call invoice settle api request
        //             // $jsonArray['access_key_id'] = $get_merchant_keys->access_key_id;
        //             // $jsonArray['secret_access_key'] = $get_merchant_keys->secret_access_key;
        //             // $jsonArray['invoice_id'] = $invoice_id;
        //             // $paid_date = date('Y-m-d');
        //             // $jsonArray['paid_date'] = $paid_date;
        //             // $jsonArray['amount'] = $this->amount;
        //             // $jsonArray['mode'] = 'Online';
        //             // $jsonArray['bank_name'] = '';
        //             // $jsonArray['bank_ref_no'] = $this->transaction_id;
        //             // $jsonArray['cheque_no'] = '';
        //             // $jsonArray['cash_paid_to'] = '';
        //             // $jsonArray['notify'] = '1';
        //             // $jsonArray['attach_invoice_pdf'] = '1';
        //             // $json = json_encode($jsonArray,true);
        //             // // add one param to settle function attach invoice pdf default=0 
        //             // $settleResponse = $this->APIrequest(env('APP_URL').'/api/v3/merchant/invoice/settle', $json, 'POST');
        //             // $arr = json_decode($settleResponse, 1);
        //             // if (isset($arr['srvrsp']['transaction_id'])) {
        //             //     $offline_transaction_id = $arr['srvrsp']['transaction_id'];
        //             //     $this->common_model->updateTable('offline_response', 'offline_response_id', $arr['srvrsp']['transaction_id'],'post_paid_invoice', 1, 'user_id','System');
        //             // }
        //         } else {
        //             DB::table('auto_invoice_api_request')->where('id', $this->auto_invoice_request_id)->update(['status' => 2]);
        //             $array['invoice_id'] = '';
        //             return $array;
        //         }
        //     } else {
        //        $array['invoice_id'] = '';
        //        return $array;
        //     }
        // }
    }


    // public function handle()
    // {
    //     $this->common_model = new ParentModel();

    //     if($this->transaction_id != '') {
            
    //         $get_auto_request_details = $this->common_model->getTableRow('auto_invoice_api_request','transaction_id',$this->transaction_id);
    //         if(!empty($get_auto_request_details)) {
    //             $api_request_json = json_decode($get_auto_request_details->api_request_json,true);
                    
    //             if(!empty($api_request_json)) {
    //                 //find access key and secret key from merchant_security_key
    //                 $get_merchant_keys = $this->common_model->getTableRow('merchant_security_key','merchant_id',$get_auto_request_details->merchant_id);
    //                 if(!empty($get_merchant_keys)) {
    //                     $api_request_json['access_key_id'] = $get_merchant_keys->access_key_id;
    //                     $api_request_json['secret_access_key'] = $get_merchant_keys->secret_access_key;
    //                     foreach ($api_request_json['invoice'] as $ik=>$ival) {
    //                         $api_request_json['invoice'][$ik]['new_customer']['customer_name'] = $this->billing_name;
    //                         $api_request_json['invoice'][$ik]['new_customer']['email'] = $this->billing_email;
    //                         $api_request_json['invoice'][$ik]['new_customer']['mobile'] = $this->billing_mobile;
    //                         $api_request_json['invoice'][$ik]['new_customer']['address'] = $this->billing_address;
    //                         $api_request_json['invoice'][$ik]['new_customer']['city'] = $this->billing_city;
    //                         $api_request_json['invoice'][$ik]['new_customer']['state'] = $this->billing_state;
    //                         $api_request_json['invoice'][$ik]['new_customer']['zipcode'] = $this->billing_zipcode;
    //                     }
                        
    //                     $get_auto_request_details->api_request_json=json_encode($api_request_json,true);
    //                     //echo $get_auto_request_details->api_request_json;

    //                     //call save invoice api function with merchant access key and secret key
    //                     $response = $this->APIrequest(env('APP_URL').'/api/v3/merchant/invoice/save', $get_auto_request_details->api_request_json, 'POST');
    //                     $response_array = json_decode($response, 1);
    //                     $invoice_id = $response_array['srvrsp'][0]['invoice_id'];
    //                     if ($invoice_id != '') {
    //                         //update status and payment_request_id in auto_invoice_request_table
    //                         DB::table('auto_invoice_api_request')
    //                         ->where('transaction_id', $this->transaction_id)
    //                         ->update([
    //                             'status' => 1,
    //                             'payment_request_id'=>$invoice_id
    //                         ]);
                            
    //                         //call invoice settle api request
    //                         $jsonArray['access_key_id'] = $get_merchant_keys->access_key_id;
    //                         $jsonArray['secret_access_key'] = $get_merchant_keys->secret_access_key;
    //                         $jsonArray['invoice_id'] = $invoice_id;
    //                         $paid_date = date('Y-m-d');
    //                         $jsonArray['paid_date'] = $paid_date;
    //                         $jsonArray['amount'] = $this->amount;
    //                         $jsonArray['mode'] = 'Online';
    //                         $jsonArray['bank_name'] = '';
    //                         $jsonArray['bank_ref_no'] = $this->transaction_id;
    //                         $jsonArray['cheque_no'] = '';
    //                         $jsonArray['cash_paid_to'] = '';
    //                         $jsonArray['notify'] = '1';
    //                         $jsonArray['attach_invoice_pdf'] = '1';
    //                         $json = json_encode($jsonArray,true);
    //                         //Add one param to settle function attach invoice pdf default=0 
    //                         $settleResponse = $this->APIrequest(env('APP_URL').'/api/v3/merchant/invoice/settle', $json, 'POST');
    //                         print_r($settleResponse);
    //                         $arr = json_decode($settleResponse, 1);
    //                         die();
    //                         if (isset($arr['srvrsp']['transaction_id'])) {
    //                             $offline_transaction_id = $arr['srvrsp']['transaction_id'];
    //                             $this->common_model->updateTable('offline_response', 'offline_response_id', $arr['srvrsp']['transaction_id'],'post_paid_invoice', 1, 'user_id','System');
    //                         }
    //                     } else {
    //                         DB::table('auto_invoice_api_request')->where('transaction_id', $this->transaction_id)->update(['status' => 2]);
    //                         $array['invoice_id'] = '';
    //                         return $array;
    //                     }
    //                     $array['invoice_id'] = $invoice_id;
    //                     return $array;
    //                 }
    //             }
    //         }
    //     }
    // }

    public function APIrequest($url, $post_string, $method = "POST")
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $post_string,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }
}
