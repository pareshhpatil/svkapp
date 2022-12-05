<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Libraries\Encrypt;
use App\Libraries\DataValidation as Valid;
use App\Libraries\Helpers;
use Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Exception;
use Validator;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{

    private $user_model = null;

    public function __construct()
    {
        $this->user_model = new User();
    }

    function loyaltylogin($merchant_url)
    {
        $data = $this->getMerchantDetail($merchant_url);
        if ($data != false) {
            return view('loyalty/login', $data);
        } else {
            return redirect('/404');
        }
    }

    function home($merchant_url)
    {
        $data = $this->getMerchantDetail($merchant_url);
        if ($data != false) {
            $data['logged_in']=true;
            return view('loyalty/home', $data);
        } else {
            return redirect('/404');
        }
    }

    function register($merchant_url)
    {
        $data = $this->getMerchantDetail($merchant_url);
        if ($data != false) {
            return view('loyalty/register', $data);
        } else {
            return redirect('/404');
        }
    }

    function otp($merchant_url)
    {
        $data = $this->getMerchantDetail($merchant_url);
        if ($data != false) {
            return view('loyalty/otp', $data);
        } else {
            return redirect('/404');
        }
    }

    function getMerchantDetail($merchant_url)
    {
        $merchant_detail = $this->user_model->getTableRow('merchant', 'display_url', $merchant_url);
        if ($merchant_detail != false) {
            $data['logo'] = $this->user_model->getColumnValue('merchant_landing', 'merchant_id', $merchant_detail->merchant_id,'logo');
            $data['merchant_id'] = Encrypt::encode($merchant_detail->merchant_id);
            $data['merchant_url'] = $merchant_url;
            $data['merchant'] = $merchant_detail;
            $data['logged_in']=false;
            return $data;
        } else {
            return false;
        }
    }
}
