<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiModel;
use Validator;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $model;
    public function __construct()
    {
        $this->model = new ApiModel();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:10|max:10'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }
        $data = $this->model->getTableRow('users', 'mobile', $request->mobile, 1);
        if ($data != false) {
            $otp = rand(1111, 9999);
            $otp = '1234';
            $this->model->saveOtp($request->mobile, $otp, $data->id);
            $success['message'] = 'OTP send successfully';
            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Mobile number is not registered, please contact to Admin'], 200);
        }
    }


    public function validateOTP(Request $request)
    {
        #validate request
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits_between:10,10',
            'otp' => 'required|digits_between:4,4'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        #check OTP is valid with mobile number
        $data = $this->model->getTableRow('otp', 'mobile', $request->mobile, 1, ['otp' => $request->otp]);

        if ($data != false) {
            #get latest user account details with mobile number
            $user = $this->model->getTableRow('users', 'id', $data->user_id);
            $str = rand() . $data->user_id;
            $token = md5($str);
            $this->model->updateTable('otp', 'id', $data->id, 'is_active', 0);
            $this->model->updateTable('users', 'id', $data->user_id, 'token', $token);
            $success['message'] = 'Login success';
            $success['user']['name'] = array('title' => 'Name', 'value' => $user->name);
            $success['user']['email'] = array('title' => 'Email', 'value' => $user->email);
            $success['user']['mobile'] = array('title' => 'Mobile', 'value' => $user->mobile);
            $success['user']['gender'] = array('title' => 'Gender', 'value' => $user->gender);
            $success['user']['address'] = array('title' => 'Address', 'value' => $user->address);
            $success['user']['company_name'] = array('title' => 'Company name', 'value' => $user->company_name);

            $success['links'][0]['title'] = 'Home';
            $success['links'][0]['icon'] = '0xf06d5';
            $success['links'][0]['link'] = env('APP_URL') . '/app/home/' . $token;

            $success['links'][1]['title'] = 'Trips';
            $success['links'][1]['icon'] = '0xee44';
            $success['links'][1]['link'] = env('APP_URL') . '/app/trips/' . $token;

            $success['links'][2]['title'] = 'Notification';
            $success['links'][2]['icon'] = '0xee3b';
            $success['links'][2]['link'] = env('APP_URL') . '/app/notification/' . $token;

            $success['links'][3]['title'] = 'Profile';
            $success['links'][3]['icon'] = '0xf0d9';
            $success['links'][3]['link'] = '';

            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Invalid OTP'], 401);
        }
    }
}
