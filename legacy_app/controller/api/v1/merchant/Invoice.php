<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Invoice extends Api
{

    private $errorlist = NULL;

    function __construct()
    {
        parent::__construct('Merchant');
        $this->version = 'v1';
    }

    /**
     * create invoice saved
     */
    function createPost($srNo, $invoice, $template_type, $templatecolumn)
    {
        try {
            $invoice['landline'] = (isset($invoice['landline'])) ? $invoice['landline'] : '';
            $invoice['bill_cycle_name'] = ($invoice['bill_cycle_name'] != '') ? $invoice['bill_cycle_name'] : '';
            $invoice['late_fee'] = ($invoice['latefee'] > 0) ? $invoice['latefee'] : 0;
            $invoice['particular_total'] = isset($invoice['particular_total_lable']) ? $invoice['particular_total_lable'] : 'Particular total';
            $invoice['tax_total'] = isset($invoice['tax_total_lable']) ? $invoice['tax_total_lable'] : 'Tax total';
            $invoice['user_code'] = '';
            $invoice['user_code_name'] = '';
            // $invoice['notify_patron'] = ($invoice['invoice_properties']['notify_patron'] == 0) ? 0 : 1;
            $invoice['notify_patron'] = 1;
            $invoice['coupon_id'] = 0;
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
                    default:
                        $invoice['newvalues'][] = $column['name'];
                        $invoice['newvalues'][] = $column['price'];
                        $invoice['newvalues'][] = $column['quantity'];
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
                $invoice['newvalues'][] = $column['percentage'];
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
                $invoice['newvalues'][] = $column['percentage'];
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
        } catch (Exception $e) {

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0001]Error while createing post from Json reqest' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function save()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            $template_id = $this->encrypt->decode($this->jsonArray['template_id']);
            if (strlen($template_id) != 10) {
                $template_id = $this->common->getRowValue('value', 'encrypt_backup', 'encrypt_key', $this->jsonArray['template_id'], 0);
                if ($template_id == FALSE) {
                    return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
                }
            }
            if (strlen($template_id) != 10) {
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
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }

            if (empty($info)) {
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
                        $templatecolumn[$row['column_type']][] = $row['column_id'];
                    }
                }
            }


            if ($this->user_id == $info['user_id']) {
                $template_type = $info['template_type'];
                $srNo = 0;

                foreach ($this->jsonArray['invoice'] as $invoice) {
                    $_POST = $this->createPost($srNo, $invoice, $template_type, $templatecolumn);
                    $this->customerWrapper($_POST);
                    $validator->validateAPIInvoice($this->merchant_id);
                    $hasErrors = $validator->fetchErrors();
                    if ($hasErrors == false) {
                        $POST_array[] = $_POST;
                    } else {
                        $this->handleValidationError($hasErrors, "invoice[$srNo]");
                    }
                    $srNo++;
                }
                $res = $this->common->isValidPackageInvoice($this->merchant_id, count($POST_array), 'indi');
                if ($res == false) {
                    SwipezLogger::info(__CLASS__, '[E285-1]Error while validating package invoice count for merchant [' . $this->merchant_id . ']');
                    return $this->printJsonResponse($req_time, 'ER02032');
                    $this->errorlist = array();

                    # TODO : add message to queue to send an email to SUPPORT_EMAILS
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

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving invoice Error: for user [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function update()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            $invoice_id = $this->jsonArray['invoice_id'];
            if (strlen($invoice_id) != 10) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
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
                $invoice = $this->common->getPaymentRequestDetails($invoice_id, $this->user_id);
            } catch (Exception $e) {
                Sentry\captureException($e);
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }

            if (empty($invoice)) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }

            $status = $invoice['payment_request_status'];
            if ($status == 0 || $status == 4 || $status == 5) {

                $template_id = $invoice['template_id'];
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


                $array1 = $this->getTemplateJson($this->version, $info, $rows, 'invoice_id');

                $this->compairJsonArray($array1, $this->jsonArray);

                $template_type = $info['template_type'];
                $srNo = 0;
                $invoice = $this->jsonArray['invoice'];

                $templatecolumn = array();

                foreach ($rows as $row) {
                    if ($row['save_table_name'] == 'metadata' && $row['column_type'] != 'PT' && $row['column_type'] != 'TT') {
                        if ($row['column_type'] == 'M') {
                            if ($row['default_column_value'] == 'Custom') {
                                $templatecolumn[$row['column_type']][] = $row['column_id'];
                            }
                        } else {
                            $templatecolumn[$row['column_type']][] = $row['column_id'];
                        }
                    }
                }

                $_POST = $this->createPost(0, $invoice, $template_type, $templatecolumn);
                $validator->validateAPIInvoice($this->merchant_id);
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    $_POST['customer_id'] = $customerModel->isExistCustomerCode($this->merchant_id, $_POST['customer_code']);
                    $success = $this->update_invoice($_POST, $invoice_id, $invoiceModel);
                    $error_code = 0;
                    $this->errorlist = NULL;
                } else {
                    $this->handleValidationError($hasErrors, "invoice");
                    $error_code = 'ER01007';
                }

                return $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
            } else {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01009');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0007]Error while sending payment request Error: for merchant [' . $this->user_id . '] and for Invoice id [' . $invoice_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function settle()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            $payment_req_id = $this->jsonArray['invoice_id'];
            if (strlen($payment_req_id) != 10) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }
            require_once MODEL . 'merchant/PaymentrequestModel.php';
            $requestModel = new PaymentRequestModel();

            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($requestModel);

            $array1 = $this->getsettleJson();
            $this->compairJsonArray($array1, $this->jsonArray);

            try {
                $request = $this->common->getSingleValue('payment_request', 'payment_request_id', $payment_req_id, 0, " and merchant_id='" . $this->merchant_id . "'");
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
                        default:
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
                        }
                        $success = array('invoice_id' => $payment_req_id, 'code' => $result['user_code'], 'transaction_id' => $result['offline_response_id'], 'paid_date' => $date->format('Y-m-d'), 'patron_name' => $result['patron_name'], 'email_id' => $result['email_id'], 'amount' => $_POST['amount']);

                        $error_code = 0;
                    } else {
                        $this->handleValidationError($hasErrors);
                    }
                    return $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
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

    function delete()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            $payment_req_id = $this->jsonArray['invoice_id'];
            if (strlen($payment_req_id) != 10) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }
            require_once MODEL . 'merchant/PaymentrequestModel.php';
            $requestModel = new PaymentRequestModel();
            $array1 = $this->getdeleteJson();
            $this->compairJsonArray($array1, $this->jsonArray);
            try {
                $request = $this->common->getPaymentRequestDetails($payment_req_id, $this->user_id);
                if (empty($request)) {
                    return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
                } else {
                    $requestModel->updatePaymentRequestStatus($payment_req_id, 3);
                    $success = array('invoice_id' => $payment_req_id);
                    return $this->printJsonResponse($req_time, 0, $success, $this->errorlist);
                }
            } catch (Exception $e) {
                Sentry\captureException($e);
                SwipezLogger::warn(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0010]Error while get payment request details payment request id:[' . $payment_req_id . '] Error:' . $e->getMessage());
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0008+1]Error while delete invoice  Error: for merchant [' . $this->user_id . '] and for invoice id [' . $payment_req_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    public function save_invoice($POST_array, $template_id, $invoiceModel)
    {
        foreach ($POST_array as $_POST) {
            $billdate = new DateTime($_POST['bill_date']);
            $duedate = new DateTime($_POST['due_date']);
            $_POST['last_payment'] = 0;
            $_POST['adjustment'] = 0;
            $amount = $_POST['totalcost'] + $_POST['totaltax'] + $_POST['previous_dues'];
            $result = $invoiceModel->saveInvoice($_POST['invoice_number'], $template_id, $this->user_id, $_POST['customer_id'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $_POST['bill_cycle_name'], $_POST['newvalues'], $_POST['ids'], $_POST['narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['particular_total'], $_POST['tax_total'], $_POST['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $_POST['supplier'], $_POST['late_fee'], $_POST['notify_patron'], $_POST['coupon_id'], 0, 1, 0, 0, null, null, 0, $_POST['expiry_date'], $this->user_id, $_POST['landline'], $_POST['user_code']);
            $success[] = array(
                'invoice_id' => $result['request_id'], 'code' => $result['code'], 'bill_date' => $billdate->format('Y-m-d'),
                'patron_name' => $_POST['patron_name'], 'email_id' => $_POST['email_id'], 'mobile' => $_POST['mobile'], 'amount' => $amount
            );
        }
        return $success;
    }

    public function settle_payment($post, $payment_req_id, $mode, $date, $requestModel)
    {
        try {
            $result = $requestModel->respond($post['amount'], $post['bank_name'], $payment_req_id, $date->format('Y-m-d'), $mode, $post['bank_transaction_no'], $post['cheque_no'], $post['cash_paid_to'], 0, 0, $this->user_id);
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0011]Error while settle payment request Error: for payment_req_id [' . $payment_req_id . '] ' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    public function settle_update($post, $payment_req_id, $mode, $date, $requestModel)
    {
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

    public function update_invoice($post, $invoice_id, $invoiceModel)
    {
        $billdate = new DateTime($post['bill_date']);
        $duedate = new DateTime($post['due_date']);
        $last_payment = ($_POST['last_payment'] > 0) ? $_POST['last_payment'] : 0;
        $adjustment = ($_POST['adjustment'] > 0) ? $_POST['adjustment'] : 0;
        $amount = $_POST['totalcost'] + $_POST['totaltax'] + $_POST['previous_dues'] - $last_payment - $adjustment;
        $result = $invoiceModel->updateApiInvoice($invoice_id, $this->user_id, $post['customer_id'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $post['bill_cycle_name'], $post['newvalues'], $post['ids'], $post['narrative'], $post['totalcost'], $post['totaltax'], $post['particular_total'], $post['tax_total'], $post['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $post['supplier'], $post['late_fee'], $post['expiry_date'], $post['notify_patron'], $post['coupon_id']);
        $success = array('invoice_id' => $result['invoice_id'], 'code' => $_POST['customer_code'], 'bill_date' => $billdate->format(
            'Y-m-d'
        ), 'patron_name' => $result['patron_name'], 'email_id' => $result['patron_email'], 'mobile' => $result['patron_mobile'], 'amount' => $amount);
        return $success;
    }

    function createErrorlist($code, $key_name, $key_path)
    {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }

    function handleValidationError($errors, $path_ = NULL)
    {
        foreach ($errors as $key => $value) {
            $path = ($path_ != NULL) ? $path_ . "." . $key : $key;
            switch ($key) {
                case 'patron_name':
                    $this->createErrorlist('ER02005', $key, $path);
                    break;
                case 'email_id':
                    $this->createErrorlist('ER02004', $key, $path);
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
                case 'bill_date':
                    $this->createErrorlist('ER02002', $key, $path);
                    break;
                case 'due_date':
                    $this->createErrorlist('ER02012', $key, $path);
                    break;
                case 'expiry_date':
                    $this->createErrorlist('ER02013', $key, $path);
                    break;
                case 'cheque_no':
                    $this->createErrorlist('ER02007', $key, $path);
                    break;
                case 'bank_transaction_no':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'cash_paid_to':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'bank_name':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'date':
                    $this->createErrorlist('ER02002', $key, $path);
                    break;
                case 'bill_cycle_name':
                    $this->createErrorlist('ER02014', $key, $path);
                    break;
                default:
                    $this->createErrorlist('ER02015', $key, $path);
                    break;
            }
        }
    }

    public function getlist()
    {
        $req_time = date("Y-m-d H:i:s");
        $from_date = $this->jsonArray['from_date'];
        $to_date = $this->jsonArray['to_date'];
        require_once MODEL . 'merchant/ReportModel.php';
        $transactionModel = new ReportModel();
        require_once CONTROLLER . 'Paymentvalidator.php';

        $array1 = $this->getInvoiceListJson();
        $this->compairJsonArray($array1, $this->jsonArray);

        $_POST['from_date'] = $from_date;
        $_POST['to_date'] = $to_date;
        $_POST['to_cdate'] = $to_date;

        $validator = new Paymentvalidator($transactionModel);
        $validator->validatePaymentReceivedAPI();
        $hasErrors = $validator->fetchErrors();
        if ($hasErrors == FALSE) {
            $datetime1 = new DateTime($from_date);
            $datetime2 = new DateTime($to_date);
            $interval = $datetime1->diff($datetime2);
            $diff = $interval->format('%a');
            if ($diff > 31 && $this->jsonArray['customer_code'] == '') {
                return $this->printJsonResponse($req_time, 'ER02010');
            }
            try {
                $customer_id = 0;
                $status = '';
                $where = '';
                if ($this->jsonArray['customer_code'] != '') {
                    $customer_id = $this->common->getRowValue('customer_id', 'customer', 'customer_code', $this->jsonArray['customer_code'], 0, " and merchant_id='" . $this->merchant_id . "'");
                    if ($customer_id == FALSE) {
                        $customer_id = 0;
                    }
                }
                if ($this->jsonArray['group'] != '') {
                    $group_id = $this->common->getRowValue('group_id', 'customer_group', 'group_name', $this->jsonArray['group'], 1, " and merchant_id='" . $this->merchant_id . "'");
                    if ($group_id > 0) {
                        $where = " where customer_group like ~%" . '{' . $group_id . '}' . '%~';
                    }
                }
                $data = $this->getFilterData();
                $franchise_id = ($this->jsonArray['franchise_id'] > 0) ? $this->jsonArray['franchise_id'] : 0;
                $result = $transactionModel->getReportInvoiceDetail($this->merchant_id, $from_date, $to_date, 1, '', $customer_id, $data['status'], $data['filter'], NULL, 0, $franchise_id, 0, $where);
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[A0010]Error while get payment request details payment request id:[' . $payment_req_id . '] Error:' . $e->getMessage());
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }
        } else {
            foreach ($hasErrors as $key => $value) {
                $path = ($path_ != NULL) ? $path_ . "." . $key : $key;
                switch ($key) {
                    case 'from_date':
                        $this->createErrorlist('ER02002', $key, $path);
                        break;
                    case 'to_date':
                        $this->createErrorlist('ER02002', $key, $path);
                        break;
                    case 'to_cdate':
                        $this->createErrorlist('ER02009', $key, $path);
                        break;
                }
            }
        }

        $report = $result;
        $result = array();
        $int = 0;
        foreach ($report as $row) {
            $group = 'All';
            if ($row['customer_group'] != '["{0}"]') {
                $group_array = json_decode($row['customer_group'], 1);
                foreach ($group_array as $grp) {
                    if ($grp != '{0}') {
                        $grp = str_replace('{', '', $grp);
                        $id = str_replace('}', '', $grp);
                        $group_name = $this->common->getRowValue('group_name', 'customer_group', 'group_id', $id, 1, " and merchant_id='" . $this->merchant_id . "'");

                        $group .= ',' . $group_name;
                    }
                }
            }

            $result[$int]['invoice_id'] = $row['invoice_id'];
            $result[$int]['invoice_number'] = $row['invoice_number'];
            $result[$int]['bill_date'] = substr($row['bill_date'], 0, 10);
            $result[$int]['due_date'] = substr($row['due_date'], 0, 10);
            $result[$int]['sent_date'] = $row['sent_date'];
            $result[$int]['customer_code'] = $row['customer_code'];
            $result[$int]['customer_name'] = $row['customer_name'];
            $result[$int]['customer_group'] = $group;
            $result[$int]['email'] = $row['__Email'];
            $result[$int]['mobile'] = $row['__Mobile'];
            $result[$int]['address'] = $row['__Address'];
            $result[$int]['city'] = $row['__City'];
            $result[$int]['state'] = $row['__State'];
            $result[$int]['zipcode'] = $row['__Zipcode'];
            $result[$int]['amount'] = $row['invoice_amount'];
            $result[$int]['paid_amount'] = $row['paid_amount'];
            $result[$int]['status'] = $row['status'];
            $result[$int]['cycle_name'] = $row['cycle_name'];
            $result[$int]['franchise_id'] = $row['franchise_id'];
            $result[$int]['franchise_name'] = $row['__Franchise_name'];
            $result[$int]['created_by'] = $row['__Created_by'];
            $int++;
        }

        return $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist, 1);
    }

    public function detail()
    {
        $req_time = date("Y-m-d H:i:s");
        $invoice_id = $this->jsonArray['invoice_id'];
        $array1 = $this->getInvoiceDetailJson();
        $this->compairJsonArray($array1, $this->jsonArray);
        $info = $this->common->getPaymentRequestDetails($invoice_id, $this->merchant_id);
        $particular_column = $this->common->getRowValue('particular_column', 'invoice_template', 'template_id', $info['template_id']);
        $particular_column = json_decode($particular_column, 1);
        $plugin = json_decode($info['plugin_value'], 1);
        if ($info['message'] != 'success') {
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
        }

        switch ($info['payment_request_status']) {
            case 0:
                $inv_status = 'Submitted';
                break;
            case 1:
                $inv_status = 'Paid online';
                break;
            case 2:
                $inv_status = 'Paid offline';
                break;
            case 3:
                $inv_status = 'Rejected';
                break;
            case 4:
                $inv_status = 'Failed';
                break;
            case 5:
                $inv_status = 'Initiated';
                break;
            case 7:
                $inv_status = 'Partialy paid';
                break;
        }

        require_once CONTROLLER . 'InvoiceWrapper.php';
        $invoice = new InvoiceWrapper($this->common);
        $smarty = $invoice->asignSmarty($info, null, $invoice_id);
        $result['invoice_id'] = $info['payment_request_id'];
        $result['invoice_number'] = $info['invoice_number'];
        $result['bill_date'] = $info['bill_date'];
        $result['due_date'] = $info['due_date'];
        $result['cycle_name'] = $info['cycle_name'];
        $result['customer_code'] = $info['customer_code'];
        $result['customer_name'] = $info['customer_name'];
        $result['email'] = $info['customer_email'];
        $result['mobile'] = $info['customer_mobile'];
        $result['address'] = $info['customer_address'];
        $result['city'] = $info['customer_city'];
        $result['zipcode'] = $info['customer_zip'];
        $result['state'] = $info['customer_state'];
        $result['narrative'] = $info['narrative'];
        $result['absolute_cost'] = $info['absolute_cost'];
        $result['paid_amount'] = $info['paid_amount'];
        $result['advance'] = $info['advance'];
        $result['previous_due'] = $info['previous_due'];
        $result['late_fee'] = $info['late_fee'];
        $result['franchise_id'] = $info['franchise_id'];
        $result['short_url'] = $info['short_url'];
        $result['invoice_status'] = $inv_status;
        $p_int = 0;
        $particular = array();
        foreach ($smarty['particular'] as $row) {
            foreach ($particular_column as $pckey => $pcvalue) {
                if ($pckey != 'sr_no') {
                    $particular[$p_int][$pckey] = $row[$pckey];
                }
            }
            $p_int++;
        }

        $p_int = 0;
        $tax = array();
        foreach ($smarty['tax'] as $row) {
            $tax[$p_int]['tax_name'] = $row['tax_name'];
            $tax[$p_int]['percentage'] = $row['tax_percent'];
            $tax[$p_int]['applicable_on'] = $row['applicable'];
            $tax[$p_int]['amount'] = $row['tax_amount'];
            $tax[$p_int]['narrative'] = '';
            $p_int++;
        }

        $deduct = array();
        if ($plugin['has_deductible'] == 1) {
            $deduct = $plugin['deductible'];
        }

        $p_int = 0;
        $custom = array();
        foreach ($smarty['header'] as $row) {
            $custom[$p_int]['column_name'] = $row['column_name'];
            $custom[$p_int]['value'] = $row['value'];
            $p_int++;
        }

        $tnc = array();
        foreach ($smarty['tnc'] as $row) {
            $tnc[] = $row['column_name'];
        }

        $result['custom_column'] = $custom;
        $result['particular'] = $particular;
        $result['tax'] = $tax;
        $result['deductable'] = $deduct;
        $result['tnc'] = $tnc;

        return $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist);
    }

    function timestamp()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            try {
                $timestamp = $this->common->getRowValue('max(last_update_date)', 'payment_request', 'merchant_id', $this->merchant_id);
                $timestamp2 = $this->common->getRowValue('max(last_update_date)', 'payment_transaction', 'merchant_id', $this->merchant_id);
                $success = array('invoice_timestamp' => $timestamp, 'transaction_timestamp' => $timestamp2);
                return $this->printJsonResponse($req_time, 0, $success, $this->errorlist);
            } catch (Exception $e) {
                Sentry\captureException($e);
                SwipezLogger::warn(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0010]Error while get payment request details payment request id:[' . $payment_req_id . '] Error:' . $e->getMessage());
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0008+1]Error while delete invoice  Error: for merchant [' . $this->user_id . '] and for invoice id [' . $payment_req_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function getFilterData()
    {
        switch ($this->jsonArray['invoice_status']) {
            case 'Submitted':
                $status = 0;
                break;
            case 'Failed':
                $status = 4;
                break;
            case 'Paid online':
                $status = 1;
                break;
            case 'Paid offline':
                $status = 2;
                break;
            case 'Rejected':
                $status = 3;
                break;
            case 'Initiated':
                $status = 5;
                break;
            default:
                $status = '';
                break;
        }
        $data['status'] = $status;

        switch ($this->jsonArray['filter_by']) {
            case 'bill_date':
                $filter = 'bill_date';
                break;
            case 'due_date':
                $filter = 'due_date';
                break;
            case 'sent_date':
                $filter = 'created_date';
                break;
            default:
                $filter = 'bill_date';
                break;
        }
        $data['filter'] = $filter;
        return $data;
    }
}
