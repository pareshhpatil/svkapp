<?php

/*
 * Class helps in sending SMS out using mGage integration
 */

/**
 * Description of SMSHelper
 *
 * @author Shuhaid
 */
class SMSSender {

    private $_mode = NULL;
    public $url = SMS_URL;
    public $val1 = SMS_USER_NAME;
    public $val2 = SMS_PASSWORD;
    public $val3 = SMS_SENDER;
    public $val4 = '';
    public $val5 = '';
    public $merchant_id = '';
    public $sms_gateway_type = 1;

    function __construct($mode_ = NULL) {
        if ($mode_ == NULL) {
            $this->_mode = getenv('ENV');
        } else {
            $this->_mode = $mode_;
        }
    }

    function send($message_, $number_, $is_error = FALSE) {
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
            # check unsubscribe mobile 
            if ($is_error == FALSE) {
                $model = new Model();
                $unsubMobile = $model->isUnsubscribe('mobile', $number_, $this->merchant_id);
                if ($unsubMobile == TRUE) {
                    SwipezLogger::info(__CLASS__, 'Unsubscribe Mobile exist: ' . $number_);
                    return;
                }
                if ($this->sms_gateway_type == 2) {
                    $is_valid = $model->isValidatePackage($this->merchant_id);
                    if ($is_valid == FALSE) {
                        SwipezLogger::info(__CLASS__, 'Sms pack is expired Merchant_id: ' . $this->merchant_id);
                        return;
                    }
                }
                if ($this->merchant_id != '') {
                    $model->addSMSCount($this->merchant_id);
                }
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $invokeURL); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $response = curl_exec($ch);
            $error = curl_error($ch);
            if ($error != "") {
                return $error;
            }
            curl_close($ch);

            $responseArr = $this->handleResponse($response);
            $this->handleResponseMail($response, $message_, $number_);
            #logging error if sms sending failed
            return $responseArr;
        } else {
            //$response = "Message Accepted for Request ID=70414170921368011248849~code=API000 & info=Air2web accepted & Time =2014/11/27/18/12";
        }
    }

    function handleResponse($response_) {
        $responseArr = json_decode($response_, 1);
        if (isset($responseArr['message'])) {
            return $responseArr['message'];
        } else if (isset($responseArr['ErrorMessage'])) {
            return $responseArr['ErrorMessage'];
        } else {
            return $response_;
        }
    }

    function handleResponseMail($response, $message, $number) {
        try {
            $array = explode('&', $response);
            if (isset($array['Info'])) {
                if ($array['Info'] != 'Platform Accepted') {
                    $emailWrapper = new EmailWrapper();
                    SwipezLogger::debug(__CLASS__, 'SMS sending failed mail sending');
                    $body_message = "Merchant id : " . $this->merchant_id . "<br>" . "Mobile : " . $number . "<br>" . "SMS: " . $message . "<br>" . "Response: " . $response;
                    $emailWrapper->sendMail('support@swipez.in', "", 'SMS sending failed', $body_message, $body_message);
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, 'Error while sending mail for SMS failed ');
        }
    }

}
