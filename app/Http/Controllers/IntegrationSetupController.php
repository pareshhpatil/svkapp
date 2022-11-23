<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Model\IntegrationSetup;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AppController;

class IntegrationSetupController extends AppController
{
    public function index(Request $request)
    {

        $model = new IntegrationSetup();
        $stripesuccess = 0;
        if ($request->code) {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $response = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->code
            ]);
            $merchant_id = Encrypt::decode(Session::get('merchant_id'));
            // $payment_gateway = $model->addPaymentGateway($response, $merchant_id);
            $model->updateStripeAccount($response->stripe_user_id, $merchant_id);
            Session::flash('success', 'You stripe payment gateway is successfully integrated');
            return redirect('/merchant/integrations');
        }

        $merchant_id = Encrypt::decode(Session::get('merchant_id'));

        $data = Helpers::setBladeProperties('Integrations', ['benefit'], []);

        //get all active PG list with stripe PG
        $getAllActivePGDetails = $model->getActivePG($merchant_id, $all_PG = 0);
        $data['activePG'] = $getAllActivePGDetails;

        //get all active PG list without stripe PG
        $data['getActivePGS'] = $model->getActivePG($merchant_id, $all_PG = 1);

        $data['checkKYCComplete'] = $model->getColumnValue('merchant', 'merchant_id', $merchant_id, 'is_legal_complete');
        $data['bank_detail'] = $model->getTableRow('merchant_bank_detail', 'merchant_id', $merchant_id);

        Session::put('valid_ajax', 'integration-details');
        $data['list'] = $model->getIntegrations();

        return view('app/merchant/integration-setup/index', $data);
    }

    public function getSetupDetails()
    {

        $request_id = $_POST['integration_id'];
        $pg_id = $_POST['pg_id'];
        //$request_id = $this->encrypt->decode($request_id);
        $merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $model = new IntegrationSetup();
        $integrationData = $model->getIntegrationDetails($request_id);

        //get active PG details
        if ($pg_id != '') {
            $getActivePGDetails = DB::table('merchant_fee_detail')
                ->select('merchant_fee_detail.merchant_id', 'pg_val1', 'pg_val2', 'pg_val3', 'pg_val4', 'merchant_fee_detail.pg_id', 'merchant_fee_detail.is_active', 'payment_gateway.pg_type', 'payment_gateway.pg_id')
                ->join('payment_gateway', 'payment_gateway.pg_id', '=', 'merchant_fee_detail.pg_id')
                //->join('merchant','merchant.merchant_id','=','merchant_fee_detail.merchant_id')
                ->where('merchant_fee_detail.merchant_id', $merchant_id)
                ->where('payment_gateway.pg_id', $pg_id)
                ->where('merchant_fee_detail.is_active', 1)->first();
        } else {
            $getActivePGDetails = DB::table('merchant_fee_detail')
                ->select('merchant_fee_detail.merchant_id', 'merchant_fee_detail.pg_id', 'merchant_fee_detail.is_active', 'payment_gateway.pg_type', 'payment_gateway.pg_id')
                ->join('payment_gateway', 'payment_gateway.pg_id', '=', 'merchant_fee_detail.pg_id')
                //->join('merchant','merchant.merchant_id','=','merchant_fee_detail.merchant_id')
                ->where('merchant_fee_detail.merchant_id', $merchant_id)
                ->where('payment_gateway.pg_type', $integrationData->integration_type)
                ->where('merchant_fee_detail.is_active', 1)->first();
        }


        //dd($getActivePGDetails);
        //check KYC complete or not
        $checkKYCComplete = $model->getColumnValue('merchant', 'merchant_id', $merchant_id, 'is_legal_complete');

        return view('app/merchant/integration-setup/integration-view-detail', compact('integrationData', 'getActivePGDetails', 'checkKYCComplete', 'pg_id'));
        //echo json_encode($list);
    }

    function integrations(Request $request)
    {
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
        return view('getting-started/integrations', $data);
    }

    function stripeConnect()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        return redirect()->secure('https://connect.stripe.com/oauth/authorize?response_type=code&client_id=' . env('STRIPE_CLIENT_ID') . '&scope=read_write');
    }
    function payoneerResponse(Request $request)
    {
        $url = env('PAYONEER_API_URL') . '/v2/programs/' . env('PAYONEER_PARTNER_ID') . '/payees/' . $this->merchant_id . '/details';
        $secret = base64_encode(env('PAYONEER_USERNAME') . ':' . env('PAYONEER_API_PASSWORD'));
        $header = array(
            "Authorization: Basic " . $secret,
            "Content-Type: application/json"
        );

        $response = Helpers::APIrequest($url, '', "GET",  true, $header);
        $response = json_decode($response, 1);
        $model = new IntegrationSetup();
        $model->updateStripeAccount(null, $this->merchant_id, 1, $response['payoneer_id']);
        Session::flash('success', 'Payoneer payment gateway is successfully integrated');
        return redirect('/merchant/integrations');
    }

    function payoneerConnect()
    {
        $url = env('PAYONEER_API_URL') . '/v2/programs/' . env('PAYONEER_PARTNER_ID') . '/payees/registration-link';
        $secret = base64_encode(env('PAYONEER_USERNAME') . ':' . env('PAYONEER_API_PASSWORD'));
        $header = array(
            "Authorization: Basic " . $secret,
            "Content-Type: application/json"
        );
        $data = array('payee_id' => $this->merchant_id, 'redirect_url' => env('APP_URL') . '/merchant/integrations/payoneer/response');
        $data['payee']['type'] = 'COMPANY';
        $data['payee']['company']['name'] = Session::get('company_name');
        $data['payee']['contact']['first_name'] = Session::get('display_name');
        $data['payee']['contact']['last_name'] = Session::get('last_name');
        $data['payee']['contact']['email'] = Session::get('email_id');
        $data['payee']['contact']['mobile'] = Session::get('mobile');
        $response = Helpers::APIrequest($url, json_encode($data, true), "POST",  true, $header);
        $response = json_decode($response, 1);
        if (isset($response['registration_link'])) {
            return redirect()->secure($response['registration_link']);
        } else {
            return redirect()->back()->withErrors(['msg' => 'Something went please try again later.']);
        }
    }

    function stripeSave(Request $request)
    {
        $model = new IntegrationSetup();
        if ($request->pg_id > 0) {
            DB::table('payment_gateway')
                ->where('pg_id', $request->pg_id)
                ->update([
                    'pg_val1' => $request->stripe_user_id,
                    'pg_val4' => $request->secret
                ]);
        } else {
            $model->addPaymentGateway($request, $this->merchant_id);
        }

        Session::flash('success_title', 'Stripe credentials saved');
        Session::flash('success', 'Stripe credentials you entered are updated in your account. Your invoices are now integrated with Stripe payments.');
        return redirect('/merchant/integrations');
    }

    function payoneerAccount()
    {
        $data = file_get_contents('php://input');
        $response = json_decode($data, 1);
        if (isset($response['Payee Id'])) {
            $merchant_id = $response['Payee Id'];
            $model = new IntegrationSetup();
            $model->updateStripeAccount(null, $merchant_id, 2, $response['Payoneer Id']);
            $company_name = $model->getColumnValue('merchant', 'merchant_id', $merchant_id, 'company_name');
            $model->addPayoneerGateway($company_name, $merchant_id);
        }
    }
}
