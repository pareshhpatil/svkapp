<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token');

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Api
{

    protected $encrypt = NULL;
    protected $user_id = NULL;
    protected $version = NULL;
    protected $versionprint = NULL;
    protected $controller = NULL;
    protected $method = NULL;
    public $merchant_id = NULL;
    protected $jsonArray = NULL;
    protected $ApiErrors = NULL;
    protected $type = NULL;
    protected $common = NULL;
    public $webrequest = true;
    public $response = '';

    function __construct($type_ = null)
    {
        SwipezLogger::$path = LIB . "config/log4php_api_config.xml";
        $this->encrypt = new Encryption();
        $this->view = new View();
        require_once UTIL . 'Api/ApiErrors.php';
        $this->ApiErrors = new ApiErrors();
        $this->type = $type_;
        if ($type_ != 'Template' && $type_ != 'Login') {
            $this->validatAPIkey();
        }
        require_once(MODEL . 'CommonModel.php');
        $this->common = new CommonModel();
    }

    /**
     * Validate api merchant security key
     */
    function getIP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function validatAPIkey()
    {
        if (isset($_POST['data'])) {
        } else {
            $data = file_get_contents('php://input');
            if (!isset($data)) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01006');
            }
            $_POST['data'] = $data;
        }
        # Validate Json format
        $jsonarray = json_decode($_POST['data'], 1);
        if (!$this->validate_json($_POST['data'])) {
            SwipezLogger::debug(__CLASS__, json_encode($jsonarray));
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01001');
        }
        # convert json to array
        #SwipezLogger::debug(__CLASS__,'['.$this->versionprint.']'.'['.$this->controller.']'.'['.$this->method.']'. json_encode($jsonarray));
        $access_key_id = $jsonarray['access_key_id'];
        $secret_access_key = $jsonarray['secret_access_key'];
        try {
            if (getenv('ENV') == 'PROD' && BATCH_CONFIG == false) {
                if ($this->webrequest == true) {
                    $Validkeys = config('accesskey');
                    if (!in_array($access_key_id, $Validkeys)) {
                        SwipezLogger::debug(__CLASS__, 'IP[' . $this->getIP() . ']' . json_encode($jsonarray));
                        echo $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01003');
                        die();
                    }
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
        }
        $this->jsonArray = $jsonarray;
        require_once MODEL . 'api/ApiModel.php';
        $apiModel = new ApiModel();
        $result = $apiModel->validatAPIkey($access_key_id, $secret_access_key);
        if (!empty($result)) {
            $this->user_id = $result['user_id'];
            $this->merchant_id = $result['merchant_id'];
            SwipezLogger::debug(__CLASS__, 'IP[' . $this->getIP() . ']' . '[' . $this->merchant_id . ']' . json_encode($jsonarray));
        } else {
            SwipezLogger::debug(__CLASS__, 'IP[' . $this->getIP() . ']' . json_encode($jsonarray));
            echo $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01003');
            die();
        }
    }

    function printJsonResponse($req_time, $error_code = '', $srvrsp = '', $errorlist = '', $total_rows = 0)
    {
        $response['reqtime'] = $req_time;
        $response['resptime'] = date("Y-m-d H:i:s");
        if ($total_rows == 1) {
            $response['total_records'] = count($srvrsp);
        }
        $response['srvrsp'] = $srvrsp;
        $response['errcode'] = $error_code;
        $errorMessage = ($error_code != '') ? $this->ApiErrors->fetch($error_code) : '';
        $response['errmsg'] = $errorMessage;
        $response['errlist'] = $errorlist;
        if ($error_code == '') {
            //SwipezLogger::info(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . json_encode($response));
        } else {
            //$errormerge[] = array($error_code, $errorMessage, $errorlist);
            // Commented this line after seeing this error in live logs
            // Error handling and error message printing should be handled by the calling method
            // SwipezLogger::warn(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . json_encode($errormerge));
        }
        if ($this->type == 'Merchant' || $this->type == 'Login') {
            $response = json_encode($response);
            if ($this->webrequest == true) {
                return $response;
            } else {
                $this->response = $response;
                return;
            }
        } else {
            $error[] = array($error_code, $errorMessage);
            $this->view->error = $error;
            $this->view->errorlist = $errorlist;
            $this->view->render('header/guest');
            $this->view->render('error/api');
            $this->view->render('footer/footer');
        }
    }

    function validate_json($str = NULL)
    {
        if (is_string($str)) {
            @json_decode($str);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

    function getTemplateJson($version, $info, $rows, $type, $value = '', $inv_type = '')
    {
        try {
            $template_type = $info['template_type'];
            $column = array();
            $bdscolumn = array();
            $int = 0;
            $p_int = 0;
            $b_int = 0;
            $mint = 0;
            $t_int = 0;
            $d_int = 0;
            $count = 0;
            $particular_column = json_decode($info['particular_column'], 1);
            $default_particular = json_decode($info['default_particular'], 1);
            $default_tax = json_decode($info['default_tax'], 1);
            $plugin = json_decode($info['plugin'], 1);
            $function_column = array();
            foreach ($rows as $row) {
                if ($row['save_table_name'] == 'metadata' && $row['column_type'] != 'PT' && $row['column_type'] != 'TT') {
                    if ($row['column_type'] == 'M' && $row['default_column_value'] == 'Custom') {
                        $mainheader[$mint]['id'] = $row['column_id'];
                        $mainheader[$mint]['name'] = $row['column_name'];
                        $mainheader[$mint]['type'] = $row['column_datatype'];
                        $mainheader[$mint]['value'] = '';
                        $mint++;
                    }

                    if ($row['column_type'] == 'H') {
                        $exit = 0;
                        if ($row['function_id'] == 9) {
                            $mapping_details = $this->common->getfunctionMappingDetails($row['column_id'], $row['function_id']);
                            if ($mapping_details['param'] == 'system_generated') {
                                $exit = 1;
                            }
                        }
                        if ($exit == 0) {
                            $column[$int]['id'] = $row['column_id'];
                            $column[$int]['name'] = $row['column_name'];
                            $column[$int]['type'] = $row['column_datatype'];
                            $column[$int]['value'] = '';
                            $int++;
                        }
                    }

                    if ($row['column_type'] == 'BDS') {
                        $bdscolumn[$b_int]['id'] = $row['column_id'];
                        $bdscolumn[$b_int]['name'] = $row['column_name'];
                        $bdscolumn[$b_int]['type'] = $row['column_datatype'];
                        $bdscolumn[$b_int]['value'] = '';
                        $b_int++;
                    }
                }
                $count++;
            }

            if ($template_type != 'travel') {
                foreach ($default_particular as $pcname) {
                    foreach ($particular_column as $col => $name) {
                        if ($col == 'item') {
                            $particular[$p_int][$col] = $pcname;
                        } else {
                            $particular[$p_int][$col] = '';
                        }
                    }
                    $p_int++;
                }

                if (empty($default_particular)) {
                    foreach ($particular_column as $col => $name) {
                        $particular[$p_int][$col] = '';
                    }
                }
            }

            foreach ($default_tax as $tax_id) {
                $tax_detail = $this->common->getSingleValue('merchant_tax', 'tax_id', $tax_id);
                $tax[$t_int]['name'] = $tax_detail['tax_name'];
                $tax[$t_int]['percentage'] = $tax_detail['percentage'];
                $tax[$t_int]['applicable_on'] = '';
                $t_int++;
            }

            if (empty($default_tax)) {
                $tax[$t_int]['name'] = '';
                $tax[$t_int]['percentage'] = '';
                $tax[$t_int]['applicable_on'] = '';
            }


            if ($plugin['has_deductible'] == 1) {
                foreach ($plugin['deductible'] as $deductible) {
                    $deduct[$d_int]['name'] = $deductible['tax_name'];
                    $deduct[$d_int]['percentage'] = $deductible['percent'];
                    $deduct[$d_int]['applicable_on'] = '';
                    $d_int++;
                }
                if (empty($plugin['deductible'])) {
                    $deduct[$d_int]['name'] = '';
                    $deduct[$d_int]['percentage'] = '';
                    $deduct[$d_int]['applicable_on'] = '';
                }
            }

            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray[$type] = $value;
            if ($inv_type == 'subscription') {
                $jsonArray['mode'] = '';
                $jsonArray['repeat_every'] = '';
                $jsonArray['start_date'] = '';
                $jsonArray['due_date'] = '';
                $jsonArray['end_mode'] = '';
                $jsonArray['occurences'] = '';
                $jsonArray['end_date'] = '';
                $jsonArray['carry_forword_dues'] = '';
            }
            if ($version == 'v3' && $plugin['has_franchise'] == 1) {
                $invoice['franchise_id'] = '';
            }
            if ($version == 'v3' && $plugin['has_vendor'] == 1) {
                $invoice['vendor_id'] = '';
            }

            $invoice['customer_code'] = '';

            $invoice['bill_cycle_name'] = '';
            $invoice['bill_date'] = 'YYYY-MM-DD eg. '.date('Y-m-d');
            $invoice['due_date'] = 'YYYY-MM-DD eg. '.date('Y-m-d');

            if ($version == 'v3' && $plugin['has_e_invoice'] == 1) {
                $invoice['enable_einvoice'] = '1';
                $invoice['send_einvoice_to_customer'] = '1';
            }
            if ($version == 'v3' && $plugin['is_prepaid'] == 1) {
                $invoice['advance_received'] = '';
            }
            if ($version == 'v3' && $plugin['has_partial'] == 1) {
                $invoice['enable_partial_payment'] = '1';
                $invoice['partial_min_amount'] = $plugin['partial_min_amount'];
            }
            if ($version == 'v3' && $plugin['has_online_payments'] == 1) {
                //$invoice['has_online_payments'] = '1';
                $invoice['enable_payments'] = $plugin['enable_payments'];
            }
            if ($plugin['has_webhook'] == 1) {
                $invoice['webhook_id'] = '';
            }
            if ($version != 'v1') {
                $invoice['invoice_properties']['notify_patron'] = '1';
            }

            if (!empty($mainheader)) {
                $invoice['main_header_fields'] = $mainheader;
            }
            $invoice['custom_header_fields'] = $column;
            if (!empty($bdscolumn)) {
                $invoice['booking_details_column'] = $bdscolumn;
            }

            if ($template_type != 'simple') {
                if ($template_type == 'travel') {
                    $t_particular = json_decode($info['properties'], 1);
                    foreach ($t_particular as $tk => $tp) {
                        if (isset($tp['column'])) {
                            $array = array();
                            foreach ($tp['column'] as $tpcc => $tpc) {
                                $array[$tpcc] = '';
                            }
                            $invoice[$tk][] = $array;
                        }
                    }
                } else {
                    $invoice['particular_rows'] = $particular;
                }
                $invoice['tax_rows'] = $tax;
                $invoice['deduct_rows'] = $deduct;
            }

            if ($plugin['has_coupon'] == 1 && $version != 'v1') {
                $invoice['coupon']['associate_coupon']['id'] = "";
                $invoice['coupon']['create_coupon']['coupon_code'] = "";
                $invoice['coupon']['create_coupon']['description'] = "";
                $invoice['coupon']['create_coupon']['type'] = "";
                $invoice['coupon']['create_coupon']['value'] = "";
                $invoice['coupon']['create_coupon']['start_date'] = "";
                $invoice['coupon']['create_coupon']['end_date'] = "";
            }
            if ($version == 'v3') {
                $customer = $this->getcustomerSaveJson($version, $this->merchant_id, 'Insert');
                if ($plugin['has_cc']) {
                    $invoice['cc_email'] = $plugin['cc_email'];
                }
                $invoice['new_customer'] = $customer;
            }
            if ($type == 'invoice_id' || $inv_type == 'subscription') {
                $jsonArray['invoice'] = $invoice;
            } else {
                $jsonArray['invoice'][0] = $invoice;
            }

            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[E0035]Error while getting json for template id [' . $template_id . '] ' . $e->getMessage());
        }
    }

    function getsettleJson($version = 1)
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['invoice_id'] = '';
            $jsonArray['paid_date'] = '';
            $jsonArray['amount'] = '';
            $jsonArray['mode'] = '';
            $jsonArray['bank_name'] = '';
            $jsonArray['bank_ref_no'] = '';
            $jsonArray['cheque_no'] = '';
            $jsonArray['cash_paid_to'] = '';
            if ($version > 1) {
                $jsonArray['notify'] = '';
            }
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getcustomerSaveJson($version, $merchant_id, $type = NULL)
    {
        try {
            require_once MODEL . 'merchant/CustomerModel.php';
            $custmodel = new CustomerModel();
            $customer_column = $custmodel->getCustomerBreakup($merchant_id);
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            if ($merchant_setting['customer_auto_generate'] == 0) {
                $customer['customer_code'] = '';
            }
            $customer['customer_name'] = '';
            $customer['email'] = '';
            $customer['mobile'] = '';
            $customer['address'] = '';
            $customer['state'] = '';
            $customer['city'] = '';
            $customer['zipcode'] = '';
            $customer['country'] = '';
            foreach ($customer_column as $col) {
                $customer['custom_fields'][] = array('id' => $col['column_id'], 'name' => $col['column_name'], 'type' => $col['column_datatype'], 'value' => '');
            }
            if ($type == NULL) {
                $jsonArray['access_key_id'] = '';
                $jsonArray['secret_access_key'] = '';
                $jsonArray['customer'][0] = $customer;
            } else {
                $jsonArray = $customer;
            }
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getcustomerUpdateJson($version, $merchant_id, $type = NULL)
    {
        try {
            require_once MODEL . 'merchant/CustomerModel.php';
            $custmodel = new CustomerModel();
            $customer_column = $custmodel->getCustomerBreakup($merchant_id);
            $customer['customer_id'] = '';
            $customer['customer_code'] = '';
            $customer['customer_name'] = '';
            $customer['email'] = '';
            $customer['mobile'] = '';
            $customer['address'] = '';
            $customer['state'] = '';
            $customer['city'] = '';
            $customer['zipcode'] = '';
            $customer['country'] = '';
            foreach ($customer_column as $col) {
                $customer['custom_fields'][] = array('id' => $col['column_id'], 'name' => $col['column_name'], 'type' => $col['column_datatype'], 'value' => '');
            }
            if ($type == NULL) {
                $jsonArray['access_key_id'] = '';
                $jsonArray['secret_access_key'] = '';
                $jsonArray['customer'][0] = $customer;
            } else {
                $jsonArray = $customer;
            }
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getdeleteJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['invoice_id'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035-1]Error while getting json' . $e->getMessage());
        }
    }

    function getagentSaveJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['email'] = '';
            $jsonArray['first_name'] = '';
            $jsonArray['last_name'] = '';
            $jsonArray['mobile'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035-1]Error while getting json' . $e->getMessage());
        }
    }

    function getPaymentReceivedJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['from_date'] = '';
            $jsonArray['to_date'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getInvoiceListJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['from_date'] = '';
            $jsonArray['to_date'] = '';
            $jsonArray['filter_by'] = '';
            $jsonArray['invoice_status'] = '';
            $jsonArray['customer_code'] = '';
            $jsonArray['group'] = '';
            $jsonArray['franchise_id'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getInvoiceDetailJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['invoice_id'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getSettlementListJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['from_date'] = '';
            $jsonArray['to_date'] = '';
            $jsonArray['franchise_id'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getSearchCustomerJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['keyword'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getListCustomerJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['searchby'] = '';
            $jsonArray['keyword'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getCustomerDetailJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['customer_code'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getValidateLoginJson()
    {
        try {
            $jsonArray['user_id'] = '';
            $jsonArray['merchant_id'] = '';
            $jsonArray['cookie'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getValidateLoginCheckJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['username'] = '';
            $jsonArray['password'] = '';
            $jsonArray['login_type'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getSMSJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['data'][0]['mobile'] = '';
            $jsonArray['data'][0]['sms'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getRefundJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['transaction_id'] = '';
            $jsonArray['amount'] = '';
            $jsonArray['reason'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getDeleteUserJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['user_id'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function getPaymentStatusJson()
    {
        try {
            $jsonArray['access_key_id'] = '';
            $jsonArray['secret_access_key'] = '';
            $jsonArray['transaction_type'] = '';
            $jsonArray['id'] = '';
            return $jsonArray;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function compairJsonArray($array1, $array2)
    {
        try {
            $result = array_diff_key($array1, $array2);

            if (!empty($result)) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
            }

            $result = array_diff_key($array2, $array1);

            if (!empty($result)) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
            }

            if (isset($array1['customer'][0])) {
                foreach ($array2['customer'] as $invoice) {
                    $result = array_diff_key($array1['customer'][0], $array2['customer'][0]);
                    $result2 = array_diff_key($array2['customer'][0], $array1['customer'][0]);

                    if (!empty($result) || !empty($result2)) {
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
                    }
                }
            } else {
                if (isset($array1['customer'])) {
                    $result = array_diff_key($array1['customer'], $array2['customer']);
                    $result2 = array_diff_key($array2['customer'], $array1['customer']);

                    if (!empty($result) || !empty($result2)) {
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
                    }
                }
            }

            if (isset($array1['invoice'][0])) {
                foreach ($array2['invoice'] as $invoice) {
                    $result = array_diff_key($array1['invoice'][0], $invoice);
                    $result2 = array_diff_key($invoice, $array1['invoice'][0]);
                    if (isset($result2['invoice_narrative']) && count($result2) == 1) {
                        $result2 = array();
                    }

                    if (!empty($result) || !empty($result2)) {
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
                    }
                    if (isset($array1['invoice'][0]['new_customer'])) {
                        $result = array_diff_key($array1['invoice'][0]['new_customer'], $invoice['new_customer']);
                        $result2 = array_diff_key($invoice['new_customer'], $array1['invoice'][0]['new_customer']);
                        if (!empty($result) || !empty($result2)) {
                            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
                        }
                    }
                }
            } else {
                if (isset($array1['invoice'])) {
                    $result = array_diff_key($array1['invoice'], $array2['invoice']);
                    $result2 = array_diff_key($array2['invoice'], $array1['invoice']);
                    unset($result['new_customer']);
                    unset($result2['new_customer']);
                    if (!empty($result) || !empty($result2)) {
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
                    }

                    if (isset($array1['invoice']['new_customer'])) {
                        if (isset($array2['invoice']['new_customer'])) {
                            $result = array_diff_key($array1['invoice']['new_customer'], $array2['invoice']['new_customer']);
                            $result2 = array_diff_key($invoice['invoice']['new_customer'], $array1['invoice']['new_customer']);
                            if (!empty($result) || !empty($result2)) {
                                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01011');
        }
    }

    function customerWrapper($post)
    {
        try {
            require_once MODEL . 'merchant/CustomerModel.php';
            $Customermodel = new CustomerModel();

            if ($post['user_code'] != '') {
                $customer_id = $Customermodel->isExistCustomerCode($this->merchant_id, $post['user_code']);
                $customer_code = $post['user_code'];
                $auto_generate = 0;
            } else {
                $auto_generate = 1;
            }

            if ($customer_id == FALSE && $_POST['email_id'] != '') {
                $data = $Customermodel->getcustomerId($this->merchant_id, $_POST['first_name'], $_POST['email_id']);
                if ($data != FALSE) {
                    $customer_id = $data['customer_id'];
                    $customer_code = $data['customer_code'];
                } else {
                    $customer_id = FALSE;
                }
            }

            if ($customer_id == FALSE) {
                if ($auto_generate == 1) {
                    $customer_code = $Customermodel->getCustomerCode($this->merchant_id);
                    $_POST['customer_code'] = $customer_code;
                } else {
                    $customer_code = $post['user_code'];
                }
                if ($customer_code != '') {
                    $result = $Customermodel->saveCustomer($this->user_id, $this->merchant_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email_id'], $_POST['mobile'], $_POST['address'], '', '', '', 0, '', '');
                }
                $_POST['customer_id'] = $result['customer_id'];
            } else {
                $_POST['customer_id'] = $customer_id;
            }
            $_POST['customer_code'] = $customer_code;
        } catch (Exception $e) {
            Sentry\captureException($e);
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    public function validateAPIDate($data)
    {
        if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function saveShortUrl($payment_req_id)
    {
        $payment_link[] = config('app.url') . '/patron/paymentrequest/view/' . $this->encrypt->encode($payment_req_id);
        include_once SWIPEZ_UTIL_PATH . '/src/shorturl/SwipezShortURLWrapper.php';
        $shortUrlWrap = new SwipezShortURLWrapper();
        $shortUrls = $shortUrlWrap->SaveUrl($payment_link);
        $this->common->updateShortURL($payment_req_id, $shortUrls[0]);
        return $shortUrls[0];
    }

    function roundAmount($amount, $num)
    {
        $text = number_format($amount, $num);
        $amount = str_replace(',', '', $text);
        return $amount;
    }
}
