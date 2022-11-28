<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Ticket extends Api {

    Private $errorlist = NULL;

    function __construct() {
        parent::__construct('Merchant');
        $this->version = 'v1';
    }

    

    function deleteagent() {
        $array1 = $this->getDeleteUserJson();
        $this->compairJsonArray($array1, $this->jsonArray);
        $req_time = date("Y-m-d H:i:s");
        require_once MODEL . 'merchant/SubuserModel.php';
        $SubuserModel = new SubuserModel();
        $result = $SubuserModel->existUserId($this->jsonArray['user_id'], $this->user_id);
        if ($result == true) {
            $this->common->updateUserStatus(21, $this->jsonArray['user_id']);
            $result = array('message' => 'User has been deleted');
        } else {
            $this->errorlist = array('user_id' => 'user_id does not exist');
            $error_code = 'ER02030';
        }
        $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist);
    }

    function saveagent() {
        try {
            $req_time = date("Y-m-d H:i:s");

            $array1 = $this->getagentSaveJson();
            $this->compairJsonArray($array1, $this->jsonArray);

            require_once MODEL . 'merchant/SubuserModel.php';
            $SubuserModel = new SubuserModel();

            require_once CONTROLLER . 'merchant/Subuservalidator.php';
            $validator = new Subuservalidator($SubuserModel);
            $_POST['email'] = $this->jsonArray['email'];
            $_POST['mobile'] = $this->jsonArray['mobile'];
            $_POST['first_name'] = $this->jsonArray['first_name'];
            $_POST['last_name'] = $this->jsonArray['last_name'];

            $validator->validateApiSubmerchantSave($this->user_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                
            } else {
                foreach ($hasErrors as $err) {
                    $this->errorlist[$err[0]] = $err[1];
                }
            }

            if ($this->errorlist == NULL) {
                $result = $SubuserModel->savesubMerchant($this->user_id, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], '', '', 0);
                if ($result['message'] == 'success') {
                    $SubuserModel->updateTicketStatus(1, $result['user_id']);
                    $this->sendMail($result['usertimestamp'], $_POST['email']);
                    $success = array('user_id' => $result['user_id'], 'email' => $_POST['email']);
                } else {
                    $error_code = 'ER02030';
                }
            } else {
                $error_code = 'ER02030';
            }
            $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving invoice Error: for user [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    public function sendMail($concatStr_, $toEmail_) {
        try {
            $encoded = $this->encrypt->encode($concatStr_);
            $env = getenv('ENV');
            $host = ($env == 'LOCAL') ? 'http' : 'https';
            $baseurl = $host . '://' . $_SERVER['SERVER_NAME'];
            $verifyemailurl = $baseurl . '/merchant/register/verifyemail/' . $encoded . '';

            $emailWrapper = new EmailWrapper();
            $mailcontents = $emailWrapper->fetchMailBody("user.verifyemail");

            if (isset($mailcontents[0]) && isset($mailcontents[1])) {
                $message = $mailcontents[0];
                $message = str_replace('__EMAILID__', $toEmail_, $message);
                $message = str_replace('__LINK__', $verifyemailurl, $message);
                $message = str_replace('__BASEURL__', $baseurl, $message);

                $emailWrapper->sendMail($toEmail_, "", $mailcontents[1], $message);
            } else {
                SwipezLogger::warn("Mail could not be sent with verify email link to : " . $toEmail_);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E166]Error while sending mail Error: email link to : ' . $toEmail_ . $e->getMessage());
        }
    }

    function createErrorlist($code, $key_name, $key_path) {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }

}
