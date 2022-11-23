<?php

use App\Http\Controllers\PaymentWrapperController;

/**
 * Event controller class to handle Event requests for patron
 */
class Event extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->patronCookie();
        //TODO : Check if using static function is causing any problems!
        $this->view->js = array('invoice');
        $this->view->selectedMenu = 'event';
        $this->view->disable_talk = 1;
    }

    /**
     * Display event request
     */
    public function redirect()
    {
        // header('Location:/patron/event/view/zKp-uQkJ8VsCryLecFLyRw', TRUE, 301);
    }

    public function defineTag($event_request_id, $type)
    {
        $req_detail = $this->common->getSingleValue('event_request', 'event_request_id', $event_request_id);
        if ($req_detail['global_tag'] != '') {
            $this->view->global_tag = $req_detail['global_tag'];
            $this->smarty->assign("global_tag", $req_detail['global_tag']);
        }
        if ($type == 1 && $req_detail['landing_tag'] != '') {
            $this->view->landing_tag = $req_detail['landing_tag'];
            $this->smarty->assign("landing_tag", $req_detail['landing_tag']);
        }
        if ($type == 2 && $req_detail['success_tag'] != '') {
            $this->view->success_tag = $req_detail['success_tag'];
            $this->smarty->assign("success_tag", $req_detail['success_tag']);
        }
    }

    public function view($link)
    {
        try {
            $payment_request_id = $this->encrypt->decode($link);
            if (strlen($payment_request_id) != 10) {
                $payment_request_id = $this->encryptBackup($link);
                $link = $this->encrypt->encode($payment_request_id);
            }

            $has_errors = $this->session->get('billingerrors');
            if (!empty($has_errors)) {
                $this->smarty->assign("errors", $has_errors);
                $this->session->remove('billingerrors');
            }
            $occurence_id = 0;
            $div_int = 0;
            $occurence_selected = '';
            $rows = $this->common->getInvoiceBreakup($payment_request_id, 'Event');
            $info = $this->common->getPaymentRequestDetails($payment_request_id, NULL, 2);
            $this->defineTag($payment_request_id, 1);
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E008]Error while geting event details. for merchant [' . $this->merchant_id . '] and for event request id [' . $payment_request_id . ']');
                $this->setInvalidLinkError();
            }

            $occurence = $this->common->getListValue('event_occurence', 'event_request_id', $payment_request_id, 1, ' order by start_date');
            $current_datetime = strtotime(date("Y-m-d H:i:s"));
            $current_date = strtotime(date("Y-m-d"));
            if ($info['stop_booking_time'] == '::') {
                $stop_booking_array = array();
            } else {
                $stop_booking_array = explode(':', $info['stop_booking_time']);
            }
            foreach ($occurence as $key => $occ) {
                $occ_time = strtotime($occ['end_date'] . ' ' . $occ['end_time']);
                if ($occ['start_time'] == '') {
                    $occ['start_time'] = '00:00';
                }
                $start_time = strtotime($occ['start_date'] . ' ' . $occ['start_time']);
                if ($occ['start_time'] == $occ['end_time']) {
                    if ($current_date > strtotime($occ['end_date'])) {
                        unset($occurence[$key]);
                    }
                } else {
                    if ($current_datetime > $occ_time) {
                        unset($occurence[$key]);
                    }
                }

                if (!empty($stop_booking_array)) {
                    if ($stop_booking_array[0] != '' && $stop_booking_array[0] != 0) {
                        $start_time = strtotime('-' . $stop_booking_array[0] . ' day', $start_time);
                    }
                    if ($stop_booking_array[1] != '' && $stop_booking_array[1] != 0) {
                        $start_time = strtotime('-' . $stop_booking_array[1] . ' hours', $start_time);
                    }
                    if ($stop_booking_array[2] != '' && $stop_booking_array[2] != 0) {
                        $start_time = strtotime('-' . $stop_booking_array[2] . ' minutes', $start_time);
                    }
                    if ($current_datetime > $start_time) {
                        unset($occurence[$key]);
                    }
                }
            }
            $currency = json_decode($info['currency'], 1);
            if (count($occurence) > 1) {
                if (isset($_POST['occurence_id'])) {
                    $occurence_selected = $_POST['occurence_id'];
                    $occurence_id = $_POST['occurence_id'];
                    $div_int = $_POST['div_int'];
                }
            } else {
                $occurence_id = $occurence[0]['occurence_id'];
                $occurence_selected = $occurence_id;
            }
            if (count($currency) > 1) {
                if (isset($_POST['currency_id'])) {
                    $currency_id = $_POST['currency_id'];
                } else {
                    $currency_id = 0;
                }
            } else {
                $currency_id = $currency[0];
            }
            if ($occurence_id > 0) {
                $occ_row = $this->common->getSingleValue('event_occurence', 'occurence_id', $occurence_id);
                $occurence_date = $occ_row['start_date'];
                if ($occ_row['start_time'] != '') {
                    $occurence_date = $occ_row['start_date'] . ' ' . substr($occ_row['start_time'], 0, 5);
                }
                $type_query = "and (package_type=2 OR occurence like '%" . $occurence_date . "%')";
            } else {
                $type_query = "and package_type=2";
            }
            if ($currency_id != '0') {
                $package = $this->common->getListValue('event_package', 'event_request_id', $payment_request_id, 1, $type_query);
            }
            $count = 0;
            $is_flexible = 0;

            $packagecategory = $this->common->getListValue('event_package', 'event_request_id', $payment_request_id, 1);
            foreach ($packagecategory as $cat) {
                if ($cat['category_name'] == '') {
                    $category_name = 'Default';
                } else {
                    $category_name = $cat['category_name'];
                }
                if (!in_array($category_name, $cataa)) {
                    $pcat['name'] = $category_name;
                    $max_cat_price = $this->common->getRowValue('max(price)', 'event_package', 'event_request_id', $payment_request_id, 1, " and category_name='" . $cat['category_name'] . "'");
                    $min_cat_price = $this->common->getRowValue('min(price)', 'event_package', 'event_request_id', $payment_request_id, 1, " and category_name='" . $cat['category_name'] . "'");
                    $exist_singleday = $this->common->getRowValue('count(package_id)', 'event_package', 'event_request_id', $payment_request_id, 1, " and category_name='" . $cat['category_name'] . "' and package_type=1");
                    $pcat['max_cat_price'] = $max_cat_price;
                    $pcat['min_cat_price'] = $min_cat_price;
                    $pcat['enable_date'] = $exist_singleday;
                    $cataa[] = $category_name;
                    $package_category[] = $pcat;
                }
            }
            if ($info['coupon_id'] > 0) {
                $is_coupon = 1;
            } else {
                $is_coupon = 0;
            }

            if (count($package_category) > 1) {
                $this->smarty->assign("is_actegory_event", 1);
            }
            $this->smarty->assign("package_category", $package_category);

            foreach ($package as $p) {

                if ($p['category_name'] == '') {
                    $category_name = 'Default';
                } else {
                    $category_name = $p['category_name'];
                }

                if ($package[$count]['is_flexible'] == 1) {
                    $is_flexible += $is_flexible;
                }
                $package_id = $package[$count]['package_id'];

                if ($p['package_type'] == 2) {
                    $package[$count]['occurence_id'] = $occurence[0]['occurence_id'];
                } else {
                    $package[$count]['occurence_id'] = $occurence_id;
                }

                if ($package[$count]['seats_available'] != 0) {
                    $available = $this->common->getPackageBookCount($package_id, $package[$count]['occurence_id'], $payment_request_id);
                    $package[$count]['available'] = $package[$count]['seats_available'] - $available;
                    if ($package[$count]['available'] < $p['max_seat']) {
                        if ($package[$count]['available'] < 1 || $package[$count]['sold_out'] == 1) {
                            $package[$count]['sold_out'] = 1;
                        }
                        $package[$count]['max_seat'] = $package[$count]['available'];
                    }
                }
                if ($p['coupon_code'] > 0) {
                    $is_coupon = 1;
                }


                if (count($currency) > 1) {
                    $currency_array = json_decode($p['currency_price'], 1);
                    $package[$count]['price'] = $currency_array[$currency_id]['price'];
                    $package[$count]['min_price'] = $currency_array[$currency_id]['min_price'];
                    $package[$count]['max_price'] = $currency_array[$currency_id]['max_price'];
                }

                $packagearray[$category_name][] = $package[$count];

                $count++;
            }
            $pgdetails = $this->common->getPGDetails($info['merchant_user_id']);
            $this->smarty->assign("pg", $pgdetails);

            $this->smarty->assign("url", $link);
            $this->view->canonical = 'patron/event/view/' . $link;
            $host_link = $this->app_url;
            $link = $host_link . '/patron/event/view/' . $link;
            $this->smarty->assign("payment_request_id", $payment_request_id);
            $this->smarty->assign("host_link", $host_link);
            $this->smarty->assign("div_int", $div_int);
            $this->smarty->assign("link", $link);
            $this->smarty->assign("ENV", $this->view->env);
            $this->smarty->assign("is_coupon", $is_coupon);
            $this->smarty->assign("is_flexible", $is_flexible);
            $this->smarty->assign("occurence_id", $occurence_id);
            $this->smarty->assign("occurence_selected", $occurence_selected);
            $this->smarty->assign("info", $info);
            $this->smarty->assign("package", $packagearray);
            $this->smarty->assign("occurence", $occurence);
            $this->smarty->assign("currency", $currency);
            $currency_icon = $this->common->getRowValue('icon', 'currency', 'code', $currency_id);
            $this->smarty->assign("currency_icon", $currency_icon);
            $this->smarty->assign("currency_id", $currency_id);
            $this->asignSmarty($rows, $info, $payment_request_id);
            $merchant_page = '';
            if ($info['display_url'] != '') {
                $merchant_page = $this->app_url . '/m/' . $info['display_url'];
            }

            $this->smarty->assign('is_payment', FALSE);
            $this->smarty->assign("merchant_page", $merchant_page);
            if ($info['merchant_type'] == 2 && $info['legal_complete'] == 1) {
                $this->session->set('paidMerchant_request', TRUE);
                $this->smarty->assign('is_payment', TRUE);
            }
            $package = $this->common->getSingleValue('merchant', 'merchant_id', $info['merchant_id']);
            if ($package['package_expiry_date'] < date('Y-m-d') && in_array($package['merchant_plan'], array(9, 3))) {
                $this->smarty->assign('is_payment', FALSE);
            }
            $this->smarty->assign("header", $rows);
            $this->smarty->assign("occurence", $occurence);
            $this->view->title = $info['event_name'];
            $this->view->description = '';
            $this->smarty->display(VIEW . 'patron/event/header_event.tpl');
            $this->smarty->display(VIEW . 'patron/event/package_event.tpl');
            $this->smarty->display(VIEW . 'patron/event/footer_event.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E009]Error while payment request view initiate Error:for merchant [' . $merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Display payment confirmation page for patron
     */
    public function pay($link)
    {
        try {
            if (empty($_POST)) {
                $_POST = $this->session->get('event_payment_post');
                if (empty($_POST)) {
                    header('Location:/patron/event/view/' . $link);
                    die();
                } else {
                    $this->session->remove('event_payment_post');
                }
            }
            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            if (!isset($_POST['is_flexible'])) {
                $validator->validateEventBooking();
                $hasErrors = $validator->fetchErrors();
            } else {
                $this->smarty->assign("is_flexible", 1);
            }
            if ($hasErrors == FALSE) {
                $this->view->payment_request_id = $link;
                $payment_request_id = $this->encrypt->decode($link);
                $info = $this->common->getPaymentRequestDetails($payment_request_id, NULL, 2);
                $this->defineTag($payment_request_id, 0);

                foreach ($_POST['package_qty'] as $key => $value) {
                    if ($value == 0) {
                        unset($_POST['package_id'][$key]);
                    }
                }
                if (empty($_POST['package_id'])) {
                    header('Location:/patron/event/view/' . $link);
                    die();
                }
                $extra = ' and package_id in (' . implode(',', $_POST['package_id']) . ')';
                $package = $this->common->getListValue('event_package', 'event_request_id', $payment_request_id, 1, $extra);
                foreach ($package as $row) {
                    $package_array[$row['package_id']] = $row;
                }

                $int = 0;
                $absolute_cost = 0;
                $seat = 0;
                $currency_id = $_POST['currency'];
                foreach ($_POST['package_id'] as $key => $value) {
                    $package[$int] = $package_array[$value];
                    $package[$int]['seat'] = $_POST['package_qty'][$key];
                    $package[$int]['occurence_id'] = $_POST['occurence_id'][$key];
                    $occ_row = $this->common->getSingleValue('event_occurence', 'occurence_id', $_POST['occurence_id'][$key]);
                    $package[$int]['date'] = $occ_row;
                    $currency_array = json_decode($package[$int]['currency_price'], 1);
                    if ($currency_array[$currency_id] > 0) {
                        $package[$int]['price'] = $currency_array[$currency_id]['price'];
                        $package[$int]['min_price'] = $currency_array[$currency_id]['min_price'];
                        $package[$int]['max_price'] = $currency_array[$currency_id]['max_price'];
                    }
                    $event_package[] = $package[$int];
                    $seat = $seat + $_POST['package_qty'][$key];
                    $int++;
                }



                $absolute_cost = $_POST['grand_total'];
                $this->smarty->assign("payee_capture", json_decode($info['payee_capture'], 1));
                $this->smarty->assign("attendees_capture", json_decode($info['attendees_capture'], 1));
                $this->smarty->assign("package", $event_package);
                $absolute_cost_incr = $this->encrypt->encode($absolute_cost);
                $this->smarty->assign("absolute_cost_incr", $absolute_cost_incr);
                $this->smarty->assign("absolute_cost", $absolute_cost);
                $surcharge_amount = $this->common->getSurcharge($info['merchant_id'], $absolute_cost);
                $franchise_id = 0;
                if ($info['pg_to_franchise'] == 1 && $info['franchise_id'] > 0) {
                    $franchise_id = $info['franchise_id'];
                }
                $pg_details = $this->common->getMerchantPG($info['merchant_id'], $franchise_id, $currency_id);
                if (count($pg_details) > 1) {
                    $absolute_cost = $absolute_cost;
                    $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                } else {
                    if ($pg_details[0]['pg_surcharge_enabled'] == 1) {
                        $this->smarty->assign("pg_surcharge_enabled", 1);
                    } else {
                        $absolute_cost = $absolute_cost + $surcharge_amount;
                    }
                    $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
                }

                $currency_icon = $this->common->getRowValue('icon', 'currency', 'code', $currency_id);

                $this->smarty->assign("currency_icon", $currency_icon);
                $this->smarty->assign("currency_id", $currency_id);
                $this->session->set('event_post', $_POST);
                $this->smarty->assign("post", $_POST);
                $this->smarty->assign("surcharge_amount", $surcharge_amount);
                $this->smarty->assign("absolute_cost_display", $absolute_cost);
                $this->smarty->assign("coupon_id", $_POST['coupon_id']);
                $this->smarty->assign("tax_amount", $_POST['service_tax']);
                $this->smarty->assign("discount_amount", $_POST['coupon_discount']);
                $this->smarty->assign("seat", $seat);
                if (empty($info)) {
                    SwipezLogger::error(__CLASS__, '[E207]Error while patron geting event details. event request id ' . $payment_request_id);
                    $this->setGenericError();
                } else {

                    $this->session->remove('paidMerchant_request');
                    if ($this->validateLogin('patron') == FALSE) {
                        $this->smarty->assign("isGuest", '1');
                    }

                    $this->session->set('confirm_payment', TRUE);

                    if (count($pg_details) > 1) {
                        $invoice = new PaymentWrapperController();
                        $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
                        $this->smarty->assign("paypal_id", $radio['paypal_id']);
                        $this->smarty->assign("radio", $radio['radio']);
                    }


                    if ($this->validateLogin('patron') == FALSE) {
                        $this->smarty->assign("isGuest", '1');
                    }
                    $this->view->js = array('invoice', 'event');
                    $post_link = '/patron/event/payment/' . $link;
                    $this->smarty->assign("post_link", $post_link);
                    $this->smarty->assign("info", $info);
                    $this->smarty->assign("payment_request_id", $link);
                    $this->smarty->assign("title", $info['template_name']);
                    $this->view->title = 'Confirm your payment';
                    $this->view->render('header/event_confirm');
                    $this->smarty->display(VIEW . 'patron/event/confirm.tpl');
                    $this->view->render('footer/invoice');
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->view($link);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function setPgdetails($pg_details)
    {
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
            if (!isset($credit_fee_id)) {
                $credit_fee_id = $net_fee_id;
                $credit_card_pg = $netbanking_pg;
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
                //$radio[] = array('name' => 'PAYTM_SUB', 'pg_id' => $paytm_sub_pg, 'fee_id' => $paytm_sub_fee_id);
            }
            $this->smarty->assign("radio", $radio);
        }
    }

    /**
     * Invoke payment gateway
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
                $this->setGenericError();
            }
            $payment_request_id = $this->encrypt->decode($_POST['payment_req']);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, NULL, 2);

            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E043-10]Error while getEventRequestDetails Event request id ' . $payment_request_id . 'Error: ' . $e->getMessage());
                $this->setGenericError();
            } else {
                $eventpath = "/patron/event/view/" . $_POST['payment_req'];
                $repath = "/patron/event/pay/" . $_POST['payment_req'];
                $this->session->set('return_payment_url', $repath);
                foreach (array_count_values($_POST['package_id']) as $key => $val) {
                    $occ_key = array_search($key, $_POST['package_id']);
                    $this->validatePackageSeat($key, $_POST['occurence_id'][$occ_key], $val, $eventpath);
                }
                require_once MODEL . 'merchant/CustomerModel.php';
                $customer = new CustomerModel($this->model);
                $_POST['name'] = trim($_POST['name']);
                $space_position = strpos($_POST['name'], ' ');
                if ($space_position > 0) {
                    $first_name = substr($_POST['name'], 0, $space_position);
                    $last_name = substr($_POST['name'], $space_position);
                } else {
                    $first_name = $_POST['name'];
                    $last_name = '';
                }
                $_POST['first_name'] = $first_name;
                $_POST['last_name'] = $last_name;
                require_once CONTROLLER . 'Paymentvalidator.php';
                $validator = new Paymentvalidator($this->model);
                $validator->validateBillingEventDetails();
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    $customer_id = $this->getCustomerId($info);
                } else {
                    $this->session->set('billingerrors', $hasErrors);
                    header('Location: /patron/event/view/' . $_POST['payment_req']);
                    exit;
                }
                if ($customer_id > 0) {
                    $patron_id = $customer_id;
                    $customer_id = $customer_id;
                    $this->session->set('userid', $patron_id);
                } else {

                    SwipezLogger::error(__CLASS__, '[E043-9]Error while getting patron id Error: ' . $e->getMessage());
                    $this->setGenericError();
                }
                $attendee_array = json_decode($info['attendees_capture'], 1);
                $_POST['attendee_customer_id'] = array();
                foreach ($_POST['package_id'] as $key => $val) {
                    if (empty($attendee_array)) {
                        $_POST['attendee_customer_id'][] = 0;
                    } else {
                        $update = 0;
                        if ($key == 1 && $_POST['same_aspayee'] == 1) {
                            $update = $customer_id;
                        }
                        $_POST['attendee_customer_id'][] = $this->getAttendeesCustomerId($info, $key, $update);
                    }
                }

                if (!isset($_POST['narrative'])) {
                    $_POST['narrative'] = '';
                }

                $fee_id = 0;
                if (isset($_POST['payment_mode'])) {
                    $fee_id = $this->encrypt->decode($_POST['payment_mode']);
                }

                if ($_POST['amount'] > 0) {
                    $increpted_amount = $this->encrypt->decode($_POST['increpted']);
                    $surcharge_amount = $this->common->getSurcharge($info['merchant_id'], $increpted_amount, $fee_id);
                    $increpted_amount = $increpted_amount + $surcharge_amount;
                    $amount = $_POST['amount'];
                } else {
                    $amount = 0;
                }

                if ($_POST['coupon_id'] > 0) {
                    $coupon_id = $_POST['coupon_id'];
                } else {
                    $coupon_id = 0;
                }

                $flexible_amount = 0;
                if (isset($_POST['flexible_amount'])) {
                    foreach ($_POST['flexible_amount'] as $f) {
                        $flexible_amount = $flexible_amount + $f;
                    }
                }

                $amount = $amount + $flexible_amount;
                if (isset($_POST['payment_mode'])) {
                    $pg_details = $this->common->getPaymentGatewayDetails($info['merchant_id'], $fee_id);
                } else {
                    $pg_details = $this->common->getPaymentGatewayDetails($info['merchant_id'], null, 0, $_POST['currency']);
                }
                if (empty($pg_details)) {
                    SwipezLogger::error(__CLASS__, '[E043]Error while getting merchant pg details Payment_request_id: ' . $payment_request_id . ' Merchant_id: ' . $info['merchant_user_id']);
                    $this->setGenericError();
                }
                $user_detail = $this->model->getPaymentDetails($payment_request_id, $patron_id);
                $user_detail['franchise_id'] = $info['franchise_id'];
                $pg_id = $pg_details['pg_id'];
                $amount = $amount + $surcharge_amount;
                if ($amount != $increpted_amount && $flexible_amount == 0) {
                    SwipezLogger::error(__CLASS__, '[E888]Error Amount changed by customer Actual Amount: ' . $increpted_amount . ' Entered Amount: ' . $amount);
                    $this->setGenericError();
                }

                if (isset($_POST['custom_column_id'])) {
                    foreach ($_POST['custom_column_id'] as $val) {
                        $custom_column_id[] = $val;
                        if (is_array($_POST[$val . '_value'])) {
                            $custom_column_value[] = implode(',', $_POST[$val . '_value']);
                        } else {
                            $custom_column_value[] = $_POST[$val . '_value'];
                        }
                    }
                }
                if ($user_detail['@message'] == 'success') {
                    $transaction_id = $this->model->intiatePaymentTransaction($payment_request_id, $customer_id, $patron_id, $user_detail['@merchant_id'], $amount, $_POST['tax'], $_POST['discount'], $pg_id, $pg_details['fee_detail_id'], $_POST['seat'], $_POST['occurence_id'], $_POST['package_id'], $_POST['attendee_customer_id'], $coupon_id, $_POST['narrative'], $custom_column_id, $custom_column_value, $info['franchise_id'], $info['vendor_id'], $_POST['currency']);
                    if ($coupon_id > 0) {
                        $this->common->genericupdate('payment_transaction', 'coupon_id', $coupon_id, 'pay_transaction_id', $transaction_id);
                    }
                    $this->session->set('transaction_type', 'event');
                    $this->session->set('transaction_id', $transaction_id);
                    $user_detail['@absolute_cost'] = $amount;
                } else {
                    SwipezLogger::error(__CLASS__, '[E154]Error while invoking payment gateway Error: Invalid link or link has already used for user id[' . $this->user_id . ']');
                    $this->setGenericError();
                }

                if (isset($_POST['name'])) {
                    if (isset($this->user_id)) {
                        $id = $this->user_id;
                    } else {
                        $id = $customer_id;
                    }
                    $this->common->SaveDatachange($id, 2, $transaction_id, -1, $customer_id, $first_name, $last_name, $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zipcode']);
                }

                if ($this->validateLogin('patron') == FALSE) {
                    $user_detail['@patron_first_name'] = $first_name;
                    $user_detail['@patron_last_name'] = $last_name;
                    $user_detail['@patron_email'] = $_POST['email'];
                    $user_detail['@patron_mobile_no'] = $_POST['mobile'];
                    $user_detail['@patron_address1'] = $_POST['address'];
                    $user_detail['@patron_city'] = $_POST['city'];
                    $user_detail['@patron_state'] = $_POST['state'];
                    $user_detail['@patron_zipcode'] = $_POST['zipcode'];
                }

                $address = $user_detail['@patron_address1'];
                $city = $user_detail['@patron_city'];
                $state = $user_detail['@patron_state'];
                $zipcode = $user_detail['@patron_zipcode'];
                $mobile = $user_detail['@patron_mobile_no'];
                $email = $user_detail['@patron_email'];
                $user_detail['vendor_id'] = $info['vendor_id'];
                $userpost['name'] = $_POST['name'];
                $userpost['email'] = $_POST['email'];
                $userpost['mobile'] = $_POST['mobile'];
                $userpost['address'] = $_POST['address'];
                $userpost['city'] = $_POST['city'];
                $userpost['state'] = $_POST['state'];
                $userpost['zipcode'] = $_POST['zipcode'];
                $event_post = $this->session->get('event_post');
                $post_array = array_merge($event_post, $userpost);
                $this->session->set('event_payment_post', $post_array);
                if ($user_detail['@absolute_cost'] == 0) {
                    $this->freeBooking($transaction_id, $customer_id);
                }
                $user_detail['currency'] = $_POST['currency'];
                SwipezLogger::info(__CLASS__, 'Payment transaction initiated. Transaction id: ' . $transaction_id . ', Request id: ' . $payment_request_id . ', Merchant name: ' . $user_detail['@company_name'] . ', Patron email: ' . $email . ', Mobile: ' . $mobile . ' Address: ' . $address . ' City: ' . $city . ' State: ' . $state . ' Zipcode: ' . $zipcode . ' , Patron id: ' . $id . ', Amount: ' . $user_detail['@absolute_cost']);

                $paymentWrapper = new PaymentWrapperController();

                $pg_details['repath'] = $repath;
                $_SESSION['transaction_type'] = 'event';
                $pg_details['fee_id'] = $pg_details['fee_detail_id'];
                $response = $paymentWrapper->paymentProcced($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, NULL, NULL, NULL, NULL, 'guest', $this->common, $this->view);
                $this->setError('Payment failed', $response, true);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E043]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function getCustomerId($info)
    {
        require_once MODEL . 'merchant/CustomerModel.php';
        $customer = new CustomerModel($this->model);
        $capture_array = json_decode($info['payee_capture'], 1);
        $customer_ids = $customer->getexistcustomerId($info['merchant_id'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile']);
        $column_ids = array();
        $column_values = array();
        foreach ($capture_array as $row) {
            if ($row['type'] != 'system') {
                $column_id = $row['name'];
                $value = $_POST[$column_id];
                $column_ids[] = $column_id;
                $column_values[] = $value;
                if (!empty($customer_ids)) {
                    $rows = $this->common->getListValue('customer_column_values', 'column_id', $column_id, 1, " and value='" . $value . "' and customer_id in (" . implode(',', $customer_ids) . ")");
                    $customer_ids = array();
                    foreach ($rows as $r) {
                        $customer_ids[] = $r['customer_id'];
                    }
                }
            }
        }
        if (empty($customer_ids)) {
            $customer_code = $customer->getCustomerCode($info['merchant_id']);
            $patron = $customer->saveCustomer($info['merchant_id'], $info['merchant_id'], $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], '', $_POST['city'], $_POST['state'], $_POST['zipcode'], $column_ids, $column_values);
            return $patron['customer_id'];
        } else {
            return $customer_ids[0];
        }
    }

    function getAttendeesCustomerId($info, $int, $update = 0)
    {
        require_once MODEL . 'merchant/CustomerModel.php';
        $customer = new CustomerModel($this->model);
        if (isset($_POST['attendee_name'][$int])) {
            $name = $_POST['attendee_name'][$int];
            $name = trim($name);
        } else {
            $name = 'NA';
        }
        $email = isset($_POST['attendee_email'][$int]) ? $_POST['attendee_email'][$int] : '';
        $mobile = isset($_POST['attendee_mobile'][$int]) ? $_POST['attendee_mobile'][$int] : '';
        $address = isset($_POST['attendee_address'][$int]) ? $_POST['attendee_address'][$int] : '';
        $city = isset($_POST['attendee_city'][$int]) ? $_POST['attendee_city'][$int] : '';
        $state = isset($_POST['attendee_state'][$int]) ? $_POST['attendee_state'][$int] : '';
        $zipcode = isset($_POST['attendee_zipcode'][$int]) ? $_POST['attendee_zipcode'][$int] : '';
        $space_position = strpos($name, ' ');
        if ($space_position > 0) {
            $first_name = substr($name, 0, $space_position);
            $last_name = substr($name, $space_position);
        } else {
            $first_name = $name;
            $last_name = '';
        }
        $capture_array = json_decode($info['attendees_capture'], 1);
        $customer_ids = $customer->getexistcustomerId($info['merchant_id'], $first_name, $last_name, $email, $mobile);
        $column_ids = array();
        $column_values = array();
        foreach ($capture_array as $row) {
            if ($row['type'] != 'system') {
                $column_id = $row['name'];
                $value = $_POST['attendee_' . $column_id][$int];
                $column_ids[] = $column_id;
                $column_values[] = $value;
                if (!empty($customer_ids)) {
                    $rows = $this->common->getListValue('customer_column_values', 'column_id', $column_id, 1, " and value='" . $value . "' and customer_id in (" . implode(',', $customer_ids) . ")");
                    $customer_ids = array();
                    foreach ($rows as $r) {
                        $customer_ids[] = $r['customer_id'];
                    }
                }
            }
        }
        if ($update != 0) {
            $customer_code = $this->common->getRowValue('customer_code', 'customer', 'customer_id', $update);
            $customer->updateCustomer($update, $update, $customer_code, $first_name, $last_name, $email, $mobile, $address, '', $city, $state, $zipcode, $column_ids, $column_values, array(), array());
            return $update;
        } else {
            if (empty($customer_ids)) {
                $customer_code = $customer->getCustomerCode($info['merchant_id']);
                $patron = $customer->saveCustomer($info['merchant_id'], $info['merchant_id'], $customer_code, $first_name, $last_name, $email, $mobile, $address, '', $city, $state, $zipcode, $column_ids, $column_values);
                return $patron['customer_id'];
            } else {
                return $customer_ids[0];
            }
        }
    }

    function freeBooking($transaction_id, $customer_id = null)
    {
        try {
            if ($customer_id != null) {
                $this->common->genericupdate('payment_transaction', 'payment_transaction_status', 1, 'pay_transaction_id', $transaction_id, $customer_id);
                $this->model->updatetransactionSeat($transaction_id);
                $list = $this->model->gettransactionCoupon($transaction_id);
                foreach ($list as $row) {
                    $this->model->updatetransactionCoupon($row['countc'], $row['coupon_code']);
                }
            }
            $details = $this->common->getReceipt($transaction_id, 'Online');
            $details['MerchantRefNo'] = $details['transaction_id'];
            if ($customer_id == null) {
                $details['pg_type'] = 'booking';
            } else {
                $details['pg_type'] = 'event';
            }

            $details['BillingName'] = $details['patron_name'];
            $details['BillingEmail'] = $details['patron_email'];
            $details['merchant_name'] = $details['company_name'];
            $details['TransactionID'] = 'Null';
            $details['date'] = date('Y-m-d H:i');
            $details['DateCreated'] = date('Y-m-d H:i');
            $details['Amount'] = $details['amount'];
            $details['payment_mode'] = '-';
            $this->session->set('response', $details);
            require_once CONTROLLER . 'Notification.php';
            $notification = new Notification();
            $notification->sendMailReceipt($details['transaction_id'], 1);
            $setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $details['merchant_id']);
            $details['sms_gateway_type'] = $setting['sms_gateway_type'];
            $sg_details = NULL;
            if ($setting['sms_gateway'] > 1) {
                $sg_details = $this->common->getSingleValue('sms_gateway', 'sg_id', $setting['sms_gateway']);
            }
            $merchant_mobile = $this->common->getRowValue('mobile_no', 'user', 'user_id', $details['merchant_user_id']);
            $patron_mobile = $this->common->getRowValue('mobile', 'customer', 'customer_id', $details['customer_id'], 0);
            $transaction_link = $this->encrypt->encode($details['transaction_id']);
            $long_url = $this->view->server_name . '/patron/transaction/receipt/' . $transaction_link;
            $shortUrl = $notification->saveShortUrl($long_url);
            $this->common->updateShortURL($details['transaction_id'], $shortUrl, 2);
            $details['transaction_short_url'] = $shortUrl;
            //$notification->sendSMSReceiptMerchant($details, $merchant_mobile, $sg_details);
            //$notification->sendSMSReceiptCustomer($details, $patron_mobile, $sg_details);
            header('Location:/patron/transaction/receipt/' . $transaction_link . '/success');
            exit();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E009]Error while Error while free booking payment transaction id [' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function asignSmarty($rows, $info, $payment_request_id)
    {
        foreach ($rows as $row) {
            if ($row['column_type'] == 'H') {
                $header[$row['column_position']]['column_position'] = $row['column_position'];
                $header[$row['column_position']]['column_name'] = $row['column_name'];
                $header[$row['column_position']]['value'] = $row['value'];
                $header[$row['column_position']]['invoice_id'] = $row['invoice_id'];
            }
        }

        $this->smarty->assign("header", $header);
        $this->smarty->assign("payment_request_id", $payment_request_id);
        $encode = $this->encrypt->encode($payment_request_id);
        $link = $this->app_url . '/patron/event/view/' . $encode;
        $this->smarty->assign("link", $link);
        $availableunits = $info['seats_available'] - $info['count_transaction'];
        $this->smarty->assign("available_units", $availableunits);
        $this->smarty->assign("seats_available", $info['seats_available']);
        $this->smarty->assign("is_valid", "YES");

        if ($info['seats_available'] > 0 && $availableunits < 1) {
            //  $this->smarty->assign("is_valid", "NO");
            //  $this->smarty->assign("invalid_message", "Currently there are no seats available for this event. For any queries, contact the merchant.");
        }

        $todate = new DateTime($info['event_to_date']);
        $todate = $todate->format('Y-m-d');
        if ($todate < date('Y-m-d') && $info['occurence'] > 0) {
            $this->smarty->assign("is_valid", "NO");
            $this->smarty->assign("invalid_message", "Event booking has been expired.");
        }

        if ($info['is_active'] != 1) {
            $this->smarty->assign("is_valid", "NO");
            $this->smarty->assign("invalid_message", "This event booking has been stopped by the organiser.");
        }

        $this->smarty->assign("image_path", $info['image_path']);
        $this->smarty->assign("banner_path", $info['banner_path']);
    }

    public function validatePackageSeat($package_id, $occur_id, $seat, $repath)
    {
        $total_seat = $this->model->getEventPackageDetails($package_id);
        if ($total_seat > 0) {
            $book_seat = $this->model->getEventPackageBookCount($package_id, $occur_id);
            $av = $total_seat - $book_seat;
            if ($av < $seat) {
                SwipezLogger::error(__CLASS__, '[E689]Error Event Package full package id: ' . $package_id);
                $this->setError('Error while event booking', 'Please check booking availability and try again. click <a href="' . $repath . '" >here</a>');
                header("Location:/error");
                exit;
            }
        }
    }

    /**
     * Display payment success page
     */
    public function Invoice($link)
    {
        try {
            $payment_request_id = $this->encrypt->decode($link);
            $rows = $this->common->getInvoiceBreakup($payment_request_id);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, NULL, 2);
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E008]Error while geting invoice details. for merchant [' . $merchant_id . '] and for payment request id [' . $particular['payment_request_id'] . ']');
                $this->setGenericError();
            }
            if ($info['event_type'] == 2) {
                $event_type = 'causes';
            } else {
                $event_type = 'event';
            }
            $package = $this->common->getListValue('event_package', 'event_request_id', $payment_request_id, 1);
            $this->smarty->assign("package", $package);
            $this->smarty->assign("info", $info);
            $this->smarty->assign("is_invoice", TRUE);
            $this->asignSmarty($rows, $info, $payment_request_id);

            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'patron/transaction/view_' . $event_type . '.tpl');
            $this->view->render('footer/invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E009]Error while payment request view initiate Error:for merchant [' . $merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
