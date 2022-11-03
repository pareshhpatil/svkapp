<?php

namespace App\Libraries;

/*
 * Class helps in sending SMS out using SMS integration
 */

/**
 * Description of SMSHelper
 *
 * @author Paresh
 */
class SMSSender {

    public $_mode = NULL;
    public $url = NULL;
    public $val1 = NULL;
    public $val2 = NULL;
    public $val3 = 'SWIPEZ';
    public $val4 = '';
    public $val5 = '';

    public function __construct() {
        $this->url = env('SMS_GATEWAY_URL');
        $this->val1 = env('SMS_USERNAME');
        $this->val2 = env('SMS_PASSWORD');
    }

    public function send($message_, $number_) {
        $this->_mode = env('APP_ENV');
        $message_ = str_replace(" ", "%20", $message_);
        $message_ = str_replace("&", "%26", $message_);
        $message_ = preg_replace("/\r|\n/", "%0a", $message_);
        $invokeURL = $this->url;
        $invokeURL = str_replace("__VAL1__", $this->val1, $invokeURL);
        $invokeURL = str_replace("__VAL2__", $this->val2, $invokeURL);
        $invokeURL = str_replace("__VAL3__", $this->val3, $invokeURL);
        $invokeURL = str_replace("__VAL4__", $this->val4, $invokeURL);
        $invokeURL = str_replace("__VAL5__", $this->val5, $invokeURL);
        $invokeURL = str_replace("__MESSAGE__", $message_, $invokeURL);
        $invokeURL = str_replace("__NUM__", $number_, $invokeURL);

        $response = "";
        //$this->_mode = "PROD";
        $testing_array = array('9730946150');
        if (in_array($number_, $testing_array)) {
            $this->_mode = "PROD";
        }
        //Send SMS only if the mode is set to LIVE or DEV
        if ($this->_mode == "PROD" && strlen($number_) > 9) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $invokeURL); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            $response = curl_exec($ch);
            $error = curl_error($ch);
            if ($error != "") {
                return $error;
            }
            curl_close($ch);
            $responseArr = json_decode($response, 1);
            $responseArr = $responseArr['ErrorMessage'];
            #logging error if sms sending failed
            return $responseArr;
        } else {
            //$response = "Message Accepted for Request ID=70414170921368011248849~code=API000 & info=Air2web accepted & Time =2014/11/27/18/12";
        }
    }

}
