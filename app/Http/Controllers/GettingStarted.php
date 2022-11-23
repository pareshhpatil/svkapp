<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Libraries\Encrypt;
use Validator;
use App\Jobs\MerchantRegistrationNotification;
use App\Jobs\MerchantRegistrationAddToCRM;
use App\Jobs\MerchantRegistrationCrmPreference;
use App\Jobs\MerchantRegistrationCrmIndustry;
use App\Libraries\Helpers;
use Exception;
use Stripe;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\View;

class GettingStarted extends Controller
{

    private $user_model = null;
    private $service_id = 2;
    private $siteMap;

    public function __construct()
    {
        $this->siteMap = new SitemapController();
        $this->user_model = new User();
        if (Session::has('product_service_id')) {
            $this->service_id = Encrypt::decode(Session::get('product_service_id'));
        }
        View::share('hide_smartlook_for_patron', true);
    }
    /**
     * Getting started landing page
     */
    public function index()
    {
        if (Session::has('merchant_id')) {
            $merchant_id = Encrypt::decode(Session::get('merchant_id'));

            $profile_step = $this->user_model->getColumnValue('merchant_setting', 'merchant_id', $merchant_id, 'profile_step');
            switch ($profile_step) {
                case 1:
                    return redirect('/merchant/getting-started/welcome');
                    break;
                case 2:
                    return redirect('/merchant/getting-started/features');
                    break;
                case 3:
                    return redirect('/merchant/getting-started/set-industry');
                    break;
            }
        }
        if (!Session::has('product_login_token')) {
            return redirect('/merchant/register');
        }

        $id = Encrypt::decode(Session::get('product_login_token'));
        $google_validate = Session::get('google_validate');
        if ($google_validate == true) {
            $data['password'] = uniqid();
        } else {
            $data['password'] = '';
        }
        $data['google_validate'] = $google_validate;
        $detail = $this->user_model->getTableRow('otp', 'id', $id);
        $data['service_id'] = Session::get('product_service_id');
        $data['total_step'] = ($this->service_id == 2 || $this->service_id == 6) ? 4 : 3;
        $data['detail'] = $detail;
        $data['title'] = 'Setup your account ✒';
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['d_type'] = "web";
        return view('getting-started/index', $data);
    }

    /**
     * Register validated merchant
     */

    public function merchantRegister(Request $request)
    {
        $validation = [
            'password' => 'required|min:6|max:20',
            'company_name' => 'required|max:100',
            'name' => 'required|max:100',
            'email' => 'required|email|Emailexist',
            'mobile' => 'required|max:14|min:10'
        ];

        $validator = Validator::make($request->all(), $validation, ['emailexist' => 'Email id already exists.']);
        if ($validator->fails()) {
            return redirect('merchant/getting-started')
                ->withErrors($validator)
                ->withInput();
        }

        if ($this->is_english($request->company_name) == false) {
            return redirect('merchant/getting-started')->withInput()->withErrors(['Please enter value only in english letters.']);
        }

        if ($this->is_english($request->name) == false) {
            return redirect('merchant/getting-started')->withInput()->withErrors(['Please enter value only in english letters.']);
        }

        $service_id = 2;
        $plan_id = 2;
        if ($_POST['service_id'] != '') {
            $service_id = Encrypt::decode($_POST['service_id']);
            Session::put('service_id', $service_id);
        }
        $campaign_id = Helpers::getCookie('registration_campaign_id');
        $campaign_id = ($campaign_id > 0) ? $campaign_id : 0;
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $name_array = $this->getLastName($_POST['name']);
        $_POST['country_code'] = (isset($_POST['country_code'])) ? $_POST['country_code'] : '91';

        $result = $this->user_model->merchantRegister($_POST['email'], $name_array['first_name'], $name_array['last_name'], $_POST['country_code'], $_POST['mobile'], $password, $_POST['company_name'], $plan_id, $campaign_id, $service_id);
        if ($result->Message == 'success') {
            //Add message to queue
            $merchant_id = $result->merchant_id;

            try {
                if (env('APP_ENV') != 'LOCAL') {
                    MerchantRegistrationNotification::dispatch($merchant_id, $_POST['company_name'], $_POST['email'], $_POST['mobile'], $name_array['first_name'], $name_array['last_name'], $campaign_id, $service_id, 'web')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
                    MerchantRegistrationAddToCRM::dispatch($merchant_id, $_POST['company_name'], $_POST['email'], $_POST['mobile'], $name_array['first_name'], $name_array['last_name'], $campaign_id, $service_id, 'web')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
                }
            } catch (Exception $e) {
                app('sentry')->captureException($e);
                return Helpers::handleCatch(__METHOD__, $e->getMessage());
            }
            Session::flash('new_registered', 'Yes');
            Auth::loginUsingId($result->id);
            $user = Auth::user();
            $userController = new UserController();
            $userController->setLoginSession($user);
            return redirect("/merchant/getting-started/welcome");
        } else {
            return Helpers::handleCatch(__METHOD__, $result->Message);
        }
    }

    function is_english($str)
    {
        if (strlen($str) != strlen(utf8_decode($str))) {
            return false;
        } else {
            return true;
        }
    }

    public function preferencesave(Request $request)
    {
        $merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $online_payment = isset($request->online_payment) ? 1 : 0;
        $create_invoice = isset($request->create_invoice) ? 1 : 0;
        $create_customer = isset($request->create_customer) ? 1 : 0;
        $send_paymentlink = isset($request->send_paymentlink) ? 1 : 0;
        $bulk_invoice = isset($request->bulk_invoice) ? 1 : 0;
        $create_subscription = isset($request->subscription) ? 1 : 0;
        $this->user_model->saveSignPreference($merchant_id, $online_payment, $create_invoice, $create_customer, $send_paymentlink, $bulk_invoice, $create_subscription);
        $this->user_model->updateProfileStep($merchant_id, 3);
        Session::put('profile_step', 3);

        #Update Clickup task preference fields Queue dispatch
        if (env('APP_ENV') != 'LOCAL') {
            MerchantRegistrationCrmPreference::dispatch($merchant_id,  $create_invoice, $create_customer, $send_paymentlink, $online_payment, $create_subscription, $bulk_invoice)->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
        }
        return redirect('/merchant/getting-started/set-industry');
    }

    public function industrysave()
    {
        $merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_model->updateProfileStep($merchant_id, 4);
        Session::put('currency', $_POST['currency']);
        $this->user_model->updateTable('merchant_setting', 'merchant_id', $merchant_id, 'currency', json_encode($_POST['currency']));
        $this->user_model->updateMerchantIndustry($merchant_id, $_POST['industry_type'], $_POST['employee_count'], $_POST['customer_count']);
        $url = $this->user_model->setDefaultDisplayURL($merchant_id);
        //$this->siteMap->createSiteMap($url);        

        Session::put('profile_step', 4);
        Session::put('new_merchant', true);

        #Update Clickup task industry fields Queue dispatch
        if (env('APP_ENV') != 'LOCAL') {
            MerchantRegistrationCrmIndustry::dispatch($merchant_id,  $_POST['industry_type'], $_POST['customer_count'], $_POST['employee_count'])->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
            // SubDomainManagement::dispatch($merchant_id, $url)->onQueue(env('SQS_SUBDOAMIN_MANAGEMENT'));
        }

        $service_id = Encrypt::encode(Session::get('service_id'));
        return redirect('/merchant/dashboard/index/' . $service_id);
    }

    function getLastName($name)
    {
        $name = trim($name);
        $space_position = strpos($name, ' ');
        if ($space_position > 0) {
            $data['first_name'] = substr($name, 0, $space_position);
            $data['last_name'] = substr($name, $space_position);
        } else {
            $data['first_name'] = $name;
            $data['last_name'] = '';
        }
        return $data;
    }

    /**
     * Getting started landing page
     */
    public function welcome()
    {
        $data['title'] = 'Welcome aboard 🙌';
        $data['name'] = Session::get('display_name');
        if (Session::has('new_registered')) {
            $data['new_registered'] = 'Yes';
        }
        $data['total_step'] = ($this->service_id == 2 || $this->service_id == 6) ? 4 : 3;
        return view('getting-started/welcome', $data);
    }

    /**
     * Get industry from merchant
     */
    public function industry()
    {
        $data['title'] = 'Customize your account 🎨';
        $data['industry_list'] = $this->user_model->getConfigList('industry_type');
        $data['currency_list'] = $this->user_model->getTableList('currency', 'is_active', 1);
        $data['total_step'] = ($this->service_id == 2 || $this->service_id == 6) ? 4 : 3;
        return view('getting-started/industry', $data);
    }

    /**
     * Pick from feature list
     */
    public function featurelist()
    {
        $data['title'] = 'Select features ✨';
        $merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_model->updateProfileStep($merchant_id, 2);
        $data['total_step'] = ($this->service_id == 2 || $this->service_id == 6) ? 4 : 3;
        return view('getting-started/features', $data);
    }

    function remindmelater($type = null)
    {
        if ($type == 'helphero') {
            Session::put('help_hero_popup', 0);
            Session::forget('document_upload');
        } elseif ($type == 'cable') {
            Session::put('show_cable_popup', 0);
            Session::put('disable_cable_popup', 1);
        } elseif ($type == 'getstarted') {
            Session::put('disable_get_started', 1);
        }
        return redirect('/merchant/dashboard');
    }

    function integrations(Request $request)
    {
        dd($request);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        if ($request->code) {
            $response = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->code
            ]);
            $merchant_id = Encrypt::decode(Session::get('merchant_id'));
            $payment_gateway = $this->user_model->addPaymentGateway($response, $merchant_id);
        }
        $payment_gateway = $this->user_model->getPaymentGateway(Session::get('merchnat_id'));
        $data = Helpers::setBladeProperties('Integrations', [], []);
        return redirect(route('merchant.integrations'));
    }
}
