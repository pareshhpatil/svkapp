<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Model\Autocollect;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Exception;

class AutocollectController extends Controller
{

    private $autocollect_model = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->autocollect_model = new Autocollect();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    public function planCreate($errors = null)
    {
        Helpers::hasRole(2, 26);
        $data = Helpers::setBladeProperties('Create plan', ['autocollect'], [3,136, 137]);
        $data['errors'] = $errors;
        return view('app/merchant/autocollect/plancreate', $data);
    }

    public function planSave()
    {
        dd($_POST);
        $post_string = json_encode($_POST);
        $result = Helpers::APIrequest('v1/autocollect/plan/save', $post_string);
        $array = json_decode($result, 1);
        if ($array['status'] == 0) {
            if (is_array($array['error'])) {
                $errors = $array['error'];
            } else {
                $errors[] = $array['error'];
            }
            return $this->planCreate($errors);
        } else {
            Session::flash('success', $array['success']);
            return redirect('/merchant/autocollect/plans');
        }
    }

    public function subscriptionSave()
    {
        $plan_details = $this->autocollect_model->getTableRow('autocollect_plans', 'plan_id', $_POST['plan_id']);
        $_POST['auth_amount'] = $plan_details->amount;
        $_POST['amount'] = $plan_details->amount;
        $_POST['return_url'] = env('APP_URL') . '/secure/cashfreeautocollect';
        $_POST['expiry_date'] = date("Y-m-d H:i:s", strtotime(" +" . $plan_details->occurrence . " months"));
        $post_string = json_encode($_POST);
        $result = Helpers::APIrequest('v1/autocollect/subscription/save', $post_string);
        $array = json_decode($result, 1);
        if ($array['status'] == 0) {
            if (is_array($array['error'])) {
                $errors = $array['error'];
            } else {
                $errors[] = $array['error'];
            }
            return $this->subscriptionCreate($errors);
        } else {
            Session::flash('success', $array['success']);
            return redirect('/merchant/autocollect/subscriptions');
        }
    }

    public function planList()
    {
        Helpers::hasRole(1, 26);
        $data = Helpers::setBladeProperties('Plan list', [], [3,136, 137]);
        $data['datatablejs'] = 'table-no-export';
        $list = array();
        $collectkey = $this->autocollect_model->autoCollectKeys($this->merchant_id);
        if ($collectkey == true) {
            $result = Helpers::APIrequest('v1/autocollect/plan/list', '', "GET");
            $result = json_decode($result);
            if (!empty($result->data)) {
                $list = $result->data;
                foreach ($list as $key => $row) {
                    $list[$key]->encrypted_id = Encrypt::encode($row->plan_id);
                }
            }
        }
        $data['collectkey'] = $collectkey;
        $data['list'] = $list;
        return view('app/merchant/autocollect/planlist', $data);
    }

    public function subscriptionCreate($errors = null)
    {
        Helpers::hasRole(2, 26);
        Session::put('valid_ajax', 'beneficiary');
        $data = Helpers::setBladeProperties('Create Subscription', ['autocollect', 'payout'], [136, 138]);
        $data['script'] = "setBenifeciaryType(1);";
        $data['errors'] = $errors;
        $list = array();
        $result = Helpers::APIrequest('v1/autocollect/plan/list', '', "GET");
        $result = json_decode($result);
        if (!empty($result->data)) {
            $list = $result->data;
        }
        $data['list'] = $list;
        return view('app/merchant/autocollect/subscriptioncreate', $data);
    }

    public function subscriptionList()
    {
        Helpers::hasRole(1, 26);
        $dates = Helpers::setListDates();
        $data = Helpers::setBladeProperties('Subscription list', [], [136, 138]);
        $data['datatablejs'] = 'table-no-export';
        $api_url = 'v1/autocollect/subscription/list';
        $result = Helpers::APIrequest($api_url, 'from_date=' . $dates['from_date'] . '&to_date=' . $dates['to_date'], "POST");
        $list = array();
        $result = json_decode($result);
        if (!empty($result->data)) {
            $list = $result->data;
            foreach ($list as $key => $row) {
                $list[$key]->encrypted_id = Encrypt::encode($row->subscription_id);
            }
        }
        $data['list'] = $list;
        return view('app/merchant/autocollect/subscriptionlist', $data);
    }

    public function transactionList()
    {
        Helpers::hasRole(1, 26);
        $dates = Helpers::setListDates();
        $data = Helpers::setBladeProperties('Transaction list', [], [136, 139]);
        $data['datatablejs'] = 'table-no-export';
        $list = $this->autocollect_model->getTransactionList($this->merchant_id, $dates['from_date'], $dates['to_date']);
        $data['list'] = $list;
        return view('app/merchant/autocollect/transactionlist', $data);
    }

    public function deletePlan($link)
    {
        Helpers::hasRole(3, 26);
        $id = Encrypt::decode($link);
        $this->autocollect_model->deletePlan($id, $this->merchant_id, $this->user_id);
        Session::flash('success', 'Plan has been deleted.');
        return redirect('/merchant/autocollect/plans');
    }

    public function deleteSubscription($link)
    {
        Helpers::hasRole(3, 26);
        $id = Encrypt::decode($link);
        $result = Helpers::APIrequest('v1/autocollect/subscription/delete/' . $id, '', "GET");
        Session::flash('success', 'Subscription has been deleted.');
        return redirect('/merchant/autocollect/subscriptions');
    }

    function paymentstatus()
    {
        Log::error('cashfree subscription status' . json_encode($_POST));
        Log::error('cashfree subscription status' . json_encode($_GET));
    }
}
