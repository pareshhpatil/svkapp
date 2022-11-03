<?php

use SebastianBergmann\Type\NullType;

include_once('bulkupload.php');
require_once(MODEL . 'merchant/InvoiceModel.php');
require_once(CONTROLLER . 'InvoiceWrapper.php');

class Saveinvoice extends BulkUpload
{

    private $invoiceModel = null;
    private $invoiceWrap = null;

    function __construct()
    {
        parent::__construct();
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceWrap = new InvoiceWrapper($this->common);
        $this->invoicesave();
    }

    public function invoicesave()
    {
        try {
            $bulkuploadlist = $this->getbulkuploadlist(2, 1);
            foreach ($bulkuploadlist as $bulkupload) {
                try {
                    if (!empty($bulkupload)) {
                        $this->logger->info(__CLASS__, 'Saving Bulk upload invoice initiate bulk_id is : ' . $bulkupload['bulk_upload_id'] . ' and total rows are ' . $bulkupload['total_rows']);
                        $file = '../../../../public/uploads/Excel/' . $bulkupload['merchant_id'] . '/staging/' . $bulkupload['system_filename'];
                        if (!file_exists($file)) {
                            $this->updateBulkUploadStatus($bulkupload['bulk_upload_id'], 1, 'File does not exist');
                            throw new Exception($file . ' file does not exist');
                        } else {
                            $this->bulk_upload($file, $bulkupload['bulk_upload_id']);
                        }
                    }
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    $this->logger->error(__CLASS__, '[BE12]Error while invoice save Error: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE12]Error while invoice save Error: ' . $e->getMessage());
        }
    }

    public function bulk_upload($inputFile, $bulk_id)
    {
        try {
            $this->updateBulkUploadStatus($bulk_id, 3);
            $inputFileType = PHPExcel_IOFactory::identify($inputFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFile);
            $subject = $objPHPExcel->getProperties()->getSubject();
            $link = $this->encrypt->decode($subject);
            $template_id = substr($link, 0, 10);

            $version='';
            if (strlen($link) > 30) {
                if (strlen($link) == 33) {
                    $update_date = substr($link, 10, -4);
                    $type = substr($link, -4, 1);
                    $sheet_type = substr($link, -3, 1);
                    $info['version'] = 'v2'; //for country column in excel sheet
                } else {
                    $update_date = substr($link, 10, -2);
                    $type = substr($link, -2, 1);
                    $sheet_type = substr($link, -1);
                    $info['version'] = 2;
                }
            } else {
                $update_date = substr($link, 10, -1);
                $type = substr($link, -1);
                $sheet_type = 1;
                $info['version'] = 1;
            }

            $version=$info['version'];


            $worksheet = $objPHPExcel->getSheet(0);
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            $errors = array();
            $templateinfo = array();

            if ($template_id != '') {
                $info = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);
                require_once CONTROLLER . 'InvoiceWrapper.php';
                $invoice = new InvoiceWrapper($this->common);
                $templateinfo = $invoice->getTemplateBreakup($template_id);
                $template_type = $info['template_type'];
            }
            if (empty($templateinfo['header'])) {

                SwipezLogger::error(__CLASS__, '[E283]Error while validating excel Error: template id [' . $template_id . ']');
                $errors[0]['row'] = 1;
                $errors[0][0][0] = 'Invalid template';
                $errors[0][0][1] = 'Download excel from template list and re-upload with consumer data.';
            }
            $last_col = 0;
            if ($sheet_type == 2) {
                require_once 'savestagingcustomer.php';
                $customer = new SaveStagingCustomer();
                $row_number = ($template_type == 'travel') ? 3 : 2;
                $array = $customer->bulkUploadFile(null, null, $worksheet, $row_number, $info['merchant_id'],$version);
                $last_col = $array['last_col'];
                $customer_codes = $this->getCustomerCodes($array['POSTarray'], $info['merchant_id']);
            }



            $getcolumnvalue = array();
            if (empty($errors)) {
                require_once CONTROLLER . 'merchant/BulkuploadWrapper.php';
                $bulkWrapper = new BulkuploadWrapper($this->common);
                $_POSTarray = $bulkWrapper->getInvoiceExcelRows($worksheet, $templateinfo, $info, $template_id, $type, $last_col, $customer_codes);
            }


            $errorrow = 2;
            try {
                if (empty($_POSTarray) && empty($errors)) {
                    $this->logger->error(__CLASS__, '[BE2]Error while validating excel Error: empty excel');
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__CLASS__, '[BE3]Error while validating excel Error: ' . $e->getMessage());
            }

            if (empty($errors)) {
                $count_int = 0;
                try {
                    foreach ($_POSTarray as $_POST) {
                        $this->uploadinvoicesave($bulk_id, $info, $templateinfo);
                        $count_int++;
                    }
                    $this->logger->info(__CLASS__, 'Bulk invoice saved sucessfully bulk id : ' . $bulk_id . ' Total invoices ' . $count_int);
                    $this->updateBulkUploadStatus($bulk_id, 3);
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    $this->logger->error(__CLASS__, '[BE4]Error while bulkupload invoice save excel Error: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE5]Error while uploading excel Error: ' . $e->getMessage());
        }
    }

    function getCustomerCodes($POSTarray, $merchant_id)
    {
        require_once MODEL . 'merchant/CustomerModel.php';
        $customer = new CustomerModel();
        $user_id = $this->common->getRowValue('user_id', 'merchant', 'merchant_id', $merchant_id);
        $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
        $auto_generate = $merchant_setting['customer_auto_generate'];
        $customer_codes = array();
        foreach ($POSTarray as $_POST) {
            $exist = false;
            $space_position = strpos($_POST['customer_name'], ' ');
            if ($space_position > 0) {
                $_POST['first_name'] = substr($_POST['customer_name'], 0, $space_position);
                $_POST['last_name'] = substr($_POST['customer_name'], $space_position);
            } else {
                $_POST['first_name'] = $_POST['customer_name'];
                $_POST['last_name'] = '';
            }
            if ($auto_generate == 0) {
                $exist = $customer->isExistCustomerCode($merchant_id, $_POST['customer_code']);
            } else {
                $exist = $customer->existCustomerDetail($merchant_id, $_POST['first_name'], $_POST['state'], $_POST['email'], $_POST['mobile'], $_POST['GST']);
                if ($exist == false) {
                    $_POST['customer_code'] = $customer->getCustomerCode($merchant_id);
                } else {
                    $_POST['customer_code'] = $exist['customer_code'];
                }
            }
            if ($exist == false) {
                $response = $customer->saveCustomer($user_id, $merchant_id, $_POST['customer_code'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], '', $_POST['city'], $_POST['state'], $_POST['zipcode'],  $_POST['ids'],  $_POST['values'], 0, '', $_POST['GST']);
                $customer_codes[] = $_POST['customer_code'];
            } else {
                $customer_codes[] = $_POST['customer_code'];
            }
        }

        return $customer_codes;
    }

    function uploadinvoicesave($bulk_id, $info, $templateinfo)
    {
        try {
            $this->prepareupload($templateinfo);
            $billdate = new DateTime($_POST['bill_date']);
            $duedate = new DateTime($_POST['due_date']);
            if (isset($_POST['mainvalues'])) {
                $_POST['values'] = array_merge($_POST['mainvalues'], $_POST['values']);
                $_POST['ids'] = array_merge($_POST['mainids'], $_POST['ids']);
            }
            $_POST['expiry_date'] = (isset($_POST['expiry_date'])) ? $_POST['expiry_date'] : '';
            $_POST['expiry_date'] = ($_POST['expiry_date'] != '') ? $_POST['expiry_date'] : NULL;
            $_POST['late_fee'] = ($_POST['late_fee'] > 0) ? $_POST['late_fee'] : 0;
            $type = ($_POST['type'] > 0) ? $_POST['type'] : 1;
            $carry_forward_due = ($_POST['carry_forward_due'] > 0) ? $_POST['carry_forward_due'] : 0;
            $_POST['customer_id'] = $this->common->getRowValue('customer_id', 'customer', 'customer_code', $_POST['customer_code'], 1, " and merchant_id='" . $info['merchant_id'] . "'");
            if ($_POST['customer_id'] > 0) {
                $plugin_value = $this->invoiceWrap->setInvoicePluginValues($info['template_id'], 'bulk');
                $plugin_array = json_decode($plugin_value, true);
                if ($plugin_array['has_custom_reminder'] == 1) {
                    $_POST['has_custom_reminder'] = 1;
                }

                $billing_profile_id = $info['profile_id'];
                if (isset($_POST['billing_profile'])) {
                    $billing_profile_id = $this->common->getRowValue('id', 'merchant_billing_profile', 'profile_name', $_POST['billing_profile'], 1, " and merchant_id='" . $info['merchant_id'] . "'");
                }
                $type = ($_POST['type'] > 0) ? $_POST['type'] : 1;
                $auto_generate_invoice = (isset($_POST['auto_generate_invoice'])) ? $_POST['auto_generate_invoice'] : 1;
                if ($type == 2) {
                    $plugin_array['invoice_title'] = $_POST['estimate_title'];
                }

                $plugin_value = json_encode($plugin_array);
                $currency = (isset($_POST['currency'])) ? $_POST['currency'] : 'INR';

                $travels = array();
                $travel_particular = array('travel_section' => 'b', 'travel_cancel_section' => 'c', 'hotel_section' => 'hb', 'facility_section' => 'fs');
                foreach ($travel_particular as $tpk => $tpc) {
                    if (isset($_POST['travel_particular'][$tpk])) {
                        foreach ($_POST['travel_particular'][$tpk] as $ktp => $tpcc) {
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
                            $travels['mrp'][] = ($tpcc['mrp'] > 0) ? $tpcc['mrp'] : 0;
                            $travels['product_expiry_date'][] = isset($tpcc['product_expiry_date']) ? $tpcc['product_expiry_date'] : null;
                            $travels['product_number'][] = isset($tpcc['product_number']) ? $tpcc['product_number'] : '';
                            $travels['gst'][] = ($tpcc['gst'] > 0) ? $tpcc['gst'] : 0;
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
                if ($type == 3 || $type == 4) {
                    $payment_request_type = 4;
                    if ($type == 3) {
                        $invoice_type = 1;
                    } else if ($type == 4) {
                        $invoice_type = 2;
                    }
                } else {
                    $invoice_type = $type;
                    $payment_request_type = 3; //for bulk 
                }
                $result = $this->ImportSaveInvoice($bulk_id, $info['merchant_id'], $info['user_id'], $_POST['customer_id'], $info['template_id'], $_POST['invoice_number'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $_POST['expiry_date'], $_POST['bill_cycle_name'], $_POST['values'], $_POST['ids'], $_POST['invoice_narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $_POST['late_fee'], $_POST['advance'], $_POST['notify_patron'], $_POST['franchise_id'], $_POST['vendor_id'], $info['user_id'], $invoice_type, $payment_request_type, 0, $_POST['has_custom_reminder'], $plugin_value, $billing_profile_id, $carry_forward_due, $auto_generate_invoice, $currency, $_POST['einvoice_type'], $_POST['product_taxation_type']);
                if ($result['message'] == 'success') {
                    //if type is subscription invoice or subscription estimate
                    if ($type == 3 || $type == 4) {
                        if ($_POST['mode'] == 'month') {
                            $add = 'months';
                            $time_period = '+1 MONTH';
                            $mode = 3;

                            if ($_POST['repeat_every'] == 1) {
                                $display_text = 'Monthly';
                            } else {
                                $display_text = 'Every ' . $_POST['repeat_every'] . ' months';
                            }
                            $on = $billdate->format('d');
                            $display_text = $display_text . ' on day ' . $on;
                        } else if ($_POST['mode'] == 'week') {
                            $add = 'weeks';
                            $time_period = '+1 WEEK';
                            $mode = 2;

                            if ($_POST['repeat_every'] == 1) {
                                $display_text = 'Weekly';
                            } else {
                                $display_text = 'Every ' . $_POST['repeat_every'] . ' weeks';
                            }
                        } else if ($_POST['mode'] == 'day') {
                            $add = 'days';
                            $time_period = '+1 DAY';
                            $mode = 1;

                            if ($_POST['repeat_every'] == 1) {
                                $display_text = 'Daily';
                            } else {
                                $display_text = 'Every ' . $_POST['repeat_every'] . ' days';
                            }
                        } else if ($_POST['mode'] == 'year') {
                            $add = 'year';
                            $time_period = '+1 YEAR';
                            $mode = 4;

                            if ($_POST['repeat_every'] == 1) {
                                $display_text = 'Annually';
                            } else {
                                $display_text = 'Every ' . $_POST['repeat_every'] . ' years';
                            }
                            $day = $billdate->format('d');
                            $month = $billdate->format('M');
                            $display_text = $display_text . ' on ' . $month . ' ' . $day;
                        }

                        if ($_POST['end_mode'] == 'never') {
                            $end_mode = 1;
                            $occurrence = 0;
                            $end_date = $billdate->format('Y-m-d');
                        } else if ($_POST['end_mode'] == 'occurence') {
                            $end_mode = 2;
                            $occurrence = $_POST['end_value'];

                            //calculate end date from occurence
                            $days = ($occurrence * $_POST['repeat_every']) - 1;
                            $start_date = $billdate->format('Y-m-d');
                            $end_date = strtotime("+" . $days . " " . $add . "", strtotime($start_date));
                            $end_date = date('Y-m-d', $end_date);
                        } else if ($_POST['end_mode'] == 'end date') {
                            $end_mode = 3;
                            $occurrence = 0;
                            $end_value = new DateTime($_POST['end_value']);
                            $end_date = $end_value->format('Y-m-d');

                            if (isset($end_date)) {
                                $date1 = strtotime($_POST['bill_date']);
                                $date2 = strtotime($_POST['end_value']);
                                $occurenceCnt = 1;

                                while (($date1 = strtotime($time_period, $date1)) <= $date2) {
                                    $occurenceCnt++;
                                }
                                $occurrence = (int)($occurenceCnt / $_POST['repeat_every']);
                            }
                        }

                        //calculate due diff
                        $date1 = date_create($_POST['bill_date']);
                        $date2 = date_create($_POST['due_date']);
                        $diff = date_diff($date1, $date2);
                        $date_diff = $diff->format("%a");

                        $carry_due = (isset($_POST['carry_due']) && $_POST['carry_due'] == 1) ? 1 : 0;

                        $billing_period_start_date = NULL;
                        if (isset($_POST['billing_period_start_date']) && $_POST['billing_period_start_date'] != '') {
                            $billing_period_start_date = new DateTime($_POST['billing_period_start_date']);
                            $billing_period_start_date = $billing_period_start_date->format('Y-m-d');
                        }
                        $billing_period_duration = (isset($_POST['billing_period_duration']) && $_POST['billing_period_duration'] != '') ? $_POST['billing_period_duration'] : NULL;
                        $billing_period_type = (isset($_POST['billing_period_type']) && $_POST['billing_period_type'] != '') ? $_POST['billing_period_type'] : NULL;;

                        $resultSubscription = $this->ImportSaveSubscription(
                            $result['request_id'],
                            $info['merchant_id'],
                            $bulk_id,
                            $info['user_id'],
                            $mode,
                            $_POST['repeat_every'],
                            NULL,
                            $billdate->format('Y-m-d'),
                            $end_mode,
                            $end_date,
                            $occurrence,
                            $display_text,
                            $duedate->format('Y-m-d'),
                            $date_diff,
                            $carry_due,
                            $billing_period_start_date,
                            $billing_period_duration,
                            $billing_period_type
                        );
                    }

                    $this->invoiceModel->saveInvoiceParticular(
                        $result['request_id'],
                        0,
                        $_POST['particular_id'],
                        $_POST['item'],
                        $_POST['annual_recurring_charges'],
                        $_POST['sac_code'],
                        $_POST['description'],
                        $_POST['qty'],
                        $_POST['unit_type'],
                        $_POST['rate'],
                        $_POST['gst'],
                        $_POST['tax_amount'],
                        $_POST['discount_perc'],
                        $_POST['discount'],
                        $_POST['total_amount'],
                        $_POST['narrative'],
                        $info['user_id'],
                        $info['merchant_id'],
                        1,
                        $bulk_id,
                        $_POST['mrp'],
                        $_POST['product_expiry_date'],
                        $_POST['product_number']
                    );
                    $this->invoiceModel->saveInvoiceTax($result['request_id'], $_POST['tax_id'], $_POST['tax_percent'], $_POST['tax_applicable'], $_POST['tax_amt'], $_POST['tax_detail_id'], $info['user_id'], 1, $bulk_id);
                    if (!empty($travels)) {

                        $this->invoiceModel->saveTravelDetails(
                            $result['request_id'],
                            $travels['texistid'],
                            $travels['btype'],
                            $travels['booking_date'],
                            $travels['journey_date'],
                            $travels['name'],
                            $travels['type'],
                            $travels['unit_type'],
                            $travels['sac_code'],
                            $travels['from'],
                            $travels['to'],
                            $travels['amt'],
                            $travels['charge'],
                            $travels['unit'],
                            $travels['rate'],
                            $travels['mrp'],
                            $travels['product_expiry_date'],
                            $travels['product_number'],
                            $travels['discount_percent'],
                            $travels['discount'],
                            $travels['gst'],
                            $travels['total_amount'],
                            $info['user_id'],
                            $travels['description'],
                            $travels['information'],
                            1,
                            $bulk_id
                        );
                    }
                    if ($_POST['vendor_id'] > 0) {
                        $grand_total = $_POST['totaltax'] + $_POST['totalcost'];
                        if (strtolower($_POST['commission_type']) == 'percentage') {
                            $type = 1;
                            $commission_amount = $grand_total * $_POST['commission_value'] / 100;
                        } else {
                            $type = 2;
                            $commission_amount = $_POST['commission_value'];
                        }
                        if ($commission_amount > 0) {
                            $this->invoiceModel->saveVendorCommission($result['request_id'], $info['merchant_id'], $_POST['vendor_id'], $commission_amount, $type, $_POST['commission_value'], $info['user_id']);
                        }
                    }
                    if ($info['merchant_id'] == PENALTY_MERCHANT_ID) {
                        $batch = new Batch();
                        $batch->saveLateFee($result['request_id'], $_POST['customer_id'], $info['merchant_id'], $billdate->format('Y-m-d'));
                    }
                    return 'success';
                } else {
                    $this->logger->error(__CLASS__, '[BE6]Error while sending payment request to patron Error: ' . $result['Message']);
                }
            } else {
                $this->logger->error(__CLASS__, '[BE6]Error while saving bulk request Error: ' . $_POST['customer_code'] . ' customer code does not exist');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE7]Error while upload invoice save Error: ' . $e->getMessage());
        }
    }

    public function ImportSaveInvoice($bulk_id, $merchant_id, $user_id, $customer_id, $template_id, $invoice_number, $billdate, $duedate, $expiry_date, $cyclename, $values, $ids, $narrative, $amount, $tax, $previous_dues, $last_payment, $adjustment, $late_fee, $advance, $notify_patron, $franchise_id, $vendor_id, $created_by, $request_type = 1, $type = 1, $auto_collect_plan_id = 0, $custom_reminder = 0, $plugin_value, $billing_profile_id, $carry_forward_due, $auto_generate_invoice, $currency = 'INR', $einvoice_type = null, $product_taxation_type = 1)
    {
        try {
            $ids = implode('~', $ids);
            $values = implode('~', $values);
            $custom_reminder = ($custom_reminder > 0) ? $custom_reminder : 0;
            $sql = "call `insert_statging_invoicevalues`(:bulk_id,:merchant_id,:user_id,:customer_id,:invoice_number,:template_id,:values,:ids,:billdate,:duedate,:cyclename,:narrative,:amount,:tax,:previous_dues,:last_payment,:adjustment,:late_fee,:advance,:notify_patron,:franchise_id,:vendor_id,:expiry_date,:created_by,:request_type,:type,:auto_collect_plan_id,:custom_reminder,:plugin_value,:billing_profile_id,:carry_forward_due,:auto_generate_invoice,:currency,:einvoice_type, :product_taxation_type);";
            $params = array(
                ':bulk_id' => $bulk_id, ':merchant_id' => $merchant_id, ':user_id' => $user_id, ':customer_id' => $customer_id, ':invoice_number' => $invoice_number, ':template_id' => $template_id,
                ':values' => $values, ':ids' => $ids, ':billdate' => $billdate, ':duedate' => $duedate, ':cyclename' => $cyclename, ':narrative' => $narrative, ':amount' => $amount, ':tax' => $tax,
                ':previous_dues' => $previous_dues, ':last_payment' => $last_payment, ':adjustment' => $adjustment, ':late_fee' => $late_fee, ':advance' => $advance, ':notify_patron' => $notify_patron,
                ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id, ':expiry_date' => $expiry_date, ':created_by' => $created_by, ':request_type' => $request_type, ':type' => $type, ':auto_collect_plan_id' => $auto_collect_plan_id,
                ':custom_reminder' => $custom_reminder, ':plugin_value' => $plugin_value, ':billing_profile_id' => $billing_profile_id, ':carry_forward_due' => $carry_forward_due, ':auto_generate_invoice' => $auto_generate_invoice,
                ':currency' => $currency, ':einvoice_type' => $einvoice_type, ':product_taxation_type' => $product_taxation_type
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] != 'success') {
                throw new Exception('Bulk id ' . $bulk_id . ' Error: ' . $row['Message']);
            }
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-1]Error while saving invoice Error:  for merchant id[' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function prepareupload($invoicevalues)
    {
        try {
            $cycle_datekey = array_search('4', $_POST['col_position']);
            $bill_datekey = array_search('5', $_POST['col_position']);
            $due_datekey = array_search('6', $_POST['col_position']);
            $_POST['bill_cycle_name'] = $_POST['values'][$cycle_datekey];
            $_POST['bill_date'] = $_POST['values'][$bill_datekey];
            $_POST['due_date'] = $_POST['values'][$due_datekey];
            $_POST['late_fee'] = 0;
            $bill_date_col = 5;
            $due_date_col = 6;
            require_once PACKAGE . 'swipez/function/DataFunction.php';
            $int = 0;

            foreach ($invoicevalues['header'] as $column) {
                if ($column['function_id'] > 0) {
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
                        $_POST['bill_date'] = $function->get('value');
                    }
                    if ($column['column_position'] == $due_date_col) {
                        $function->set($_POST['due_date']);
                        $_POST['due_date'] = $function->get('value');
                    }
                    if ($column['table_name'] == 'metadata') {
                        $function->set($_POST['values'][$int]);
                        $_POST['values'][$int] = $function->get('value');
                    }
                }
                $int++;
            }
            $_POST['notify_patron'] = (strtoupper($_POST['notify_patron']) == 'NO') ? 0 : 1;

            $billdate = new DateTime($_POST['bill_date']);
            $duedate = new DateTime($_POST['due_date']);
            $_POST['bill_date'] = $billdate->format('Y-m-d');
            $_POST['due_date'] = $duedate->format('Y-m-d');
            $template_id = $this->encrypt->encode($_POST['template_id']);
            $_POST['template_id'] = $template_id;


            if (!isset($_POST['values'])) {
                $_POST['values'] = array();
            }
            if (!isset($_POST['ids'])) {
                $_POST['ids'] = array();
            }

            if (!isset($_POST['totalcost'])) {
                $_POST['totalcost'] = 0;
            }

            if (!isset($_POST['totaltax'])) {
                $_POST['totaltax'] = 0;
            }
            $_POST['previous_dues'] = ($_POST['previous_dues'] > 0) ? $_POST['previous_dues'] : 0;
            $_POST['last_payment'] = ($_POST['last_payment'] > 0) ? $_POST['last_payment'] : 0;
            $_POST['adjustment'] = ($_POST['adjustment'] > 0) ? $_POST['adjustment'] : 0;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE9]Error while prepair upload Error: ' . $e->getMessage());
        }
    }

    function roundAmount($amount, $num)
    {
        $text = number_format($amount, $num);
        $amount = str_replace(',', '', $text);
        return $amount;
    }

    function ImportSaveSubscription($request_id, $merchant_id, $bulk_id, $user_id, $mode = NULL, $repeat_every = NULL, $repeat_on = NULL, $start_date = NULL, $end_mode = NULL, $end_date = NULL, $occurrence = NULL, $display_text = NULL, $due_date = NULL, $date_diff, $carry_due = 0, $billing_start_date = NULL, $billing_period = NULL, $period_type = NULL)
    {
        try {
            $sql = "INSERT INTO `staging_subscription`(`payment_request_id`,`merchant_id`,`bulk_id`,`mode`,`repeat_every`,`repeat_on`,`start_date`,`next_bill_date`,`due_date`,`due_diff`,`end_mode`,`occurrences`,`end_date`,
`display_text`,`carry_due`,`billing_period_start_date`,`billing_period_duration`,`billing_period_type`,`created_by`,`created_date`,`last_updated_by`,`last_updated_date`)VALUES(:payment_request_id,:merchant_id,:bulk_id,:mode,:repeat_every,:repeat_on,:start_date,:next_bill_date,:due_date,:due_diff,:end_mode,:occurrences,:end_date,:display_text,:carry_due,:billing_start_date,:billing_period,:period_type,:created_by,CURRENT_TIMESTAMP(),:last_updated_by,CURRENT_TIMESTAMP());";
            $params = array(':payment_request_id' => $request_id, ':merchant_id' => $merchant_id, ':bulk_id' => $bulk_id, ':mode' => $mode, ':repeat_every' => $repeat_every, ':repeat_on' => $repeat_on, ':start_date' => $start_date, ':next_bill_date' => $start_date, ':due_date' => $due_date, ':due_diff' => $date_diff, ':end_mode' => $end_mode, ':occurrences' => $occurrence, ':end_date' => $end_date, ':display_text' => $display_text, ':carry_due' => $carry_due, ':billing_start_date' => $billing_start_date, ':billing_period' => $billing_period, ':period_type' => $period_type, ':created_by' => $user_id, ':last_updated_by' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);
            $this->logger->error(__CLASS__, '[BE10]Error while prepair upload Error: ' . $e->getMessage());
        }
        return;
    }
}

new Saveinvoice();
