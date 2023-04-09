<?php

/*
 * Class helps in sending SMS out using mGage integration
 */

/**
 * Description of SMSHelper
 *
 * @author Paresh
 */

namespace App\Lib;

use Log;

class SMSSender {

    private $_mode = NULL;
    public $url = '';
    public $val1 = '';
    public $val2 = '';
    public $val3 = '';
    public $val4 = '';
    public $val5 = '';

    function __construct($mode_ = NULL) {
        if ($mode_ == NULL) {
            $this->_mode = getenv('APP_ENV');
        } else {
            $this->_mode = $mode_;
        }
        $this->url = getenv('SMS_URL');
        $this->val1 = getenv('SMS_USERNAME');
        $this->val2 = getenv('SMS_PASSWORD');
    }

    function send($message_, $number_, $is_error = FALSE) {
        if ($number_ == '9999999999') {
            return;
        }
        $message_ = str_replace(" ", "%20", $message_);
        $message_ = str_replace("&", "%26", $message_);
        $invokeURL = $this->url;
        $invokeURL = str_replace("__VAL1__", $this->val1, $invokeURL);
        $invokeURL = str_replace("__VAL2__", $this->val2, $invokeURL);
        $invokeURL = str_replace("__VAL3__", $this->val3, $invokeURL);
        $invokeURL = str_replace("__VAL4__", $this->val4, $invokeURL);
        $invokeURL = str_replace("__VAL5__", $this->val5, $invokeURL);
        $invokeURL = str_replace("__MESSAGE__", $message_, $invokeURL);
        $invokeURL = str_replace("__NUM__", $number_, $invokeURL);

        $response = "";
        $this->_mode = "PROD";
        //Send SMS only if the mode is set to LIVE or DEV
        if ($this->_mode == "PROD" && strlen($number_) > 9) {
            # check unsubscribe mobile 
            Log::debug('sending sms to ' . $number_ . ' Message: ' . $message_);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $invokeURL); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $response = curl_exec($ch);
            Log::info('SMS response ' . json_encode($response));
            #$response = "Message Accepted for Request ID=70414170921368011248849~code=API000 & info=Air2web accepted & Time =2014/11/27/18/12";
            #$response="Message Rejected for Request ID= ~code=API-3& info=REJECTED: Empty mnumber& Time = 2014/11/27/19/47";
            #$response = NULL;

            $error = curl_error($ch);
            if ($error != "") {
                $responseArr["response_id"] = -1;
                $responseArr["response_code"] = -1;
                $responseArr["response_msg"] = -1;
                $responseArr["response_datetime"] = -1;
                $responseArr['response_error'] = $error;
                return $responseArr;
            }
            curl_close($ch);

            $responseArr = $this->handleResponse($response);
            #logging error if sms sending failed
            if ($responseArr[0] == 'R') {
                Log::error('SMS Failed to ' . $number_ . ' Error: ' . $responseArr);
            } else {
                Log::info('SMS sent to ' . $number_ . ' Message: ' . $message_);
            }
            return $responseArr;
        } else {
            //$response = "Message Accepted for Request ID=70414170921368011248849~code=API000 & info=Air2web accepted & Time =2014/11/27/18/12";
        }
    }

    function handleResponse($response_) {

        $match = NULL;
        preg_match("/(.*?ID=)(.*?)~code=(.*?)[\s|\&].*?info=(.*?)\&.*?=(.*)/", $response_, $match);
        #preg_match("/(.*?ID=)(.*?)~code=(.*?)\s.*?info=(.*?)\&.*?=(.*)/", $response_, $match);

        $responseArr["response"] = $response_;
        if (is_array($match)) {
            $responseArr["response_id"] = isset($match[2]) ? $match[2] : "NA";
            $responseArr["response_code"] = isset($match[3]) ? $match[3] : "NA";
            $responseArr["response_msg"] = isset($match[4]) ? $match[4] : "NA";
            $responseArr["response_datetime"] = isset($match[5]) ? $match[5] : "NA";
        } else {
            $responseArr["response_id"] = -1;
            $responseArr["response_code"] = -1;
            $responseArr["response_msg"] = -1;
            $responseArr["response_datetime"] = -1;
        }
        return $responseArr['response_msg'];
    }

    function fetchMessage($type) {
        $messageList["m1"] = "You have received a payment request from __CORPORATE_NAME__ for amount __AMOUNT__. To make an online payment, access your bill via __LINK__";
        $messageList["m2"] = "You have made a payment to __COMPANY_NAME__ for an amount of __AMOUNT__/-. Transaction ref id is __REF_NO__. Merchant credits subject to clearing";
        return $messageList[$type];
    }

}
