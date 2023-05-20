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
        $model =  new ApiModel();
        $otp_id = Encryption::decode($link);
        $row = $model->getTableRow('otp', 'id', $otp_id, 1);
        $data = json_decode(json_encode($row), 1);
        $data['link'] = $link;
        return view('auth.otp', $data);
    }

    public function resendotp($link)
    {

        $model =  new ApiModel();
        $otp_id = Encryption::decode($link);
        $row = $model->getTableRow('otp', 'id', $otp_id, 1);
        if ($row != false) {
            $otp = rand(1111, 9999);
            $otp = '1234';
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
        if ($data != false) {
            $otp = rand(1111, 9999);
            $otp = '1234';
            $id = $model->saveOtp($request->mobile, $otp, $data->id);
            return redirect('/login/otp/' . Encryption::encode($id));
        } else {
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
                $data = $model->updateTable('users', 'id', $data->user_id, 'token', $request->token);
            }
            Auth::loginUsingId($data->user_id, true);
            $user = Auth::user();
            Session::put('user_id', $user->id);
            Session::put('user_type', $user->user_type);
            Session::put('name', $user->name);
            Session::put('email', $user->email);
            Session::put('mobile', $user->mobile);
            Session::put('parent_id', $user->parent_id);
            Session::put('project_id', $user->project_id);
            Session::put('gender', $user->gender);
            if ($user->dark_mode == 1) {
                Session::put('mode', 'dark-mode');
            } else {
                Session::put('mode', '');
            }
            if ($user->icon == '') {
                $user->icon =  '/assets/img/avatars/' . $user->gender . '.png';
            }
            Session::put('icon', $user->icon);
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
