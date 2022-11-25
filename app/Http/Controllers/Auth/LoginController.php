<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Exception;
use Log;

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

    public $maxAttempts = 3; // change to the max attemp you want.
    public $decayMinutes = 1; // change to the minutes you want.

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public $redirectTo = '/merchant/dashboard';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_updated_date';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect(env('APP_URL') . $this->redirectTo);
        }
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect(env('APP_URL') . '/merchant/dashboard');
    }

    /*
     * Overide credentials class for activate login for email and mobile also
     * @return array
     */

    public function credentials(Request $request)
    {
        if (is_numeric($request->get('email_id'))) {
            return ['mobile_no' => $request->get('email_id'), 'password' => $request->get('password')];
        } elseif (filter_var($request->get('email_id'), FILTER_VALIDATE_EMAIL)) {
            return ['email_id' => $request->get('email_id'), 'password' => $request->get('password')];
        }
        return ['email_id' => $request->get('email_id'), 'password' => $request->get('password')];
    }

    /*
     * Override this function for validate custom login
     */

    public function login(Request $request)
    {
        $this->validateLogin($request);
        // the login attempts for this application. We'll key this by the username and the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        // validate username and password
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();
            // Make sure the user is verified
            if ($this->attemptLogin($request) && in_array($user->user_status, array(2, 12, 13, 14, 15, 16, 20))) {
                $userController = new UserController();
                return $userController->setLoginSession($user);
            } else {
                Auth::logout();
                // Increment the failed login attempts and redirect back to the
                // login form with an error message.
                $this->incrementLoginAttempts($request);
                $error_message = 'Your email has not yet been verified. Please verify your email before logging in.';
                if ($user->user_status == 21 || $user->user_status == 18) {
                    $error_message = 'Your account has been disabled. Please contact to support@swipez.in';
                }
                return redirect('/login')->withErrors(['active' => $error_message]);
            }
        }
        $this->incrementLoginAttempts($request);
        return redirect('/login')->withErrors(['active' => 'These credentials do not match our records.']);
    }

    public function validateLogin(Request $request)
    {
        return $request->validate([
            'password' => 'required|string',
            // 'recaptcha_response' => 'required|recaptcha',
        ], $this->messages());
    }

    public function messages()
    {
        return [
            'recaptcha' => 'Invalid captcha please reload page and try again',
        ];
    }

    public function showLoginForm()
    {
        if (config('app.partner.login_url')) {
            return redirect(config('app.partner.login_url'));
        }
        SEOMeta::setTitle('Swipez | Login');
        OpenGraph::setTitle('Swipez | Login');
        OpenGraph::setUrl('https://www.swipez.in/login');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['login_error'] = '';
        $data['loader'] = true;
        $data['captcha'] = true;
        return view('home/login', $data);
    }
}
