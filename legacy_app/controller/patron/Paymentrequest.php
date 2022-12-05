<?php

use App\Http\Controllers\PaymentWrapperController;

/**
 * Payment request functionality for viewing payment requests, confirming and invoking payment gateway
 * 
 */
class Paymentrequest extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->patronCookie();
        $this->view->js = array('invoice');
        $this->view->selectedMenu = array(115);
        $this->view->disable_talk = 1;
    }

    /**
     * View listing of payment requests assigned to the patron
     * 
     */
    public function viewlist()
    {
        try {
            $this->validateSession('patron');
            $dates = $this->generic->setDates();
            $from_date = $dates['from_date'];
            $to_date = $dates['to_date'];
            $cycle_selected = isset($_POST['cycle_name']) ? $_POST['cycle_name'] : '';
            $cycle_list = $this->model->getMerchantList($this->session->get('email_id'));
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date) );
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date) );
            $this->smarty->assign("cycle_selected", $cycle_selected);
            $this->smarty->assign("cycle_list", $cycle_list);
            $this->smarty->assign("title", "Payment requests");
            $this->smarty->assign("is_filter", "True");
            $fromdate = $this->generic->sqlDate($from_date);
            $todate = $this->generic->sqlDate($to_date);
            $requestlist = $this->model->getPaymentRequestList($fromdate, $todate, $cycle_selected, $this->session->get('email_id'));
            $this->smarty->assign("requestlist", $requestlist);
            $this->view->title = 'Payment Requests';

            $this->smarty->assign('title', 'Payment requests');

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Payment requests', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'patron/paymentrequest/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E039]Error while listing patron payment request Error: for user id [' . $this->user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * View payment request received by patron
     * 
     * @param type $link
     */
    public function view($link = null)
    {
        try {

            if ($link == 'F5uXl-dV0_9BuzBn4yHE_BzIhGr--9Awnl8') {
                $template_type = 'franchise_non_brand';
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_' . $template_type . '.tpl');
                $this->smarty->display(VIEW . 'patron/paymentrequest/invoice_footer.tpl');
                $this->view->render('footer/patron_invoice');
                die();
            }
            if ($link == null) {
                $this->setInvalidLinkError();
            }
            $payment_request_id = $this->encrypt->decode($link);
            if (strlen($payment_request_id) != 10) {
                $payment_request_id = $this->encryptBackup($link);
                $link = $this->encrypt->encode($payment_request_id);
            }

            if (strlen($payment_request_id) != 10) {
                $this->setInvalidLinkError();
            }

            if ($this->validateLogin('merchant') == FALSE) {
                $this->session->destroyuser();
                $this->session->set('isGuest', TRUE);
                $this->smarty->assign("isGuest", '1');
                $this->view->usertype = '';
            }

            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');
            if (empty($info)) {
                SwipezLogger::warn(__CLASS__, '[E1003]Error while geting invoice details. payment request id [' . $payment_request_id . ']');
                $this->setInvalidLinkError();
            }
            if ($info['payment_request_type'] == 4) {
                $payment_request_id = $this->common->getRowValue('payment_request_id', 'payment_request', 'parent_request_id', $info['payment_request_id'], 0, " and merchant_id='" . $info['merchant_id'] . "' order by created_date desc limit 1");
                if ($payment_request_id == false) {
                    $this->setInvalidLinkError();
                } else {
                    $invoice_link = $this->encrypt->encode($payment_request_id);
                    header('Location: /patron/paymentrequest/view/' . $invoice_link);
                    die();
                }
            }
            //remove non-printable characters from tnc
            $info['tnc'] = preg_replace('/[[:^print:]]/', '', $info['tnc']);

            if ($info['message'] != 'success') {
                SwipezLogger::error(__CLASS__, '[E207]Error while patron geting invoice details. payment request id ' . $payment_request_id);
                $this->setInvalidLinkError();
            }
            if ($info['payment_request_status'] == 6) {
                $invoice_link = $this->encrypt->encode($info['converted_request_id']);
                $this->smarty->assign("invoice_link", $invoice_link);
            }

            $plugin = json_decode($info['plugin_value'], 1);

            $this->setLogo($info);
            if ($plugin['has_coupon'] == 1) {
                $is_coupon = $this->common->isMerchantCoupon($info['merchant_user_id']);
                $this->smarty->assign("is_coupon", $is_coupon);
            }
            if ($info['template_type'] != 'construction') {
                header('Location: /patron/invoice/view/'. $link.'/703');
                die();
            }
            //if new design then added patron link here
            if (!empty($info['design_name'])) {
                header('Location: /patron/invoice/view/' . $link);
                die();
            }

            //end code for new design

            $this->smarty->assign("merchant_id", $info['merchant_id']);

            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $smarty = $invoice->asignSmarty($info, array(), $payment_request_id, 'Invoice', 'patron');
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }
            if ($plugin['has_autocollect'] == 1) {
                $parent_request_id = $this->common->getRowValue('parent_request_id', 'payment_request', 'payment_request_id', $info['payment_request_id']);
                $subscription_id = $this->common->getRowValue('subscription_id', 'subscription', 'payment_request_id', $parent_request_id);
                $subscription_id = $this->common->getRowValue('subscription_id', 'autocollect_subscriptions', 'invoice_subscription_id', $subscription_id, 1, ' and status=1');
                if ($subscription_id != false) {
                    $plugin['has_autocollect'] = 0;
                    $plugin['autocollect_plan_id'] = 0;
                }
            }
            $fee_id = $this->model->getmaxfeeID($info['merchant_id']);
            $this->smarty->assign("fee_id", $fee_id);
            $this->smarty->assign("Url", $link);
            $this->smarty->assign("info", $info);
            $this->smarty->assign("plugin", $plugin);
            $this->smarty->assign("merchant_type", $info['merchant_type']);
            $is_online_payment = ($info['merchant_type'] == 2 && $info['legal_complete'] == 1 && $smarty['error'] == '') ? 1 : 0;

            if ($info['customer_user_id'] != '') {
                $this->session->set('patron_type', 1);
                $this->smarty->assign('patron_type', 1);
            } else {
                $this->session->set('patron_type', 2);
                $this->smarty->assign('patron_type', 2);
            }

            if ($info['customer_user_id'] != $this->user_id) {
                $this->smarty->assign('diff_login', '1');
            }

            $this->smarty->assign("is_online_payment", $is_online_payment);
            $paidMerchant_request = ($is_online_payment == 1) ? TRUE : FALSE;
            $this->session->set('paidMerchant_request', $paidMerchant_request);
            $this->view->title = $info['company_name'] . ' Invoice';
            $this->view->canonical = 'patron/paymentrequest/view';

            $this->view->minwidth = '';
            if ($plugin['has_partial'] == 1) {
                $partial_payments = $this->common->querylist("call get_partial_payments('" . $payment_request_id . "')");
                $this->smarty->assign("partial_payments", $partial_payments);
            }

            $template_types = array('isp', 'franchise', 'nonbrandfranchise', 'travel');
            if (in_array($info['template_type'], $template_types)) {
                $template_type = $info['template_type'];
            } else {
                $template_type = 'detail';
            }

            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_' . $template_type . '.tpl');
            $this->smarty->display(VIEW . 'patron/paymentrequest/invoice_footer.tpl');
            $this->view->render('footer/patron_invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042-1]Error while payment request view initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Confirm payment before placing the order
     * 
     * @param type $link
     */
    public function pay($link, $coupon = NULL)
    {
        try {
            $payment_request_id = $this->encrypt->decode($link);
            if (strlen($payment_request_id) != 10) {
                $this->setGenericError();
            }
            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');
            $plugin = json_decode($info['plugin_value'], 1);
            $this->setLogo($info);
            if ($info['message'] != 'success') {
                SwipezLogger::error(__CLASS__, '[E207]Error while patron geting invoice details. payment request id ' . $payment_request_id);
                $this->setGenericError();
            }
            if ($info['payment_request_status'] == 1 || $info['payment_request_status'] == 2) {
                header('Location: /patron/paymentrequest/view/' . $link);
            }

            $post_link = $this->view->server_name . '/patron/paymentrequest/payment/' . $link;
            if (!empty($_SESSION['billingerrors'])) {
                $has_errors = $_SESSION['billingerrors'];
                if (!empty($has_errors)) {
                    $this->smarty->assign("errors", $has_errors);
                    $_SESSION['billingerrors'] = array();
                    $this->session->remove('billingerrors');
                }
            }
            $this->session->remove('paidMerchant_request');
            $this->view->payment_request_id = $link;

            if ($this->validateLogin('merchant') == FALSE) {
                $this->smarty->assign("isGuest", '1');
                $this->smarty->assign('diff_login', '1');
            } else {
                if ($info['customer_user_id'] != $this->user_id) {
                    $this->smarty->assign('diff_login', '1');
                    // $user_info = $this->model->getPatronDetails($this->user_id);
                }
            }

            $req_post_info = $this->session->get('req_confirm_post');
            if (isset($req_post_info)) {
                $this->session->remove('req_confirm_post');
                $user_info = $req_post_info;
            }

            if (isset($user_info)) {
                $info['customer_name'] = $user_info['name'];
                $info['customer_address'] = $user_info['address'];
                $info['customer_city'] = $user_info['city'];
                $info['customer_zip'] = $user_info['zipcode'];
                $info['customer_state'] = $user_info['state'];
                $info['customer_email'] = $user_info['email_id'];
                $info['customer_mobile'] = $user_info['mobile_no'];
                $info['customer_country'] = $user_info['country'];
            }

            $this->session->set('confirm_payment', TRUE);
            $title = 'Confirm your payment';
            $this->smarty->assign("post_link", $post_link);
            $this->smarty->assign("url", $info["display_url"]);
            $this->smarty->assign("amount", $info["absolute_cost"]);

            $this->smarty->assign("discount", 0);
            $session_coupon = $this->session->get('approve_coupon_id');
            if ($session_coupon > 0) {
                $coupon_id = $session_coupon;
            }
            if ($_POST['coupon_id'] > 0) {
                $coupon_id = $_POST['coupon_id'];
            }
            if ($coupon_id > 0) {
                $info['coupon_id'] = $coupon_id;
                $this->session->set('approve_coupon_id', $coupon_id);
            }
            if ($coupon_id > 0 && $plugin['has_coupon'] == 1) {
                $coupon_details = $this->common->getCouponDetails($coupon_id);
                if ($coupon_details['type'] == 1) {
                    $discount = $coupon_details['fixed_amount'];
                } else {
                    $discount = $coupon_details['percent'] * $info['absolute_cost'] / 100;
                }
                $this->smarty->assign("is_coupon", 1);
                $this->smarty->assign("coupon_id", $this->encrypt->encode($coupon_id));
                $this->smarty->assign("discount", $discount);
            }
            if (isset($_POST['partial']) && $plugin['has_partial'] == 1) {
                $this->smarty->assign("is_partial", true);
            }
            if (isset($_POST['autopay']) && $plugin['has_autocollect'] == 1) {
                $this->smarty->assign("autocollect_plan_id", $plugin['autocollect_plan_id']);
            }
            $franchise_id = 0;
            if ($info['pg_to_franchise'] == 1 && $info['franchise_id'] > 0) {
                $franchise_id = $info['franchise_id'];
            }
            $pg_details = $this->common->getMerchantPG($info['merchant_id'], $franchise_id, $info['currency']);
            $date = date("m/d/Y");
            $refDate = date("m/d/Y", strtotime($info['due_date']));
            $grand_total = $info['absolute_cost'] - $info['paid_amount'];
            if ($date > $refDate) {
                $grand_total = $grand_total;
                $this->smarty->assign("invoice_total", $info['invoice_total']);
            }
            $grand_total = $grand_total - $discount;
            $this->smarty->assign("encrypt_grandtotal", $this->encrypt->encode($grand_total));
            $surcharge_amount = $this->common->getSurcharge($info['merchant_id'], $grand_total);

            if (count($pg_details) > 1) {
                $absolute_cost = $grand_total;
                $invoice = new PaymentWrapperController();
                $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
                $this->smarty->assign("paypal_id", $radio['paypal_id']);
                $this->smarty->assign("radio", $radio['radio']);
                $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                $this->smarty->assign("post_url", '/payment-gateway');
                $this->smarty->assign("request_post_url", $post_link);
                $this->smarty->assign("is_new_pg", true);
                $this->smarty->assign("post_link", '/payment-gateway');
            } else {
                $this->smarty->assign("post_url", $post_link);
                $this->smarty->assign("request_post_url", $post_link);
                $this->smarty->assign("is_new_pg", false);
                $this->smarty->assign("post_link", $post_link);
                if ($pg_details[0]['pg_surcharge_enabled'] == 1) {
                    $this->smarty->assign("pg_surcharge_enabled", 1);
                    $absolute_cost = $grand_total;
                } else {
                    $absolute_cost = $grand_total + $surcharge_amount;
                }
                $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
            }
            if ($plugin['has_partial_payment'] == 1) {
                if ($plugin['partial_min_amount'] > $grand_total) {
                    $plugin['partial_min_amount'] = $grand_total;
                }
            }
            if ($info['customer_default_column'] != null) {
                $this->smarty->assign("customer_default_column", json_decode($info['customer_default_column'], 1));
            }
            $currency_icon = $this->common->getRowValue('icon', 'currency', 'code', $info['currency']);
            $this->smarty->assign("currency_icon", $currency_icon);

            //find country code from config table
            if (isset($info['customer_country']) && $info['customer_country'] != 'India') {
                $get_country_code = $this->common->getRowValue('description', 'config', 'config_type', 'country_name', 0, " and config_value='" . $info['customer_country'] . "'");
                $info['country_mobile_code'] = '+' . $get_country_code;
            } else {
                $info['country_mobile_code'] = '+91';
            }

            $this->smarty->assign("info", $info);
            $this->smarty->assign("plugin", $plugin);
            $this->smarty->assign("surcharge_amount", $surcharge_amount);
            if ($plugin['roundoff'] == 1) {
                $absolute_cost = round($absolute_cost, 0);
            }

            $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            $country_code = $this->common->getListValue('config', 'config_type', 'country_name');
            $this->smarty->assign("country_code", $country_code);
            $this->smarty->assign("state_code", $state_code);
            $this->smarty->assign("absolute_cost", $absolute_cost);
            $this->smarty->assign("payment_request_id", $link);
            $this->smarty->assign("title", $title);
            $this->view->title = "Confirm your payment";
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'patron/paymentrequest/confirm.tpl');
            $this->view->render('footer/invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->user_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Invoke payment gateway
     * 
     */
    public function payment($link = null, $fee_id = null)
    {
        try {
            if ($link == 'paypal') {
                $data = file_get_contents('php://input');
                $dataaarray = json_decode($data, 1);
                foreach ($dataaarray as $row) {
                    $_POST[$row['name']] = $row['value'];
                }
                $_POST['payment_mode'] = $fee_id;
            }


            if (empty($_POST['payment_req'])) {
                $this->setInvalidLinkError();
            }

            $repath = '/patron/paymentrequest/pay/' . $_POST['payment_req'];
            if ($_POST['is_partial'] == 1) {
                $repath = $repath . '/partial';
            }



            $payment_request_id = $this->encrypt->decode($_POST['payment_req']);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');

            if (!empty($info)) {

                if ($info['payment_request_status'] == 1 || $info['payment_request_status'] == 2) {
                    header('Location: ' . $repath);
                }
                $plugin = json_decode($info['plugin_value'], 1);
                $this->setLogo($info);
                $user_id = $this->session->get('userid');
                $user_id = (isset($user_id)) ? $user_id : $info['customer_id'];
                $this->session->set('return_payment_url', $repath);

                if (isset($_POST['name'])) {

                    $space_position = strpos($_POST['name'], ' ');
                    $first_name = substr($_POST['name'], 0, $space_position);
                    $last_name = substr($_POST['name'], $space_position);
                    require_once CONTROLLER . 'Paymentvalidator.php';
                    $validator = new Paymentvalidator($this->model);
                    $validator->validateBillingDetails();
                    $hasErrors = $validator->fetchErrors();

                    if ($hasErrors == false) {
                        $name = $_POST['name'];
                        $email = $_POST['email'];
                        $mobile = $_POST['mobile'];
                        $city = $_POST['city'];
                        $address = $_POST['address'];
                        $state = (isset($_POST['country']) && $_POST['country'] != 'India') ? $_POST['state1'] : $_POST['state'];
                        $zipcode = $_POST['zipcode'];
                        $this->session->set('req_confirm_post', $_POST);
                    } else {
                        $this->session->set('billingerrors', $hasErrors);
                        $_SESSION['billingerrors'] = $hasErrors;
                        $this->session->set('paidMerchant_request', TRUE);
                        header('Location: ' . $repath);
                        exit;
                    }
                }
                $user_detail = $this->model->getPaymentDetails($payment_request_id, $info['customer_id']);

                $fee_id = 0;
                if ($info['payment_request_status'] != 7) {
                    $this->model->updatePaymentRequestStatus($payment_request_id, $info['customer_id'], $user_id, 5);
                }
                if (isset($_POST['payment_mode'])) {
                    $fee_id = $this->encrypt->decode($_POST['payment_mode']);
                    $pg_details = $this->common->getPaymentGatewayDetails($info['merchant_id'], $fee_id);
                } else {
                    $franchise_id = 0;
                    if ($info['pg_to_franchise'] == 1 && $info['franchise_id'] > 0) {
                        $franchise_id = $info['franchise_id'];
                    }
                    $pg_details = $this->common->getPaymentGatewayDetails($info['merchant_id'], null, $franchise_id, $user_detail['currency']);
                }

                if (empty($pg_details)) {
                    SwipezLogger::error(__CLASS__, '[E1008]Error while getting merchant pg details Payment_request_id: ' . $payment_request_id . ' Merchant_id: ' . $info['merchant_user_id']);
                    $this->setGenericError();
                }
                $pg_id = $pg_details['pg_id'];
                // $pg_id = (isset($_POST['pg_id'])) ? $_POST['pg_id'] : $pg_details['pg_id'];
                // $pg_details = $this->common->getPgDetailsbyID($pg_id, $info['merchant_id'],  $user_detail['currency']);
                $coupon_id = 0;
                if ($user_detail['@message'] == 'success' && $pg_id > 0) {
                    $discount = 0;

                    if ($_POST['deduct_amount'] > 0) {
                        $previous_due = ($info['Previous_dues'] > 0) ? $info['Previous_dues'] : 0;
                        $total_percent = ($info['tax_amount'] * 100) / $info['basic_amount'];
                        $total_deduct = $info['basic_amount'] - $_POST['deduct_amount'];
                        $total_tax = ($total_percent * $info['basic_amount']) / 100;
                        $user_detail['@absolute_cost'] = $total_deduct + $total_tax + $previous_due;
                    }

                    if ($_POST['coupon_id'] != "" && $plugin['has_coupon'] == 1) {
                        $coupon_id = $this->encrypt->decode($_POST['coupon_id']);
                        $coupon_details = $this->common->getCouponDetails($coupon_id);
                        if (!empty($coupon_details)) {
                            if ($coupon_details['type'] == 1) {
                                $discount = $coupon_details['fixed_amount'];
                            } else {
                                $discount = $coupon_details['percent'] * $user_detail['@absolute_cost'] / 100;
                            }
                            $user_detail['@absolute_cost'] = $user_detail['@absolute_cost'] - $discount;
                        }
                    }
                    $is_partial = 0;
                    if ($_POST['is_partial'] == 1) {
                        $grand_total = $info['grand_total'] - $info['paid_amount'];
                        if ($info['partial_min_amount'] > $grand_total) {
                            $info['partial_min_amount'] = $grand_total;
                        }
                        if ($_POST['partial_amount'] >= $plugin['partial_min_amount']) {
                            if ($user_detail['@absolute_cost'] > $_POST['partial_amount']) {
                                $is_partial = 1;
                            }
                            $user_detail['@absolute_cost'] = $_POST['partial_amount'];
                        } else {
                            $this->setGenericError();
                        }
                    }
                    if ($_POST['autocollect_plan_id'] > 0) {
                        $this->saveAutocollect($info['merchant_id'], $payment_request_id, $_POST['autocollect_plan_id'], $user_detail['@absolute_cost'], $info['customer_id'], $name, $email, $mobile);
                    }
                    $surcharge_amount = $this->common->getSurcharge($info['merchant_id'], $user_detail['@absolute_cost'], $fee_id);
                    $user_detail['@absolute_cost'] = $user_detail['@absolute_cost'] + $surcharge_amount;
                    if ($plugin['roundoff'] == 1) {
                        $user_detail['@absolute_cost'] = round($user_detail['@absolute_cost'], 0);
                    }
                    $this->model->updatePaymentStatus($info['customer_id'], 1);
                    $transaction_id = $this->model->intiatePaymentTransaction($payment_request_id, 1, $info['customer_id'], $user_id, $info['merchant_user_id'], $info['merchant_id'], $user_detail['@absolute_cost'], $surcharge_amount, $discount, $_POST['deduct_amount'], $_POST['deduct_text'], $pg_id, $pg_details['fee_detail_id'], $info['franchise_id'], $info['vendor_id'], '', 1, $user_detail['currency']);
                    if ($coupon_id > 0) {
                        $this->common->genericupdate('payment_transaction', 'coupon_id', $coupon_id, 'pay_transaction_id', $transaction_id);
                    }
                    if ($is_partial == 1) {
                        $this->common->genericupdate('payment_transaction', 'is_partial_payment', 1, 'pay_transaction_id', $transaction_id);
                    }
                    if (isset($_POST['name'])) {
                        if (isset($this->user_id)) {
                            $id = $this->user_id;
                        } else {
                            $id = $info['customer_id'];
                        }
                        $this->common->SaveDatachange($id, 2, $transaction_id, -1, $info['customer_id'], $first_name, $last_name, $email, $mobile, $address, $city, $state, $zipcode);
                    }
                } else {
                    SwipezLogger::error(__CLASS__, '[E1009]Error while invoking payment gateway Error: Invalid link or link has already used for user id[' . $user_id . ']');
                    $this->setGenericError();
                }

                $user_detail['customer_id'] = $info['customer_id'];
                $user_detail['vendor_id'] = $info['vendor_id'];
                $user_detail['franchise_id'] = $info['franchise_id'];
                SwipezLogger::info(__CLASS__, 'Payment transaction initiated. Transaction id: ' . $transaction_id . ', Request id: ' . $payment_request_id . ', Merchant name: ' . $user_detail['@company_name'] . ', Patron email: ' . $email . ', Mobile: ' . $mobile . ' Address: ' . $address . ' City: ' . $city . ' State: ' . $state . ' Zipcode: ' . $zipcode . ' , Patron id: ' . $user_id . ', Amount: ' . $user_detail['@absolute_cost']);

                $paymentWrapper = new PaymentWrapperController();
                $this->session->set('transaction_type', 'payment');
                $this->session->set('transaction_id', $transaction_id);
                $user_detail["xtransaction_id"] = $transaction_id;
                $pg_details['repath'] = $repath;
                $pg_details['fee_id'] = $pg_details['fee_detail_id'];
                $paymentWrapper->paymentProcced($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address, $user_id, $this->common, $this->view, $this->encrypt);
            } else {
                SwipezLogger::info(__CLASS__, '[E1010]Error while payment initiate - getting payemnt request details Payment request id : ' . $payment_request_id);
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveAutocollect($merchant_id, $payment_request_id, $plan_id, $amount, $customer_id, $name, $email, $mobile)
    {
        try {
            $plan_details = $this->common->getSingleValue('autocollect_plans', 'plan_id', $plan_id);

            $parent_request_id = $this->common->getRowValue('parent_request_id', 'payment_request', 'payment_request_id', $payment_request_id);
            $subscription_id = $this->common->getRowValue('subscription_id', 'subscription', 'payment_request_id', $parent_request_id);
            $data['plan_id'] = $plan_id;
            $data['customer_id'] = $customer_id;
            $data['customer_name'] = $name;
            $data['email_id'] = $email;
            $data['mobile'] = $mobile;
            $data['description'] = 'Subscription created';
            $data['auth_amount'] = $amount;
            $data['amount'] = $amount;
            $data['payment_request_id'] = $payment_request_id;
            $data['invoice_subscription_id'] = $subscription_id;
            $data['return_url'] = config('app.url') . '/secure/cashfreeautocollect';
            $data['expiry_date'] = date("Y-m-d H:i:s", strtotime(" +" . $plan_details['occurrence'] . " " . $plan_details['interval_type'] . "s"));
            $post_string = json_encode($data);
            $this->createApiToken($merchant_id);

            $result = $this->generic->APIrequest('v1/autocollect/subscription/save', $post_string, 'POST', $this->session->get('api_token'));
            $array = json_decode($result, 1);
            if ($array['status'] == 0) {
                if (is_array($array['error'])) {
                    $errors = $array['error'];
                } else {
                    $errors[] = $array['error'];
                }
                SwipezLogger::error(__CLASS__, 'Error while create auto collect subscription Merchant id: ' . $this->merchant_id . ' Errors: ' . json_encode($errors));
            } else {
                header('Location: ' . $array['auth_link']);
                die();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while create auto collect subscription Merchant id: ' . $this->merchant_id . ' Errors: ' . $e->getMessage());
        }
    }

    /**
     * Respond as an offline request
     */
    public function offlinepayment($link)
    {
        try {
            $payment_request_id = $this->encrypt->decode($link);
            if (strlen($payment_request_id) != 10) {
                $this->setGenericError();
            }
            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E207]Error while patron geting invoice details. payment request id ' . $payment_request_id);
                $this->setGenericError();
            } else {
                if ($info['merchant_id'] != 'M000000200') {
                    $this->setGenericError();
                    die();
                }
                $row = $this->common->getSingleValue('offline_response', 'payment_request_id', $payment_request_id);
                if (!empty($row)) {
                    $this->smarty->assign("ref_no", $row['bank_transaction_no']);
                }
                $this->smarty->assign("payment_request_id", $payment_request_id);
                $this->smarty->assign("info", $info);
                $this->view->title = 'Offline payment';
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'patron/paymentrequest/global-confirm.tpl');
                $this->view->render('footer/profile');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E045]Error while respond payment Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function respond()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location: /merchant/paymentrequest/viewlist');
            }
            require CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            $validator->validateRespond();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $coupon_id = 0;
                $discount = 0;
                $row = $this->common->getSingleValue('offline_response', 'payment_request_id', $_POST['payment_req_id']);
                if (empty($row)) {
                    $date = new DateTime($_POST['date']);
                    $result = $this->model->respond($_POST['amount'], $_POST['bank_name'], $_POST['payment_req_id'], $date->format('Y-m-d'), $_POST['response_type'], $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $coupon_id, $discount, $_POST['customer_id'], $_POST['deduct_amount'], $_POST['deduct_text']);
                    if ($result['message'] != 'success') {
                        SwipezLogger::error(__CLASS__, '[E012]Error while merchant respond payment request Error: ' . $result);
                        $this->setGenericError();
                    } else {
                        $this->smarty->assign("success", 'Offline transaction save successfully.');
                        $this->view->title = "Offline Settlement";
                        $this->view->render('header/guest');
                        $this->smarty->display(VIEW . 'merchant/transaction/global-thankyou.tpl');
                        $this->view->render('footer/profile');
                    }
                } else {
                    $this->updaterespond($row['offline_response_id']);
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
     * Respond as an offline request
     */
    public function updaterespond($offline_id)
    {
        try {
            if (empty($_POST)) {
                $this->setGenericError();
            }
            $date = new DateTime($_POST['date']);
            $this->model->respondUpdate($_POST['amount'], $_POST['bank_name'], $offline_id, $date->format('Y-m-d'), $_POST['response_type'], $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $_POST['customer_id']);
            $this->smarty->assign("success", 'Offline transaction save successfully.');
            $this->view->title = "Offline Settlement";
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/transaction/global-thankyou.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E036]Error while updating respond Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
        }
    }

    /**
     * Display payment success page 
     */
    public function success()
    {
        try {
            $user_id = $this->session->get('userid');
            $response = $this->session->get('response');
            if (empty($response)) {
                $this->setError('Invalid request', 'Please do not refresh the browser window or hit enter in the browser address bar click <a href="' . $this->view->server_name . '" >here</a> go back to home page.');
                header('location:/error');
            }
            if ($this->session->get('logged_in') != TRUE) {
                $this->view->isGuest = TRUE;
                $this->smarty->assign("isGuest", '1');
                $this->session->destroy();
            }

            $coupon_enabled = $this->common->getRowValue('coupon_enabled', 'merchant_setting', 'merchant_id', $response['merchant_id']);
            if ($coupon_enabled == 1) {
                $display_url = $this->common->getRowValue('display_url', 'merchant', 'merchant_id', $response['merchant_id']);
                if (!isset($display_url)) {
                    $display_url = 'swipez';
                }
                $coupon_link = '/m/' . $display_url . '/showcoupon/' . $this->encrypt->encode($response['merchant_id']) . '/' . $this->encrypt->encode($response['MerchantRefNo']);
                header('Location: ' . $coupon_link);
                die();
            }
            $this->smarty->assign("invoice_link", $this->encrypt->encode($response['payment_request_id']));
            $this->smarty->assign("response", $response);
            $this->session->remove('response');
            $this->view->title = 'Payment success';
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'patron/paymentrequest/success.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E046]Error while payment success initiate Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
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
            //remove non-printable characters from tnc
            $info['tnc'] = preg_replace('/[[:^print:]]/', '', $info['tnc']);
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $this->smarty->assign('info', $info);
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
            //echo $html;die();
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
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $this->smarty->assign('info', $info);
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
            //$pdf->SetAuthor('Nicola Asuni');
            //$pdf->SetTitle('TCPDF Example 006');
            //$pdf->SetSubject('TCPDF Tutorial');
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            //set default header data
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

            $pdf->SetFooterMargin(5);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(1.1);

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
            $pdf->Image($this->createDigitalSignatureImage($info['merchant_id'], $info['merchant_user_id']), 82, 250, 120, 22, 'PNG');
            // define active area for signature appearance
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
                $pdf->Output($name . '.pdf', 'D');  // I
                exit();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01pdf3]Error while download tcpdf  Error: for request id [' . $payment_request_id . '] ' . $e->getMessage());
        }
    }

    public function createDigitalSignatureImage($merchant_id = null, $merchant_user_id = null)
    {
        header('Content-Type: image/png');
        $img = imagecreatefrompng(base_path() . '/storage/app/tcpdf/check.png');

        $orig_width = imagesx($img);
        $orig_height = imagesy($img);
        $width = 620;

        $font_path = realpath(base_path() . '/storage/app/tcpdf/arial.ttf');

        //find merchant name and location from merchant_id
        $find_company_name =  $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
        $find_merchant_name = $this->common->getSingleValue('user', 'user_id', $merchant_user_id);
        $find_location = $this->common->getSingleValue('merchant_billing_profile', 'merchant_id', $merchant_id);
        //echo $merchant_name;

        $text_line_1 = 'for ' . strtoupper($find_company_name['company_name']) . ',';
        $text_line_2 = "Digitally signed by " . strtoupper($find_merchant_name['first_name']) . ' ' . strtoupper($find_merchant_name['last_name']);
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

        // Print image
        $path = base_path() . '/storage/app/tcpdf/file.png';

        imagepng($new_image, $path);
        return $path;
        //imagepng($img);
        //imagedestroy($img);  
    }

    function getgrandtotal($grand_total, $fee_id = '')
    {
        try {
            if (is_numeric($grand_total)) {
                $total = $grand_total;
            } else {
                $total = $this->encrypt->decode($grand_total);
            }
            if ($fee_id != '') {
                $fee_id = $this->encrypt->decode($fee_id);
                $pg_surcharge = $this->common->getRowValue('pg_surcharge_enabled', 'merchant_fee_detail', 'fee_detail_id', $fee_id);
                if ($pg_surcharge == 1) {
                    echo 'pgsurcharge';
                    die();
                }
                $surcharge_amount = $this->common->getSurcharge('M000000000', $total, $fee_id);
            } else {
                $surcharge_amount = 0;
            }
            echo number_format($total + $surcharge_amount, 2, '.', '');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01568]Error while getgrandtotal Error: for fee_id [' . $fee_id . '] ' . $e->getMessage());
        }
    }

    function getcoupongrandtotal($grand_total, $fee_id = '')
    {
        try {
            $surcharge_amount = 0;
            if ($fee_id != '') {
                $surcharge_amount = $this->common->getSurcharge('M000000000', $grand_total, $fee_id);
            }
            echo number_format($grand_total + $surcharge_amount, 2, '.', '');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01568]Error while getgrandtotal Error: for fee_id [' . $fee_id . '] ' . $e->getMessage());
        }
    }

    function getxwaygrandtotal($grand_total, $fee_id = '')
    {
        try {
            $total = $this->encrypt->decode($grand_total);
            $surcharge_amount = 0;
            if ($fee_id != '') {
                $fee_id = $this->encrypt->decode($fee_id);
                require_once MODEL . 'XwayModel.php';
                $xway = new XwayModel();
                $surcharge_amount = $xway->getXwaySurcharge($total, $fee_id);
            }
            echo number_format($total + $surcharge_amount, 2, '.', '');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01568]Error while getgrandtotal Error: for fee_id [' . $fee_id . '] ' . $e->getMessage());
        }
    }

    public function couponvalidate($merchant_id, $coupon_code = '')
    {
        try {
            $coupon_code = str_replace('SPACE', ' ', $coupon_code);
            if (substr($merchant_id, 0, 1) == 'U') {
                $merchant_id = $this->common->getRowValue('merchant_id', 'merchant', 'user_id', $merchant_id);
            }
            $info = $this->common->getMerchantCouponDetails($merchant_id, $coupon_code);
            if (empty($info)) {
                echo 'false';
            } else {
                $this->session->set('coupon_id', $info['coupon_id']);
                echo json_encode($info);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01pdf3]Error while download pdf  Error: for request id [' . $payment_request_id . '] ' . $e->getMessage());
        }
    }

    function setLogo($info)
    {
        if ($info['image_path'] != '') {
            $this->view->merchant_logo = '/uploads/images/logos/' . $info['image_path'];
        } else {
            $logo = $this->common->getRowValue('logo', 'merchant_landing', 'merchant_id', $info['merchant_id']);
            if ($logo != '') {
                $this->view->merchant_logo = '/uploads/images/landing/' . $logo;
            }
        }
    }


    public function testdownload()
    {
        try {

            $html = $this->smarty->fetch(VIEW . 'pdf/invoice_test.tpl');
            $mpdf = new \Mpdf\Mpdf([
                'mode' => '',
                'format' => 'A4',
                'default_font_size' => 0,
                'default_font' => '',
                'margin_left' => 4,
                'margin_right' => 4,
                'margin_top' => 4,
                'margin_bottom' => 4,
                'margin_header' => 9,
                'margin_footer' => 9,
                'orientation' => 'P',
            ]);
            $mpdf->shrink_tables_to_fit = 1;
            $mpdf->WriteHTML($html);
            $mpdf->SetDisplayMode('fullpage');

            //$mpdf->Output();

            $name = date('Y-M-d H:m:s');
            $mpdf->Output($name . '.pdf', 'D');
            exit();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01pdf36]Error while download pdf  Error: for request id [' . $payment_request_id . '] ' . $e->getMessage());
        }
    }
}
