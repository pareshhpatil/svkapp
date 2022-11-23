<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Model\Benefit;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Libraries\Encrypt;

class BenefitsController extends Controller
{

    public function index()
    {
        $paid_plan = array(9, 3, 13, 14,15);
        $merchant_plan = Session::get('merchant_plan');
        $package_expire = Session::get('package_expire');
        $merchant_id = Encrypt::decode(Session::get('merchant_id'));

        $model = new Benefit();
        $data = Helpers::setBladeProperties('Benefits', ['benefit'], [170]);
        $data['merchant_tracking'] = $model->getMerchantBenefits($merchant_id);

        $data['list'] = $model->getBenefits();
        if (in_array($merchant_plan, $paid_plan) && $package_expire == false) {
            $data['valid_plan'] = true;
        } else {
            $data['valid_plan'] = false;
        }
        if ($merchant_plan == 3) {
            $data['growth_plan'] = true;
        } else {
            $data['growth_plan'] = false;
        }
        if (Session::has('success')) {
            $data['script'] = "document.getElementById('success_btn').click();";
        }
        return view('app/merchant/benefits/index', $data);
    }

    public function apply(Request $request)
    {
        $merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $user_id = Encrypt::decode(Session::get('userid'));
        $model = new Benefit();
        $model->saveBenefit($request->benefit_id, $merchant_id);
        $detail = $model->getTableRow('benefits', 'id', $request->benefit_id);
        $user = $model->getTableRow('user', 'user_id', $user_id);
        $merchant = $model->getTableRow('merchant', 'merchant_id', $merchant_id);
        $email = $user->email_id;
        $data['detail'] = $detail;
        Helpers::sendMail($email, 'benefit', $data, 'You have applied for ' . $detail->title . ' benefit on Swipez');
        $email_list = explode(',', $detail->company_email);
        if (!empty($email_list)) {
            foreach ($email_list as $email) {
                $array['Merchant name'] = $user->first_name . ' ' . $user->last_name;
                $array['Company name'] = $merchant->company_name;
                $array['Email id'] = $user->email_id;
                $array['Mobile'] = $user->mobile_no;
                $data['data'] = $array;
                Helpers::sendMail($email, 'data', $data, $merchant->company_name . ' requested for benefit ' . $detail->title, ['support@swipez.in']);
            }
        }
        Session::flash('success', 'Awesome! Benefit details have been sent to your email id ' . $user->email_id);
        return redirect('/merchant/benefits');
    }
}
