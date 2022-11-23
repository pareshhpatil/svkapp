<?php

namespace App\Http\Controllers;

use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use App\Libraries\Helpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LookupController extends Controller {
    private $merchant_id = null;
    private $user_id = null;

    public function __construct() {
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    /**
     * Renders form to accept user input of registration like email id and mobile number
     * Also handles the post request from the same form and displays search results
     *
     * @param Request $request_ HTTP request object from Laravel
     * @return void nothing!
     */
    public function registration(Request $request_) {
        $data = Helpers::setBladeProperties('Lookup registrations', [], [158, 159]);

        $requestMethod = $request_->method();
        if($requestMethod == 'POST') {
            $data = $this->handleRegistrationPost($request_, $data);
        }
        else {
            //For GET method check in session flash if value of text box exists
            $search_criteria = $request_->session()->get('user_input', null);
            $data['search_criteria'] = $search_criteria;
        }
        return view('app/merchant/lookup/registration', $data);
    }

    /**
     * Process post request for registration search request
     *
     * @param Request $request_ Request object from post
     * @param [type] $data_ Data object to be shared with the view
     * @return void return Data to be shared with the view
     */
    private function handleRegistrationPost(Request $request_, $data_) {
        $result = array();

        $search_criteria = (isset($_POST['search_criteria'])) ? $_POST['search_criteria'] : null;
        $data['search_criteria'] = $search_criteria;

        //Store user input in session to retrieve in case of validation error via GET method
        $request_->session()->flash('user_input', $search_criteria);

        //Validate user input
        if(is_numeric($search_criteria)) {
            $validatedData = $request_->validate([
                'search_criteria' => 'required|digits:10'
            ]);
            $result = $this->lookupMobileNo($search_criteria);
        }
        else {
            $validatedData = $request_->validate([
                'search_criteria' => 'required|email'
            ]);
            $result = $this->lookupEmailId($search_criteria);
        }

        if(!empty($result)) {
            $data_['first_name'] = $result->first_name;
            $data_['last_name'] = $result->last_name;
            $data_['email_id'] = $result->email_id;
            $data_['mobile_no'] = $result->mobile_no;
            $data_['user_status'] = $result->user_status;

            $registered_date = date("M j, Y", strtotime($result->created_date));
            $data_['registered_date'] = $registered_date;
            $data_['rows'] = 1;
        }
        else {
            $data_['rows'] = 0;
        }

        return $data_;
        //In case of comma seperated search criteria use closure to define custom
        //validation method
        /*$validatedData = $request->validate([
            'search_criteria' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    $returnValue = $this->validateEmailOrMobile($value);
                    if (!$returnValue) {
                        $fail($value.' is not a valid email id or mobile number.');
                    }
                },
            ],
        ]);*/
    }

    /**
     * Gets a mobile number value and queries database user table for matched row
     *
     * @param numeric $mobileNo
     * @return array database row
     */
    private function lookupMobileNo($strMobileNo_) {
        Log::debug('lookupMobileNo');
        $user = DB::table('user')->where('mobile_no', $strMobileNo_)->first();
        return $user;
    }

    /**
     * Gets a email id and queries database user table for matched row
     *
     * @param numeric $mobileNo
     * @return array database row
     */
    private function lookupEmailId($strEmailId_) {
        Log::debug('lookupEmailId');
        $user = DB::table('user')->where('email_id', $strEmailId_)->first();
        return $user;
    }

    /**
     * Takes an input and validates if the input is a valid email id or mobile number
     * Passes false if valdation fails
     *
     * @param [String] $inputStr
     * @return void
     */
    private function validateEmailOrMobile($inputStr) {

        return true;
    }
}
