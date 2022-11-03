<?php

/**
 * Register controller to mediate patron registration process
 * 
 */
class Register extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->disable_talk = 1;
    }

    /**
     * Display patrons personal form
     */
    function index($returnUrl = NULL) {
        try {
            $this->view->title = 'Patron sign up';
            $this->view->canonical = 'patron/register';
            $this->view->description = 'Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs';
            if (isset($returnUrl)) {
                $this->view->returnUrl = $returnUrl;
            }
            $this->view->render('patron/register/register');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E052]Error while patron personal register initiate Error: ' . $e->getMessage());
        }
    }

    /**
     * Save patrons personal information
     */
    function saved($returnUrl = NULL) {
        try {
            //TODO Check if the action is POST and only then execute the rest of the code below. Maybe this 
            //should be handled in a central location someplace.
            $postUrl = NULL;
            $reurl = $returnUrl;
            $payment_request_id = '';
            if (isset($_POST['returnurl'])) {
                $returnUrl = $_POST['returnurl'];
                $postUrl = $_POST['returnurl'];
            }
            if (empty($_POST)) {
                header("Location:/patron/register");
            }
            if ($reurl == NULL) {
                $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if ($result) {
                    
                } else {

                    $captcha[0][0] = "Captcha";
                    $captcha[0][1] = "Invalid captcha please click on captcha box";
                }
            } else {
                $payment_request_id = $this->encrypt->decode($returnUrl);
                if (strlen($payment_request_id) == 10) {
                    if ($this->session->get('paidMerchant_request') != TRUE) {
                        header('Location: /patron/paymentrequest/view/' . $returnUrl);
                    }
                } else {
                    $payment_request_id = '';
                }
            }
            $space_position = strpos($_POST['name'], ' ');
            if ($space_position > 0) {
                $first_name = substr($_POST['name'], 0, $space_position);
                $last_name = substr($_POST['name'], $space_position);
            } else {
                $first_name = $_POST['name'];
                $last_name = '';
            }

            $_POST['f_name'] = $first_name;
            $_POST['l_name'] = $last_name;

            require CONTROLLER . 'patron/Registervalidator.php';
            $validator = new Registervalidator($this->model);
            $validator->validatePatronRegister();
            $hasErrors = $validator->fetchErrors();
            if (!empty($captcha)) {
                if (!empty($hasErrors)) {
                    $hasErrors = array_merge($hasErrors, $captcha);
                } else {
                    $hasErrors = $captcha;
                }
            }

            if ($hasErrors == false) {
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $mob_array = $this->generic->mobileCode($_POST['mobile']);
                $result = $this->model->createPatron($_POST['email'], $_POST['f_name'], $_POST['l_name'], $mob_array['code'], $mob_array['mobile'], $_POST['password'], $payment_request_id);
                if ($result['Message'] == 'success') {
                    $str = $this->model->getUserTimeStamp($result['user_id']);
                    if ($returnUrl != NULL) {
                        $str = $str . $returnUrl;
                    }
                    $this->registerMail($str, $result['email_id']);
                    //sending sms
                    $mobileNo = $mob_array['mobile'];
                    $message = $this->model->fetchMessage('p1');
                    $this->model->sendSMS($result['user_id'], $message, $mobileNo);
                    if ($reurl != NULL) {
                        echo json_encode(array('status' => 1));
                    } else {
                        //redirect browser to success page
                        header("Location: /patron/register/success");
                    }
                } else {
SwipezLogger::error(__CLASS__, '[E053]Error while creating patron Error: ' . $result['Message']);
                    $this->setGenericError();
                }
            } else {
                if ($reurl != NULL) {
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
                } else {
                    $this->view->setError($hasErrors);
                    $this->index();
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E054]Error while saving patron personal details Error: ' . $e->getMessage());
        }
    }

    function registerMail($str, $email) {
        $encoded = $this->encrypt->encode($str);
        $verifyemailurl = $this->app_url . '/login/verifycustomer/' . $encoded;
        SwipezLogger::debug(__CLASS__, "URL : " . $verifyemailurl);
        $emailWrapper = new EmailWrapper();
        $mailcontents = $emailWrapper->fetchMailBody("user.verifyemail");
        if (isset($mailcontents[0]) && isset($mailcontents[1])) {
            $message = $mailcontents[0];
            $message = str_replace('__EMAILID__', $email, $message);
            $message = str_replace('__LINK__', $verifyemailurl, $message);
            $message = str_replace('__BASEURL__', $this->app_url, $message);
            #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
            $emailWrapper->sendMail($email, "", $mailcontents[1], $message);
        } else {
            SwipezLogger::warn(__CLASS__, "Mail could not be sent with verify email link to : " . $email);
        }
    }

    /**
     * User information stored to DB sucessfully
     * 
     */
    function success() {
        try {
            $this->view->title = 'Email Verification Pending';
            $this->view->canonical = 'patron/register/success';
            $this->view->render('nonloggedinheader');
            $this->view->render('patron/register/success');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E055]Error while saving patron personal details Error: ' . $e->getMessage());
        }
    }

    /**
     * Verifies a users email against databases user_id & create_date columns
     * 
     * @param type $link
     */
    public function verifyemail($link) {
        try {
            $user_id = $this->session->get('userid');
            $result = $this->model->validateEmailVerificationLink($link);
            if ($result['user_id'] != '') {
                $user_id = $result['user_id'];
                $user_detail = $this->model->getUserList($user_id);
                //$this->model->emailVerify($user_detail['email_id']);
                $this->model->updateCustomer_userid($user_detail['email_id'], $user_id);
                $this->model->updateCustomer_payment($user_id);
                $this->model->updatePending_change($user_id);
                $this->model->updateCustomerStatus($user_id, 2);
                $this->model->updatePatronUserStatus(ACTIVE_PATRON, $user_id);

                $this->session->set('user_name', $user_detail['first_name'] . ' ' . $user_detail['last_name']);
                $this->session->set('display_name', $user_detail['first_name']);
                $this->session->set('email_id', $user_detail['email_id']);
                $this->session->set('user_status', ACTIVE_PATRON);
                $this->session->set('logged_in', TRUE);
                $this->session->set('userid', $user_detail['user_id']);
                if (isset($result['returnurl'])) {
                    if (substr($result['returnurl'], -6) == '!event') {
                        $returnurl = str_replace('!event', '', $result['returnurl']);
                        header("Location:/patron/event/view/" . $returnurl);
                    } elseif (substr($result['returnurl'], -5) == '!plan') {
                        $returnurl = str_replace('!plan', '', $result['returnurl']);
                        $id = $this->encrypt->decode($returnurl);
                        $short_url = $this->common->getShortURL($id);
                        $this->session->set('paidMerchant_request', TRUE);
                        header("Location:/m/" . $short_url . "/confirmpackage/" . $returnurl);
                    } else {
                        $this->session->set('paidMerchant_request', TRUE);
                        header("Location:/patron/paymentrequest/pay/" . $result['returnurl']);
                    }
                } else {
                    header("Location:/patron/register/thankyou");
                }
            } else {
SwipezLogger::error(__CLASS__, '[E218]Error while verify email Error: for user id [' . $user_id . ']');
                $this->setError('Link is not valid anymore', 'This link is not valid anymore as it was already used once OR has expired.');
                header("Location:/error");
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E056]Error while verify email Error:for user id [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Displays thank you message to user post email verification and registration
     * 
     */
    function thankyou() {
        try {
            $user_id = $this->session->get('userid');
            $this->view->canonical = 'patron/register/thankyou';
            $this->view->title = 'Thank you for registering with Swipez';
            $this->view->render('nonloggedinheader');
            $this->view->render('patron/register/thankyou');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E057]Error while thank you initiate Error:for user id [' . $user_id . '] ' . $e->getMessage());
        }
    }

}
