<?php

use App\Http\Controllers\PaymentWrapperController;

/**
 * Landing controller class to handle merchant landing pages
 */
class Landing extends Controller
{

    public $merchant_details = array();
    public $merchant_id = '';

    function __construct()
    {
        parent::__construct();
        $this->view->selectedMenu = 'companyprofileview';
        $this->view->disable_talk = 1;
    }

    /**
     * Display home
     */
    function setLandingDetails()
    {
        $this->merchant_id = $this->session->get('landing_merchant_id');
        $this->merchant_details = $this->model->getMerchantLandingDetails($this->merchant_id);
        if ($this->merchant_details['display_name'] != '') {
            $this->merchant_details['company_name'] = $this->merchant_details['display_name'];
        }
        $this->smarty->assign("url", $this->merchant_details['display_url']);
        $this->smarty->assign("details", $this->merchant_details);
        $this->view->url = $this->merchant_details['display_url'];
        $this->view->details = $this->merchant_details;
        $this->view->member_login = $this->session->getCookie('member_login');
        $setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);

        $merchant_form = $this->common->getListValue('form_builder_request', 'merchant_id', $this->merchant_id, 1, ' and display_menu=1');
        $form_builder = array();
        foreach ($merchant_form as $frm) {
            $form_builder[] = array('name' => $frm['name'], 'key' => $this->encrypt->encode($frm['id']), 'link' => '/patron/form/submit/' . $this->encrypt->encode($frm['id']));
        }
        $this->view->form_builder = $form_builder;
        $this->view->is_package = $this->model->isMerchantPlan($this->merchant_id, 1);
        $this->view->is_booking = $setting['booking_calendar'];
        if ($this->merchant_details['disable_online_payment'] == 1 || $setting['directpay_enable'] == 0 || $this->merchant_details['is_legal_complete'] == 0) {
            $direct_pay = 0;
        } else {
            $direct_pay = 1;
        }
        $this->smarty->assign("merchant_setting", $setting);
        $this->view->is_directpay = $direct_pay;
        $website = $this->model->getMerchantWebsite($this->merchant_id);
        $this->view->domain = '';
        if (!empty($website)) {
            $this->view->domain = 'http://' . $website['merchant_domain'];
        }
        if ($_SESSION['iframe'] == true) {
            $this->smarty->assign("hide_footer", true);
            $this->view->hide_header_menu = 1;
            $this->view->hide_footer = 1;
        }
    }

    function index()
    {
        try {
            $this->setLandingDetails();
            $this->smarty->assign("title", 'Home');
            $this->smarty->assign("direct_pay", $this->view->is_directpay);
            $this->smarty->assign("direct_pay", $this->view->is_directpay);
            $this->view->title = "Company - Home";
            $this->view->hideplugin = true;
            $this->view->render('header/merchantlanding');
            $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/landing/create.tpl');
            $this->view->render('footer/merchantlanding');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while merchant landing page for merchant id [' . $this->merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Display merchant policies
     */
    function policies()
    {
        try {
            $this->setLandingDetails();
            $this->view->selected = 'policies';
            $this->smarty->assign("title", 'Policies');
            $this->view->title = "Company - Policies";
            $this->view->render('header/merchantlanding');
            $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/landing/policies.tpl');
            $this->view->render('footer/merchantlanding');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Display merchant about
     */
    function aboutus()
    {
        try {
            $this->setLandingDetails();
            $this->view->selected = 'aboutus';
            $this->smarty->assign("title", 'About us');
            $this->view->title = "Company - About us";
            $this->view->render('header/merchantlanding');
            $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/landing/aboutus.tpl');
            $this->view->render('footer/merchantlanding');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Display merchant invoices
     */
    function paymybills()
    {
        try {

            $this->setLandingDetails();
            if (isset($_POST['user_id']) && $_POST['user_id'] != '') {
                require_once CONTROLLER . 'Mybills.php';
                $mybill_controller = new Mybills();
                require_once MODEL . 'MybillsModel.php';
                $mybill_model = new MybillsModel();
                $user_input = $_POST['user_id'];

                if (isset($_POST['g-recaptcha-response'])) {
                    $res = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if ($res) {
                    } else {
                        $hasErrors = "Invalid captcha please click on captcha box";
                        $this->smarty->assign("haserrors", $hasErrors);
                    }
                }
                if ($hasErrors == FALSE) {
                    $type = $mybill_controller->isEmail($user_input);
                    if ($type == 0) {
                        $type = $mybill_controller->isMobile($user_input);
                        if ($type == 0) {
                            $type = 3;
                        }
                    }
                    $rows = $mybill_model->getMybills($user_input, $type, $this->merchant_id);
                    if ($rows[0]['message'] == 'empty') {
                        SwipezLogger::info(__CLASS__, 'Record empty for Input :' . $user_input . ' Type :' . $type);
                        $this->smarty->assign("empty_message", 'No records found.');
                    } else {
                        if (isset($rows[0]['payment_request_id'])) {
                            $int = 0;
                            $base_url = getenv('BASE_URL');
                            foreach ($rows as $item) {
                                $rows[$int]['paylink'] = '/patron/paymentrequest/view/' . $this->encrypt->encode($item['payment_request_id']);
                                $int++;
                            }
                            $this->smarty->assign("requestlist", $rows);
                        } else {
                            SwipezLogger::error(__CLASS__, 'My bills input: Text- ' . $user_input . ' Type- ' . $type . ' Merchant_id- ' . $this->merchant_details['user_id'] . ' error: ' . json_encode($rows));
                        }
                    }
                }

                $this->smarty->assign("selected", $user_input);
            }

            $this->smarty->assign("title", 'Pay my bills');
            $this->view->title = "Company - Pay my bills";
            $this->view->selected = 'paymybills';
            $this->view->render('header/merchantlanding');
            $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/landing/mybills.tpl');
            $this->view->render('footer/merchantlanding');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while pay my bills Error:  for merchant id [' . $this->merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function showerror($array)
    {
        $this->smarty->assign("title", $array['title']);
        $this->smarty->assign("message", $array['message']);
        $this->view->render('header/merchantlanding');
        $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
        $this->smarty->display(VIEW . 'merchant/landing/error.tpl');
        $this->view->render('footer/merchantlanding');
    }

    /**
     * Display merchant invoices
     */
    function directpay($id = null)
    {
        try {
            $this->setLandingDetails();
            $this->smarty->assign("title", 'Direct Pay');
            $this->smarty->assign("merchant_id", $this->merchant_id);
            $this->view->title = $this->merchant_details['company_name'] . " - Direct Pay";
            $this->view->selected = 'directpay';
            $this->view->js = array('invoice');
            if ($this->view->is_directpay == 0) {
                $merchant_det = $this->common->getMerchantProfile($this->merchant_id);
                $array = array('title' => 'Payment link disabled!', 'message' => 'This payment link is currently disabled. Please contact your merchant on ' . $merchant_det['business_email'] . ' or ' . $merchant_det['business_contact'] . ' to get this activated.');
                $this->showerror($array);
                exit;
            }
            if (isset($_POST['name'])) {
                $row['direct_pay_name'] = $_POST['name'];
                $row['direct_pay_email'] = $_POST['email'];
                $row['direct_pay_mobile'] = $_POST['mobile'];
                $row['direct_pay_customer_code'] = $_POST['customer_code'];
                $row['direct_pay_purpose'] = $_POST['purpose'];
                $this->smarty->assign("cookie", $row);
            } else {
                $this->smarty->assign("cookie", $_COOKIE);
            }

            if ($id != null) {
                $id = $this->encrypt->decode($id);
                $row = $this->common->getSingleValue('direct_pay_request', 'id', $id, 1, " and merchant_id='" . $this->merchant_id . "'");
                if (empty($row)) {
                    $merchant_det = $this->common->getMerchantProfile($this->merchant_id);
                    $array = array('title' => 'Invalid payment link', 'message' => 'The link you are using is not valid anymore. Please contact your merchant on ' . $merchant_det['business_email'] . ' or ' . $merchant_det['business_contact'] . ' to get a valid link.');
                    $this->showerror($array);
                    exit;
                }
                $row['direct_pay_name'] = $row['name'];
                $row['direct_pay_email'] = $row['email'];
                $row['direct_pay_mobile'] = $row['mobile'];
                $row['direct_pay_customer_code'] = $row['customer_code'];
                $row['direct_pay_purpose'] = $row['narrative'];
                $row['direct_pay_amount'] = $row['amount'];
                $this->smarty->assign("cookie", $row);
            }

            $pg_details = $this->common->getMerchantPG($this->merchant_id);
            if (empty($pg_details)) {
                $array = array('title' => 'Payment link disabled!', 'message' => 'This payment link is currently disabled. Please contact your merchant on ' . $merchant_det['business_email'] . ' or ' . $merchant_det['business_contact'] . ' to get this activated.');
                $this->showerror($array);
                exit;
            }
            $this->view->v3captcha = true;
            if (count($pg_details) > 1) {
                $invoice = new PaymentWrapperController();
                $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
                $this->smarty->assign("paypal_id", $radio['paypal_id']);
                $this->smarty->assign("radio", $radio['radio']);
            }
            $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);

            $this->view->render('header/merchantlanding');
            $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/landing/directpay.tpl');
            $this->view->render('footer/merchantlanding');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while directpay initiate Error:  for merchant id [' . $this->merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function confirmpayment()
    {
        try {
            $this->setLandingDetails();
            $this->view->js = array('invoice');
            $this->session->remove('paidMerchant_request');
            if (isset($_POST['g-recaptcha-response'])) {
                $res = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if ($res) {
                } else {
                    $hasErrors = "Invalid captcha please click on captcha box";
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                }
            }
            $this->session->setCookie('direct_pay_name', $_POST['name']);
            $this->session->setCookie('direct_pay_email', $_POST['email']);
            $this->session->setCookie('direct_pay_mobile', $_POST['mobile']);
            $this->session->setCookie('direct_pay_customer_code', $_POST['customer_code']);
            $this->session->setCookie('direct_pay_purpose', $_POST['purpose']);
            if ($hasErrors == FALSE) {
                $member_customer_id = $this->session->get('member_customer_id');
                if (isset($member_customer_id)) {
                    $customerdetails = $this->common->getCustomerDetails($member_customer_id, $this->merchant_id);
                } else {
                    $customerdetails['address'] = $this->session->getCookie('direct_pay_address');
                    $customerdetails['city'] = $this->session->getCookie('direct_pay_city');
                    $customerdetails['state'] = $this->session->getCookie('direct_pay_state');
                    $customerdetails['zipcode'] = $this->session->getCookie('direct_pay_zipcode');
                }
                $this->smarty->assign("customerdetails", $customerdetails);
                $this->smarty->assign("post", $_POST);
                $amount = $_POST['amount'];
                $surcharge_amount = $this->common->getSurcharge($this->merchant_id, $amount);
                $pg_details = $this->common->getMerchantPG($this->merchant_id);
                if (count($pg_details) > 1) {
                    $invoice = new PaymentWrapperController();
                    $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
                    $this->smarty->assign("paypal_id", $radio['paypal_id']);
                    $this->smarty->assign("radio", $radio['radio']);
                    $this->smarty->assign("encrypt_grandtotal", $this->encrypt->encode($amount));
                    $this->view->js = array('invoice');
                    $this->smarty->assign("grand_total", $amount);
                    $this->smarty->assign("surcharge_amount", $surcharge_amount);
                    $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                } else {
                    $this->smarty->assign("grand_total", $amount + $surcharge_amount);
                    $this->smarty->assign("surcharge_amount", $surcharge_amount);
                    $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                }
                $this->smarty->assign("amount", $amount);
                $this->smarty->assign("package_type", $this->session->get('package_type'));
                $this->session->set('confirm_payment', TRUE);
                $this->smarty->assign("merchant_id", $this->merchant_details['user_id']);
                $this->view->selected = 'directpay';
                $this->view->v3captcha = true;
                $this->smarty->assign("title", 'Confirm your payment');
                $this->view->title = 'Confirm your payment';
                $this->view->render('header/merchantwebsite');
                $this->smarty->display(VIEW . 'merchant/landing/directconfirm.tpl');
                $this->view->render('footer/merchantwebsite');
            } else {
                $this->directpay();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment direct pay initiate Error:for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function directpayment($formbuilder_id = null, $fee_id = null)
    {
        try {
            if ($formbuilder_id == 'paypal') {
                $this->setLandingDetails();
                $data = file_get_contents('php://input');
                $dataaarray = json_decode($data, 1);
                foreach ($dataaarray as $row) {
                    $_POST[$row['name']] = $row['value'];
                }
                $_POST['payment_mode'] = $fee_id;
                $type = 1;
            } else {
                if ($formbuilder_id == null) {
                    $this->setLandingDetails();
                    if (isset($_POST['plan_id'])) {
                        $plan_id = $this->encrypt->decode($_POST['plan_id']);
                        $type = 3;
                    } else {
                        $type = 4;
                    }
                } else {
                    $type = 2;
                }
            }
            if (!isset($_POST['amount'])) {
                $direct_pay_link = '';
                if ($this->session->get('direct_pay_link')) {
                    $direct_pay_link = '/' . $this->session->get('direct_pay_link');
                    $this->session->remove('direct_pay_link');
                }
                $link = str_replace('directpayment', 'payment-link', $_SERVER['REQUEST_URI'] . $direct_pay_link);
                header('Location: ' . $link);
                die();
            }
            // $this->validatev3Captcha();
            $_POST['customer_name'] = $_POST['name'];
            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            $validator->validatePayment();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {

                $this->session->setCookie('direct_pay_name', $_POST['name']);
                $this->session->setCookie('direct_pay_email', $_POST['email']);
                $this->session->setCookie('direct_pay_mobile', $_POST['mobile']);
                $this->session->setCookie('direct_pay_customer_code', $_POST['customer_code']);
                $this->session->setCookie('direct_pay_purpose', $_POST['purpose']);
                $fee_id = 0;
                if (isset($_POST['payment_mode'])) {
                    $fee_id = $this->encrypt->decode($_POST['payment_mode']);
                    $pg_details = $this->common->getPaymentGatewayDetails($this->merchant_id, $fee_id);
                } else {
                    $pg_details = $this->common->getPaymentGatewayDetails($this->merchant_id, null, 0, $_POST['currency']);
                }

                if (empty($pg_details)) {
                    SwipezLogger::error(__CLASS__, '[E1008]Error while getting merchant pg details Merchant_id: ' . $this->merchant_id);
                    $this->setGenericError();
                }

                $pg_id = $pg_details['pg_id'];
                // $pg_id = (isset($_POST['pg_id'])) ? $_POST['pg_id'] : $pg_details['pg_id'];
                // $pg_details = $this->common->getPgDetailsbyID($pg_id, $this->merchant_id,  $_POST['currency']);
                $amount = $_POST['amount'];
                if ($pg_id > 0) {
                    if (isset($_POST['hash'])) {
                        $hash = $this->encrypt->decode($_POST['hash']);
                        $hash = json_decode($hash, 1);

                        if ($hash["amount"] == $_POST['amount'] && $hash["email"] == $_POST['email']) {
                        } else {
                            $this->setError('auth failed', [], true);
                        }
                    }

                    $discount = 0;
                    $coupon_id = 0;
                    if ($_POST['coupon_id'] > 0) {
                        $coupon_details = $this->common->getCouponDetails($_POST['coupon_id']);
                        $coupon_id = $_POST['coupon_id'];
                        if ($coupon_details['type'] == 1) {
                            $discount = $coupon_details['fixed_amount'];
                        } else {
                            $discount = $coupon_details['percent'] * $amount / 100;
                        }
                        $amount = $amount - $discount;
                    }
                    $surcharge_amount = $this->common->getSurcharge($this->merchant_id, $amount, $fee_id);
                    $user_detail['@absolute_cost'] = $amount + $surcharge_amount;
                    require_once MODEL . 'XwayModel.php';
                    $xwaymodel = new XwayModel();
                    $is_random_id = ($_POST['is_random_id'] == 1) ? 1 : 0;
                    $webhook_id = ($_POST['webhook_id'] > 0) ? $_POST['webhook_id'] : 0;
                    $discount_amount = ($_POST['discount_amount'] > 0) ? $_POST['discount_amount'] : 0;
                    if (isset($_POST['return_url'])) {
                        $returnurl = $_POST['return_url'];
                    } else {
                        $returnurl = env('SWIPEZ_BASE_URL') . 'm/' . $this->merchant_details['display_url'] . '/paymentsuccess';
                    }

                    $udf1 = (isset($_POST['udf1'])) ? $_POST['udf1'] : $_POST['customer_code'];
                    $udf2 = (isset($_POST['udf2'])) ? $_POST['udf2'] : '';
                    $udf3 = (isset($_POST['udf3'])) ? $_POST['udf3'] : '';
                    $udf4 = (isset($_POST['udf4'])) ? $_POST['udf4'] : '';
                    $udf5 = (isset($_POST['udf5'])) ? $_POST['udf5'] : '';

                    $details = $xwaymodel->savexwaytransaction($pg_id, $user_detail['@absolute_cost'], $this->merchant_id, $_SERVER['HTTP_REFERER'], $returnurl, '', $_POST['amount'], $surcharge_amount, $_POST['purpose'], $_POST['name'], $_POST['address'], $_POST['city'], $_POST['state'], $this->merchant_id, $_POST['zipcode'], $_POST['mobile'], $_POST['email'], $udf1, $udf2, $udf3, $udf4, $udf5, '', '', $_POST['customer_code'], 0, 0, $_POST['currency'], $is_random_id, $type, $webhook_id, $discount_amount);
                    $transaction_id = $details['xtransaction_id'];
                    if ($formbuilder_id != null) {
                        $this->common->genericupdate('form_builder_transaction', 'transaction_id', $transaction_id, 'id', $formbuilder_id, $this->merchant_id);
                    }
                    if (isset($plan_id)) {
                        $xwaymodel->updateXwayTable($transaction_id, $plan_id, $coupon_id);
                    }
                    if ($user_detail['@absolute_cost'] == 0) {
                        return $transaction_id;
                        die();
                    }
                    $details['@patron_mobile_no'] = $_POST['mobile'];
                    $details['@patron_email'] = $_POST['email'];
                    $details['@absolute_cost'] = $amount + $surcharge_amount;
                    $this->session->set('transaction_type', 'xway');
                    $this->session->set('transaction_id', $transaction_id);
                    $this->view->merchant_company_name = $this->merchant_details['company_name'];
                    $paymentWrapper = new PaymentWrapperController();
                    $pg_details['repath'] = '';
                    $pg_details['fee_id'] = $pg_details['fee_detail_id'];
                    $details['currency'] = $_POST['currency'];

                    $response = $paymentWrapper->paymentProcced($transaction_id, $details, $pg_details, $_POST['name'], '', $_POST['email'], $_POST['mobile'], NULL, NULL, NULL, NULL, 'guest', $this->common, $this->view, $this->encrypt);
                    $this->setError('Payment failed', $response, true);
                } else {
                    SwipezLogger::error(__CLASS__, '[E1009]Error while invoking payment gateway Error: Invalid link or link has already used for user id[' . $patron_id . ']');
                    $this->setGenericError();
                }
            } else {

                $this->session->set('validerrors', $hasErrors);
                $direct_pay_link = '';
                if ($this->session->get('direct_pay_link')) {
                    $direct_pay_link = '/' . $this->session->get('direct_pay_link');
                    $this->session->remove('direct_pay_link');
                }
                $link = str_replace('directpayment', 'payment-link', $_SERVER['REQUEST_URI'] . $direct_pay_link);
                header('Location: ' . $link);
                die();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function paymentsuccess()
    {
        try {
            $this->setLandingDetails();
            if ($_POST['status'] == 'success') {
                $business_email = $this->common->getMerchantProfile($this->merchant_id, 0, 'business_email');
                $mer_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
                $user = $this->common->getSingleValue('user', 'user_id', $this->merchant_details['user_id']);
                $logo = $this->common->getRowValue('logo', 'merchant_landing', 'merchant_id', $this->merchant_id);

                $result = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $_POST['transaction_id']);
                $description = $result['description'];
                $response['customer_code'] = $_POST['udf1'];
                $response['BillingName'] = $_POST['billing_name'];
                $response['BillingEmail'] = $_POST['billing_email'];
                $response['merchant_name'] = $_POST['company_name'];
                $response['TransactionID'] = $_POST['transaction_id'];
                $response['merchant_email'] = $business_email;
                $response['MerchantRefNo'] = $_POST['bank_ref_no'];
                $response['DateCreated'] = $_POST['date'];
                $response['narrative'] = $description;
                $response['Amount'] = $_POST['amount'];
                $response['payment_mode'] = $_POST['mode'];
                $response['image'] = '';
                $response['merchant_logo'] = $logo;
                $response['currency_icon'] = $this->common->getRowValue('icon', 'currency', 'code', $result['currency']);
                if ($logo != '') {
                    $response['logo'] = $this->view->server_name . "/uploads/images/landing/" . $logo;
                }
                $file_name = null;
                $invoice_link = null;


                if ($result['type'] == 3 && $result['plan_id'] > 0 && $mer_setting['plan_invoice_create'] == 1) {
                    require_once CONTROLLER . 'InvoiceWrapper.php';
                    $wrapper = new InvoiceWrapper($this->common);
                    $plandetails = $wrapper->getPlanInvoiceDetails($result);
                    $inv_details = $wrapper->xwayInvoice($plandetails);
                    if ($mer_setting['plan_invoice_send'] == 1 && !empty($inv_details)) {
                        $file_name = $inv_details['file_name'];
                        $invoice_link = $inv_details['invoice_link'];
                    }
                }

                require_once CONTROLLER . '/Secure.php';
                $secure = new Secure();
                $secure->sendMailReceipt($response, 'directpay', $file_name);
                if (isset($file_name)) {
                    unlink($file_name);
                }

                require_once CONTROLLER . 'Notification.php';
                $notify = new Notification();
                $transaction_link = $this->encrypt->encode($_POST['transaction_id']);

                $long_url = $this->view->server_name . '/patron/transaction/receipt/' . $transaction_link;
                $shortUrl = $notify->saveShortUrl($long_url);
                $result['transaction_short_url'] = $shortUrl;
                $result['sms_name'] = $_POST['company_name'];
                $result['transaction_id'] = $_POST['transaction_id'];
                $result['Amount'] = $_POST['amount'];
                $result['sms_gateway_type'] = $mer_setting['sms_gateway_type'];
                $result['merchant_id'] = $this->merchant_id;
                $result['customer_code'] = $response['customer_code'];
                $result['BillingName'] = $response['BillingName'];
                $result['merchant_user_id'] = $this->merchant_details['user_id'];

                $notify->sendSMSReceiptMerchant($result, $user['mobile_no'], $mer_setting['sms_gateway']);
                $notify->sendSMSReceiptCustomer($result, $_POST['billing_mobile'], $mer_setting['sms_gateway']);
                $coupon_enabled = $this->common->getRowValue('coupon_enabled', 'merchant_setting', 'merchant_id', $this->merchant_id);
                if ($coupon_enabled == 1) {
                    $display_url = $this->common->getRowValue('display_url', 'merchant', 'merchant_id', $this->merchant_id);
                    if (!isset($display_url)) {
                        $display_url = 'swipez';
                    }
                    $coupon_link = '/m/' . $display_url . '/showcoupon/' . $this->encrypt->encode($this->merchant_id) . '/' . $transaction_link;
                    header('Location: ' . $coupon_link);
                    die();
                }

                if ($this->session->get('logged_in') != TRUE) {
                    $this->smarty->assign("isGuest", 1);
                }
                $this->smarty->assign("invoice_link", $invoice_link);
                $this->smarty->assign("response", $response);
                $this->smarty->assign("merchant_id", $this->merchant_details['user_id']);
                $this->view->selected = 'directpay';
                $this->view->title = 'Payment success';
                $this->view->render('header/merchantwebsite');
                $this->smarty->display(VIEW . 'merchant/landing/success.tpl');
                $this->view->render('footer/merchantwebsite');
            } else {
                $repath = $this->view->server_name . "/m/" . $this->merchant_details['display_url'] . '/directpay';
                $message = $this->model->fetchMessage('p4');
                $message = str_replace('<Merchant Name>', $_POST['company_name'], $message);
                $message = str_replace('<URL>', $repath, $message);
                $message = str_replace('xxxx', $_POST['amount'] . '/-', $message);
                $response = $this->model->sendSMS($this->session->get('userid'), $message, $_POST['billing_mobile'], $this->merchant_id, 1, array());
                SwipezLogger::info(__CLASS__, 'SMS Sending to Patron Mobile: ' . $_POST['billing_mobile'] . ' Response:' . $response);

                $this->session->set('return_payment_url', $repath);
                $this->setPaymentFailedError($_POST['transaction_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E1011+989]Error while direct pay response : ' . json_encode($_POST) . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Display merchant plan
     */
    function packages($set_source = null)
    {
        try {
            $this->setLandingDetails();
            $this->view->js = array('plan');
            $plandetails = $this->model->getMerchantPlan($this->merchant_id);
            if ($this->validateLogin('patron') == FALSE) {
                $this->session->destroyuser();
                $this->smarty->assign("isGuest", '1');
            }
            if ($set_source == 'iframe') {
                $_SESSION['iframe'] = true;
                $this->smarty->assign("hide_footer", true);
                $this->view->hide_header_menu = 1;
                $this->view->hide_footer = 1;
                $set_source = null;
            }
            $set_source = strtolower($set_source);
            $set_source = trim($set_source);
            $set_source = str_replace(' ', '-', $set_source);

            $source = 0;
            $has_tax = 0;
            foreach ($plandetails as $plan) {
                $duration = $this->model->getdurationPlan($this->merchant_id, $plan['source'], $plan['category'], $plan['speed'], $plan['data']);
                $base_amount = $plan['price'];
                $tax_amount = 0;
                if ($plan['tax1_percent'] > 0) {
                    $has_tax = 1;
                    $tax = $plan['tax1_percent'] + $plan['tax2_percent'];
                    $base_amount = round(($plan['price'] * 100) / (100 + $tax), 2);
                    $tax_amount = round($plan['price'] - $base_amount, 2);
                }
                if ($set_source != null) {
                    $get_source = strtolower($plan['source']);
                    $get_source = trim($get_source);
                    $get_source = str_replace(' ', '-', $get_source);
                    if ($set_source == $get_source) {
                        $plans[$plan['category']][] = array('plan_id' => $plan['plan_id'], 'plan_link' => $this->encrypt->encode($plan['plan_id']), 'speed' => $plan['speed'], 'data' => $plan['data'], 'duration' => $duration, 'price' => $plan['price'], 'base_amount' => $base_amount, 'tax_amount' => $tax_amount);
                    }
                } else {
                    if ($plan['source'] != '') {
                        $plans[$plan['source']][$plan['category']][] = array('plan_id' => $plan['plan_id'], 'plan_link' => $this->encrypt->encode($plan['plan_id']), 'speed' => $plan['speed'], 'data' => $plan['data'], 'duration' => $duration, 'price' => $plan['price'], 'base_amount' => $base_amount, 'tax_amount' => $tax_amount);
                        $source = 1;
                    } else {
                        $plans[$plan['category']][] = array('plan_id' => $plan['plan_id'], 'plan_link' => $this->encrypt->encode($plan['plan_id']), 'speed' => $plan['speed'], 'data' => $plan['data'], 'duration' => $duration, 'price' => $plan['price'], 'base_amount' => $base_amount, 'tax_amount' => $tax_amount);
                    }
                }
            }
            $this->session->set('paidMerchant_request', TRUE);
            $this->session->set('package_type', 'packages');
            $this->smarty->assign("requestlist", $plans);
            $this->smarty->assign("is_source", $source);
            $this->smarty->assign("has_tax", $has_tax);
            $this->smarty->assign("merchant_id", $this->merchant_id);
            $this->smarty->assign("title", 'Packages');
            $this->view->title = "Packages";
            $this->view->selected = "packages";
            $this->view->render('header/merchantwebsite');
            $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
            if ($source == 1) {
                $this->smarty->display(VIEW . 'merchant/landing/myplan.tpl');
            } else {
                $this->smarty->display(VIEW . 'merchant/landing/myplan_1.tpl');
            }
            $this->view->render('footer/merchantwebsite');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function logoutcustomer($backpath)
    {
        $this->setLandingDetails();
        $this->session->remove('member_customer_id');
        foreach ($_COOKIE as $k => $v) {
            $this->session->removeCookie($k);
        }
        $this->session->remove('member_login');
        $this->session->remove('userid');
        $this->session->remove('member_customer_id');
        header('Location: /m/' . $this->merchant_details['display_url'] . '/' . $backpath);
        die();
    }

    function validateCustomerLogin()
    {
        $_POST['customer_code'] = trim($_POST['customer_code']);
        $extra = '';
        if ($_POST['password_validation'] == 1) {
            if ($_POST['password'] == '') {
                echo 'false';
                die();
            }
            $_POST['password'] = trim($_POST['password']);
            $extra = " and password='" . $_POST['password'] . "'";
        }


        $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if ($result) {
        } else {
            $hasErrors[0][0] = "Captcha";
            $hasErrors[0][1] = "Invalid captcha please click on captcha box";
        }

        if ($hasErrors == false) {
            $customer_id = $this->common->getRowValue('customer_id', 'customer', 'customer_code', $_POST['customer_code'], 1, " and merchant_id='" . $_POST['merchant_id'] . "'" . $extra);
            if (isset($customer_id) && $customer_id > 0) {
                $this->session->set('member_customer_id', $customer_id);
                return $customer_id;
            } else {
                $hasErrors[0][0] = "";
                $hasErrors[0][1] = "Invalid login credentials. In case of assistance with login please reach out to " . $this->merchant_details['email_id'] . ' & ' . $this->merchant_details['contact_no'];
            }
        }
        $this->smarty->assign("errors", $hasErrors);
    }

    public function confirmpackage($link)
    {
        try {
            $this->setLandingDetails();
            if (!empty($_POST)) {
                $member_customer_id = $this->validateCustomerLogin();
            }
            $this->view->js = array('invoice');
            $is_coupon = $this->common->isMerchantCoupon($this->merchant_details['user_id']);
            $plan_id = $this->encrypt->decode($link);
            $info = $this->common->getSingleValue('prepaid_plan', 'plan_id', $plan_id);
            if (empty($info)) {
                SwipezLogger::info(__CLASS__, '[E207]Error while geting plan details. plan id ' . $plan_id . ' Link: ' . $link);
                $this->setGenericError();
            } else {
                $this->session->remove('paidMerchant_request');
                if (!isset($member_customer_id)) {
                    $member_customer_id = $this->session->get('member_customer_id');
                }
                if (isset($member_customer_id)) {
                    $this->view->session_customer_id = $member_customer_id;
                    $this->view->backpath = 'packages';
                    $customerdetails = $this->common->getCustomerDetails($member_customer_id, $this->merchant_id);
                    $this->smarty->assign("customerdetails", $customerdetails);
                }

                if ($this->validateLogin('patron') == FALSE) {
                    $this->smarty->assign("isGuest", '1');
                }
                $surcharge_amount = $this->common->getSurcharge($this->merchant_id, $info['price']);
                $pg_details = $this->common->getMerchantPG($this->merchant_id);
                if (count($pg_details) > 1) {
                    $invoice = new PaymentWrapperController();
                    $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
                    $this->smarty->assign("paypal_id", $radio['paypal_id']);
                    $this->smarty->assign("radio", $radio['radio']);
                    $this->smarty->assign("encrypt_grandtotal", $this->encrypt->encode($info['price']));
                    $this->view->js = array('invoice');
                    $this->smarty->assign("grand_total", $info['price']);
                    $this->smarty->assign("surcharge_amount", $surcharge_amount);
                    $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                    $this->smarty->assign("post_url", '/payment-gateway');
                    $this->smarty->assign("request_post_url", '/m/' . $this->merchant_details['display_url'] . '/directpayment');
                    $this->smarty->assign("is_new_pg", true);
                } else {
                    $this->smarty->assign("grand_total", $info['price']);
                    $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                    $this->smarty->assign("pg_surcharge_enabled", $pg_details[0]['pg_surcharge_enabled']);
                    $this->smarty->assign("surcharge_amount", $surcharge_amount);
                    $this->smarty->assign("post_url", '/m/' . $this->merchant_details['display_url'] . '/directpayment');
                    $this->smarty->assign("request_post_url", '/m/' . $this->merchant_details['display_url'] . '/directpayment');
                    $this->smarty->assign("is_new_pg", true);
                }
                $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
                $country_code = $this->common->getListValue('config', 'config_type', 'country_name');
                $this->smarty->assign("country_code", $country_code);
                $this->smarty->assign("state_code", $state_code);
                $this->session->set('valid_ajax', 'package_payment');
                $this->smarty->assign("package_type", $this->session->get('package_type'));
                $this->session->set('confirm_payment', TRUE);
                $this->smarty->assign("is_coupon", $is_coupon);
                $this->smarty->assign("merchant_id", $this->merchant_details['user_id']);
                $this->smarty->assign("info", $info);
                $this->smarty->assign("plan_id", $link);
                $this->view->v3captcha = true;
                $this->view->selected = $this->session->get('package_type');
                $this->smarty->assign("title", 'Confirm your payment');
                $this->view->title = 'Confirm your payment';
                $this->view->render('header/merchantwebsite');
                $this->smarty->display(VIEW . 'merchant/landing/confirm.tpl');
                $this->view->render('footer/merchantwebsite');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function confirmmembership($link)
    {
        try {
            $this->setLandingDetails();
            $this->validatememberlogin($this->merchant_details['display_url'], 0);
            $this->view->js = array('invoice');
            $is_coupon = $this->common->isMerchantCoupon($this->merchant_details['user_id']);
            $membership_id = $this->encrypt->decode($link);

            $info = $this->common->getSingleValue('booking_membership', 'membership_id', $membership_id);
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E207]Error while geting membership details. membership id ' . $membership_id . ' Link: ' . $link);
                $this->setGenericError();
            } else {
                $this->session->remove('paidMerchant_request');
                $member_customer_id = $this->session->getCookie('member_customer_id');
                if (isset($member_customer_id)) {
                    $this->view->session_customer_id = $member_customer_id;
                    $this->view->backpath = 'booking';
                    $customerdetails = $this->common->getCustomerDetails($member_customer_id, $this->merchant_id);
                    $this->smarty->assign("customerdetails", $customerdetails);
                }

                $surcharge_amount = $this->common->getSurcharge($this->merchant_id, $info['amount']);
                $pg_details = $this->common->getMerchantPG($this->merchant_id);
                if (count($pg_details) > 1) {
                    $invoice = new PaymentWrapperController();
                    $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
                    $this->smarty->assign("paypal_id", $radio['paypal_id']);
                    $this->smarty->assign("radio", $radio['radio']);
                    $this->smarty->assign("encrypt_grandtotal", $this->encrypt->encode($info['amount']));
                    $this->view->js = array('invoice');
                    $this->smarty->assign("grand_total", $info['amount']);
                    $this->smarty->assign("surcharge_amount", $surcharge_amount);
                    $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                } else {
                    $this->smarty->assign("grand_total", $info['amount']);
                    $this->smarty->assign("pg_surcharge_enabled", $pg_details[0]['pg_surcharge_enabled']);
                    $this->smarty->assign("surcharge_amount", $surcharge_amount);
                    $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                }
                $this->view->selected = 'booking';
                $this->session->set('valid_ajax', 'package_payment');
                $this->smarty->assign("package_type", $this->session->get('package_type'));
                $this->session->set('confirm_payment', TRUE);
                $this->smarty->assign("is_coupon", $is_coupon);
                $this->smarty->assign("merchant_id", $this->merchant_details['user_id']);
                $this->smarty->assign("info", $info);
                $this->smarty->assign("membership_id", $link);
                $this->smarty->assign("title", 'Confirm your payment');
                $this->view->title = 'Confirm your payment';
                $this->view->render('header/merchantwebsite');
                $this->smarty->display(VIEW . 'merchant/landing/membership_confirm.tpl');
                $this->view->render('footer/merchantwebsite');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function packagepayment()
    {
        try {
            $this->setLandingDetails();
            require_once MODEL . 'merchant/CustomerModel.php';
            $customer = new CustomerModel($this->model);
            $plan_id = $this->encrypt->decode($_POST['plan_id']);
            $info = $this->common->getSingleValue('prepaid_plan', 'plan_id', $plan_id);
            require_once MODEL . 'patron/PaymentrequestModel.php';
            $reqmodel = new PaymentRequestModel();
            if (empty($info)) {
                SwipezLogger::info(__CLASS__, '[E043-10]Error while geting plan details. plan id ' . $plan_id);
                $this->setGenericError();
            } else {
                if ($this->validateLogin('patron') == FALSE) {
                    $space_position = strpos($_POST['name'], ' ');
                    if ($space_position > 0) {
                        $first_name = substr($_POST['name'], 0, $space_position);
                        $last_name = substr($_POST['name'], $space_position);
                    } else {
                        $first_name = $_POST['name'];
                        $last_name = '';
                    }

                    $customer_code = '';

                    require_once CONTROLLER . 'Paymentvalidator.php';
                    $validator = new Paymentvalidator($this->model);



                    $validator->validateBillingDetails();
                    $hasErrors = $validator->fetchErrors();
                    $customer_id = $this->session->get('member_customer_id');
                    if (isset($customer_id)) {
                        $hasErrors = false;
                    }
                    if ($hasErrors == false) {
                        if (isset($customer_id)) {
                            $patron['customer_id'] = $customer_id;
                        } else {
                            if ($_POST['customer_code'] != '') {
                                $customer_code = $_POST['customer_code'];
                                $customer_id = $customer->isExistCustomerCode($this->merchant_id, $customer_code);
                                if ($customer_id != FALSE) {
                                    $patron['customer_id'] = $customer_id;
                                }
                            }
                        }

                        if (empty($patron)) {
                            $patron = $customer->isExistCustomerEmail($this->merchant_id, $_POST['email'], 1);
                        }

                        if (empty($patron)) {
                            $patron = $customer->isExistCustomerEmail($this->merchant_id, $_POST['mobile'], 2);
                        }
                        if (empty($patron)) {
                            if ($customer_code == '') {
                                $customer_code = $customer->getCustomerCode($this->merchant_id);
                            }
                            $patron = $customer->saveCustomer($this->merchant_id, $this->merchant_id, $customer_code, $first_name, $last_name, $_POST['email'], $_POST['mobile'], $_POST['address'], '', $_POST['city'], $_POST['state'], $_POST['zipcode'], array(), array());
                        }
                    } else {
                        $this->session->set('billingerrors', $hasErrors);
                        header('Location: /m/' . $this->merchant_details['display_url'] . '/packages');
                        exit;
                    }
                    if (empty($patron)) {
                        SwipezLogger::error(__CLASS__, '[E043-9]Error while getting patron id Error: ' . $e->getMessage());
                        $this->setGenericError();
                    } else {
                        $patron_id = $patron['customer_id'];
                        $customer_id = $patron['customer_id'];
                        $this->session->set('userid', $patron_id);
                    }
                } else {
                    $patron_id = $this->session->get('userid');
                    $userdet = $this->model->getUserDetails($patron_id);
                    $patron = $customer->isExistCustomerEmail($this->merchant_id, $userdet['email_id'], 1);
                    if (empty($patron)) {
                        $patron = $customer->isExistCustomerEmail($this->merchant_id, $userdet['mobile_no'], 2);
                    }
                    if (empty($patron)) {
                        $customer_code = $customer->getCustomerCode($this->merchant_id);
                        $patron = $customer->saveCustomer($this->merchant_id, $this->merchant_id, $customer_code, $userdet['first_name'], $userdet['last_name'], $userdet['email_id'], $userdet['mobile_no'], '', '', '', '', '', array(), array());
                    }
                    $patron_id = $patron['customer_id'];
                    $customer_id = $patron['customer_id'];
                }

                $amount = $info['price'];
                $plan_name = $info['plan_name'];

                $values = array($plan_name, $amount, 1, $amount, '');
                $ids = array('P1', 'P2', 'P3', 'P4', 'P5');
                $row = $this->plansaveinvoice($this->merchant_details['user_id'], $customer_id, $info['price'], $info['plan_name'], $values, $ids);
                $payment_request_id = $row['request_id'];

                $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');
                $this->view->merchant_company_name = $info['company_name'];
                if (isset($_POST['name'])) {
                    $space_position = strpos($_POST['name'], ' ');
                    $first_name = substr($_POST['name'], 0, $space_position);
                    $last_name = substr($_POST['name'], $space_position);
                    require_once CONTROLLER . 'Paymentvalidator.php';
                    $validator = new Paymentvalidator($this->model);
                    $validator->validateBillingDetails();
                    $hasErrors = $validator->fetchErrors();

                    if ($hasErrors == false) {
                        $email = $_POST['email'];
                        $mobile = $_POST['mobile'];
                        $city = $_POST['city'];
                        $address = $_POST['address'];
                        $state = $_POST['state'];
                        $zipcode = $_POST['zipcode'];
                    } else {
                        $this->session->set('billingerrors', $hasErrors);
                        $this->session->set('paidMerchant_request', TRUE);
                        header('Location: /patron/paymentrequest/pay/' . $_POST['payment_req']);
                        exit;
                    }
                }

                $reqmodel->updatePaymentRequestStatus($payment_request_id, $customer_id, $patron_id, 5);
                if (isset($_POST['payment_mode'])) {
                    $fee_id = $this->encrypt->decode($_POST['payment_mode']);
                    $pg_details = $this->common->getPaymentGatewayDetails($this->merchant_id, $fee_id);
                } else {
                    $pg_details = $this->common->getPaymentGatewayDetails($this->merchant_id);
                }

                if (empty($pg_details)) {
                    SwipezLogger::error(__CLASS__, '[E1008]Error while getting merchant pg details Payment_request_id: ' . $payment_request_id . ' Merchant_id: ' . $info['merchant_user_id']);
                    $this->setGenericError();
                }
                $user_detail = $reqmodel->getPaymentDetails($payment_request_id, $info['customer_id']);
                $pg_id = $pg_details['pg_id'];
                if ($user_detail['@message'] == 'success' && $pg_id > 0) {
                    $discount = 0;
                    if ($_POST['coupon_id'] > 0) {
                        $coupon_details = $this->common->getCouponDetails($_POST['coupon_id']);
                        if ($coupon_details['type'] == 1) {
                            $discount = $coupon_details['fixed_amount'];
                        } else {
                            $discount = $coupon_details['percent'] * $user_detail['@absolute_cost'] / 100;
                        }
                        $user_detail['@absolute_cost'] = $user_detail['@absolute_cost'] - $discount;
                    }


                    $surcharge_amount = $this->common->getSurcharge($info['merchant_id'], $user_detail['@absolute_cost']);
                    $user_detail['@absolute_cost'] = $user_detail['@absolute_cost'] + $surcharge_amount;
                    $franchise_id = 0;
                    $transaction_id = $reqmodel->intiatePaymentTransaction($payment_request_id, $user_detail['@payment_request_type'], $info['customer_id'], $patron_id, $user_detail['@merchant_id'], $this->merchant_id, $user_detail['@absolute_cost'], $surcharge_amount, $discount, 0, '', $pg_id, $pg_details['fee_detail_id'], $franchise_id, 0, 'Package ' . $plan_name);
                    $this->session->set('transaction_type', 'payment');
                    $this->session->set('transaction_id', $transaction_id);
                } else {
                    SwipezLogger::error(__CLASS__, '[E1009]Error while invoking payment gateway Error: Invalid link or link has already used for user id[' . $patron_id . ']');
                    $this->setGenericError();
                }

                if (isset($_POST['name'])) {
                    if (isset($this->user_id)) {
                        $id = $this->user_id;
                    } else {
                        $id = $info['customer_id'];
                    }
                    $this->common->SaveDatachange($id, 2, $transaction_id, -1, $info['customer_id'], $first_name, $last_name, $email, $mobile, $address, $city, $state, $zipcode);
                }

                SwipezLogger::info(__CLASS__, 'Payment transaction initiated. Transaction id: ' . $transaction_id . ', Request id: ' . $payment_request_id . ', Merchant name: ' . $user_detail['@company_name'] . ', Patron email: ' . $email . ', Patron id: ' . $customer_id . ', Amount: ' . $user_detail['@absolute_cost']);

                $paymentWrapper = new PaymentWrapperController();

                $pg_details['repath'] = '/patron/paymentrequest/pay/' . $_POST['payment_req'];
                $pg_details['fee_id'] = $pg_details['fee_detail_id'];
                $paymentWrapper->paymentProcced($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address, 'guest', $this->common, $this->view);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function slotpayment()
    {
        try {
            $this->setLandingDetails();
            require_once MODEL . 'merchant/CustomerModel.php';
            $customer = new CustomerModel($this->model);

            require_once MODEL . 'merchant/BookingsModel.php';
            $bookingModel = new BookingsModel();
            require_once MODEL . 'patron/PaymentrequestModel.php';
            $reqmodel = new PaymentRequestModel();
            if ($this->validateLogin('patron') == FALSE) {
                $space_position = strpos($_POST['name'], ' ');
                if ($space_position > 0) {
                    $first_name = substr($_POST['name'], 0, $space_position);
                    $last_name = substr($_POST['name'], $space_position);
                } else {
                    $first_name = $_POST['name'];
                    $last_name = '';
                }
                $customer_code = '';
                require_once CONTROLLER . 'Paymentvalidator.php';
                $validator = new Paymentvalidator($this->model);
                $validator->validateBillingDetails();
                $hasErrors = $validator->fetchErrors();
                $customer_id = $this->session->getCookie('member_customer_id');
                if (isset($customer_id)) {
                    $hasErrors = false;
                }
                if ($hasErrors == false) {
                    if (isset($customer_id)) {
                        $patron['customer_id'] = $customer_id;
                    } else {
                        if ($_POST['customer_code'] != '') {
                            $customer_code = $_POST['customer_code'];
                            $customer_id = $customer->isExistCustomerCode($this->merchant_id, $customer_code);
                            if ($customer_id != FALSE) {
                                $patron['customer_id'] = $customer_id;
                            }
                        }
                    }

                    if (empty($patron)) {
                        $patron = $customer->isExistCustomerEmail($this->merchant_id, $_POST['email'], 1);
                    }

                    if (empty($patron)) {
                        $patron = $customer->isExistCustomerEmail($this->merchant_id, $_POST['mobile'], 2);
                    }
                    if (empty($patron)) {
                        if ($customer_code == '') {
                            $customer_code = $customer->getCustomerCode($this->merchant_id);
                        }
                        $patron = $customer->saveCustomer($this->merchant_id, $this->merchant_id, $customer_code, $first_name, $last_name, $_POST['email'], $_POST['mobile'], $_POST['address'], '', $_POST['city'], $_POST['state'], $_POST['zipcode'], array(), array());
                    }
                } else {
                    $this->session->set('billingerrors', $hasErrors);
                    header('Location: /m/' . $this->merchant_details['display_url'] . '/packages');
                    exit;
                }
                if (empty($patron)) {
                    SwipezLogger::error(__CLASS__, '[E043-9]Error while getting patron id Error: ' . $e->getMessage());
                    $this->setGenericError();
                } else {
                    $patron_id = $patron['customer_id'];
                    $customer_id = $patron['customer_id'];
                    $this->session->set('userid', $patron_id);
                }
            } else {
                $patron_id = $this->session->getCookie('userid');
                $userdet = $this->model->getUserDetails($patron_id);
                $patron = $customer->isExistCustomerEmail($this->merchant_id, $userdet['email_id'], 1);
                if (empty($patron)) {
                    $patron = $customer->isExistCustomerEmail($this->merchant_id, $userdet['mobile_no'], 2);
                }
                if (empty($patron)) {
                    $customer_code = $customer->getCustomerCode($this->merchant_id);
                    $patron = $customer->saveCustomer($this->merchant_id, $this->merchant_id, $customer_code, $userdet['first_name'], $userdet['last_name'], $userdet['email_id'], $userdet['mobile_no'], '', '', '', '', '', array(), array());
                }
                $patron_id = $patron['customer_id'];
                $customer_id = $patron['customer_id'];
            }

            $i = 0;
            $booking_details = [];
            foreach ($_POST['booking_slots'] as $slot_) {
                $slots->slot_id = $_POST['booking_slots'][$i];
                $slots->qty = $_POST['booking_qty'][$i];
                array_push($booking_details, $slots);
                $i++;
            }
            $booking_details = json_decode(json_encode($booking_details), TRUE);
            if (!$this->validateConfirmbooking($booking_details)) {
                header('Location: /m/' . $_POST["url"] . '/booking/');
            }

            if (isset($_POST['name'])) {
                $space_position = strpos($_POST['name'], ' ');
                $first_name = substr($_POST['name'], 0, $space_position);
                $last_name = substr($_POST['name'], $space_position);
                require_once CONTROLLER . 'Paymentvalidator.php';
                $validator = new Paymentvalidator($this->model);
                $validator->validateBillingDetails();
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    $email = $_POST['email'];
                    $mobile = $_POST['mobile'];
                    $city = $_POST['city'];
                    $address = $_POST['address'];
                    $state = $_POST['state'];
                    $zipcode = $_POST['zipcode'];
                } else {
                    $this->session->set('billingerrors', $hasErrors);
                    $this->session->set('paidMerchant_request', TRUE);
                    header('Location: /patron/paymentrequest/pay/' . $_POST['payment_req']);
                    exit;
                }
            }

            if (isset($_POST['payment_mode'])) {
                $fee_id = $this->encrypt->decode($_POST['payment_mode']);
                $pg_details = $this->common->getPaymentGatewayDetails($this->merchant_id, $fee_id);
            } else {
                $pg_details = $this->common->getPaymentGatewayDetails($this->merchant_id);
            }

            if (empty($pg_details)) {
                SwipezLogger::error(__CLASS__, '[E1008]Error while getting merchant pg details Payment_request_id: ' . $payment_request_id . ' Merchant_id: ' . $this->merchant_details['user_id']);
                $this->setGenericError();
            }
            $pg_id = $pg_details['pg_id'];
            // $pg_id = (isset($_POST['pg_id'])) ? $_POST['pg_id'] : $pg_details['pg_id'];
            // $pg_details = $this->common->getPgDetailsbyID($pg_id, $this->merchant_id,  $_POST['currency']);
            $discount = 0;
            $absolute_cost = array_sum($_POST['booking_amount']);
            $surcharge_amount = $this->common->getSurcharge($this->merchant_id, $absolute_cost);
            $absolute_cost = $absolute_cost + $surcharge_amount;

            if (isset($_POST['coupon_id'])) {
                $coupon_id = $_POST['coupon_id'];
                if ($coupon_id > 0) {
                    $coupon_details = $this->common->getSingleValue('coupon', 'coupon_id', $coupon_id);
                    if ($coupon_details['type'] == 1) {
                        $discount = $coupon_details['fixed_amount'];
                    } else {
                        $discount = $coupon_details['percent'] * $amount / 100;
                    }
                    $absolute_cost = $absolute_cost - $discount;
                }
            }

            if ($this->validateLogin('patron') == FALSE) {
                $user_detail = array('@patron_first_name' => $first_name, '@patron_last_name' => $last_name, '@patron_address1' => $userdet['address'], '@patron_city' => $_POST['city'], '@patron_state' => $_POST['state'], '@patron_zipcode' => $_POST['zipcode'], '@patron_mobile_no' => $_POST['mobile'], '@patron_email' => $_POST['email'], '@narrative' => 'Booking ' . $_POST['plan_name'], '@absolute_cost' => $absolute_cost, '@company_name' => $this->merchant_details['company_name'], '@company_merchant_id' => $this->merchant_id);
            } else {
                $user_detail = array('@patron_first_name' => $userdet['first_name'], '@patron_last_name' => $userdet['last_name'], '@patron_address1' => $userdet['address'], '@patron_city' => $userdet['city'], '@patron_state' => $userdet['state'], '@patron_zipcode' => $userdet['zipcode'], '@patron_mobile_no' => $userdet['mobile_no'], '@patron_email' => $userdet['email_id'], '@narrative' => 'Booking ' . $_POST['plan_name'], '@absolute_cost' => $absolute_cost, '@company_name' => $this->merchant_details['company_name'], '@company_merchant_id' => $this->merchant_id);
            }

            $transaction_id = $reqmodel->intiatePaymentTransaction(0, 5, $customer_id, $patron_id, $this->merchant_details['user_id'], $this->merchant_id, $absolute_cost, $surcharge_amount, $discount, 0, '', $pg_id, $pg_details['fee_detail_id'], $franchise_id, 0, 'Booking ' . $_POST['plan_name'], count($_POST['booking_slots']));
            $int = 0;
            if ($absolute_cost > 0) {
                $is_paid = 0;
            } else {
                $is_paid = 1;
                $this->common->genericupdate('payment_transaction', 'payment_transaction_status', 1, 'pay_transaction_id', $transaction_id);
            }
            foreach ($_POST['booking_slots'] as $slot_) {
                $bookingModel->saveBookingTransactionDetails($transaction_id, $_POST['calendar_id'], $_POST['date'], $_POST['booking_slots'][$int], $_POST['booking_fromto'][$int], $_POST['category_name'], $_POST['calendar_title'], $_POST['booking_amount'][$int], 0, $patron_id, $is_paid);
                $int++;
            }
            $this->session->set('transaction_type', 'booking');
            $this->session->set('transaction_id', $transaction_id);
            if ($absolute_cost > 0) {
                SwipezLogger::info(__CLASS__, 'Payment transaction initiated. Transaction id: ' . $transaction_id . ', Request id: ' . $payment_request_id . ', Merchant name: ' . $this->merchant_details['company_name'] . ', Patron email: ' . $email . ', Patron id: ' . $customer_id . ', Amount: ' . $absolute_cost);

                $paymentWrapper = new PaymentWrapperController();
                $pg_details['repath'] = '/patron/paymentrequest/pay/' . $_POST['payment_req'];
                $pg_details['fee_id'] = $pg_details['fee_detail_id'];
                $user_detail["xtransaction_id"] = $transaction_id;
                $user_detail["currency"] =   $_POST['currency'];

                $paymentWrapper->paymentProcced($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address, 'guest', $this->common, $this->view, $this->encrypt);
            } else {
                $bookingModel->updateSlotsStatus($transaction_id);
                require_once CONTROLLER . 'patron/Event.php';
                $event = new Event();
                $event->freeBooking($transaction_id, null);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function validateConfirmbooking($booking_details)
    {
        require_once MODEL . 'merchant/BookingsModel.php';
        $bookingModel = new BookingsModel();
        foreach ($booking_details as $booking_data) {
            $avalialble_slots  = $bookingModel->getAvailableSlotbySlotID($booking_data["slot_id"]);
            if ($booking_data["qty"] > $avalialble_slots["available_seat"]) {
                return false;
            }
        }
    }

    public function membershippayment()
    {
        try {
            $this->setLandingDetails();
            require_once MODEL . 'merchant/BookingsModel.php';
            $bookingModel = new BookingsModel();
            require_once MODEL . 'patron/PaymentrequestModel.php';
            $reqmodel = new PaymentRequestModel();

            $patron_id = $this->session->getCookie('userid');
            $userdet = $this->model->getUserDetails($patron_id);

            $customer_id = $this->session->getCookie('member_customer_id');

            if (isset($_POST['payment_mode'])) {
                $fee_id = $this->encrypt->decode($_POST['payment_mode']);
                $pg_details = $this->common->getPaymentGatewayDetails($this->merchant_id, $fee_id);
            } else {
                $pg_details = $this->common->getPaymentGatewayDetails($this->merchant_id);
            }

            if (empty($pg_details)) {
                SwipezLogger::error(__CLASS__, '[E1008]Error while getting merchant pg details Payment_request_id: ' . $payment_request_id . ' Merchant_id: ' . $this->merchant_details['user_id']);
                $this->setGenericError();
            }
            $pg_id = $pg_details['pg_id'];
            $discount = 0;

            $absolute_cost = $_POST['amount'];
            $surcharge_amount = $this->common->getSurcharge($this->merchant_id, $absolute_cost);
            $absolute_cost = $absolute_cost + $surcharge_amount;
            $franchise_id = 0;
            $member_detail = $this->common->getSingleValue('booking_membership', 'membership_id', $_POST['membership_id']);

            $user_detail = array('@patron_first_name' => $userdet['first_name'], '@patron_last_name' => $userdet['last_name'], '@patron_address1' => $_POST['address'], '@patron_city' => $_POST['city'], '@patron_state' => $_POST['state'], '@patron_zipcode' => $_POST['zipcode'], '@patron_mobile_no' => $_POST['mobile'], '@patron_email' => $_POST['email'], '@narrative' => 'Membership ' . $member_detail['title'], '@absolute_cost' => $absolute_cost, '@company_name' => $this->merchant_details['company_name'], '@company_merchant_id' => $this->merchant_id);


            $first_name = $userdet['first_name'];
            $last_name = $userdet['last_name'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];

            $start_date = $bookingModel->getmembershipStartDate($member_detail['category_id'], $customer_id);
            $this->session->set('userid', $patron_id);
            $transaction_id = $reqmodel->intiatePaymentTransaction(0, 6, $customer_id, $patron_id, $this->merchant_details['user_id'], $this->merchant_id, $absolute_cost, $surcharge_amount, $discount, 0, '', $pg_id, $pg_details['fee_detail_id'], $franchise_id, 0, 'Membership ' . $member_detail['title'], 1);
            $bookingModel->saveBookingMembershipDetails($transaction_id, $this->merchant_id, $patron_id, $customer_id, $member_detail['category_id'], $_POST['membership_id'], $member_detail['days'], $start_date, $member_detail['title'], $member_detail['amount'], 0);
            $this->session->set('transaction_type', 'membership');
            $this->session->set('transaction_id', $transaction_id);
            SwipezLogger::info(__CLASS__, 'Payment transaction initiated. Transaction id: ' . $transaction_id . ', Request id: ' . $payment_request_id . ', Merchant name: ' . $this->merchant_details['company_name'] . ', Patron email: ' . $email . ', Patron id: ' . $customer_id . ', Amount: ' . $absolute_cost);

            $paymentWrapper = new PaymentWrapperController();
            $pg_details['repath'] = '';
            $pg_details['fee_id'] = $pg_details['fee_detail_id'];
            $paymentWrapper->paymentProcced($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address, 'guest', $this->common, $this->view);
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function booking()
    {
        $this->setLandingDetails();
        if ($this->validateLogin('patron') == FALSE) {
            $this->session->destroyuser();
            $this->session->set('isGuest', TRUE);
            $this->smarty->assign("isGuest", '1');
            $this->view->usertype = '';
        }
        $this->view->backpath = 'booking';
        $category_list = $this->model->getCategoryList($this->merchant_id);
        $landing_details = $this->common->getSingleValue('merchant_landing', 'merchant_id', $this->merchant_id);
        $this->smarty->assign("booking_background", $landing_details['booking_background']);
        $this->smarty->assign("booking_title", $landing_details['booking_title']);
        $this->view->hide_header_menu = $landing_details['booking_hide_menu'];
        $this->smarty->assign("category_list", $category_list);
        $this->smarty->assign("current_date", date('d M Y'));
        $this->smarty->assign("merchant_id", $this->merchant_id);
        $this->view->js = array('booking');
        if (count($category_list) == 1) {
            $this->view->jsscript = "setMerchantDays(" . $category_list[0]['category_id'] . ", '');";
        }
        $this->session->set('valid_ajax', 'calendarJson');
        $this->smarty->assign("title", 'Booking Calendar');
        $this->view->title = $this->merchant_details['company_name'] . " - Venue booking calendar";
        $this->view->selected = 'booking';
        $this->view->render('header/merchantwebsite');
        // $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
        $this->smarty->display(VIEW . 'merchant/landing/sport_booking.tpl');
        //$this->smarty->display(VIEW . 'merchant/landing/calendar.tpl');
        $this->view->render('footer/merchantwebsite');
    }

    function validatememberlogin($url, $category_id)
    {
        if ($this->session->getCookie('member_login') != TRUE) {
            $this->session->set('login_cat_id', $category_id);
            header("Location:/m/" . $url . '/login');
            exit;
        }
    }

    function login()
    {
        $this->setLandingDetails();
        if (!empty($_POST)) {
            require_once MODEL . 'CablePackageModel.php';
            $cable = new CablePackageModel();
            $result = $cable->loginCheck($_POST['mobile'], $_POST['password']);
            if (empty($result)) {
                $this->smarty->assign("error", 'Please enter valid Mobile and Password');
            } else {
                if ($result['user_status'] != 2) {
                    $this->smarty->assign("error", 'User is not verified');
                } else {
                    $customer_id = $this->common->getRowValue('customer_id', 'customer', 'user_id', $result['user_id'], 0, " and merchant_id='" . $this->merchant_id . "'");
                    if ($customer_id > 0) {
                    } else {
                        require_once MODEL . 'merchant/CustomerModel.php';
                        $customermodel = new CustomerModel();
                        $customer_code = $customermodel->getCustomerCode($this->merchant_id);
                        $cresult = $customermodel->saveCustomer($this->merchant_details['user_id'], $this->merchant_id, $customer_code, $result['first_name'], $result['last_name'], $result['email_id'], $result['mobile_no'], '', '', '', '', '', array(), array());
                        $this->common->genericupdate('customer', 'user_id', $result['user_id'], 'customer_id', $cresult['customer_id']);
                    }
                    header("Location:/m/" . $this->merchant_details['display_url'] . '/selectcourt');
                    exit;
                }
            }
        }

        $this->smarty->assign("merchant_id", $this->merchant_id);
        $this->view->selected = 'booking';
        $this->view->render('header/merchantwebsite');
        $this->smarty->display(VIEW . 'merchant/landing/login.tpl');
        $this->view->render('footer/merchantwebsite');
    }

    function memberregister()
    {
        $this->setLandingDetails();
        $page = 'register';
        if (!empty($_POST)) {
            $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if ($result) {
                $post_data['name'] = $_POST['name'];
                $post_data['email'] = $_POST['email'];
                $post_data['mobile'] = $_POST['mobile'];
                $post_data['password'] = $_POST['password'];
                $post_data['merchant_id'] = $this->encrypt->encode($this->merchant_id);
                $env = getenv('ENV');
                if ($env != 'PROD') {
                    $post_url = 'https://h7sak-api.swipez.in';
                } else {
                    $post_url = 'https://intapi.swipez.in';
                }
                dd($post_data);
                $response = $this->cronrequest($post_data, $post_url . '/api/v1/loyalty/register');
                $res = json_decode($response, 1);
                $this->smarty->assign("post_url", $post_url);
                $this->smarty->assign("otp_details", $res);
                $page = 'otp';
            } else {
                $error = "Invalid captcha please click on captcha box";
            }
        }
        $this->smarty->assign("haserrors", $error);
        $this->smarty->assign("merchant_id", $this->merchant_id);
        $this->view->selected = 'booking';
        $this->view->render('header/merchantwebsite');
        $this->smarty->display(VIEW . 'merchant/landing/' . $page . '.tpl');
        $this->view->render('footer/merchantwebsite');
    }

    function validateMembership($category_id, $date)
    {
        $customer_id = $this->session->getCookie('member_customer_id');
        $customer_membership = $this->model->getMembership($category_id, $customer_id);
        $valid = 0;
        $int = 0;
        $reminder = 1;
        $reminder_date = '';
        $max_date = $this->date_add(5, 1);
        if (empty($customer_membership)) {
            $reminder = 0;
        }
        foreach ($customer_membership as $row) {
            if ($row['end_date'] >= $date) {
                $customer_membership[$int]['status'] = 'Active';
                $valid = 1;
                if ($row['end_date'] > $max_date) {
                    $reminder = 0;
                } else {
                    if ($reminder_date == '') {
                        $reminder_date = $row['end_date'];
                        $this->smarty->assign("end_date", $row['end_date']);
                    }
                }
            } else {
                $customer_membership[$int]['status'] = 'Expired';
            }
            $customer_membership[$int]['transaction_link'] = $this->encrypt->encode($row['transaction_id']);
            $int++;
        }

        $this->smarty->assign("valid_membership", $valid);
        $this->smarty->assign("customer_membership", $customer_membership);
        $this->smarty->assign("reminder", $reminder);
        if ($reminder == 1 || $valid == 0) {
            $list = $this->common->getListValue('booking_membership', 'merchant_id', $this->merchant_id, 1);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'membership_id');
            $this->smarty->assign("membership_list", $list);
        }
        return $valid;
    }

    function selectcourt()
    {
        header('Cache-Control: no cache'); //no cache
        session_cache_limiter('private_no_expire'); // works
        $this->setLandingDetails();
        $login_cat_id = $this->session->get('login_cat_id');
        if (isset($login_cat_id)) {
            $_POST['category_id'] = $login_cat_id;
            $this->session->remove('login_cat_id');
        }
        $membership_transaction_success = $this->session->getCookie('membership_transaction_success');
        if (isset($membership_transaction_success)) {
            $this->smarty->assign("membership_transaction_success", 1);
            $this->smarty->assign("membership_transaction_receipt", $this->session->getCookie('membership_transaction_receipt'));
            $this->session->remove('membership_transaction_success');
            $this->session->remove('membership_transaction_receipt');
            $login_cat_id = $this->session->getCookie('login_cat_id');
            $_POST['category_id'] = $login_cat_id;
            $this->session->removeCookie('login_cat_id');
        }
        if (empty($_POST)) {
            header("Location:/m/" . $this->merchant_details['display_url'] . '/booking');
            exit;
        }
        $this->view->hide_header_menu = $this->merchant_details['booking_hide_menu'];
        $this->view->backpath = 'booking';
        $category_list = $this->model->getCategoryList($this->merchant_id);
        $date = new DateTime($_POST['booking_date']);
        require_once MODEL . 'merchant/BookingsModel.php';
        $is_membership = $this->common->getRowValue('membership', 'booking_categories', 'category_id', $_POST['category_id']);
        $is_valid = 1;
        if ($is_membership == 1) {
            $this->validateMemberLogin($this->merchant_details['display_url'], $_POST['category_id']);
            $is_valid = $this->validateMembership($_POST['category_id'], $date->format('Y-m-d'));
        }
        $this->smarty->assign("is_membership", $is_membership);

        $bookingModel = new BookingsModel();
        $slots_array = $bookingModel->getCategorySlots($date->format('Y-m-d'), $_POST['category_id']);
        $from_time = substr($_POST['slot_time'], 0, 8);
        $to_time = substr($_POST['slot_time'], 12, 8);
        $from_time = new DateTime($from_time);
        $to_time = new DateTime($to_time);
        if ($is_valid == 1) {
            if ($_POST['slot_time'] != '') {
                $court = $bookingModel->getSlotCategoryCourt($date->format('Y-m-d'), $from_time->format('H:i:s'), $to_time->format('H:i:s'), $_POST['category_id']);
            } else {
                $court = $bookingModel->getCategoryCourt($date->format('Y-m-d'), $_POST['category_id']);
            }
            foreach ($slots_array as $slot) {
                $slots[] = $slot['time_from'] . ' To ' . $slot['time_to'];
            }
            $int = 0;
            foreach ($court as $c) {
                $slotsdetails = $bookingModel->getSlots($date->format('Y-m-d'), $c['calendar_id']);
                $court[$int]['available'] = count($slotsdetails);
                $court[$int]['slots'] = $slotsdetails;
                $int++;
            }
            $this->smarty->assign("courts", $court);
        }
        if ($_POST['booking_date'] == '') {
            $_POST['booking_date'] = date('d M Y');
        }
        $this->view->jsscript = "setMerchantDays(" . $_POST['category_id'] . ", '" . $date->format('Y,m,d') . "');";
        $this->view->category_id = $_POST['category_id'];
        $this->view->select_booking_date = $date->format('Y,m,d');
        $this->view->booking_date = $_POST['booking_date'];
        $this->smarty->assign("is_valid", $is_valid);
        $this->smarty->assign("category_id", $_POST['category_id']);
        $this->smarty->assign("category_list", $category_list);
        $this->smarty->assign("slots_array", $slots);
        $this->smarty->assign("slot_time", $_POST['slot_time']);
        $this->smarty->assign("current_date", $_POST['booking_date']);
        $this->smarty->assign("merchant_id", $this->merchant_id);
        $this->view->js = array('booking');
        $this->session->set('valid_ajax', 'calendarJson');
        $this->smarty->assign("title", 'Booking Calendar');
        $this->view->title = "Booking Calendar";
        $this->view->selected = 'booking';
        $this->view->render('header/merchantwebsite');
        // $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
        $this->smarty->display(VIEW . 'merchant/landing/select_court.tpl');
        //$this->smarty->display(VIEW . 'merchant/landing/calendar.tpl');
        $this->view->render('footer/booking_calendar');
    }

    function selectslot()
    {
        header('Cache-Control: no cache'); //no cache
        session_cache_limiter('private_no_expire'); // works
        $this->setLandingDetails();
        if (empty($_POST)) {
            header("Location:/m/" . $this->merchant_details['display_url'] . '/booking');
            exit;
        }
        $category_list = $this->model->getCategoryList($this->merchant_id);
        $date = new DateTime($_POST['booking_date']);
        $this->view->hide_header_menu = $this->merchant_details['booking_hide_menu'];
        $last_date = strtotime($_POST['booking_date'] . ' -1 day');
        $last_date = date('d M Y', $last_date);
        $next_date = strtotime($_POST['booking_date'] . ' 1 day');
        $next_date = date('d M Y', $next_date);
        require_once MODEL . 'merchant/BookingsModel.php';
        $bookingModel = new BookingsModel();
        $slots_array = $bookingModel->getCategorySlots($date->format('Y-m-d'), $_POST['category_id']);
        $slot_id = 0;
        if ($_POST['slot_time'] != '') {
            $from_time = substr($_POST['slot_time'], 0, 8);
            $to_time = substr($_POST['slot_time'], 12, 8);
            $from_time = new DateTime($from_time);
            $to_time = new DateTime($to_time);
            $slot_id = $bookingModel->getSlotCategoryCourtID($date->format('Y-m-d'), $from_time->format('H:i:s'), $to_time->format('H:i:s'), $_POST['category_id'], $_POST['calendar_id']);
        }
        foreach ($slots_array as $slot) {
            $slots[] = $slot['time_from'] . ' To ' . $slot['time_to'];
        }
        $int = 0;

        $slotsdet = $bookingModel->getSlotsv2($date->format('Y-m-d'), $_POST['calendar_id'], $_POST['package_id']);
        $package = $bookingModel->getPackageDetails($_POST['package_id']);

        foreach ($slotsdet as &$data) {
            $slots_package_array = [];
            if ($data["slot_price_count"] > 1) {
                $slot_title_array = explode(",", $data["slot_title"]);
                $slot_description_array = explode(",", $data["slot_description"]);
                $slot_price_array = explode(",", $data["slot_price"]);
                $slot_id_array = explode(",", $data["slot_id"]);
                $is_multiple_array = explode(",", $data["is_multiple"]);
                $min_seat_array = explode(",", $data["min_seat"]);
                $max_seat_array = explode(",", $data["max_seat"]);
                $total_seat_array = explode(",", $data["total_seat"]);
                $available_seat_array = explode(",", $data["available_seat"]);
                $slot_special_text_array = explode(",", $data["slot_special_text"]);
                foreach ($slot_title_array as $lkey => $slot_title) {
                    $slot_price = $slot_price_array[$lkey];
                    $slot_description = $slot_description_array[$lkey];
                    $slot_ids = $slot_id_array[$lkey];
                    $is_multiple = $is_multiple_array[$lkey];
                    $min_seat = $min_seat_array[$lkey];
                    $max_seat = $max_seat_array[$lkey];
                    $total_seat = $total_seat_array[$lkey];
                    $available_seat = $available_seat_array[$lkey];
                    $slot_special_text = $slot_special_text_array[$lkey];
                    $array = [
                        'slot_id' =>  $slot_ids,
                        'slot_title' =>  $slot_title,
                        'slot_description' => $slot_description,
                        'is_multiple' => $is_multiple,
                        'slot_price' => $slot_price,
                        'min_seat' => $min_seat,
                        'max_seat' => $max_seat,
                        'total_seat' => $total_seat,
                        'available_seat' => $available_seat,
                        'slot_special_text' => $slot_special_text
                    ];
                    array_push($slots_package_array, $array);
                }
            } else if ($data["slot_price_count"] == 1) {
                $array = [
                    'slot_id' =>  $data["slot_id"],
                    'slot_title' => $data["slot_title"],
                    'slot_description' => $data["slot_description"],
                    'slot_price' => $data["slot_price"],
                    'is_multiple' => $data["is_multiple"],
                    'slot_price' =>  $data["slot_price"],
                    'min_seat' =>  $data["min_seat"],
                    'max_seat' =>  $data["max_seat"],
                    'total_seat' => $data["total_seat"],
                    'available_seat' => $data["available_seat"],
                    'slot_special_text' =>  $data["slot_special_text"]
                ];

                array_push($slots_package_array, $array);
            }
            $data["slots_package_array"] = $slots_package_array;
        }
        $max_booking = $this->common->getRowValue('max_booking', 'booking_calendars', 'calendar_id', $_POST['calendar_id']);
        $description = $this->common->getRowValue('description', 'booking_calendars', 'calendar_id', $_POST['calendar_id']);
        $this->view->category_id = $_POST['category_id'];
        $this->view->select_booking_date = $date->format('Y,m,d');
        $this->view->booking_date = $_POST['booking_date'];
        $this->smarty->assign("category_id", $_POST['category_id']);
        $this->smarty->assign("court_name", $_POST['court_name']);
        $this->smarty->assign("calendar_id", $_POST['calendar_id']);
        $this->smarty->assign("package_id", $_POST['package_id']);
        $this->smarty->assign("max_booking", $max_booking);
        $this->smarty->assign("description", $description);
        $this->smarty->assign("slot_id", $slot_id);
        $this->smarty->assign("next_date", $next_date);
        $this->smarty->assign("last_date", $last_date);
        $this->smarty->assign("category_list", $category_list);
        $this->smarty->assign("slots", $slotsdet);
        $this->smarty->assign("slots_array", $slots);
        $this->smarty->assign("package", $package);
        $this->smarty->assign("slot_time", $_POST['slot_time']);
        $this->smarty->assign("current_date", $_POST['booking_date']);
        $this->smarty->assign("merchant_id", $this->merchant_id);
        $this->view->js = array('booking');
        $this->session->set('valid_ajax', 'calendarJson');
        $this->smarty->assign("title", 'Booking Calendar');
        $this->view->title = "Booking Calendar";
        $this->view->selected = 'booking';
        $this->view->render('header/merchantwebsite');
        // $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
        $this->smarty->display(VIEW . 'merchant/landing/select_slot.tpl');
        //$this->smarty->display(VIEW . 'merchant/landing/calendar.tpl');
        $this->view->render('footer/booking_calendar');
    }

    function selectpackage()
    {
        header('Cache-Control: no cache'); //no cache
        session_cache_limiter('private_no_expire'); // works
        $this->setLandingDetails();
        if (empty($_POST)) {
            header("Location:/m/" . $this->merchant_details['display_url'] . '/booking');
            exit;
        }
        $category_list = $this->model->getCategoryList($this->merchant_id);
        $date = new DateTime($_POST['booking_date']);
        $this->view->hide_header_menu = $this->merchant_details['booking_hide_menu'];
        $last_date = strtotime($_POST['booking_date'] . ' -1 day');
        $last_date = date('d M Y', $last_date);
        $next_date = strtotime($_POST['booking_date'] . ' 1 day');
        $next_date = date('d M Y', $next_date);
        require_once MODEL . 'merchant/BookingsModel.php';
        $bookingModel = new BookingsModel();
        $slots_array = $bookingModel->getCategorySlots($date->format('Y-m-d'), $_POST['category_id']);
        $slot_id = 0;
        if ($_POST['slot_time'] != '') {
            $from_time = substr($_POST['slot_time'], 0, 8);
            $to_time = substr($_POST['slot_time'], 12, 8);
            $from_time = new DateTime($from_time);
            $to_time = new DateTime($to_time);
            $slot_id = $bookingModel->getSlotCategoryCourtID($date->format('Y-m-d'), $from_time->format('H:i:s'), $to_time->format('H:i:s'), $_POST['category_id'], $_POST['calendar_id']);
        }
        foreach ($slots_array as $slot) {
            $slots[] = $slot['time_from'] . ' To ' . $slot['time_to'];
        }
        $int = 0;
        //$slotsdet = $bookingModel->getSlotsv2($date->format('Y-m-d'), $_POST['calendar_id']);
        $packages = $bookingModel->getAllPackages($date->format('Y-m-d'), $_POST['calendar_id']);
        //dd( $packages);
        $max_booking = $this->common->getRowValue('max_booking', 'booking_calendars', 'calendar_id', $_POST['calendar_id']);
        $description = $this->common->getRowValue('description', 'booking_calendars', 'calendar_id', $_POST['calendar_id']);
        $this->view->category_id = $_POST['category_id'];
        $this->view->select_booking_date = $date->format('Y,m,d');
        $this->view->booking_date = $_POST['booking_date'];
        $this->smarty->assign("category_id", $_POST['category_id']);
        $this->smarty->assign("court_name", $_POST['court_name']);
        $this->smarty->assign("calendar_id", $_POST['calendar_id']);
        $this->smarty->assign("max_booking", $max_booking);
        $this->smarty->assign("description", $description);
        $this->smarty->assign("slot_id", $slot_id);
        $this->smarty->assign("next_date", $next_date);
        $this->smarty->assign("last_date", $last_date);
        $this->smarty->assign("category_list", $category_list);
        $this->smarty->assign("slots", $slotsdet);
        $this->smarty->assign("slots_array", $slots);
        $this->smarty->assign("packages", $packages);
        $this->smarty->assign("slot_time", $_POST['slot_time']);
        $this->smarty->assign("current_date", $_POST['booking_date']);
        $this->smarty->assign("merchant_id", $this->merchant_id);
        $this->view->js = array('booking');
        $this->session->set('valid_ajax', 'calendarJson');
        $this->smarty->assign("title", 'Booking Calendar');
        $this->view->title = "Booking Calendar";
        $this->view->selected = 'booking';
        $this->view->render('header/merchantwebsite');
        // $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
        $this->smarty->display(VIEW . 'merchant/landing/select_package.tpl');
        //$this->smarty->display(VIEW . 'merchant/landing/calendar.tpl');
        $this->view->render('footer/booking_calendar');
    }

    public function confirmslot()
    {
        try {
            $this->setLandingDetails();
            $this->view->js = array('invoice');
            if (count($_POST['booking_slots']) < 2) {
                header('location: /m/' . $this->merchant_details['display_url'] . '/booking');
                die();
            }
            $is_coupon = 0; //$this->common->isMerchantCoupon($result['user_id']);
            $this->view->hide_header_menu = $this->merchant_details['booking_hide_menu'];
            $this->session->remove('paidMerchant_request');
            $member_customer_id = $this->session->getCookie('member_customer_id');
            if (isset($member_customer_id)) {
                $customerdetails = $this->common->getCustomerDetails($member_customer_id, $this->merchant_id);
                $this->smarty->assign("customerdetails", $customerdetails);
            }
            if ($this->validateLogin('patron') == FALSE) {
                $this->smarty->assign("isGuest", '1');
            }
            $amount = 0;

            require_once MODEL . 'merchant/BookingsModel.php';
            $bookingModel = new BookingsModel();

            $sint = 0;
            foreach ($_POST['booking_slot_id'] as $slots_id) {
                if ($_POST['booking_slot_id'][$sint] > 0) {
                    $booking_slots_ids[] = $_POST['booking_slot_id'][$sint];
                    $booking_slots_qty[] = $_POST['booking_qty'][$sint];
                }
                $sint++;
            }


            $cal_info = $bookingModel->getCalendarCategory($_POST['calendar_id']);
            $cal_info['capture'] = explode(',', $cal_info['capture_details']);
            $slots_array = $bookingModel->getSlots($_POST['date'], $_POST['calendar_id']);
            $int = 0;
            foreach ($slots_array as $slot) {
                if (in_array($slot['slot_id'], $booking_slots_ids)) {
                    $booking_slots[$int]['fromto'] = 'From ' . $slot['time_from'] . ' To ' . $slot['time_to'];
                    $booking_slots[$int]['amount'] = $slot['slot_price'];
                    $booking_slots[$int]['slot_id'] = $slot['slot_id'];
                    $booking_slots[$int]['slot_title'] = $slot['slot_title'];
                    $booking_slots[$int]['package_name'] = $slot['package_name'];
                    $booking_slots[$int]['category'] = $cal_info['category_name'];
                    $booking_slots[$int]['calendar'] = $cal_info['calendar_title'];
                    $keys = array_keys($booking_slots_ids, $slot['slot_id']);
                    $qty = $booking_slots_qty[$keys[0]];
                    $booking_slots[$int]['booking_qty'] = $qty;
                    $amount = $amount + ($slot['slot_price'] * $qty);
                    $int++;
                }
            }

            $this->smarty->assign("booking_slots", $booking_slots);
            $this->smarty->assign("date", $_POST['date']);
            $this->smarty->assign("calendar_id", $_POST['calendar_id']);
            $this->smarty->assign("category_name", $cal_info['category_name']);
            $this->smarty->assign("calendar_title", $cal_info['calendar_title']);
            $this->smarty->assign("confirmation_message", $cal_info['confirmation_message']);
            $this->smarty->assign("tandc", $cal_info['tandc']);
            $this->smarty->assign("cancellation_policy", $cal_info['cancellation_policy']);


            $surcharge_amount = $this->common->getSurcharge($this->merchant_id, $amount);
            $pg_details = $this->common->getMerchantPG($this->merchant_id);
            if (count($pg_details) > 1) {
                foreach ($pg_details as $pg) {
                    if ($pg['pg_type'] == 4 || $pg['pg_type'] == 6) {
                        $netbanking_pg = $this->encrypt->encode($pg['pg_id']);
                        $net_fee_id = $this->encrypt->encode($pg['fee_detail_id']);
                    } else if ($pg['pg_type'] == 2) {
                        $paytm_pg = $this->encrypt->encode($pg['pg_id']);
                        $paytm_fee_id = $this->encrypt->encode($pg['fee_detail_id']);
                    } else if ($pg['pg_type'] == 5) {
                        $paytm_sub_pg = $this->encrypt->encode($pg['pg_id']);
                        $paytm_sub_fee_id = $this->encrypt->encode($pg['fee_detail_id']);
                    } else {
                        $credit_card_pg = $this->encrypt->encode($pg['pg_id']);
                        $credit_fee_id = $this->encrypt->encode($pg['fee_detail_id']);
                    }
                }
                $radio[] = array('name' => 'Credit card', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);
                $radio[] = array('name' => 'Debit card', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);
                if (isset($netbanking_pg)) {
                    $radio[] = array('name' => 'Net banking', 'pg_id' => $netbanking_pg, 'fee_id' => $net_fee_id);
                } else {
                    $radio[] = array('name' => 'Net banking', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);
                }
                if (isset($paytm_pg)) {
                    $radio[] = array('name' => 'PAYTM', 'pg_id' => $paytm_pg, 'fee_id' => $paytm_fee_id);
                }
                if (isset($paytm_sub_pg)) {
                    // $radio[] = array('name' => 'PAYTM_SUB', 'pg_id' => $paytm_sub_pg, 'fee_id' => $paytm_sub_fee_id);
                }
                $this->smarty->assign("radio", $radio);
                $this->smarty->assign("post_url", '/payment-gateway');
                $this->smarty->assign("request_post_url", '/m/' . $this->merchant_details['display_url'] . '/slotpayment');
                $this->smarty->assign("is_new_pg", true);
                $this->smarty->assign("encrypt_grandtotal", $this->encrypt->encode($amount));
                $this->view->js = array('invoice');
                $this->smarty->assign("grand_total", $amount);
                $this->smarty->assign("surcharge_amount", $surcharge_amount);
                $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
            } else {
                $this->smarty->assign("post_url", '/m/' . $this->merchant_details['display_url'] . '/slotpayment');
                $this->smarty->assign("request_post_url", '/m/' . $this->merchant_details['display_url'] . '/slotpayment');
                $this->smarty->assign("is_new_pg", false);
                $this->smarty->assign("grand_total", $amount + $surcharge_amount);
                $this->smarty->assign("surcharge_amount", $surcharge_amount);
                $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
            }

            $this->smarty->assign("package_type", $this->session->get('package_type'));
            $this->session->set('confirm_payment', TRUE);
            $this->smarty->assign("merchant_id", $this->merchant_id);
            $this->smarty->assign("is_coupon", $is_coupon);
            $this->smarty->assign("plan_id", $link);
            $this->view->selected = 'booking';
            $this->smarty->assign("title", $cal_info['category_name'] . ' ' . $cal_info['calendar_title']);
            $this->smarty->assign("booking_title", $cal_info['category_name'] . ' ' . $cal_info['calendar_title']);
            $this->smarty->assign("booking_unit", $cal_info['booking_unit']);
            $this->smarty->assign("capture_details", $cal_info['capture']);
            $this->view->title = 'Confirm your payment';
            $this->view->render('header/merchantwebsite');
            $this->smarty->display(VIEW . 'merchant/landing/slot_confirm.tpl');
            $this->view->render('footer/merchantwebsite');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Display merchant contact
     */
    function contactus()
    {
        try {
            $this->setLandingDetails();
            $this->smarty->assign("title", 'Contact us');
            $this->view->title = "Company - Contact us";
            $this->view->selected = 'contactus';
            $this->view->render('header/merchantwebsite');
            $this->smarty->display(VIEW . 'merchant/landing/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/landing/contactus.tpl');
            $this->view->render('footer/merchantwebsite');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $this->merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function onlinepayment()
    {
        try {
            $this->setLandingDetails();
            header('Location: /m/' . $this->merchant_details['display_url'] . '/paymybills');
            die();
            //$this->smarty->display(UTIL . 'batch/mailer/' . $this->merchant_details['display_url'] . '.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error online payment mailer Error:  for user id [' . $this->merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function memberlogout()
    {
        try {
            $this->setLandingDetails();
            $this->session->destroy();
            header('Location: /m/' . $this->merchant_details['display_url'] . '/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E08398]Error while member logout' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function plansaveinvoice($user_id, $customer_id, $amount, $plan, $values, $ids, $coupon_id = 0)
    {
        try {
            $this->setLandingDetails();
            $template_id = $this->model->getplantemplate($user_id);
            if ($template_id != '') {
                $rows = $this->common->getTemplateBreakup($template_id);
                require_once MODEL . 'merchant/TemplateModel.php';
                $templatemodel = new TemplateModel();
                $info = $templatemodel->getTemplateInfo($template_id);
                require_once MODEL . 'merchant/InvoiceModel.php';
                $InvoiceModel = new InvoiceModel();
                $row = $InvoiceModel->saveInvoice($_POST['invoice_number'], $template_id, $user_id, $customer_id, date('Y-m-d'), date('Y-m-d'), 'Package ' . date('Y M'), $values, $ids, $plan, $amount, 0, 'Particular total', 'Tax total', 0, 0, 0, '', 0, 0, 0, $coupon_id, 0, 1, 0, 0, null, null, 0, 0, '', $customer_id);
                return $row;
            } else {
                SwipezLogger::error(__CLASS__, '[EWARN158] Missing Package Template');
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E088]Error while paln saveinvoice mailer Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function showcoupon($merchant_id = null, $transaction_link = null)
    {
        try {
            $this->setLandingDetails();
            $transaction_id = $this->encrypt->decode($transaction_link);
            $payment_request_id = '';
            if (substr($transaction_id, 0, 1) == 'T') {
                $payment_request_id = $this->common->getRowValue('payment_request_id', 'payment_transaction', 'pay_transaction_id', $transaction_id);
            } else {
                $payment_request_id = $this->common->getRowValue('payment_request_id', 'xway_transaction', 'xway_transaction_id', $transaction_id);
            }
            if (strlen($payment_request_id) == 10) {
                $invoice_link = $this->encrypt->encode($payment_request_id);
                $this->smarty->assign("invoice_link", $invoice_link);
            }
            $this->smarty->assign("transaction_link", $transaction_link);
            $transaction_id = $this->encrypt->decode($transaction_link);
            if ($this->env != 'PROD') {
                $merchant_id = 'Hh-ryPjmWXG2kz-WErY5IQ';
                $transaction_link = 'SPY97IT-aKCMTI_QTMPttw';
                $transaction_id = 'X000049451';
            }
            $this->smarty->assign("court_link", $this->session->getCookie('court_link'));
            $this->session->removeCookie('court_link');
            $this->smarty->assign("merchant_key", $merchant_id);
            $this->smarty->assign("transaction_key", $transaction_id);
            $this->smarty->assign("title", 'Coupon');
            $this->view->title = "Swipez - Coupons";
            $this->view->render('header/coupon');
            $this->smarty->display(VIEW . 'merchant/landing/coupon.tpl');
            $this->view->render('footer/merchantwebsite');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while pay my bills Error:  for merchant id [' . $this->merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function qrcodereceipt($link, $edit = '')
    {
        $this->setLandingDetails();
        $merchant_id = $this->session->get('merchant_id');
        if ($link != 'bookingid') {
            $payment_transaction_id = $this->encrypt->decode($link);
            if (strlen($payment_transaction_id) != 10) {
                $payment_transaction_id = $this->common->getRowValue('value', 'encrypt_backup', 'encrypt_key', $link, 0);
                if ($payment_transaction_id == FALSE) {
                    $this->setInvalidLinkError();
                }
            }
        }

        if (isset($_POST['booking_id'])) {
            $payment_transaction_id = $_POST['booking_id'];
            $link = $this->encrypt->encode($_POST['booking_id']);
        }
        $type = substr($payment_transaction_id, 0, 1);
        $receipt_info = array();
        switch ($type) {
            case 'T':
                $type = 'Online';
                break;
            case 'H':
                $type = 'Offline';
                break;
        }
        if (strlen($payment_transaction_id) == 10) {
            $receipt_info = $this->common->getReceipt($payment_transaction_id, $type);
        }
        if (!empty($receipt_info)) {
            $logo = '';
            if ($receipt_info['image'] == '') {
                if ($receipt_info['merchant_logo'] != '') {
                    $logo = '/uploads/images/landing/' . $receipt_info['merchant_logo'];
                }
            } else {
                $logo = '/uploads/images/logos/' . $receipt_info['image'];
            }
            $this->smarty->assign("logo", $logo);
            $this->smarty->assign("link", $link);
            $this->smarty->assign("is_edit", $edit);
            $this->view->paymentdetail = $receipt_info;
            $this->view->render('header/guest');
            $this->smarty->assign("response", $receipt_info);
            if ($receipt_info['payment_request_type'] == 2) {
                $attendee_details = $this->common->getAttendeeDetails($payment_transaction_id);
                $custom_capture_detail = $this->common->getEventCaptureDetails($payment_transaction_id);
                $this->smarty->assign("c_c_detail", $custom_capture_detail);
                $is_available = 0;
                foreach ($attendee_details as $det) {
                    if ($det['coupon_code'] != '') {
                        $coupon_id = $det['coupon_code'];
                    }
                    if ($det['is_availed'] == 0) {
                        $is_available = 1;
                    }
                }
                if ($coupon_id > 0) {
                    $coupon_details = $this->common->getSingleValue('coupon', 'coupon_id', $coupon_id);
                }

                $this->smarty->assign("coupon_code", $coupon_details['coupon_code']);
                $this->smarty->assign("attendee_details", $attendee_details);
                $typee = 'event';
            } else if ($receipt_info['payment_request_type'] == 5) {
                $booking_details = $this->common->getBookingDetails($payment_transaction_id);
                $is_available = 0;
                foreach ($booking_details as $det) {
                    if ($det['is_availed'] == 0) {
                        $is_available = 1;
                    }
                }
                $this->smarty->assign("booking_details", $booking_details);
                $typee = 'booking';
            }
            $this->smarty->assign("is_available", $is_available);
            $this->smarty->assign("receipt_info", $receipt_info);
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/event/qr_' . $typee . '_attendies.tpl');
            $this->view->render('footer/nonfooter');
        } else {
            if (isset($_POST['booking_id'])) {
                $this->smarty->assign("is_bookingid", 1);
            }
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/event/invalidqrcode.tpl');
            $this->view->render('footer/nonfooter');
        }
    }

    function attendeeavailed($type = 'event')
    {
        $this->setLandingDetails();
        foreach ($_POST['detail_id'] as $id) {
            if (in_array($id, $_POST['is_availed'])) {
                $availed = 1;
            } else {
                $availed = 0;
            }
            if ($type == 'event') {
                $this->common->genericupdate('event_transaction_detail', 'is_availed', $availed, 'event_transaction_detail_id', $id, $this->user_id);
            } else {
                $this->common->genericupdate('booking_transaction_detail', 'is_availed', $availed, 'booking_transaction_detail_id', $id, $this->user_id);
            }
        }
        $this->smarty->assign("link", $_POST['transaction_id']);
        $this->view->render('header/guest');
        $this->smarty->display(VIEW . 'merchant/event/successqrcode.tpl');
        $this->view->render('footer/nonfooter');
    }

    function qrcode()
    {
        $this->setLandingDetails();
        $has_qr = $this->common->getRowValue('has_qrcode', 'merchant_setting', 'merchant_id', $this->merchant_id);
        if ($has_qr == 0) {
            $this->setError('Access denied', 'You do not have access to this feature. If you need access to this feature please contact to support@swipez.in');
            header("Location:/error");
            exit;
        }
        $this->view->render('merchant/event/qrcode');
    }

    function bookingid()
    {
        $this->view->render('merchant/event/bookingid');
    }
}
