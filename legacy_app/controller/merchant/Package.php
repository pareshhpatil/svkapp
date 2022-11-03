<?php

use App\Http\Controllers\PaymentWrapperController;

/**
 * Landing controller class to handle merchant landing pages
 */
class Package extends Controller {

    private $support_user_id = 'U000002349';
    private $support_merchant_id = 'M000000151';

    function __construct() {
        parent::__construct();
        if ($this->env != 'PROD') {
            $this->support_merchant_id = 'M000000001';
            $this->support_user_id = 'U000000001';
        }
        $this->view->selectedMenu = 'companyprofileview';
    }

    public function custom($link = NULL) {
        try {
            $inv_unit = $this->session->get('custom_inv_count');
            $booking_unit = $this->session->get('custom_booking_count');
            $this->view->inv_count = ($inv_unit > 0) ? $inv_unit : 0;
            $this->view->booking_count = ($booking_unit > 0) ? $booking_unit : 0;
            $this->smarty->display(VIEW . 'merchant/package/custom.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/custompackage');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E046]Error while displaying custom package view' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function confirm($link = NULL) {
        try {
            $this->view->show_hubspot = 1;
            $this->view->js = array('invoice');
            if (isset($_POST['eventsVal'])) {
                if (!isset($this->user_id)) {
                    $this->user_id = 'Merchant';
                }
                $info = $this->calculateCustom();
            } else {
                if ($link == null) {
                    header('location:/pricing');
                    die();
                }
                $plan_id = $this->encrypt->decode($link);
                if (!is_numeric($plan_id)) {
                    $plan_id = $this->encryptBackup($link);
                    $link = $this->encrypt->encode($plan_id);
                }
                $info = $this->model->getPackageDetail($plan_id);
            }
            if (empty($info)) {
SwipezLogger::error(__CLASS__, '[E207]Error while geting plan details. plan id ' . $plan_id . ' Link: ' . $link);
                $this->setGenericError();
            }
            $is_coupon = $this->common->isMerchantCoupon($this->support_user_id);
            $errors = $this->session->get('billingerrors');
            $this->session->remove('billingerrors');
            $this->smarty->assign("billingerrors", $errors);
            $this->smarty->assign("is_coupon", $is_coupon);
            $this->smarty->assign("support_user_id", $this->support_user_id);
            $pg_details = $this->common->getMerchantPG($this->support_merchant_id);
            if (count($pg_details) > 1) {
                $invoice = new PaymentWrapperController();
                $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
                $this->smarty->assign("paypal_id", $radio['paypal_id']);
                $this->smarty->assign("radio", $radio['radio']);
            }
            $user_id = $this->session->get('userid');
            $merchant_id = $this->session->get('merchant_id');
            if (isset($user_id)) {
                $customerdetails = $this->model->getUserDetail($user_id,$merchant_id);
                $this->smarty->assign("customerdetails", $customerdetails);
            } else {
                header('location:/package/'.$link);
            }
            $this->view->title = 'Confirm package';
            $this->smarty->assign("package_type", $this->session->get('package_type'));
            $this->session->set('confirm_payment', TRUE);
            $this->smarty->assign("info", $info);
            $this->smarty->assign("plan_id", $link);
            $this->view->render('header/packageheader');
            $this->smarty->display(VIEW . 'merchant/package/confirm.tpl');
            $this->view->render('footer/invoice');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function calculateCustom() {
        try {
            $inv_unit = $_POST['invoicesVal'];
            if ($inv_unit == 0) {
                $inv_cost = 0;
            } else if ($inv_unit < 1001) {
                $inv_cost = 2360;
            } else if ($inv_unit > 1000 && $inv_unit < 2501) {
                $inv_cost = 3540;
            } else if ($inv_unit > 2500 && $inv_unit < 5001) {
                $inv_cost = 4130;
            } else if ($inv_unit > 5000 && $inv_unit < 7001) {
                $inv_cost = 4720;
            } else if ($inv_unit > 7000 && $inv_unit < 8501) {
                $inv_cost = 5310;
            } else if ($inv_unit > 8500 && $inv_unit < 10001) {
                $inv_cost = 5310;
            } else {
                $inv_cost = 5999;
            }
            $event_unit = $_POST['eventsVal'];
            if ($event_unit == 0) {
                $event_cost = 0;
            } else if ($event_unit < 1001) {
                $event_cost = 2360;
            } else if ($event_unit > 1000 && $event_unit < 2501) {
                $event_cost = 3540;
            } else if ($event_unit > 2500 && $event_unit < 5001) {
                $event_cost = 4130;
            } else if ($event_unit > 5000 && $event_unit < 7001) {
                $event_cost = 4720;
            } else if ($event_unit > 7000 && $event_unit < 8501) {
                $event_cost = 5310;
            } else if ($event_unit > 8500 && $event_unit < 10001) {
                $event_cost = 5310;
            } else {
                $event_cost = 5999;
            }

            $total_cost = $inv_cost + $event_cost;
            $free_sms = round($total_cost * 0.04167);
            $description = "";
            if ($inv_unit > 0) {
                $description = $inv_unit . ' Invoices and ';
            }
            if ($event_unit > 0) {
                $description .= $event_unit . ' Event bookings and ';
            }
            $description .= $free_sms . ' Free SMS';
            $this->session->set('custom_inv_count', $inv_unit);
            $this->session->set('custom_booking_count', $event_unit);
            $total_cost = round($total_cost / 1.18);
            $package_id = $this->model->saveCustomPackage($description, $total_cost, $inv_cost, $event_cost, $inv_unit, $event_unit, $free_sms, $this->user_id);
            $response['package_name'] = 'Custom Package';
            $response['package_description'] = $description;
            $response['package_cost'] = $total_cost;
            $response['custom_package_id'] = $package_id;
            $response['package_id'] = 0;
            return $response;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function payment($link = null, $fee_id = null) {
        try {
            if ($link == 'paypal') {
                $data = file_get_contents('php://input');
                $dataaarray = json_decode($data, 1);
                foreach ($dataaarray as $row) {
                    $_POST[$row['name']] = $row['value'];
                }
                $_POST['payment_mode'] = $fee_id;
            }
            $package_id = $_POST['package_id'];
            $custom_package_id = ($_POST['custom_package_id'] > 0) ? $_POST['custom_package_id'] : 0;
            if ($package_id > 0) {
                $info = $this->model->getPackageDetail($package_id);
            } else {
                $this->session->remove('custom_inv_count');
                $this->session->remove('custom_booking_count');
                $info = $this->common->getSingleValue('custom_package', 'package_id', $custom_package_id);
                $info['package_name'] = 'Custom Package ' . $info['package_description'];
            }
            if (empty($info)) {
SwipezLogger::error(__CLASS__, '[E043-10]Error while geting plan details. package id ' . $package_id);
                $this->setGenericError();
            }
            $amount = $info['package_cost'];
            $absolute_cost = $amount;
            if (isset($_POST['smspack'])) {
                $pack = ($_POST['smspack'] > 1) ? $_POST['smspack'] : 1;
                $amount = $info['package_cost'] * $pack;
                $info['package_name'] = $info['package_name'] . ' ' . $pack;
                $absolute_cost = $amount;
            } else {
                $discount = 0;
                $coupon_id = 0;
                if ($_POST['coupon_id'] > 0) {
                    $coupon_details = $this->common->getCouponDetails($_POST['coupon_id']);
                    if ($coupon_details ['type'] == 1) {
                        $discount = $coupon_details['fixed_amount'];
                    } else {
                        $discount = $coupon_details ['percent'] * $amount / 100;
                    }
                    $absolute_cost = $amount - $discount;
                    $coupon_id = $_POST['coupon_id'];
                }
            }
            $tax = $absolute_cost * 18 / 100;
            $space_position = strpos($_POST['name'], ' ');
            $first_name = substr($_POST['name'], 0, $space_position);
            $last_name = substr($_POST['name'], $space_position);
            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            $validator->validateBillingDetails();
            $hasErrors = $validator->fetchErrors();

            if ($hasErrors == FALSE) {
                if (isset($_POST['company_name'])) {
                    require_once CONTROLLER . 'merchant/Register.php';
                    $register = new Register();
                    $result = $register->saved('package');
                    if ($result['status'] == 1) {
                        $this->user_id = $result['user_id'];
                        $this->merchant_id = $result['merchant_id'];
                    } else {
                        $hasErrors = $result['error'];
                    }
                }
            }

            $repath = '/merchant/package/confirm/' . $this->encrypt->encode($_POST['package_id']);
            if ($hasErrors == false) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $mobile = $_POST['mobile'];
                $city = $_POST['city'];
                $address = $_POST['address'];
                $state = $_POST['state'];
                $zipcode = $_POST['zipcode'];
            } else {
                $this->session->set('billingerrors', $hasErrors);
                header('Location: ' . $repath);
                exit;
            }
            $this->session->set('return_payment_url', $repath);
            if (isset($_POST['payment_mode'])) {
                $fee_id = $this->encrypt->decode($_POST['payment_mode']);
                $pg_details = $this->common->getPaymentGatewayDetails($this->support_merchant_id, $fee_id);
            } else {
                $pg_details = $this->common->getPaymentGatewayDetails($this->support_merchant_id);
            }
            if (empty($pg_details)) {
                SwipezLogger ::error(__CLASS__, '[E1008]Error while getting merchant pg details package_id: ' . $package_id);
                $this->setGenericError();
            }
            $absolute_cost = $absolute_cost + $tax;
            $absolute_cost = round($absolute_cost, 0);
            $user_detail['@absolute_cost'] = $absolute_cost;
            $user_detail['@company_merchant_id'] = 'M000000151';
            $user_detail['@company_name'] = 'Swipez';
            $user_detail['@narrative'] = $info['package_name'];
            if (isset($this->user_id)) {
                $user_id = $this->user_id;
                $merchant_id = $this->merchant_id;
            } else {
                $user_det = $this->common->querysingle("select user_id from user where email_id='" . $email . "' and user_status>12");
                if (isset($user_det['user_id'])) {
                    $user_id = $user_det['user_id'];
                    $merchant_id = $this->common->getRowValue('merchant_id', 'merchant', 'user_id', $user_id);
                } else {
                    $this->setError('Merchant not registered', 'Merchant not registered. Please <a href="/merchant/register">click here</a> to register with ' . $email . ' email id and try again');
                    header("Location:/error");
                    exit;
                }
            }
            $this->session->set('userid', $user_id);
            $this->session->set('merchant_id', $merchant_id);
            $transaction_id = $this->model->intiateFeeTransaction($package_id, $custom_package_id, $merchant_id, $amount, $absolute_cost, $tax, $coupon_id, $discount, $pg_details['pg_id'], $pg_details['pg_type'], $info['package_name'], $user_id);
            if ($custom_package_id > 0) {
                $this->model->updateCustomPackage($transaction_id, $custom_package_id, $merchant_id);
            }
            $this->model->savePackageTransactionDetails($transaction_id, $name, $email, $mobile, $address, $city, $state, $zipcode);
            $this->session->set('transaction_type', 'package');
            $this->session->set('transaction_id', $transaction_id);

            SwipezLogger::debug(__CLASS__, 'Redirecting merchant for payment : Transaction id: ' . $transaction_id . ',  Merchant name: ' . $name . ', email: ' . $email . ', Amount: ' . $amount);

            $paymentWrapper = new PaymentWrapperController();
            $pg_details['repath'] = $repath;
            $paymentWrapper->paymentProcced($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address, 'guest', $this->common, $this->view);
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function success($link=null) {
        try {
            $transaction_id=$this->encrypt->decode($link);
            $transaction = $this->common->getSingleValue('package_transaction', 'package_transaction_id', $transaction_id);
            $detail = $this->common->getSingleValue('package_transaction_details', 'package_transaction_id', $transaction_id);
            $this->smarty->assign("info", $transaction);
            $this->smarty->assign("detail", $detail);
            $this->view->title = 'Payment success';
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/package/success.tpl');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E046]Error while payment success initiate Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
