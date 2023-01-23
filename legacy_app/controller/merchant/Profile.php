<?php

/**
 * Profile controller class to handle profile update for merchant
 */

use App\Jobs\SupportTeamNotification;
use App\Jobs\MerchantRegistrationCrmService;
use App\Jobs\MerchantServiceAddToCRM;
use App\Jobs\MerchantRegistrationCrmPennyDrop;
use App\Jobs\MerchantRegistrationCrmDocument;
use Illuminate\Support\Facades\DB;

class Profile extends Controller
{

    function __construct($guest = false)
    {
        parent::__construct();

        //TODO : Check if using static function is causing any problems!
        if ($guest == false) {
            $this->validateSession('merchant');
        }
        $this->view->selectedMenu = 'profile';
    }

    /**
     * Display merchant profile
     */
    function index()
    {
        try {
            $this->hasRole(1, 100);
            $merchant_id = $this->session->get('merchant_id');
            $user_id = $this->session->get('userid');
            $personalDetails = $this->model->getPersonalDetails($user_id);
            if (empty($personalDetails)) {
                SwipezLogger::error(__CLASS__, '[E014]Error while update merchant profile fetching personal details. user id ' . $user_id);
                $this->setGenericError();
            }
            $merchantDetails = $this->model->getMerchantDetails($merchant_id);

            if (empty($merchantDetails)) {
                SwipezLogger::error(__CLASS__, '[E015]Error while update merchant profile fetching merchant details Error: ');
                $this->setGenericError();
            }
            $accountDetails = $this->model->getBankDetails($this->merchant_id);
            $pgDetails = $this->model->getPGDetails($merchantDetails['merchant_id']);
            $entitytype = $this->model->getEntityType();
            if (empty($entitytype)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty enitity type list while merchant update profile ');
            }
            $industrytype = $this->model->getIndustryType();
            if (empty($industrytype)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty industry type list while merchant update profile ');
            }
            if ($accountDetails['address_proof'] != '') {
                $accountDetails['address_proof'] = json_decode($accountDetails['address_proof'], 1);
            }
            if ($accountDetails['partner_pan_card'] != '') {
                $accountDetails['partner_pan_card'] = json_decode($accountDetails['partner_pan_card'], 1);
            }
            $GettingStarted = $this->session->get('GettingStarted');
            if ($GettingStarted == true) {
                $this->session->set('GettingStarted', false);
                $this->smarty->assign("GettingStarted", true);
            }
            $state_code = $this->model->getStates();
            $this->view->js = array('template', 'dashboard');
            $this->smarty->assign("personal", $personalDetails);
            $this->smarty->assign("merchant", $merchantDetails);
            $this->smarty->assign("state_code", $state_code);
            $this->smarty->assign("account", $accountDetails);
            $this->smarty->assign("pg", $pgDetails);
            $this->smarty->assign("entitytype", $entitytype);
            $this->smarty->assign("industrytype", $industrytype);
            $this->view->title = "Update profile";
            $this->smarty->assign('title', $this->view->title);
            $breadcumbs_array = array(
                array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                array('title' => 'Company settings', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/profile/profile.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E016]Error while merchant profile initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function complete($type = '', $subtype = '')
    {
        $this->hasRole(1, 100);
        if ($type == 'GettingStarted') {
            $this->session->set('GettingStarted', true);
            $type = '';
        }
        $merchant = $this->common->getSingleValue('merchant_detail', 'merchant_id', $this->merchant_id);
        if ($merchant['is_legal_complete'] == 1) {
            // header('Location:/merchant/profile');
            // exit();
        }
        $this->smarty->assign("info", $merchant);
        //$step = $this->common->getRowValue('profile_step', 'merchant_setting', 'merchant_id', $this->merchant_id);
        $account = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $this->merchant_id);

        if ($merchant['entity_type'] == null && $type == 'document') {
            header('Location: /merchant/profile/complete/company', 301);
            die();
        }
        /**switch ($step) {
            case 4:
                if ($type != '' && $type != 'company')
                    header('Location: /merchant/profile/complete/company', 301);
                break;
            case 5:
                if ($type == '' || $type == 'document')
                    header('Location: /merchant/profile/complete/contact', 301);
                break;
            case 6:
                if ($type == '')
                    header('Location: /merchant/profile/complete/bank', 301);
                break;
            case 7:
                if ($type == '')
                    header('Location: /merchant/profile/complete/document', 301);
                break;
        }**/
        switch ($type) {
            case 'contact':
                if (isset($_POST['form_type'])) {
                    $this->smarty->assign("info", $_POST);
                } else {
                    $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
                    $this->smarty->assign("state_code", $state_code);
                }
                break;
            case 'document':
                $is_complete = $this->completeDocument($account, $merchant['entity_type']);
                $this->smarty->assign("document_complete", $is_complete);
                if (isset($_POST['form_type'])) {
                    $this->smarty->assign("info", $_POST);
                } else {
                    $account['account_number'] = $account['account_no'];
                    $info =  array_merge($merchant, $account);
                    $this->smarty->assign("info", $info);
                }
                $account['verification_status'] = 0;
                break;
            case 'bank':
                $is_complete = $this->completeDocument($account, $merchant['entity_type']);
                $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
                $this->smarty->assign("merchant_setting", $merchant_setting);
                $this->smarty->assign("document_complete", $is_complete);
                $bank_warning = $this->session->get('bank_warning');
                if ($bank_warning == true) {
                    $this->session->remove('bank_warning');
                    $this->smarty->assign("bank_warning", true);
                }

                if (isset($_POST['form_type'])) {
                    $this->smarty->assign("info", $_POST);
                } else {
                    $account['account_number'] = $account['account_no'];
                    $this->smarty->assign("info", $account);
                }
                break;
            case 'international':
                $type = 'international';
                $this->smarty->assign("currency_list", $this->common->getListValue('currency', 'is_active', 1));
                break;
            case 'company':
                $type = 'company';
                $entitytype = $this->model->getEntityType();
                $industrylist = $this->model->getIndustryType();
                if (isset($_POST['form_type'])) {
                    $this->smarty->assign("info", $_POST);
                }
                $this->smarty->assign("entitytype", $entitytype);
                $this->smarty->assign("industry_list", $industrylist);
                break;
            default:
                header('Location: /404', 404);
                die();
                break;
        }
        $this->smarty->assign("account", $account);
        if ($account['verification_status'] == 1 && $type == 'bank') {
            $type = 'verify';
        }
        $this->smarty->assign("verify", $subtype);
        if ($this->session->get('info_message')) {
            $this->smarty->assign("info_message", $this->session->get('info_message'));
            $this->session->remove('info_message');
        }
        $international_currency = $this->session->get('currency');
        $key = array_search('INR', $international_currency, true);
        if ($key !== false) {
            unset($international_currency[$key]);
        }
        $this->smarty->assign("int_currency", implode(', ', $international_currency));
        $this->view->js = array('template', 'register', 'dashboard');
        $this->view->entityselected = isset($_POST['type']) ? $_POST['type'] : '';
        $account_type = $this->session->get('account_type');
        if ($account_type == 2 && $this->env == 'PROD') {
            $this->view->show_full_story = 1;
        }
        $currency = $this->session->get('currency');
        if (in_array('INR', $currency)) {
            $this->smarty->assign("has_inr_currency", 1);
            if (count($currency) > 1) {
                $this->smarty->assign("has_other_currency", 1);
            }
        } else {
            $this->smarty->assign("has_other_currency", 1);
        }
        $this->smarty->assign("currency", $this->session->get('currency'));
        $this->view->hide_menu = 1;
        $this->view->hide_home_menu = 1;
        $this->session->set('valid_ajax', 'profile');
        $this->view->title = "Complete Registration";
        $this->view->header_file = ['profile'];
        $this->view->render('header/guest');
        $this->smarty->display(VIEW . 'merchant/register/complete_' . $type . '.tpl');
        $this->view->render('footer/profile');
    }

    function setting($type = null)
    {
        try {
            $this->hasRole(1, 12);
            $this->view->js = array('column_function');
            $merchant_id = $this->session->get('merchant_id');
            if ($type != null) {
                $invoice_numbers = $this->common->getListValue('merchant_auto_invoice_number', 'merchant_id', $merchant_id, 1, " and type=1");
                $estimate_inv_number = $this->common->getSingleValue('merchant_auto_invoice_number', 'merchant_id', $merchant_id, 1, ' and type=2');
                $vendor_code_number = $this->common->getSingleValue('merchant_auto_invoice_number', 'merchant_id', $merchant_id, 1, ' and type=7');
                $this->smarty->assign("invoice_numbers", $invoice_numbers);
                $this->smarty->assign("estimate_inv_number", $estimate_inv_number);
                $this->smarty->assign("vendor_code_number", $vendor_code_number);
            }
            $merchantSetting = $this->model->getMerchantSettings($merchant_id);
            if (empty($merchantSetting)) {
                SwipezLogger::error(__CLASS__, '[E019]Found empty merchant setting details for merchant id: ' . $merchant_id);
                $this->setGenericError();
            }

            $this->smarty->assign("setting", $merchantSetting);
            $this->smarty->assign("type", $type);
            $this->smarty->assign("reset_password", json_decode($merchantSetting['reset_password_source'], 1));

            if ($type == false) {
                $this->view->title = "Customer settings";
                $breadcumbs_array = array(
                    array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                    array('title' => 'Personal Preferences', 'url' => ''),
                    array('title' => $this->view->title, 'url' => '')
                );
            } else {
                $this->view->title = "Invoice sequence";
                //Breadcumbs array start
                $breadcumbs_array = array(
                    array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                    array('title' => 'Billing & Invoicing', 'url' => ''),
                    array('title' => $this->view->title, 'url' => '')
                );
            }
            $this->smarty->assign('title', $this->view->title);
            //Breadcumbs array start
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->selectedMenu = array(14, 58);
            $this->view->canonical = 'merchant/profile';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/profile/setting.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E016]Error while merchant setting initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function switchlogin($link)
    {
        try {
            $link = $this->encrypt->decode($link);
            $user_id = substr($link, 0, 10);
            require_once MODEL . '/LoginModel.php';
            $login = new LoginModel();
            $email = $this->common->getRowValue('email_id', 'user', 'user_id', $user_id);
            $token = $login->saveLoginToken($user_id, $email);
            header('Location: /login/token/' . $token);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E016+6]Error while Switch Login for user id [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function settings()
    {
        try {
            $this->view->selectedMenu = array(14);
            $this->view->title = 'Settings';
            $this->smarty->assign('title', $this->view->title);
            $this->smarty->assign('multi_currency', env('ENABLE_MULTI_CURRENCY'));

            //Breadcrumbs should remember context 
            $old_links = $this->session->get('breadcrumbs');
            if (!empty($old_links) && ($old_links['menu'] == 'collect_payments' || $old_links['menu'] == 'create_customer')) {
                $this->session->remove('breadcrumbs');
            }
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => $this->view->title, 'url' => '/merchant/profile/settings')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            $this->smarty->assign("user_role",$this->session->get('user_role') );
            //Breadcumbs array end

            $food_franchise_mids = explode(',', env('FOOD_FRANCHISE_NON_BRAND'));
            if (in_array($this->merchant_id, $food_franchise_mids)) {
                $this->smarty->assign("non_brand_food_franchise", true);
            }

            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/profile/landing.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E016+6]Error while Switch Login for user id [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function featuredenied()
    {
        try {
            $this->view->title = "Feature denied";
            $this->view->selectedMenu = 'profile';
            $this->view->canonical = 'merchant/profile';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/profile/denied.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E016]Error while merchant setting initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function currencysaved()
    {
        $this->common->genericupdate('merchant_setting', 'currency', json_encode($_POST['currency']), 'merchant_id', $this->merchant_id, $this->user_id);
        $this->session->set('currency', $_POST['currency']);
        header('Location:/merchant/integrations/stripe');
        exit();
    }

    function profilecompanysaved()
    {
        $this->hasRole(2, 100);
        if (empty($_POST)) {
            header('Location:/merchant/profile/complete');
            exit();
        }
        require CONTROLLER . 'merchant/Registervalidator.php';
        $validator = new Registervalidator($this->model);
        $validator->validateCompanySave();
        $hasErrors = $validator->fetchErrors();
        if ($hasErrors == false) {
            if ($this->merchant_id == '') {
                $result = $this->model->convertMerchant($this->user_id);
                $this->merchant_id = $result['merchant_id'];
                $this->session->set('merchant_id', $result['merchant_id']);
            }
            $_POST['gst_number'] = ($_POST['gst_available'] == 1) ? $_POST['gst_number'] : '';
            $this->model->updateProfileCompany($this->merchant_id, $_POST['entity_type'], $_POST['industry_type'], $_POST['pan'], $_POST['gst_number'], $this->user_id);
            $bank_det = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $this->merchant_id);
            $bank_det_id = $bank_det['bank_detail_id'];
            $_POST['gst_available'] = ($_POST['gst_available'] == 1) ? 1 : 0;
            if ($bank_det_id > 0) {
                $this->common->genericupdate('merchant_bank_detail', 'gst_available', $_POST['gst_available'], 'bank_detail_id', $bank_det_id, $this->user_id);
            } else {
                $bank_det_id = $this->model->saveBankDetail($this->merchant_id, '', '', '', '', 1, '', '', '', '', $_POST['gst_available']);
            }
            if ($_POST['gst_available'] == 1) {
                $this->common->genericupdate('merchant', 'company_name', $_POST['company_name'], 'merchant_id', $this->merchant_id, $this->user_id);
                $this->model->updateProfileUserContact($_POST['first_name'], $_POST['last_name'], $this->user_id);
                $this->model->updateProfileMerchantContact($this->merchant_id, $_POST['business_email'], $_POST['business_contact'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zipcode'], 'India', $this->user_id, $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zipcode'], 'India');
            }
            $this->common->genericupdate('merchant_setting', 'profile_step', 5, 'merchant_id', $this->merchant_id, $this->user_id);
            $this->session->set('profile_step', 5);
            $this->session->set('entity_type', 1);
            header('Location: /merchant/profile/complete/contact');
            die();
        } else {

            $this->smarty->assign("haserrors", $hasErrors);
            $this->complete('company');
        }
    }

    function profilecontactsaved()
    {
        $this->hasRole(2, 100);
        if (empty($_POST)) {
            header('Location:/merchant/profile/complete/contact');
            exit();
        }
        require CONTROLLER . 'merchant/Registervalidator.php';
        $validator = new Registervalidator($this->model);
        $validator->validateContactSave();
        $hasErrors = $validator->fetchErrors();
        if ($hasErrors == false) {
            $this->model->updateProfileUserContact($_POST['first_name'], $_POST['last_name'],  $this->user_id);
            $this->model->updateProfileMerchantContact($this->merchant_id, $_POST['business_email'], $_POST['business_contact'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $_POST['country'], $this->user_id, $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $_POST['country']);
            $this->common->genericupdate('merchant_setting', 'profile_step', '6', 'merchant_id', $this->merchant_id, $this->user_id);
            $this->session->set('profile_step', 6);
            $this->session->set('reg_city', 1);
            header('Location: /merchant/profile/complete/document');
            die();
        } else {
            $this->smarty->assign("haserrors", $hasErrors);
            $this->complete('contact');
        }
    }

    function pennyDrop()
    {
        $addr = $this->common->getMerchantProfile($this->merchant_id);
        $bank = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $this->merchant_id);
        $user = $this->common->getSingleValue('user', 'user_id', $this->user_id);

        $login_token = $this->model->getToken(env('SWIPEZ_MERCHANT_ID'));
        $json = $this->apisrequest(env('SWIPEZ_API_URL') . 'token', 'login_token=' . $login_token, array());
        $array = json_decode($json, 1);
        $api_token = $array['success']['token'];
        $header = array(
            "Authorization: Bearer " . $api_token
        );

        $amount = "1." . rand(0, 99);
        $this->common->genericupdate('merchant_bank_detail', 'penny_amount', $amount, 'merchant_id', $this->merchant_id, $this->user_id);

        $error_msg = '';
        $info['name'] = $bank['account_holder_name'];
        $info['email_id'] = $addr['business_email'];
        $info['mobile'] = $user['mobile_no'];
        $info['account_number'] = $bank['account_no'];
        $info['ifsc'] = $bank['ifsc_code'];
        $info['address'] = $addr['address'];
        $info['type'] = 'Merchant';
        $info['city'] = '';
        $info['state'] = '';
        $info['pincode'] = $addr['zipcode'];
        if ($this->env == 'PROD') {
            $json = json_encode($info);
            $beneficiary_id = $this->model->getBeneficiaryId(env('SWIPEZ_MERCHANT_ID'), $bank['account_no'], $bank['ifsc_code']);
            if ($beneficiary_id == false) {
                $result = $this->apisrequest(env('SWIPEZ_API_URL') . 'v1/beneficiary/save', $json, $header);
                $array = json_decode($result, 1);
            } else {
                $array['beneficiary_id'] = $beneficiary_id;
                $array['status'] = 1;
            }
            if ($array['status'] == 0 && $array['error'] == 'Entered bank Account is already registered') {
                $removed = $this->removeBeneficiaryId($bank['account_no'], $bank['ifsc_code']);
                if ($removed == true) {
                    $result = $this->apisrequest(env('SWIPEZ_API_URL') . 'v1/beneficiary/save', $json, $header);
                    $array = json_decode($result, 1);
                }
            }

            if ($array['status'] == 0) {
                $error_msg = $array['error'];
            } else {
                $info['beneficiary_id'] = $array['beneficiary_id'];
                $info['amount'] = $amount;
                $info['narrative'] = 'Penny drop';
                $json = json_encode($info);
                $result = $this->apisrequest(env('SWIPEZ_API_URL') . 'v1/beneficiary/transfer', $json, $header);
                $array = json_decode($result, 1);
                if ($array['status'] == 0) {
                    $error_msg = $array['error'];
                    $emailWrapper = new EmailWrapper();
                    $subject = "Penny drop failed";
                    $emailWrapper->cc = ['shuhaid.lambe@swipez.in'];
                    $body_message = "Company Name: " . $this->session->get('company_name') . " <br>Email: " . $this->session->get('email_id') . "<br>" . "Merchant id: " . $this->merchant_id . "<br> Error: " . $error_msg;
                    $emailWrapper->sendMail('paresh@swipez.in', "", $subject, $body_message, $body_message);
                    $pennystatus = 'failed';
                } else {
                    $pennystatus = 'sent';
                    $this->common->genericupdate('merchant_bank_detail', 'verification_status', 1, 'merchant_id', $this->merchant_id, $this->user_id);
                }
                MerchantRegistrationCrmPennyDrop::dispatch($this->merchant_id, $pennystatus)->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
            }
            return $error_msg;
        } else {
            $this->common->genericupdate('merchant_bank_detail', 'verification_status', 1, 'merchant_id', $this->merchant_id, $this->user_id);
        }
    }

    function removeBeneficiaryId($account_no, $ifsc_code)
    {
        try {
            $payout_keys = $this->common->getRowValue("`value`", 'merchant_config_data', 'merchant_id', env('SWIPEZ_MERCHANT_ID'), 0, " and `key`='PAYOUT_KEY_DETAILS'");
            if ($payout_keys != '') {
                $array = json_decode($payout_keys, 1);
                require_once UTIL . 'CashfreePayoutAPI.php';
                $payout = new CashfreePayoutAPI($array['key'], $array['secret'], $array['mode']);
                $array = $payout->getBeneficiary($account_no, $ifsc_code);
                if ($array['status'] == 1) {
                    if (isset($array['response']['data']['beneId'])) {
                        $beneficiary_id = $array['response']['data']['beneId'];
                        $row = $payout->removeBeneficiary($beneficiary_id);
                        return true;
                    }
                }
            }
            return false;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E017] Error while get Beneficiary Id. Merchant id [' . $this->merchant_id . ']' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update merchant profile
     */
    function completesaved()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/profile/complete/document');
                exit();
            }
            $hasErrors = FALSE;
            if ($hasErrors == false) {
                $verification_status = $this->common->getRowValue('verification_status', 'merchant_bank_detail', 'merchant_id', $this->merchant_id);
                if ($verification_status != 2) {        // if bank verification is not completed, $completed will be zero.
                    $complete = 0;
                } else {
                    $complete = $this->validateuploadform(1);
                }
                if ($complete == 1) {
                    //$desc = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $this->merchant_id);
                    MerchantRegistrationCrmDocument::dispatch($this->merchant_id, 'submitted')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));

                    $desc = DB::table('merchant_bank_detail')
                        ->select('account_holder_name', 'adhar_card', 'pan_card', 'cancelled_cheque', 'gst_certificate', 'address_proof', 'company_incorporation_certificate', 'partnership_deed')
                        ->where('merchant_id', $this->merchant_id)
                        ->get();

                    $title  = $this->session->get('company_name') . " uploaded PG docs";
                    $description = "Documents link that are uploaded \n " . http_build_query($desc, "", " \n ");

                    MerchantServiceAddToCRM::dispatch('PG_DOC', $this->merchant_id, $this->session->get('company_name'), $title, $description, 'web')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));

                    $this->common->genericupdate('merchant_setting', 'profile_step', '7', 'merchant_id', $this->merchant_id, $this->user_id);
                    $this->session->set('profile_step', 7);
                } else {
                    MerchantRegistrationCrmDocument::dispatch($this->merchant_id, 'partial')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
                }
                $this->documentsave(2, $complete);
                if ($verification_status != 2 && $_POST['submit_type'] == 'submit_document') {
                    $this->session->set('bank_warning', true);
                    header('Location:/merchant/profile/complete/bank');
                    exit();
                } else {
                    if (count($this->session->get('currency')) > 1) {
                        //$this->session->set('successMessage', 'Your profile has been updated successfully.');
                        header('Location:/merchant/profile/complete/bank');
                    } else {
                        //$this->session->set('successMessage', 'Your profile has been updated successfully.');
                        header('Location:/merchant/profile/complete/bank');
                    }

                    die();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->view->setError($hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->complete();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E017]Error while update profile Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Update merchant profile
     */
    function validateamount()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/profile/complete/bank');
                exit();
            }
            $detail = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $this->merchant_id, 1);

            $penny_amount = $detail['penny_amount'];
            if ($penny_amount != $_POST['penny_amount']) {
                MerchantRegistrationCrmPennyDrop::dispatch($this->merchant_id, 'invalid')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
                $error = 'You have entered an incorrect penny drop amount. Please get in touch with our support team via chat or drop us an email on support@swipez.in. Our support team will help you complete your bank account verification';
            } else {
                $this->common->genericupdate('merchant_bank_detail', 'verification_status', 2, 'merchant_id', $this->merchant_id, $this->user_id);
                $this->session->set('bank_verified', 1);
                if ($detail['gst_available'] == 1) {
                    $this->saveMarketPlaceVendor($this->merchant_id);
                } else {
                    $this->sendSupportNotification("Penny drop completed", 'INT_NOTIFY');
                }
                MerchantRegistrationCrmPennyDrop::dispatch($this->merchant_id, 'verified')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
                $this->common->genericupdate('merchant_setting', 'profile_step', '6', 'merchant_id', $this->merchant_id, $this->user_id);
                $this->session->set('profile_step', 6);
                $this->session->set('successMessage', 'Bank account information verified. Please proceed to complete your KYC information by clicking Next');
                header('Location:/merchant/profile/complete/bank');
                die();
            }
            $this->smarty->assign("error", $error);
            $this->complete('bank');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E017]Error while update profile Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function skippennydrop()
    {
        $this->common->genericupdate('merchant_setting', 'profile_step', '7', 'merchant_id', $this->merchant_id, $this->user_id);
        $this->session->set('profile_step', 7);
        $this->session->set('info_message', 'Your bank account verification process is incomplete. Our support team will get in touch with you and complete the process.');
        //$this->sendSupportNotification("Penny drop incomplete",'INT_NOTIFY');
        MerchantRegistrationCrmPennyDrop::dispatch($this->merchant_id, 'skip')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));

        $title  = $this->session->get('company_name') . " penny drop incomplete";
        $description = "Merchant Descripton";
        MerchantServiceAddToCRM::dispatch('PENNY_DROP', $this->merchant_id, $this->session->get('company_name'), $title, $description, 'web')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));

        if (count($this->session->get('currency')) > 1) {
            //$this->session->set('successMessage', 'Your profile has been updated successfully.');
            header('Location:/merchant/profile/complete/international');
        } else {
            //$this->session->set('successMessage', 'Your profile has been updated successfully.');
            header('Location:/merchant/profile/complete/document');
        }

        exit();
    }

    /**
     * Update merchant profile
     */
    function banksaved()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/profile/complete/bank');
                exit();
            }
            $hasErrors = FALSE;
            $_POST['online_payment'] = 1;
            if ($hasErrors == false) {
                $this->common->genericupdate('merchant_setting', 'online_payment', $_POST['online_payment'], 'merchant_id', $this->merchant_id);
                if ($_POST['online_payment'] != 1) {
                    $this->common->genericupdate('merchant_setting', 'profile_step', '7', 'merchant_id', $this->merchant_id, $this->user_id);
                    $this->session->set('profile_step', 7);
                    //$this->common->genericupdate('merchant', 'is_legal_complete', '1', 'merchant_id', $this->merchant_id, $this->user_id);
                    //$this->session->set('is_legal', 1);
                    header('Location:/merchant/dashboard');
                    exit();
                }
                $_POST['account_type'] = ($_POST['account_type'] > 0) ? $_POST['account_type'] : 0;
                $bank_det_id = $this->common->getRowValue('bank_detail_id', 'merchant_bank_detail', 'merchant_id', $this->merchant_id, 1);
                if ($bank_det_id > 0) {
                    $this->model->updateBankDetail($bank_det_id, $this->merchant_id, $_POST['account_number'], $_POST['account_holder_name'], $_POST['ifsc_code'], $_POST['bank_name'], $_POST['account_type']);
                } else {
                    $this->model->saveBankDetail($this->merchant_id, $_POST['account_number'], $_POST['account_holder_name'], $_POST['ifsc_code'], $_POST['bank_name'], $_POST['account_type'], '', '', '', '', 0);
                }
                if (isset($_POST['btnskip'])) {
                    if (count($this->session->get('currency')) > 1) {
                        header('Location:/merchant/profile/complete/international');
                    } else {
                        header('Location:/merchant/dashboard');
                    }
                    die();
                }
                $error = $this->pennyDrop();
                if ($error != '') {
                    $this->smarty->assign("error", $error);
                    $this->complete('bank');
                } else {
                    header('Location:/merchant/profile/complete/bank/verify');
                }
            } else {
                dd($hasErrors);
                $this->smarty->assign("haserrors", $hasErrors);
                $this->view->setError($hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->complete();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E017]Error while update profile Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Update merchant profile
     */
    function update()
    {
        try {
            $this->hasRole(2, 100);
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/profile');
            }
            require CONTROLLER . 'merchant/Registervalidator.php';
            $validator = new Registervalidator($this->model);
            $validator->validateUpdate();
            $hasErrors = $validator->fetchErrors();

            if (isset($_POST['website']) && $_POST['website'] != '') {
                $this->model->updateMerchantWebsite($this->merchant_id, $_POST['website']);
            }
            if ($_FILES["doc_adhar_card"]['error'] == 0 && isset($_FILES["doc_adhar_card"])) {
                $doc_adhar_card = $this->model->uploadDocument($_FILES["doc_adhar_card"], $this->merchant_id, 'Adhar_card');
                $this->model->updateMerchantDoc($this->merchant_id, 'adhar_card', $doc_adhar_card);
            }
            if ($_FILES["doc_pan_card"]['error'] == 0 && isset($_FILES["doc_pan_card"])) {
                $doc_pan_card = $this->model->uploadDocument($_FILES["doc_pan_card"], $this->merchant_id, 'Pan_card');
                $this->model->updateMerchantDoc($this->merchant_id, 'pan_card', $doc_pan_card);
            }
            if ($_FILES["doc_cancelled_cheque"]['error'] == 0 && isset($_FILES["doc_cancelled_cheque"])) {
                $doc_cancelled_cheque = $this->model->uploadDocument($_FILES["doc_cancelled_cheque"], $this->merchant_id, 'Cancelled_cheque');
                $this->model->updateMerchantDoc($this->merchant_id, 'cancelled_cheque', $doc_cancelled_cheque);
            }
            if ($_FILES["doc_gst_cer"]['error'] == 0 && isset($_FILES["doc_gst_cer"])) {
                $gst_cer = $this->model->uploadDocument($_FILES["doc_gst_cer"], $this->merchant_id, 'Gst_certificate');
                $this->model->updateMerchantDoc($this->merchant_id, 'gst_certificate', $gst_cer);
            }
            if ($_FILES["biz_reg_proof"]['error'] == 0 && isset($_FILES["biz_reg_proof"])) {
                $biz_reg_proof = $this->model->uploadDocument($_FILES["biz_reg_proof"], $this->merchant_id, 'biz_reg_proof');
                $this->model->updateMerchantDoc($this->merchant_id, 'business_reg_proof', $biz_reg_proof);
            }
            if ($_FILES["company_certificate"]['error'] == 0 && isset($_FILES["company_certificate"])) {
                $company_certificate = $this->model->uploadDocument($_FILES["company_certificate"], $this->merchant_id, 'company_certificate');
                $this->model->updateMerchantDoc($this->merchant_id, 'company_incorporation_certificate', $company_certificate);
            }
            if ($_FILES["company_aoa"]['error'] == 0 && isset($_FILES["company_aoa"])) {
                $company_aoa = $this->model->uploadDocument($_FILES["company_aoa"], $this->merchant_id, 'company_aoa');
                $this->model->updateMerchantDoc($this->merchant_id, 'company_aoa', $company_aoa);
            }
            if ($_FILES["company_moa"]['error'] == 0 && isset($_FILES["company_moa"])) {
                $company_moa = $this->model->uploadDocument($_FILES["company_moa"], $this->merchant_id, 'company_moa');
                $this->model->updateMerchantDoc($this->merchant_id, 'company_moa', $company_moa);
            }

            if ($hasErrors == false) {
                $business_contact = ($_POST['business_contact'] > 0) ? $_POST['business_contact'] : 0;
                $phone_on_invoice = ($_POST['phone_on_invoice'] > 0) ? $_POST['phone_on_invoice'] : 0;



                $result = $this->model->pesonalUpdate($this->user_id, $this->merchant_id, $_POST['email'], $_POST['first_name'], $_POST['last_name'], $_POST['mob_country_code'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['country'], $_POST['zip'], $_POST['type'], $_POST['industry_type'], $_POST['company_name'], $_POST['registration_no'], $_POST['gst_number'], $_POST['cin_no'], $_POST['pan'], $_POST['tan'], $_POST['current_address'], $_POST['current_address2'], $_POST['current_city'], $_POST['current_state'], $_POST['current_country'], $_POST['current_zip'], $_POST['business_email'], $_POST['country_code'], $business_contact, $phone_on_invoice);
                if ($result == 'success') {
                    $this->session->set('display_name', ucfirst($_POST['first_name']));
                    $this->session->set('successMessage', 'Your profile has been updated successfully.');
                    header('Location:/merchant/profile');
                    // $this->documentsave(1);
                    die();
                } else {
                    SwipezLogger::error(__CLASS__, '[E017-a]Error while update profile Error: for merchant [' . $merchant_id . ']' . $result);
                    $this->setGenericError();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->index();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E017]Error while update profile Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function validateuploadform($req = 0, $return_type = 0)
    {
        $bank_det = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $this->merchant_id);
        $detail = json_decode($_POST['detail'], 1);
        if ($bank_det['cancelled_cheque'] == '' || $bank_det['cancelled_cheque'] == null) {
            $bank_det['cancelled_cheque'] = $detail["cheque"];
        }
        if ($bank_det['adhar_card'] == '' || $bank_det['adhar_card'] == null) {
            $bank_det['adhar_card'] = $detail['adhar_card'];
        }

        if ($bank_det['address_proof'] == '' || $bank_det['address_proof'] == null) {
            $bank_det['address_proof'] = $detail['address_proof'];
        }

        if ($bank_det['company_incorporation_certificate'] == '' || $bank_det['company_incorporation_certificate'] == null) {
            $bank_det['company_incorporation_certificate'] = $detail['comp_cer'];
        }

        if ($bank_det['gst_certificate'] == '' || $bank_det['gst_certificate'] == null) {
            $bank_det['gst_certificate'] = $detail["gst"];
        }

        if ($bank_det['business_reg_proof'] == '' || $bank_det['business_reg_proof'] == null) {
            $bank_det['business_reg_proof'] = $detail['biz_reg'];
        }
        if ($bank_det['pan_card'] == '' || $bank_det['pan_card'] == null) {
            $bank_det['pan_card'] = $detail['pan_card'];
        }

        if ($bank_det['partner_pan_card'] == '' || $bank_det['partner_pan_card'] == null) {
            $bank_det['partner_pan_card'] = $detail['partner_pancard'];
        }

        if ($bank_det['partnership_deed'] == '' || $bank_det['partnership_deed'] == null) {
            $bank_det['partnership_deed'] = $detail['partner_deed'];
        }

        if ($bank_det['company_moa'] == '' || $bank_det['company_moa'] == null) {
            $bank_det['company_moa'] = $detail['company_moa'];
        }

        if ($bank_det['company_aoa'] == '' || $bank_det['company_aoa'] == null) {
            $bank_det['company_aoa'] = $detail['company_aoa'];
        }
        $status = $this->completeDocument($bank_det, $_POST['biz_type']);
        if ($req == 0) {
            if ($_POST['confirm'] != 1) {
                $status = 0;
                $hasErrors['Confirm'][] = 'Confirm';
                $hasErrors['Confirm'][] = 'Please check confirm box to submit the form';
            }
            $array['status'] = $status;
            $error = '';
            foreach ($hasErrors as $er) {
                $error .= '<b>' . $er[0] . ':</b> ' . $er[1] . '<br>';
            }
            $array['error'] = $error;
            echo json_encode($array);
        } else {
            if ($return_type == 0) {
                return $status;
            } else {
                echo $status;
            }
        }
    }

    function completeDocument($bank_det, $biz_type)
    {
        $complete = 0;
        switch ($biz_type) {
            case 2:
                if ($bank_det['cancelled_cheque'] != '' && $bank_det['address_proof'] != '' && $bank_det['company_incorporation_certificate'] != '' && ($bank_det['gst_certificate'] != '' || $bank_det['business_reg_proof'] != '')) {
                    $complete = 1;
                }
                break;
            case 6:
                if ($bank_det['cancelled_cheque'] != '' && $bank_det['adhar_card'] != '' && $bank_det['pan_card'] != '' && ($bank_det['gst_certificate'] != '' || $bank_det['business_reg_proof'] != '')) {
                    $complete = 1;
                }
                break;
            case 4:
                if ($bank_det['cancelled_cheque'] != '' && $bank_det['adhar_card'] != '' && $bank_det['pan_card'] != '' && ($bank_det['gst_certificate'] != '' || $bank_det['business_reg_proof'] != '')) {
                    $complete = 1;
                }
                break;
            case 3:
                if ($bank_det['cancelled_cheque'] != '' && $bank_det['address_proof'] != '' && $bank_det['partner_pan_card'] != '' && $bank_det['partnership_deed'] != '' && ($bank_det['gst_certificate'] != '' || $bank_det['business_reg_proof'] != '')) {
                    $complete = 1;
                }
                break;
        }
        return $complete;
    }

    function updatesetting()
    {
        try {
            $this->hasRole(2, 12);
            $merchant_id = $this->session->get('merchant_id');

            if (empty($_POST)) {
                header('Location:/merchant/profile/setting');
            }
            if ($_POST['type'] == '') {
                $type = '';
                $show_add = ($_POST['show_ad'] > 0) ? $_POST['show_ad'] : 0;
                $auto_approve = ($_POST['auto_approve'] > 0) ? $_POST['auto_approve'] : 0;
                $is_reminder = ($_POST['is_reminder'] > 0) ? $_POST['is_reminder'] : 0;
                $password_validation = ($_POST['password_validation'] > 0) ? $_POST['password_validation'] : 0;
                $result = $this->model->settingUpdate($this->session->get('userid'), $merchant_id, $show_add, $auto_approve, $is_reminder, $password_validation);
            } else {
                $this->common->genericupdate('merchant_auto_invoice_number', 'prefix', $_POST['estimate_prefix'], 'merchant_id', $this->merchant_id, null, " and type=2");

                if (isset($_POST['subscript'])) {
                    $subscript = $_POST['subscript'];
                    $lastNumber = $_POST['lastnumber'];
                    $int = 0;
                    foreach ($subscript as $script) {
                        $res = $this->model->existPrefix($merchant_id, $subscript[$int]);
                        if ($res == FALSE) {
                            $this->model->saveInvoiceNumber($this->session->get('userid'), $merchant_id, $subscript[$int], $lastNumber[$int]);
                        } else {
                            $hasErrors['Prefix'][0] = 'Invoice prefix';
                            $hasErrors['Prefix'][1] = 'Invoice prefix already exist';
                        }
                        $int++;
                    }
                }
                $type = '/sequence';
            }

            if ($hasErrors == true) {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->setting($type);
                die();
            }
            $this->session->set('successMessage', 'Updates made to your setting have been saved.');
            header('Location:/merchant/profile/setting' . $type);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E017]Error while update merchant setting Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function updateautonumber()
    {
        $this->hasRole(2, 12);
        $merchant_id = $this->session->get('merchant_id');
        if (empty($_POST)) {
            header('Location:/merchant/profile/setting');
        }
        $this->model->updateInvoiceNumber($this->session->get('userid'), $merchant_id, $_POST['subscript'], $_POST['last_number'], $_POST['auto_invoice_id']);
        $this->session->set('successMessage', 'Updates made to your setting have been saved.');
        header('Location:/merchant/profile/setting/sequence');
    }

    function addcurrency()
    {
        $currency = $this->session->get('currency');
        $currency[] = $_POST['currency'];
        $this->session->set('currency', $currency);
        $this->common->genericupdate('merchant_setting', 'currency', json_encode($currency), 'merchant_id', $this->merchant_id, $this->user_id);
        $this->session->setFlash('successMessage', 'Currency has been added');
        header('Location: /merchant/profile/currency');
        die();
    }

    function delete($id, $type = null)
    {
        $this->hasRole(3, 12);
        $merchant_id = $this->session->get('merchant_id');
        if ($type == 'gst') {
            $id = $this->encrypt->decode($id);
            if (is_numeric($id)) {
                $this->common->genericupdate('merchant_billing_profile', 'is_active', 0, 'id', $id, $this->user_id);
                $this->session->setFlash('successMessage', 'Billing profile has been deactivated');
                header('Location: /merchant/profile/gstprofile');
                die();
            }
        } elseif ($type == 'currency') {
            $currency = $this->session->get('currency');
            $key = array_search($id, $currency);

            if ($key !== false) {
                array_splice($currency, $key, 1);
            }

            $this->session->set('currency', $currency);
            $this->common->genericupdate('merchant_setting', 'currency', json_encode($currency), 'merchant_id', $merchant_id, $this->user_id);
            $this->session->setFlash('successMessage', 'Currency has been deactivated');
            header('Location: /merchant/profile/currency');
            die();
        } else {
            $this->model->deleteInvoiceNumber($this->session->get('userid'), $merchant_id, $id);
            $this->session->set('successMessage', 'Updates made to your setting have been saved.');
            header('Location:/merchant/profile/setting/sequence');
        }
    }

    function changeplan($link)
    {
        $this->hasRole(2, 100);
        $plan_id = $this->encrypt->decode($link);
        $merchant_id = $this->merchant_id;
        $this->model->updatePlan($plan_id, $merchant_id, $this->user_id);
        $this->session->set('merchant_plan', $plan_id);
        if ($this->env == 'PROD') {
            $subject = "Merchant package changed";
            $body_message = "Company Name: " . $this->session->get('company_name') . " <br>Email: " . $this->session->get('email_id') . "<br>" . "Merchant id: " . $this->merchant_id . "Plan ID: " . $plan_id;
            SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
        }
        $this->session->set('successMessage', 'Package has been changed successfully');
        header('Location:/merchant/profile/packagedetails');
    }

    function activate($link, $response = null)
    {
        $this->hasRole(2, 100);
        $service_id = $this->encrypt->decode($link);
        $det = $this->common->getSingleValue('swipez_services', 'service_id', $service_id);
        $service_name = $det['title'];
        $exist = $this->common->getRowValue('id', 'merchant_active_apps', 'service_id', $service_id, 1, " and user_id='" . $this->user_id . "'");
        if ($exist == false) {
            //$subject = "Service activation initiated";
            //$body_message = "Company Name: " . $this->session->get('company_name') . " <br>Service name: " . $title . "<br>" . "User id: " . $this->user_id . "<br>Service ID: " . $service_id;
            //SupportTeamNotification::dispatch($subject, $body_message,'INT_NOTIFY')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
            //MerchantRegistrationCrmService::dispatch($this->merchant_id)->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
            $title  = $this->session->get('company_name') . " applied for " . $service_name;
            $description = "Service id : " . $service_id;
            MerchantServiceAddToCRM::dispatch('BENEFIT', $this->merchant_id, $this->session->get('company_name'), $title, $description, 'web')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));

            $this->model->saveServiceRequest($service_id, $det['merge_menu'], $this->merchant_id, $this->user_id);
        }
        if (!empty($response) && $response == 'response') {
            $res['status'] = 1;
            echo json_encode($res);
        } else {
            $this->session->set('successMessage', 'Service activation request has been sent. Our support team will get in touch with you shortly.');
            header('Location:/merchant/dashboard/home');
        }
    }

    function packagedetails()
    {
        try {
            $this->hasRole(2, 100);
            $requestlist = $this->model->getPackageTransaction($this->merchant_id);
            $account = $this->common->getSingleValue('account', 'merchant_id', $this->merchant_id, 1);
            if ($account['custom_package_id'] > 0) {
                $package = $this->common->getSingleValue('custom_package', 'package_id', $account['custom_package_id']);
                $package['package_name'] = 'Custom Package';
            } else {
                $package = $this->common->getSingleValue('package', 'package_id', $account['package_id']);
            }

            $setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
            $row_count = $this->common->querysingle("select count(payment_request_id) as pcount from payment_request where merchant_id='" . $this->merchant_id . "' and parent_request_id <> '0' and created_date>'" . $account['start_date'] . "'");
            $account_sum['total_invoices'] = $row_count['pcount'];

            $sum_count = $this->common->querysingle("SELECT sum(license_bought) as bought,sum(license_available) as available from merchant_addon where is_active=1 and start_date<=NOW() and end_date>=NOW() and package_id=7 and merchant_id='" . $this->merchant_id . "'");
            $account_sum['sms_bought'] = $sum_count['bought'];
            $account_sum['sms_consume'] = $sum_count['bought'] - $sum_count['available'];


            $int = 0;
            foreach ($requestlist as $item) {
                $requestlist[$int]['link'] = $this->encrypt->encode($item['package_transaction_id']);

                if (substr($item['pg_ref_no'], 0, 1) == 'H') {
                    $details = $this->common->getSingleValue('offline_response', 'offline_response_id', $item['pg_ref_no']);
                } else if (substr($item['pg_ref_no'], 0, 1) == 'T') {
                    $details = $this->common->getSingleValue('payment_transaction', 'pay_transaction_id', $item['pg_ref_no']);
                }
                if (!empty($details)) {
                    $requestlist[$int]['payment_request_id'] = $details['payment_request_id'];
                    $requestlist[$int]['invoice_link'] = $this->encrypt->encode($details['payment_request_id']);
                }
                $int++;
            }
            $this->smarty->assign("requestlist", $requestlist);
            $this->view->selectedMenu = 'package_details';
            $this->smarty->assign("account", $account);
            $this->smarty->assign("package", $package);
            $this->smarty->assign("sms_plan_link", $this->encrypt->encode(7));
            $this->smarty->assign("setting", $setting);
            $this->smarty->assign("account_sum", $account_sum);
            $this->smarty->assign("requestlist", $requestlist);
            $this->view->title = 'Package details';
            $this->smarty->assign('title', $this->view->title);
            $breadcumbs_array = array(
                array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                array('title' => 'Company settings', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/profile/package_detail.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while payment request list initiate Error: for merchant [' . $this->merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function packagereceipt($link, $pdf = null)
    {
        try {
            $this->hasRole(2, 100);
            $transaction_id = $this->encrypt->decode($link);
            $trans = $this->common->getSingleValue('package_transaction', 'package_transaction_id', $transaction_id);
            $trans['payment_mode'] = 'Online payment';
            if (substr($trans['pg_ref_no'], 0, 1) == 'H') {
                $details = $this->common->getSingleValue('offline_response', 'offline_response_id', $trans['pg_ref_no']);
                switch ($details['offline_response_type']) {
                    case 2:
                        $trans['payment_mode'] = 'Cheque';
                        break;
                    case 3:
                        $trans['payment_mode'] = 'Cash';
                        break;
                }
            } else if (substr($trans['pg_ref_no'], 0, 1) == 'T') {
                $details = $this->common->getSingleValue('payment_transaction', 'pay_transaction_id', $trans['pg_ref_no']);
            }
            if (!empty($details)) {
                $trans['invoice_number'] = $this->common->getRowValue('invoice_number', 'payment_request', 'payment_request_id', $details['payment_request_id']);
            }
            $trans['email_id'] = $this->session->get('email_id');
            $trans['company_name'] = $this->session->get('company_name');
            $this->smarty->assign("info", $trans);
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/profile/receipt.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while payment request list initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function saveMarketPlaceVendor($merchant_id)
    {
        $merchant = $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
        $bank = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $merchant_id);
        $user = $this->common->getSingleValue('user', 'user_id', $merchant['user_id']);
        $_POST['vendor_name'] = $merchant['company_name'];
        $_POST['email'] = $user['email_id'];
        $_POST['mobile'] = $user['mobile_no'];
        $merchant_addr = $this->common->getMerchantProfile($merchant_id);
        $_POST['address'] = ($merchant_addr['address'] != '') ? $merchant_addr['address'] : 'Creaticity Mall, Shastrinagar, Yerawada';
        $_POST['city'] = 'Pune';
        $_POST['state'] = ($merchant_addr['state'] != '') ? $merchant_addr['state'] : 'Maharashtra';
        $_POST['zipcode'] = ($merchant_addr['zipcode'] != '') ? $merchant_addr['zipcode'] : '411006';
        $_POST['account_number'] = $bank['account_no'];
        $_POST['account_holder_name'] = $bank['account_holder_name'];
        $_POST['ifsc_code'] = $bank['ifsc_code'];
        $_POST['gst'] = '';
        $_POST['pan'] = '';
        $_POST['adhar_card'] = '';
        $_POST['online_settlement'] = 1;
        $_POST['account_type'] = 'Saving';
        $_POST['commision_type'] = '1';
        $_POST['commision_percent'] = '100';
        $_POST['commision_amount'] = '0';
        $_POST['settlement_type'] = '2';
        //require_once CONTROLLER . 'merchant/Vendor.php';
        require_once MODEL . 'merchant/VendorModel.php';
        $vendormodel = new VendorModel();
        //$vendor = new Vendor(true);
        $swipez_merchant_id = env('SWIPEZ_MERCHANT_ID');
        //$data = $vendor->savePGVendor($swipez_merchant_id,'');
        // SwipezLogger::debug(__CLASS__, 'Swipez vendor response Merchant id: ' . $merchant_id . json_encode($data));
        // if ($data['status'] == 1) {
        $pg_vendor_id = '';
        $vendor_id = $vendormodel->createVendor($pg_vendor_id, $swipez_merchant_id, $_POST, 1, $merchant['user_id']);
        $exist_event = $this->common->getRowValue('id', 'merchant_active_apps', 'merchant_id', $this->merchant_id, 1, ' and service_id=4');
        if ($exist_event == false) {
            $tdr = 2.1;
        } else {
            $tdr = 4;
        }
        $vendormodel->saveFeeDetail($merchant_id, $vendor_id, env('SWIPEZ_PG_ID'), $tdr);
        //  }
        return $vendor_id;
    }

    function documentsave($profile_submit = null, $completed = 0)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $bank_det = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $this->merchant_id);
            $bank_det_id = $bank_det['bank_detail_id'];
            if ($bank_det_id > 0) {
            } else {

                $bank_det_id = $this->model->saveBankDetail($this->merchant_id, '', '', '', '', 1, '', '', '', '', 0);
            }

            if (isset($_FILES['address_prrof'])) {
                $int = 0;
                foreach ($_FILES['address_prrof']['name'] as $key => $val) {
                    if ($val != '') {
                        $address_proof[$int] = array('name' => $_FILES['address_prrof']['name'][$key], 'type' => $_FILES['address_prrof']['type'][$key], 'tmp_name' => $_FILES['address_prrof']['tmp_name'][$key], 'error' => $_FILES['address_prrof']['error'][$key], 'size' => $_FILES['address_prrof']['size'][$key]);
                        $int++;
                    }
                }
            }

            if (isset($_FILES['partner_pan_card'])) {
                $int = 0;
                foreach ($_FILES['partner_pan_card']['name'] as $key => $val) {
                    if ($val != '') {
                        $partner_pan_card[$int] = array('name' => $_FILES['partner_pan_card']['name'][$key], 'type' => $_FILES['partner_pan_card']['type'][$key], 'tmp_name' => $_FILES['partner_pan_card']['tmp_name'][$key], 'error' => $_FILES['partner_pan_card']['error'][$key], 'size' => $_FILES['partner_pan_card']['size'][$key]);
                        $int++;
                    }
                }
            }
            $doc_cancelled_cheque = '';
            $gst_cer = '';
            $company_moa = '';
            $company_aoa = '';
            $company_certificate = '';
            $doc_pan_card = '';
            $partnership_deed = '';
            $doc_adhar_card = '';

            if ($_FILES["biz_reg_proof"]['error'] == 0 && $_FILES["biz_reg_proof"]['name'] != '') {
                $biz_reg_proof = $this->model->uploadDocument($_FILES["biz_reg_proof"], $this->merchant_id, 'biz_reg_proof');
                $this->common->genericupdate('merchant_bank_detail', 'business_reg_proof', $biz_reg_proof, 'bank_detail_id', $bank_det_id, $this->user_id);
            }

            if ($_FILES["doc_cancelled_cheque"]['error'] == 0 && $_FILES["doc_cancelled_cheque"]['name'] != '') {
                $doc_cancelled_cheque = $this->model->uploadDocument($_FILES["doc_cancelled_cheque"], $this->merchant_id, 'Cancelled_cheque');
                $this->common->genericupdate('merchant_bank_detail', 'cancelled_cheque', $doc_cancelled_cheque, 'bank_detail_id', $bank_det_id, $this->user_id);
            }

            if ($_FILES["doc_gst_cer"]['error'] == 0 && $_FILES["doc_gst_cer"]['name'] != '') {
                $gst_cer = $this->model->uploadDocument($_FILES["doc_gst_cer"], $this->merchant_id, 'Gst_certificate');
                $this->common->genericupdate('merchant_bank_detail', 'gst_certificate', $gst_cer, 'bank_detail_id', $bank_det_id, $this->user_id);
            }


            if ($_FILES["company_certificate"]['error'] == 0 && $_FILES["company_certificate"]['name'] != '') {
                $company_certificate = $this->model->uploadDocument($_FILES["company_certificate"], $this->merchant_id, 'company_certificate');
                $this->common->genericupdate('merchant_bank_detail', 'company_incorporation_certificate', $company_certificate, 'bank_detail_id', $bank_det_id, $this->user_id);
            }

            if ($_FILES["company_moa"]['error'] == 0 && $_FILES["company_moa"]['name'] != '') {
                $company_moa = $this->model->uploadDocument($_FILES["company_moa"], $this->merchant_id, 'company_moa');
                $this->common->genericupdate('merchant_bank_detail', 'company_moa', $company_moa, 'bank_detail_id', $bank_det_id, $this->user_id);
            }

            if ($_FILES["company_aoa"]['error'] == 0 && $_FILES["company_aoa"]['name'] != '') {
                $company_aoa = $this->model->uploadDocument($_FILES["company_aoa"], $this->merchant_id, 'company_aoa');
                $this->common->genericupdate('merchant_bank_detail', 'company_aoa', $company_aoa, 'bank_detail_id', $bank_det_id, $this->user_id);
            }

            if ($_FILES["doc_pan_card"]['error'] == 0 && $_FILES["doc_pan_card"]['name'] != '') {
                $doc_pan_card = $this->model->uploadDocument($_FILES["doc_pan_card"], $this->merchant_id, 'Pan_card');
                $this->common->genericupdate('merchant_bank_detail', 'pan_card', $doc_pan_card, 'bank_detail_id', $bank_det_id, $this->user_id);
            }
            if ($_FILES["partnership_deed"]['error'] == 0 && $_FILES["partnership_deed"]['name'] != '') {
                $partnership_deed = $this->model->uploadDocument($_FILES["partnership_deed"], $this->merchant_id, 'partnership_deed');
                $this->common->genericupdate('merchant_bank_detail', 'partnership_deed', $partnership_deed, 'bank_detail_id', $bank_det_id, $this->user_id);
            }

            if ($_FILES["doc_adhar_card"]['error'] == 0 && $_FILES["doc_adhar_card"]['name'] != '') {
                $doc_adhar_card = $this->model->uploadDocument($_FILES["doc_adhar_card"], $this->merchant_id, 'Adhar_card');
                $this->common->genericupdate('merchant_bank_detail', 'adhar_card', $doc_adhar_card, 'bank_detail_id', $bank_det_id, $this->user_id);
            }

            if (isset($address_proof)) {
                foreach ($address_proof as $docs) {
                    $doc_address_proof[] = $this->model->uploadDocument($docs, $this->merchant_id, 'Address_Proof');
                }
                if ($bank_det['address_proof'] != '') {
                    $arry = json_decode($bank_det['$doc_address_proof'], 1);
                    $all = array_merge($arry, $doc_address_proof);
                    $this->common->genericupdate('merchant_bank_detail', 'address_proof', json_encode($all), 'bank_detail_id', $bank_det_id, $this->user_id);
                } else {
                    $this->common->genericupdate('merchant_bank_detail', 'address_proof', json_encode($doc_address_proof), 'bank_detail_id', $bank_det_id, $this->user_id);
                }
            }

            if (isset($partner_pan_card)) {
                foreach ($partner_pan_card as $docs) {
                    $doc_partner_pan_card[] = $this->model->uploadDocument($docs, $this->merchant_id, 'partner_pan_card');
                }

                if ($bank_det['partner_pan_card'] != '') {
                    $arry = json_decode($bank_det['partner_pan_card'], 1);
                    $all = array_merge($arry, $doc_partner_pan_card);
                    $this->common->genericupdate('merchant_bank_detail', 'partner_pan_card', json_encode($all), 'bank_detail_id', $bank_det_id, $this->user_id);
                } else {
                    $this->common->genericupdate('merchant_bank_detail', 'partner_pan_card', json_encode($doc_partner_pan_card), 'bank_detail_id', $bank_det_id, $this->user_id);
                }
            }

            if ($completed == 1) {
                $this->common->genericupdate('merchant_setting', 'disable_cashfree_doc', 1, 'merchant_id', $this->merchant_id, $this->user_id);
                $this->common->genericupdate('merchant', 'is_legal_complete', 1, 'merchant_id', $this->merchant_id, $this->user_id);
                $this->session->set('is_legal', 1);
                $this->common->genericupdate('user', 'user_status', 15, 'user_id', $this->user_id);
                $this->session->set('document_upload', false);
                $this->sendSupportNotification("Cashfree documents uploaded", 'SUPPORT');
            } elseif ($profile_submit == 1) {
                $this->session->set('successMessage', 'Your profile has been updated successfully.');
                header('Location:/merchant/profile/complete/bank');
                die();
            } else {
                return true;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E017]Error while update profile Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function sendSupportNotification($subject, $type = 'SUPPORT')
    {
        $body_message = "Company Name: " . $this->session->get('company_name') .
            "<br> Email: " . $this->session->get('email_id') .
            "<br>" . "Merchant id: " . $this->merchant_id;
        SupportTeamNotification::dispatch($subject, $body_message, $type)->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
    }

    /**
     * Upload logo and banner images
     */
    public function uploadBase64Image($file)
    {
        try {

            $filename = 'uploads/images/landing/' . $this->merchant_id;
            if (file_exists($filename)) {
            } else {
                mkdir($filename, 0755);
            }

            $newname = 'uploads/images/landing/' . $this->merchant_id . '/' . time() . '.png';
            //Check if the file with the same name is already exists on the server
            if (file_exists($newname)) {
                unlink($newname);
            }
            //Attempt to move the uploaded file to it's new place
            if ((file_put_contents($newname, $file))) {
                return '/' . $newname;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E136]Error while uploading template logo Error: ' . $e->getMessage());
        }
    }

    function digitalsignature($iframe = null)
    {
        $signature_data = $this->common->getRowValue('value', 'merchant_config_data', 'merchant_id', $this->merchant_id, 1, " and `key`='DIGITAL_SIGNATURE' ");
        if ($signature_data == true) {
            $sig_data = json_decode($signature_data, 1);
            $is_exist = true;
        } else {
            $sig_data = array();
            $is_exist = false;
        }
        $fonts = $this->common->getListValue('config', 'config_type', 'signature_font');
        if (!empty($_POST)) {
            if (isset($_POST['file'])) {
                if ($_FILES['signature_file']['error'] == 0) {
                    require_once MODEL . 'merchant/CompanyprofileModel.php';
                    $profile = new CompanyprofileModel();
                    $file_name = $profile->uploadImage($_FILES['signature_file'], $_FILES['signature_file']['name'], $this->merchant_id);
                    $sig_data['signature_file'] = $file_name;
                    $sig_data['type'] = 'file';
                    $sig_data['align'] = $_POST['img_align'];
                }
            }

            if (isset($_POST['font'])) {
                $sig_data['type'] = 'font';
                $sig_data['font_size'] = $_POST['font_size'];
                $sig_data['font_value'] = $_POST['font_name'];
                $sig_data['name'] = $_POST['name'];
                $sig_data['font_file'] = $fonts[$_POST['font_name'] - 1]['description'];
                $sig_data['font_name'] = $fonts[$_POST['font_name'] - 1]['config_value'];
                $sig_data['align'] = $_POST['align'];
                $img = $_POST['font_image']; // Your data 'data:image/png;base64,AAAFBfj42Pj4';
                if ($img != '') {
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $font_image = $this->uploadBase64Image($data);
                    $sig_data['font_image'] = $font_image;
                }
            }
            $this->model->saveMerchantData($this->merchant_id, $this->user_id, 'DIGITAL_SIGNATURE', json_encode($sig_data), $is_exist);
            $this->session->setFlash('successMessage', 'Signature details have been saved');
            header('Location: /merchant/profile/digitalsignature/' . $iframe);
            die();
        }

        if (!empty($sig_data)) {
            $this->smarty->assign("name", $sig_data['name']);
            $this->smarty->assign("font_size", $sig_data['font_size']);
            $this->smarty->assign("font_value", $sig_data['font_value']);
            $this->smarty->assign("signature_file", $sig_data['signature_file']);
            $this->smarty->assign("type", $sig_data['type']);
            $this->smarty->assign("align", $sig_data['align']);
        } else {
            $this->smarty->assign("name", $this->session->get('display_name'));
            $this->smarty->assign("font_size", 30);
            $this->smarty->assign("font_value", 1);
            $this->smarty->assign("type", 'font');
        }
        $this->smarty->assign("iframe", $iframe);
        $this->smarty->assign("fonts", $fonts);
        $this->view->js = array('dashboard');
        $this->view->selectedMenu = array(14, 168);
        $this->view->title = 'Digital signature';
        $this->smarty->assign('title', $this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
            array('title' => 'Billing & Invoicing', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        if ($iframe == null) {
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
        } else {
            $this->view->render('header/guest');
            $this->view->hide_footer = 1;
        }
        $this->smarty->display(VIEW . 'merchant/profile/signature.tpl');
        $this->view->render('footer/profile');
    }

    function gstprofile()
    {
        $gst_list = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);
        $gst_list = $this->generic->getDateFormatList($gst_list, 'created_at', 'created_date');
        $gst_list = $this->generic->getEncryptedList($gst_list, 'encrypted_id', 'id');

        $this->smarty->assign("list", $gst_list);
        $this->view->selectedMenu = array(14);
        $this->view->hide_first_col = true;
        $this->view->datatablejs = 'table-no-export';
        $this->view->title = 'Billing profile';
        $this->smarty->assign('title', $this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
            array('title' => 'Billing & Invoicing', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/profile/gstlist.tpl');
        $this->view->render('footer/list');
    }

    function currency()
    {
        $bank_detail = $this->common->getSingleValue('merchant_bank_detail', 'merchant_id', $this->merchant_id);
        $mcurrency = $this->session->get('currency');
        if (empty($mcurrency)) {
            $mcurrency = ['INR'];
            $this->session->set('currency', $mcurrency);
        }
        $currency = str_replace(array('[', ']', '"'), array('', '', "'"), json_encode($mcurrency));
        $merchant_currency = $this->model->getMerchantCurrency($currency);
        $currency_list = $this->common->getListValue('currency', 'is_active', 1);

        $this->smarty->assign("currency", $mcurrency);
        $this->smarty->assign("currency_list", $currency_list);
        $this->smarty->assign("merchant_currency", $merchant_currency);
        $this->smarty->assign("bank_detail", $bank_detail);
        $this->view->selectedMenu = array(14);
        $this->view->datatablejs = 'table-no-export';
        $this->view->title = 'Currency';
        $this->smarty->assign('title', $this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/profile/currency.tpl');
        $this->view->render('footer/list');
    }

    function gstsave()
    {
        $gst_number = $_POST['gst_number'];
        $exist = $this->common->getRowValue('profile_name', 'merchant_billing_profile', 'merchant_id', $this->merchant_id, 1, " and profile_name='" . $_POST['profile_name'] . "'");
        if ($exist == false) {
            $seq_id = ($_POST['seq_id'] > 0) ? $_POST['seq_id'] : 0;
            if ($_POST['gst_available'] == 1) {
                $state = $_POST['state'];
            } else {
                $state = $_POST['state2'];
            }
            $this->model->saveGST($this->merchant_id, $this->user_id, $_POST['profile_name'], $_POST['gst_number'], $_POST['company_name'], $state, $_POST['address'], $_POST['business_email'], $_POST['business_contact'], $seq_id, json_encode($_POST['currency']));
            $this->session->setFlash('successMessage', 'Billing profile has been saved');
            header('Location: /merchant/profile/gstprofile');
            die();
        } else {
            $this->smarty->assign("error", 'GST number already exist.');
            $this->gstcreate();
        }
    }

    function gstupdatesave()
    {
        $seq_id = ($_POST['seq_id'] > 0) ? $_POST['seq_id'] : 0;
        $this->model->updateGST($_POST['id'], $_POST['profile_name'], $this->merchant_id, $this->user_id, $_POST['address'], $_POST['business_email'], $_POST['business_contact'], $seq_id);
        $this->session->setFlash('successMessage', 'Billing profile has been saved');
        header('Location: /merchant/profile/gstprofile');
        die();
    }

    function gstcreate()
    {
        $active_package = $this->common->getRowValue('package_id', 'account', 'merchant_id', $this->merchant_id, 1);
        if ($active_package != 3) {
            $message = 'You do not have access to this feature. If you need access to this feature please contact Swipez support.';
            $this->setError('Access denied', $message, true);
        }

        $gst_number = $this->common->getMerchantProfile($this->merchant_id, 0, 'gst_number');
        $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
        $invoice_numbers = $this->common->getListValue('merchant_auto_invoice_number', 'merchant_id', $this->merchant_id, 1, " and type=1");
        $this->smarty->assign("currency_list", $this->session->get('currency'));
        $this->smarty->assign("invoice_numbers", $invoice_numbers);
        $this->smarty->assign("state_code", $state_code);
        $this->smarty->assign("gst_number", $gst_number);
        $this->smarty->assign("company_name", $this->session->get('company_name'));
        $this->view->js = array('template', 'register', 'dashboard');
        $this->session->set('valid_ajax', 'profile');
        $this->view->selectedMenu = array(14);
        $this->view->title = 'Create billing profile';
        $this->smarty->assign('title', $this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
            array('title' => 'Billing & Invoicing', 'url' => ''),
            array('title' => 'Billing profile', 'url' => '/merchant/profile/gstprofile'),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->smarty->assign("pan_number", substr($gst_number, 2, 10));
        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/profile/gstcreate.tpl');
        $this->view->render('footer/profile');
    }

    function gstupdate($link)
    {
        $id = $this->encrypt->decode($link);
        $detail = $this->common->getSingleValue('merchant_billing_profile', 'id', $id);
        $invoice_numbers = $this->common->getListValue('merchant_auto_invoice_number', 'merchant_id', $this->merchant_id, 1, " and type=1");
        $this->smarty->assign("invoice_numbers", $invoice_numbers);
        $this->smarty->assign("info", $detail);
        $this->view->js = array('template', 'register', 'dashboard');
        $this->session->set('valid_ajax', 'profile');
        $this->view->selectedMenu = array(14);
        $this->view->title = 'Update billing profile';
        $this->smarty->assign('title', $this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
            array('title' => 'Billing & Invoicing', 'url' => ''),
            array('title' => 'Billing profile', 'url' => '/merchant/profile/gstprofile'),
            array('title' => $detail['profile_name'], 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/profile/gstupdate.tpl');
        $this->view->render('footer/profile');
    }

    function accesskey()
    {
        try {
            if (isset($_POST['submit_password'])) {
                $password = $this->common->getRowValue('password', 'user', 'user_id', $this->user_id);
                if (password_verify($_POST['password'], $password)) {
                    $this->session->set('accesskeyValidation', 1);
                } else {
                    $error = 'Invalid Password';
                }
            }
            if (isset($_POST['reset_key']) || isset($_POST['generate_key'])) {
                if ($_POST['key_id'] > 0) {
                    $this->model->updateKeyDetail($_POST['key_id'], $this->merchant_id, $this->user_id);
                } else {
                    $this->model->saveKeyDetail($this->merchant_id, $this->user_id);
                }
                $this->session->set('successMessage', 'Keys have been generated successfully');
                header('Location:/merchant/profile/accesskey');
                die();
            }

            if (isset($_POST['delete_key'])) {
                $this->model->updateKeyDetail($_POST['key_id'], '', '');
                $this->session->set('successMessage', 'Keys have been deleted successfully');
                header('Location:/merchant/profile/accesskey');
                die();
            }

            $det = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id);
            if (!empty($det)) {
                $this->smarty->assign("det", $det);
            }
            $this->view->selectedMenu = array(14, 117);
            $this->smarty->assign("error", $error);
            $this->smarty->assign("valid", $this->session->get('accesskeyValidation'));
            $this->view->title = 'API keys';
            $this->smarty->assign('title', $this->view->title);
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                array('title' => 'Company settings', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/profile/accesskey.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E016+6]Error while Switch Login for user id [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function producttaxation()
    {
        $det = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
        $product_taxation  = $det["product_taxation"];
        $this->view->js = array('template', 'register', 'dashboard');
        $this->session->set('valid_ajax', 'profile');
        $this->view->selectedMenu = array(14);
        $this->view->title = 'Product Taxation';
        $this->smarty->assign('title', $this->view->title);
        $this->smarty->assign('product_taxation', $product_taxation);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
            array('title' => 'Billing & Invoicing', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->header_file = ['product_taxation'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/profile/producttaxation.tpl');
        $this->view->render('footer/product_taxation');
    }

    function updatetaxation()
    {
        try {
            $this->hasRole(2, 12);
            $merchant_id = $this->session->get('merchant_id');

            if (empty($_POST)) {
                header('Location:/merchant/profile/producttaxation');
            }

            $result = $this->model->updatetaxation($this->session->get('userid'), $merchant_id, $_POST["taxation_type"]);

            $this->session->set('successMessage', 'Updates made to your setting have been saved.');
            header('Location:/merchant/profile/producttaxation');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E017]Error while update merchant setting Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
