<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Invoice;
use Illuminate\Support\Facades\DB;

class InvoiceCreationViaXway implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auto_invoice_request_id;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($auto_invoice_request_id)
    {
        $this->auto_invoice_request_id = $auto_invoice_request_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invoiceModel = new Invoice();
        if($this->auto_invoice_request_id != '') {
            
            $api_request_json = $invoiceModel->getColumnValue('auto_invoice_api_request','id',$this->auto_invoice_request_id,'api_request_json');
            if(!empty($api_request_json)) {
                //call save invoice api function with merchant access key and secret key
                $response = $this->APIrequest(env('APP_URL').'/api/v3/merchant/invoice/save', $api_request_json, 'POST');
                $response_array = json_decode($response, 1);
                $invoice_id = $response_array['srvrsp'][0]['invoice_id'];
                if ($invoice_id != '') {
                    //update status and payment_request_id in auto_invoice_request_table
                    $invoiceModel->updateAutoInvoiceRequestStatus($this->auto_invoice_request_id,$invoice_id,1);
                } else {
                    $invoiceModel->updateAutoInvoiceRequestStatus($this->auto_invoice_request_id,null,2);
                }
            }
        }
    }

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

    public function failed(\Exception $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }

}
