<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use View;

#Crypt::encryptString('Hello world.')
#Crypt::decryptString($encrypted)

class ErrorController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function display() {
        @session_start();
        if (isset($_SESSION['error_message'])) {
            $data['message'] = $_SESSION['error_message'];
            $data['title'] = $_SESSION['error_title'];
            unset($_SESSION['error_title']);
            unset($_SESSION['error_message']);
        } else {
            $data['message'] = 'Error in system for this process please try again later.';
            $data['title'] = 'Error';
        }
        return view('error.display', $data);
    }

    public function pagenotfound() {
        return view('error.404', array());
    }

}
