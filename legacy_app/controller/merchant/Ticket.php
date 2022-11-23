<?php

/**
 * Template controller class to handle Template for merchant
 */
class Ticket extends Controller {

    function __construct() {
        parent::__construct();
    }

    function redirect() {
        $user_id = $this->session->get('system_user_id');
        $cookie_val = $user_id . time();
        $key = $this->encrypt->encode($cookie_val);
        $exist = $this->common->getRowValue('user_id', 'user_cookie', 'user_id', $user_id);
        if ($exist == false) {
            $this->model->insertUserCookie($user_id, $this->merchant_id, $key);
        } else {
            $this->model->updateUserCookie($user_id, $key);
        }
        $string = $key . '&' . $user_id . '&' . $this->merchant_id;
        $link = base64_encode($string);
        header('Location: https://helpdesk.swipez.in/auth/redirect/' . $link);
        exit();
    }

    function decode($link) {
        $decode = base64_decode($link);
        $array = explode('&', $decode);
        print_r($array);
    }

}
