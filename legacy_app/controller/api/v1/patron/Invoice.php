<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Invoice extends Api {

    Private $errorlist = NULL;
    private $version = 'v1';

    function __construct() {
        parent::__construct('Patron');
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

            $int = 0;
            $p_int = 0;
            $t_int = 0;
            foreach ($invoice['custom_header_fields'] as $column) {
                if ($templatecolumn['H'][$int] != $column['id']) {
                    $this->createErrorlist('ER02001', 'id', "invoice[$srNo].custom_header_fields[$int].id");
                }

                $invoice['newvalues'][] = $column['value'];
                $invoice['ids'][] = $column['id'];
                if (ucwords($column['type']) == 'Primary') {
                    $invoice['user_code'] = $column['value'];
                }
                $int++;
            }
            $particular_total = 0;
            $tax_total = 0;
            foreach ($invoice['particular_rows'] as $column) {
                if ($templatecolumn['PF'][$p_int] != $column['id'] && $column['id'] != '') {
                    $this->createErrorlist('ER02001', 'id', "invoice[$srNo].particular_rows[$p_int].id");
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
                    $this->createErrorlist('ER02001', 'id', "invoice[$srNo].tax_rows[$t_int].id");
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
                $invoice['first_name'] = $_POST['patron_name'];
                $invoice['last_name'] = '';
            }
            return $invoice;
        } catch (Exception $e
        ) {
            
SwipezLogger::error(__CLASS__, '[A0001]Error while createing post from Json reqest' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function pay() {
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
                
SwipezLogger::error(__CLASS__, '[A0002]Error decrept template Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }
            require_once MODEL . 'merchant/TemplateModel.php';
            $templateModel = new TemplateModel();

            require_once MODEL . 'merchant/InvoiceModel.php';
            $invoiceModel = new InvoiceModel();

            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($templateModel);

            try {
                $info = $templateModel->getTemplateInfo($template_id);
            } catch (Exception $e) {
Sentry\captureException($e);
                
SwipezLogger::error(__CLASS__, '[A0003]Error get template info Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }

            if (empty($info)) {
SwipezLogger::error(__CLASS__, '[A0003-1]Error get template info Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']');
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }

            $rows = $templateModel->getTemplateBreakup($template_id);
            if (empty($rows)) {
SwipezLogger::error(__CLASS__, '[A0004]Error get template rows Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            }


            $array1 = $this->getTemplateJson($this->version, $info, $rows, 'template_id');
            $this->compairJsonArray($array1, $this->jsonArray);


            $templatecolumn = array();
            foreach ($rows as $row) {
                if ($row['save_table_name'] == 'metadata' && $row['column_type'] != 'PT' && $row['column_type'] != 'TT') {
                    $templatecolumn[$row['column_type']][] = $row['column_id'];
                    if ($info['template_type'] == 'society' && $row['column_position'] == 10) {
                        $templatecolumn['previous_dues_id'] = $row['column_id'];
                    }
                }
            }


            if ($this->user_id == $info['user_id']) {
                $template_type = $info['template_type'];
                $srNo = 0;
                foreach ($this->jsonArray['invoice'] as $invoice) {
                    $_POST = $this->createPost($srNo, $invoice, $template_type, $templatecolumn);
                    $validator->validateInvoice($this->user_id);
                    $hasErrors = $validator->fetchErrors();
                    if ($hasErrors == false) {
                        $POST_array[] = $_POST;
                    } else {
                        $this->handleValidationError($hasErrors, "invoice[$srNo]");
                    }
                    $srNo++;
                }

                if ($this->errorlist == NULL) {
                    $success = $this->save_invoice($POST_array, $template_id, $invoiceModel);
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
            
SwipezLogger::error(__CLASS__, '[A0006]Error while sending payment request Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function save_invoice($POST_array, $template_id, $invoiceModel) {
        foreach ($POST_array as $_POST) {
            $billdate = new DateTime($_POST['bill_date']);
            $duedate = new DateTime($_POST['due_date']);
            $result = $invoiceModel->saveInvoice($_POST['invoice_number'], $template_id, $this->user_id, $_POST['first_name'], $_POST['last_name'], $_POST['email_id'], $_POST['mobile'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $_POST['bill_cycle_name'], $_POST['newvalues'], $_POST['ids'], $_POST['narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['particular_total'], $_POST['tax_total'], $_POST['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $_POST['supplier'], $_POST['landline'], $_POST ['late_fee'], $this->user_id, $_POST['landline'], $_POST['user_code']);
            SwipezLogger::info(__CLASS__, 'Payment Invoice save successfully Invoice id:' . $result['request_id'] . ' by patron email id:' . $_POST['email_id'] . ' merchant id:' . $this->user_id);
            header('Location:/patron/paymentrequest/view/' . $this->encrypt->encode($result['request_id']));
            exit;
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
            }
        }
    }

}
