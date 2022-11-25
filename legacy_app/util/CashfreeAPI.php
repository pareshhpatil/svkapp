<?php

/**
 * Version 1.0
 */
class CashfreeAPI
{

    private $client_id = null;
    private $client_secret = null;
    private $api_url = null;

    function __construct($id, $secret)
    {
        $this->client_id = $id;
        $this->client_secret = $secret;
        $env = getenv('ENV');
        if ($env == 'PROD') {
            $this->api_url = "https://ces-api.cashfree.com/";
        } else {
            $this->api_url = "https://ces-gamma.cashfree.com/";
        }
    }

    function getToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "ces/v1/authorize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "X-Client-Id: " . $this->client_id,
                "X-Client-Secret: " . $this->client_secret
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response['status'] = 0;
            $response['message'] = "cURL Error #:" . $err;
            return $response;
        } else {
            $response = json_decode($response, 1);
            return $response;
        }
    }

    function getVendor($id)
    {
        $response = $this->getToken();
        if ($response['status'] == 'SUCCESS') {
            $token = $response['data']['token'];
        } else {
            $response['status'] = 0;
            $response['error'] = $response['message'];
            return $response;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "/ces/v1/getVendor/" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }

        return $res;
    }

    function adjustVendorBalance($vendor_id, $adjustmentId, $type, $narrative, $amount)
    {
        $response = $this->getToken();
        if ($response['status'] == 'SUCCESS') {
            $token = $response['data']['token'];
        } else {
            $response['status'] = 0;
            $response['error'] = $response['message'];
            return $response;
        }
        $data['vendorId'] = $vendor_id;
        $data['adjustmentId'] = $adjustmentId;
        $data['type'] = $type;
        $data['amount'] = $amount;
        $data['remarks'] = $narrative;
        $data = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "/ces/v1/adjustVendor",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }

        return $res;
    }

    function transferVendor($vendor_id, $amount)
    {
        $response = $this->getToken();
        if ($response['status'] == 'SUCCESS') {
            $token = $response['data']['token'];
        } else {
            $response['status'] = 0;
            $response['error'] = $response['message'];
            return $response;
        }
        $data['vendorId'] = $vendor_id;
        $data['amount'] = $amount;
        $data = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "/ces/v1/requestVendorPayout",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }

        return $res;
    }

    function withdrawAmount($amount, $remark)
    {
        $response = $this->getToken();
        if ($response['status'] == 'SUCCESS') {
            $token = $response['data']['token'];
        } else {
            $response['status'] = 0;
            $response['error'] = $response['message'];
            return $response;
        }
        $data['amount'] = $amount;
        $data['remarks'] = $remark;
        $data = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "/ces/v1/requestWithdrawal",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }

        return $res;
    }

    function transferStatus($id)
    {
        $response = $this->getToken();
        if ($response['status'] == 'SUCCESS') {
            $token = $response['data']['token'];
        } else {
            $response['status'] = 0;
            $response['error'] = $response['message'];
            return $response;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "/ces/v1/getVendorTransfer/" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }

        return $res;
    }


    function refundStatus($appID,$key,$id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cashfree.com/api/v1/refundStatus/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'appId' => $appID,
                'secretKey' => $key,
                'refundId' => $id
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }

        return $res;
    }

    function getLedgerBalance()
    {
        $response = $this->getToken();
        if ($response['status'] == 'SUCCESS') {
            $token = $response['data']['token'];
        } else {
            $response['status'] = 0;
            $response['error'] = $response['message'];
            return $response;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "/ces/v1/getBalance",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }

        return $res;
    }

    function getLedgerStatement($maxReturn, $last_return_id = null)
    {
        $response = $this->getToken();
        if ($response['status'] == 'SUCCESS') {
            $token = $response['data']['token'];
        } else {
            $response['status'] = 0;
            $response['error'] = $response['message'];
            return $response;
        }
        $curl = curl_init();
        $last_return = '';
        if ($last_return_id != null) {
            $last_return = '&lastReturnId=' . $last_return_id;
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "ces/v1/getLedger?maxReturn=" . $maxReturn . $last_return,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }

        return $res;
    }

    function saveVendor($data)
    {
        $response = $this->getToken();
        if ($response['status'] == 'SUCCESS') {
            $token = $response['data']['token'];
        } else {
            $response['status'] = 0;
            $response['error'] = $response['message'];
            return $response;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "ces/v1/addVendor",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }
        return $res;
    }
}
