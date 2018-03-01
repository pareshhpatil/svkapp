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
        return view('auth.denied');
        exit;
    }

    public function expired() {
        return view('auth.expired');
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
            $_SESSION['user_type'] = $userObj->user_type;
            $_SESSION['logo'] = $userObj->logo;
            CustomSession::set('logged_in', 1);
            CustomSession::set('user_name', $userObj->name);
            CustomSession::set('user_id', $userObj->user_id);
            CustomSession::set('company_name', $userObj->company_name);
            CustomSession::set('admin_id', $userObj->admin_id);
            CustomSession::set('user_type', $userObj->user_type);
            CustomSession::set('logo', $userObj->logo);


            header('location: /admin/dashboard');
            exit;
        } else {
            return view('auth.login', array('error' => 'There was an error with your E-Mail/Password combination. Please try again.'));
        }
    }

}
