<?php

namespace App\Lib;

use Exception;
use Log;

class ValidateValue {

    public function __construct($model_) {
        $this->_model = $model_;
    }

    public function required($data) {
        if (empty($data)) {
            return "Field is empty";
        }
    }

    public function grequired($data) {
        if (empty($data)) {
            return "Zero value request are not allowed";
        }
    }

    public function prequired($data) {
        if (empty($data)) {
            return "Atleast one particular should be included";
        }
    }

    public function requiredArray($data) {
        if (empty($data)) {
            return "Please include atleast 1 Particular label field";
        }
    }

    public function compareReqArray($exist, $new) {

        if (empty($exist) && empty($new)) {
            return "Please include atleast 1 Particular label field";
        }
    }

    public function checkname($name) {
        $emptyarray = array();
        if (!preg_match_all('$[a-zA-Z]+(([\'\,\.\-\][a-zA-Z])?[a-zA-Z]*)*$', $name, $emptyarray))
            return "Please enter a valid name";
    }

    public function isSpecialChar($char) {
        if (preg_match('/[\'^£$%&*()}{@#~?><>|=_+¬-]/', $char)) {
            return "Special characters not allowed.";
        }
    }

    public function minlength($data, $arg) {
        if ($data != '') {
            if (strlen($data) < $arg) {
                return "Please enter minimum $arg characters";
            }
        }
    }

    public function validatecustomcolumn($data) {
        if ($data != '') {
            if (strlen($data) < $arg) {
                return "Please enter minimum $arg characters";
            }
        }
    }

    public function maxlength($data, $arg) {
        if (strlen($data) > $arg) {
            return "Please enter text lesser than $arg characters";
        }
    }

    public function compairBillDate($billdate, $duedate) {
        $date1 = strtotime($billdate);
        $date2 = strtotime($duedate);
        if ($date1 > $date2) {
            return "Bill date should not be greater than due date";
        }
    }

    public function compairExpiryDate($expirydate, $duedate) {
        if (strlen($expirydate) > 5) {
            $date1 = new DateTime($expirydate);
            $date2 = new DateTime($duedate);
            if ($date1 < $date2) {
                return "Expiry date should not be less than due date";
            }
        }
    }

    public function compairEndDate($end_date, $start_date) {
        if (strlen($end_date) > 5) {
            $date1 = new DateTime($end_date);
            $date2 = new DateTime($start_date);
            if ($date1 < $date2) {
                return "End date should not be less than start date";
            }
        }
    }

    public function compairWithKey($id, $key) {
        if ($id != $key) {
            return "Primary ID and key does not match";
        }
    }

    public function CompairCurrentDate($duedate) {
        $cur_date = new DateTime(date('Y-m-d'));
        $due_date = new DateTime($duedate);
        if ($cur_date > $due_date) {
            return "Due date should not be less than current date";
        }
    }

    public function validateAPIDate($data) {
        if ($data != '') {
            if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $data)) {
                
            } else {
                return "Please enter valid date format (YYYY-mm-dd)";
            }
        }
    }

    public function validateWEBDate($data) {
        if (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/', $data)) {
            
        } else {

            return "Please enter valid date format (dd-mm-YYYY) eg. 20-02-2017";
        }
    }

    public function validateAPIDateTime($data) {
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $data)) {
            
        } else {
            return "Please enter valid date time format (YYYY-mm-dd H:i:s)";
        }
    }

    public function digit($data) {
        $data = "" . $data . "";
        if (isset($data) && trim($data) != '') {
            if (ctype_digit($data) == false) {
                return "Please enter only digits";
            }
        }
    }

    public function __call($name, $arguments) {
        throw new Exception("$name does not exist inside of: " . __CLASS__);
    }

    public function validEmail($data_) {
        if (filter_var($data_, FILTER_VALIDATE_EMAIL) == false && $data_ != '' && strlen($data_) < 50) {
            return "Please enter a valid email address format";
        }
    }

    public function isValidEmail($data_, $type = NULL) {
        if (filter_var($data_, FILTER_VALIDATE_EMAIL) == false && $data_ != '' && strlen($data_) < 50) {
            return "Please enter a valid email address format";
        } else {
            $emailExists = $this->_model->emailAlreadyExists($data_);
            if ($emailExists) {
                if ($type == 'register') {
                    return "Email ID already exists. If this email address belongs to you, then please use the forgot password option OR try registering with a new email address";
                } else {
                    return "The email ID you are trying to add is already registered on our platform. Please use another email address for adding the sub-merchant";
                }
            }
        }
    }

    public function isValidUrl($data_, $merchant_id) {
        $urlExists = $this->_model->UrlExists($data_, $merchant_id);
        if ($urlExists) {
            return "URL already exists";
        }
    }

    public function isValidTemplatename($data_) {
        $templateExists = $this->_model->isExistTemplate($data_);
        if ($templateExists == TRUE) {
            return "Name already exists";
        }
    }

    public function isExistCorporate($data_) {
        $templateExists = $this->_model->isExistCorporate($data_);
        if ($templateExists == FALSE) {
            return "Corporate id does not exist";
        }
    }

    public function isExistUser($data_) {
        $templateExists = $this->_model->isExistUser($data_);
        if ($templateExists == FALSE) {
            return "User id does not exist";
        }
    }

    public function isExistInsurance($data_) {
        $templateExists = $this->_model->isExistInsurance($data_);
        if ($templateExists == FALSE) {
            return "Insurance company id does not exist";
        }
    }

    public function isExistPolicy($data_) {
        $templateExists = $this->_model->isExistPolicy($data_);
        if ($templateExists == FALSE) {
            return "Policy id does not exist";
        }
    }

    public function isValidCalendarname($data_, $userid) {
        $templateExists = $this->_model->isExistCalendar($data_, $userid);
        if ($templateExists == TRUE) {
            return "Name already exists";
        }
    }

    public function isValidInvoiceNumber($data_, $user_id, $payment_request_id = NULL) {
        if ($data_ != '') {
            $isExists = $this->common->isExistInvoiceNumber($user_id, $data_, $payment_request_id);
            if ($isExists != FALSE) {
                return "Invoice number already exists";
            }
        }
    }

    public function isValidCustomerCode($data_, $merchant_id, $customer_id = NULL) {
        if ($data_ != 'NULL') {
            $isExists = $this->_model->isExistCustomerCode($merchant_id, $data_, $customer_id);
            if ($isExists != FALSE) {
                return "Customer code already exists";
            }
        }
    }

    public function is_existCustomerCode($data_, $merchant_id) {
        if ($data_ != 'NULL') {
            $isExists = $this->_model->isExistCustomerCode($merchant_id, $data_);
            if ($isExists == FALSE) {
                return "Customer code does not exists";
            }
        }
    }

    public function isValidCouponCode($data_, $userid) {
        $templateExists = $this->_model->isExistCoupon($data_, $userid);
        if ($templateExists == TRUE) {
            return "Coupon code already exists";
        }
    }

    public function isValidLogin($username, $password) {
        $session = new Session();
        $disable_email = $session->get('disable_email');
        if ($disable_email == $username) {
            return 'Your id has been disabled. It will be enabled once you have reset your password.If you have any queries related to this please use the <a href="/helpdesk" class="iframe"> contact us </a> option to get in touch.';
        } else {
            $data = $this->_model->queryLoginInfo($username, $password);
            if ($data['isValid'] == 0) {
                if ($data['loginattempt'] > 4) {
                    $session->set('show_captcha', TRUE);
                }
                if ($data['loginattempt'] == 10) {
                    $session->set('forgot_email', TRUE);
                    $session->set('is_disable', TRUE);
                    $session->set('disable_email', $username);
                    return 'Forgot password request has been sent to your registered email address. Please use the link within the forgot password email to reset your password. Till then your id has been disabled for security reasons. It will be enabled once you have reset your password. If you have any queries related to this please use the <a href="/helpdesk" class="iframe"> contact us</a> option to get in touch.';
                } else if ($data['status'] == 18) {
                    return 'Your id has been disabled. It will be enabled once you have reset your password. If you have any queries related to this please use the <a href="/helpdesk" class="iframe"> contact us</a> option to get in touch.';
                } else {
                    if ($data['status'] == 1 || $data['status'] == 11) {
                        $session->set('unverified_userid', $data['user_id']);
                        $session->set('unverified_status', $data['status']);
                        $session->set('unverified_email', $username);
                        return 'Your email has not yet been verified. Please verify your email before logging in. In case if you have not received our email in your Inbox, please check your Spam folder. <a href="/login/verification"> Click here</a> to resend a new verification email.';
                    } else {
                        return "There was an error with your E-Mail/Password combination. Please try again";
                    }
                }
            } else {
                $session->remove('show_captcha');
                $session->remove('is_disable');
                $session->remove('disable_email');
                $session->set('login_data', $data);
            }
        }
    }

    public function isPasswordExist($data_, $userid) {

        $Exists = $this->_model->isExistPassword($data_, $userid);
        if ($Exists == FALSE) {
            return "Current password is incorrect. Please try again";
        }
    }

    public function isOldPassword($exist, $new) {
        if ($exist == $new) {
            return "The new password you have entered is the same as your current password. Please use a different password to reset";
        }
    }

    public function nodigit($data_) {
        if (ctype_alpha($data_) == false && !empty($data_)) {
            return "Numeric values not allowed";
        }
    }

    public function isRequireCountrycode($llCountryCode, $landline) {

        if ($landline != '' && $llCountryCode == '') {
            return "Field is empty";
        }
    }

    public function isValidPassword($password_, $verifyPassword_) {
        /*
          Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
          $ = beginning of string
          \S* = any set of characters
          (?=\S{8,}) = of at least length 8
          (?=\S*[\d]) = and at least one number
          $ = end of the string
         */
        $emptyarray = array();
        if (!preg_match_all('$\S*(?=\S{8,})(?=\S*[\d])\S*$', $password_, $emptyarray))
            return "Password needs to be atleast 8 characters long and should contain atleast 1 numeric value";

        if ($password_ != $verifyPassword_)
            return "Passwords do not match. Please make sure both password are the same";
    }

    public function isMoney($data_) {
        if ($data_ != '') {
            if (!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data_)) {
                return "Entered amount is not valid";
            } else {
                if (round($data_) > 100000000) {
                    return "Amount should not exceed 100000000.00";
                }
            }
        }
    }

    public function isamount($data_) {
        if ($data_ != '') {
            if (!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data_)) {
                return "Entered amount is not valid";
            }
        }
    }

    public function minAmt($data, $arg) {
        if ($data != '') {
            if (!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data)) {
                
            } else {
                if ($data < $arg) {
                    return "Please enter value greater than " . $arg;
                }
            }
        }
    }

    public function maxAmt($data, $arg) {
        if ($data != '') {
            if (!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data)) {
                return "Entered value is not valid";
            } else {
                if ($data > $arg) {
                    return "Please enter value lesser than " . $arg;
                }
            }
        }
    }

    public function compairminmax($min, $max) {
        if ($min != '' && $max != '') {
            if ($min > $max) {
                return "Min transaction should be lesser than max transaction";
            }
        }
    }

    public function isValidParticularTax($data_) {
        foreach ($data_ as $key => $value) {
            $result = $this->maxlength($value, 250);
            if (isset($result)) {
                return $result;
            }
        }
    }

    public function isValidImageExt($data_) {
        if ($data_['tmp_name'] != '') {
            $finfo = finfo_open(FILEINFO_MIME);
            $filename = basename($data_['name']);
            $allowed_ext = array('gif', 'png', 'jpeg', 'jpg', 'GIF', 'PNG', 'JPEG', 'JPG');
            $allowed_mimetype = array('image/jpeg', 'image/gif', 'image/png', 'image/jpg');
            $mimetype = mime_content_type($data_['tmp_name']);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            finfo_close($finfo);
            if (!in_array($ext, $allowed_ext)) {
                return "Supported image formats for logo uploads are .png , .jpeg , .gif";
            } else {
                if (!in_array($mimetype, $allowed_mimetype)) {
                    return "Logos can only uploaded be gif, png, jpeg, jpg Format";
                }
            }
        }
    }

    public function isValidExcelExt($data_) {
        if ($data_['tmp_name'] != '') {
            $finfo = finfo_open(FILEINFO_MIME);
            $filename = basename($data_['name']);
            $allowed_ext = array('xlsx', 'xls');
            $allowed_mimetype = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'
                , 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel',
                'application/xls', 'application/x-xls', 'application/vnd.ms-office');
            $mimetype = mime_content_type($data_['tmp_name']);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            finfo_close($finfo);
            if (!in_array($ext, $allowed_ext)) {
                return "Allowed extensions are .xls & .xlsx";
            } else {
                if (!in_array($mimetype, $allowed_mimetype)) {
                    return "Uploaded file is not recognized as a valid excel file";
                }
            }
        }
    }

    public function isValidPDFExt($data_) {
        if ($data_['tmp_name'] != '') {
            $finfo = finfo_open(FILEINFO_MIME);
            $filename = basename($data_['name']);
            $allowed_ext = array('pdf');
            $allowed_mimetype = array('application/pdf');
            $mimetype = mime_content_type($data_['tmp_name']);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            finfo_close($finfo);
            if (!in_array($ext, $allowed_ext)) {
                Log::error('[E280]Error while upload PDF Error: Invalid extension-' . $ext);
                return "Allowed extensions are .pdf";
            } else {
                if (!in_array($mimetype, $allowed_mimetype)) {
                    Log::error('[E278]Error while upload pdf Error: Invalid mimetype-' . $mimetype);
                    return "Uploaded file is not recognized as a valid pdf file";
                }
            }
        }
    }

    public function isValidExcelsize($data_, $maxsize) {
        if ($data_['size'] > $maxsize || $data_['error'] == 1) {
            return "File size cannot be greater than " . $maxsize / 1000 . "KB";
        }
    }

    public function isValidImagesize($data_, $maxsize) {
        if ($data_['size'] > $maxsize || $data_['error'] == 1) {
            return "Logo size cannot be greater than " . $maxsize / 1000 . "KB";
        }
    }

    public function captcha($data_) {
        @session_start();
        if (empty($_SESSION['6_letters_code']) || strcasecmp($_SESSION['6_letters_code'], $data_) != 0) {
            return "Code entered in captcha does not match the one shown in the image. Please re-enter";
        }
    }

    public function isPercentage($data_) {
        if ($data_ != '') {
            if (!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data_)) {
                return "Entered percentage is not valid";
            } else {
                if ($data_ > 100) {
                    return "Percentage should not exceed 100%";
                }
            }
        }
    }

    function valuesWithdatatype($values_, $datatypes_) {
        if ($datatypes_ == 'integer') {
            $return = $this->digit((string) $values_);
        } else if ($datatypes_ == 'money') {
            $return = $this->isamount($values_);
        } else if ($datatypes_ == 'percentage') {
            $return = $this->isPercentage($values_);
        }
        if ($return != '') {
            return $return;
        }
    }

    public function noValidation($data) {
        
    }

}
