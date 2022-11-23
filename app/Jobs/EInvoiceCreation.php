<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Helpers;
use App\Model\Invoice;
use DOMPDF;
use Illuminate\Support\Facades\Storage;
use App\Libraries\MailWrapper;
use App\Http\Controllers\EinvoiceController;

class EInvoiceCreation implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $payment_request_id;
    protected $source;
    protected $notify;
    protected $model;
    protected $einvoiceController;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payment_request_id, $source, $notify)
    {
        $this->payment_request_id = $payment_request_id;
        $this->source = $source;
        $this->notify = $notify;
    }


    public function handle()
    {
        $this->model = new Invoice();
        $this->einvoiceController = new EinvoiceController();
        $info = $this->model->getInvoiceInfo($this->payment_request_id, 'customer');
        $info = json_decode(json_encode($info, 1), 1);
        $merchant_state_code = $this->model->getGstStateCode($info['merchant_state']);
        $customer_state_code = $this->model->getGstStateCode($info['customer_state']);
        $request_id = $this->model->saveEinvoiceRequest($info, $this->source, $this->notify);

        $key_details = $this->model->getMerchantData($info['merchant_id'], 'EINVOICE_CREDENTIALS');
        $response['status'] = 0;
        $payload = '';
        if ($key_details != false) {
            $key_details = json_decode($key_details, 1);
            $response =  $this->einvoiceController->createToken($key_details['username'], $key_details['password'], $info['merchant_state'], $info['gst_number'], $this->payment_request_id);
        } else {
            $response['errors'] = array('Missing E-invoice credentials');
        }

        if ($info['einvoice_type'] == 'EXPWOP' || $info['einvoice_type'] == 'EXPWP') {
            $info['customer_gst_number'] = 'URP';
        }

        if ($response['status'] == 1) {
            $data['Version'] = '1.1';
            $data['TranDtls']['TaxSch'] = 'GST';
            $data['TranDtls']['SupTyp'] = ($info['einvoice_type'] != '') ? $info['einvoice_type'] : 'B2B';
            $data['TranDtls']['RegRev'] = 'N';
            $data['TranDtls']['EcmGstin'] = null;
            $data['TranDtls']['IgstOnIntra'] = 'N';

            $data['DocDtls']['Typ'] = 'INV';
            $data['DocDtls']['No'] = $info['invoice_number'];
            $date = date_create($info['bill_date']);
            $bill_date = date_format($date, "d/m/Y");
            $data['DocDtls']['Dt'] = $bill_date;

            $data['SellerDtls']['Gstin'] = $info['gst_number'];
            $data['SellerDtls']['LglNm'] = $info['company_name'];
            $data['SellerDtls']['TrdNm'] = $info['company_name'];
            $data['SellerDtls']['Addr1'] = substr($info['merchant_address'], 0, 100);
            if (strlen($info['merchant_address']) > 100) {
                $data['SellerDtls']['Addr2'] = substr($info['merchant_address'], 100, 100);
            }
            $data['SellerDtls']['Loc'] = $info['merchant_city'];
            $data['SellerDtls']['Pin'] = (int) $info['merchant_zipcode'];
            $data['SellerDtls']['Stcd'] = (string)  $merchant_state_code;
            $data['SellerDtls']['Ph'] = $info['business_contact'];
            $data['SellerDtls']['Em'] = $info['business_email'];

            $pos = ($info['customer_zip'] == '999999') ? 96 : $customer_state_code;
            if (strlen($customer_state_code) == 1) {
                $customer_state_code = '0' . $customer_state_code;
            }

            $data['BuyerDtls']['Gstin'] = $info['customer_gst_number'];
            $data['BuyerDtls']['LglNm'] = $info['customer_name'];
            $data['BuyerDtls']['TrdNm'] = $info['customer_name'];
            $data['BuyerDtls']['Pos'] = (string) $pos;
            $data['BuyerDtls']['Addr1'] = substr($info['customer_address'], 0, 100);
            if (strlen($info['customer_address']) > 100) {
                $data['BuyerDtls']['Addr2'] = substr($info['customer_address'], 100, 100);
            }
            $data['BuyerDtls']['Loc'] = $info['customer_city'];
            $data['BuyerDtls']['Pin'] = (int) $info['customer_zip'];
            $data['BuyerDtls']['Stcd'] = (string)  $pos;
            $data['BuyerDtls']['Ph'] = $info['customer_mobile'];
            $data['BuyerDtls']['Em'] = $info['customer_email'];

            $particulars = $this->model->getInvoiceParticular($this->payment_request_id);
            $travelparticulars = $this->model->getTravelInvoiceParticular($this->payment_request_id);
            $taxes = $this->model->getInvoiceTax($this->payment_request_id);
            $inter = 0;
            foreach ($taxes as $tax) {
                if ($tax->tax_type == 3) {
                    $inter = 1;
                }
            }
            $int = 1;
            $total_taxable = 0;
            $total_igst = 0;
            $total_cgst = 0;
            $total_taxable = 0;
            $invoice_total = 0;
            $total_discount = 0;
            foreach ($particulars as $row) {
                $particular['SlNo'] = (string) $int;
                $particular['PrdDesc'] = substr($row->item, 0, 100);

                $particular['Unit'] = $this->getUnitCode($row->unit_type);
                $particular['HsnCd'] = $this->getSacCode($row->sac_code, $info['billing_profile_id']);
                if (substr($particular['HsnCd'], 0, 2) == '99') {
                    $particular['IsServc'] = 'Y';
                } else {
                    $particular['IsServc'] = 'N';
                }
                $particular['Qty'] = ($row->qty > 0) ? (int) $row->qty : 1;
                $particular['UnitPrice'] = ($row->rate > 0) ? (float) $row->rate : (float) $row->total_amount;
                $particular['TotAmt'] = (float) $row->total_amount;
                $particular['Discount'] = (float) $row->discount;
                $particular['AssAmt'] = (float) $row->total_amount;
                $particular['GstRt'] = (int) $row->gst;
                $total_discount = $total_discount + $row->discount;
                if ($inter == 1) {
                    $gstAmt = round($row->total_amount *  $row->gst / 100, 2);
                    $total_igst = $total_igst + $gstAmt;
                    $particular['IgstAmt'] = (float) $gstAmt;
                    $particular['CgstAmt'] = 0;
                    $particular['SgstAmt'] = 0;
                } else {
                    $gstAmt = round($row->total_amount *  $row->gst / 100, 2);
                    $particular['IgstAmt'] = 0;
                    $particular['CgstAmt'] = (float) $gstAmt / 2;
                    $particular['SgstAmt'] = (float) $gstAmt / 2;
                    $total_cgst = $total_cgst + $gstAmt / 2;
                }
                $total_taxable = $total_taxable + $row->total_amount;
                $particular['TotItemVal'] = $gstAmt + $row->total_amount;
                $invoice_total = $invoice_total + $particular['TotItemVal'];
                $data['ItemList'][] = $particular;
                $int++;
            }

            foreach ($travelparticulars as $row) {
                $particular['SlNo'] = (string) $int;
                $desc = (strlen($row->name) > 3) ? $row->name : 'Booking';
                $particular['PrdDesc'] = substr($desc, 0, 100);
                $particular['Unit'] = $this->getUnitCode($row->unit_type);
                $particular['HsnCd'] = $this->getSacCode($row->sac_code, $info['billing_profile_id']);
                if (substr($particular['HsnCd'], 0, 2) == '99') {
                    $particular['IsServc'] = 'Y';
                } else {
                    $particular['IsServc'] = 'N';
                }
                $particular['Qty'] = ($row->units > 0) ? (int) $row->units : 1;
                $particular['UnitPrice'] = ($row->rate > 0) ? (float) $row->rate : (float) $row->total;
                $particular['TotAmt'] = (float) $row->total;
                $particular['Discount'] = (float) $row->discount;
                $particular['AssAmt'] = (float) $row->total;
                $particular['GstRt'] = (int) $row->gst;
                $total_discount = $total_discount + $row->discount;
                if ($inter == 1) {
                    $gstAmt = round($row->total *  $row->gst / 100, 2);
                    $total_igst = $total_igst + $gstAmt;
                    $particular['IgstAmt'] = (float) $gstAmt;
                    $particular['CgstAmt'] = 0;
                    $particular['SgstAmt'] = 0;
                } else {
                    $gstAmt = round($row->total *  $row->gst / 100, 2);
                    $particular['IgstAmt'] = 0;
                    $particular['CgstAmt'] = (float) $gstAmt / 2;
                    $particular['SgstAmt'] = (float) $gstAmt / 2;
                    $total_cgst = $total_cgst + $gstAmt / 2;
                }
                $total_taxable = $total_taxable + $row->total;
                $particular['TotItemVal'] = $gstAmt + $row->total;
                $invoice_total = $invoice_total + $particular['TotItemVal'];
                $data['ItemList'][] = $particular;
                $int++;
            }
            $data['ValDtls']['AssVal'] = (float) $total_taxable;
            $data['ValDtls']['CgstVal'] = (float) $total_cgst;
            $data['ValDtls']['SgstVal'] = (float) $total_cgst;
            $data['ValDtls']['IgstVal'] = (float) $total_igst;
            $data['ValDtls']['Discount'] = (float)  $total_discount;
            $data['ValDtls']['TotInvVal'] = (float) $invoice_total;
            $payload = json_encode($data);
            $response = $this->createEinvoice($response, $payload, $key_details['username'], $info['gst_number']);
        }
        if ($response['status'] == 1) {
            $this->model->updateEinvoice($request_id, $payload, 1, '', $response['Irn'], $response['SignedQRCode'], $response['AckNo'], $response['AckDt']);
            if ($this->notify == 1 && $info['customer_email'] != '') {
                $einfo = $this->model->getTableRow('einvoice_request', 'id', $request_id);
                $dataView['data'] = $data;
                $dataView['info'] = $einfo;
                if (!defined('DOMPDF_ENABLE_HTML5PARSER')) define("DOMPDF_ENABLE_HTML5PARSER", true);
                if (!defined('DOMPDF_ENABLE_FONTSUBSETTING')) define("DOMPDF_ENABLE_FONTSUBSETTING", true);
                if (!defined('DOMPDF_UNICODE_ENABLED')) define("DOMPDF_UNICODE_ENABLED", true);
                if (!defined('DOMPDF_DPI')) define("DOMPDF_DPI", 120);
                if (!defined('DOMPDF_ENABLE_REMOTE')) define("DOMPDF_ENABLE_REMOTE", true);
                $pdf = DOMPDF::loadView('app.merchant.einvoice.einvoice', $dataView);
                $pdf->setPaper("a4", "portrait");
                $content = $pdf->download()->getOriginalContent();
                $file_name = 'app/e-Invoice' . time() . '.pdf';
                Storage::put($file_name, $content);
                //return $pdf->download('invoice.pdf');

                $location = storage_path('app/' . $file_name);
                $mail = new MailWrapper();
                $data['attachment'] = $location;
                $data['attachment_name'] = 'E-invoice';
                $data['data'][''] = 'Dear ' . $info['customer_name'] . ',<br><br>Please find attached e-invoice from ' . $info['company_name'] . '<br><br><br>Regards,<br>' . $info['company_name'] . '<br>' . $info['business_contact'];
                $mail->sendmail($info['customer_email'], 'data', $data, 'E-invoice from ' . $info['company_name']);
                Storage::delete($file_name);
            }
        } else {
            $this->model->updateEinvoice($request_id, $payload, 2, $response['errors'], '', '', null, null);
        }
    }

    private function getUnitCode($code)
    {
        $array = array('BAG', 'BAL', 'BDL', 'BKL', 'BOU', 'BOX', 'BTL', 'BUN', 'CAN', 'CBM', 'CCM', 'CMS', 'CTN', 'DOZ', 'DRM', 'GGR', 'GMS', 'GRS', 'GYD', 'KGS', 'KLR', 'KME', 'MLT', 'MTR', 'MTS', 'NOS', 'PAC', 'PCS', 'PRS', 'QTL', 'ROL', 'SET', 'SQF', 'SQM', 'SQY', 'TBS', 'TGM', 'THD', 'TON', 'TUB', 'UGS', 'UNT', 'YDS', 'OTH');
        if (in_array($code, $array)) {
            return $code;
        } else {
            return 'OTH';
        }
    }
    private function getSacCode($code, $profile_id)
    {
        $exist = $this->model->getColumnValue('hsn_sac_code', 'code', $code, 'id');
        if ($exist == false) {
            $code =  $this->model->getColumnValue('merchant_billing_profile', 'id', $profile_id, 'sac_code');
            if ($code == '') {
                return false;
            }
        }
        return $code;
    }


    function createEinvoice($response, $payload, $username, $gst)
    {
        $decsek = $response['decSek'];
        $token = $response['AuthToken'];
        $data1['Data'] = $this->einvoiceController->encryptData(base64_encode($payload), base64_encode($decsek));

        $header =  array(
            "Content-Type: application/json",
            "Client-Id: " . env('EINVOICE_CLIENT_ID'),
            "Client-Secret: " . env('EINVOICE_SECRET'),
            "user-name: " . $username,
            "authtoken: " . $token,
            "gstin: " . $gst
        );

        $post_url = env('EINVOICE_BASE_URL') . 'einvoiceapi/v1.03/Invoice';
        $post_string = json_encode($data1);
        $response = Helpers::APIrequest($post_url, $post_string, "POST", true, $header);
        $array = json_decode($response, 1);
        if ($array['Status'] == 1) {
            $json = $this->einvoiceController->decryptData($array['Data'], $decsek);
            $response_array = json_decode($json, 1);
            $response_array['status'] = 1;
        } else {
            $response_array['status'] = 0;
            $errors = array();
            foreach ($array['ErrorDetails'] as $error) {
                $errors[] = $error['ErrorMessage'];
            }
            $response_array['errors'] = json_encode($errors);
        }
        return $response_array;
    }
}
