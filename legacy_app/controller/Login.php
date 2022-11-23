<?php

class Login extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index($type = NULL)
    {
        try {
            if ($this->session->get('logged_in') == TRUE) {
                $usertype = ($this->session->get('user_status') != ACTIVE_PATRON) ? 'merchant' : 'patron';
                header("Location:/merchant/dashboard/home");
            }
            $this->view->isPatron = ($type == 'patron') ? TRUE : FALSE;
            $this->view->showCaptcha = $this->session->get('show_captcha');
            $this->view->canonical = $_GET['url'];
            $this->view->title = 'Sign in';
            $this->view->type = "login";
            $this->view->render('login/login');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E066]Error while login initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function forgot()
    {
        try {
            $user_id = $this->session->get('userid');
            $this->view->canonical = $_GET['url'];
            $this->view->title = 'Forgot password';
            $this->view->type = "forgot";
            $this->view->render('login/login');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E067]Error while forgot initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function success()
    {
        try {
            $user_id = $this->session->get('userid');
            $this->view->title = 'Password reset success';
            $this->view->render('header/guest');
            $this->view->render('login/message');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E068]Error while password reset success initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function forgotrequestsuccess()
    {
        try {
            $user_id = $this->session->get('userid');
            $this->view->title = 'Forgot password link sent';
            $this->view->render('header/guest');
            $this->view->render('login/forgotrequestsave');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E069]Error while forgot request success Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function forgotrequest()
    {
        try {
            if (isset($_POST['username'])) {
                $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if ($result) {
                    require CONTROLLER . 'Profilevalidator.php';
                    $validator = new Profilevalidator($this->model);
                    $validator->validateForgotPasswordRequest();
                    $hasErrors = $validator->fetchErrors();
                } else {
                    $hasErrors[0][0] = "Captcha";
                    $hasErrors[0][1] = "Invalid captcha please click on captcha box";
                }
                $is_mobile = 0;
                if ($hasErrors == false) {
                    $email = $_POST['username'];
                    if (preg_match("/^(\+[\d]{1,5}|0)?[1-9]\d{9}$/", $email)) {
                        $is_mobile = 1;
                        $mobile = substr($email, -10);
                        if (strlen($email) == 10) {
                            $email = '+91' . $email;
                        } else {
                            if (substr($email, 0, 1) == '+') {
                            } else {
                                $email = '+' . $email;
                            }
                        }
                    }
                    $res = $this->model->forgotPasswordRequest($email);
                    $result = $res['@string'];
                    if ($result != 'error') {
                        if ($is_mobile == 0) {
                            if ($result == null) {
                                $data = $this->common->getSingleValue('forgot_password', 'user_id', $this->user_id, 1);
                                $result = $data['last_update_date'] . $data['email_id'];
                            }
                            $data = $this->model->sendMail($result, $email);
                            header('Location:/login/forgotrequestsuccess');
                            die();
                        } else {
                            $message = $this->model->fetchMessage('p9');
                            $otp = rand(1000, 9999);
                            $this->common->genericupdate('forgot_password', 'otp', $otp, 'id', $res['@id']);
                            $message = str_replace('<OTP>', $otp, $message);
                            $this->model->sendSMS(null, $message, $mobile);
                            $this->view->id = $this->encrypt->encode($res['@id']);
                            $this->view->title = 'Forgot password link sent';
                            $this->view->render('header/guest');
                            $this->view->render('login/forgotpasswordotp');
                            $this->view->render('footer/footer');
                        }
                    } else {
                        $this->setError('Invalid email id', 'This email id or mobile is not registered with Swipez. Please click <a href="' . $this->view->server_name . '/merchant/register"> here </a> if you would like to start using Swipez with this email id');
                        header("Location:/error");
                    }
                } else {
                    $this->view->setError($hasErrors);
                    $this->forgot();
                }
            } else {
                header('Location:/error');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E070]Error while forgot email send Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function otpvalidate()
    {
        try {
            $id = $this->encrypt->decode($_POST['id']);
            $result = $this->common->getSingleValue('forgot_password', 'id', $id, 1);
            if ($result['otp'] == $_POST['otp']) {
                $this->common->genericupdate('forgot_password', 'is_active', 0, 'id', $id);
                $encoded = $this->encrypt->encode($result['last_update_date'] . $result['email_id']);
                header('Location:' . '/login/forgotpassword/' . $encoded);
                die();
            } else {
                $this->view->id = $_POST['id'];
                $this->view->error = 'Error: Invalid OTP';
                $this->view->render('header/guest');
                $this->view->render('login/forgotpasswordotp');
                $this->view->render('footer/footer');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E072]Error while reset password Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function forgotpassword($link)
    {
        try {
            $result = $this->model->isValidforgotpasswordlink($link);
            if ($result != 'failed') {
                $this->session->remove('show_captcha');
                $this->session->remove('is_disable');
                $this->session->remove('disable_email');
                $this->view->link = $link;
                $this->view->email = $result;
                $this->view->title = 'Forgot password reset';
                $this->view->render('header/guest');
                $this->view->render('login/forgotpassword');
                $this->view->render('footer/footer');
            } else {
                $this->setError('Link is not valid anymore', 'This link is not valid anymore as it was already used once OR has expired.');
                header("Location:/error");
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E071]Error while listing patron transaction Error:  ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function verifyvendor($link = '')
    {
        try {
            $string = $this->encrypt->decode($link);
            $merchant_id = substr($string, 0, 10);
            $user_id = substr($string, 10);
            $row = $this->common->getSingleValue('user', 'user_id', $user_id);
            if ($row['user_status'] == 11) {
                $this->session->remove('show_captcha');
                $this->session->remove('is_disable');
                $this->session->remove('disable_email');
                $this->view->email = $this->encrypt->encode($user_id);
                $this->view->user_name = $row['mobile_no'];
                $this->view->link = $link;
                $this->view->type = 'vendor';
                $this->view->service_id = $this->encrypt->encode(2);
                $this->view->verify = 1;
                $this->view->title = 'Set you password';
                $this->view->render('login/resetpassword');
            } else {
                $this->setError('Link is not valid anymore', 'This link is not valid anymore as it was already used once OR has expired.');
                header("Location:/error");
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E025]Error while merchant verify email initiate Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            header("Location:/error");
        }
    }

    function resetpassword($link)
    {
        try {
            require CONTROLLER . 'Profilevalidator.php';
            $validator = new Profilevalidator($this->model);
            $validator->validateForgotResetPassword();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $user_id = $this->encrypt->decode($_POST['email']);
                if (strlen($user_id) == 10 && substr($user_id, 0, 1) == 'U') {
                    $result = $this->model->resetPassword($password, $user_id);
                    if (isset($_POST['verified'])) {
                        $user_id = $this->encrypt->decode($_POST['email']);
                        $this->common->genericupdate('user', 'user_status', '15', 'user_id', $user_id);
                        $email = $this->common->getRowValue('email_id', "user", "user_id", $user_id, 0);
                        $token = $this->model->saveLoginToken($user_id, $email);
                        header("Location:/login/token/" . $token . "/" . $_POST['service_id'], 301);
                        die();
                    }
                    header('Location: /login/success');
                } else {
                    $this->setGenericError();
                }
            } else {
                $this->view->setError($hasErrors);
                if ($_POST['type'] == 'vendor') {
                    $this->verifyvendor($link);
                } else {
                    if (isset($_POST['verified'])) {
                        $this->verifycustomer($link);
                    } else {
                        $this->forgotpassword($link);
                    }
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E072]Error while reset password Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function verifycustomer($link, $service_id = 0)
    {
        try {
            $url = $this->encrypt->decode($link);
            $user_id = substr($url, 0, 10);
            $credate = substr($url, 10, 19);
            $data = $this->common->getSingleValue("user", "user_id", $user_id, 0, " and created_date='" . $credate . "'");
            if (empty($data)) {
                $this->setError('Link is not valid anymore', 'This link is not valid anymore as it was already used once OR has expired.');
                header("Location:/error");
            } else {
                $password = $this->common->getRowValue('password', "user", "user_id", $user_id, 0);
                if ($password == '') {
                    $this->session->remove('show_captcha');
                    $this->session->remove('is_disable');
                    $this->session->remove('disable_email');
                    $this->view->email = $this->encrypt->encode($user_id);
                    $this->view->link = $link;
                    $this->view->verify = 1;
                    $this->view->title = 'Set you password';
                    $this->view->render('login/resetpassword');
                } else {
                    $this->common->genericupdate('user', 'user_status', '15', 'user_id', $user_id);
                    $token = $this->model->saveLoginToken($data['user_id'], $data['email_id']);
                    $_POST['username'] = $data['email_id'];
                    $_POST['password'] = 'test123';
                    $_POST['login_token'] = $token;
                    $this->failed(null, $service_id);
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E072]Error while reset password Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function verification()
    {
        try {
            $user_id = $this->session->get('unverified_userid');
            if (isset($user_id)) {
                $email = $this->session->get('unverified_email');
                $status = $this->session->get('unverified_status');
                require MODEL . 'patron/RegisterModel.php';
                $reg = new RegisterModel();
                $type = ($status == 11 || $status == 19) ? 'merchant' : 'patron';
                $str = $reg->getUserTimeStamp($user_id);
                $reg->sendMail($str, $email, $type);
                header("Location: /" . $type . "/register/success");
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E072]Error while reset password Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function failed($returnurl = NULL, $service_id = 0)
    {
        try {
            header('Location:/login');
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E073]Error while login check Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function keepalive()
    {
        Session::put('last_action', time());
    }

    public function validateotp($value)
    {
        $otp = $this->session->get('memberOTP');
        $otp = $this->encrypt->decode($otp);
        if ($otp == $value) {
            echo 1;
            $this->session->remove('memberOTP');
            $this->session->set('valid_member', TRUE);
        } else {
            echo 0;
        }
    }

    public function logincheck()
    {
        $this->session->remove('show_captcha');
        $valid_captcha = $this->validateV3Captcha();
        if ($valid_captcha == true) {
            $response = $this->failed('newajax');
            if ($response != 'success') {
                $response = $response['error'][0]['value'];
            } else {
                header('Location:/merchant/dashboard/home');
                die();
            }
        } else {
            $response = 'Invalid captcha please reload page and try again';
        }
        $this->session->set('invalidlogin_error', $response);
        //header('Location:/login');
    }

    public function validateV3Captcha()
    {
        if (isset($_POST['recaptcha_response']) && !empty($_POST['recaptcha_response'])) {
            //your site secret key
            $secret = env('V3_CAPTCHA_SECRET');
            //get verify response data
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret) . '&response=' . urlencode($_POST['recaptcha_response']);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);
            header('Content-type: application/json');
            if ($responseKeys["success"]) {
                if ($responseKeys["score"] >= 0.05) {
                    return true;
                }
            }
        }
        return false;
    }

    public function googlelogin($aaa = null)
    {
        SwipezLogger::debug(__CLASS__, 'initiate :' . $aaa);

        if (!empty($_GET)) {
            SwipezLogger::debug(__CLASS__, 'GET :' . json_encode($_GET));
        }
    }
}
