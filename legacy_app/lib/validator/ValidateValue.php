<?php

class ValidateValue
{

    private $common;

    public function __construct($model_)
    {
        $this->_model = $model_;
        $this->common = new CommonModel();
    }

    public function required($data)
    {
        if (empty($data)) {
            return "Field is empty.";
        }
    }

    public function validateState($data, $states = null, $country_name=null)
    {
        if($country_name=='India' || $country_name == null) {
            if ($data != '') {
                if ($states != null) {
                    $stateArr = [];
                    foreach ($states as $state) {
                        $stateArr[] = str_replace(' ', '', $state);
                    }
                    if (!in_array(str_replace(' ', '', strtolower($data)), $stateArr)) {
                        //return "Invalid state name. Please enter valid state name";
                        return 'State name is incorrect. For a list of valid state names,  <a href="https://helpdesk.swipez.in/help/add-state-name-to-customer-database" target="_blank">'.'click here'.'</a>';
                    }
                }
            }
        }
    }

    public function validateCountry($data, $countries = null)
    {
        if ($data != '') {
            if ($countries != null) {
                $countriesArr = [];
                foreach ($countries as $country) {
                    $countriesArr[] = str_replace(' ', '', $country);
                }
                if (!in_array(str_replace(' ', '', strtolower($data)), $countriesArr)) {
                    //return "Invalid country name. Please enter valid country name";
                    return 'Country name is incorrect. For a list of valid country names, read <a href="https://helpdesk.swipez.in/help/add-country-name-to-customer-database" target="_blank">'. 'click here' .'</a>';
                }
            }
        }
    }

    public function grequired($data)
    {
        if (empty($data)) {
            return "Zero value request are not allowed.";
        }
    }

    public function prequired($data)
    {
        if (empty($data)) {
            return "Atleast one particular should be included";
        }
    }

    public function requiredArray($data)
    {
        if (empty($data)) {
            return "Please include atleast 1 Particular label field";
        }
    }

    public function compareReqArray($exist, $new)
    {

        if (empty($exist) && empty($new)) {
            return "Please include atleast 1 Particular label field";
        }
    }

    public function checkname($name)
    {
        $emptyarray = array();
        if (!preg_match_all('$[a-zA-Z]+(([\'\,\.\-\][a-zA-Z])?[a-zA-Z]*)*$', $name, $emptyarray))
            return "Please enter a valid name";
    }

    public function GSTNumber($data)
    {
        if (isset($data) && $data != '') {
            $emptyarray = array();
            if (!preg_match_all('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', $data, $emptyarray)) {
                return "Please enter a valid GST";
            }
        }
    }

    public function validifscCode($data)
    {
        if (isset($data) && $data != '') {
            if (!preg_match('/^[A-Za-z]{4}0[A-Z0-9]{6}$/', $data)) {
                return "Please enter a valid IFSC code";
            }
        }
    }

    public function minlength($data, $arg)
    {
        if ($data != '') {
            if (strlen($data) < $arg) {
                return "Please enter minimum $arg characters.";
            }
        }
    }

    public function maxlength($data, $arg)
    {
        if (strlen($data) > $arg) {
            return "Please enter text lesser than $arg characters.";
        }
    }

    public function compairBillDate($billdate, $duedate)
    {
        if ($billdate != 'NA' && $duedate != 'NA') {
            if (strlen($billdate) < 15 && strlen($duedate) < 15) {
                $date1 = new DateTime($billdate);
                $date2 = new DateTime($duedate);
                if ($date1 > $date2) {
                    return "Bill date should not be greater than due date";
                }
            }
        }
    }

    public function compairExpiryDate($expirydate, $duedate)
    {
        if ($expirydate != 'NA' && $duedate != 'NA') {
            if (strlen($expirydate) < 15 && strlen($duedate) < 15) {
                if (strlen($expirydate) > 5) {
                    $date1 = new DateTime($expirydate);
                    $date2 = new DateTime($duedate);
                    if ($date1 < $date2) {
                        return "Expiry date should not be less than due date";
                    }
                }
            }
        }
    }

    public function compairEndDate($end_date, $start_date)
    {
        if ($end_date != 'NA' && $start_date != 'NA') {
            if (strlen($end_date) > 5) {
                $date1 = new DateTime($end_date);
                $date2 = new DateTime($start_date);
                if ($date1 < $date2) {
                    return "End date should not be less than start date";
                }
            }
        }
    }

    public function CompairCurrentDate($duedate)
    {
        if ($duedate != 'NA') {
            if (strlen($duedate) < 15) {
                $cur_date = new DateTime(date('Y-m-d'));
                $due_date = new DateTime($duedate);
                if ($cur_date > $due_date) {
                    return "Date should not be less than current date";
                }
            }
        }
    }

    public function validateDate($data)
    {
        if ($data != '') {
            if ($data == 'NA') {
                return "Please enter valid date";
            }
            if (strlen($data) < 15) {
                $from_date = new DateTime('1990-02-15');
                $to_date = new DateTime('2051-02-15');
               
                try{
                    $date = new DateTime($data);
                    if ($date > $from_date && $date < $to_date) {
                    } else {
                        return "Please enter valid date";
                    }
                } catch(Exception $e) {
                    return "Please enter valid date";
                }
            } else {
                return "Please enter valid date";
            }
        }
    }

    public function validateAPIDate($data)
    {
        if ($data != '') {
            if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $data)) {
            } else {
                return "Please enter valid date format (YYYY-mm-dd)";
            }
        }
    }

    public function digit($data)
    {
        if (isset($data) && trim($data) != '') {
            if (ctype_digit($data) == false) {
                return "Please enter only digits.";
            }
        }
    }

    public function __call($name, $arguments)
    {
        throw new Exception("$name does not exist inside of: " . __CLASS__);
    }

    public function validEmail($data_)
    {
        if (filter_var($data_, FILTER_VALIDATE_EMAIL) == false && $data_ != '') {
            return "Please enter a valid email address format";
        }
    }

    public function validEmailMobile($data_)
    {
        if (filter_var($data_, FILTER_VALIDATE_EMAIL) == false && $data_ != '') {
            if (!preg_match("/^(\+[\d]{1,5}|0)?[1-9]\d{9}$/", $data_)) {
                return "Please enter a valid email ID Or Mobile number";
            }
        }
    }

    public function isValidEmail($data_, $type = NULL, $user_id = null)
    {
        if (filter_var($data_, FILTER_VALIDATE_EMAIL) == false && $data_ != '') {
            return "Please enter a valid email address format";
        } else {
            $emailExists = $this->_model->emailAlreadyExists($data_, $user_id);
            if ($emailExists) {
                if ($type == 'register') {
                    return 'Email ID already exists. If this email address belongs to you, then please <a href="/login">Login</a> your account or use the forgot password option OR try registering with a new email address';
                } else {
                    return "The email ID you are trying to add is already registered on our platform. Please use another email address for adding the sub-merchant";
                }
            }
        }
    }

    public function isValidUrl($data_, $merchant_id)
    {
        $urlExists = $this->_model->UrlExists($data_, $merchant_id);
        if ($urlExists) {
            return "This URL is already taken up, please try another URL.";
        }
    }

    public function isValidTemplatename($data_, $userid)
    {
        $templateExists = $this->_model->isExistTemplate($data_, $userid);
        if ($templateExists == TRUE) {
            return "Name already exists.";
        }
    }

    public function isValidUpdateUniquename($data_, $userid, $id = null)
    {
        $templateExists = $this->_model->isExistTemplate($data_, $userid, $id);
        if ($templateExists == TRUE) {
            return "Name already exists.";
        }
    }

    public function isValidSMSCOUNT($data_, $userid)
    {
        $res = $this->_model->isValidSMSCOUNT($data_, $userid);
        if ($res == FALSE) {
            return "SMS package exceeded please purchase more SMS";
        }
    }

    public function isValidCalendarname($data_, $userid)
    {
        $templateExists = $this->_model->isExistCalendar($data_, $userid);
        if ($templateExists == TRUE) {
            return "Name already exists.";
        }
    }

    public function isValidInvoiceNumber($data_, $user_id, $payment_request_id = NULL)
    {
        if ($data_ != '') {
            if ($payment_request_id == NULL) {
                $isExists = $this->common->isExistInvoiceNumber($user_id, $data_, $payment_request_id);
                if ($isExists != FALSE) {
                    return "Invoice number already exists";
                }
            }
        }
    }

    function validMobile($data_)
    {
        if (!preg_match("/^(\+[\d]{1,5}|0)?[1-9]\d{9}$/", $data_)) {
            return "Please enter a valid Mobile number";
        }
    }

    function multiMobile($data)
    {
        $failed = 0;
        $list = explode(',', $data);
        foreach ($list as $mobile) {
            if (isset($mobile) && trim($mobile) != '') {
                if (ctype_digit($mobile) == false) {
                    $failed = 1;
                } else {
                    if (strlen($mobile) != 10) {
                        $failed = 1;
                    }
                }
            }
        }
        if ($failed == 1) {
            return "Invalid mobile number. Please enter 10 digits mobile number";
        }
    }

    function multiEmail($data)
    {
        $failed = 0;
        $list = explode(',', $data);
        foreach ($list as $email) {
            if (isset($email) && trim($email) != '') {
                if ($this->validEmail($email) == false) {
                } else {
                    $failed = 1;
                }
            }
        }
        if ($failed == 1) {
            return "Please enter a valid email address format";
        }
    }

    public function existin($data_, $array)
    {
        if ($data_ != '') {
            if (!in_array($data_, $array)) {
                return "Please enter valid value eg. " . implode(',', $array);
            }
        }
    }

    public function isMaxAmount($data_, $user_id)
    {
        if ($data_ != '') {
            $isExists = $this->common->isMaxAmount($user_id, $data_);
            if ($isExists != FALSE) {
                return "Due to free package invoice amount cannot be greater than " . $isExists;
            }
        }
    }

    public function isMinAmount($data_, $min)
    {
        if ($data_ != '') {
            if ($data_ < $min) {
                return "Amount should not be less than " . $min;
            }
        }
    }

    public function isValidCustomerCode($data_, $merchant_id, $customer_id = NULL)
    {
        if (isset($data_)) {
            if ($data_ != 'NULL' && $data_ != '') {
                $isExists = $this->_model->isExistCustomerCode($merchant_id, $data_, $customer_id);
                if ($isExists != FALSE) {
                    return "Code already exists";
                }
            }
        }
    }

    public function isValidBeneficiaryCode($data_, $merchant_id, $customer_id = NULL)
    {
        if (isset($data_)) {
            if ($data_ != 'NULL' && $data_ != '') {
                $isExists = $this->_model->isValidBeneficiaryCode($merchant_id, $data_, $customer_id);
                if ($isExists != FALSE) {
                    return "Beneficiary code already exists";
                }
            }
        }
    }

    public function is_existCustomerCode($data_, $merchant_id)
    {
        if ($data_ != 'NULL') {
            $isExists = $this->_model->isExistCustomerCode($merchant_id, $data_);
            if ($isExists == FALSE) {
                return "Customer code does not exists.";
            }
        }
    }

    public function validateGSTState($data_)
    {
        if ($data_ != 'NULL') {
            $isExists = $this->_model->validateGSTstate($data_);
            if ($isExists == 0) {
                return "Invalid state name " . $data_;
            }
        }
    }

    public function is_existFranchiseID($data_, $merchant_id)
    {
        if ($data_ != '0') {
            if ($data_ == '') {
                return "Field is empty.";
            } else {
                if ($data_ > 0) {
                    $isExists = $this->common->getSingleValue('franchise', 'franchise_id', $data_, 1, " and merchant_id='" . $merchant_id . "' and status=1");
                    if (empty($isExists)) {
                        return "Franchise id does not exists.";
                    }
                }
            }
        }
    }

    public function is_existVendorID($data_, $merchant_id)
    {
        if ($data_ != '0') {
            if ($data_ == '') {
                return "Field is empty.";
            } else {
                if ($data_ > 0) {
                    $isExists = $this->common->getSingleValue('vendor', 'vendor_id', $data_, 1, " and merchant_id='" . $merchant_id . "' and status=1");
                    if (empty($isExists)) {
                        return "Vendor id does not exists.";
                    }
                }
            }
        }
    }

    public function isValidVendorID($data_, $merchant_id)
    {
        if ($data_ != '0') {
            if ($data_ == '') {
                return "Field is empty.";
            } else {
                if ($data_ > 0) {
                    $isExists = $this->common->getSingleValue('vendor', 'vendor_id', $data_, 1, " and merchant_id='" . $merchant_id . "' and status=1");
                    if (empty($isExists)) {
                        return "Vendor id does not exists.";
                    }
                } else {
                    return "Vendor id does not exists.";
                }
            }
        }
    }

    public function isValidBeneficiaryID($data_, $merchant_id)
    {
        if ($data_ != '0') {
            if ($data_ == '') {
                return "Field is empty.";
            } else {
                $isExists = $this->common->getSingleValue('beneficiary', 'beneficiary_id', $data_, 1, " and merchant_id='" . $merchant_id . "' and status=1");
                if (empty($isExists)) {
                    return "Beneficiary id does not exists.";
                }
            }
        }
    }

    public function isValidTrasferVendor($data_, $merchant_id)
    {
        if ($data_ != '0') {
            if ($data_ == '') {
                return "Field is empty.";
            } else {
                if ($data_ > 0) {
                    if ($_POST['type'] == 1) {
                        $isExists = $this->common->getSingleValue('vendor', 'vendor_id', $data_, 1, " and merchant_id='" . $merchant_id . "' and online_pg_settlement=1 and status=1");
                        if (empty($isExists)) {
                            return "Vendor does not have PG settlement.";
                        }
                    }
                }
            }
        }
    }

    public function is_existWebhookID($data_, $merchant_id)
    {
        if ($data_ > 0) {
            $isExists = $this->common->getSingleValue('merchant_webhook', 'webhook_id', $data_, 1, " and merchant_id='" . $merchant_id . "'");
            if (empty($isExists)) {
                return "Webhook id does not exists.";
            }
        }
    }

    public function isValidCouponCode($data_, $merchant_id)
    {
        $templateExists = $this->_model->isExistCoupon($data_, $merchant_id);
        if ($templateExists == TRUE) {
            return "Coupon code already exists.";
        }
    }

    public function isValidBeneficiery($data_, $merchant_id)
    {
        $templateExists = $this->_model->isExistBeneficiaryAccount($merchant_id, $data_, $_POST['ifsc_code']);
        if ($templateExists == TRUE) {
            return "Beneficiary account details already exists.";
        }
    }

    public function isValidLogin($username, $password)
    {
        $session = new SessionLegacy();
        $disable_email = $session->get('disable_email');
        if ($disable_email == $username) {
            return 'Your id has been disabled. It will be enabled once you have reset your password.If you have any queries related to this please use the <a href="/helpdesk" class="iframe"> contact us </a> option to get in touch.';
        } else {
            $data = $this->_model->queryLoginInfo($username, $password);
            if ($data['isValid'] == 0) {
                SwipezLogger::info("Login", $username . " login failed login attempt " . $data['loginattempt']);
                if ($data['loginattempt'] > 1) {
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
                    if ($data['status'] == 1 || $data['status'] == 11 || $data['status'] == 19) {
                        $session->set('unverified_userid', $data['user_id']);
                        $session->set('unverified_status', $data['status']);
                        $session->set('unverified_email', $username);
                        return 'Your email has not yet been verified. Please verify your email before logging in. In case if you have not received our email in your Inbox, please check your Spam folder. <a href="/login/verification"> Click here</a> to resend a new verification email.';
                    } else {
                        return "There was an error with your E-Mail/Password combination. Please try again.";
                    }
                }
            } else {
                SwipezLogger::info("Login", "User " . $username . " loggedin");
                $session->remove('show_captcha');
                $session->remove('is_disable');
                $session->remove('disable_email');
                $session->set('login_data', $data);
            }
        }
    }

    public function isPasswordExist($data_, $userid)
    {
        $Exists = $this->_model->isExistPassword($data_, $userid);
        if ($Exists == FALSE) {
            return "Current password is incorrect. Please try again.";
        }
    }

    public function isOldPassword($exist, $new)
    {
        if ($exist == $new) {
            return "The new password you have entered is the same as your current password. Please use a different password to reset.";
        }
    }

    public function nodigit($data_)
    {
        if (ctype_alpha($data_) == false && !empty($data_)) {
            return "Numeric values not allowed";
        }
    }

    public function isRequireCountrycode($llCountryCode, $landline)
    {

        if ($landline != '' && $llCountryCode == '') {
            return "Field is empty.";
        }
    }

    public function isValidPassword($password_, $verifyPassword_)
    {
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
            return "Password needs to be atleast 8 characters long and should contain atleast 1 numeric value.";

        if ($password_ != $verifyPassword_)
            return "Passwords do not match. Please make sure both password are the same.";
    }

    public function isMoney($data_)
    {
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

    public function isamount($data_)
    {
        if ($data_ != '') {
            if (!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data_)) {
                return "Entered amount is not valid";
            }
        }
    }

    public function minAmt($data, $arg)
    {
        if ($data != '') {
            if (!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data)) {
            } else {
                if ($data < $arg) {
                    return "Please enter value greater than " . $arg;
                }
            }
        }
    }

    public function maxAmt($data, $arg)
    {
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

    public function compairminmax($min, $max)
    {
        if ($min != '' && $max != '') {
            if ($min > $max) {
                return "Min transaction should be lesser than max transaction";
            }
        }
    }

    public function isValidParticularTax($data_)
    {
        foreach ($data_ as $key => $value) {
            $result = $this->maxlength($value, 250);
            if (isset($result)) {
                return $result;
            }
        }
    }

    public function isValidParticular($data_)
    {
        foreach ($data_ as $key => $value) {
            switch ($key) {
                case 'item':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->maxlength($val, 250);
                    }
                    break;
                case 'annual_recurring_charges':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->maxlength($val, 45);
                    }
                    break;
                case 'unit_type':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->maxlength($val, 45);
                    }
                    break;
                case 'sac_code':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->maxlength($val, 45);
                    }
                    break;
                case 'description':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->maxlength($val, 500);
                    }
                    break;
                case 'qty':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->isamount($val);
                    }
                    break;
                case 'rate':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->isamount($val);
                    }
                    break;
                case 'gst':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->inArray($val, array(0, 5, 12, 18, 28));
                    }
                    break;
                case 'tax_amount':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->isamount($val);
                    }
                    break;
                case 'discount':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->isamount($val);
                    }
                    break;
                case 'total_amount':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->isamount($val);
                    }
                    break;
                case 'narrative':
                    foreach ($_POST[$key] as $val) {
                        $result = $this->maxlength($val, 250);
                    }
                    break;
            }
            if (isset($result)) {
                return $value . ' ' . $result;
            }
        }
    }

    public function isValidDocs($data_)
    {
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $result = $this->isValidImagePDFExt($_FILES[$key]);
                if ($result != false) {
                    return $result;
                }
            }
        }
    }

    public function isValidImageExt($data_)
    {
        if ($data_['tmp_name'] != '') {
            $finfo = finfo_open(FILEINFO_MIME);
            $filename = basename($data_['name']);
            $allowed_ext = array('gif', 'png', 'jpeg', 'jpg', 'GIF', 'PNG', 'JPEG', 'JPG');
            $allowed_mimetype = array('image/jpeg', 'image/gif', 'image/png', 'image/jpg');
            $mimetype = mime_content_type($data_['tmp_name']);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            finfo_close($finfo);
            if (!in_array($ext, $allowed_ext)) {
                SwipezLogger::error(__CLASS__, '[E281]Error while upload image Error: Invalid extension-' . $ext);
                return "Supported image formats for logo uploads are .png , .jpeg , .gif";
            } else {
                if (!in_array($mimetype, $allowed_mimetype)) {
                    SwipezLogger::error(__CLASS__, '[E279]Error while upload image Error: Invalid mimetype-' . $mimetype);
                    return "Logos can only uploaded be gif, png, jpeg, jpg Format";
                }
            }
        }
    }

    public function isValidImagePDFExt($data_)
    {
        if ($data_['tmp_name'] != '') {
            $finfo = finfo_open(FILEINFO_MIME);
            $filename = basename($data_['name']);
            $allowed_ext = array('gif', 'png', 'jpeg', 'jpg', 'doc', 'dot', 'docx', 'dotx', 'docm', 'dotm', 'pdf', 'GIF', 'PNG', 'JPEG', 'JPG', 'PDF');
            $allowed_mimetype = array('image/jpeg', 'image/gif', 'image/png', 'image/jpg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.wordprocessingml.template', 'application/vnd.ms-word.document.macroEnabled.12', 'application/vnd.ms-word.template.macroEnabled.12');
            $mimetype = mime_content_type($data_['tmp_name']);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            finfo_close($finfo);
            if (!in_array(strtolower($ext), $allowed_ext)) {
                SwipezLogger::error(__CLASS__, '[E281]Error while upload image Error: Invalid extension-' . $ext);
                return "Supported file formats for uploads are Image, Word and PDF";
            } else {
                if (!in_array($mimetype, $allowed_mimetype)) {
                    SwipezLogger::error(__CLASS__, '[E279]Error while upload image Error: Invalid mimetype-' . $mimetype);
                    return "File can only uploaded be Image, Word and PDF Format";
                }
            }
        }
    }

    public function isValidExcelExt($data_)
    {
        if ($data_['tmp_name'] != '') {
            $finfo = finfo_open(FILEINFO_MIME);
            $filename = basename($data_['name']);
            $allowed_ext = array('xlsx', 'xls');
            $allowed_mimetype = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel',
                'application/xls', 'application/x-xls', 'application/vnd.ms-office', 'application/octet-stream'
            );
            $mimetype = mime_content_type($data_['tmp_name']);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            finfo_close($finfo);
            if (!in_array($ext, $allowed_ext)) {
                SwipezLogger::error(__CLASS__, '[E280]Error while upload excel Error: Invalid extension-' . $ext);
                return "Allowed extensions are .xls & .xlsx";
            } else {
                if (!in_array($mimetype, $allowed_mimetype)) {
                    SwipezLogger::error(__CLASS__, '[E278]Error while upload excel Error: Invalid mimetype-' . $mimetype);
                    return "Uploaded file is not recognized as a valid excel file.";
                }
            }
        }
    }

    public function isValidCSVExt($data_)
    {
        if ($data_['tmp_name'] != '') {
            $finfo = finfo_open(FILEINFO_MIME);
            $filename = basename($data_['name']);
            $allowed_ext = array('csv');
            $allowed_mimetype = array('application/vnd.ms-excel', 'mimetype-text/plain', 'text/plain', 'application/csv', 'application/x-csv', 'text/csv', 'text/comma-separated-values', 'text/x-comma-separated-values', 'text/tab-separated-values');
            $mimetype = mime_content_type($data_['tmp_name']);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            finfo_close($finfo);
            if (!in_array($ext, $allowed_ext)) {
                SwipezLogger::error(__CLASS__, '[E280]Error while upload excel Error: Invalid extension-' . $ext);
                return "Allowed extensions are .csv";
            } else {
                if (!in_array($mimetype, $allowed_mimetype)) {
                    SwipezLogger::error(__CLASS__, '[E278]Error while upload excel Error: Invalid mimetype-' . $mimetype);
                    return "Uploaded file is not recognized as a valid excel file.";
                }
            }
        }
    }

    public function isValidExcelsize($data_, $maxsize)
    {
        if ($data_['size'] > $maxsize || $data_['error'] == 1) {
            return "Excel size cannot be greater than " . $maxsize / 1000 . "KB";
        }
    }

    public function isValidImagesize($data_, $maxsize)
    {
        if ($data_['size'] > $maxsize || $data_['error'] == 1) {
            return "Logo size cannot be greater than " . $maxsize / 1000 . "KB";
        }
    }

    public function captcha($data_)
    {
        @session_start();
        if (empty($_SESSION['6_letters_code']) || strcasecmp($_SESSION['6_letters_code'], $data_) != 0) {
            return "Code entered in captcha does not match the one shown in the image. Please re-enter.";
        }
    }

    public function isPercentage($data_)
    {
        if ($data_ != '') {
            if (!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data_)) {
                return "Entered percentage is not valid";
            } else {
                if ($data_ > 100) {
                    return "percentage should not exceed 100%";
                }
            }
        }
    }

    public function isSpecialChar($char)
    {
        if (preg_match('/[\'^£$%&*()}{@#~?><>|=_+¬-]/', $char)) {
            return "Special characters not allowed.";
        }
    }

    public function validateWEBDate($data)
    {
        if ($data != '') {
            if (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1]) [a-zA-Z]{3} [0-9]{4}$/', $data)) {
            } else {

                return "Please enter valid date format (dd M YYYY) eg. 20 Feb 2017";
            }
        }
    }

    public function validateAPIDateTime($data)
    {
        if ($data != '') {
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $data)) {
            } else {
                return "Please enter valid date time format (YYYY-mm-dd H:i:s)";
            }
        }
    }

    function valuesWithdatatype($values_, $datatypes_)
    {
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

    public function isValidRoleCount($data_, $userid)
    {
        $templateExists = $this->_model->isRoleCount($userid);
        if ($templateExists == TRUE) {
            return 'Role limit has been exceeded. Please upgrade your <a href="/home/pricing">package</a> or contact to support@swipez.in';
        }
    }

    public function inArray($data, $arr = array())
    {
        if ($data != '') {
            if (!in_array($data, $arr)) {
                return "Please enter valid value eg. " . implode(',', $arr);
            }
        }
    }
    public function ArrayValidate($data_, $arr = array())
    {
        if (!empty($data_)) {
            foreach ($data_ as $data) {
                if ($data != '') {
                    if (!in_array($data, $arr)) {
                        return "Please enter valid value eg. " . implode(',', $arr);
                    }
                }
            }
        }
    }
    public function ArrayValid($data_, $validation)
    {
        if (!empty($data_)) {
            foreach ($data_ as $data) {
                if ($data != '') {
                    if ($validation == 'isamount') {
                        $return = $this->isamount($data);
                        if ($return) {
                            return $return;
                        }
                    }
                }
            }
        }
    }

    public function inArrayExist($data, $arr)
    {
        if ($data != '') {
            if (!in_array($data, $arr)) {
                return "Please enter valid value";
            }
        }
    }

    public function numberRange($data, $start, $end) {
        if($data!='') {
            $rows = (isset($data) ? $data : 0);
            
            if (!(is_numeric($rows) && $rows >= $start && $rows <= $end)) {
                return "Please enter valid value between $start and $end.";
            }
        }
    }
    
}
