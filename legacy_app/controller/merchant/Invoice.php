<?php

include_once SWIPEZ_UTIL_PATH . '/src/shorturl/SwipezShortURLWrapper.php';

use Illuminate\Support\Facades\Redis;


/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Invoice extends Controller
{

    function __construct()
    {
        parent::__construct();


        //TODO : Check if using static function is causing any problems!

        $this->view->selectedMenu = 'request';
        $this->validateSession('merchant');
        //        $this->view->js = array('validation.js');
        $this->view->js = array('template');
    }

    /**
     * Create invoice initiate
     */
    function create($type = 'invoice')
    {
        try {
            $this->hasRole(2, 3);
            $merchant_id = $this->session->get('merchant_id');
            $user_id = $this->session->get('userid');
            $sub_franchise_id = $this->session->get('sub_franchise_id');
            $templatelist = $this->common->getTemplateList($user_id, $sub_franchise_id);
            $templatelist = $this->generic->getEncryptedList($templatelist, 'encrypted_id', 'template_id');
            $this->session->set('valid_ajax', 'template_preview');
            if ($type == 'estimate') {
                $request_type = 2;
                $this->view->selectedMenu = array(3, 122);
                $this->smarty->assign("title", 'Create an estimate&nbsp;');
                $this->view->title = 'Create an estimate';
            } else {
                $request_type = 1;
                $this->view->selectedMenu = array(3, 19);
                $this->smarty->assign("title", 'Create an invoice&nbsp;');
                $this->view->title = 'Create an invoice';
            }
            if (count($templatelist) == 1) {
                $_POST['selecttemplate'] = $templatelist[0]['template_id'];
            }

            $this->smarty->assign("request_type", $request_type);
            $this->smarty->assign("templatelist", $templatelist);


            if (isset($_POST['selecttemplate']) && $_POST['selecttemplate'] != '') {
                require_once CONTROLLER . 'InvoiceWrapper.php';
                $invWrap = new InvoiceWrapper($this->common);
                $reInfo = $invWrap->setCreateInvoice($_POST['selecttemplate'], $merchant_id, $user_id, 'individual');
                foreach ($reInfo['smarty'] as $key => $value) {
                    $this->smarty->assign($key, $value);
                }
                $this->view->function_script = $reInfo['function_script'] . 'setProductRates();';
                if (count($reInfo['smarty']['customer_list']) == 1) {
                    $this->view->function_script .= 'selectCustomer(' . $reInfo['smarty']['customer_list'][0]['customer_id'] . ');';
                }
            }


            $jsarray[] = 'customer';
            $jsarray[] = 'template';
            $jsarray[] = 'coveringnote';
            $jsarray[] = 'invoiceformat';
            $this->view->js = $jsarray;
            $this->view->header_file = ['create_invoice'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/invoice/select.tpl');
            if (isset($_POST['selecttemplate'])) {
                $template_type = $reInfo['template_type'];
                if ($template_type != 'travel_ticket_booking' && $template_type != 'franchise' && $template_type != 'travel') {
                    $template_type = 'particular';
                }
                $this->smarty->display(VIEW . 'merchant/invoice/invoice_header.tpl');
                if ($reInfo['template_type'] != 'scan') {
                    $this->smarty->display(VIEW . 'merchant/invoice/create_' . $template_type . '.tpl');
                }
                $this->smarty->display(VIEW . 'merchant/invoice/invoice_footer.tpl');
                $this->smarty->display(VIEW . 'merchant/template/supplier.tpl');
            }
            $this->smarty->display(VIEW . 'merchant/invoice/footer.tpl');
            $this->view->render('footer/create_event');
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E003]Error while merchant invoice create initiate Error: for merchant [' . $merchant_id . '] and for template [' . $this->view->templateselected . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Update invoice initiate
     */
    function update($link, $staging = 0)
    {
        try {
            $this->hasRole(2, 3);
            if (!isset($link)) {
                $this->setGenericError();
            }
            $merchant_id = $this->session->get('merchant_id');
            $payment_request_id = $this->encrypt->decode($link);
            $this->smarty->assign("req_id", $payment_request_id);
            $this->smarty->assign("link", $link);
            if ($staging == 0) {
                $info = $this->common->getPaymentRequestDetails($payment_request_id, $this->merchant_id);
            } else {
                $info = $this->common->getPaymentRequestDetails($payment_request_id, $this->merchant_id, 3);
            }
            if ($info['message'] != 'success') {
                SwipezLogger::error(__CLASS__, '[E008-1]Error while initiate update invoice Error: invalid status for merchant [' . $merchant_id . '] and for payment request id ' . $payment_request_id);
                $this->setInvalidLinkError();
            }

            if ($info['payment_request_status'] == 3) {
                $this->setError('Invalid payment request', 'Payment request has already deleted');
                header('location: /error');
                exit;
            } elseif ($info['payment_request_status'] == 1) {
                $this->setError('Invalid payment request', 'Payment request has already paid online');
                header('location: /error');
                exit;
            } elseif ($info['payment_request_status'] == 2) {
                $this->setError('Invalid payment request', 'Payment request has already settled');
                header('location: /error');
                exit;
            }


            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $smarty = $invoice->setUpdateInvoiceSmarty($info, $staging);
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }

            $this->view->function_script = $smarty['function_script'];
            $template_type = $smarty['template_type'];
            $jsarray[] = 'coveringnote';
            $jsarray[] = 'customer';
            $jsarray[] = 'template';
            $jsarray[] = 'invoiceformat';
            $this->view->js = $jsarray;
            $this->smarty->assign("post_link", '/merchant/invoice/invoiceupdate');
            if ($template_type != 'travel_ticket_booking' && $template_type != 'franchise' && $template_type != 'travel') {
                $template_type = 'particular';
            }
            $this->view->title = 'Update Invoice';
            $this->view->header_file = ['create_invoice'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/invoice/update_header.tpl');
            if ($smarty['template_type'] != 'scan') {
                $this->smarty->display(VIEW . 'merchant/invoice/update_' . $template_type . '.tpl');
            }
            $this->smarty->display(VIEW . 'merchant/invoice/update_footer.tpl');
            $this->smarty->display(VIEW . 'merchant/template/supplier.tpl');
            $this->smarty->display(VIEW . 'merchant/invoice/footer.tpl');
            $this->view->render('footer/create_event');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E005]Error while sending payment request Error: for merchant [' . $merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * create invoice saved
     */
    function validateInvoicePlan($req_id)
    {
        $valid = true;
        if ($req_id == null) {
            $merchant_plan = $this->session->get('merchant_plan');
            if ($merchant_plan > 0) {
                if ($this->session->get('package_expire')) {
                    $valid = false;
                }
            } else {
                $valid = $this->common->isValidPackageInvoice($this->merchant_id, 1, 'indi', $merchant_plan);
            }
            if ($valid == false) { {
                    $hasErrors[0][0] = 'Package expired';
                    $hasErrors[0][1] = 'Swipez Package Expired. Please renew your <a href="/merchant/package/confirm/' . $this->session->get('package_link') . '">package</a> to send more invoices.';
                    return $hasErrors;
                }
            }
        }
    }

    function invoicesave($validate = NULL, $req_id = NULL, $post_type = 'insert')
    {
        try {
            if ($validate == null) {
                //dd($_POST);
                //dd($_FILES);
            }
            if (!isset($_POST['template_id'])) {
                header("Location:/merchant/invoice/create");
            }

            $product_taxation_type = $_POST['product_taxation_type'] ? $_POST['product_taxation_type'] : 1;
            $template_id = $this->encrypt->decode($_POST['template_id']);
            $payment_request_type = ($_POST['payment_request_type'] > 0) ? $_POST['payment_request_type'] : 1;
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);

            if ($payment_request_type == 4) {
                $subscription = $invoice->setSubscriptionPost();
            }

            if ($_POST['template_type'] == 'construction') {
                $_POST['grand_total'] = str_replace(',', '', $_POST['grand_total']);
                $_POST['totalcost'] = $_POST['grand_total'];
            }

            $invoicevalues = $invoice->getTemplateBreakup($template_id);
            $invoice->setInvoiceFunctionData($invoicevalues, $post_type, $req_id, $validate);

            $billdate = $this->generic->sqlDate($_POST['bill_date']);
            $duedate = $this->generic->sqlDate($_POST['due_date']);

            $this->generic->setEmptyArray(array('newvalues', 'ids', 'deduct_tax', 'supplier', 'cc', 'reminders', 'particular_id', 'item', 'annual_recurring_charges', 'sac_code', 'description', 'qty', 'rate', 'gst', 'tax_amount', 'discount_perc', 'discount', 'total_amount', 'narrative', 'tax_id', 'tax_percent', 'tax_applicable', 'tax_amt', 'tax_detail_id'));
            $this->generic->setZeroValue(array('commision_amount', 'commision_value', 'late_fee', 'coupon_id', 'franchise_id', 'vendor_id', 'totaltax', 'totalcost', 'previous_dues', 'last_payment', 'adjustment', 'advance', 'is_partial', 'partial_min_amount', 'autocollect_plan_id', 'has_custom_reminder', 'billing_profile_id'));
            $this->generic->setEmptyValue(array('invoice_narrative', 'invoice_number', 'invoice_title', 'einvoice_type'));

            if ($_POST['template_type'] == 'travel_ticket_booking') {
                $this->generic->convertSQLDateTime(array('booking_date', 'journey_date'));
            }

            if ($_POST['template_type'] == 'travel') {
                $this->generic->convertSQLDateTime(array('sec_booking_date', 'sec_journey_date', 'sec_from_date', 'sec_to_date'));
            }

            $hasErrors = $this->validateInvoicePlan($req_id);
            if ($hasErrors == FALSE) {
                require CONTROLLER . 'Paymentvalidator.php';
                $validator = new Paymentvalidator($this->model);
                $validator->validateInvoice($this->user_id, $req_id);
                $hasErrors = $validator->fetchErrors();
            }
            if ($validate != NULL && $hasErrors == false) {
                echo 'yes';
                die();
            }

            if ($hasErrors == false) {
                if (isset($_POST['mainvalues'])) {
                    $_POST['newvalues'] = array_merge($_POST['mainvalues'], $_POST['newvalues']);
                    $_POST['ids'] = array_merge($_POST['mainids'], $_POST['ids']);
                }
                $staging = ($_POST['staging'] == 1) ? 1 : 0;
                $plugin = $invoice->setInvoicePluginValues($template_id);
                $plugin_array = json_decode($plugin, 1);

                $generate_estimate_invoice = ($_POST['generate_estimate_invoice'] > 0) ? 1 : 0;

                $check_inventory_gst_update = 0;
                if (empty($_POST['gst'])) {
                    $_POST['gst'] = $_POST['product_gst'];
                } else {
                    if (!empty($_POST['gst']) && !empty($_POST['product_gst'])) {
                        $check_inventory_gst_update = 1;
                    }
                }
                $revision = false;
                if ($_POST['template_type'] == 'construction') {
                    $_POST['totalcost'] = str_replace(',', '', $_POST['grand_total']);
                }
                if ($post_type == 'insert') {
                    $result = $this->model->saveInvoice($this->merchant_id, $this->user_id, $_POST['customer_id'], $template_id, $_POST['invoice_number'], $billdate, $duedate, $_POST['expiry_date'], $_POST['bill_cycle_name'], $_POST['newvalues'], $_POST['ids'], $_POST['invoice_narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $_POST['late_fee'], $_POST['advance'], $_POST['notify_patron'], $_POST['payment_request_status'], $_POST['franchise_id'], $_POST['vendor_id'], $this->session->get('system_user_id'), $_POST['request_type'], $payment_request_type, $_POST['autocollect_plan_id'], $_POST['has_custom_reminder'], $plugin, $_POST['billing_profile_id'], $generate_estimate_invoice, 0, $_POST['currency'], $_POST['einvoice_type'], $product_taxation_type);
                } else {
                    if ($plugin_array['save_revision_history'] == 1) {
                        $revision = true;
                        $revision_data['payment_request'] = $this->common->getSingleValue('payment_request', 'payment_request_id', $req_id);
                        $revision_data['invoice_column_values'] = $this->common->getListValue('invoice_column_values', 'payment_request_id', $req_id, 1);
                        if ($_POST['template_type'] == 'construction') {
                            $revision_data['invoice_construction_particular'] = $this->common->getListValue('invoice_construction_particular', 'payment_request_id', $req_id, 1);
                        } else {
                            $revision_data['invoice_particular'] = $this->common->getListValue('invoice_particular', 'payment_request_id', $req_id, 1);
                            $revision_data['invoice_tax'] = $this->common->getListValue('invoice_tax', 'payment_request_id', $req_id, 1);
                        }
                    }

                    $result = $this->model->updateInvoice($req_id, $this->user_id, $_POST['customer_id'], $_POST['invoice_number'], $billdate, $duedate, $_POST['expiry_date'], $_POST['bill_cycle_name'], $_POST['existvalues'], $_POST['existids'], $_POST['invoice_narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $_POST['late_fee'], $_POST['advance'], $_POST['notify_patron'], $_POST['payment_request_status'], $_POST['franchise_id'], $_POST['vendor_id'], $this->session->get('system_user_id'), $_POST['request_type'], $_POST['autocollect_plan_id'], $plugin, $staging, $_POST['billing_profile_id'], $generate_estimate_invoice, $_POST['currency'], $_POST['einvoice_type'], $product_taxation_type);
                    if (isset($_POST['newvalues'])) {
                        foreach ($_POST['newvalues'] as $k => $v) {
                            $this->model->saveInvoiceMetaValues($req_id, $_POST['ids'][$k], $v,  $this->user_id);
                        }
                    }
                }
                if ($result['message'] == 'success') {
                    $payment_request_id = $result['request_id'];

                    //save new covering note
                    if ($_POST['covering_id']) {

                        if ($post_type == 'insert') {
                            $coverdetails = $this->common->getSingleValue('covering_note', 'covering_id', $_POST['covering_id']);

                            $this->model->saveInvoiceCoveringNote($payment_request_id, $this->merchant_id, $coverdetails['template_name'], $coverdetails['body'], $coverdetails['subject'], $coverdetails['invoice_label'], $coverdetails['pdf_enable'], $this->user_id, 0);
                        } else {

                            $coverdetails = $this->common->getSingleValue('covering_note', 'covering_id', $_POST['covering_id']);

                            $this->model->saveInvoiceCoveringNote($payment_request_id, $this->merchant_id, $coverdetails['template_name'], $coverdetails['body'], $coverdetails['subject'], $coverdetails['invoice_label'], $coverdetails['pdf_enable'], $this->user_id, 1);
                        }
                    }
                    $link = $this->encrypt->encode($payment_request_id);
                    $this->model->saveInvoiceParticular($payment_request_id, $_POST['payment_request_status'], $_POST['particular_id'], $_POST['item'], $_POST['annual_recurring_charges'], $_POST['sac_code'], $_POST['description'], $_POST['qty'], $_POST['unit_type'], $_POST['rate'], $_POST['gst'], $_POST['tax_amount'], $_POST['discount_perc'], $_POST['discount'], $_POST['total_amount'], $_POST['narrative'], $this->session->get('system_user_id'), $this->merchant_id, $staging, 0, $_POST['mrp'], $_POST['product_expiry_date'], $_POST['product_number']);
                    $tax_det = $this->model->saveInvoiceTax($payment_request_id, $_POST['tax_id'], $_POST['tax_percent'], $_POST['tax_applicable'], $_POST['tax_amt'], $_POST['tax_detail_id'], $this->session->get('system_user_id'), $staging);
                    if ($post_type != 'insert') {
                        $this->common->genericupdate('invoice_vendor_commission', 'is_active', 0, 'payment_request_id', $payment_request_id, $this->user_id);
                    }
                    if ($_POST['vendor_id'] > 0) {
                        $vendor_id = $_POST['vendor_id'];
                        $this->model->saveVendorCommission($payment_request_id, $this->merchant_id, $vendor_id, $_POST['commision_amount'], $_POST['commision_type'], $_POST['commision_value'], $this->user_id);
                    }

                    //update inventory only for one particular if taxes added in template or in invoice and particular has 0 percent gst
                    if (count($_POST['item']) == 1 && $_POST['product_gst'][0] == 0) {
                        //find tax type 
                        if (!empty($_POST['tax_id'])) {
                            $update_inventory = 0;
                            foreach ($_POST['tax_id'] as $kt => $tval) {
                                if ($tval != '') {
                                    $find_tax_type = $this->common->getRowValue('tax_type', 'merchant_tax', 'tax_id', $tval);
                                    if ($_POST['tax_applicable'][$kt] > 0) {
                                        if ($find_tax_type == 1 || $find_tax_type == 2) {
                                            $gst_percent = $_POST['tax_percent'][$kt];
                                            $updated_gst_percent = $gst_percent * 2;
                                            $update_inventory = 1;
                                        } else if ($find_tax_type == 3) {
                                            $gst_percent = $_POST['tax_percent'][$kt];
                                            $updated_gst_percent = $gst_percent;
                                            $update_inventory = 1;
                                        }
                                    }
                                }
                            }
                            if ($update_inventory == 1) {
                                $find_product_id = $this->common->getRowValue('product_id', 'invoice_particular', 'payment_request_id', $payment_request_id);
                                $this->common->genericupdate('invoice_particular', 'gst', $updated_gst_percent, 'payment_request_id', $payment_request_id, $this->user_id, " and product_id='" . $find_product_id . "'");
                                $this->common->genericupdate('merchant_product', 'gst_percent', $updated_gst_percent, 'product_id', $find_product_id);
                            }
                        }
                    }

                    //if gst change while creating invoice then update gst_percent in inventory also
                    if ($check_inventory_gst_update == 1) {
                        $get_all_invoice_particualrs = $this->common->getListValue('invoice_particular', 'payment_request_id', $payment_request_id);
                        if (!empty($get_all_invoice_particualrs)) {
                            foreach ($get_all_invoice_particualrs as $ik => $ip_val) {
                                //find gst_percent for a particular from inventory (merchant_product table)
                                $find_gst_percent = $this->common->getRowValue('gst_percent', 'merchant_product', 'product_id', $ip_val['product_id']);
                                if ($find_gst_percent != $ip_val['gst']) {
                                    $this->common->genericupdate('merchant_product', 'gst_percent', $ip_val['gst'], 'product_id', $ip_val['product_id']);
                                }
                            }
                        }
                    }
                    //end update inventory logic

                    if ($tax_det['has_expense'] == 1) {
                        $this->uploadExpenseInvoicefile($payment_request_id);
                    }
                    if ($_POST['has_custom_reminder'] == 1) {
                        $this->model->saveInvoiceReminder($payment_request_id, $duedate, $_POST['reminders'], $_POST['reminder_subject'], $_POST['reminder_sms'], $this->merchant_id, $this->session->get('system_user_id'));
                    }
                    if ($_POST['template_type'] == 'travel_ticket_booking') {
                        $this->generic->setEmptyArray(array('texistid', 'btype', 'booking_date', 'journey_date', 'b_name', 'b_type', 'b_unit_type', 'b_sac_code', 'b_from', 'b_to', 'b_amt', 'b_charge', 'b_total'));
                        $this->generic->setArrayZeroValue(array('b_amt', 'b_charge', 'b_total'));
                        $this->model->saveTravelDetails($payment_request_id, $_POST['texistid'], $_POST['btype'], $_POST['booking_date'], $_POST['journey_date'], $_POST['b_name'], $_POST['b_type'], $_POST['b_unit_type'], $_POST['b_sac_code'], $_POST['b_from'], $_POST['b_to'], $_POST['b_amt'], $_POST['b_charge'], $_POST['b_total'], $this->session->get('userid'), $_POST['b_description'], $_POST['b_information']);
                    }

                    if ($_POST['template_type'] == 'construction') {
                        $this->generic->setArrayZeroValue(array(
                            'original_contract_amount', 'approved_change_order_amount', 'current_contract_amount', 'previously_billed_percent', 'previously_billed_amount', 'current_billed_percent', 'current_billed_amount', 'stored_materials', 'total_billed', 'retainage_percent', 'retainage_amount_previously_withheld', 'retainage_amount_for_this_draw', 'retainage_release_amount', 'total_outstanding_retainage'
                        ));
                        $this->common->genericupdate('payment_request', 'contract_id', $_POST['contract_id'], 'payment_request_id', $payment_request_id);
                        if ($post_type != 'insert') {
                            $this->common->genericupdate('invoice_construction_particular', 'is_active', 0, 'payment_request_id', $payment_request_id);
                        }
                        foreach ($_POST['bill_code'] as $k => $v) {
                            $this->model->saveConstructionDetails($payment_request_id, $_POST, $k, $this->user_id);
                        }
                    }


                    if ($_POST['template_type'] == 'travel') {
                        $this->generic->setEmptyArray(array('sec_booking_date', 'sec_journey_date', 'sec_name', 'sec_type', 'sec_unit_type', 'sec_sac_code', 'sec_from', 'sec_to', 'sec_from_date', 'sec_to_date', 'sec_item'));
                        $this->generic->setArrayZeroValue(array('sec_exist_id', 'sec_discount', 'sec_discount_perc', 'sec_tax_amount', 'sec_gst', 'sec_rate', 'sec_qty', 'sec_charge', 'sec_amount'));
                        foreach ($_POST['sec_type_value'] as $k => $st) {
                            if ($st == 'hb' || $st == 'fs') {
                                $_POST['sec_booking_date'][$k] = $_POST['sec_from_date'][$k];
                                $_POST['sec_journey_date'][$k] = $_POST['sec_to_date'][$k];
                                $_POST['sec_name'][$k] = $_POST['sec_item'][$k];
                            } else {
                                if ($st == 'tb') {
                                    $_POST['sec_type_value'][$k] = 'b';
                                } else {
                                    $_POST['sec_type_value'][$k] = 'c';
                                }
                            }
                        }
                        $this->model->saveTravelDetails($payment_request_id, $_POST['sec_exist_id'], $_POST['sec_type_value'], $_POST['sec_booking_date'], $_POST['sec_journey_date'], $_POST['sec_name'], $_POST['sec_type'], $_POST['sec_unit_type'], $_POST['sec_sac_code'], $_POST['sec_from'], $_POST['sec_to'], $_POST['sec_amount'], $_POST['sec_charge'], $_POST['sec_qty'], $_POST['sec_rate'], $_POST['sec_mrp'], $_POST['sec_product_expiry_date'], $_POST['sec_product_number'], $_POST['sec_discount_perc'], $_POST['sec_discount'], $_POST['sec_gst'], $_POST['sec_total_amount'], $this->session->get('userid'), $_POST['sec_description'], $_POST['sec_information']);
                    }

                    if ($_POST['template_type'] == 'franchise' || $_POST['template_type'] == 'nonbrandfranchise') {
                        if ($post_type != 'insert') {
                            $this->model->disableSale($payment_request_id, $this->user_id);
                        }
                        foreach ($_POST['sale_date'] as $k => $v) {
                            $sale_date = $v;
                            $date = $this->generic->sqlDate($sale_date);
                            $gross = ($_POST['gross_sale'][$k] > 0) ? $_POST['gross_sale'][$k] : 0;
                            $non_brand_gross = ($_POST['non_brand_gross_sale'][$k] > 0) ? $_POST['non_brand_gross_sale'][$k] : 0;

                            $this->model->saveFranchiseSale($_POST['customer_id'], $_POST['gs_id'][$k], $payment_request_id, $date, $gross, $non_brand_gross, $this->user_id);
                        }
                        $bill_period = $_POST['sale_date'][0] . ' To ' . $sale_date;
                        $this->generic->setArrayZeroValue(array('non_brand_gross_bilable_sale', 'non_brand_sale_tax', 'non_brand_net_bilable_sale', 'non_brand_gross_comm_percent', 'non_brand_gross_comm_amt', 'non_brand_waiver_comm_percent', 'non_brand_waiver_comm_amt', 'non_brand_net_comm_percent', 'non_brand_net_comm_amt'));
                        $this->model->saveFranchiseSummary($post_type, $payment_request_id, $_POST['gross_bilable_sale'], $_POST['net_bilable_sale'], $_POST['gross_comm_percent'], $_POST['waiver_comm_percent'], $_POST['net_comm_percent'], $_POST['gross_comm_amt'], $_POST['waiver_comm_amt'], $_POST['net_comm_amt'], $_POST['non_brand_gross_bilable_sale'], $_POST['non_brand_net_bilable_sale'], $_POST['non_brand_gross_comm_percent'], $_POST['non_brand_waiver_comm_percent'], $_POST['non_brand_net_comm_percent'], $_POST['non_brand_gross_comm_amt'], $_POST['non_brand_waiver_comm_amt'], $_POST['non_brand_net_comm_amt'], $_POST['penalty'], $bill_period, $this->user_id);
                    }

                    if (isset($_FILES['scan_file'])) {
                        if ($_FILES['scan_file']['error'] == 0) {
                            $folder_path = $this->merchant_id . '/' . $payment_request_id;
                            $url = $this->generic->uploadFile($_FILES['scan_file'], $folder_path, $link, 'invoice', 'S3BUCKET_INVOICE');
                            $this->common->genericupdate('payment_request', 'document_url', $url, 'payment_request_id', $payment_request_id);
                        }
                    }
                    //handle subscription code
                    if ($_POST['payment_request_status'] != 11) {
                        if ($_POST['notify_patron'] == 1) {  //payment_request_status not draft
                            $notification = $this->getNotificationObj();
                            if ($post_type == 'insert') {
                                $revised = 0;
                            } else {
                                $revised = 1;
                            }
                            $notification->sendInvoiceNotification($payment_request_id, $revised, 1, $_POST['custom_covering']);
                        }
                        if ($_POST['has_e_invoice'] == 1 &&  $payment_request_type != 4) {
                            $einvoice[] = array('id' => $payment_request_id, 'notify' => $_POST['notify_e_invoice']);
                            $array['source'] = 'Individual';
                            $array['data'] = $einvoice;
                            $this->apisrequest(env('SWIPEZ_BASE_URL') . 'api/v2/einvoice/queue', json_encode($array), []);
                        }
                    }

                    if ($revision == true) {
                        $this->storeRevision($req_id, $revision_data, $_POST['template_type']);
                    }

                    $success_array['notify_patron'] = $result['notify_patron'];
                    $success_array['invoice_type'] = $_POST['request_type'];
                    $success_array['link'] = $link;
                    $success_array['type'] = $post_type;
                    $mobile = $this->common->getRowValue('mobile', 'customer', 'customer_id', $_POST['customer_id']);
                    if (strlen($mobile) == 10) {
                        $mobile = '91' . $mobile;
                        $success_array['mobile'] = '&phone=' . $mobile;
                    } else {
                        $success_array['mobile'] = '';
                    }
                    $this->session->set('success_array', $success_array);

                    if ($payment_request_type == 4) {
                        $subscription['billdate'] = $billdate;
                        $subscription['duedate'] = $duedate;
                        $this->handleSubscription($payment_request_id, $subscription, $_POST['payment_request_status']);
                    }

                    if ($staging == 1) {
                        header("Location:/merchant/bulkupload/view/" . $link);
                    } else {
                        header("Location:/merchant/paymentrequest/view/" . $link);
                    }
                    die();
                } else {
                    SwipezLogger::error(__CLASS__, '[E004]Error while sending payment request to patron Error: from  merchant [' . $this->merchant_id . ']' . $result['Message']);
                    $this->setGenericError();
                }
            } else {
                if ($validate == NULL) {
                    $_POST['selecttemplate'] = $template_id;
                    $this->smarty->assign("haserrors", $hasErrors);
                    $type = ($_POST['request_type'] == 2) ? 'estimate' : 'invoice';
                    if ($post_type == 'insert') {
                        $this->create($type);
                    } else {
                        $link = $this->encrypt->encode($req_id);
                        $this->update($link);
                    }
                } else {
                    foreach ($hasErrors as $error_name) {
                        $error = '<b>' . $error_name[0] . '</b> -';
                        $int = 1;
                        while (isset($error_name[$int])) {
                            $error .= '' . $error_name[$int];
                            $int++;
                        }
                        $err[]['value'] = $error;
                    }
                    $haserror['error'] = $err;
                    echo json_encode($haserror);
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E005]Error while sending payment request Error: for merchant [' . $merchant_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function storeRevision($req_id, $revision, $template_type)
    {
        $user_id = $this->session->get('system_user_id');
        $new_payment_request = $this->common->getSingleValue('payment_request', 'payment_request_id', $req_id);
        $result = array_diff($revision['payment_request'], $new_payment_request);
        $revision_array = [];
        if (env('APP_ENV') != 'LOCAL') {
            $revision_column_json = Redis::get('revision_payment_request_column');
        }
        if ($revision_column_json == null) {
            $revision_column_json = '{"bill_date":"Bill date","due_date":"Due date","grand_total":"Grand total","currency":"Currency"}';
        }
        $revision_columns = json_decode($revision_column_json, 1);
        if (!empty($result)) {
            foreach ($result as $key => $row) {
                if (isset($revision_columns[$key])) {
                    $col_name = $revision_columns[$key];
                    $old_value = $revision['payment_request'][$key];
                    $new_value = $new_payment_request[$key];
                    if ($key == 'bill_date' || $key == 'due_date') {
                        $old_value = $this->generic->htmlDate($old_value);
                        $new_value = $this->generic->htmlDate($new_value);
                    }
                    if ($key == 'grand_total') {
                        $old_value = number_format($old_value, 2);
                        $new_value = number_format($new_value, 2);
                    }

                    $title =  'updated ' . ucfirst($col_name) . ' from ' . $old_value . ' to ' . $new_value;
                    $revision_array['payment_request'][$key] = array('title' => $title, 'old_value' => $revision['payment_request'][$key], 'new_value' => $new_payment_request[$key]);
                }
            }
        }
        $new_invoice_values = $this->common->getListValue('invoice_column_values', 'payment_request_id', $req_id, 1);

        $column_values = [];
        foreach ($revision['invoice_column_values'] as $row) {
            $column_values[$row['invoice_id']] = array('column_id' => $row['column_id'], 'value' => $row['value']);
        }
        $new_column_values = [];
        foreach ($new_invoice_values as $row) {
            $new_column_values[$row['invoice_id']] = array('column_id' => $row['column_id'], 'value' => $row['value']);
        }

        if (!empty($column_values)) {
            foreach ($column_values as $key => $row) {
                if ($column_values[$key]['value'] != $new_column_values[$key]['value']) {
                    $col_name = $this->common->getRowValue('column_name', 'invoice_column_metadata', 'column_id', $column_values[$key]['column_id']);
                    $title =  'updated ' . $col_name . ' from ' . $column_values[$key]['value'] . ' to ' . $new_column_values[$key]['value'];
                    $revision_array['invoice_column_values'][$key] = array('title' => $title, 'old_value' => $column_values[$key]['value'], 'new_value' => $new_column_values[$key]['value']);
                }
            }
        }

        if (!empty($revision_array)) {
            $version_count = $this->common->querysingle("select count(*) as total from invoice_revision where payment_request_id='" . $req_id . "'");

            if ($version_count['total'] > 0) {
                $version = $version_count['total'] + 1;
                $version = 'V' . $version;
            } else {
                $version = 'V1';
            }

            $new_particular = [];
            $old_particular = [];

            if ($template_type == 'construction') {
                $table_name = 'invoice_construction_particular';
            } else {
                $table_name = 'invoice_particular';
            }

            $new_construction_particular = $this->common->getListValue($table_name, 'payment_request_id', $req_id, 1);
            foreach ($revision[$table_name] as $row) {
                $id = $row['id'];
                $removeKeys = array('id', 'payment_request_id', 'calculated_perc', 'calculated_row', 'is_active', 'created_by', 'created_date', 'last_update_by', 'last_update_date');
                $row = array_diff_key($row, array_flip($removeKeys));
                $old_particular[$id] = $row;
            }
            foreach ($new_construction_particular as $row) {
                $id = $row['id'];
                $removeKeys = array('id', 'payment_request_id', 'calculated_perc', 'calculated_row', 'is_active', 'created_by', 'created_date', 'last_update_by', 'last_update_date');
                $row = array_diff_key($row, array_flip($removeKeys));
                $new_particular[$id] = $row;
            }

            foreach ($old_particular as $key => $row) {
                if (isset($new_particular[$key])) {
                    $array1 = $old_particular[$key];
                    $array2 = $new_particular[$key];
                    $result = array_diff($array1, $array2);
                    if (!empty($result)) {
                        $title =  'updated row';
                        $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'update', 'old_value' => $old_particular[$key], 'new_value' => $new_particular[$key]);
                    }
                } else {
                    $title =  'removed row';
                    $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'remove', 'old_value' => $old_particular[$key], 'new_value' => $new_particular[$key]);
                }
            }

            foreach ($new_particular as $key => $row) {
                if (!isset($old_particular[$key])) {
                    $title =  'added new row';
                    $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'add', 'old_value' => [], 'new_value' => $new_particular[$key]);
                }
            }

            if ($template_type != 'construction') {
               
                $new_tax = [];
                $old_tax = [];
                $table_name = 'invoice_tax';
                $new_construction_tax = $this->common->getListValue($table_name, 'payment_request_id', $req_id, 1);
                foreach ($revision[$table_name] as $row) {
                    $id = $row['id'];
                    $removeKeys = array('id', 'payment_request_id', 'calculated_perc', 'calculated_row', 'is_active', 'created_by', 'created_date', 'last_update_by', 'last_update_date');
                    $row = array_diff_key($row, array_flip($removeKeys));
                    $old_tax[$id] = $row;
                }
                foreach ($new_construction_tax as $row) {
                    $id = $row['id'];
                    $removeKeys = array('id', 'payment_request_id', 'calculated_perc', 'calculated_row', 'is_active', 'created_by', 'created_date', 'last_update_by', 'last_update_date');
                    $row = array_diff_key($row, array_flip($removeKeys));
                    $new_tax[$id] = $row;
                }

                foreach ($old_tax as $key => $row) {
                    if (isset($new_tax[$key])) {
                        $array1 = $old_tax[$key];
                        $array2 = $new_tax[$key];
                        $result = array_diff($array1, $array2);
                        if (!empty($result)) {
                            $title = 'updated row';
                            $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'update', 'old_value' => $old_tax[$key], 'new_value' => $new_tax[$key]);
                        }
                    } else {
                        $title = 'removed row';
                        $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'remove', 'old_value' => $old_tax[$key], 'new_value' => $new_tax[$key]);
                    }
                }

                foreach ($new_tax as $key => $row) {
                    if (!isset($old_tax[$key])) {
                        $title = 'added new row';
                        $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'add', 'old_value' => [], 'new_value' => $new_tax[$key]);
                    }
                }
            }

            $this->common->genericupdate('payment_request', 'revision_no', $version, 'payment_request_id', $req_id);
            $this->model->saveRevision($req_id, json_encode($revision_array), $version, $user_id);
        }
    }

    function saveInvoicePreview($payment_request_id)
    {
        if ($payment_request_id != '') {
            if (strlen($payment_request_id) != 10 || $payment_request_id != $_POST['payment_request_id']) {
                $this->setInvalidLinkError();
            }
            $notify_patron = $_POST['notify_patron'];
            //first update payment request status & notify patron into table
            $this->common->genericupdate('payment_request', 'payment_request_status', 0, 'payment_request_id', $payment_request_id);
            $this->common->genericupdate('payment_request', 'notify_patron', $notify_patron, 'payment_request_id', $payment_request_id);

            $link = $this->encrypt->encode($payment_request_id);
            $get_payment_request_details = $this->common->getSingleValue('payment_request', 'payment_request_id', $payment_request_id);
            $get_invoice_values = $this->common->querylist("select value from invoice_column_values where payment_request_id='$payment_request_id'");

            $invoice_values = array();
            foreach ($get_invoice_values as $k => $val) {
                $invoice_values[$k] = $val['value'];
            }

            $result = $this->model->saveInvoicePreview($this->merchant_id, $this->user_id, $payment_request_id, $get_payment_request_details['invoice_type'], $invoice_values, $get_payment_request_details['invoice_number'], $get_payment_request_details['payment_request_status'], $_POST['payment_request_type']);
            if ($result['message'] == 'success') {
                if (isset($notify_patron) && $notify_patron == 1) {
                    $notification = $this->getNotificationObj();
                    $revised = 0;
                    $notification->sendInvoiceNotification($payment_request_id, $revised, 1, $custom_covering = null);
                }

                //find payment_request_type
                if ($_POST['payment_request_type'] == 4) {
                    require_once UTIL . 'batch/Batch.php';
                    require_once UTIL . 'batch/subscription/subscription.php';
                    define('req_type', $payment_request_id);
                    header("Location:/merchant/subscription/success");
                } else {
                    $success_array['notify_patron'] = $get_payment_request_details['notify_patron'];
                    $success_array['invoice_type'] = $get_payment_request_details['invoice_type'];
                    $success_array['link'] = $link;
                    $success_array['type'] = 'insert';
                    $mobile = $this->common->getRowValue('mobile', 'customer', 'customer_id', $get_payment_request_details['customer_id']);
                    if (strlen($mobile) == 10) {
                        $mobile = '91' . $mobile;
                        $success_array['mobile'] = '&phone=' . $mobile;
                    } else {
                        $success_array['mobile'] = '';
                    }
                    $this->session->set('success_array', $success_array);
                    header("Location:/merchant/paymentrequest/view/" . $link);
                }
            } else {
                $this->common->genericupdate('payment_request', 'payment_request_status', 11, 'payment_request_id', $payment_request_id);
                SwipezLogger::error(__CLASS__, '[E004]Error while converting draft payment request : from  merchant [' . $this->merchant_id . ']' . $result['message']);
                $this->setGenericError();
            }
            die();
        }
    }

    function uploadExpenseInvoicefile($payment_request_id)
    {
        $link = $this->encrypt->encode($payment_request_id);
        require_once CONTROLLER . 'merchant/Paymentrequest.php';
        $invoice = new Paymentrequest();
        $file_path = $invoice->download($link, 1);

        $bucket = getenv('S3BUCKET_INVOICE');
        require_once UTIL . 'SiteBuilderS3Bucket.php';
        $aws = new SiteBuilderS3Bucket('ap-south-1');
        $keyname = $this->merchant_id . '/' . $payment_request_id . '/' . $link . '.pdf';
        $fileurl = $aws->putBucket($bucket, $keyname, $file_path, 'pdf');
        $this->common->genericupdate('staging_expense', 'file_path', $fileurl, 'payment_request_id', $payment_request_id);
    }

    function handleSubscription($payment_request_id, $data = null, $payment_request_status = 0)
    {
        require_once MODEL . 'merchant/SubscriptionModel.php';
        $model = new SubscriptionModel();
        if ($_POST['subscription_id'] > 0) {
            $model->update_Subscription($_POST['subscription_id'], $this->user_id, $data['mode'], $data['repeat_every'], $data['repeat_on'], $data['billdate'], $data['end_mode'], $data['end_date'], $data['occurrence'], $data['display_text'], $data['duedate'], $data['date_diff'], $data['carry_due'], $data['billdate'], $data['billing_start_date'], $data['billing_period'], $data['period_type']);
        } else {
            $model->save_Subscription($payment_request_id, $this->merchant_id, $this->user_id, $data['mode'], $data['repeat_every'], $data['repeat_on'], $data['billdate'], $data['end_mode'], $data['end_date'], $data['occurrence'], $data['display_text'], $data['duedate'], $data['date_diff'], $data['carry_due'], $data['billdate'], $data['billing_start_date'], $data['billing_period'], $data['period_type']);
            define('req_type', $payment_request_id);
        }

        require_once UTIL . 'batch/Batch.php';
        require_once UTIL . 'batch/subscription/subscription.php';
        if ($payment_request_status == 11) {
            $link = $this->encrypt->encode($payment_request_id);
            header("Location:/merchant/paymentrequest/view/" . $link);
        } else {
            header("Location:/merchant/subscription/success");
        }
        die();
    }

    /**
     * Update invoice saved
     */
    function invoiceupdate()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header("Location:/merchant/paymentrequest/viewlist");
            }
            $req_id = $this->encrypt->decode($_POST['payment_request_id']);
            $this->invoicesave(NULL, $req_id, 'update');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E005]Error while sending payment request Error: for merchant [' . $merchant_id . '] , for payment request id [' . $payment_request_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
