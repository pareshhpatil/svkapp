<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Invoice extends Api {

    Private $errorlist = NULL;

    function __construct() {
        parent::__construct('Merchant');
        $this->version = 'v2';
    }

    /**
     * create invoice saved
     */
    function createPost($srNo, $invoice, $template_type, $templatecolumn) {
        try {
            $invoice['landline'] = (isset($invoice['landline'])) ? $invoice['landline'] : '';
            $invoice['bill_cycle_name'] = ($invoice['bill_cycle_name'] != '') ? $invoice['bill_cycle_name'] : '';
            $invoice['late_fee'] = ($invoice['latefee'] > 0) ? $invoice['latefee'] : 0;
            $invoice['particular_total'] = isset($invoice['particular_total_lable']) ? $invoice['particular_total_lable'] : 'Particular total';
            $invoice['tax_total'] = isset($invoice['tax_total_lable']) ? $invoice['tax_total_lable'] : 'Tax total';
            $invoice['user_code'] = '';
            $invoice['user_code_name'] = '';
            $invoice['notify_patron'] = ($invoice['invoice_properties']['notify_patron'] == 0) ? 0 : 1;
            $coupon_id = 0;
            if (!empty($invoice['coupon'])) {
                $coupon_id = $this->getCoupon_id($invoice['coupon'], $srNo);
            }
            $invoice['coupon_id'] = $coupon_id;
            $int = 0;
            $p_int = 0;
            $t_int = 0;
            $d_int = 0;
            foreach ($invoice['main_header_fields'] as $column) {
                if ($templatecolumn['M'][$int] != $column['id']) {
                    $this->createErrorlist('ER02001', 'id', "invoice[$srNo].main_header_fields[$int].id");
                }
                $invoice['newvalues'][] = $column['value'];
                $invoice['ids'][] = $column['id'];
                $int++;
            }
            $int = 0;
            foreach ($invoice['custom_header_fields'] as $column) {
                if ($templatecolumn['H'][$int] != $column['id']) {
                    $this->createErrorlist('ER02001', 'id', "invoice[$srNo].custom_header_fields[$int].id");
                }
                if (ucwords($column['type']) == 'Primary') {
                    $invoice['user_code'] = $column['value'];
                    $invoice['user_code_name'] = $column['name'];
                    if ($template_type == 'simple') {
                        $column['value'] = '';
                    }
                }

                if (ucwords($column['type']) == 'Textarea') {
                    $invoice['address'] = substr($column['value'], 0, 250);
                    if ($template_type == 'simple') {
                        $column['value'] = '';
                    }
                }

                $invoice['newvalues'][] = $column['value'];
                $invoice['ids'][] = $column['id'];

                $int++;
            }
            $particular_total = 0;
            $tax_total = 0;
            foreach ($invoice['particular_rows'] as $column) {
                if ($templatecolumn['PF'][$p_int] != $column['id'] && $column['id'] == '') {
                    $this->createErrorlist('ER02001', 'id', "invoice[$srNo].particular_rows[$int].id");
                }
                $column_id = $column['id'];
                switch ($template_type) {
                    case 'hotel':
                        $invoice['newvalues'][] = $column['name'];
                        $invoice['newvalues'][] = $column['quantity'];
                        $invoice['newvalues'][] = $column['price'];
                        $total = $column['price'] * $column['quantity'];
                        $particular_total = $particular_total + $total;
                        $invoice['newvalues'][] = $total;
                        if ($column_id != '') {
                            $invoice['ids'][] = $column_id;
                            $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                        } else {
                            $invoice['ids'][] = 'P1';
                            $invoice['ids'][] = 'P2';
                            $invoice['ids'][] = 'P3';
                            $invoice['ids'][] = 'P4';
                        }
                        break;
                    case 'school':
                        $invoice['newvalues'][] = $column['name'];
                        $invoice['newvalues'][] = $column['duration'];
                        $invoice['newvalues'][] = $column['amount'];
                        $particular_total = $particular_total + $column['amount'];
                        $invoice['newvalues'][] = $column['narrative'];
                        if ($column_id != '') {
                            $invoice['ids'][] = $column_id;
                            $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                        } else {
                            $invoice['ids'][] = 'P1';
                            $invoice['ids'][] = 'P2';
                            $invoice['ids'][] = 'P3';
                            $invoice['ids'][] = 'P4';
                        }
                        break;
                    case 'isp':
                        $invoice['newvalues'][] = $column['description'];
                        $invoice['newvalues'][] = $column['annual_recurring_charges'];
                        $invoice['newvalues'][] = $column['time_period'];
                        $invoice['newvalues'][] = $column['amount'];
                        $particular_total = $particular_total + $column['amount'];
                        if ($column_id != '') {
                            $invoice['ids'][] = $column_id;
                            $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                        } else {
                            $invoice['ids'][] = 'P1';
                            $invoice['ids'][] = 'P2';
                            $invoice['ids'][] = 'P3';
                            $invoice['ids'][] = 'P4';
                        }
                        break;
                    default :
                        $invoice['newvalues'][] = $column['name'];
                        $invoice['newvalues'][] = $column['price'];
                        $invoice ['newvalues'][] = $column['quantity'];
                        $total = $column['price'] * $column['quantity'];
                        $particular_total = $particular_total + $total;
                        $invoice['newvalues'][] = $total;
                        $invoice['newvalues'][] = $column['narrative'];
                        if ($column_id != '') {
                            $invoice['ids'][] = $column_id;
                            $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                            $invoice['ids'][] = $column_id++;
                        } else {
                            $invoice['ids'][] = 'P1';
                            $invoice['ids'][] = 'P2';
                            $invoice['ids'][] = 'P3';
                            $invoice['ids'][] = 'P4';
                            $invoice['ids'][] = 'P5';
                        }
                        break;
                }
                $p_int++;
            }
            foreach ($invoice['tax_rows'] as $column) {
                if ($templatecolumn['TF'][$t_int] != $column['id'] && $column['id'] != '') {
                    $this->createErrorlist('ER02001', 'id', "invoice[$srNo].tax_rows[$int].id");
                }
                $column_id = $column['id'];
                $invoice['newvalues'][] = $column['name'];
                $invoice['newvalues'] [] = $column['percentage'];
                $invoice['newvalues'][] = $column['applicable_on'];
                $total = ($column['percentage'] * $column['applicable_on']) / 100;
                $tax_total = $tax_total + $total;
                $invoice['newvalues'][] = $total;
                $invoice['newvalues'][] = $column['narrative'];
                if ($column_id != '') {
                    $invoice['ids'][] = $column_id;
                    $column_id++;
                    $invoice['ids'][] = $column_id++;
                    $invoice['ids'][] = $column_id++;
                    $invoice['ids'][] = $column_id++;
                    $invoice['ids'][] = $column_id++;
                } else {
                    $invoice['ids'][] = 'T1';
                    $invoice['ids'][] = 'T2';
                    $invoice['ids'][] = 'T3';
                    $invoice['ids'][] = 'T4';
                    $invoice['ids'][] = 'T5';
                }
                $t_int++;
            }

            foreach ($invoice['deduct_rows'] as $column) {
                if ($templatecolumn['DF'][$d_int] != $column['id'] && $column['id'] != '') {
                    $this->createErrorlist('ER02001', 'id', "invoice[$srNo].tax_rows[$int].id");
                }
                $column_id = $column['id'];
                $invoice['newvalues'][] = $column['name'];
                $invoice['newvalues'] [] = $column['percentage'];
                $invoice['newvalues'][] = $column['applicable_on'];
                $total = ($column['percentage'] * $column['applicable_on']) / 100;
                $invoice['newvalues'][] = $total;
                if ($column_id != '') {
                    $invoice['ids'][] = $column_id;
                    $column_id++;
                    $invoice['ids'][] = $column_id++;
                    $invoice['ids'][] = $column_id++;
                    $invoice['ids'][] = $column_id++;
                } else {
                    $invoice['ids'][] = 'D1';
                    $invoice['ids'][] = 'D2';
                    $invoice['ids'][] = 'D3';
                    $invoice['ids'][] = 'D4';
                }
                $d_int++;
            }

            if ($template_type == 'simple') {
                $invoice['totalcost'] = ($invoice['amount'] != '') ? $invoice['amount'] : 0;
                $invoice['totaltax'] = (isset($invoice['totaltax'])) ? $invoice['totaltax'] : 0;
            } else {
                $invoice['totalcost'] = $particular_total;
                $invoice['totaltax'] = $tax_total;
            }

            $invoice['newvalues'] = (isset($invoice['newvalues'])) ? $invoice['newvalues'] : array();
            $invoice['ids'] = (isset($invoice['ids'])) ? $invoice['ids'] : array();
            $invoice['supplier'] = array();
            $invoice['narrative'] = (isset($invoice['narrative'])) ? $invoice['narrative'] : '';
            if (isset($invoice['previous_dues'])) {
                $invoice['previous_dues'] = ($invoice['previous_dues'] > 0) ? $invoice['previous_dues'] : 0;
            } else {
                $invoice['previous_dues'] = 0;
            }
            $space_position = strpos($invoice['patron_name'], ' ');
            if ($space_position > 0) {
                $invoice['first_name'] = substr($invoice['patron_name'], 0, $space_position);
                $invoice['last_name'] = substr($invoice['patron_name'], $space_position);
            } else {
                $invoice['first_name'] = $invoice['patron_name'];
                $invoice['last_name'] = '';
            }
            return $invoice;
        } catch (Exception $e
        ) {
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0001]Error while createing post from Json reqest' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function save() {
        try {

            $req_time = date("Y-m-d H:i:s");
            try {
                $template_id = $this->encrypt->decode($this->jsonArray ['template_id']);
                if (strlen($template_id) != 10) {
                    $template_id = $this->common->getRowValue('value', 'encrypt_backup', 'encrypt_key', $this->jsonArray ['template_id'], 0);
                    if ($template_id == FALSE) {
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
                    }
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0002]Error decrept template Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }

            require_once MODEL . 'merchant/CustomerModel.php';
            $Customer_model = new CustomerModel();

            require_once MODEL . 'merchant/TemplateModel.php';
            $templateModel = new TemplateModel();

            require_once MODEL . 'merchant/InvoiceModel.php';
            $invoiceModel = new InvoiceModel();

            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($Customer_model);

            try {
                $info = $templateModel->getTemplateInfo($template_id);
            } catch (Exception $e) {
Sentry\captureException($e);
                
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0003]Error get template info Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }

            if (empty($info)) {
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0003-1]Error get template info Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']');
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }


            $rows = $this->common->getTemplateBreakup($template_id);
            if (empty($rows)) {
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0004]Error get template rows Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            }


            $array1 = $this->getTemplateJson($this->version, $info, $rows, 'template_id');
            $this->compairJsonArray($array1, $this->jsonArray);


            $templatecolumn = array();
            foreach ($rows as $row) {
                if ($row['save_table_name'] == 'metadata' && $row['column_type'] != 'PT' && $row['column_type'] != 'TT') {
                    if ($row['column_type'] == 'M') {
                        if ($row['default_column_value'] == 'Custom') {
                            $templatecolumn[$row['column_type']][] = $row['column_id'];
                        }
                    } else {
                        $exit = 0;
                        if ($row['function_id'] == 9) {
                            $mapping_details = $this->common->getfunctionMappingDetails($row['column_id'], $row['function_id']);
                            if ($mapping_details['param'] == 'system_generated') {
                                $exit = 1;
                            }
                        }
                        if ($exit == 0) {
                            $templatecolumn[$row['column_type']][] = $row['column_id'];
                            if ($info['template_type'] == 'society' && $row['column_position'] == 10) {
                                $templatecolumn['previous_dues_id'] = $row['column_id'];
                            }
                        }
                    }
                }
            }


            if ($this->user_id == $info['user_id']) {
                $template_type = $info['template_type'];
                $srNo = 0;

                $invoicevalues = $rows;
                require_once CONTROLLER . 'InvoiceWrapper.php';
                $invoice = new InvoiceWrapper($this->common);
                $invoicevalues = $invoice->getTemplateBreakup($invoicevalues);

                foreach ($this->jsonArray['invoice'] as $invoice) {
                    $_POST = $this->createPost($srNo, $invoice, $template_type, $templatecolumn);
                    if ($template_type == 'simple') {
                        $bill_date_col = 4;
                        $due_date_col = 5;
                    } else {
                        $bill_date_col = 5;
                        $due_date_col = 6;
                    }
                    require_once PACKAGE . 'swipez/function/DataFunction.php';
                    $mhf = count($_POST['main_header_fields']);
                    $int = ($mhf > 0) ? $mhf : 0;
                    foreach ($invoicevalues['header'] as $column) {
                        if ($column['function_id'] > 0) {
                            $exit = 0;
                            if ($column['function_id'] == 9) {
                                $mapping_details = $this->common->getfunctionMappingDetails($column['column_id'], $column['function_id']);
                                if ($mapping_details['param'] == 'system_generated') {
                                    $function_details = $this->common->getSingleValue('column_function', 'function_id', $column['function_id']);
                                    $function = new $function_details['php_class']();
                                    $function->req_type = 'Staging invoice';
                                    $function->function_id = $column['function_id'];
                                    if (!empty($mapping_details)) {
                                        $function->param_name = $mapping_details['param'];
                                        $function->param_value = $mapping_details['value'];
                                    }
                                    $function->set('');
                                    $_POST['newvalues'][] = $function->get('value');
                                    $_POST['ids'][] = $column['column_id'];
                                    $exit = 1;
                                }
                            } else {
                                $function_details = $this->common->getSingleValue('column_function', 'function_id', $column['function_id']);
                                $function = new $function_details['php_class']();
                                $function->req_type = 'Staging invoice';
                                $function->function_id = $column['function_id'];
                                $mapping_details = $this->common->getfunctionMappingDetails($column['column_id'], $column['function_id']);
                                if (!empty($mapping_details)) {
                                    $function->param_name = $mapping_details['param'];
                                    $function->param_value = $mapping_details['value'];
                                }

                                if ($column['column_position'] == $bill_date_col) {
                                    $function->set($_POST['bill_date']);
                                    $billdate = $function->get('value');
                                    $billdate = new DateTime($billdate);
                                    $_POST['bill_date'] = $billdate->format('Y-m-d');
                                }
                                if ($column['column_position'] == $due_date_col) {
                                    $function->set($_POST['due_date']);
                                    $duedate = $function->get('value');
                                    $duedate = new DateTime($duedate);
                                    $_POST['due_date'] = $duedate->format('Y-m-d');
                                }
                                if ($column['table_name'] == 'metadata') {
                                    $function->set($_POST['newvalues'][$int]);
                                    $_POST['newvalues'][$int] = $function->get('value');
                                }
                            }
                        }
                        if ($column['table_name'] == 'metadata' && $exit == 0) {
                            $int++;
                        }
                    }
                    $validator->validateAPIBeforecustomer($this->merchant_id);
                    $hasErrors = $validator->fetchErrors();
                    if ($hasErrors == false) {
                        $this->customerWrapper($_POST);
                        $validator->validateAPIInvoice($this->merchant_id, $this->user_id);
                        $hasErrors = $validator->fetchErrors();
                    }
                    if ($hasErrors == false) {
                        $POST_array[] = $_POST;
                    } else {
                        $this->handleValidationError($hasErrors, "invoice[$srNo]");
                    }
                    $srNo++;
                }
                $res = $this->common->isValidPackageInvoice($this->merchant_id, count($POST_array), 'indi');
                if ($res ==false) {
SwipezLogger::error(__CLASS__, '[E285-1]Error while validating package invoice count Error: api rows more than ' . substr($res, 2) . 'for merchant [' . $this->merchant_id . ']');
                    return $this->printJsonResponse($req_time, 'ER02032');
                    $this->errorlist = array();
                }

                if ($this->errorlist == NULL) {
                    $success = $this->save_invoice($POST_array, $template_id, $invoiceModel);
                    $error_code = 0;
                } else {
                    $error_code = 'ER01007';
                }
                return $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
            } else {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while sending payment request Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function settle() {
        try {
            $req_time = date("Y-m-d H:i:s");
            $payment_req_id = $this->jsonArray['invoice_id'];
            $notify = ($this->jsonArray['notify'] == 1) ? 1 : 0;
            $_POST['is_partial'] = ($this->jsonArray['is_partial'] == 1) ? 1 : 0;
            if (strlen($payment_req_id) != 10) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }
            require_once MODEL . 'merchant/PaymentrequestModel.php';
            $requestModel = new PaymentRequestModel();

            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($requestModel);

            $array1 = $this->getsettleJson(2);
            unset($this->jsonArray['is_partial']);
            $this->compairJsonArray($array1, $this->jsonArray);


            try {
                $request = $this->common->getSingleValue('payment_request', 'payment_request_id', $payment_req_id, 0, " and merchant_id='" . $this->merchant_id . "'");
                $plugin = json_decode($request['plugin_value'], 1);
                
                if ($plugin['has_partial'] == 0) {
                    $_POST['is_partial'] = 0;
                } else {
                    if ($_POST['is_partial'] == 1) {
                        if ($plugin['partial_min_amount'] > $this->jsonArray['amount']) {
                            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02042');
                            die();
                        }
                    }
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                SwipezLogger::warn(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0010]Error while get payment request details payment request id:[' . $payment_req_id . '] Error:' . $e->getMessage());
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }


            if (!empty($request)) {
                $status = $request['payment_request_status'];
                if ($status != 1) {
                    switch ($this->jsonArray['mode']) {
                        case 'NEFT':
                            $mode = 1;
                            break;
                        case 'Cheque':
                            $mode = 2;
                            break;
                        case 'Cash':
                            $mode = 3;
                            break;
                        case 'Online':
                            $mode = 5;
                            break;
                        default :
                            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01010');
                            break;
                    }

                    $_POST['date'] = $this->jsonArray['paid_date'];
                    $_POST['bank_transaction_no'] = $this->jsonArray['bank_ref_no'];
                    $_POST['bank_name'] = $this->jsonArray['bank_name'];
                    $_POST['cheque_no'] = $this->jsonArray['cheque_no'];
                    $_POST['cash_paid_to'] = $this->jsonArray['paid_to'];
                    $_POST['mode'] = $mode;
                    $_POST['amount'] = $this->jsonArray['amount'];
                    $validator->validateRespond();
                    $hasErrors = $validator->fetchErrors();
                    try {
                        $date = new DateTime($_POST['date']);
                    } catch (Exception $e) {
Sentry\captureException($e);
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02002');
                    }
                    if ($hasErrors == NULL) {
                        if ($status == 2) {
                            $result = $this->settle_update($_POST, $payment_req_id, $mode, $date, $requestModel);
                        } else {
                            $result = $this->settle_payment($_POST, $payment_req_id, $mode, $date, $requestModel);
                            if ($_POST['is_partial'] == 1) {
                                $this->common->queryexecute("call set_partialypaid_amount('" . $payment_req_id . "');");
                            }
                        }
                        if ($notify == 1) {
                            $receipt_info = $this->common->getReceipt($result['offline_response_id'], 'offline');
                            require_once CONTROLLER . '/Secure.php';
                            $secure = new Secure();
                            $receipt_info['BillingEmail'] = $receipt_info['patron_email'];
                            $receipt_info['MerchantRefNo'] = $receipt_info['transaction_id'];
                            $receipt_info['BillingName'] = $receipt_info['patron_name'];
                            $receipt_info['merchant_name'] = $receipt_info['company_name'];
                            $receipt_info['is_offline'] = 1;
                            $secure->sendMailReceipt($receipt_info, 'directpay');
                        }

                        $success = array('invoice_id' => $payment_req_id, 'code' => $result['user_code'], 'transaction_id' => $result['offline_response_id'], 'paid_date' => $date->format('Y-m-d'), 'patron_name' => $result['patron_name'], 'email_id' => $result['email_id'], 'amount' => $_POST['amount']);

                        $error_code = 0;
                    } else {
                        $this->handleValidationError($hasErrors);
                    } return $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
                } else {
                    return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01009');
                }
            } else {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0008]Error while settle payment request Error: for merchant [' . $this->user_id . '] and for invoice id [' . $payment_req_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    public function settle_payment($post, $payment_req_id, $mode, $date, $requestModel) {
        try {
            $post['is_partial'] = ($post['is_partial'] == 1) ? 1 : 0;
            $result = $requestModel->respond($post['amount'], $post['bank_name'], $payment_req_id, $date->format('Y-m-d'), $mode, $post['bank_transaction_no'], $post['cheque_no'], $post['cash_paid_to'], 0, 0, $this->user_id, 0, '', '', $post['is_partial']);
            return $result;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0011]Error while settle payment request Error: for payment_req_id [' . $payment_req_id . '] ' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    public function settle_update($post, $payment_req_id, $mode, $date, $requestModel) {
        try {
            require_once MODEL . 'CommonModel.php';
            $common_model = new CommonModel();
            $detail = $common_model->getofflineTransaction_id($payment_req_id);
            if (!empty($detail)) {
                $requestModel->respondUpdate($post['amount'], $post['bank_name'], $detail['offline_response_id'], $date->format('Y-m-d'), $mode, $post['bank_transaction_no'], $post['cheque_no'], '', $post['cash_paid_to'], $this->user_id);
                $receipt = $common_model->getReceipt($detail['offline_response_id'], 'Offline');
                $result['user_code'] = $detail['user_code'];
                $result['offline_response_id'] = $detail['offline_response_id'];
                $result['patron_name'] = $receipt['patron_name'];
                $result['email_id'] = $receipt['patron_email'];
            } else {
                require_once MODEL . 'merchant/PaymentrequestModel.php';
                $requestModel = new PaymentRequestModel();
                $result = $this->settle_payment($post, $payment_req_id, $mode, $date,$requestModel);
            }
            return $result;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0012]Error while update  settle payment request Error: for payment_req_id [' . $payment_req_id . '] ' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    public function save_invoice($POST_array, $template_id, $invoiceModel) {
        foreach ($POST_array as $_POST) {
            try {
                $billdate = new DateTime($_POST['bill_date']);
                $duedate = new DateTime($_POST['due_date']);
            } catch (Exception $e) {
Sentry\captureException($e);
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02002');
            }

            if (substr($_POST['invoice_number'], 0, 16) == 'System generated') {
                $invoice_number = $this->common->getInvoiceNumber($this->user_id, substr($_POST['invoice_number'], 16));
                $int = 0;
                foreach ($_POST['newvalues'] as $val) {
                    if ($val == $_POST['invoice_number'] && $val != '') {
                        $_POST['newvalues'][$int] = $invoice_number;
                    }
                    $int++;
                }
                $_POST['invoice_number'] = $invoice_number;
            }
            $last_payment = ($_POST['last_payment'] > 0) ? $_POST['last_payment'] : 0;
            $adjustment = ($_POST['adjustment'] > 0) ? $_POST['adjustment'] : 0;
            $amount = $_POST['totalcost'] + $_POST['totaltax'] + $_POST['previous_dues'] - $last_payment - $adjustment;
            $result = $invoiceModel->saveInvoice($_POST['invoice_number'], $template_id, $this->user_id, $_POST['customer_id'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $_POST['bill_cycle_name'], $_POST['newvalues'], $_POST['ids'], $_POST['narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['particular_total'], $_POST['tax_total'], $_POST['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $_POST['supplier'], $_POST ['late_fee'], 0, $_POST ['notify_patron'], $_POST ['coupon_id'], 0, 1, 0, 0, null, null, 0, $_POST ['expiry_date'], $this->user_id, $_POST['landline'], $_POST['user_code']);
            $success[] = array('invoice_id' => $result['request_id'], 'code' => $result['code'], 'bill_date' => $billdate->format('Y-m-d'),
                'patron_name' => $_POST['patron_name'], 'email_id' => $_POST['email_id'], 'mobile' => $_POST['mobile'], 'amount' => $amount);
        }
        return $success;
    }

    function createErrorlist($code, $key_name, $key_path) {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }

    function handleValidationError($errors, $path_ = NULL) {
        foreach ($errors as $key => $value) {
            $path = ($path_ != NULL) ? $path_ . "." . $key : $key;
            switch ($key) {
                case 'patron_name': $this->createErrorlist('ER02005', $key, $path);
                    break;
                case 'email_id' : $this->createErrorlist('ER02004', $key, $path);
                    break;
                case 'mobile':
                    $this->createErrorlist('ER02003', $key, $path);
                    break;
                case 'totalcost':
                    $key = 'amount';
                    $this->createErrorlist('ER02006', $key, $path);
                    break;
                case 'amount':
                    $this->createErrorlist('ER02006', $key, $path);
                    break;
                case 'late_fee':
                    $key = 'latefee';
                    $this->createErrorlist('ER02006', $key, $path);
                    break;
                case 'bill_date': $this->createErrorlist('ER02002', $key, $path);
                    break;
                case 'due_date': $this->createErrorlist('ER02012', $key, $path);
                    break;
                case 'expiry_date': $this->createErrorlist('ER02013', $key, $path);
                    break;
                case 'cheque_no':
                    $this->createErrorlist('ER02007', $key, $path);
                    break;
                case 'bank_transaction_no': $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'cash_paid_to': $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'bank_name': $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'date':
                    $this->createErrorlist('ER02002', $key, $path);
                    break;
                case 'bill_cycle_name':
                    $this->createErrorlist('ER02014', $key, $path);
                    break;
                case 'coupon_code':
                    $this->createErrorlist('ER02018', $key, $path);
                    break;
                case 'coupon_code':
                    $this->createErrorlist('ER02018', $key, $path);
                    break;
                case 'start_date':
                    $this->createErrorlist('ER02002', $key, $path);
                    break;
                case 'end_date':
                    $this->createErrorlist('ER02002', $key, $path);
                    break;
                case 'fixed_amount':
                    $this->createErrorlist('ER02019', $key, $path);
                    break;
                case 'percent':
                    $this->createErrorlist('ER02019', $key, $path);
                    break;
                default :
                    $this->createErrorlist('ER02015', $key, $path);
                    break;
            }
        }
    }

    function getCoupon_id($coupon_det, $srNo) {
        require_once MODEL . 'merchant/CouponModel.php';
        $CouponModel = new CouponModel();
        if ($coupon_det['associate_coupon']['id'] > 0) {
            $coupon_id = $coupon_det['associate_coupon']['id'];
            $result = $CouponModel->isUserCoupon($coupon_id, $this->merchant_id);
            if ($result == TRUE) {
                return $coupon_id;
            } else {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02016');
            }
        } else {
            if ($coupon_det['create_coupon']['value'] > 0) {
                $_POST['coupon_code'] = $coupon_det['create_coupon']['coupon_code'];
                $description = $coupon_det['create_coupon']['description'];
                $value = $coupon_det['create_coupon']['value'];
                $type = $coupon_det['create_coupon']['type'];
                $_POST['start_date'] = $coupon_det['create_coupon']['start_date'];
                $_POST['end_date'] = $coupon_det['create_coupon']['end_date'];
                switch ($type) {
                    case 'F':
                        $_POST['fixed_amount'] = $value;
                        $_POST['percent'] = 0;
                        $is_fixed = 1;
                        break;
                    case 'P':
                        $_POST['fixed_amount'] = 0;
                        $_POST['percent'] = $value;
                        $is_fixed = 0;
                        break;
                    default :
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02017');
                        return;
                        break;
                }

                require_once CONTROLLER . 'merchant/Templatevalidator.php';
                $validator = new Templatevalidator($CouponModel);
                $validator->validateCouponSave($this->merchant_id);
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    $start_date = new DateTime($_POST['start_date']);
                    $end_date = new DateTime($_POST['end_date']);
                    $coupon_id = $CouponModel->createCoupon($this->user_id, $this->merchant_id, $_POST['coupon_code'], $description, $start_date->format('Y-m-d'), $end_date->format('Y-m-d'), 0, $is_fixed, $_POST['percent'], $_POST['fixed_amount']);
                    return $coupon_id;
                } else {
                    $this->handleValidationError($hasErrors, "invoice[" . $srNo . "][coupon]");
                }
            } else {
                return 0;
            }
        }
    }

}
