<?php

class Group extends Controller {

    function __construct() {
        parent::__construct();
    }

    function login() {
        try {
            if ($this->session->get('logged_in') == TRUE) {
                $usertype = ($this->session->get('user_status') != ACTIVE_PATRON) ? 'merchant' : 'patron';
                header("Location:/" . $usertype . "/dashboard");
            }

            require_once MODEL . 'LoginModel.php';
            $login_model = new LoginModel();
            $group_list[] = array('group_id' => 'G000000046', 'merchant_name' => 'Malaka Spice');
            $group_list[] = array('group_id' => 'G000000047', 'merchant_name' => 'Malaka Street');
            $group_list[] = array('group_id' => 'G000000048', 'merchant_name' => 'Malaka Spice, Baner');
            $group_list[] = array('group_id' => 'G000000356', 'merchant_name' => 'Tvum');

            $this->view->group_list = $group_list;
            $this->view->showCaptcha = $this->session->get('show_captcha');
            $this->view->canonical = $_GET['url'];
            $this->view->title = 'Group Sign in';
            $this->view->type = "login";
            $this->view->render('login/group_login');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E066]Error while login initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function success() {
        try {
            $user_id = $this->session->get('userid');
            $this->view->title = 'Password reset success';
            $this->HTMLValidationPrinter();
            $this->view->render('header/guest');
            $this->view->render('login/message');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E068]Error while password reset success initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function failed($returnurl = NULL) {
        try {
            if (empty($_POST)) {
                header('Location:/group/login');
            }
            if (isset($returnurl)) {
                $this->view->returnurl = $returnurl;
            }
            require CONTROLLER . 'Profilevalidator.php';
            $validator = new Profilevalidator($login_model);

            if (isset($_POST['g-recaptcha-response'])) {
                $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if ($result) {
                    $validator->ValidateLoginEmail();
                    $hasErrors = $validator->fetchErrors();
                } else {
                    $hasErrors[0][0] = "Captcha";
                    $hasErrors[0][1] = "Invalid captcha please click on captcha box";
                    $this->view->setError($hasErrors);
                    $this->login();
                    return;
                }
            }

            if ($hasErrors == FALSE) {
                require_once MODEL . 'LoginModel.php';
                $login_model = new LoginModel();

                $data = $login_model->queryGroupLoginInfo($_POST['username'], $_POST['password'], $_POST['group_id']);
                if (empty($data)) {
                    $hasErrors = "There was an error with your E-Mail/Password combination. Please try again.";
                } else {
                    if ($data['isValid'] == 0) {
                        SwipezLogger::info("Login", $username . " login failed login attempt " . $data['loginattempt']);
                        if ($data['loginattempt'] > 4) {
                            $this->session->set('show_captcha', TRUE);
                        }
                        if ($data['loginattempt'] == 10) {
                            $this->session->set('forgot_email', TRUE);
                            $this->session->set('is_disable', TRUE);
                            $this->session->set('disable_email', $username);
                            $hasErrors = 'Forgot password request has been sent to your registered email address. Please use the link within the forgot password email to reset your password. Till then your id has been disabled for security reasons. It will be enabled once you have reset your password. If you have any queries related to this please use the <a href="' . $this->view->server_name . '/helpdesk" class="iframe"> contact us</a> option to get in touch.';
                        } else if ($data['status'] == 18) {
                            $hasErrors = 'Your id has been disabled. It will be enabled once you have reset your password. If you have any queries related to this please use the <a href="' . $this->view->server_name . '/helpdesk" class="iframe"> contact us</a> option to get in touch.';
                        } else {
                            if ($data['status'] == 1 || $data['status'] == 11) {
                                $hasErrors = 'Your email verification is pending. Please verify your email and try again. If you have any queries, please use the <a href="' . $this->view->server_name . '/helpdesk" class="iframe"> contact us</a> option to get in touch.';
                            } else {
                                $hasErrors = "There was an error with your E-Mail/Password combination. Please try again.";
                            }
                        }
                    } else {
                        SwipezLogger::info("Login", "User " . $username . " loggedin");
                        $this->session->remove('show_captcha');
                        $this->session->remove('is_disable');
                        $this->session->remove('disable_email');
                        $this->session->set('login_data', $data);
                    }
                }
            } else {
                SwipezLogger::info("Login", $_POST['username'] . " wrong data input login failed Password " . $_POST['password']);
            }

            if ($hasErrors == false) {
                $data = $data;
                $this->session->remove('login_data');
                $this->session->set('user_status', $data['status']);
                $this->session->set('user_name', $data['name']);
                $this->session->set('display_name', $data['firstName']);
                $this->session->set('email_id', $data['email_id']);
                if ($data['merchant_type'] > 0) {
                    if ($data['status'] == 20) {
                        $this->session->set('group_type', 2);
                        $this->session->set('roles', explode(',', $data['roles']));
                        $this->session->set('sub_user_id', $data['sub_user_id']);
                    } else {
                        $this->session->set('system_user_id', $data['user_id']);
                    }
                    $this->session->set('merchant_type', $data['merchant_type']);
                    $this->session->set('merchant_id', $data['merchant_id']);
                    $this->session->set('merchant_display_url', $data['display_url']);
                    $this->session->set('merchant_domain', $data['merchant_domain']);
                    $this->session->set('bulk_upload_limit', $data['bulk_upload_limit']);
                    $this->session->set('merchant_status', $data['merchant_status']);
                    $this->session->set('company_name', $data['company_name']);
                } else {
                    $this->session->set('patron_has_payreq', $data['patron_has_payreq']);
                }
                $this->session->set('logged_in', TRUE);
                $this->session->set('userid', $data['user_id']);
                if ($this->session->get('user_status') == ACTIVE_PATRON) {
                    if (isset($returnurl)) {
                        if (substr($returnurl, -4) == '|onl') {
                            $returnurl = str_replace('|onl', '', $returnurl);
                            header('Location: /patron/paymentrequest/pay/' . $returnurl);
                        } else if (substr($returnurl, -5) == '|eonl') {
                            $returnurl = str_replace('|eonl', '', $returnurl);
                            header('Location: /patron/event/pay/' . $returnurl);
                        } else {
SwipezLogger::error(__CLASS__, '[E073-o]Error while login check Error: ');
                            $this->setGenericError();
                        }
                    } else if ($data['patron_has_payreq'] == 0) {
                        header('Location:/patron/profile/suggest');
                    } else {
                        header('Location:/patron/dashboard');
                    }
                } else {
                    header('Location:/merchant/dashboard');
                }
            } else {

                $forgot_email = $this->session->get('forgot_email');
                if ($forgot_email == TRUE) {
                    $this->session->remove('forgot_email');
                    $res = $login_model->forgotPasswordRequest($_POST['username'], $_POST['group_id']);
                    $result = $res['@string'];
                    if ($result != 'error') {
                        $data = $login_model->sendMail($result, $_POST['username']);
                    }
                }

                $Errors['username'][0] = 'Login';
                $Errors['username'][1] = $hasErrors;

                $this->session->set('isValidPost', TRUE);
                $this->view->setError($Errors);
                $this->login();
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E073]Error while login check Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function forgotrequest() {
        try {
            if (isset($_POST['email'])) {

                require_once MODEL . 'LoginModel.php';
                $login_model = new LoginModel();

                $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if ($result) {
                    require CONTROLLER . 'Profilevalidator.php';
                    $validator = new Profilevalidator($login_model);
                    $validator->validateForgotPasswordRequest();
                    $hasErrors = $validator->fetchErrors();
                } else {
                    $hasErrors[0][0] = "Captcha";
                    $hasErrors[0][1] = "Invalid captcha please click on captcha box";
                }
                if ($hasErrors == false) {
                    $res = $login_model->forgotPasswordRequest($_POST['email'], $_POST['group_id']);
                    $result = $res['@string'];
                    if ($result != 'error') {
                        $login_model->sendMail($result, $_POST['email']);
                        header('Location:/login/forgotrequestsuccess');
                    } else {
                        $this->setError('Invalid email id', 'This email id is not registered with Swipez. Please click <a href="' . $this->view->server_name . '/merchant/register"> here </a> if you would like to start using Swipez with this email id');
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

}
