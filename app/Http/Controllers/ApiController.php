<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiModel;
use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;
use Illuminate\Support\Facades\Http;
use App\Models\StaffModel;
use Illuminate\Support\Facades\Log;

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
            $this->model->aveToken($request->token, $data->user_id);
            $str = rand() . $data->user_id;
            $token = md5($str);
            $this->model->updateTable('otp', 'id', $data->id, 'is_active', 0);
            $this->model->updateTable('users', 'id', $data->user_id, 'token', $token);
            $success['message'] = 'Login success';

            $success['user']['profile_photo'] = 'https://siddhivinayaktravel.in/dist/uploads/employee/1682156612.jpg';
            $success['user_detail'][] = array('title' => 'Name', 'value' => $user->name);
            $success['user_detail'][] = array('title' => 'Email', 'value' => $user->email);
            $success['user_detail'][] = array('title' => 'Mobile', 'value' => $user->mobile);
            $success['user_detail'][] = array('title' => 'Gender', 'value' => $user->gender);
            $success['user_detail'][] = array('title' => 'Address', 'value' => $user->address);
            $success['user_detail'][] = array('title' => 'Company name', 'value' => $user->company_name);

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
            $token = $this->model->getColumnValue('users', 'parent_id', $user_id, 'token', ['user_type' => $user_type, 'app_notification' => 1]);
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

    function sendWhatsappMessage($user_id, $user_type, $template_name, $params, $button_link = null, $lang = 'en', $store = 0)
    {
        $button_json = '';
        if ($button_link != null) {
            $button_json = ',{"type":"button","index":"0","sub_type":"url","parameters":[{"type":"text","text":"' . $button_link . '"}]}';
        }

        if ($user_type == 'mobile') {
            $mobile = $user_id;
        } else {
            $mobile = $this->model->getColumnValue('users', 'parent_id', $user_id, 'mobile', ['user_type' => $user_type, 'whatsapp_notification' => 1]);
            if ($mobile == false) {
                if ($user_type == 4) {
                    $mobile = $this->model->getColumnValue('driver', 'id', $user_id, 'mobile');
                } elseif ($user_type == 5) {
                    $mobile = $this->model->getColumnValue('passenger', 'id', $user_id, 'mobile');
                }
            }
        }



        if ($mobile != false && strlen($mobile) == 10) {
            $failed = $this->model->getColumnValue('whatsapp_failed', 'mobile', $mobile, 'id');
            if ($failed == false) {

                $json = '{"messaging_product":"whatsapp","to":"91' . $mobile . '","type":"template","template":{"name":"' . $template_name . '","language":{"code":"' . $lang . '"},"components":[{"type":"body","parameters":[]}' . $button_json . ']}}';
                $array = json_decode($json, 1);
                $array['template']['components'][0]['parameters'] = $params;
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('WHATSAPP_TOKEN'),
                    'Content-Type' => 'application/json', // Specify JSON content type if sending JSON data
                ])->post('https://graph.facebook.com/v19.0/350618571465341/messages', $array);

                if ($response->successful()) {
                    $responseData = $response->json(); // Convert response to JSON
                    // Handle successful response
                    $name = $this->model->getColumnValue('employee', 'mobile', $mobile, 'name');
                    $name = ($name == false) ? '' : $name;
                    $StaffModel = new StaffModel();
                    $message = $this->getWhatsappMessage($template_name, $params, $button_link);
                    $StaffModel->saveWhatsapp($mobile, $name, 'Sent', 'Sent', 'text', $message, $responseData['messages'][0]['id']);

                    return $responseData['messages'][0]['id'];
                } else {
                    //
                    Log::error($response->status() . '-' . $response->body());
                    //dd($response->status(), $response->body()); // Output status code and response body for debugging
                }
            }
        }
    }

    function getWhatsappMessage($type, $params, $link)
    {
        $messages['ride_status'] = "<b>Ride Status Update</b>

        Status: {{1}}

        Details:
        Booking ID: {{2}}
        Passenger name: {{3}}
        Pickup Time: {{4}}
        Pickup Location: {{5}}
        Driver Name: {{6}}
        Driver Contact: {{7}}
        Link : https://app.svktrv.in/l/{{Link}}";

        $messages['booking_details'] = "<b>Booking details</b>

        Hello {{1}} ,

        We're excited to confirm your cab booking details:

        Booking ID: #{{2}}
        Passenger name: {{9}}
        Pickup Time: {{3}}
        Pickup Location: {{4}}
        Drop-off Location: {{5}}
        Cab Type: {{6}}
        Driver Name: {{7}}
        Driver Contact: {{8}}
        Link : https://app.svktrv.in/l/{{Link}}";

        $messages['driver_booking_details'] = "<b>बुकिंग अलर्ट</b>

        नमस्ते {{1}}

        बुकिंग आईडी: #{{2}}
        पिकअप स्थान: {{3}}
        ड्रॉप-ऑफ स्थान: {{4}}
        पिकअप समय: {{5}}
        यात्री का नाम: {{6}}
        यात्री संपर्क: {{7}}
        Link : https://app.svktrv.in/l/{{Link}}";

        if (!isset($messages[$type])) {
            return $type;
        }

        foreach ($params as $key => $row) {
            $key = $key + 1;
            $messages[$type] = str_replace('{{' . $key . '}}', $row['text'], $messages[$type]);
        }

        $messages[$type] = str_replace('{{Link}}', $link, $messages[$type]);

        return $messages[$type];
    }

    function validateAuth($auth)
    {
        return $this->model->getColumnValue('mataka_user', 'key', $auth, 'id');
    }


    function saveMataka(Request $request)
    {
        $user_id = $this->validateAuth($request->header('Auth'));
        if ($user_id != false) {
            $array = $request->all();
            if ($array['id'] > 0) {
                $this->model->updateTableData('mataka', 'id', $array['id'], $array);
            } else {
                unset($array['id']);
                $array['date'] = date('Y-m-d');
                $this->model->saveTable('mataka', $array, $user_id);
            }
        }
    }
    function loginMataka(Request $request)
    {
        if (env('MATAKA_TOKEN') == $request->header('Auth')) {
            $array = $request->all();
            $detail = $this->model->getTableRow('mataka_user', 'username', $array['user_name'], 1, ['password' => $array['password']]);
            if ($detail != false) {
                $return = json_decode(json_encode($detail), 1);
                $return['status'] = 'success';
                return response()->json($return);
            } else {
                $return['status'] = 'failed';
                return response()->json($return);
            }
        }
    }
    function getMataka(Request $request, $type, $date)
    {
        $user_id = $this->validateAuth($request->header('Auth'));
        $date = ($date == 'na') ? date('Y-m-d') : $date;
        $num = 10;
        $groupSize = 5;
        if ($type == 'open') {
            $num = 100;
        }
        $array = [];
        $summary = [];
        for ($i = 1; $i <= $num; $i++) {
            $group = floor(($i - 1) / $groupSize) * $groupSize + 1;
            //$array[$group][$i] = [];
        }

        if ($user_id != false) {
            $list = $this->model->getList('mataka', ['is_active' => 1, 'created_by' => $user_id, 'date' => $date, 'type' => $type]);
            foreach ($list as $row) {
                $number = (int)$row->number;
                $group = floor(($number - 1) / $groupSize) * $groupSize + 1;
                $i = 0;
                while (isset($array[$group][$i][$number])) {
                    $i++;
                }
                $array[$group][$i][$number][] = $row;
                if (isset($summary[$number])) {
                    $summary[$number] = $summary[$number] + $row->amount;
                } else {
                    $summary[$number] =  $row->amount;
                }
            }
            return response()->json(['rows' => $array, 'summary' => $summary]);
        }
    }

    function getMatakaNumbers(Request $request, $number, $date)
    {
        $user_id = $this->validateAuth($request->header('Auth'));
        if ($user_id != false) {
            $list = $this->model->getList('mataka', ['is_active' => 1, 'created_by' => $user_id, 'date' => $date, 'number' => $number]);
            return response()->json($list);
        }
    }
    function getMatakaLatest(Request $request, $count)
    {
        $user_id = $this->validateAuth($request->header('Auth'));
        if ($user_id != false) {
            $list = $this->model->getList('mataka', ['is_active' => 1, 'created_by' => $user_id], '*', $count, 'id');
            return response()->json($list);
        }
    }
    function getMatakaDetail(Request $request, $id)
    {
        $user_id = $this->validateAuth($request->header('Auth'));
        if ($user_id != false) {
            $detail = $this->model->getTableRow('mataka', 'id', $id, 1, ['created_by' => $user_id]);
            return response()->json($detail);
        }
    }

    function getMatakaSummary(Request $request)
    {
        $user_id = $this->validateAuth($request->header('Auth'));
        if ($user_id != false) {
            $summary = [];
            $total = 0;
            $open = 0;
            $bracket = 0;
            $transaction = [];
            $list = $this->model->getList('mataka', ['is_active' => 1, 'created_by' => $user_id, 'date' => date('Y-m-d')], '*', 0, 'id');
            foreach ($list as $k => $row) {
                if ($k < 5) {
                    $transaction[] = $row;
                }
                $total = $total + $row->amount;
                if ($row->type == 'open') {
                    $open = $open + $row->amount;
                } else {
                    $bracket = $bracket + $row->amount;
                }
            }
            $summary['total'] = number_format($total);
            $summary['open'] = number_format($open);
            $summary['bracket'] = number_format($bracket);
            $summary['transaction'] = $transaction;
            return response()->json($summary);
        }
    }
}
