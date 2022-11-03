<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Subscription extends Api {

    Private $errorlist = NULL;

    function __construct() {
        parent::__construct('Merchant');
        $this->version = 'v1';
    }

    /**
     * create invoice saved
     */
    function createPost($srNo, $invoice, $template_type, $templatecolumn) {
        try {
            $invoice['landline'] = (isset($invoice['landline'])) ? $invoice['landline'] : '';
            if (isset($invoice['franchise_id'])) {
                $invoice['franchise_id'] = ($invoice['franchise_id'] > 0) ? $invoice['franchise_id'] : '';
            } else {
                $invoice['franchise_id'] = '0';
            }
            if (isset($invoice['vendor_id'])) {
                $invoice['vendor_id'] = ($invoice['vendor_id'] > 0) ? $invoice['vendor_id'] : '';
            } else {
                $invoice['vendor_id'] = '0';
            }
            if (isset($invoice['webhook_id'])) {
                $invoice['webhook_id'] = ($invoice['webhook_id'] > 0) ? $invoice['webhook_id'] : 0;
            } else {
                $invoice['webhook_id'] = 0;
            }

            if (isset($invoice['advance_received'])) {
                $invoice['advance'] = ($invoice['advance_received'] > 0) ? $invoice['advance_received'] : 0;
            } else {
                $invoice['advance'] = 0;
            }
            $invoice['franchise_id'] = (isset($invoice['franchise_id'])) ? $invoice['franchise_id'] : '0';
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
                if ($templatecolumn['PF'][$p_int] != $column['id'] && $column['id'] != '') {
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
            } foreach ($invoice['tax_rows'] as $column) {
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
            $invoice['previous_dues'] = (isset($invoice['previous_dues']) && $invoice['previous_dues'] != '') ? $invoice['previous_dues'] : 0;
            return $invoice;
        } catch (Exception $e
        ) {
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0001]Error while createing post from Json reqest' . $e->getMessage());
            $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
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
                        $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
                    }
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0002]Error decrept template Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }
            require_once MODEL . 'merchant/TemplateModel.php';
            $templateModel = new TemplateModel();

            require_once MODEL . 'merchant/CustomerModel.php';
            $customerModel = new CustomerModel();

            require_once MODEL . 'merchant/InvoiceModel.php';
            $invoiceModel = new InvoiceModel();

            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($customerModel);

            try {
                $info = $templateModel->getTemplateInfo($template_id);
            } catch (Exception $e) {
Sentry\captureException($e);
                
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0003]Error get template info Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }

            if (empty($info)) {
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0003-1]Error get template info Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']');
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }


            $rows = $this->common->getTemplateBreakup($template_id);
            if (empty($rows)) {
SwipezLogger::error(__CLASS__, '[A0004]Error get template rows Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            }


            $array1 = $this->getTemplateJson('v3', $info, $rows, 'template_id', '', 'subscription');
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
                $invoice = $this->jsonArray['invoice'];





                $_POST = $this->createPost($srNo, $invoice, $template_type, $templatecolumn);
                if (isset($_POST['cc_email'])) {
                    $_POST['cc'] = $invoice['cc_email'];
                }
                $_POST['franchise_name_invoice'] = $info['franchise_name_invoice'];
                $invoicevalues = $rows;
                require_once CONTROLLER . 'InvoiceWrapper.php';
                $invoice = new InvoiceWrapper($this->common);
                $invoicevalues = $invoice->getTemplateBreakup($invoicevalues);
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
                            } else {
                                $key = array_search($column['column_id'], $_POST['ids']);
                                $_POST['invoice_number'] = $_POST['newvalues'][$key];
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
                                $res = $this->validateAPIDate($billdate);
                                if ($res == true) {
                                    $billdate = new DateTime($billdate);
                                    $_POST['bill_date'] = $billdate->format('Y-m-d');
                                } else {
                                    $_POST['bill_date'] = $billdate;
                                }
                            }
                            if ($column['column_position'] == $due_date_col) {
                                $function->set($_POST['due_date']);
                                $duedate = $function->get('value');
                                $res = $this->validateAPIDate($duedate);
                                if ($res == true) {
                                    $duedate = new DateTime($duedate);
                                    $_POST['due_date'] = $duedate->format('Y-m-d');
                                } else {
                                    $_POST['due_date'] = $duedate;
                                }
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
                $_POST['mode'] = $this->jsonArray['mode'];
                $_POST['repeat_every'] = $this->jsonArray['repeat_every'];
                $_POST['start_date'] = $this->jsonArray['start_date'];
                $_POST['end_mode'] = $this->jsonArray['end_mode'];
                $_POST['occurences'] = $this->jsonArray['occurences'];
                $_POST['end_date'] = $this->jsonArray['end_date'];
                $_POST['carry_forword_dues'] = $this->jsonArray['carry_forword_dues'];

                $validator->validateSubscriptionAPIInvoice($this->merchant_id, $this->user_id);
                $hasErrors = $validator->fetchErrors();
                unset($hasErrors['customer_code']);
                if ($hasErrors == false || empty($hasErrors)) {
                    if ($_POST['customer_code'] != '') {
                        $customer_id = $customerModel->isExistCustomerCode($this->merchant_id, $_POST['customer_code']);
                    }
                    $_POST['covering_id'] = 0;
                    if ($info['has_covering_note'] == 1) {
                        $_POST['covering_id'] = $info['covering_id'];
                    }

                    if ($info['has_custom_notification'] == 1) {
                        $_POST['custom_subject'] = $info['custom_subject'];
                        $_POST['custom_sms'] = $info['custom_sms'];
                    }


                    $res = $this->validateCustomer($srNo, $this->jsonArray['invoice']['new_customer'], $customer_id);
                    if ($customer_id == FALSE && $_POST['email'] != '') {
                        $data = $customerModel->getcustomerId($this->merchant_id, $_POST['first_name'], $_POST['email']);
                        if ($data != FALSE) {
                            $customer_id = $data['customer_id'];
                            $_POST['customer_code'] = $data['customer_code'];
                        } else {
                            $customer_id = FALSE;
                        }
                    }

                    if ($customer_id == false) {
                        if ($this->errorlist == NULL) {
                            $result = $this->save_Customer($customerModel);
                            if ($result == FALSE) {
                                $this->createErrorlist('ER02031', 'customer_code', "Invoice[$srNo].customer_code");
                            } else {
                                $_POST['customer_id'] = $result['customer_id'];
                            }
                        }
                    } else {
                        if ($res == true) {
                            $result = $this->update_Customer($customerModel, $customer_id, $_POST['customer_code']);
                        }
                        $_POST['customer_id'] = $customer_id;
                    }
                    $POST_array = $_POST;
                } else {
                    $this->handleValidationError($hasErrors, "invoice[$srNo]");
                }
                $srNo++;

                $res = $this->common->isValidPackageInvoice($this->merchant_id, 1, 'indi');
                if ($res ==false) {
SwipezLogger::error(__CLASS__, '[E285-1]Error while validating package invoice count Error: api rows more than ' . substr($res, 2) . 'for merchant [' . $this->merchant_id . ']');
                    $this->printJsonResponse($req_time, 'ER02032');
                    $this->errorlist = array();
                }


                if ($this->errorlist == NULL) {
                    $success = $this->save_invoice($_POST, $template_id, $invoiceModel);
                    $error_code = 0;
                } else {
                    $error_code = 'ER01007';
                }
                $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
            } else {
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while sending payment request Error: for merchant [' . $this->merchant_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01007');
        }
    }

    function validateCustomer($srNo, $data, $customer_id = null) {
        if (empty($data)) {
            return true;
        }
        require_once MODEL . 'merchant/CustomerModel.php';
        $Customer_model = new CustomerModel();

        require_once CONTROLLER . 'merchant/Customervalidator.php';
        $validator = new Customervalidator($Customer_model);

        $array1 = $this->getcustomerSaveJson($this->version, $this->merchant_id);
        foreach ($data as $key => $value) {
            $_POST[$key] = $value;
        }
        $int = 0;
        $space_position = strpos($_POST['customer_name'], ' ');
        if ($space_position > 0) {
            $_POST['first_name'] = substr($_POST['customer_name'], 0, $space_position);
            $_POST['last_name'] = substr($_POST['customer_name'], $space_position);
        } else {
            $_POST['first_name'] = $_POST['customer_name'];
            $_POST['last_name'] = '';
        }

        $addressfull = $_POST['address'];
        $_POST['address'] = substr($addressfull, 0, 250);
        $_POST['address2'] = substr($addressfull, 250);

        $_POST['email'] = ($_POST['email'] == '') ? 'emailunavailable@swipez.in' : $_POST['email'];
        $_POST['mobile'] = ($_POST['mobile'] == '') ? '9999999999' : $_POST['mobile'];
        //$_POST['customer_name'] = ($_POST['customer_name'] == '') ? 'No name' : $_POST['customer_name'];
        foreach ($array1['customer'][0]['custom_fields'] as $struct) {
            if ($struct['id'] != $data['custom_fields'][$int]['id']) {
                $this->createErrorlist('ER02001', 'id', "Invoice[$srNo].customer.custom_fields[$int].id");
            } else {
                $_POST['column_id'][] = $data['custom_fields'][$int]['id'];
                $_POST['column_value'][] = $data['custom_fields'][$int]['value'];
            }
            $int++;
        }

        if ($customer_id == NULL) {
            $validator->validateCustomerSave($this->merchant_id);
        } else {
            $validator->validateCustomerUpdate($this->merchant_id, $customer_id);
        }

        $hasErrors = $validator->fetchErrors();
        if (!isset($data['customer_code'])) {
            unset($hasErrors['customer_code']);
        }
        if ($hasErrors == false) {
            return true;
        } else {
            if ($customer_id == NULL) {
                $this->handleValidationError($hasErrors, "invoice[$srNo].customer");
            } else {
                return false;
            }
        }
    }

    function save_Customer($Customer_model) {

        $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
        if ($merchant_setting['customer_auto_generate'] == 1) {
            $customer_code = $Customer_model->getCustomerCode($this->merchant_id);
            $_POST['customer_code'] = $customer_code;
        } else {
            $customer_code = $_POST['customer_code'];
        }

        $column_id = (empty($_POST['column_id'])) ? array() : $_POST['column_id'];
        $column_value = (empty($_POST['column_value'])) ? array() : $_POST['column_value'];

        $row = $Customer_model->saveCustomer($this->user_id, $this->merchant_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $column_id, $column_value);
        if ($row['message'] == 'success') {
            return $row;
        } else {
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving customer Error: for user [' . $this->user_id . '] and for error [' . json_encode($row) . ']');
            return FALSE;
        }
    }

    function update_Customer($Customer_model, $customer_id, $customer_code) {
        $_POST['customer_code'] = $customer_code;
        $column_id = (empty($_POST['column_id'])) ? array() : $_POST['column_id'];
        $column_value = (empty($_POST['column_value'])) ? array() : $_POST['column_value'];
        $excolumn_id = array();
        $excolumn_value = array();
        $newcolumn_id = array();
        $newcolumn_value = array();
        $int = 0;
        foreach ($column_id as $id) {
            $v_id = $Customer_model->getcustomervalueid($id, $customer_id);
            if ($v_id > 0) {
                $excolumn_id[] = $v_id;
                $excolumn_value[] = $column_value[$int];
            } else {
                $newcolumn_id[] = $id;
                $newcolumn_value[] = $column_value[$int];
            }
            $int++;
        }

        $row = $Customer_model->updateCustomer($this->user_id, $customer_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $newcolumn_id, $newcolumn_value, $excolumn_id, $excolumn_value);
        if ($row['message'] == 'success') {
            return $row;
        } else {
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving customer Error: for user [' . $this->user_id . '] and for error [' . json_encode($row) . ']');
            return FALSE;
        }
    }

    public function save_invoice($post, $template_id, $invoiceModel) {
        $_POST = $post;
        try {
            $billdate = new DateTime($_POST['bill_date']);
            $duedate = new DateTime($_POST['due_date']);
        } catch (Exception $e) {
Sentry\captureException($e);
            $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02002');
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
        $franchise_id = ($_POST['franchise_id'] > 0) ? $_POST['franchise_id'] : 0;
        $vendor_id = ($_POST['vendor_id'] > 0) ? $_POST['vendor_id'] : 0;
        $_POST['franchise_name_invoice'] = ($_POST['franchise_name_invoice'] == 0) ? 0 : 1;
        $amount = $_POST['totalcost'] + $_POST['totaltax'] + $_POST['previous_dues'] - $last_payment - $adjustment;
        if (isset($_POST['cc'])) {
            foreach ($_POST['cc'] as $c) {
                $_POST['newvalues'][] = $c;
                $_POST['ids'][] = 'CC';
            }
        }
        $result = $invoiceModel->saveInvoice($_POST['invoice_number'], $template_id, $this->user_id, $_POST['customer_id'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $_POST['bill_cycle_name'], $_POST['newvalues'], $_POST['ids'], $_POST['narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['particular_total'], $_POST['tax_total'], $_POST['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $_POST['supplier'], $_POST ['late_fee'], $_POST['advance'], $_POST ['notify_patron'], $_POST ['coupon_id'], $franchise_id, $_POST['franchise_name_invoice'], $vendor_id, $_POST['covering_id'], $_POST['custom_subject'], $_POST['custom_sms'], $_POST['webhook_id'], $_POST ['expiry_date'], $this->user_id, $_POST['landline'], $_POST['user_code'], $_POST['address'], 1, 4);
        if ($result['has_custom_reminder'] == 1) {
            $res = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);
            $reminders = explode(',', $res['custom_reminder']);
            $subjects = json_decode($res['custom_reminder_subject'], 1);
            $smss = json_decode($res['custom_reminder_sms'], 1);

            foreach ($reminders as $key => $rm) {
                $due_date = strtotime("-" . $rm . " days", strtotime($duedate->format('Y-m-d')));
                $rdate = date('Y-m-d', $due_date);
                $invoiceModel->saveReminder($result['request_id'], $rdate, $subjects[$key], $smss[$key], $this->merchant_id, $this->user_id);
            }
        }
        //

        $_POST['bill_date'] = $_POST['start_date'];
        $billdate = new DateTime($_POST['start_date']);
        $duedate = new DateTime($_POST['due_date']);

        $repeat_every = $_POST['repeat_every'];
        $repeat_on = isset($_POST['repeat_on']) ? $_POST['repeat_on'] : 0;
        $start_date = $billdate;

        if (isset($_POST['end_date'])) {
            $end_date = new DateTime($_POST['end_date']);
            $end_date = $end_date->format('Y-m-d');
        } else {
            $end_date = date('Y-m-d');
        }


        if (isset($_POST['billing_start_date'])) {
            $billing_start_date = new DateTime($_POST['billing_start_date']);
            $billing_start_date = $billing_start_date->format('Y-m-d');
        } else {
            $billing_start_date = NULL;
        }
        $billing_period = isset($_POST['billing_period']) ? $_POST['billing_period'] : NULL;
        $period_type = isset($_POST['period_type']) ? $_POST['period_type'] : NULL;


        $due_date = $duedate;
        $diff = date_diff($start_date, $due_date);
        $date_diff = $diff->format("%a");
        $carry_due = ($_POST['carry_forword_dues'] > 0) ? $_POST['carry_forword_dues'] : 0;
        $occurrence = ($_POST['occurences'] > 0) ? $_POST['occurences'] : 0;
        $display_text = '';
        switch ($_POST['mode']) {
            case 'Daily':
                $mode = 1;
                $add = 'days';
                break;
            case 'Weekly':
                $mode = 2;
                $add = 'weeks';
                break;
            case 'Monthly':
                $mode = 3;
                $add = 'months';
                break;
            case 'Yearly':
                $mode = 4;
                $add = 'year';
                break;
        }
        switch ($_POST['end_mode']) {
            case 'Never':
                $end_mode = 1;
                break;
            case 'Occurences':
                $end_mode = 2;
                break;
            case 'End date':
                $end_mode = 3;
                break;
        }

        if ($end_mode == 2) {
            $days = ($occurrence * $repeat_every) - 1;
            $start_date = $start_date->format('Y-m-d');
            $end_date = strtotime("+" . $days . " " . $add . "", strtotime($start_date));
            $end_date = date('Y-m-d', $end_date);
        }

        //

        require_once MODEL . 'merchant/SubscriptionModel.php';
        $subscription_model = new SubscriptionModel();

        $subscription_id = $subscription_model->save_Subscription($result['request_id'], $this->merchant_id, $this->user_id, $mode, $repeat_every, $repeat_on, $billdate->format('Y-m-d'), $end_mode, $end_date, $occurrence, $display_text, $duedate->format('Y-m-d'), $date_diff, $carry_due, $billdate->format('Y-m-d'), $billing_start_date, $billing_period, $period_type);
        define('req_type', $result['request_id']);
        require_once UTIL . 'batch/Batch.php';
        require_once UTIL . 'batch/subscription/subscription.php';

        $short_url = '';
        if ($this->webrequest == true) {
            $short_url = $this->saveShortUrl($result['request_id']);
        }
        $success[] = array('invoice_id' => $result['request_id'], 'subscription_id' => $subscription_id, 'code' => $_POST['customer_code'], 'bill_date' => $billdate->format('Y-m-d'), 'patron_name' => $result['patron_name'], 'email_id' => $result['patron_email'], 'mobile' => $result['patron_mobile'], 'amount' => $this->roundAmount($amount - $_POST['advance'], 2), 'short_url' => $short_url);
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
                case 'franchise_id':
                    $this->createErrorlist('ER02033', $key, $path);
                    break;
                case 'vendor_id':
                    $this->createErrorlist('ER02035', $key, $path);
                    break;
                case 'customer_code':
                    $this->createErrorlist('ER02031', $key, $path);
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
                case 'invoice_number':
                    $this->createErrorlist('ER02035', $key, $path);
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
                case 'customer_code': $this->createErrorlist('ER02031', $key, $path);
                    break;
                case 'email' : $this->createErrorlist('ER02004', $key, $path);
                    break;
                case 'mobile':
                    $this->createErrorlist('ER02003', $key, $path);
                    break;
                case 'customer_name':
                    $this->createErrorlist('ER02005', $key, $path);
                    break;
                case 'address':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'state':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'city':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'zipcode':
                    $this->createErrorlist('ER02007', $key, $path);
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
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02016');
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
                        $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02017');
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
                    $coupon_id = $CouponModel->createCoupon($this->user_id,$this->merchant_id, $_POST['coupon_code'], $description, $start_date->format('Y-m-d'), $end_date->format('Y-m-d'), 0, $is_fixed, $_POST['percent'], $_POST['fixed_amount']);
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
