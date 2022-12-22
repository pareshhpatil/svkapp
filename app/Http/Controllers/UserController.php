<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Libraries\Encrypt;
use App\Libraries\DataValidation as Valid;
use App\Libraries\Helpers;
use Google_Client;
use Log;
use Illuminate\Support\Facades\Auth as SwipezAuth;
use Illuminate\Support\Facades\Session;
use Exception;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use App\Jobs\MerchantRegistrationNotification;
use Validator;
use Illuminate\Http\Request;
use DateTime;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Firebase\Auth\Token\Exception\InvalidToken;

use App\Console\Commands\ImportBriqData;

class UserController extends Controller
{

    private $user_model = null;

    public function __construct()
    {
        $this->user_model = new User();
    }

    public function sendOTP()
    {
        //$valid_captcha = $this->validateCaptcha();
        $account_type = 1;
        $valid_captcha = true;
        if ($valid_captcha == true) {
            $usertext = $_POST['username'];
            $valid = false;
            $mobile = '';
            $email = '';
            $response = '';
            $user_id = null;
            $type = isset($_POST['exist_type']) ? $_POST['exist_type'] : 1;
            if (Valid::validMobile($usertext)) {
                $valid = true;
                $mobile = $usertext;
            }
            if (Valid::validEmail($usertext)) {
                $valid = true;
                $email = $usertext;
            }
            if ($valid == true) {
                $otp = rand(1000, 9999);
                if ($mobile != '') {
                    $exist_mobile = $this->user_model->getTableRow('user', 'mobile_no', $mobile);
                    if (!empty($exist_mobile) && $type == 1) {
                        $valid = false;
                        $response = 'Mobile number already exists. Would you like to <a href="/login">login</a>';
                    } else if (!empty($exist_mobile) && $type == 3) {
                        $valid = false;
                        $response = 'Mobile number already exists. Would you like to <span style="color:#18AEBF "><a href="/login">login</a></span>';
                    } else {
                        if (empty($exist_mobile) && $type == 2) {
                            $valid = false;
                            $response = 'Mobile number does not exists. Would you like to register <a href="/merchant/register">Register</a>';
                        } else {
                            if (!empty($exist_mobile)) {
                                $user_id = $exist_mobile->user_id;
                                if ($exist_mobile->password != '') {
                                    $account_type = 2;
                                    $valid = false;
                                    $response = 'success';
                                }
                            }
                        }
                    }

                    if ($valid == true) {
                        $sms = "Your One-Time Password (OTP) is $otp to login into Swipez";
                        $this->user_model->saveOTP($mobile, $email, $otp, $user_id);
                        Helpers::sendSMS($sms, $mobile);
                        Log::debug('Registration OTP sent to mobile ' . $mobile);
                        $response = 'success';
                    }
                }

                if ($email != '') {
                    $exist_email = $this->user_model->getTableRow('user', 'email_id', $email);


                    if (!empty($exist_email) && $type == 1) {
                        $valid = false;
                        $response = 'Email id already exists. Would you like to <a href="/login">login</a>';
                    } else if (!empty($exist_email) && $type == 3) {
                        $valid = false;
                        $response = 'Email id already exists. Would you like to <span style="color:#18AEBF "><a href="/login">login</a></span>';
                    } else {
                        if (empty($exist_email) && $type == 2) {
                            $valid = false;
                            $response = 'Email id is not registered with us. Would you like to register a new account <a href="/merchant/register">Register</a>';
                        } else {
                            if (!empty($exist_email)) {
                                $user_id = $exist_email->user_id;
                                if ($exist_email->password != '') {
                                    $account_type = 2;
                                    $valid = false;
                                    $response = 'success';
                                }
                            }
                        }
                    }

                    if ($valid == true) {
                        $this->user_model->saveOTP($mobile, $email, $otp, $user_id);
                        $data['otp'] = $otp;
                        Helpers::sendMail($email, 'otp', $data, 'One time password for Swipez Login');
                        Log::debug('Registration OTP sent to email ' . $email);
                        $response = 'success';
                    }
                }
                Session::forget('google_validate');
            } else {
                $response = 'Invalid input please enter valid email id or 10 digit mobile';
            }
        } else {
            $response = 'Invalid captcha please try again';
        }
        $array['message'] = $response;
        $array['account_type'] = $account_type;
        $array['type'] = $type;
        echo json_encode($array);
    }

    public function sendValidatedEmail($source, $email, $mobile, $service_id = 0)
    {
        $service = '';
        if ($service_id > 0) {
            $service_detail = $this->user_model->getTableRow('swipez_services', 'service_id', $service_id);
            $service = $service_detail->title;
        }
        $data['source'] = $source;
        $data['email'] = $email;
        $data['mobile'] = $mobile;
        $data['service'] = $service;
        $campaign_id = Helpers::getCookie('registration_campaign_id');
        $campaign_name = '';
        if ($campaign_id > 0) {
            $camp_det = $this->user_model->getTableRow('registration_campaigns', 'id', $campaign_id);
            if (!empty($camp_det)) {
                $campaign_name = $camp_det->campaign_name;
            }
        }
        $data['campaign_name'] = $campaign_name;
        if (env('APP_ENV') != 'LOCAL') {
            MerchantRegistrationNotification::dispatch('', $service, $email, $mobile, $campaign_name, $source, $campaign_id, $service_id)->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
        }
    }

    public function validateOTP()
    {
        //$valid_captcha = $this->validateCaptcha();
        $valid_captcha = true;
        if ($valid_captcha == true) {
            $username = $_POST['username'];
            $type = isset($_POST['exist_type']) ? $_POST['exist_type'] : 1;
            $otp = $_POST['otp'];
            if (strlen($otp) == 4) {
                $otpresponse = $this->user_model->validateOTP($username, $otp);
                if ($otpresponse != false) {
                    $mobile = '';
                    $email = '';
                    if (strlen($username) == 10) {
                        $mobile = $username;
                    } else {
                        $email = $username;
                    }
                    $this->sendValidatedEmail('OTP', $email, $mobile, $_POST['service_id']);
                    Session::put('product_login_token', Encrypt::encode($otpresponse->id));
                    if ($type == 3) {
                        Session::put('product_service_id', $_POST['service_id']);
                    } else {
                        Session::put('product_service_id', Encrypt::encode($_POST['service_id']));
                    }

                    $response['status'] = 1;
                    if ($type == 2 && $otpresponse->user_id != '') {
                        $m_detail = $this->user_model->getTableRow('merchant', 'user_id', $otpresponse->user_id);
                        if (!empty($m_detail)) {
                            $response['status'] = 2;
                            $response['redirect_link'] = '/moneycontrol/easybiz/' . Encrypt::encode($m_detail->merchant_id . $otpresponse->user_id);
                        }
                    }

                    if ($type == 3) {
                        $response['status'] = 3;
                        $response['response'] = "OTP Verified";
                        $response['type'] = $type;
                    }

                    Log::debug('OTP validated for ' . $username);
                } else {
                    $response['error'] = 'Invalid OTP please enter valid OTP';
                    $response['status'] = 0;
                    $response['type'] = $type;
                    Log::debug('OTP validation failed for ' . $username);
                }
            } else {
                $response['error'] = 'Invalid OTP please enter valid OTP';
                $response['status'] = 0;
                $response['type'] = $type;
                Log::debug('OTP validation failed for ' . $username);
            }
        } else {
            $response['error'] = 'Invalid captcha please try again';
            $response['status'] = 0;
        }
        echo json_encode($response);
    }

    public function validateCaptcha()
    {
        if (isset($_POST['recaptcha_response']) && !empty($_POST['recaptcha_response'])) {
            //your site google secret key
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

    public function googleLogin($token, $service_id = 0)
    {
        $clientID = env('GOOGLE_AUTH_CLIENT_ID');
        $client = new Google_Client(['client_id' => $clientID]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($token);
        if ($payload) {
            $email = $payload['email'];
            $exist = $this->user_model->emailExist($email);
            if ($exist == false) {
                $id = $this->user_model->saveOTP('', $email, rand(1000, 9999));
                Session::put('product_login_token', Encrypt::encode($id));
                Session::put('product_service_id', Encrypt::encode($service_id));
                Session::put('google_validate', true);
                $this->sendValidatedEmail('Google', $email, '', $service_id);
                return redirect('/merchant/getting-started', 301);
            } else {
                $service_id = ($service_id > 0) ? Encrypt::encode($service_id) : null;
                $redirect_url = $this->setTokenLoginDetails($exist->user_id, $service_id);
                return redirect($redirect_url);
            }
        } else {
            return redirect('/', 301);
        }
    }

    /**
     * Sets a login session for any user that logins to their Swipez account. This is used for merchants as well as patrons of all types.
     * Basicailly if this does not work no one can login to Swipez.
     *
     * - This function impacts following functionality :
     * 1. Patron login
     * 2. Merchant login
     * 3. Sub-merchant login
     * 4. Cable customer login
     * 5. MH12 obo
     * Test change
     *
     * Run following function in laravel dusk to test impact of changes in this method.
     *
     * @param [type] $user
     * @return void
     */
    public function setLoginSession($user)
    {
        Session::put('user_status', $user->user_status);
        Session::put('user_name', $user->name);
        Session::put('display_name', $user->first_name);
        Session::put('last_name', $user->last_name);
        Session::put('full_name', $user->first_name . ' ' . $user->last_name);
        Session::put('email_id', $user->email_id);
        Session::put('mobile', $user->mobile_no);
        Session::put('system_user_id', Encrypt::encode($user->user_id));
        Session::put('auth_id', Encrypt::encode($user->id));
        Session::put('logged_in', true);

        $preference = $this->user_model->getPreferences($user->user_id);

        Session::put('default_timezone', $preference->timezone ??  'America/Cancun');
        Session::put('default_currency', $preference->currency ?? 'USD');
        Session::put('default_date_format', $preference->date_format ?? 'M d yyyy');
        Session::put('default_time_format', $preference->time_format ?? '24');

        Session::forget('menus');
        //Checking whether user is a cable customer 
        if ($user->login_type == 2) {
            Session::put('userid', Encrypt::encode($user->user_id));
            Session::forget('merchant_id');
            $service_list = $this->setActiveService($user->user_id, 2);
            if (!empty($service_list)) {
                foreach ($service_list as $service) {
                    if ($service['service_id'] == 9) {
                        return redirect('/cable/settopbox');
                    }
                }
            };
            return redirect('/patron/dashboard');
        } else {
            //Check if user is a sub merchant login
            if ($user->user_status == 20) {
                $merchant = $this->user_model->getTableRow('merchant', 'group_id', $user->group_id);
                $this->setSubuserSession($user);
            } else {
                Session::put('group_type', 1);
                $merchant = $this->user_model->getTableRow('merchant', 'user_id', $user->user_id);
            }
            if (!empty($merchant)) {
                if ($user->master_login_group_id > 0) {
                    $this->setMasterLogin($user);
                }

                $paid_package = array(3, 4, 9, 12, 13, 14, 15);
                Session::forget('package_expire');
                Session::forget('package_reminder_days');
                if (in_array($merchant->merchant_plan, $paid_package)) {
                    Session::put('is_paid_package', 1);
                    $package_reminder_date = Date('Y-m-d', strtotime(env('PLAN_REMINDER_DAYS', 7) . ' days'));
                    if ($merchant->package_expiry_date < $package_reminder_date) {
                        if ($merchant->package_expiry_date < date('Y-m-d')) {
                            Session::put('package_expire', true);
                        } else {
                            $date1 = new DateTime($merchant->package_expiry_date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $diff = date_diff($date1, $date2);
                            $days = $diff->format("%a");
                            Session::put('package_reminder_days', $days);
                        }
                        Session::put('package_link', Encrypt::encode($merchant->merchant_plan));
                        if ($merchant->merchant_plan == 14) {
                            Session::put('choose_package_link', '/venue-booking-software-pricing');
                        } else if ($merchant->merchant_plan == 13) {
                            Session::put('choose_package_link', '/event-registration-pricing');
                        } else {
                            Session::put('choose_package_link', '/billing-software-pricing');
                        }
                    }
                } else {
                    //set flag - paid package not assigned to merchant
                    Session::put('is_paid_package', 0);
                }

                $this->setActiveService($merchant->merchant_id);

                $franchise = $this->user_model->getTableRow('franchise', 'merchant_id', $merchant->merchant_id);
                if (!empty($franchise)) {
                    Session::put('has_franchise', 1);
                }
                $vendor = $this->user_model->getTableRow('vendor', 'merchant_id', $merchant->merchant_id);
                if (!empty($vendor)) {
                    Session::put('vendor_enable', 1);
                }
                Session::put('account_type', $merchant->type);
                Session::put('merchant_type', $merchant->merchant_type);
                Session::put('merchant_id', Encrypt::encode($merchant->merchant_id));
                Session::put('company_name', $merchant->company_name);
                Session::put('merchant_plan', $merchant->merchant_plan);
                Session::put('display_url', $merchant->display_url);
                Session::put('partner_id', $merchant->partner_id);
                Session::put('service_id', $merchant->service_id);
                Session::put('is_legal', $merchant->is_legal_complete);
                Session::put('userid', Encrypt::encode($merchant->user_id));
                Session::put('created_date', $merchant->created_date);

                // storing variables to check getting started steps are completed or not
                if ($merchant->entity_type == null) {    // using it to check if the merchant has completed the GST details step
                    Session::put('entity_type', 0);
                } else {
                    Session::put('entity_type', 1);
                }
                $isCityRegistered = $this->user_model->getRegCity($merchant->merchant_id);
                if ($isCityRegistered == null) {     // using it to check if user has completely filled the company details or not.
                    Session::put('reg_city', 0);
                } else {
                    Session::put('reg_city', 1);
                }
                $bankdetail = $this->user_model->getTableRow('merchant_bank_detail', 'merchant_id', $merchant->merchant_id);

                $isXwayActive = $this->user_model->isXwayActive($merchant->merchant_id);
                Session::put('is_xway_active', $isXwayActive);
                $isPaymentActive = $this->user_model->isPaymentActive($merchant->merchant_id);
                Session::put('is_payment_active', $isPaymentActive);

                if ($isXwayActive == false && $isPaymentActive == false) {
                    Session::put('has_payment_active', false);
                } else {
                    Session::put('has_payment_active', true);
                }

                $setting = $this->user_model->getTableRow('merchant_setting', 'merchant_id', $merchant->merchant_id);
                $isCompletedCompayPage = $this->user_model->getColumnValue('merchant_landing', 'merchant_id', $merchant->merchant_id, 'is_complete_company_page');
                $isCompletedCompayPage = ($isCompletedCompayPage != false) ? $isCompletedCompayPage : 0;
                $profile_step = $setting->profile_step;
                $currency = json_decode($setting->currency, 1);
                Session::put('isCompletedCompayPage', $isCompletedCompayPage);
                Session::put('request_demo', $setting->request_demo);
                Session::put('profile_step', $profile_step);
                Session::put('currency', $currency);
                $isBankVerified = 0;
                if ($bankdetail != false) {
                    $isBankVerified = ($bankdetail->verification_status == 2) ? 1 : 0;
                    if (count($currency) > 1 && $bankdetail->stripe_status == 0) {
                        $isBankVerified = 0;
                    }
                }


                Session::put('bank_verified', $isBankVerified);

                if ($setting->default_cust_column_renamed == 1) {
                    $customer_default_column = $this->user_model->getMerchantData($merchant->merchant_id, 'CUSTOMER_DEFAULT_COLUMN');
                    if ($customer_default_column != false) {
                        Session::put('customer_default_column', json_decode($customer_default_column, 1));
                    }
                } else {
                    Session::forget('customer_default_column');
                }


                if (Session::has('redirect_package_id')) {
                    $link = Session::get('redirect_package_id');
                    Session::forget('redirect_package_id');
                    return redirect('/merchant/package/confirm/' . $link);
                }
                return redirect('/merchant/dashboard');
            } else {
                Log::error('Merchant details does not exist for this user id :' . $user->user_id);
                header('Location: /error');
                exit();
            }
        }
    }

    /*
     * Set session for sub merchants storing roles and functionalities access given by merchant
     * as well as if this is franchise login here we are assign franchise_id
     * Customer group access: if merchant give a specific customer group to this login here we assign customer group for this login
     * so user can access only customer which are included in this group
     * if this function failed user will get access denied error message or customers not showing 
     * 
     * * - This function impacts following functionality :
     * 1. Sub-merchant login
     * 2. Franchise login
     * 
     * Run following function in laravel dusk to test impact of changes in this method.
     *
     * @param [type] $user
     * @return void
     */

    public function setSubuserSession($user)
    {
        $role_id = $this->user_model->getColumnValue('user_privileges', 'user_id', $user->user_id, 'role_id');
        Session::put('sub_user_id', $user->user_id);
        Session::put('sub_franchise_id', $user->franchise_id);
        if ($role_id == 0) {
            Session::put('all_roles', true);
            Session::put('group_type', 1);
        } else {
            if ($role_id != false) {
                $user_role = $this->user_model->getTableRow('roles', 'role_id', $role_id);
                Session::put('view_roles', explode(',', $user_role->view_controllers));
                Session::put('update_roles', explode(',', $user_role->update_controllers));
                Session::put('delete_roles', explode(',', $user_role->delete_controllers));
                Session::put('group_type', 2);
            }
            if ($user->customer_group != '') {
                $customer_group = $this->user_model->getColumnValue('user', 'user_id', $user->user_id, 'customer_group');
                Session::put('login_customer_group', explode(',', $customer_group));
            }
        }
    }

    public function setActiveService($merchant_id, $type = 1)
    {
        $service_list = $this->user_model->getActiveServiceList($merchant_id, $type);
        $merchant_services = array();
        if (!empty($service_list)) {
            $int = 0;
            foreach ($service_list as $row) {
                $service_list[$int]['key'] = Encrypt::encode($row['service_id']);
                $merchant_services[$row['service_id']] = $row['title'];
                $int++;
            }
            Session::put('active_service_list', $service_list);
            Session::put('merchant_services', $merchant_services);
        }
        return $service_list;
    }

    /*
     * Setting master merchant login details
     * Merchant can see list of logins 
     * Merchant can switch login using this list 
     * If this function failed merchant can not see login dropdown and can not switch login account
     * 
     * * - This function impacts following functionality :
     * 1. Master merchant login
     * 
     * Run following function in laravel dusk to test impact of changes in this method.
     *
     * @param [type] $user
     * @return void
     */

    public function setMasterLogin($user)
    {
        $master_list = $this->user_model->getMasterLoginList($user->master_login_group_id);
        if (!empty($master_list)) {
            $int = 0;
            foreach ($master_list as $row) {
                if ($user->user_id == $row['user_id']) {
                    $master_list[$int]['active'] = 'active';
                } else {
                    $master_list[$int]['active'] = '';
                }
                $master_list[$int]['key'] = Encrypt::encode($row['user_id'] . $row['merchant_id']);
                $int++;
            }
            Session::put('master_login_group', $master_list);
            Session::put('has_master_login', 1);
        }
    }

    public function tokenLogin($token, $service_id = null)
    {
        $token = str_replace(array(' ', '%', ';', '', 'update', 'delete'), '', $token);
        $detail = $this->user_model->getTableRow('login_token', 'token', $token);
        if ($detail != false) {
            if ($detail->status == 0) {
                $this->user_model->updateTokenStatus($detail->id, 1);
                $redirect_url = $this->setTokenLoginDetails($detail->user_id, $service_id);
                return redirect(env('ASSET_URL') . $redirect_url);
            } else {
                return redirect('/login')->withErrors(['Token' => 'Token expired']);
            }
        } else {
            return redirect('/login')->withErrors(['Token' => 'Invalid token']);
        }
    }

    public function setTokenLoginDetails($user_id, $service_id)
    {
        $id = $this->user_model->getColumnValue('user', 'user_id', $user_id, 'id');
        SwipezAuth::loginUsingId($id);
        $user = SwipezAuth::user();

        $this->setLoginSession($user);
        if (Session::has('redirect_package_id')) {
            $link = Session::get('redirect_package_id');
            Session::forget('redirect_package_id');
            return '/merchant/package/confirm/' . $link;
        }
        if ($service_id != null) {
            return '/merchant/dashboard/index/' . $service_id;
        } else {
            return '/merchant/dashboard';
        }
    }

    public function logoutUser()
    {
        SwipezAuth::logout();
        Session::flush();
        return redirect('/login', 301);
    }

    public function forgotpassword()
    {
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        return view('home/forgot', $data);
    }

    public function passwordsave(Request $request)
    {
        $validation = [
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8'
        ];

        $validator = Validator::make($request->all(), $validation);
        if ($validator->fails()) {
            return $this->easybizsetpassword($_POST['user_id'], $validator->errors());
        } else {
            $string = Encrypt::decode($_POST['user_id']);
            $merchant_id = substr($string, 0, 10);
            $user_id = substr($string, 10);
            $this->user_model->updateUserPassword($user_id, $_POST['password'], 12);
            $this->user_model->updateCompanyName($merchant_id, $_POST['company_name']);
            $redirect_url = $this->setTokenLoginDetails($user_id, null);
            return redirect($redirect_url);
        }
    }

    /**
     * Returns view for Swipez set password
     *
     * @return view
     */
    public function easybizsetpassword($link, $errors = null)
    {
        $string = Encrypt::decode($link);
        $merchant_id = substr($string, 0, 10);
        $user_id = substr($string, 10);
        $detail = $this->user_model->getTableRow('user', 'user_id', $user_id);
        if (!empty($detail)) {
            if ($detail->user_status == 11) {
                SEOMeta::setTitle('Swipez | Set password');
                SEOMeta::setDescription('Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs');
                OpenGraph::setTitle('Swipez | Set password');
                OpenGraph::setDescription('Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs');
                OpenGraph::setUrl('https://www.swipez.in/merchant/register');

                $merchant_detail = $this->user_model->getTableRow('merchant', 'merchant_id', $merchant_id);

                $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
                $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
                $data['javascript'] = array('register');
                $data['service_id'] = '2';
                $data['company_name'] = $merchant_detail->company_name;
                $data['user_id'] = $link;
                $data['errors'] = $errors;
                return view('home/easybizsetpassword', $data);
            }
        }
        return redirect('/moneycontrol/easybiz');
    }

    /**
     * Returns view for Swipez set password
     *
     * @return view
     */
    public function easybizlanding()
    {
        setcookie('registration_campaign_id', '32', time() + (864000 * 30), "/"); // 86400 = 1 day
        SEOMeta::setTitle('Swipez | Set password');
        SEOMeta::setDescription('Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs');
        OpenGraph::setTitle('Swipez | Set password');
        OpenGraph::setDescription('Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs');
        OpenGraph::setUrl('https://www.swipez.in/merchant/register');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        return view('home/easybiz', $data);
    }

    public function paymentLogin($link)
    {
        if (SwipezAuth::check()) {
            return redirect('/merchant/package/confirm/' . $link);
        }
        Session::put('redirect_package_id', $link);
        $package_id = Encrypt::decode($link);
        $detail = $this->user_model->getTableRow('package', 'package_id', $package_id);
        SEOMeta::setTitle('Swipez | Login');
        OpenGraph::setTitle('Swipez | Login');
        OpenGraph::setUrl('https://www.swipez.in/login');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['login_error'] = '';
        $data['loader'] = true;
        $data['detail'] = $detail;
        return view('home/packagelogin', $data);
    }

    public function checkToken(Request $request)
    {
        $encrypt_token = $request->token;
        $encrypt_token = str_replace(' ', '+', $encrypt_token);
        $decrypt_token = $this->decryptBriqToken($encrypt_token);

        if ($decrypt_token["result"]["status"] == 'success') {
            $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
            $auth = $factory->createAuth();

            try {
                $verifiedIdToken = $auth->verifyIdToken($decrypt_token["result"]["token"]);
            } catch (InvalidToken $e) {
                log::error('BRIQ login error: The token is invalid: ' . $e->getMessage());
                return view('errors/briq-register');
            } catch (\InvalidArgumentException $e) {
                log::error('BRIQ login error: The token could not be parsed:' . $e->getMessage());
                return view('errors/briq-register');
            }

            $uid = $verifiedIdToken->claims()->get('sub');
            $user = $auth->getUser($uid);
            $user_uid = $user->uid;
            $email = $user->email;

            $email = isset($email) ? trim($email) : null;

            if ($email != '' || $email != null) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $exist_email = $this->user_model->getTableRow('user', 'email_id', $email);
                    $exist_uid = $this->user_model->getTableRow('user', 'briq_user_id', $user_uid);

                    if (empty($exist_email)) {
                        if (empty($exist_uid)) {
                            $data_setup_type =  env('AUTO_SETUP_CONSTRUCTION_TEST_DATA');
                            if ($data_setup_type == 'BRIQ') {
                                $briq_user_emails = ['briq.com', 'br.iq'];
                                $parts = explode('@', $email);
                                $domain = array_pop($parts);
                                if (in_array($domain, $briq_user_emails)) {
                                    //register with data 
                                    return $this->registerNewBriqUser($email, $user_uid, 1);
                                } else {
                                    //register without data 
                                    return $this->registerNewBriqUser($email, $user_uid, 0);
                                }
                            } else if ($data_setup_type == 'ALL') {
                                //register with data 
                                return $this->registerNewBriqUser($email, $user_uid, 1);
                            } else {
                                // register without data  
                                return $this->registerNewBriqUser($email, $user_uid, 0);
                            }
                        } else {
                            $this->user_model->updateTable('user', 'briq_user_id', $user_uid, 'email_id', $email);
                            $redirect_url =  $this->setTokenLoginDetails($exist_uid->user_id, null);
                            return redirect($redirect_url);
                        }
                    } else {
                        $this->user_model->updateTable('user', 'email_id', $email, 'briq_user_id', $user_uid);
                        $redirect_url =  $this->setTokenLoginDetails($exist_email->user_id, null);
                        return redirect($redirect_url);
                    }
                } else {
                    log::error('BRIQ login error: email recieved from firebase is not a valid email: ' . $email);
                    return view('errors/briq-register');
                }
            } else {
                log::error('BRIQ login error: email recieved from firebase is blank : ' . $email . "checking UID....");
                $exist_uid = $this->user_model->getTableRow('user', 'briq_user_id', $user_uid);
                if (empty($exist_uid)) {
                    log::error('BRIQ login error: UID recieved from firebase is blank : ' . $user_uid);
                    return view('errors/briq-register');
                } else {
                    $redirect_url =  $this->setTokenLoginDetails($exist_uid->user_id, null);
                    return redirect($redirect_url);
                }
            }
        } else {
            return view('errors/briq-register');
        }
    }

    function registerNewBriqUser($email, $uid, $is_data)
    {
        $response = Helpers::APIrequest(env('BRIQ_USER_DETAIL_API_URL') . $uid, '', "GET", true,  array("AUTH: " . env('BRIQ_USER_API_AUTH')));
        $response = json_decode($response, 1);

        $company_id = $response["user_data"]["company_id"];

        $result =  $this->user_model->briqRegister($email, 'first', 'last', '1', '', uniqid(),  $company_id, 2, 0, 2);
        if ($result->Message == 'success') {
            //remove these 4 qurrioes and put in procedure 
            $this->user_model->updateTable('user', 'email_id', $email, 'briq_user_id', $uid);
            $this->user_model->updateUserDetails($result->user_id, $result->user_id, 12);
            $this->user_model->updateTable('merchant_setting', 'merchant_id', $result->merchant_id, 'profile_step', '7');
            $this->user_model->updateTable('merchant_setting', 'merchant_id', $result->merchant_id, 'currency', ["USD"]);
            if($is_data){
                // insert test data 
                $imprt_data  = new ImportBriqData();
                $imprt_data->insertData($result->merchant_id,$result->user_id );
            }
            $redirect_url =  $this->setTokenLoginDetails($result->user_id, null);

            Session::put('default_timezone', 'America/Cancun');
            Session::put('default_currency', 'USD');
            Session::put('default_date_format', 'M d yyyy');
            Session::put('default_time_format', '24');

            return redirect($redirect_url);
        } else {
            return Helpers::handleCatch(__METHOD__, $result->Message);
        }
    }

    function decryptBriqToken($encrypt_token)
    {
        $response = Helpers::APIrequest(env('BRIQ_GCP_TOKEN_DECRYPT_URL'), '{"data": {"token":"' . $encrypt_token . '"}}', "POST", true,  array("Content-Type: application/json"));
        $response = json_decode($response, 1);
        if ($response['result']['status'] == 'success') {
            return $response;
        } else {
            log::error('BRIQ token decrypt error');
            return view('errors/briq-register');
        }
    }
}
