<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\ParentModel;
use App\Models\ApiModel;
use Validator;
use App\Http\Lib\Encryption;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\MasterController;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function otp($link)
    {
        if ($link == '__manifest.json') {
            return false;
        }
        $model =  new ApiModel();
        $otp_id = Encryption::decode($link);
        $row = $model->getTableRow('otp', 'id', $otp_id, 1);
        if ($row == false) {
            return redirect('/login');
        }
        $data = json_decode(json_encode($row), 1);
        $data['link'] = $link;
        return view('auth.otp', $data);
    }

    public function resendotp($link)
    {
        $apicontroller = new ApiController();
        $model =  new ApiModel();
        $otp_id = Encryption::decode($link);
        $row = $model->getTableRow('otp', 'id', $otp_id, 1);
        if ($row != false) {
            $otp = rand(1111, 9999);
            //$message = $otp . ' is OTP to verify your mobile number with Siddhivinayak Travels House';
            $apicontroller->sendSMS($row->mobile, ['var1' => $otp], '67fd2c55d6fc05588a2e1d03');
            $id = $model->saveOtp($row->mobile, $otp, $row->user_id);
            return redirect('/login/otp/' . Encryption::encode($id));
        } else {
            return back()->withErrors([
                'mobile' => 'Mobile is not registered.'
            ]);
        }
    }
    public function sendotp(Request $request)
    {
        $apicontroller = new ApiController();
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:10|max:10'
        ]);
        if ($validator->fails()) {
            return back()->withErrors([
                $validator->errors()
            ]);
        }
        $model =  new ApiModel();
        $data = $model->getTableRow('users', 'mobile', $request->mobile, 1);
        if ($data == false) {
            $passenger = $model->getTableRow('passenger', 'mobile', $request->mobile, 1);
            if ($passenger != false) {
                $array['user_type'] = 5;
                $array['role_id'] = 1;
                $array['admin_id'] = 1;
                $array['name'] = $passenger->employee_name;
                $array['gender'] = $passenger->gender;
                $array['email'] = $passenger->email;
                $array['mobile'] = $passenger->mobile;
                $array['user_name'] = $passenger->mobile;
                $array['address'] = $passenger->address;
                $array['location'] = $passenger->location;
                $array['project_id'] = $passenger->project_id;
                $array['parent_id'] = $passenger->id;
                $array['password'] = 'napasswe';
                $array['company_name'] = 'Siddhivinayak Travels';
                $passenger = $model->saveTable('users', $array, 0);
                $data = $model->getTableRow('users', 'mobile', $request->mobile, 1);
            }


            if ($data == false) {
                $driver = $model->getTableRow('driver', 'mobile', $request->mobile, 1);
                if ($driver != false) {
                    $array['user_type'] = 4;
                    $array['role_id'] = 1;
                    $array['admin_id'] = 1;
                    $array['name'] = $driver->name;
                    $array['gender'] = 'Male';
                    $array['email'] = $driver->email;
                    $array['mobile'] = $driver->mobile;
                    $array['user_name'] = $driver->mobile;
                    $array['address'] = $driver->address;
                    $array['location'] = $driver->location;
                    $array['project_id'] = 0;
                    $array['parent_id'] = $driver->id;
                    $array['password'] = 'napassweq';
                    $array['company_name'] = 'Siddhivinayak Travels';
                    $passenger = $model->saveTable('users', $array, 0);
                    $data = $model->getTableRow('users', 'mobile', $request->mobile, 1);
                }
            }
        }
        if ($data != false) {
            $test_array = array('9999999999', '9999999993', '9999999995', 'a9730946150');
            $otp = rand(1111, 9999);
            if (in_array($request->mobile, $test_array)) {
                $otp = '1234';
            } else {
                $message = $otp . ' is OTP to verify your mobile number with Siddhivinayak Travels House';
                //$apicontroller->sendSMS($request->mobile, $message, '1107168138576339315');
                $apicontroller->sendSMS($request->mobile, ['var1' => $otp], '67fd2c55d6fc05588a2e1d03');

                //$MasterController = new MasterController();
                //$json = '{"messaging_product":"whatsapp","to":"91' . $request->mobile . '","type":"template","template":{"name":"otp","language":{"code":"en"},"components":[{"type":"body","parameters":[{"type":"text","text":"' . $otp . '"}]}]}}';
                //$MasterController->sendWhatsappMessage(json_decode($json, 1));
                //$this->notifyAdmin('App login user Name: ' . $data->name . ' Mobile: ' . $request->mobile . ' OTP: ' . $otp);
            }
            $id = $model->saveOtp($request->mobile, $otp, $data->id);
            return redirect('/login/otp/' . Encryption::encode($id));
        } else {
            //$this->notifyAdmin('App new login user Mobile: ' . $request->mobile);
            return back()->withErrors([
                'mobile' => 'Mobile is not registered.'
            ]);
        }
    }

    public function validateOTP(Request $request)
    {
        $model =  new ApiModel();
        #validate request
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits_between:10,10',
            'otp' => 'required|digits_between:4,4'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        #check OTP is valid with mobile number
        $data = $model->getTableRow('otp', 'mobile', $request->mobile, 1, ['otp' => $request->otp]);
        if ($data != false) {
            if ($request->token != '') {
                $model->updateTable('users', 'id', $data->user_id, 'token', $request->token);
            }
            Auth::loginUsingId($data->user_id, true);
            $user = Auth::user();
            Session::put('user_id', $user->id);
            Session::put('admin_id', $user->admin_id);
            Session::put('role_id', $user->role_id);
            Session::put('user_type', $user->user_type);
            Session::put('name', $user->name);
            Session::put('email', $user->email);
            Session::put('mobile', $user->mobile);
            Session::put('parent_id', $user->parent_id);
            Session::put('project_id', $user->project_id);
            Session::put('gender', $user->gender);
            Session::put('token', $user->token);
            Session::put('show_ad', $user->show_ad);
            if ($user->role_id == 2) {
                $user_access = $model->getTableRow('user_access', 'user_id',  $user->id);
                if ($user_access != false) {
                    Session::put('user_access', json_decode(json_encode($user_access), 1));
                }
            }
            if ($user->dark_mode == 1) {
                Session::put('mode', 'dark-mode');
            } else {
                Session::put('mode', '');
            }
            if ($user->icon == '') {
                $user->icon =  '/assets/img/avatars/' . $user->gender . '.png';
            }
            Session::put('icon', $user->icon);
            if ($user->role_id == 2) {
                return redirect('staff/dashboard');
            }
            return redirect('/dashboard');
        } else {
            return back()->withErrors([
                'otp' => 'Invalid OTP please try again',
            ]);
        }
    }

    public function verify(Request $request)
    {
        $user = new User();
        $result = $user->userLogin($request->user_name, $request->password);
        if ($result == false) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        } else {
            Auth::loginUsingId($result->id);
            $user = Auth::user();
            Session::put('user_id', $user->id);
            Session::put('name', $user->name);
            Session::put('role_id', $user->role_id);
            Session::put('company_name', $user->company_name);
            Session::put('email', $user->email);
            Session::put('mobile', $user->mobile);
            Session::put('project_id', $user->project_id);
            $this->setMenu($user->role_id);
            return redirect('/home');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->to('/login');
    }

    public function setMenu($role_id)
    {
        $model = new ParentModel();
        if ($role_id == 1) {
            $list = $model->getTableListOrderby('menu', 'is_active', 1, 'seq');
        }
        $list = json_decode(json_encode($list), 1);
        $array = array();
        foreach ($list as $row) {
            $array[$row['parent_id']][$row['id']] = $row;
        }
        Session::put('menus', $array);
    }
}
