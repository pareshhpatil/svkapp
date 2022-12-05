<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
require_once MODEL . 'merchant/TaxModel.php';

use App\Jobs\InvoiceCreationViaXway;
use App\Jobs\EInvoiceCreation;
use App\Model\Product;
use App\Http\Controllers\UppyFileUploadController;
use App\Model\ProductAttribute;
use App\Model\StockLedger;
use App\Http\Controllers\ProductController;
use App\Model\ProductAttributeValue;
use Illuminate\Support\Facades\Storage;

class Invoice extends Api
{
    private $errorlist = NULL;
    private $tax_model = null;

    function __construct()
    {
        parent::__construct('Merchant');
        $this->version = 'v3';
        $this->tax_model = new TaxModel();
    }

    /**
     * create invoice saved
     */
    function createPost($srNo, $invoice, $template_type, $templatecolumn)
    {
        try {

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

            if (isset($invoice['profile_id'])) {
                $invoice['profile_id'] = ($invoice['profile_id'] > 0) ? $invoice['profile_id'] : 0;
            } else {
                $invoice['profile_id'] = 0;
            }

            if (isset($invoice['advance_received'])) {
                $invoice['advance'] = ($invoice['advance_received'] > 0) ? $invoice['advance_received'] : 0;
            } else {
                $invoice['advance'] = 0;
            }
            if (isset($invoice['enable_einvoice'])) {
                $invoice['enable_einvoice'] = ($invoice['enable_einvoice'] > 0) ? $invoice['enable_einvoice'] : 0;
                $invoice['send_einvoice_to_customer'] = ($invoice['send_einvoice_to_customer'] > 0) ? $invoice['send_einvoice_to_customer'] : 0;
            } else {
                $invoice['enable_einvoice'] = 0;
            }

            $invoice['franchise_id'] = (isset($invoice['franchise_id'])) ? $invoice['franchise_id'] : '0';
            $invoice['bill_cycle_name'] = ($invoice['bill_cycle_name'] != '') ? $invoice['bill_cycle_name'] : '';
            $invoice['late_fee'] = ($invoice['latefee'] > 0) ? $invoice['latefee'] : 0;
            $invoice['notify_patron'] = ($invoice['invoice_properties']['notify_patron'] == 0) ? 0 : 1;
            $invoice['product_taxation_type'] =  (isset($invoice['invoice_properties']['product_taxation_type'])) ? $invoice['invoice_properties']['product_taxation_type'] : '1';
            $invoice['currency'] = (isset($invoice['currency'])) ? $invoice['currency'] : 'INR';
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
            if (isset($invoice['vehicle_section'])) {
                $invoice['particular_rows'] = $invoice['vehicle_section'];
                unset($invoice['vehicle_section']);
            }
            foreach ($invoice['particular_rows'] as $column) {
                $invoice['particular_id'][] = 0;
                if (isset($column['id'])) {
                    if ($template_type == 'isp') {
                        $invoice['item'][] = $column['description'];
                    } else {
                        $invoice['item'][] = $column['name'];
                    }
                    if (isset($column['discount_perc'])) {
                        $invoice['discount_perc'][] = $column['discount_perc'];
                    }
                    if (isset($column['discount'])) {
                        $invoice['discount'][] = $column['discount'];
                    }
                    if (isset($column['price'])) {
                        $invoice['rate'][] = $column['price'];
                        $amount = $column['price'];
                        if ($column['quantity'] > 0) {
                            $invoice['qty'][] = $column['quantity'];
                            $amount = $amount * $column['quantity'];
                        }
                    } else {
                        $amount = $column['amount'];
                    }
                    if (isset($column['annual_recurring_charges'])) {
                        $invoice['annual_recurring_charges'][] = $column['annual_recurring_charges'];
                    }
                    if (isset($column['time_period'])) {
                        $invoice['description'][] = $column['time_period'];
                    }
                    $invoice['total_amount'][] = $amount;
                    $invoice['narrative'][] = $column['narrative'];
                } else {
                    foreach ($column as $col_key => $col_val) {
                        if ($col_key == 'total_amount') {
                            $amount = $col_val;
                        }
                        $invoice[$col_key][] = $col_val;
                    }
                }
                $particular_total = $particular_total + $amount;
                $p_int++;
            }

            foreach ($invoice['tax_rows'] as $column) {
                if ($column['percentage'] > 0) {
                    $tax_id = $this->tax_model->getTaxid($column['name'], $column['percentage'], '', 0, $this->merchant_id, $this->user_id);
                    $invoice['tax_id'][] = $tax_id;
                    $invoice['tax_detail_id'][] = 0;
                    $invoice['tax_name'][] = $column['name'];
                    $invoice['tax_percent'][] = $column['percentage'];
                    $invoice['tax_applicable'][] = $column['applicable_on'];
                    $total = ($column['percentage'] * $column['applicable_on']) / 100;
                    $tax_total = $tax_total + $total;
                    $invoice['tax_amt'][] = $total;
                    $t_int++;
                }
            }

            foreach ($invoice['deduct_rows'] as $column) {
                $invoice['deduct_tax'][] = $column['name'];
                $invoice['deduct_percent'][] = $column['percentage'];
                $invoice['deduct_applicable'][] = $column['applicable_on'];
                $total = ($column['percentage'] * $column['applicable_on']) / 100;
                $invoice['deduct_total'][] = $total;
                $d_int++;
            }
            $invoice['totalcost'] = $particular_total;
            $invoice['totaltax'] = $tax_total;

            $invoice['newvalues'] = (isset($invoice['newvalues'])) ? $invoice['newvalues'] : array();
            $invoice['ids'] = (isset($invoice['ids'])) ? $invoice['ids'] : array();
            $invoice['supplier'] = array();
            $invoice['invoice_narrative'] = '';
            $invoice['previous_dues'] = (isset($invoice['previous_dues']) && $invoice['previous_dues'] != '') ? $invoice['previous_dues'] : 0;
            $invoice['is_partial'] = ($invoice['enable_partial_payment'] == 1) ? 1 : 0;
            $invoice['partial_min_amount'] = ($invoice['partial_min_amount'] > 0) ? $invoice['partial_min_amount'] : 0;

            return $invoice;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0001]Error while createing post from Json reqest' . $e->getMessage());
            $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
            die();
        }
    }

    function save()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            try {
                $template_id = $this->encrypt->decode($this->jsonArray['template_id']);
                if (strlen($template_id) != 10) {
                    $template_id = $this->common->getRowValue('value', 'encrypt_backup', 'encrypt_key', $this->jsonArray['template_id'], 0);
                    if ($template_id == FALSE) {
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0002]Error decrept template Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
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
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }

            if (empty($info)) {

                SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0003-1]Error get template info Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']');
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }


            $rows = $this->common->getListValue('invoice_column_metadata', 'template_id', $template_id, 1, ' order by sort_order,column_id');

            if (empty($rows)) {

                SwipezLogger::error(__CLASS__, '[A0004]Error get template rows Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            }

            $plugin = json_decode($info['plugin'], 1);
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


            if ($this->merchant_id == $info['merchant_id']) {
                $template_type = $info['template_type'];
                $srNo = 0;
                foreach ($this->jsonArray['invoice'] as $invoice) {
                    $_POST = $this->createPost($srNo, $invoice, $template_type, $templatecolumn);
                    $_POST['franchise_name_invoice'] = $plugin['franchise_name_invoice'];
                    require_once CONTROLLER . 'InvoiceWrapper.php';
                    $invoice = new InvoiceWrapper($this->common);
                    $invoicevalues = $invoice->getTemplateBreakup($template_id);
                    $bill_date_col = 5;
                    $due_date_col = 6;
                    require_once PACKAGE . 'swipez/function/DataFunction.php';
                    $mhf = count($_POST['main_header_fields']);
                    $int = ($mhf > 0) ? $mhf : 0;
                    foreach ($invoicevalues['header'] as $column) {
                        $exit = 0;
                        if ($column['function_id'] > 0) {
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
                            } elseif ($column['function_id'] == 4) {
                                $mapping_details = $this->common->getfunctionMappingDetails($column['column_id'], $column['function_id']);
                                if ($mapping_details['param'] == 'auto_calculate') {
                                    $previous_dues = $this->common->getRowValue('balance', 'customer', 'customer_code', $_POST['customer_code'], 1, " and merchant_id='" . $this->merchant_id . "'");
                                    $_POST['newvalues'][] = $previous_dues;
                                    $_POST['ids'][] = $column['column_id'];
                                    $_POST['previous_dues'] = $previous_dues;
                                    $exit = 1;
                                } else {
                                    $key = array_search($column['column_id'], $_POST['ids']);
                                    $_POST['previous_dues'] = $_POST['newvalues'][$key];
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

                    $currency = $this->common->getRowValue('currency', 'merchant_setting', 'merchant_id', $this->merchant_id);

                    $validator->validateAPIInvoice($this->merchant_id, $this->user_id, null, json_decode($currency, 1));
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


                        $res = $this->validateCustomer($srNo, $this->jsonArray['invoice'][$srNo]['new_customer'], $customer_id);

                        $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);

                        if ($customer_id == FALSE && $_POST['email'] != '' && $merchant_setting['customer_auto_generate'] == 1) {
                            $data = $customerModel->existCustomerDetail($this->merchant_id, $_POST['first_name'], $_POST['state'], $_POST['email'], $_POST['mobile'], $_POST['gst_number']);
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
                        $POST_array[] = $_POST;
                    } else {
                        $this->handleValidationError($hasErrors, "invoice[$srNo]");
                    }
                    $srNo++;
                }

                $res = $this->common->isValidPackageInvoice($this->merchant_id, count($POST_array), 'indi');
                if ($res == false) {
                    SwipezLogger::info(__CLASS__, '[E285-1]Error while validating package invoice count for merchant [' . $this->merchant_id . ']');
                    require_once UTIL . 'SupportNotification.php';
                    $supportNotification = new SupportNotification();
                    $subject = 'Package expired merchant still trying to use platform ' . $this->merchant_id;
                    $message = 'Merchant id : ' . $this->merchant_id . '<br> Activity : API invocation of creating invoices';
                    $supportNotification->notificationJob($subject, $message, 'SUPPORT');
                    return $this->printJsonResponse($req_time, 'ER02032');
                    $this->errorlist = array();
                }

                if ($this->errorlist == NULL) {
                    $success = $this->save_invoice($POST_array, $template_id, $invoiceModel);
                    /*  Darshana
                        check if seetlement block is exist in save request then get request id and form settle api request 
                        and call settle method
                    */
                    foreach ($POST_array as $sk => $sval) {
                        if (isset($sval['settlement']) && !empty($sval['settlement'])) {
                            $sval['settlement']['invoice_id'] = $success[$sk]['invoice_id'];
                            //call settle method
                            $this->jsonArray = $sval['settlement'];
                            $settleResponse = $this->settle();
                            $arr = json_decode($settleResponse, 1);
                            $success[$sk]['settlement'] = $arr;
                        }
                    }
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

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while sending payment request Error: for merchant [' . $this->merchant_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01007');
        }
    }

    function validateCustomer($srNo, $data, $customer_id = null)
    {
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
                $data_type = $this->common->getRowValue('column_datatype', 'customer_column_metadata', 'column_id', $data['custom_fields'][$int]['id']);
                if ($data_type == 'gst') {
                    $_POST['gst_number'] = $data['custom_fields'][$int]['value'];
                } elseif ($data_type == 'password') {
                    $_POST['password'] = $data['custom_fields'][$int]['value'];
                } elseif ($data_type == 'company_name') {
                    $_POST['company_name'] = $data['custom_fields'][$int]['value'];
                }
                if ($data_type != 'company_name') {
                    $_POST['column_id'][] = $data['custom_fields'][$int]['id'];
                    $_POST['column_value'][] = $data['custom_fields'][$int]['value'];
                }
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

    function save_Customer($Customer_model)
    {

        $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
        if ($merchant_setting['customer_auto_generate'] == 1) {
            $customer_code = $Customer_model->getCustomerCode($this->merchant_id);
            $_POST['customer_code'] = $customer_code;
        } else {
            $customer_code = $_POST['customer_code'];
        }

        $column_id = (empty($_POST['column_id'])) ? array() : $_POST['column_id'];
        $column_value = (empty($_POST['column_value'])) ? array() : $_POST['column_value'];
        $_POST['gst_number'] = (isset($_POST['gst_number'])) ? $_POST['gst_number'] : '';
        $_POST['password'] = (isset($_POST['password'])) ? $_POST['password'] : '';
        $_POST['company_name'] = (isset($_POST['company_name'])) ? $_POST['company_name'] : '';
        $_POST['country'] = isset($_POST['country']) ? $_POST['country'] : 'India';

        $row = $Customer_model->saveCustomer($this->user_id, $this->merchant_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $column_id, $column_value, 0, $_POST['password'], $_POST['gst_number'], $_POST['company_name'],$_POST['country']);
        if ($row['message'] == 'success') {
            return $row;
        } else {

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving customer Error: for user [' . $this->user_id . '] and for error [' . json_encode($row) . ']');
            return FALSE;
        }
    }

    function update_Customer($Customer_model, $customer_id, $customer_code)
    {
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
        $_POST['country'] = isset($_POST['country']) ? $_POST['country'] : 'India';
        
        $row = $Customer_model->updateCustomer($this->user_id, $customer_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $newcolumn_id, $newcolumn_value, $excolumn_id, $excolumn_value, '', '', $_POST['company_name'],$_POST['country']);
        if ($row['message'] == 'success') {
            return $row;
        } else {

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving customer Error: for user [' . $this->user_id . '] and for error [' . json_encode($row) . ']');
            return FALSE;
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
                $invoice = $this->common->getPaymentRequestDetails($invoice_id, $this->merchant_id);
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


                $rows = $this->common->getListValue('invoice_column_metadata', 'template_id', $template_id, 1, ' order by sort_order,column_id');

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

                require_once CONTROLLER . 'InvoiceWrapper.php';
                $invoice_vall = new InvoiceWrapper($this->common);
                $invoicevalues = $invoice_vall->getTemplateBreakup($rows);
                $bill_date_col = 5;
                $due_date_col = 6;
                $_POST = $this->createPost(0, $invoice, $template_type, $templatecolumn);
                require_once PACKAGE . 'swipez/function/DataFunction.php';
                $mhf = count($_POST['main_header_fields']);
                $int = ($mhf > 0) ? $mhf : 0;
                foreach ($invoicevalues['header'] as $column) {
                    $exit = 0;
                    if ($column['function_id'] > 0) {
                        if ($column['function_id'] == 9) {
                            $mapping_details = $this->common->getfunctionMappingDetails($column['column_id'], $column['function_id']);
                            if ($mapping_details['param'] == 'system_generated') {
                                $req_detail = $this->common->getSingleValue('payment_request', 'payment_request_id', $invoice_id);
                                $_POST['newvalues'][] = $req_detail['invoice_number'];
                                $_POST['invoice_number'] = $req_detail['invoice_number'];
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



                $validator->validateAPIInvoice($this->merchant_id, $this->user_id, $invoice_id);
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    $_POST['customer_id'] = $customerModel->isExistCustomerCode($this->merchant_id, $_POST['customer_code']);
                    $success = $this->update_invoice($_POST, $invoice_id, $invoiceModel);
                    $error_code = 0;
                    $this->errorlist = NULL;
                } else {
                    $this->errorlist = NULL;
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

    public function save_invoice($POST_array, $template_id, $invoiceModel)
    {
        foreach ($POST_array as $_POST) {
            try {
                $billdate = new DateTime($_POST['bill_date']);
                $duedate = new DateTime($_POST['due_date']);
            } catch (Exception $e) {
                Sentry\captureException($e);
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02002');
            }

            $last_payment = ($_POST['last_payment'] > 0) ? $_POST['last_payment'] : 0;
            $adjustment = ($_POST['adjustment'] > 0) ? $_POST['adjustment'] : 0;
            $_POST['late_fee'] = ($_POST['late_fee'] > 0) ? $_POST['late_fee'] : 0;
            $_POST['advance'] = ($_POST['advance'] > 0) ? $_POST['advance'] : 0;

            $franchise_id = ($_POST['franchise_id'] > 0) ? $_POST['franchise_id'] : 0;
            $vendor_id = ($_POST['vendor_id'] > 0) ? $_POST['vendor_id'] : 0;
            $_POST['franchise_name_invoice'] = ($_POST['franchise_name_invoice'] == 0) ? 0 : 1;
            $amount = $_POST['totalcost'] + $_POST['totaltax'] + $_POST['previous_dues'] - $last_payment - $adjustment;

            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $plugin_value = $invoice->setInvoicePluginValues($template_id, 'api');
            $plugin_array = json_decode($plugin_value, true);
            $billing_profile_id = $plugin_array['profile_id'];
            unset($plugin_array['profile_id']);
            $plugin_value = json_encode($plugin_array);

            if ($_POST['profile_id'] > 0) {
                $billing_profile_id = $_POST['profile_id'];
            }

            $travels = array();
            $travel_particular = array('travel_section' => 'b', 'travel_cancel_section' => 'c', 'hotel_section' => 'hb', 'facility_section' => 'fs');
            foreach ($travel_particular as $tpk => $tpc) {
                if (isset($_POST[$tpk])) {

                    foreach ($_POST[$tpk] as $ktp => $tpcc) {
                        if (isset($tpcc['from_date'])) {
                            $tpcc['booking_date'] = $tpcc['from_date'];
                        }
                        if (isset($tpcc['from_date'])) {
                            $tpcc['journey_date'] = $tpcc['to_date'];
                        }
                        if (isset($tpcc['item'])) {
                            $tpcc['name'] = $tpcc['item'];
                        }
                        $travels['texistid'][] = 0;
                        $travels['btype'][] = $tpc;
                        $travels['booking_date'][] = isset($tpcc['booking_date']) ? $tpcc['booking_date'] : '2014-01-01';
                        $travels['journey_date'][] = isset($tpcc['journey_date']) ? $tpcc['journey_date'] : '2014-01-01';
                        $travels['name'][] = isset($tpcc['name']) ? $tpcc['name'] : '';
                        $travels['type'][] = isset($tpcc['type']) ? $tpcc['type'] : '';
                        $travels['unit_type'][] = isset($tpcc['unit_type']) ? $tpcc['unit_type'] : '';
                        $travels['sac_code'][] = isset($tpcc['sac_code']) ? $tpcc['sac_code'] : '';
                        $travels['from'][] = isset($tpcc['from']) ? $tpcc['from'] : '';
                        $travels['to'][] = isset($tpcc['to']) ? $tpcc['to'] : '';
                        $travels['amt'][] = isset($tpcc['amount']) ? $tpcc['amount'] : '0';
                        $travels['charge'][] = isset($tpcc['charge']) ? $tpcc['charge'] : '0';
                        $travels['unit'][] = isset($tpcc['qty']) ? $tpcc['qty'] : '1';
                        $travels['rate'][] = isset($tpcc['rate']) ? $tpcc['rate'] : '0';
                        $travels['gst'][] = isset($tpcc['gst']) ? $tpcc['gst'] : '0';
                        $travels['discount_percent'][] = isset($tpcc['discount_percent']) ? $tpcc['discount_percent'] : '0';
                        $travels['discount'][] = isset($tpcc['discount']) ? $tpcc['discount'] : '0';
                        $total_amount = ($tpcc['total_amount'] > 0) ? $tpcc['total_amount'] : 0;
                        $travels['total_amount'][] = $total_amount;
                        $travels['description'][] = isset($tpcc['description']) ? $tpcc['description'] : '';
                        $travels['information'][] = isset($tpcc['information']) ? $tpcc['information'] : '';
                        if ($tpc == 'c') {
                            $_POST['totalcost'] = $_POST['totalcost'] - $total_amount;
                        } else {
                            $_POST['totalcost'] = $_POST['totalcost'] + $total_amount;
                        }
                    }
                }
            }
            $payment_request_type = (isset($_POST['payment_request_type'])) ? $_POST['payment_request_type'] : 1;
            $parent_request_id =  (isset($_POST['parent_request_id'])) ? $_POST['parent_request_id'] : 0;

            $result = $invoiceModel->saveInvoice($this->merchant_id, $this->user_id, $_POST['customer_id'], $template_id, $_POST['invoice_number'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $_POST['expiry_date'], $_POST['bill_cycle_name'], $_POST['newvalues'], $_POST['ids'], $_POST['invoice_narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['previous_dues'], $last_payment, $adjustment, $_POST['late_fee'], $_POST['advance'], $_POST['notify_patron'], 0, $franchise_id, $vendor_id, $this->user_id, 1, $payment_request_type, 0, 0, $plugin_value, $billing_profile_id, 1, $parent_request_id, $_POST['currency'], null,  $_POST['product_taxation_type']);
            if ($result['message'] == 'success') {
                $invoiceModel->saveInvoiceParticular($result['request_id'], 0, $_POST['particular_id'], $_POST['item'], $_POST['annual_recurring_charges'], $_POST['sac_code'], $_POST['description'], $_POST['qty'], $_POST['unit_type'], $_POST['rate'], $_POST['gst'], $_POST['tax_amount'], $_POST['discount_perc'], $_POST['discount'], $_POST['total_amount'], $_POST['narrative'], $this->user_id, $this->merchant_id, 0, 0, $_POST['mrp'], $_POST['product_expiry_date'], $_POST['product_number']);
                $invoiceModel->saveInvoiceTax($result['request_id'], $_POST['tax_id'], $_POST['tax_percent'], $_POST['tax_applicable'], $_POST['tax_amt'], $_POST['tax_detail_id'], $this->user_id, 0);
                if (!empty($travels)) {
                    $invoiceModel->saveTravelDetails(
                        $result['request_id'],
                        $travels['texistid'],
                        $travels['btype'],
                        $travels['booking_date'],
                        $travels['journey_date'],
                        $travels['name'],
                        $travels['unit_type'],
                        $travels['sac_code'],
                        $travels['type'],
                        $travels['from'],
                        $travels['to'],
                        $travels['amt'],
                        $travels['charge'],
                        $travels['unit'],
                        $travels['rate'],
                        $travels['discount_percent'],
                        $travels['discount'],
                        $travels['gst'],
                        $travels['total_amount'],
                        $travels['description'],
                        $travels['informtion'],
                        $this->user_id
                    );
                }
                $short_url = '';
                if ($this->webrequest == true) {
                    $short_url = $this->saveShortUrl($result['request_id']);
                }
                if ($_POST['enable_einvoice'] == 1) {
                    $notify = ($_POST['send_einvoice_to_customer'] == 1) ? 1 : 0;
                    EInvoiceCreation::dispatch($result['request_id'], 'Swipez API', $notify)->onQueue(env('SQS_EINVOICE_QUEUE'));
                }
                $customer_det = $this->common->getSingleValue('customer', 'customer_id', $_POST['customer_id']);
                $success[] = array('invoice_id' => $result['request_id'], 'code' => $customer_det['customer_code'], 'bill_date' => $billdate->format('Y-m-d'), 'patron_name' => $customer_det['first_name'] . ' ' . $customer_det['last_name'], 'email_id' => $customer_det['email'], 'mobile' => $customer_det['mobile'], 'amount' => $this->roundAmount($amount - $_POST['advance'], 2), 'short_url' => $short_url);
            } else {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
            }
        }

        return $success;
    }

    function handleExistingValues($payment_request_id, $newvalues, $ids)
    {
        $rows = $this->common->getListValue('invoice_column_values', 'payment_request_id', $payment_request_id, 1);
        $exist_value = array();
        $exist_id = array();
        foreach ($rows as $row) {
            if (in_array($row['column_id'], $ids)) {
                $key = array_search($row['column_id'], $ids);
                $exist_id[] = $row['invoice_id'];
                $exist_value[] = $newvalues[$key];
                unset($newvalues[$key]);
                unset($ids[$key]);
            }
        }
        return array('newvalues' => $newvalues, 'ids' => $ids, 'exist_value' => $exist_value, 'exist_id' => $exist_id);
    }

    public function update_invoice($post, $invoice_id, $invoiceModel)
    {
        $billdate = new DateTime($post['bill_date']);
        $duedate = new DateTime($post['due_date']);
        $last_payment = ($_POST['last_payment'] > 0) ? $_POST['last_payment'] : 0;
        $adjustment = ($_POST['adjustment'] > 0) ? $_POST['adjustment'] : 0;
        $franchise_id = ($_POST['franchise_id'] > 0) ? $_POST['franchise_id'] : 0;
        $vendor_id = ($_POST['vendor_id'] > 0) ? $_POST['vendor_id'] : 0;
        $amount = $post['totalcost'] + $post['totaltax'] + $post['previous_dues'] - $last_payment - $adjustment;
        $exist_array = $this->handleExistingValues($invoice_id, $post['newvalues'], $post['ids']);

        $request = $this->common->getSingleValue('payment_request', 'payment_request_id', $invoice_id, 0, " and merchant_id='" . $this->merchant_id . "'");

        require_once CONTROLLER . 'InvoiceWrapper.php';
        $invoice = new InvoiceWrapper($this->common);
        $plugin_value = $invoice->setInvoicePluginValues($request['template_id'], 'api');
        $result = $invoiceModel->updateInvoice($invoice_id, $this->user_id, $_POST['customer_id'], $_POST['invoice_number'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $_POST['expiry_date'], $_POST['bill_cycle_name'], $_POST['newvalues'], $_POST['ids'], $_POST['invoice_narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['previous_dues'], $last_payment, $adjustment, $_POST['late_fee'], $_POST['advance'], $_POST['notify_patron'], 0, $franchise_id, $vendor_id, $this->user_id, 1, 0, $plugin_value, 0, 0, 1, $_POST['currency'], null,  $_POST['product_taxation_type']);
        if ($result['message'] == 'success') {
            $invoiceModel->saveInvoiceParticular($result['request_id'], 0, $_POST['particular_id'], $_POST['item'], $_POST['annual_recurring_charges'], $_POST['sac_code'], $_POST['description'], $_POST['qty'], $_POST['unit_type'], $_POST['rate'], $_POST['gst'], $_POST['tax_amount'], $_POST['discount_perc'], $_POST['discount'], $_POST['total_amount'], $_POST['narrative'], $this->user_id, $this->merchant_id, 0, 0, $_POST['mrp'], $_POST['product_expiry_date'], $_POST['product_number']);
            $invoiceModel->saveInvoiceTax($result['request_id'], $_POST['tax_id'], $_POST['tax_percent'], $_POST['tax_applicable'], $_POST['tax_amt'], $_POST['tax_detail_id'], $this->user_id, 0);
            $short_url = $request['short_url'];
            $customer_det = $this->common->getSingleValue('customer', 'customer_id', $_POST['customer_id']);
        } else {
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
        $success = array('invoice_id' => $result['request_id'], 'code' => $customer_det['customer_code'], 'bill_date' => $billdate->format('Y-m-d'), 'patron_name' => $customer_det['first_name'] . ' ' . $customer_det['last_name'], 'email_id' => $customer_det['email'], 'mobile' => $customer_det['mobile'], 'amount' => $request['absolute_cost']);
        return $success;
    }

    function createErrorlist($code, $key_name, $key_path)
    {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }

    function settle()
    {
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
                if ($request['enable_partial_payment'] == 0) {
                    $_POST['is_partial'] = 0;
                } else {
                    if ($_POST['is_partial'] == 1) {
                        if ($request['partial_min_amount'] > $this->jsonArray['amount']) {
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
                        case 'Swipez':
                            $mode = 6;
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
                    if ($mode == 3 && isset($this->jsonArray['cod_status'])) {
                        $_POST['cod_status'] = $this->jsonArray['cod_status'];
                    }
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

                            $file_name = null;
                            if (isset($this->jsonArray['attach_invoice_pdf']) && $this->jsonArray['attach_invoice_pdf'] == 1) {
                                $link = $this->encrypt->encode($payment_req_id);
                                require_once CONTROLLER . 'patron/Paymentrequest.php';
                                $Paymentrequest = new Paymentrequest();
                                $file_name = $Paymentrequest->download($link, 1);
                            }
                            $secure->sendMailReceipt($receipt_info, 'directpay', $file_name);
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

    public function settle_payment($post, $payment_req_id, $mode, $date, $requestModel)
    {
        try {
            $post['is_partial'] = ($post['is_partial'] == 1) ? 1 : 0;
            $result = $requestModel->respond($post['amount'], $post['bank_name'], $payment_req_id, $date->format('Y-m-d'), $mode, $post['bank_transaction_no'], $post['cheque_no'], $post['cash_paid_to'], 0, 0, $this->user_id, 0, '', '', $post['is_partial'], $post['cod_status']);
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
                $result = $this->settle_payment($post, $payment_req_id, $mode, $date, $requestModel);
            }
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0012]Error while update  settle payment request Error: for payment_req_id [' . $payment_req_id . '] ' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function handleValidationError($errors, $path_ = NULL)
    {
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
                case 'customer_code':
                    $this->createErrorlist('ER02031', $key, $path);
                    break;
                case 'email':
                    $this->createErrorlist('ER02004', $key, $path);
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
                default:
                    $this->createErrorlist('ER02015', $key, $path);
                    break;
            }
        }
    }

    function getCoupon_id($coupon_det, $srNo)
    {
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
                    default:
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

    /* This function is calling from woocommerce */
    function scheduleInvoice()
    {
        try {
            //check template is valid for that merchant
            try {
                $template_id = $this->encrypt->decode($this->jsonArray['template_id']);
                if (strlen($template_id) != 10) {
                    $template_id = $this->common->getRowValue('value', 'encrypt_backup', 'encrypt_key', $this->jsonArray['template_id'], 0);
                    if ($template_id == FALSE) {
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0002]Error decrept template Error: for merchant [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01005');
            }

            require_once MODEL . 'merchant/TemplateModel.php';
            $templateModel = new TemplateModel();

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

            if ($this->merchant_id == $info['merchant_id']) {
                require_once MODEL . 'XwayModel.php';
                $xwayModel = new XwayModel();
                $auto_invoice_request_tbl_id = $xwayModel->saveAutoInvoiceApiRequest(null, $_POST['data'], $this->merchant_id);
                if ($auto_invoice_request_tbl_id != '') {
                    //InvoiceCreationViaXway::dispatch(127)->onQueue(env('SQS_INVOICE_CREATION_VIA_XWAY'));
                    //InvoiceCreationViaXway::dispatch($auto_invoice_request_tbl_id)->onQueue(env('SQS_INVOICE_CREATION_VIA_XWAY'));
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, 'Error while calling schedule invoice' . $e->getMessage());
        }
    }

    /* Delete invoice api */
    function deleteInvoice()
    {
        $req_time = date("Y-m-d H:i:s");
        $payment_req_id = $this->jsonArray['invoice_id'];
        $payment_request_status = $this->jsonArray['status'];

        if (strlen($payment_req_id) != 10) {
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
        } else {
            require_once MODEL . 'CommonModel.php';
            $common_model = new CommonModel();
            $common_model->genericupdate('payment_request', 'payment_request_status', $payment_request_status, 'payment_request_id', $payment_req_id);
            $this->common->queryexecute("select delete_ledger('" . $payment_req_id . "',1)");
            $this->common->queryexecute("call `stock_management`('" . $this->merchant_id . "','" . $payment_req_id . "',3,2);");
            $success = array('invoice_id' => $payment_req_id);

            return $this->printJsonResponse($req_time, 0, $success, $this->errorlist);
        }
    }

    /* This function is calling from woocommerce */
    function cancelRefundTransaction()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            require_once MODEL . 'CommonModel.php';
            $common_model = new CommonModel();
            $request = $this->common->getSingleValue('payment_request', 'parent_request_id', $this->jsonArray['order_id'], 0, " and merchant_id='" . $this->merchant_id . "'");

            if ($this->jsonArray['mode'] == 'wcpm_swipez') {
                if ($request != false) {
                    if ($this->jsonArray['status'] == 'cancelled') {
                        //set invoice status as cancelled
                        $this->setDeleteInvoiceData($request['payment_request_id'], 12);
                        $common_model->genericupdate('offline_response', 'is_active', 0, 'payment_request_id', $request['payment_request_id']);
                    } else if ($this->jsonArray['status'] == 'refunded') {
                        //find entry in offline resonse table
                        $offlineResponse = $this->common->getRowValue('bank_transaction_no', 'offline_response', 'payment_request_id', $request['payment_request_id'], 0, " and merchant_id='" . $this->merchant_id . "'");
                        if ($offlineResponse != false) {
                            $refundArray = $this->jsonArray;
                            $refundArray['transaction_id'] = $offlineResponse;
                            $response = $this->callRefundApi($refundArray);
                            $array = json_decode($response, 1);

                            if ($array['errmsg'] == '') {
                                $this->setDeleteInvoiceData($request['payment_request_id'], 9);
                            }
                        }
                        //set transaction status refunded
                    }
                    if ($this->jsonArray['status'] == 'cancelled' || $this->jsonArray['status'] == 'refunded') {
                        $common_model->genericupdate('offline_response', 'is_active', 0, 'payment_request_id', $request['payment_request_id'], $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
                    }
                } else {
                    //if invoice not generated
                    if ($this->jsonArray['status'] == 'refunded') {
                        $xwayResponse = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $this->jsonArray['transaction_id'], 0, " and xway_transaction_status='1' and merchant_id='" . $this->merchant_id . "'");
                        if ($xwayResponse != false) {
                            $refundArray = $this->jsonArray;
                            $refundArray['transaction_id'] = $this->jsonArray['transaction_id'];
                            $response = $this->callRefundApi($refundArray);
                        }
                    }
                }
            } else if ($this->jsonArray['mode'] == 'cod') {
                if ($request != false) {
                    if ($this->jsonArray['status'] == 'cancelled') {
                        //set invoice status as cancelled
                        $this->setDeleteInvoiceData($request['payment_request_id'], 12);
                    } else if ($this->jsonArray['status'] == 'refunded') {
                        $this->setDeleteInvoiceData($request['payment_request_id'], 9);
                    }
                    if ($this->jsonArray['status'] == 'cancelled' || $this->jsonArray['status'] == 'refunded') {
                        $common_model->genericupdate('offline_response', 'is_active', 0, 'payment_request_id', $request['payment_request_id'], $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
                    }
                }
            } else if ($this->jsonArray['mode'] != 'cod' && $this->jsonArray['mode'] != 'wcpm_swipez') {
                if ($request != false) {
                    if ($this->jsonArray['status'] == 'cancelled') {
                        //set invoice status as cancelled
                        $this->setDeleteInvoiceData($request['payment_request_id'], 12);
                    } else if ($this->jsonArray['status'] == 'refunded') {
                        $this->setDeleteInvoiceData($request['payment_request_id'], 9);
                        //update transaction status as refunded
                    }
                    if ($this->jsonArray['status'] == 'cancelled' || $this->jsonArray['status'] == 'refunded') {
                        $common_model->genericupdate('offline_response', 'is_active', 0, 'payment_request_id', $request['payment_request_id'], $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
                    }
                }
            } else {
                //mode is not recognized
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, 'Error while calling settle COD invoice from woocommerce' . $e->getMessage());
        }
    }

    public function callRefundApi($refundArray)
    {
        $response = array();
        if (!empty($refundArray)) {
            $xwayResponse = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $refundArray['transaction_id'], 0, " and xway_transaction_status='1' and merchant_id='" . $this->merchant_id . "'");
            if ($xwayResponse != false) {
                $array['access_key_id'] = $refundArray['access_key_id'];
                $array['secret_access_key'] = $refundArray['secret_access_key'];
                $array['transaction_id'] = $xwayResponse['xway_transaction_id'];
                $array['amount'] = $xwayResponse['absolute_cost'];
                $array['reason'] = 'Refund amount';
                $_POST['data'] = json_encode($array);

                require_once CONTROLLER . 'api/v1/merchant/Payment.php';
                $apiinv = new Payment(true);
                $apiinv->refund();
                $response = $apiinv->response;
                return $response;
            }
        }
        return $response;
    }

    public function setDeleteInvoiceData($payment_request_id, $status)
    {
        $deleteInvoice['invoice_id'] = $payment_request_id;
        $deleteInvoice['status'] = $status;
        $this->jsonArray = $deleteInvoice;
        $this->deleteInvoice();
    }

    /* This function is calling from woocommerce */
    function settleCOD()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            //find order_id is exist in payment_request for the merchant
            $request = $this->common->getSingleValue('payment_request', 'parent_request_id', $this->jsonArray['order_id'], 0, " and merchant_id='" . $this->merchant_id . "'");
            if ($request != false) {
                //form settle array
                //check exist entry in offline response table
                $exist_offline_response = $this->common->getSingleValue('offline_response', 'payment_request_id', $request['payment_request_id'], 0, " and bank_transaction_no='" . $this->jsonArray['order_id'] . "' and merchant_id='" . $this->merchant_id . "'");
                if ($exist_offline_response == false) {
                    $settlement['access_key_id'] = $this->jsonArray['access_key_id'];
                    $settlement['secret_access_key'] = $this->jsonArray['secret_access_key'];
                    $settlement['invoice_id'] = $request['payment_request_id'];
                    $settlement['paid_date'] = date('Y-m-d');
                    $settlement['amount'] = $request['grand_total'];
                    $settlement['mode'] = 'Cash';
                    $settlement['bank_name'] = '';
                    $settlement['bank_ref_no'] = $this->jsonArray['order_id'];
                    $settlement['cheque_no'] = '';
                    $settlement['cash_paid_to'] = '';
                    $settlement['notify'] = '1';
                    $settlement['attach_invoice_pdf'] = '1';
                    $settlement['cod_status'] = 'cash_received';

                    $this->jsonArray = $settlement;
                    $this->settle();
                } else {
                    $this->common->genericupdate('payment_request', 'payment_request_status', 2, 'payment_request_id', $request['payment_request_id']);
                    $this->common->genericupdate('offline_response', 'is_active', 1, 'payment_request_id', $request['payment_request_id']);
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, 'Error while calling settle COD invoice from woocommerce' . $e->getMessage());
        }
    }

    public function save_WC_Product()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            //find order_id is exist in payment_request for the merchant
            if (!empty($this->jsonArray)) {
                unset($this->jsonArray['access_key_id']);
                unset($this->jsonArray['secret_access_key']);
                $is_stock_managed = $this->jsonArray['is_stock_managed'];
                unset($this->jsonArray['is_stock_managed']);
                $this->jsonArray['merchant_id'] = $this->merchant_id;
                $this->jsonArray['price'] = ($this->jsonArray['price'] != '') ? $this->jsonArray['price'] : 0;
                $this->jsonArray['wc_post_id'] = $this->merchant_id . '-' . $this->jsonArray['wc_post_id'];
                $this->jsonArray['created_by'] = $this->user_id;
                $this->jsonArray['last_update_by'] = $this->user_id;

                $this->productModel = new Product();
                //first check product is exist or not with wc_post_id
                $existProduct = $this->productModel->checkWCProductExist($this->jsonArray['wc_post_id'], $this->jsonArray['merchant_id']);
                if (empty($existProduct)) {
                    if ($this->jsonArray['goods_type'] == 'variable') {
                        //add product attribute metadata
                        if (isset($this->jsonArray['productAttributeMetadata']) && !empty($this->jsonArray['productAttributeMetadata'])) {
                            $this->saveProductAttrData($this->jsonArray['productAttributeMetadata']);
                        }
                        //check product image is added
                        if ($this->jsonArray['product_image'] != null) {
                            $this->jsonArray['product_image'] = $this->productImageUpload($this->jsonArray['wc_post_id'], $this->jsonArray['product_image'], 'upload');
                        }
                        $saveParentProduct = $this->jsonArray;
                        unset($saveParentProduct['variable_products']);
                        unset($saveParentProduct['productAttributeMetadata']);

                        $saveProductId = $this->productModel->saveProduct($saveParentProduct);

                        if ($saveProductId) {
                            if (!empty($this->jsonArray['variable_products'])) {
                                $this->setVariableProduct($this->jsonArray['variable_products'], $saveProductId, $is_stock_managed);
                            }
                        }
                    } else {
                        //saved simple product
                        //check product image is added
                        if ($this->jsonArray['product_image'] != null) {
                            $this->jsonArray['product_image'] = $this->productImageUpload($this->jsonArray['wc_post_id'], $this->jsonArray['product_image'], 'upload');
                        }
                        $saveProductId = $this->productModel->saveProduct($this->jsonArray);

                        //update parent id for simple product 
                        $updateProduct['parent_id'] = $saveProductId;
                        $updateProductId = $this->productModel->updateProduct($updateProduct, $saveProductId);

                        if (isset($this->jsonArray['has_stock_keeping']) && $this->jsonArray['has_stock_keeping'] == 1 && $this->jsonArray['available_stock'] != 0) {
                            //create enrty in stock_ledger table for invenotry management
                            $savedStockLedgerId = $this->saveProductStockLedger($this->jsonArray['price'], $this->jsonArray['available_stock'], $saveProductId, $is_stock_managed);
                        }
                    }
                } else {
                    //update simple or variable product 
                    if ($this->jsonArray['goods_type'] == 'variable') {
                        if (isset($this->jsonArray['productAttributeMetadata']) && !empty($this->jsonArray['productAttributeMetadata'])) {
                            $this->saveProductAttrData($this->jsonArray['productAttributeMetadata']);
                        }
                        $updateProduct = $this->jsonArray;
                        unset($updateProduct['productAttributeMetadata']);
                        unset($updateProduct['variable_products']);

                        if ($updateProduct['product_image'] != null && $existProduct['product_image'] != null) {
                            $updateProduct['product_image'] = $this->productImageUpload($this->jsonArray['wc_post_id'], $this->jsonArray['product_image'], 'update', $existProduct['product_image']);
                        } else if ($updateProduct['product_image'] != null && $existProduct['product_image'] == null) {
                            $updateProduct['product_image'] = $this->productImageUpload($this->jsonArray['wc_post_id'], $this->jsonArray['product_image'], 'upload');
                        } else if ($updateProduct['product_image'] == null && $existProduct['product_image'] != null) {
                            $updateProduct['product_image'] = $this->productImageUpload($this->jsonArray['wc_post_id'], $this->jsonArray['product_image'], 'delete', $existProduct['product_image']);
                        }

                        $saveProductId = $this->productModel->updateProduct($updateProduct, $existProduct['product_id']);

                        if (!empty($this->jsonArray['variable_products'])) {
                            $this->setVariableProduct($this->jsonArray['variable_products'], $saveProductId, $is_stock_managed);
                        }
                    } else {
                        $updateProduct = $this->jsonArray;
                        unset($updateProduct['productAttributeMetadata']);

                        if ($updateProduct['product_image'] != null && $existProduct['product_image'] != null) {
                            $updateProduct['product_image'] = $this->productImageUpload($this->jsonArray['wc_post_id'], $this->jsonArray['product_image'], 'update', $existProduct['product_image']);
                        } else if ($updateProduct['product_image'] != null && $existProduct['product_image'] == null) {
                            $updateProduct['product_image'] = $this->productImageUpload($this->jsonArray['wc_post_id'], $this->jsonArray['product_image'], 'upload');
                        } else if ($updateProduct['product_image'] == null && $existProduct['product_image'] != null) {
                            $updateProduct['product_image'] = $this->productImageUpload($this->jsonArray['wc_post_id'], $this->jsonArray['product_image'], 'delete', $existProduct['product_image']);
                        }

                        $saveProductId = $this->productModel->updateProduct($updateProduct, $existProduct['product_id']);
                        if (isset($this->jsonArray['has_stock_keeping']) && $this->jsonArray['has_stock_keeping'] == 1 && $this->jsonArray['available_stock'] != 0) {
                            //create enrty in stock_ledger table for invenotry management
                            $savedStockLedgerId = $this->saveProductStockLedger($this->jsonArray['price'], $this->jsonArray['available_stock'], $saveProductId, $is_stock_managed);
                        }
                    }
                }

                if ($saveProductId) {
                    //if product is simple the update parent id
                    $success = array('product_id' => $saveProductId);
                }
                $success = array('product_id' => $saveProductId);
                return $this->printJsonResponse($req_time, 0, $success, $this->errorlist);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, 'Error while calling save inventory from woocommerce' . $e->getMessage());
        }
    }

    function productImageUpload($wc_post_id = null, $product_image = null, $mode = 'upload', $exist_product_image = null)
    {
        $fileUpload = new UppyFileUploadController();
        $product_base_url = 'https://s3.' . env('S3REGION') . '.amazonaws.com/' . env('S3BUCKET_EXPENSE') . '/products/';

        if ($mode == 'upload') {
            $productImagePath = $fileUpload->uploadWCProductImage($product_image, $wc_post_id);
            if (!empty($productImagePath) && $productImagePath['status'] == 200) {
                return $productImagePath['fileUploadPath'];
            } else {
                return NULL;
            }
        } else if ($mode == 'update') {
            $path_parts = pathinfo($product_image);
            $post_id = $this->encrypt->encode($wc_post_id);

            $file_name = $post_id . '-' . $path_parts['filename'];
            $image_name = $product_base_url . $file_name;

            if ($image_name != $exist_product_image) {
                $exist = Storage::disk('s3_expense')->exists($exist_product_image);
                if ($exist) {
                    Storage::disk('s3_expense')->delete($exist_product_image);
                }
                $productImagePath = $fileUpload->uploadWCProductImage($product_image, $wc_post_id);
                if (!empty($productImagePath) && $productImagePath['status'] == 200) {
                    return $productImagePath['fileUploadPath'];
                } else {
                    return NULL;
                }
            } else {
                return $image_name;
            }
        } else if ($mode == 'delete') {
            $exist = Storage::disk('s3_expense')->exists($exist_product_image);
            if ($exist) {
                Storage::disk('s3_expense')->delete($exist_product_image);
            }
            return NULL;
        }
    }

    function saveProductStockLedger($price = 0, $avail_stk = 0, $product_id = null, $is_stock_managed = 0)
    {
        //check stock is changed or updated 
        $stockLedger = new StockLedger();

        $existProductStockLedger = $stockLedger->checkProductStockLedger($product_id);
        if (empty($existProductStockLedger)) {
            $saveStock['product_id'] = $product_id;
            $saveStock['reference_id'] = $product_id;
            $saveStock['quantity'] = $avail_stk;
            $saveStock['amount'] = $price;
            $saveStock['reference_type'] = 1;
            $saveStock['narrative'] = 'Stock added';
            $saveStock['created_by'] = $this->user_id;
            $saveStock['last_update_by'] = $this->user_id;
            $stockLedger = new StockLedger();
            $savedStockLedgerId = $stockLedger->saveStockLedger($saveStock);
            return $savedStockLedgerId;
        } else {
            if ($is_stock_managed == 1) {
                /*
                if($existProductStockLedger['quantity'] != $avail_stk) {
                    if($existProductStockLedger['quantity'] > $avail_stk) {
                        $qty = $existProductStockLedger['quantity'] - $avail_stk;
                        $qty = -$qty;
                    } else {
                        $qty = $avail_stk - $existProductStockLedger['quantity'];
                    }
    
                    //add new stock 
                    $saveStock['product_id'] = $product_id;
                    $saveStock['reference_id'] = $product_id;
                    $saveStock['quantity'] = $qty;
                    $saveStock['amount'] = $price;
                    $saveStock['reference_type'] = 1;
                    $saveStock['narrative'] = 'woocommerce stock adjustment';
                    $saveStock['created_by'] = $this->user_id;
                    $saveStock['last_update_by'] = $this->user_id;
                    $stockLedger = new StockLedger();
                    $savedStockLedgerId = $stockLedger->saveStockLedger($saveStock);
                    return $savedStockLedgerId;
                }*/
            }
            //dd($updateStockLedger);
            //$update = StockLedger::where('id', $existProductStockLedger['id'])->update($updateStockLedger);
        }
        return true;
    }

    function saveProductMetaValues($attributes = null, $saveVarProductId = null)
    {
        $productCon = new ProductController();
        $variation_id = $productCon->generateRandomString(6);
        foreach ($attributes as $akk => $attr_val) {
            $attrvalues = explode(":", $attr_val);
            //find attr_id from metadata table 
            $productAttribute = new ProductAttribute();

            $existProductAttr = $productAttribute->checkProductAttrExist(ucwords(strtolower($attrvalues[0])), $this->merchant_id);

            if (!empty($existProductAttr)) {
                $saveAttr['product_id'] = $saveVarProductId;
                $saveAttr['merchant_id'] = $this->merchant_id;
                $saveAttr['variation_id'] = $variation_id;
                $saveAttr['attribute_id'] = $existProductAttr['id'];;
                $saveAttr['attribute_value'] = ltrim($attrvalues[1]);
                $saveAttr['created_by'] = $this->user_id;
                $saveAttr['last_update_by'] = $this->user_id;
                $productAttrVal = new ProductAttributeValue();
                $savedAttrValueId = $productAttrVal->saveProductAttrValue($saveAttr);
            }
        }
    }

    function saveProductAttrData($attrData = null)
    {
        if (!empty($attrData)) {
            foreach ($attrData as $ak => $aval) {
                $saveProductAttribute['name'] = ucwords(strtolower($aval['title']));
                //check product-attr metadata is exist or not
                $productAttribute = new ProductAttribute();
                $existProductAttr = $productAttribute->checkProductAttrExist($saveProductAttribute['name'], $this->merchant_id);
                if (empty($existProductAttr)) {
                    $saveProductAttribute['merchant_id'] = $this->merchant_id;
                    if (!empty($aval['values'])) {
                        $saveProductAttribute['default_values'] = $aval['values'];
                    }
                    $saveProductAttribute['created_by'] = $this->user_id;
                    $saveProductAttribute['last_update_by'] = $this->user_id;
                    $savedQuery = $productAttribute->saveProductAttribute($saveProductAttribute);
                } else {
                    // update default values
                    if (!empty($aval['values'])) {
                        $wc_attr_values = json_decode($aval['values'], true);
                        $exist_attr_values = json_decode($existProductAttr['default_values'], true);

                        foreach ($wc_attr_values as $wk => $wval) {
                            if (!in_array($wval, $exist_attr_values)) {
                                $exist_attr_values[] = $wval;
                                $saveProductAttribute['default_values'] = json_encode($exist_attr_values, true);
                                $update = ProductAttribute::where('id', $existProductAttr['id'])->update($saveProductAttribute);
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    function setVariableProduct($variableProducts = null, $saveProductId = null, $is_stock_managed = 0)
    {
        $this->productModel = new Product();
        if (!empty($variableProducts)) {
            foreach ($variableProducts as $vk => $varPro) {
                $varPro['wc_post_id'] = $this->merchant_id . '-' . $varPro['wc_post_id'];
                $varPro['parent_id'] = $saveProductId;
                $varPro['merchant_id'] = $this->merchant_id;
                $varPro['created_by'] = $this->user_id;
                $varPro['last_update_by'] = $this->user_id;

                //upload variable product image to s3 bucket
                $fileUpload = new UppyFileUploadController();
                $productImagePath = $fileUpload->uploadWCProductImage($varPro['product_image']);
                if (!empty($productImagePath) && $productImagePath['status'] == 200) {
                    $varPro['product_image'] = $productImagePath['fileUploadPath'];
                } else {
                    $varPro['product_image'] = NULL;
                }
                //unset($varPro['attributes']);
                $saveVariationProduct = $varPro;
                unset($saveVariationProduct['attributes']);

                $existProduct = $this->productModel->checkWCProductExist($varPro['wc_post_id'], $this->merchant_id);

                if (empty($existProduct)) {
                    //save variable product
                    $saveVarProductId = $this->productModel->saveProduct($saveVariationProduct);
                } else {
                    //update variable product
                    $saveVarProductId = $this->productModel->updateProduct($saveVariationProduct, $existProduct['product_id']);
                }

                if (isset($varPro['has_stock_keeping']) && $varPro['has_stock_keeping'] == 1 && $varPro['available_stock'] != 0) {
                    //create enrty in stock_ledger table for invenotry management
                    $savedStockLedgerId = $this->saveProductStockLedger($varPro['price'], $varPro['available_stock'], $saveVarProductId, $is_stock_managed);
                }

                //save product attribute meta values
                if (!empty($varPro['attributes'])) {
                    $this->saveProductMetaValues($varPro['attributes'], $saveVarProductId);
                }
            }
        }
        return true;
    }
}
