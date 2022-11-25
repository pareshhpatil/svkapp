<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Sms extends Api {

    Private $errorlist = NULL;

    function __construct() {
        parent::__construct('Merchant');
        $this->version = 'v1';
    }

    function send() {
        try {
            $req_time = date("Y-m-d H:i:s");
            $array1 = $this->getSMSJson();
            $this->compairJsonArray($array1, $this->jsonArray);
            $srNo = 0;
            require_once UTIL . 'batch/Batch.php';
            $batch = new Batch();
            $sms_gateway = $this->common->getRowValue('sms_gateway', 'merchant_setting', 'merchant_id', $this->merchant_id);
            $sg_details = null;
            if ($sms_gateway > 1) {
                $sg_details = $this->common->getSingleValue('sms_gateway', 'sg_id', $sms_gateway);
            }
            $error_code = 0;
            $model = new Model();
            $is_valid = $model->isValidatePackage($this->merchant_id, 1);
            if ($is_valid == FALSE) {
                SwipezLogger::info(__CLASS__, 'Sms pack is expired Merchant_id: ' . $this->merchant_id);
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02037');
                return;
            }

            foreach ($this->jsonArray['data'] as $row) {
                if (strlen($row['mobile']) == 10) {
                    if (strlen($row['sms']) > 160) {
                        $error_code = 'ER02008';
                    } else {
                        $batch->sendSMS(null, $row['sms'], $row['mobile'], $this->merchant_id, 2, $sg_details);
                        $success[] = array('status' => 'Sent', 'mobile' => $row['mobile']);
                    }
                } else {
                    $error_code = 'ER02036';
                }
            }
            return $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving invoice Error: for user [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function createErrorlist($code, $key_name, $key_path) {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }

}
