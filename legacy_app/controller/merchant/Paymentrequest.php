<?php
/**
 * Payment request functionality for viewing payment requests, confirming and invoking payment gateway
 * 
 */
//use PDF;

class Paymentrequest extends Controller
{

    function __construct()
    {
        parent::__construct();

        $this->validateSession('merchant');
        $this->view->selectedMenu = array(5, 28);
        $this->view->js = array('invoice');
        //find company column name from customer metadata
        if ($this->session->has('customer_company_label')) {
            $this->get_custom_company_col_name = $this->session->get('customer_company_label');
            $this->smarty->assign("company_column_name", $this->session->get('customer_company_label'));
        } else {
            $this->get_custom_company_col_name = $this->common->getRowValue('column_name', 'customer_column_metadata', 'column_datatype', 'company_name', 1, " and merchant_id='" . $this->merchant_id . "'");
            $this->get_custom_company_col_name =  ($this->get_custom_company_col_name != false) ? $this->get_custom_company_col_name : 'Company name';
            $this->smarty->assign("company_column_name", $this->get_custom_company_col_name);
            $this->session->set('customer_company_label', $this->get_custom_company_col_name);
        }
    }

    /**
     * View listing of payment requests assigned to the patron
     * 
     */
    public function viewlist()
    {
        try {
            $this->hasRole(1, 6);
            $merchant_id = $this->session->get('merchant_id');
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');

            $redis_items = $this->getSearchParamRedis('invoice_estimate_list');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $invoice_status = $_POST['invoice_status'];
                $cycle_selected = isset($_POST['cycle_name']) ? $_POST['cycle_name'] : '';
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $invoice_status = '0';
                $cycle_selected = '';
            }

            //find last search criteria into Redis 
            if(isset($redis_items['invoice_estimate_list']['search_param']) && $redis_items['invoice_estimate_list']['search_param']!=null) {
                $from_date = $redis_items['invoice_estimate_list']['search_param']['from_date'];
                $to_date = $redis_items['invoice_estimate_list']['search_param']['to_date'];
                $invoice_status = $redis_items['invoice_estimate_list']['search_param']['invoice_status'];
                $_POST['invoice_type'] = $redis_items['invoice_estimate_list']['search_param']['invoice_type'];
            }
            $this->view->showLastRememberSearchCriteria = true;
            
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            //$cycle_selected = isset($_POST['cycle_name']) ? $_POST['cycle_name'] : '';
            $cycle_list = array();
            #$cycle_list = $this->model->getCycleList($this->session->get('userid'), $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));


            $_SESSION['display_column'] = array();
            $_SESSION['_from_date'] = $fromdate->format('Y-m-d');
            $_SESSION['_to_date'] = $todate->format('Y-m-d');
            $_SESSION['_cycle_name'] = $cycle_selected;
            $_SESSION['_bulk_id'] = 0;
            $_SESSION['_type'] = 1;
            $_SESSION['_invoice_type'] = $_POST['invoice_type'];
            $_SESSION['_invoice_status'] = $invoice_status;

            $_SESSION['default_timezone'] = $this->session->get('default_timezone');
            $_SESSION['default_time_format'] = $this->session->get('default_time_format');
            $_SESSION['default_date_format'] = $this->session->get('default_date_format');

            if ($this->session->get('customer_default_column')) {
                $columns = $this->session->get('customer_default_column');
                $_SESSION['_customer_code_text'] = $columns['customer_code'];
            }
            $this->session->set('valid_ajax', 'payment_request_list');
            $this->smarty->assign("from_date",  $this->generic->formatDateString($from_date) );
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("cycle_selected", $cycle_selected);
            $this->smarty->assign("cycle_list", $cycle_list);
            $this->smarty->assign("invoice_type", $_POST['invoice_type']);
            $this->smarty->assign("invoice_status", $invoice_status);
            $this->smarty->assign("title", 'Invoice / Estimate list&nbsp;');
            $this->smarty->assign("is_filter", "True");
            $this->view->selectedMenu = array(5, 28);
            $this->view->hide_first_col = true;
            $this->view->hide_col_index = 4;
            $this->view->list_name = 'invoice_estimate_list';
            $this->view->title = 'Invoice / Estimate list';
            $this->view->js = array('transaction');

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Sales', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->sum_column = 4;
            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'paymentrequest.php';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/paymentrequest/list.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while payment request list initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    private function getWhatsapptext($info)
    {
        $link = $this->view->server_name . '/patron/paymentrequest/view/' . $this->encrypt->encode($info['payment_request_id']);
        if (strlen($info['customer_mobile']) == 10) {
            $mobile = '91' . $info['customer_mobile'];
            $mobile = '&phone=' . $mobile;
        } else {
            $mobile = '';
        }
        $sms = "Your latest invoice by " . $info['company_name'] . " for " . $this->moneyFormatIndia($info['grand_total']) . " is ready. Click here to view your invoice and make the payment online - " . $link;
        return 'https://api.whatsapp.com/send?text=' . $sms . $mobile;
    }


    public function getRevisionDetails($request_id = null)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'payment_request_list') {
            if ($request_id == null) {
                $request_id = $_POST['payment_request_id'];
            }
            $request_id = $this->encrypt->decode($request_id);
            if (strlen($request_id) != 10) {
                $this->setInvalidLinkError();
            }
            $user_name = $this->session->get('display_name');
            $request = $this->common->getSingleValue('payment_request', 'payment_request_id', $request_id);
            $invoice_create_by = $this->common->getRowValue('first_name', 'user', 'user_id', $request['created_by']);
            $invoice_create_by = ($invoice_create_by == $user_name) ? 'You' : $invoice_create_by;
            $this->smarty->assign("request_link", $this->encrypt->encode($request_id));
            $this->smarty->assign("invoice_create_by", $invoice_create_by);
            $this->smarty->assign("invoice_create_date", $this->generic->formatTimeString($request['created_date']));

            $revision = $this->common->getListValue('invoice_revision', 'payment_request_id', $request_id, 1," order by id desc");
            $this->smarty->assign("count", count($revision)-1);
            foreach ($revision as $k => $v) {
                $revision[$k]['link'] = $this->encrypt->encode($v['id']);
                $created_by = $this->common->getRowValue('first_name', 'user', 'user_id', $v['last_update_by']);
                $created_by = ($created_by == $user_name) ? 'You' : $created_by;
                $revision[$k]['user_name'] = $created_by;
                $revision[$k]['array'] = json_decode($v['json'], 1);
                $revision[$k]['last_update_date'] = $this->generic->formatTimeString($v['last_update_date']);
            }
            $this->smarty->assign("revision", $revision);

            $this->smarty->display(VIEW . 'merchant/paymentrequest/revision_detail.tpl');
            //echo json_encode($list);
        }
    }

    public function view($link)
    {

        try {
            header('Content-Type: text/html; charset=UTF-8');
            $this->hasRole(1, 6);
            $payment_request_id = $this->encrypt->decode($link);
            if (strlen($payment_request_id) != 10) {
                $this->setInvalidLinkError();
            }

            if ($this->session->has('help_hero_popup')) {
                $this->session->remove('help_hero_popup');
            }

            $info = $this->common->getPaymentRequestDetails($payment_request_id, $this->merchant_id);
            if ($info['message'] != 'success' || $info['template_type'] == 'event') {
                $this->setInvalidLinkError();
            }
            if ($info['template_type'] == 'construction') {
                header('Location: /merchant/invoice/viewg703/' . $link);
                die();
            }
            if (!empty($info['design_name'])) {
                header('Location: /merchant/invoice/view/' . $link);
                die();
            }

            //remove non-printable characters from tnc
            $info['tnc'] = preg_replace('/[[:^print:]]/', '', $info['tnc']);

            $banklist = $this->common->querylist("select config_key, config_value from config where config_type='Bank_name'");
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $smarty = $invoice->asignSmarty($info, $banklist, $payment_request_id);
            $smarty['user_name'] = $this->session->get('user_name');
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }

            if ($info['invoice_type'] == 1) {
                $title_type = "invoice";
            } else {
                $title_type = "estimate";
            }

            $this->view->title = 'View ' . $title_type;
            $this->view->minwidth = '';

            if ($this->session->get('success_array')) {
                $whatsapp_share = $this->getWhatsapptext($info);
                $success_array = $this->session->get('success_array');
                $active_payment = $this->session->get('has_payment_active');
                $this->session->remove('success_array');
                $this->smarty->assign("invoice_success", true);
                $this->smarty->assign("whatsapp_share", $whatsapp_share);
                foreach ($success_array as $key => $val) {
                    $this->smarty->assign($key, $val);
                }
                if ($this->session->get('has_payment_active') == false) {
                    $this->session->set('has_payment_active', $this->model->isPaymentActive($this->merchant_id));
                }
                if ($success_array['type'] == 'insert' && $active_payment == false) {
                    $this->smarty->assign("payment_gateway_info", true);
                }
            }
            $template_types = array('isp', 'franchise', 'nonbrandfranchise', 'travel');
            if (in_array($info['template_type'], $template_types)) {
                $template_type = $info['template_type'];
            } else {
                $template_type = 'detail';
            }
            $plugin = json_decode($info['plugin_value'], 1);
            if ($plugin['has_partial'] == 1) {
                $partial_payments = $this->common->querylist("call get_partial_payments('" . $payment_request_id . "')");
                $this->smarty->assign("partial_payments", $partial_payments);
            }

            $this->smarty->assign('title', $this->view->title);
            //Breadcumbs array start
            if ($info['payment_request_type'] == 5) {
                $breadcumbs_array = array(
                    array('title' => 'Sales', 'url' => ''),
                    array('title' => 'Subscriptions', 'url' => '/merchant/subscription/viewlist'),
                    array('title' => $this->view->title, 'url' => '')
                );
            } else {
                $breadcumbs_array = array(
                    array('title' => 'Sales', 'url' => ''),
                    array('title' => 'Invoices/Estimates', 'url' => '/merchant/paymentrequest/viewlist'),
                    array('title' => $this->view->title, 'url' => '')
                );
            }


            $this->smarty->assign("links", $breadcumbs_array);
            $this->view->selectedMenu = array(5, 28);
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_' . $template_type . '.tpl');
            $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_footer.tpl');
            $this->view->render('footer/invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E009]Error while payment request view initiate Error:for merchant [' . $this->merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Confirm payment before placing the order
     * 
     * @param type $link
     */
    public function pay($link)
    {
        try {
            $this->hasRole(1, 6);
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/paymentrequest/view/' . $link);
            } else {
                $this->view->payment_request_id = $link;
                $payment_request_id = $this->encrypt->decode($link);
                $this->view->title = 'Confirm your payment';
                $this->view->header_file = ['profile'];
                $this->view->render('header/app');
                $this->view->render('withmenu');
                $this->view->render('merchant/paymentrequest/pay');
                $this->view->render('footer/footer');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E010]Error while payment request pay initiate Error: for merchant [' . $merchant_id . '] and with payment request id[' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Invoke payment gateway
     * 
     */
    public function payment()
    {
        try {
            if (empty($_POST['payment_req'])) {
                header('Location:/merchant/paymentrequest/viewlist');
            }
            require MODEL . 'patron/PaymentrequestModel.php';
            $this->model->invokePaymentGateway($_POST['payment_req'], $this->session->get('userid'));
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E011]Error while invoke payment gateway Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Invoke payment gateway
     * 
     */
    public function estimateinvoice()
    {
        try {
            if (empty($_POST['estimate_req_id'])) {
                header('Location:/merchant/paymentrequest/viewlist');
                die();
            }
            $this->common->queryexecute("call convert_estimate_to_invoice(:request_id,@request_id)", array(':request_id' => $_POST['estimate_req_id']));
            $row = $this->common->querysingle("select @request_id");

            if (isset($row['@request_id'])) {
                if ($_POST['send_invoice'] == 1) {
                    $notification = $this->getNotificationObj();
                    $notification->sendInvoiceNotification($row['@request_id'], 0, 1);
                }
                if (isset($_POST['has_online_payments'])) {
                    $info = $this->common->getPaymentRequestDetails($_POST['estimate_req_id'], $this->merchant_id);
                    $plugin = json_decode($info['plugin_value'], 1);

                    foreach ($plugin as $pk => $pval) {
                        if ($pk == 'has_online_payments') {
                            $plugin[$pk]['has_online_payments'] = $_POST['has_online_payments'];
                        }
                        if ($pk == 'enable_payments') {
                            $plugin[$pk]['enable_payments'] = $_POST['enable_payments'];
                        }
                    }
                    $this->common->genericupdate('payment_request', 'plugin_value', json_encode($plugin, true), 'payment_request_id', $info['converted_request_id']);
                }
                $this->session->set('successMessage', 'Invoice have been generated.');
                $link = $this->encrypt->encode($row['@request_id']);
                header('Location:/merchant/paymentrequest/view/' . $link);
                die();
            } else {
                SwipezLogger::error(__CLASS__, '[E011+988]Error while convert estimate to invoice : ' . json_encode($row));
                Sentry\captureMessage('Convert estimate to invoice failed: ' . json_encode($row));
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E011]Error while invoke payment gateway Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function estimatesettle()
    {
        try {
            if (empty($_POST['estimate_req_id'])) {
                header('Location:/merchant/paymentrequest/viewlist');
                die();
            }
            $generate_estimate_invoice = $this->common->getRowValue('generate_estimate_invoice', 'payment_request', 'payment_request_id', $_POST['estimate_req_id']);
            if ($generate_estimate_invoice == 1) {
                $this->common->queryexecute("call convert_estimate_to_invoice(:request_id,@request_id)", array(':request_id' => $_POST['estimate_req_id']));
                $row = $this->common->querysingle("select @request_id");
            } else {
                $row['@request_id'] = $_POST['estimate_req_id'];
            }
            if (isset($row['@request_id'])) {
                $_POST['payment_req_id'] = $row['@request_id'];
                if ($_POST['send_receipt'] == 1) {
                    $_POST['receipt_invoice'] = 1;
                }
                $this->respond();
            } else {
                SwipezLogger::error(__CLASS__, '[E011+988]Error while convert estimate to invoice : ' . json_encode($row));
                Sentry\captureMessage('Convert estimate to invoice failed : ' . json_encode($row));
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E011]Error while invoke payment gateway Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function delete($link)
    {
        try {
            $this->hasRole(3, 6);
            $payment_request_id = $this->encrypt->decode($link);

            if (strlen($payment_request_id) != 10 || $_SERVER["HTTP_REFERER"] == '') {
                throw new Exception('Invalid link');
            } else {

                $this->model->updatePaymentRequestStatus($payment_request_id, 3);
                $this->common->queryexecute("select delete_ledger('" . $payment_request_id . "',1)");
                $this->common->queryexecute("call `stock_management`('" . $this->merchant_id . "','" . $payment_request_id . "',3,2);");
                             
                $paymentRequestData = $this->model->getPaymentRequestRow($payment_request_id); 
                foreach($paymentRequestData["change_order_id"] as $co_id){
                    $this->model->updateCOStatus($co_id); 
                }
                
                $this->session->set('successMessage', 'Invoice have been deleted.');
                header("Location:" . $_SERVER["HTTP_REFERER"]);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E011-87]Error while delete invoice Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Respond as an offline request
     */
    public function respond()
    {
        try {
            $this->validatePackage();
            $this->hasRole(2, 6);
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location: /merchant/paymentrequest/viewlist');
                die();
            }
            if (strlen($_POST['payment_req_id']) != 10) {
                header('Location: /merchant/paymentrequest/viewlist');
                die();
            }
            require CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            $validator->validateRespond();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $coupon_id = 0;
                $discount = 0;
                if ($_POST['coupon_used'] == 1) {
                    $coupon_id = $_POST['coupon_id'];
                    $discount = $_POST['discount'];
                }
                $date = new DateTime($_POST['date']);

                $is_partial = (isset($_POST['is_partial']) > 0) ? 1 : 0;

                $PaymentRequestRow = $this->model->getPaymentRequestRow($_POST['payment_req_id']);
                if($PaymentRequestRow[0]['payment_request_status'] == '2' && $PaymentRequestRow[0]['grand_total'] > $_POST['amount']) {
                    if(!empty($_POST['offline_response_id'])) {
                        $offlineResponseId = $this->encrypt->decode($_POST['offline_response_id']);
                        $result = $this->model->respondUpdate($_POST['amount'], $_POST['bank_name'], $offlineResponseId, $_POST['payment_req_id'], $date->format('Y-m-d'), $_POST['response_type'], $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $coupon_id, $discount, $this->user_id, $_POST['deduct_amount'], $_POST['deduct_text'], $_POST['cheque_status'], 1, $_POST['cod_status']);
                    }

                } else {
                    $result = $this->model->respond($_POST['amount'], $_POST['bank_name'], $_POST['payment_req_id'], $date->format('Y-m-d'), $_POST['response_type'], $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $coupon_id, $discount, $this->user_id, $_POST['deduct_amount'], $_POST['deduct_text'], $_POST['cheque_status'], $is_partial, $_POST['cod_status']);
                }
                //$result = $this->model->respond($_POST['amount'], $_POST['bank_name'], $_POST['payment_req_id'], $date->format('Y-m-d'), $_POST['response_type'], $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $coupon_id, $discount, $this->user_id, $_POST['deduct_amount'], $_POST['deduct_text'], $_POST['cheque_status'], $is_partial, $_POST['cod_status']);

                if ($result['message'] != 'success') {
                    SwipezLogger::error(__CLASS__, '[E012]Error while merchant respond payment request Merchant id: ' . $this->merchant_id . ' Error: ' . json_encode($result));
                    Sentry\captureMessage('Payment request settelement Merchant id: ' . $this->merchant_id . ' Error: ' . json_encode($result));
                    $this->setGenericError();
                } else {
                    if($is_partial) {
                        $this->common->queryexecute("call set_partialypaid_amount_without_plugin('" . $_POST['payment_req_id'] . "');");
                    } else {
                        $this->common->queryexecute("call set_partialypaid_amount('" . $_POST['payment_req_id'] . "');");
                    }
                    $this->smarty->assign("success", 'Offline transaction save successfully.');
                    $file_name = null;

                    if (isset($_POST['estimate_req_id'])) {
                        $this->common->genericupdate('offline_response', 'estimate_request_id', $_POST['estimate_req_id'], 'offline_response_id', $result['offline_response_id']);
                        $this->common->genericupdate('payment_request', 'payment_request_status', 2, 'payment_request_id', $_POST['estimate_req_id']);
                    }

                    if ($_POST['receipt_invoice'] == 1) {
                        $reqlink = $this->encrypt->encode($_POST['payment_req_id']);
                        $file_name = $this->download($reqlink, 1);
                    }
                    require_once CONTROLLER . '/Notification.php';
                    $secure = new Notification();
                    if ($_POST['send_receipt'] == 1 && $_POST['notification_type'] != 2) {
                        $receipt_info = $secure->sendMailReceipt($result['offline_response_id'], 0, $file_name);
                    } else {
                        $receipt_info = $this->common->getReceipt($result['offline_response_id'], 'offline');
                    }

                    if ($_POST['send_receipt'] == 1) {
                        if ($_POST['notification_type'] != 1) {
                            $receipt_info['sms_name'] = $receipt_info['company_name'];
                            $long_url = $this->view->server_name . '/patron/transaction/receipt/' . $this->encrypt->encode($result['offline_response_id']);
                            $shortUrl = $secure->saveShortUrl($long_url);
                            $receipt_info['transaction_short_url'] = $shortUrl;
                            $secure->sendSMSReceiptCustomer($receipt_info, $receipt_info['patron_mobile'], 0);
                        }
                    }

                    $receipt_info['BillingEmail'] = $receipt_info['patron_email'];
                    $receipt_info['MerchantRefNo'] = $receipt_info['transaction_id'];
                    $receipt_info['BillingName'] = $receipt_info['patron_name'];
                    $receipt_info['merchant_name'] = $receipt_info['company_name'];
                    $receipt_info['is_offline'] = 1;

                    //get payment receipt feilds if plugin is on 
                    //get template details for showing custom configure fields
                    $template_info = $this->common->getSingleValue('payment_request', 'payment_request_id', $_POST['payment_req_id']);
                    $receipt_info['has_customized_payment_receipt'] = 0;

                    if (isset($template_info['plugin_value']) && !empty($template_info['plugin_value'])) {
                        //check if custom configure receipt fields plugin is on or not
                        $plugin_value = json_decode($template_info['plugin_value'], 1);

                        if (isset($plugin_value['has_customized_payment_receipt']) && $plugin_value['has_customized_payment_receipt'] == 1) {
                            $receipt_info['has_customized_payment_receipt'] = 1;
                            if (!empty($plugin_value['receipt_fields'])) {
                                $receipt_info['custom_fields'] = $this->common->setReceiptFields($receipt_info, $plugin_value['receipt_fields'], $type = "Offline");
                            }
                        }
                    }
                    //end receipt field logic

                    $logo = '';
                    if ($receipt_info['image'] == '') {
                        if ($receipt_info['merchant_logo'] != '') {
                            $logo = '/uploads/images/landing/' . $receipt_info['merchant_logo'];
                        }
                    } else {
                        $logo = '/uploads/images/logos/' . $receipt_info['image'];
                    }
                    $this->smarty->assign("logo", $logo);
                    $this->view->title = "Offline Settlement";
                    $this->smarty->assign("response", $receipt_info);
                    $this->view->header_file = ['profile'];
                    $this->view->render('header/app');
                    $this->smarty->display(VIEW . 'merchant/transaction/offlinereceipt.tpl');
                    $this->view->render('footer/profile');
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->viewlist();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E013]Error while merchant payment respond  Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Get custom configure receipt fileds
     */
    public function getReceiptFields($payment_request_id, $customer_id, $receipt_fields)
    {
        $receipt_info = array();
        if (!empty($receipt_fields)) {
            foreach ($receipt_fields as $k => $val) {
                $find_invoice_metadata_details = $this->common->getSingleValue('invoice_column_metadata', 'column_id', $val['column_id']);

                //find values for custom fields
                if ($find_invoice_metadata_details['save_table_name'] == 'customer') {
                    if ($find_invoice_metadata_details['column_name'] == 'Customer code') {
                        //$column_name = "customer_code";
                        //$getValue = $this->common->getRowValue('customer_code', 'customer', 'customer_id', $customer_id);
                        //$receipt_info['custom_fields'][$find_invoice_metadata_details['column_name']]= $getValue;
                    } else if ($find_invoice_metadata_details['column_name'] == 'Email ID') {
                        //$column_name = "email";
                    } else if ($find_invoice_metadata_details['column_name'] == 'Mobile no') {
                        $column_name = "mobile";
                    } else if ($find_invoice_metadata_details['column_name'] == 'Address') {
                        $column_name = "address";
                    } else if ($find_invoice_metadata_details['column_name'] == 'City') {
                        $column_name = "city";
                    } else if ($find_invoice_metadata_details['column_name'] == 'State') {
                        $column_name = "state";
                    } else if ($find_invoice_metadata_details['column_name'] == 'Zipcode') {
                        $column_name = "zipcode";
                    }

                    if ($column_name != '') {
                        $getValue = $this->common->getRowValue($column_name, 'customer', 'customer_id', $customer_id);
                        $receipt_info[$find_invoice_metadata_details['column_name']] = $getValue;
                    }
                } else if ($find_invoice_metadata_details['save_table_name'] == 'customer_metadata') {
                    //take values customer_column_values table
                    //find customer metadata values from customer_column_values
                    $getValue = $this->common->getRowValue('value', 'customer_column_values', 'column_id', $find_invoice_metadata_details['customer_column_id'], 0, " and customer_id='" . $customer_id . "'");
                    $receipt_info[$find_invoice_metadata_details['column_name']] = $getValue;
                } else if ($find_invoice_metadata_details['save_table_name'] == 'request') {
                    if ($find_invoice_metadata_details['column_name'] == 'Due date') {
                        $column_name = 'due_date';
                    } else if ($find_invoice_metadata_details['column_name'] == 'Billing cycle name') {
                        $column_name = 'billing_cycle_id';
                    } else if ($find_invoice_metadata_details['column_name'] == 'Bill date') {
                        $column_name = 'bill_date';
                    }
                    if ($column_name != '') {
                        $getValue = $this->common->getRowValue($column_name, 'payment_request', 'payment_request_id', $payment_request_id);
                        $receipt_info[$find_invoice_metadata_details['column_name']] = $getValue;
                    }
                } else if ($find_invoice_metadata_details['save_table_name'] == 'metadata') {
                    //find value for column_id
                    $getValue = $this->common->getRowValue('value', 'invoice_column_values', 'column_id', $find_invoice_metadata_details['column_id'], 0, " and payment_request_id='" . $payment_request_id . "'");
                    $receipt_info[$find_invoice_metadata_details['column_name']] = $getValue;
                }
            }
        }
        return $receipt_info;
    }

    public function download($link, $savepdf = 0)
    {
        try {
            ini_set("pcre.backtrack_limit", "2500000");
            $payment_request_id = $this->encrypt->decode($link);
            if (strlen($payment_request_id) != 10) {
                $payment_request_id = $this->encryptBackup($link);
                $link = $this->encrypt->encode($payment_request_id);
            }
            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E008]Error while geting invoice details. for payment request id [' . $payment_request_id . ']');
                Sentry\captureMessage('Invoice details empty. for payment request id [' . $payment_request_id . ']');
                exit();
                return;
            }
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);

            //remove non-printable characters from tnc
            $info['tnc'] = preg_replace('/[[:^print:]]/', '', $info['tnc']);

            $this->smarty->assign('info', $info);
            $this->smarty->assign('is_merchant', 1);
            $smarty = $invoice->asignSmarty($info, array(), $payment_request_id);
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }
            #require_once('/opt/app/lib/MPDF60/mpdf.php');
            $template_type = 'detail';
            $templates = array('isp', 'franchise', 'nonbrandfranchise', 'travel');

            if (in_array($info['template_type'], $templates)) {
                $template_type = $info['template_type'];
            }
            $margin_top = 5;
            if ($info['template_type'] == 'scan') {
                $margin_top = 10;
            }
            $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            $html .= $this->smarty->fetch(VIEW . 'pdf/invoice_' . $template_type . '.tpl');
            if ($savepdf == 2) {
                echo $html . '<script type="text/javascript"> window.print(); </script>';
                die();
            }

            $mpdf = new \Mpdf\Mpdf([
                'mode' => '',
                'format' => 'A4',
                'default_font_size' => 0,
                'default_font' => '',
                'margin_left' => 2,
                'margin_right' => 2,
                'margin_top' => $margin_top,
                'margin_bottom' => 7,
                'margin_header' => 2,
                'margin_footer' => 5,
                'fontDir' => array_merge($fontDirs, [
                    FONT_PATH . '/mpdf/custom-fonts',
                ]),
                'fontdata' => $fontData + [
                    "homemadeapple" => [
                        'R' => "HomemadeApple-Regular.ttf",
                    ],
                    "mrssaintdelafield" => [
                        'R' => "MrsSaintDelafield-Regular.ttf",
                    ],
                    "marckscript" => [
                        'R' => "MarckScript-Regular.ttf",
                    ],
                    "kristi" => [
                        'R' => "Kristi-Regular.ttf",
                    ],
                    "architectsdaughter" => [
                        'R' => "ArchitectsDaughter-Regular.ttf"
                    ]
                ],
            ]);
            $mpdf->setFooter('Page {PAGENO} of {nb}');
            //$mpdf->SetImportUse();
            $mpdf->shrink_tables_to_fit = 1;
            $mpdf->WriteHTML($html);
            $mpdf->SetDisplayMode('fullpage');
            if ($info['document_url'] != '') {
                $fileInfo = pathinfo($info['document_url']);
                $mpdf->AddPage();
                if ($fileInfo['extension'] == 'pdf') {
                    $s3file = file_get_contents($info['document_url']);
                    file_put_contents(TMP_FOLDER . $fileInfo['basename'], $s3file);
                    $pagecount = $mpdf->SetSourceFile(TMP_FOLDER . $fileInfo['basename']);
                    $tplId = $mpdf->ImportPage($pagecount);
                    $mpdf->UseTemplate($tplId);
                    unlink(TMP_FOLDER . $fileInfo['basename']);
                } else {
                    $mpdf->WriteHTML('<img src="' . $info['document_url'] . '"/>');
                }
            }
            //$mpdf->Output();

            if ($savepdf == 1) {
                $name = 'invoice_' . time();
                $file_name = TMP_FOLDER . $name . '.pdf';
                $mpdf->Output($file_name, 'F');
                return $file_name;
            } else {
                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');
                $mpdf->Output($name . '.pdf', 'D');
                exit();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01pdf3]Error while download pdf  Error: for request id [' . $payment_request_id . '] ' . $e->getMessage());
        }
    }

    public function download_tcpdf($link, $savepdf = 0)
    {
        try {
            // Include the main TCPDF library (search for installation path).
            require_once(base_path() . '/vendor/tecnickcom/tcpdf/tcpdf.php');
            ini_set("pcre.backtrack_limit", "2500000");
            $payment_request_id = $this->encrypt->decode($link);
            if (strlen($payment_request_id) != 10) {
                $payment_request_id = $this->encryptBackup($link);
                $link = $this->encrypt->encode($payment_request_id);
            }
            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');

            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E008]Error while geting invoice details. for payment request id [' . $payment_request_id . ']');
                Sentry\captureMessage('Invoice details empty. for payment request id [' . $payment_request_id . ']');
                exit();
                return;
            }

            //remove non-printable characters from tnc
            $info['tnc'] = preg_replace('/[[:^print:]]/', '', $info['tnc']);
            if ($info['particular_column'] != '') {
                $total_cols = count(json_decode($info['particular_column'], true));
                //calculate width of particular table columns
                $width = 95 / ($total_cols - 1);
                $info['particular_tbl_width'] = $width;
            }

            $plugin = json_decode($info['plugin_value'], 1);
            $merchant_id = $this->session->get('merchant_id');

            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $this->smarty->assign('info', $info);
            $this->smarty->assign('is_merchant', 1);
            $smarty = $invoice->asignSmarty($info, array(), $payment_request_id);
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }
            $template_type = 'detail';
            $templates = array('isp', 'franchise', 'travel');

            if (in_array($info['template_type'], $templates)) {
                $template_type = $info['template_type'];
            }

            $html = $this->smarty->fetch(VIEW . 'pdf/invoice_isp_tcpdf.tpl');

            if ($savepdf == 2) {
                echo $html . '<script type="text/javascript"> window.print(); </script>';
                die();
            }

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            //$pdf->SetCreator(PDF_CREATOR);
            // $pdf->SetAuthor('Nicola Asuni');
            // $pdf->SetTitle('TCPDF Example 006');
            // $pdf->SetSubject('TCPDF Tutorial');
            // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // // set default header data
            // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

            // set header and footer fonts
            //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            //$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetMargins(0, 5, 3, true);

            $pdf->SetFooterMargin(5);  //PDF_MARGIN_FOOTER

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); //PDF_MARGIN_BOTTOM

            // set image scale factor
            $pdf->setImageScale(1.1); //PDF_IMAGE_SCALE_RATIO

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
            // set font
            $pdf->SetFont('verdana', '', 13);
            $pdf->SetPrintHeader(false);
            // add a page
            $pdf->AddPage();

            $certificate = 'file://' . base_path() . '/storage/app/tcpdf/' . $plugin['cert_file'];

            // set additional information in the signature
            $tcpdf_info = array(
                'Name' => '',
                'Location' => '',
                'Reason' => '',
                'ContactInfo' => $info['business_email'],
            );

            // set document signature
            $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $tcpdf_info);

            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');

            $y = $pdf->getY();
            //echo $y;die();
            $tbl = $this->smarty->fetch(VIEW . 'pdf/invoice_isp_tcpdf_footer.tpl');;

            //$pdf->writeHTMLCell($tbl, true, false, false, false, '');
            $pdf->setY(-100);
            $pdf->writeHTMLCell('', '', 4, '', $tbl, 0, 0, 0, true, 'middle', true);

            //$this->createDigitalSignatureImage($merchant_id);
            $pdf->Image($this->createDigitalSignatureImage($merchant_id), 82, 250, 120, 22, 'PNG');

            //define active area for signature appearance
            $pdf->setSignatureAppearance(82, 250, 120, 22);

            //set page border 
            $pdf->SetLineStyle(array('width' => 15, 'color' => array(127, 127, 127)));
            $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
            $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
            $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
            $pdf->Line(0, 0, 0, $pdf->getPageHeight());
            $pdf->SetLineStyle(array('width' => 14, 'color' => array(255, 255, 255)));
            $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
            $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
            $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
            $pdf->Line(0, 0, 0, $pdf->getPageHeight());

            // ---------------------------------------------------------
            //ob_end_clean();
            //Close and output PDF document

            if ($savepdf == 1) {
                $name = 'invoice_' . time();
                $file_name = TMP_FOLDER . $name . '.pdf';
                $pdf->Output($file_name, 'F');
                return $file_name;
            } else {
                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');
                $pdf->Output($name . '.pdf', 'I'); // D
                exit();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01pdf3]Error while download tcpdf  Error: for request id [' . $payment_request_id . '] ' . $e->getMessage());
        }
    }

    public function createDigitalSignatureImage($merchant_id = null)
    {
        header('Content-Type: image/png');
        $img = imagecreatefrompng(base_path() . '/storage/app/tcpdf/check.png');

        $orig_width = imagesx($img);
        $orig_height = imagesy($img);
        $width = 620;

        $font_path = realpath(base_path() . '/storage/app/tcpdf/arial.ttf');

        //find merchant name and location from merchant_id
        $merchant_name = $this->session->get('full_name');
        $company_name = $this->session->get('company_name');

        $find_location = $this->common->getSingleValue('merchant_billing_profile', 'merchant_id', $merchant_id);
        //echo $merchant_name;
        $text_line_1 = 'for ' . strtoupper($company_name) . ',';
        $text_line_2 = "Digitally signed by " . strtoupper($merchant_name);
        $text_line_3 = "Date: " . date('Y.m.d h.i.s'); //2021.09.15 11:19:08 +05:30
        $text_line_4 =  "Authorised Signatory";

        if ($find_location != false && $find_location['city'] != null) {
            $location = $find_location['city'];
            $text_line_5 = "Location: " . $location;
        }
        // Calc the new height
        $height = (($orig_height * $width) / $orig_width);
        // Create new image to display
        $new_image = imagecreatetruecolor($width, $height);
        // Create some colors
        $white = imagecolorallocate($new_image, 0, 0, 0);

        // Create new blank image with changed dimensions
        imagecopyresized($new_image, $img, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

        // Add text to image
        imagettftext($new_image, 14, 0, 180, 20, $white, $font_path, $text_line_1);
        imagettftext($new_image, 10, 0, 180, 50, $white, $font_path, $text_line_2);
        imagettftext($new_image, 10, 0, 180, 70, $white, $font_path, $text_line_3);
        imagettftext($new_image, 12, 0, 460, 100, $white, $font_path, $text_line_4);

        if ($find_location != false) {
            imagettftext($new_image, 10, 0, 180, 90, $white, $font_path, $text_line_5);
        }

        //Print image
        $path = base_path() . '/storage/app/tcpdf/file.png';

        imagepng($new_image, $path);
        return $path;
        //imagepng($img);
        //imagedestroy($img);  
    }
}
