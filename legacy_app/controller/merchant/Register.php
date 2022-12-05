<?php

use App\Jobs\MerchantRegistrationNotification;
use App\Jobs\MerchantRegistrationUpdateCrm;

/**
 * Register controller to mediate patron registration process
 *
 */
class Register extends Controller {

    function __construct() {
        parent::__construct();
        // Auth::handleLogin();
    }

    /**
     * Display Merchant personal form
     */
    function index($plan_id = NULL) {
        try {
            $this->view->show_hubspot = 1;
            if ($plan_id == NULL) {
                $this->view->plan_id = $this->encrypt->encode(2);
            } else {
                $this->view->plan_id = $plan_id;
            }
            $this->view->title = 'Merchant Sign up';
            $this->view->canonical = $_GET['url'];
            //$this->view->render('header/guest');
            $this->view->render('merchant/register/register');
            //$this->view->render('footer/register');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E018]Error while merchant personal register initiate Error: ' . $e->getMessage());
        }
    }

    /**
     * Display Merchant personal form
     */
    function complete() {
        try {
            $token_data = $this->getTokenDetail();
            $this->view->service_id = $this->session->get('product_service_id');
            $google_validate = $this->session->get('google_validate');
            if ($google_validate == true) {
                $this->view->randpassword = uniqid();
            }
            $this->view->data = $token_data;
            $this->view->google_validate = $google_validate;
            $this->view->title = 'Merchant Sign up';
            $this->view->render('merchant/register/product_register');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E018]Error while merchant personal register initiate Error: ' . $e->getMessage());
        }
    }

    function getTokenDetail() {
        try {
            $product_login_token = $this->session->get('product_login_token');
            if (!isset($product_login_token)) {
                header('Location: /merchant/register');
                exit;
            }
            $id = $this->encrypt->decode($product_login_token);
            $row = $this->common->getSingleValue('otp', 'id', $id);
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__ . __FUNCTION__, $e->getMessage());
        }
    }

    /**
     * Display Merchant personal form
     */
    function campaign($link = '') {
        try {
            $campaign_id = $this->common->getRowValue('id', 'registration_campaigns', 'campaign_text_id', $link, 1);
            if ($campaign_id != false) {
                $this->session->setCookie('registration_campaign_id', $campaign_id);
            }
            header('Location: /merchant/register');
            exit;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E018]Error while merchant personal register initiate Error: ' . $e->getMessage());
        }
    }

    /**
     * Save patrons personal information
     */
    function saved($type = null) {
        //TODO Check if the action is POST and only then execute the rest of the code below. Maybe this
        //should be handled in a central location someplace.
        try {
            if (empty($_POST)) {
                header("Location:/merchant/register");
            }
            SwipezLogger::info(__CLASS__, 'Started final save of new registration ' . $_POST['email']);
            if (isset($_POST['g-recaptcha-response'])) {
                $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if ($result) {
                    
                } else {
                    $hasErrors[0][0] = "Captcha";
                    $hasErrors[0][1] = "Invalid captcha please click on captcha box";
                }
            } else {
                require_once MODEL . 'merchant/RegisterModel.php';
                $registerModel = new RegisterModel();
                require CONTROLLER . 'merchant/Registervalidator.php';
                $validator = new Registervalidator($registerModel);
                $validator->validateMerchantRegister();
                $hasErrors = $validator->fetchErrors();
                SwipezLogger::info(__CLASS__, 'Validate merchant register ' . $hasErrors);
            }

            $service_id = 2;
            if ($_POST['service_id'] != '') {
                $service_id = $this->encrypt->decode($_POST['service_id']);
            }

            if ($hasErrors == false) {
                if (isset($_POST['plan_id'])) {
                    $plan_id = $this->encrypt->decode($_POST['plan_id']);
                } else {
                    $plan_id = 2;
                }
                $campaign_id = $this->session->getCookie('registration_campaign_id');
                $license_id = $this->session->get('license_key_id');
                $this->session->remove('license_key_id');
                $license_id = ($license_id > 0) ? $license_id : 0;
                $campaign_id = ($campaign_id > 0) ? $campaign_id : 0;

                $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $mob_array = $this->generic->mobileCode($_POST['mobile']);
                $name_array = $this->generic->getLastName($_POST['name']);

                SwipezLogger::info(__CLASS__, 'Invoking create merchant stored proc ' . $_POST['email']);

                $result = $registerModel->createMerchant($_POST['email'], $name_array['first_name'], $name_array['last_name'], $mob_array['code'], $mob_array['mobile'], $_POST['password'], $_POST['company_name'], 2, $campaign_id, $license_id, $service_id);
                $_POST['mobile'] = $mob_array['mobile'];

                SwipezLogger::info(__CLASS__, 'Create merchant stored proc complete ' . $_POST['email']);

                if ($result['Message'] == 'success') {
                    //Add message to queue
                    $merchant_id = $result['merchant_id'];
                    SwipezLogger::info(__CLASS__, 'Adding message to queue ' . $merchant_id);
                    try {
                        MerchantRegistrationNotification::dispatch($merchant_id, $_POST['company_name'], $_POST['email'], $mob_array['mobile'], $name_array['first_name'], $name_array['last_name'], $campaign_id, $service_id)->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
                        SwipezLogger::info(__CLASS__, 'Merchant Registration Notification added to SQS queue merchant id: ' . $merchant_id);
                        MerchantRegistrationUpdateCrm::dispatch($merchant_id, $_POST['company_name'], $_POST['email'], $mob_array['mobile'], $name_array['first_name'], $name_array['last_name'], $campaign_id, $service_id)->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
                        SwipezLogger::info(__CLASS__, 'Merchant Registration Update CRM added to SQS queue merchant id: ' . $merchant_id);
                    } catch (Exception $e) {
Sentry\captureException($e);
                        
SwipezLogger::error(__CLASS__, '[E023]Error while adding to SQS queue : ' . $e->getMessage());
                    }
                    //Get login token for merchant
                    $token = $this->common->saveLoginToken($result['user_id'], $result['email_id']);
                    SwipezLogger::info(__CLASS__, 'Login token saved and method complete' . $merchant_id);
                    if ($type == 'package') {
                        $result['status'] = 1;
                        return $result;
                    } else {
                        header("Location:/login/token/" . $token . "/" . $_POST['service_id'], 301);
                        die();
                    }
                } else {
SwipezLogger::error(__CLASS__, '[E022]Error while creating merchant Error: ' . $result['Message']);
                    $this->setGenericError();
                }
            } else {
                if (count($hasErrors) == 1) {
                    if ($hasErrors['email'][0] == 'Email ID') {
                        $token_data = $this->getTokenDetail();
                        if ($token_data['email_id'] == $_POST['email']) {
                            $user_id = $this->model->getUserId($_POST['email']);
                            if ($user_id != false) {
                                $token = $this->common->saveLoginToken($user_id, $_POST['email']);
                                SwipezLogger::info(__CLASS__, 'Login token saved and method complete' . $user_id);
                                header("Location:/login/token/" . $token . "/" . $_POST['service_id'], 301);
                                die();
                            }
                        }
                    }
                }


                if ($type == 'package') {
                    $res['status'] = 0;
                    $res['error'] = $hasErrors;
                    return $res;
                } else {
                    $this->view->title = 'Merchant Sign up';
                    $this->view->_POST = $_POST;
                    $this->view->setError($hasErrors);
                    $this->complete();
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E023]Error while creating merchant Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * User information stored to DB sucessfully
     *
     */
    function success() {
        $this->view->showcampaign_script = $this->session->get('showcampaign_script');
        $this->view->title = 'Email Verification Pending';
        $this->view->render('header/guest');
        $this->view->render('merchant/register/success');
        $this->view->render('footer/footer');
    }

    function dashboard($link) {
        $verification_token = $this->session->get('verification_token');
        $this->session->remove('verification_token');
        if (isset($verification_token)) {
            header("Location:/login/verifycustomer/" . $verification_token, 301);
        } else {
            header("Location:/login", 301);
        }
    }

    /**
     * Verifies a users email against databases user_id & create_date columns
     *
     * @param type $link
     */
    public function verifyemail($link = '') {
        try {
            $result = $this->model->validateEmailVerificationLink($link);
            if ($result != '') {
                $this->session->set('merchant_id', $result['merchant_id']);
                $this->session->set('userid', $result['user_id']);
                $this->session->set('group_type', $result['type']);
                $license_key_id = $this->common->getRowValue('license_key_id', 'account', 'merchant_id', $result['merchant_id'], 1);
                $emailWrapper = new EmailWrapper();
                if ($license_key_id > 0) {
                    $package_id = $this->common->getRowValue('package_id', 'license', 'id', $license_key_id);
                    $package_info = $this->common->getRowValue('package_description', 'package', 'package_id', $package_id);
                    $mailcontents = $emailWrapper->fetchMailBody("merchant.license");
                    $mailcontents[0] = str_replace('__PACKAGE_DETAIL__', $package_info, $mailcontents[0]);
                } else {
                    $mailcontents = $emailWrapper->fetchMailBody("merchant.welcome");
                }
                $company_name = $this->common->getRowValue('company_name', 'merchant', 'merchant_id', $result['merchant_id']);
                $subject = $mailcontents[1];
                $message = $mailcontents[0];
                $message = str_replace('__COMPANY_NAME__', $company_name, $message);
                $emailWrapper->sendMail($result['email_id'], "", $subject, $message, $message);
                $this->session->set('verification_token', $link);
                if ($result['plan_id'] != '') {
                    header("Location:/merchant/package/confirm/" . $result['plan_id']);
                } else {
                    if ($result['user_status'] == 15) {
                        header("Location:/merchant/register/thankyou/activated");
                    } else {
                        header("Location:/merchant/register/thankyou");
                    }
                }
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

    /**
     * Displays thank you message to user post email verification and registration
     *
     */
    function thankyou($status = null) {
        try {
            $type = $this->session->get('group_type');
            $this->view->fb_pixel = 1;
            $this->view->link = $this->session->get('verification_token');
            $this->view->status = $status;
            $this->view->title = 'Email verification successful';
            $this->view->render('header/guest');
            $this->view->render('merchant/register/thankyou');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E026]Error on thank you page Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            header("Location:/error");
        }
    }

    function assignmerchantPG($token, $merchant_id) {
        $swipez_user_id = $this->common->getRowValue('user_id', 'merchant', 'merchant_id', env('SWIPEZ_MERCHANT_ID'));

        $token_detail = $this->common->getSingleValue('login_token', 'token', $token, 0, ' and status=0');
        $user_id = $token_detail['user_id'];
        if ($user_id == $swipez_user_id) {
            $this->common->genericupdate('login_token', 'status', 1, 'id', $token_detail['id']);
            $exist = $this->common->getRowValue('fee_detail_id', 'merchant_fee_detail', 'merchant_id', $merchant_id);
            if ($exist == false) {
                require_once CONTROLLER . 'merchant/Profile.php';
                $profile = new Profile(true);
                $vendor_id = $profile->saveMarketPlaceVendor($merchant_id);
                if ($vendor_id == false) {
                    echo 'Failed';
                } else {
                    echo 'Success';
                }
            } else {
                echo 'Success';
            }
        } else {
            echo 'Invalid token';
        }
    }

}
