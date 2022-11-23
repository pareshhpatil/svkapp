<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Model\Invoice;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use DOMPDF;
use App\Jobs\EInvoiceCreation;

class EinvoiceController extends Controller
{
    private $einvoice_model = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->einvoice_model = new Invoice();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }
    public function  view($link, $type = null)
    {
        $id = Encrypt::decode($link);
        $info = $this->einvoice_model->getTableRow('einvoice_request', 'id', $id);
        $dataView['data'] = json_decode($info->request_json, 1);
        $dataView['info'] = $info;
        if ($type == 'pdf') {
            define("DOMPDF_ENABLE_HTML5PARSER", true);
            define("DOMPDF_ENABLE_FONTSUBSETTING", true);
            define("DOMPDF_UNICODE_ENABLED", true);
            define("DOMPDF_DPI", 120);
            define("DOMPDF_ENABLE_REMOTE", true);
            $pdf = DOMPDF::loadView('app.merchant.einvoice.einvoice', $dataView);
            $pdf->setPaper("a4", "portrait");
            return $pdf->download('e-Invoice.pdf');
        }
        // return $content;
        //Storage::put('app/name' . time() . '.pdf', $content);

        return view('app.merchant.einvoice.einvoice', $dataView);
    }
    public function  einvoice()
    {
        $data = Helpers::setBladeProperties('E Invoice List', [], [175, 5]);
        $detail = array();

        $dates = Helpers::setListDates();
        $detail = $this->einvoice_model->GetEinvoiceList($this->merchant_id, $dates['from_date'], $dates['to_date']);
        $data['datatablejs'] = 'table-no-export';
        foreach ($detail as $key => $row) {
            $detail[$key]->encrypted_id = Encrypt::encode($row->id);
            if ($row->status == 1) {
                $expired_time = date("Y-m-d H:i:s", strtotime('+24 hours', strtotime($row->ack_date)));
                if (date("Y-m-d H:i:s") > $expired_time) {
                    $detail[$key]->cancel_active = 0;
                } else {
                    $detail[$key]->cancel_active = 1;
                }
            }
        }
        $data['list'] = $detail;
        return view('app/merchant/einvoice/index', $data);
    }

    public function recreateeInvoice($link)
    {
        $id = Encrypt::decode($link);
        $info = $this->einvoice_model->getTableRow('einvoice_request', 'id', $id);
        if ($info != false) {
            $this->einvoice_model->updateTable('einvoice_request', 'id', $id, 'is_active', 0);
            EInvoiceCreation::dispatch($info->payment_request_id, $info->source, $info->notify)->onQueue(env('SQS_EINVOICE_QUEUE'));
        }
        return redirect('/merchant/einvoice/list');
    }

    public function errorseInvoice($link)
    {
        $id = Encrypt::decode($link);
        $info = $this->einvoice_model->getTableRow('einvoice_request', 'id', $id);
        if ($info == false) {
            return redirect('/merchant/einvoice/list');
        }
        $data['errors'] = json_decode($info->error, 1);
        return view('app/merchant/einvoice/errors', $data);
    }

    public function deleteeInvoice($link)
    {
        $id = Encrypt::decode($link);
        $info = $this->einvoice_model->getTableRow('einvoice_request', 'id', $id);
        if ($info != false) {
            $this->einvoice_model->updateTable('einvoice_request', 'id', $id, 'is_active', 0);
            Session::flash('success', 'e-Invoice record deleted successfully');
        }
        return redirect('/merchant/einvoice/list');
    }

    public function canceleInvoice($link)
    {
        $id = Encrypt::decode($link);
        $info = $this->einvoice_model->getTableRow('einvoice_request', 'id', $id);
        if ($info != false) {
            $key_details = $this->einvoice_model->getMerchantData($info->merchant_id, 'EINVOICE_CREDENTIALS');
            $response['status'] = 0;
            $payload = '';
            if ($key_details != false) {
                $key_details = json_decode($key_details, 1);
                $merchant_state = $this->einvoice_model->getConfigValue('gst_state_code', substr($info->merchant_gst, 0, 2));
                $response =  $this->createToken($key_details['username'], $key_details['password'], $merchant_state, $info->merchant_gst, $info->payment_request_id);
                $data['Irn'] = $info->irn;
                $data['CnlRsn'] = '1';
                $data['CnlRem'] = 'Wrong entry';
                $payload = json_encode($data);
                if ($response['status'] == 1) {
                    $response = $this->cancelEinvoiceApi($response, $payload, $key_details['username'], $info->merchant_gst);
                    if ($response['status'] == '1') {
                        $this->einvoice_model->updateTable('einvoice_request', 'id', $id, 'status', 3);
                        Session::flash('success', 'e-Invoice cancelled successfully');
                        return redirect('/merchant/einvoice/list');
                    }
                }
            } else {
                $response['errors'] = 'Missing E-invoice credentials';
            }
            return redirect('/merchant/einvoice/list')->with('error', $response['errors']);
        }
        return redirect('/merchant/einvoice/list');
    }


    function cancelEinvoiceApi($response, $payload, $username, $gst)
    {
        $decsek = $response['decSek'];
        $token = $response['AuthToken'];
        $data1['Data'] = $this->encryptData(base64_encode($payload), base64_encode($decsek));

        $header =  array(
            "Content-Type: application/json",
            "Client-Id: " . env('EINVOICE_CLIENT_ID'),
            "Client-Secret: " . env('EINVOICE_SECRET'),
            "user-name: " . $username,
            "authtoken: " . $token,
            "gstin: " . $gst
        );

        $post_url = env('EINVOICE_BASE_URL') . 'einvoiceapi/v1.03/Invoice/Cancel';
        $post_string = json_encode($data1);
        $response = Helpers::APIrequest($post_url, $post_string, "POST", true, $header);
        $array = json_decode($response, 1);
        if ($array['Status'] == 1) {
            $json = $this->decryptData($array['Data'], $decsek);
            $response_array = json_decode($json, 1);
            $response_array['status'] = 1;
        } else {
            $response_array['status'] = 0;
            $response_array['errors'] = (isset($array['ErrorDetails'][0]['ErrorMessage'])) ? $array['ErrorDetails'][0]['ErrorMessage'] : 'Error while cancel e-Invoice';
        }
        return $response_array;
    }

    public function createToken($username, $password, $state, $gst, $payment_request_id)
    {
        $appkey = $this->generateRandomString() . $payment_request_id;
        $user_details =  array("UserName" => $username, "Password" => $password, "AppKey" => base64_encode($appkey), "ForceRefreshAccessToken" => true);
        $data1['Data'] = $this->encryptAuthData(json_encode($user_details));

        $header =  array(
            "Content-Type: application/json",
            "Client-Id: " . env('EINVOICE_CLIENT_ID'),
            "Client-Secret: " . env('EINVOICE_SECRET'),
            "State-Cd: " . $state,
            "Gstin: " . $gst
        );

        $post_url = env('EINVOICE_BASE_URL') . 'einvoiceapi/v1.04/auth';

        $post_string = json_encode($data1);
        $response = Helpers::APIrequest($post_url, $post_string, "POST", true, $header);

        $response = json_decode($response, 1);
        if ($response['Status'] == '1') {
            $array['status'] = 1;
            $array['AuthToken'] = $response['Data']['AuthToken'];
            $array['Sek'] = $response['Data']['Sek'];
            $array['decSek'] = $this->decryptData($array['Sek'], $appkey);;
        } else {
            $array['status'] = 0;
            $errors = array();
            foreach ($response['ErrorDetails'] as $error) {
                $errors[] = $error['ErrorMessage'];
            }
            $array['errors'] = json_encode($errors);
        }
        return  $array;
    }

    function encryptAuthData($data)
    {
        $file_path = env('EINVOICE_PUBLIC_KEY_PATH');
        openssl_public_encrypt(base64_encode($data), $encrypted, file_get_contents($file_path));
        return base64_encode($encrypted);
    }

    function encryptData($data,  $sek)
    {
        // return base64_encode(openssl_encrypt(base64_encode($data),"AES-256-ECB",$sek, OPENSSL_RAW_DATA));
        return base64_encode(openssl_encrypt(base64_decode($data), "AES-256-ECB", base64_decode($sek), OPENSSL_RAW_DATA));
        //return base64_encode(openssl_encrypt($data,"AES-256-ECB",$sek, OPENSSL_RAW_DATA));
    }
    function decryptData($data, $key)
    {
        //return openssl_decrypt(base64_decode($data),"AES-256-ECB",$key, OPENSSL_RAW_DATA);
        return openssl_decrypt(base64_decode($data), "AES-256-ECB", $key, OPENSSL_RAW_DATA);
        //return openssl_decrypt($data,"AES-256-ECB",$key, OPENSSL_RAW_DATA);
    }

    function generateRandomString()
    {
        $length = 22;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
