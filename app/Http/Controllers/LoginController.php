<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardController
 *
 * @author Paresh
 */

namespace App\Http\Controllers;

use App\Model\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller {

    function __construct() {
        parent::__construct();
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function index(Request $request) {
        return view('auth.login');
    }

    public function logout(Request $request) {
        @session_start();
        session_destroy();
        Session::flush();
        header('Location: /login');
        exit;
    }

    public function accessdenied() {
        $data['title'] = 'Access denied';
        $data['company_name'] = Session::get('company_name');
        $data['company_logo'] = Session::get('logo');
        $data['current_date'] = date('d-m-Y');
        $user_type = Session::get('user_type');
        if ($user_type == 1) {
            $type = 'admin';
        } elseif ($user_type == 2) {
            $type = 'employee';
        } elseif ($user_type == 3) {
            $type = 'client';
        }
        $data['login_type'] = $type;
        return view('auth.denied', $data);
        exit;
    }

    public function expired() {
        return view('auth.expired');
    }

    public function getwordmoney($amount) {
        echo $this->displaywords($amount);
    }

    public function check(Request $request) {
        $user_name = $request->user_name;
        $password = $request->password;
        $login = new Login();
        $userObj = $login->hasEmailId($user_name, $password);
        if (!empty($userObj)) {
            @session_start();
            $_SESSION['logged_in'] = 1;
            $_SESSION['user_name'] = $userObj->name;
            $_SESSION['user_id'] = $userObj->user_id;
            $_SESSION['company_name'] = $userObj->company_name;
            $_SESSION['admin_id'] = $userObj->admin_id;
            $_SESSION['employee_id'] = $userObj->employee_id;
            $_SESSION['user_type'] = $userObj->user_type;
            $_SESSION['logo'] = $userObj->logo;
            CustomSession::set('logged_in', 1);
            CustomSession::set('user_name', $userObj->name);
            CustomSession::set('user_id', $userObj->user_id);
            CustomSession::set('company_name', $userObj->company_name);
            CustomSession::set('admin_id', $userObj->admin_id);
            CustomSession::set('employee_id', $userObj->employee_id);
            CustomSession::set('user_type', $userObj->user_type);
            CustomSession::set('logo', $userObj->logo);
            if ($userObj->user_type == 1) {
                $type = 'admin';
            } elseif ($userObj->user_type == 2) {
                $type = 'employee';
            } elseif ($userObj->user_type == 3) {
                $type = 'client';
            } elseif ($userObj->user_type == 4) {
                $type = 'vendor';
            }elseif ($userObj->user_type == 7) {
                $type = 'company';
            }
			elseif ($userObj->user_type == 5) {
                $type = 'admin';
            }
			
            header('location: /' . $type . '/dashboard');
            exit;
        } else {
            return view('auth.login', array('error' => 'There was an error with your E-Mail/Password combination. Please try again.'));
        }
    }

}
