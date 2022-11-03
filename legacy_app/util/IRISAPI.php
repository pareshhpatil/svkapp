<?php

/**
 * Version 1.0
 */
class IRISAPI {

    private $user_name = null;
    private $password = null;
    private $api_url = null;
    private $token = null;
    private $company_id = null;
    private $tenant = null;
    private $api_key = null;

    function __construct($data) {
        $this->api_url = $data[0]['config_value'];
        $this->tenant = $data[1]['config_value'];
        $this->user_name = $data[2]['config_value'];
        $this->password = $data[3]['config_value'];
        $this->api_key = $data[4]['config_value'];
    }

    function getToken() {
        $json = '{"email":"' . $this->user_name . '", "password":"' . $this->password . '"}';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.irisgst.com/irisgst/mgmt/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "product:SAPPHIRE",
                "tenant: " . $this->tenant
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $response = json_decode($response, 1);
        if ($err) {
            $response['status'] = 0;
            $response['message'] = "cURL Error #:" . $err;
            return $response;
        } else {
            if ($response['status'] == 'SUCCESS') {
                $this->company_id = $response['response']['companyid'];
                $this->token = $response['response']['token'];
                return $response;
            } else {
                $response['status'] = 0;
                return $response;
            }
        }
    }

    function saveInvoice($gst_number, $json, $type = 'GSTR1') {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        if ($type == 'GSTR1') {
            $url = "gstr/addInvoices/regularInvoices?ct=INVOICE&gstin=" . $gst_number;
        } elseif ($type == 'GSTR2') {
            $url = "gstr/addInvoices/GSTR2RiandCdnInvoices?gstin=" . $gst_number;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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

    function getInvoiceList($json) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        while ($run == 1) {
            $run = 0;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->api_url . "invoice/invoices?page=" . $page . "&size=100",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => array(
                    "companyid:" . $this->company_id,
                    "X-Auth-Token:" . $this->token,
                    "product:SAPPHIRE",
                    "content-type:application/json"
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
                $rows = json_decode($response, true);
                if (count($rows['response']) == 100) {
                    $run = 1;
                } else {
                    $run = 0;
                }
                $res['response'][] = $rows['response'];
            }
            $page++;
        }
        
        return $res;
    }

    function getInvoiceDetail($invoice_id, $gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "invoice/invoice?invoiceId=" . $invoice_id . "&ft=GSTR1&gstin=" . $gstin,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
        }
        $page++;

        return $res;
    }

    function getGSTData($gstin, $fp, $action = 'RETSUM', $type = 'GSTR1') {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstn/data?gstin=" . $gstin . "&fillingPeriod=" . $fp . "&returnType=" . $type . "&action=" . $action . "&refreshData=true",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        
        return $res;
    }

    function get3BInvoice($period, $gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        $json = '{"cname":null,"ct":null,"ctin":null,"fp":"' . $period . '","fromDate":null,"ft":"GSTR3B","gstin":"' . $gstin . '","inum":null,"onum":null,"status":null,"toDate":null,"hasErrors":null,"hasWarnings":null,"val":null,"dty":null,"gstinStatus":null,"branchCode":null,"invTyp":null,"dst":null}';
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "invoice/invoices?page=0&size=100",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function createGSTR($period, $gstin, $type) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        if ($type == 'GSTR1') {
            $url = 'gstr1';
        } else {
            $url = 'gstr';
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . $url . "/create?gstin=" . $gstin . "&fp=" . $period . "&formType=" . $type,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function createDocument($json, $gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "/gstr/addInvoices/documentDetails?ct=INVOICE&gstin=" . $gstin,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function deleteGSTR($period, $gstin, $type = 'GSTR1') {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        if ($type == 'GSTR1') {
            $url = 'aggregate/gstr1';
            $method = 'GET';
        } else {
            $url = 'gstr';
            $method = 'DELETE';
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . $url . "/discardDraft?gstin=" . $gstin . "&fp=" . $period . "&formType=" . $type,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function get3bdraftSummary($fp, $gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "invoice/downloadReturnSummary?fp=" . $fp . "&gstin=" . $gstin . "&returnType=GSTR3B",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function validateConnection($gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "company/validateGstnSession?gstin=" . $gstin,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function generateOTP($company_id) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "company/generate/otp?companyid=" . $company_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function submitOTP($company_id, $otp) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "company/authenticate/otp?companyid=" . $company_id . "&otp=" . $otp,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function submitGSTChecklist($fp, $gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstr/submit/checklist?fp=" . $fp . "&gstin=" . $gstin . "&ft=GSTR1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;
        return $res;
    }

    function submitGSTErrors($json) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstn/submitStatusErrorReportDownload",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;
        return $res;
    }

    function submitGST($gstn, $fp, $type = 'SAVE', $frmtype = 'GSTR1') {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstn/communication?gstin=" . $gstn . "&filingPeriod=" . $fp . "&formType=" . $frmtype . "&ipAddress=27.168.1.1&apiAction=" . $type . "&discardDraft=false",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;
        return $res;
    }

    function getGSTRSummary($period, $gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstr/returnSummary?gstin=" . $gstin . "&fp=" . $period . "&ft=GSTR1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function submitGSTR3b($file_path, $period, $gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['response']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($file_path);
        } else { //
            $cFile = '@' . realpath($file_path);
        }
        $post = array('file' => $cFile);
        $curl = curl_init();

        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstr/upload?gstin=" . $gstin . "&fp=" . $period . "&ft=GSTR3B",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                "X-Auth-Token: " . $this->token,
                "companyid: " . $this->company_id,
                "product:SAPPHIRE",
                "content-type: multipart/form-data",
                "mimeType: multipart/form-data",
                "fileInputFormat: AGGREGATED"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function getGSTSubmitSTatus($gstin, $period) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstn/submitStatus?filingPeriod=" . $period . "&gstin=" . $gstin . "&returnType=GSTR1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function getGSTSaveSTatus($period, $gstin, $type = 'GSTR1') {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstn/saveStatusDetails?gstin=" . $gstin . "&fp=" . $period . "&returnType=" . $type,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function deleteInvoice($json) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "invoice/deleteInvoice",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }

    function getGSTRStatus($period, $gstin) {
        if ($this->token == NULL) {
            $response = $this->getToken();
            if ($response['status'] == 'SUCCESS') {
                $token = $response['data']['token'];
            } else {
                $response['status'] = 0;
                $response['error'] = $response['message'];
                return $response;
            }
        }
        $page = 0;
        $run = 1;
        $res = array();
        $run = 0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gstr/status?gstin=" . $gstin . "&filingperiod=" . $period . "&returntype=GSTR1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "companyid:" . $this->company_id,
                "X-Auth-Token:" . $this->token,
                "product:SAPPHIRE",
                "content-type:application/json"
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
            $rows = json_decode($response, true);
            $res['response'] = $rows['response'];
            $res['message'] = $rows['message'];
        }
        $page++;

        return $res;
    }
    
    function getGSTReturnStatus($gstin) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://taxpayer.irisgst.com/api/returnstatus?gstin=" . $gstin,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "apikey:" . $this->api_key
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
            $rows = json_decode($response, true);
            $res['response'] = $rows;
        }

        return $res;
    }
    
    
    function getGSTInfo($gstin) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://taxpayer.irisgst.com/api/search?gstin=" . $gstin,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "apikey:" . $this->api_key
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
            $rows = json_decode($response, true);
            $res['response'] = $rows;
        }

        return $res;
    }

}
