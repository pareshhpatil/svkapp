<?php

namespace App\Http\Controllers;

use App\Http\Lib\Encryption;
use Illuminate\Http\Request;
use App\Models\ApiModel;
use App\Models\User;
use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;
use Illuminate\Support\Facades\Auth;


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
            $id=$this->model->saveOtp($request->mobile, $otp, $data->id);
            $success['token'] = Encryption::encode($id);
            $success['message'] = 'OTP send successfully';
            return response()->json($success, 200);
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
            $userd = User::where('id', $data->user_id)->first();
            $apiToken =$userd->createToken("API TOKEN")->plainTextToken;

            $this->model->saveToken($request->token, $data->user_id);
            $str = rand() . $data->user_id;
            $token = md5($str);
            $this->model->updateTable('otp', 'id', $data->id, 'is_active', 0);
            $this->model->updateTable('users', 'id', $data->user_id, 'token', $token);


            $success['user_id']=$user->id;
            $success['user_type']=$user->user_type;
            $success['name']=$user->name;
            $success['email']=$user->email;
            $success['mobile']=$user->mobile;
            $success['parent_id']=$user->parent_id;
            $success['project_id']=$user->project_id;
            $success['gender']=$user->gender;
            $success['token']=$apiToken;
            $success['mode']=$user->dark_mode;
            $success['icon']=$user->icon;
           

            return response()->json($success, 200);
        } else {
            return response()->json(['error' => 'Invalid OTP'], 401);
        }
    }


    public function sendSMS($number_, $message_, $template_id)
    {
        $message_ = str_replace(" ", "%20", $message_);
        $message_ = str_replace("&", "%26", $message_);
        $message_ = preg_replace("/\r|\n/", "%0a", $message_);
        $invokeURL = env('SMS_URL');
        $invokeURL = str_replace("__MESSAGE__", $message_, $invokeURL);
        $invokeURL = str_replace("__NUM__", $number_, $invokeURL);
        $invokeURL = str_replace("__TEMPLATE_ID__", $template_id, $invokeURL);
        $client = new Client();
        $request = new Req('GET', $invokeURL);
        $res = $client->sendAsync($request)->wait();
    }

    public function ivrCall($from, $to)
    {
        $body['form_params']['authkey'] = env('IVR_KEY');
        $body['form_params']['agentmobile'] = $from;
        $body['form_params']['customermobile'] =  $to;
        $client = new Client();
        $response = $client->request('POST', 'https://beta.teleforce.in/api/tfapi/clicktocall', $body);
        return $response->getBody()->getContents();
    }

    public function userSMS($user_id, $user_type, $message_, $template_id)
    {
        $number_ = $this->model->getColumnValue('users', 'parent_id', $user_id, 'mobile', ['user_type' => $user_type]);
        if ($number_ == false && $user_type == 5) {
            $number_ = $this->model->getColumnValue('passenger', 'id', $user_id, 'mobile');
        }
        $this->sendSMS($number_, $message_, $template_id);
    }


    public function sendNotification($user_id, $user_type, $title, $body, $url = '', $image = 'https://app.svktrv.in/assets/img/banner.png', $tokens = [])
    {
        if (empty($tokens)) {
            $token = $this->model->getColumnValue('users', 'parent_id', $user_id, 'token', ['user_type' => $user_type]);
            if ($token != '') {
                $tokens[] = $token;
            }
        }

        if (!empty($tokens)) {
            $key = env('FIREBASE_KEY');
            $array['registration_ids'] = $tokens;
            $array['notification']['body'] = $body;
            $array['notification']['title'] = $title;
            $array['notification']['sound'] = "default";
            if ($image != '') {
                $array['notification']['image'] = $image;
            }
            $array['notification']['content_available'] = true;
            $array['notification']['priority'] = "normal";
            $array['notification']['sound'] = "default";
            if ($url != '') {
                $array['notification']['link'] = $url;
                $array['data']['deepLink'] = $url;
            }

            $client = new Client();
            $headers = [
                'Authorization' => 'key=' . $key,
                'Content-Type' => 'application/json'
            ];
            $body = json_encode($array);

            $request = new Req('POST', 'https://fcm.googleapis.com/fcm/send', $headers, $body);
            $res = $client->sendAsync($request)->wait();
        }
    }
}
