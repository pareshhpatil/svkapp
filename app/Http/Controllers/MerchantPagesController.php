<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use App\Model\MerchantPage;
use Illuminate\Support\Facades\Session;
use Log;
use Illuminate\Support\Facades\Redis;
use PhpParser\Node\Expr\Isset_;

class MerchantPagesController extends Controller
{

    private $merchant_id = null;
    private $user_id = null;

    public function __construct($url = null)
    {
        $this->MerchantPageModel = new MerchantPage();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
        $this->url = $url;
    }

    public function getDetails($url)
    {
        $merchant = $this->MerchantPageModel->getTableRow('merchant', 'display_url', $url);
        if ($merchant == null) {
            $old_url_detail = $this->MerchantPageModel->getTableRow('display_url_backup', 'old_url', $url);
            if ($old_url_detail == null) {
                return 0;
            }
            if ($old_url_detail != null) {
                $merchant = $this->MerchantPageModel->getTableRow('merchant', 'display_url', $old_url_detail->new_url);
            }
        }

        $isLogin = $this->checkMerchantLogin($url);
        if (!empty($merchant)) {
            $industry = $this->MerchantPageModel->getIndustry($merchant->industry_type);
            if ($industry == null) {
                $industry = $this->MerchantPageModel->getIndustry(28);
            }
        } else {
            return 0;
        }

        $industry_name = $industry->config_value;
        $merchant_detail = $this->MerchantPageModel->getTableRow('merchant_landing', 'merchant_id', $merchant->merchant_id);
        $merchant_website = $this->MerchantPageModel->getMerchantWebsite($merchant->merchant_id);
        //show tour flag is set for hero help tour
        $showTour = !empty($merchant_detail) ? $merchant_detail->is_complete_company_page : 0;

        $default_data = $this->MerchantPageModel->getTableRow('default_industry_merch_landing', 'industry_id', $industry->config_key);
        if ($merchant_detail == null) {                                                           // if merchant details are not present in merchant landing table
            $merchant_detail = $default_data;
            $logo = 'landingpage/default-logo.png';
            $random_work = rand(1, 10);
            if ($merchant_detail->industry_id == 0 || $merchant_detail->industry_id == 28) {      // if industry is 0 or null or other
                $default_banner = $merchant_detail->default_image_path;
            } else {
                $default_banner = 'landingpage/' . $merchant_detail->industry_id . '_' . $random_work . '.jpg';
            }
            $default_random_work = 'landingpage/' . $industry->config_key . '_' . $random_work . '.jpg';
            if (!is_file(public_path($default_random_work))) {
                $default_random_work = 'landingpage/work_with_us.jpg';
            }
        } else {                                                                                   // if merchant details are present in merchant landing table
            if ($merchant_detail->logo == null) {
                $logo = 'landingpage/default-logo.png';
            } else {
                $logo = null;
            }
            if ($merchant_detail->banner == null) {
                $default_banner = $default_data->default_image_path;
            } else {
                $default_banner = null;
            }
            if ($merchant_detail->publishable == 0) {
                return 0;
            }
            $random_work = rand(1, 10);
            $default_random_work = 'landingpage/' . $industry->config_key . '_' . $random_work . '.jpg';
            if (!is_file(public_path($default_random_work))) {
                $default_random_work = 'landingpage/work_with_us.jpg';
            }
        }
        $description = ucfirst($merchant->company_name) . " | " . ucfirst($industry_name) . " | " . ($merchant_detail->overview == null ? $default_data->overview : $merchant_detail->overview);
        $data['merchant_website'] = $merchant_website;
        $data['merchant_detail'] = $merchant_detail;
        $data['default_data'] = $default_data;
        $data['showTour'] = $showTour;
        $data['isLogin'] = $isLogin;
        $data['merchant'] = $merchant;
        $data['default_banner'] = $default_banner;
        $data['default_random_work'] = $default_random_work;
        $data['logo'] = $logo;
        $data['industry_name'] = $industry_name;
        if (strlen($description) > 160) {
            $data['description'] = substr($description, 0, 160);
        } else {
            $data['description'] = $description;
        }
        return $data;
    }

    public function merchantIndex($url)
    {
        $data = $this->getDetails($url);
        if ($data == 0) {
            return view('mpages.error');
        }
        return view('mpages.merchant_index', $data);
    }

    public function connectMail(Request $request)
    {
        $merchant = $this->MerchantPageModel->getTableRow('merchant', 'display_url', $request->url);
        $user = $this->MerchantPageModel->getTableRow('user', 'user_id', $merchant->user_id);
        $data['url'] = $request->url;
        $data['useremail'] = $request->email;
        $data['userquery'] = $request->all()['query'];
        $data['username'] = $request->name;
        $array['data'] = $data;
        Helpers::sendMail($user->email_id, 'subdomain_contact_mail', $array, 'Contact Us Mail');
        return redirect()->back()->with('success', 'Your query is successfully submitted.');
    }

    public function isEmail($user_id)
    {
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL) == false && $user_id != '') {
            return 0;
        } else {
            return 1;
        }
    }

    public function isMobile($user_id)
    {
        if (preg_match('/^\d{10}$/', $user_id) && substr($user_id, 0, 1) != 0) {
            return 2;
        } else {
            return 0;
        }
    }

    public function merchantBills(Request $request, $url = null)
    {
        $mybills = [];

        $data = $this->getDetails($url);
        if ($data == 0) {
            return view('mpages.error');
        }
        $user_input = '';
        if ($request->method() == "POST") {
            $user_input = $request->user_input;
            $type = $this->isEmail($request->user_input);
            if ($type == 0) {
                $type = $this->isMobile($request->user_input);
                if ($type == 0) {
                    $type = 3;
                }
            }
            $mybills = $this->MerchantPageModel->getMerchantBills($request->user_input, $type, $data['merchant']->merchant_id);
            if (!empty($mybills)) {
                foreach ($mybills as $bk => $bill) {
                    if (!empty($bill->payment_request_id)) {
                        $mybills[$bk]->paylink = env('SWIPEZ_BASE_URL') . 'patron/paymentrequest/view/' . Encrypt::encode($bill->payment_request_id);
                    } else {
                        unset($mybills[0]);
                    }
                }
            }
            $data['mybills'] = $mybills;
            $data['user_input'] = $user_input;
            $data['online_payment'] = 1;
            if (count($mybills) == 0) {
                session::pull('info');
                session::flash('info', 'No Bills Found For Customer - ' . $request->user_input);
                return view('mpages.pay_my_bill', $data);
            }
        }
        $online_payment = 1;
        $findMerchantFeeDetails = $this->MerchantPageModel->isExistData($data['merchant']->merchant_id, 'merchant_fee_detail', 'is_active', 1);
        if ($findMerchantFeeDetails == false) {
            $online_payment = 0;
        }
        $data['online_payment'] = $online_payment;
        $data['mybills'] = $mybills;
        $data['user_input'] = $user_input;
        return view('mpages.pay_my_bill', $data);
    }

    public function merchantContactus($url)
    {
        $data = $this->getDetails($url);
        if ($data == 0) {
            return view('mpages.error');
        }
        return view('mpages.contactus', $data);
    }

    public function merchantAboutus($url)
    {
        $data = $this->getDetails($url);
        if ($data == 0) {
            return view('mpages.error');
        }
        return view('mpages.aboutus', $data);
    }

    public function merchantPolicies($url = null)
    {
        $data = $this->getDetails($url);
        if ($data == 0) {
            return view('mpages.error');
        }
        return view('mpages.policies', $data);
    }

    public function merchantDirectPay(Request $request, $url = null, $link = null)
    {
        $data = $this->getDetails($url);
        if ($data == 0) {
            return view('mpages.error');
        }
        $currency_icon = '₹';
        $currency = 'INR';
        $detail = array();
        if ($link != null) {
            Session::put('direct_pay_link', $link);
            $id = Encrypt::decode($link);
            if (is_numeric($id)) {
                $detail = $this->MerchantPageModel->getTableRow('direct_pay_request', 'id', $id);
                $currency = $detail->currency;
                $currency_icon = $this->MerchantPageModel->getColumnValue('currency', 'code', $detail->currency, 'icon');
            }
        }
        $data['post_url'] = env('SWIPEZ_BASE_URL') . 'm/' . $data["merchant"]->display_url . '/directpayment';
        $data['request_post_url'] =  env('SWIPEZ_BASE_URL') . 'm/' . $data["merchant"]->display_url . '/directpayment';
        $data['is_new_pg'] =  false;
        $show_msg = 0;
        //check merchant_fee_details for the customer
        $directpay_page_setting = $this->MerchantPageModel->getMerchantData($data['merchant']->merchant_id, 'DIRECTPAY_PAGE_SETTING');

        $findMerchantFeeDetails = $this->MerchantPageModel->getMerchantFeeDetail($data['merchant']->merchant_id, $currency);
        if ($findMerchantFeeDetails == false) {
            $show_msg = 1;
        } else {
            if (count($findMerchantFeeDetails) > 1) {
                $radio = $this->getPGRadio($findMerchantFeeDetails);
                $data['radio'] = $radio['radio'];
                $data['post_url'] = env('SWIPEZ_BASE_URL') . 'payment-gateway';
                $data['request_post_url'] =  env('SWIPEZ_BASE_URL') . 'm/' . $data["merchant"]->display_url . '/directpayment';
                $data['is_new_pg'] =  true;
            } else {
                $data['post_url'] = env('SWIPEZ_BASE_URL') . 'm/' . $data["merchant"]->display_url . '/directpayment';
                $data['request_post_url'] =  env('SWIPEZ_BASE_URL') . 'm/' . $data["merchant"]->display_url . '/directpayment';
                $data['is_new_pg'] =  false;
            }
        }
        if (Session::has('validerrors')) {
            foreach (Session::get('validerrors') as $row) {
                $data['validerrors'][] = $row[0] . '-' . $row[1];
            }
            Session::remove('validerrors');
        }

        // dd($request->cookie('payment_session'));
        if ($directpay_page_setting != false) {
            $data['page_setting'] = json_decode($directpay_page_setting, 1);
        }

        //find country list 
        $country_code = $this->MerchantPageModel->getConfigList('country_name');
        $data['country_code'] = $country_code;

        if (isset($detail->country) && $detail->country != 'India') {
            $get_country_code = $this->MerchantPageModel->getCountryCode($detail->country);
            $detail->country_mobile_code = '+' . $get_country_code->description;
        }

        $data['show_msg'] = $show_msg;
        $data['detail'] = $detail;
        $data['currency_icon'] = $currency_icon;
        return view('mpages.direct_pay', $data);
    }

    function checkMerchantLogin($url = null)
    {
        /* check merchant is logged in or not and check his website name*/
        if ($url != null) {
            $merchant = $this->MerchantPageModel->getTableRow('merchant', 'display_url', $url);
            if ($merchant == false) {
                return 0;
            }
            if ($this->merchant_id && $this->user_id && ($url == $merchant->display_url)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function getPGRadio($pg_details)
    {
        $paypal_account_id = '';
        foreach ($pg_details as $pg) {
            if ($pg->pg_type == 4 || $pg->pg_type == 6) {
                $netbanking_pg = Encrypt::encode($pg->pg_id);
                $net_fee_id = Encrypt::encode($pg->fee_detail_id);
            } else if ($pg->pg_type == 2) {
                $paytm_pg = Encrypt::encode($pg->pg_id);
                $paytm_fee_id = Encrypt::encode($pg->fee_detail_id);
            } else if ($pg->pg_type == 5) {
                $paytm_sub_pg = Encrypt::encode($pg->pg_id);
                $paytm_sub_fee_id = Encrypt::encode($pg->fee_detail_id);
            } else if ($pg->pg_type == 9) {
                $international_pg = Encrypt::encode($pg->pg_id);
                $international_fee_id = Encrypt::encode($pg->fee_detail_id);
            } else if ($pg->pg_type == 8) {
                $paypal_pg = Encrypt::encode($pg->pg_id);
                $paypal_fee_id = Encrypt::encode($pg->fee_detail_id);
                $paypal_account_id = $pg['pg_val4'];
            } else if ($pg->pg_type == 12) {
                $payoneer_fee_id = Encrypt::encode($pg->fee_detail_id);
                $payoneer_id = Encrypt::encode($pg->pg_id);
            } else if ($pg->pg_type == 11) {
                $stripe_fee_id = Encrypt::encode($pg->fee_detail_id);
                $stripe_id = Encrypt::encode($pg->pg_id);
            } else {
                $credit_card_pg = Encrypt::encode($pg->pg_id);
                $credit_fee_id = Encrypt::encode($pg->fee_detail_id);
            }
        }
        if (!isset($credit_fee_id)) {
            $credit_fee_id = $net_fee_id;
            $credit_card_pg = $netbanking_pg;
        }
        $radio[] = array('name' => 'UPI', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);
        $radio[] = array('name' => 'Credit/Debit card', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);

        if (isset($netbanking_pg)) {
            $radio[] = array('name' => 'Net banking', 'pg_id' => $netbanking_pg, 'fee_id' => $net_fee_id);
        } else {
            $radio[] = array('name' => 'Net banking', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);
        }
        if (isset($paytm_pg)) {
            $radio[] = array('name' => 'Paytm', 'pg_id' => $paytm_pg, 'fee_id' => $paytm_fee_id);
        }
        if (isset($international_pg)) {
            $radio[] = array('name' => 'International Card <a  class="popovers" data-container="body" data-trigger="hover" data-content="Cards issued by banks outside of India" data-original-title="" title=""><i class="fa fa-info-circle"></i></a>', 'pg_id' => $international_pg, 'fee_id' => $international_fee_id);
        }
        if (isset($paytm_sub_pg)) {
            $radio[] = array('name' => 'PAYTM_SUB', 'pg_id' => $paytm_sub_pg, 'fee_id' => $paytm_sub_fee_id);
        }
        if (isset($paypal_pg)) {
            $radio[] = array('name' => 'PAYPAL', 'pg_id' => $paypal_pg, 'fee_id' => $paypal_fee_id);
        }
        if (isset($payoneer_id)) {
            $radio[] = array('name' => 'Payoneer', 'pg_id' => $payoneer_id, 'fee_id' => $payoneer_fee_id);
        }
        if (isset($stripe_id)) {
            $radio[] = array('name' => 'Stripe', 'pg_id' => $stripe_id, 'fee_id' => $stripe_fee_id);
        }
        $array['radio'] = $radio;
        $array['paypal_id'] = $paypal_account_id;
        return $array;
    }

    public function merchantPaymentGateway(Request $request, $url = null, $link = null)
    {
        $data = $this->getDetails($request->url);
        if ($data == 0) {
            return view('mpages.error');
        }
        $detail = array();
        if ($link != null) {
            Session::put('direct_pay_link', $link);
            $id = Encrypt::decode($link);
            if (is_numeric($id)) {
                $detail = $this->MerchantPageModel->getTableRow('direct_pay_request', 'id', $id);
                $currency = $detail->currency;
                $currency_icon = $this->MerchantPageModel->getColumnValue('currency', 'code', $detail->currency, 'icon');
            }
        }

        if (isset($request->form_type)) {
            $pg_details = $this->MerchantPageModel->getXwayPaymentGatewayDetails($data["merchant"]->merchant_id, null, 0, $request->currency);
        } else {
            $pg_details = $this->MerchantPageModel->getPaymentGatewayDetails($data["merchant"]->merchant_id, null, 0, $request->currency);
        }

        $upi_fee_id = '';
        $cashfree_fee_id = '';
        $paytm_fee_id = '';
        $is_paytm = false;
        foreach ($pg_details as $data_pg) {
            if ($data_pg->pg_type == '13') {
                // $upi_pg_id = $data_pg->pg_id;
                $upi_fee_id = Encrypt::encode($data_pg->fee_detail_id);
            } else if ($data_pg->pg_type == '2') {
                // $paytm_pg_id = $data_pg->pg_id;
                $is_paytm = true;
                $paytm_fee_id = Encrypt::encode($data_pg->fee_detail_id);
            } else {
                // $cashfree_pg_id = $data_pg->pg_id;
                $cashfree_fee_id = Encrypt::encode($data_pg->fee_detail_id);
            }
        }
        $data['url'] = $request->url;
        $data['amount'] = $request->amount;
        // $data['upi_pg_id'] =  $upi_pg_id;
        // $data['cashfree_pg_id'] =  $cashfree_pg_id;
        // $data['paytm_pg_id'] =  $paytm_pg_id;
        $data['upi_fee_id'] =  $upi_fee_id;
        $data['paytm_fee_id'] =  $paytm_fee_id;
        $data['cashfree_fee_id'] =  $cashfree_fee_id;
        $data['is_paytm'] =  $is_paytm;
        $data['post_url'] = $request->post_url;
        $data['request_post_url'] = $request->request_post_url;
        $data['post'] = $request->all();
        $newjson = '{ "amount" : "' . $data['post']["amount"] . '", "email" :  "' . $data['post']["email"] . '"}';
        $data['post']["hash"] = Encrypt::encode($newjson);

        return view('mpages.paymentgateway', $data);
    }

    public function upipgtrack()
    {
        $paymentWrapper = new PaymentWrapperController();
        $platform_bill_id = $_POST["platform_bill_id"];
        $order_hash = $_POST["order_hash"];
        $trans_id  = Encrypt::decode($order_hash);
        $paymentWrapper->setuTrackTransactionStatus($order_hash, $platform_bill_id, $trans_id);
    }

    public function setu(Request $request)
    {
        $data = $request->events;
        foreach ($data as $res) {
            $this->MerchantPageModel->saveSetuResponse($res, 'SETU',  'SETU');
            if (isset($res["data"]["billerBillID"])) {
                $hash  = Encrypt::encode($res["data"]["billerBillID"]);
                if (Redis::exists($hash)) {
                    $mn = Redis::get($hash);
                    $mn = json_decode($mn, 1);
                    if ($res["data"]["status"] == 'PAYMENT_SUCCESSFUL') {
                        $mn["payment_status"] = 'SUCCESS';
                    } else {
                        $mn["payment_status"] = $res["data"]["status"];
                    }
                    $mn["setu_response"] = $res["data"];
                    Redis::del($hash);
                    Redis::set($hash, json_encode($mn), 'EX', 604800);
                } else {
                    Log::error('redis key doesnt exists');
                }
            }
        }
    }

    public function stripe(Request $request)
    {
		
        return $request;
        
    }
}
