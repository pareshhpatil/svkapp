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
        $this->middleware('guest')->except('logout');
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
