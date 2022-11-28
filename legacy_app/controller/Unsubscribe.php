<?php

class Unsubscribe extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function select($link = null)
    {
        try {
            $peyment_request_id = $this->encrypt->decode($link);
            if (strlen($peyment_request_id) == 10) {
                $detail = $this->common->getPaymentRequestDetails($peyment_request_id, 'customer');
                $this->smarty->assign('email', $detail['customer_email']);
                $this->smarty->assign('mobile', $detail['customer_mobile']);
                $this->smarty->assign('customerlink', $this->encrypt->encode($detail['customer_id']));
                $this->smarty->assign('link', $link);
                $this->smarty->assign('mobilelink', $this->encrypt->encode($detail['customer_mobile']));
                $this->view->title = 'Unsubscribe';
                $this->view->js[] = 'unsubscribe';
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'unsubscribe/select.tpl');
                $this->view->render('footer/footer');
            } else {
                SwipezLogger::info(__CLASS__, '[U00189] Invalid unsubscribe link ' . $link);
                $this->setInvalidLinkError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[U001]Error while select initiate' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function sendotp($link)
    {
        try {
            $sentotp = $this->session->get($link);
            $sentotp = isset($sentotp) ? $sentotp : 0;
            $this->session->set($link, $sentotp + 1);
            if ($sentotp < 3) {
                $customer_id = $this->encrypt->decode($link);
                $detail = $this->common->querysingle("select mobile from customer where customer_id=" . $customer_id);
                $mobile = $detail['mobile'];
                if ($mobile[0] == '0') {
                    $mobile = substr($mobile, 1);
                }
                if (strlen($mobile) == 10) {
                    $otp = rand(1000, 9999);
                    $this->model->saveOtp($mobile, $otp);
                    $message = "Your One-Time Password (OTP) is " . $otp . " to login into Swipez";
                    $res = $this->model->sendSMS(null, $message, $mobile, '', 1);
                    echo 1;
                }
            } else {
                echo 'OTP limit has exceeded';
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[U002]Error while send OTP' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function verifyotp($link, $otp)
    {
        try {
            $mobile = $this->encrypt->decode($link);
            if ($mobile[0] == '0') {
                $mobile = substr($mobile, 1);
            }
            $detail = $this->model->verifyOtp($mobile, $otp);
            echo $detail;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[U004]Error while verifyotp' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function saveunsubscribe($link)
    {
        try {
            if (isset($_POST)) {
                if (isset($_POST['g-recaptcha-response'])) {
                    $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if ($result) {
                    } else {
                        $hasErrors = "Invalid captcha please click on captcha box";
                        $this->smarty->assign("haserrors", $hasErrors);
                    }
                }
                if ($hasErrors == FALSE) {
                    $peyment_request_id = $this->encrypt->decode($link);
                    $detail = $this->common->getPaymentRequestDetails($peyment_request_id, 'customer');

                    $email = $detail['customer_email'];
                    $merchant_id = $detail['merchant_id'];
                    $mobile = ($_POST['verifymobile'] == 1) ? $detail['customer_mobile'] : '';
                    $id = $this->model->existUnsubscribe($peyment_request_id, $merchant_id);
                    if ($id > 0) {
                        $this->model->updateUnsubscribe($id, $email, $mobile, $peyment_request_id, $merchant_id, $_POST['type']);
                    } else {
                        $this->model->saveUnsubscribe($email, $mobile, $peyment_request_id, $merchant_id, $_POST['type']);
                    }

                    $this->view->render('header/guest');
                    $this->smarty->display(VIEW . 'unsubscribe/success.tpl');
                    $this->view->render('footer/footer');
                } else {
                    $this->select($link);
                }
            } else {
                header("Location:/unsubscribe/" . $link);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[U003]Error while save unsubscribe' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
