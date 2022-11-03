<?php

/*
 * Stores list of SMS messages. This is hard coded in this class as a hash array
 * at the moment. This should eventually replaced by a caching solution.
 */

/**
 * Description of SMSMessage
 *
 * @author Shuhaid
 */
class ApiErrors {

    private $_errors = array();

    function __construct() {
        $this->_errors["ER01001"] = "Invalid json request format.";
        $this->_errors["ER01002"] = "Invalid request URL.";
        $this->_errors["ER01003"] = "Invalid API key.";
        $this->_errors["ER01004"] = "Access denied to API services.";
        $this->_errors["ER01005"] = "Invalid template id.";
        $this->_errors["ER01006"] = "Invalid request method.";
        $this->_errors["ER01007"] = "Invoice upload failed.";

        $this->_errors["ER01008"] = "Invalid invoice id.";
        $this->_errors["ER01009"] = "Invoice already paid online.";
        $this->_errors["ER01010"] = "Invalid payment mode.";
        $this->_errors["ER01011"] = "Json format not match.";

        $this->_errors["ER02001"] = "Id field not recognized. Do not change id field.";
        $this->_errors["ER02002"] = "Date should be formatted in YYYY-MM-DD format (ex. 2015-12-24).";
        $this->_errors["ER02003"] = "Mobile number should be only 10 digits.";
        $this->_errors["ER02004"] = "Incorrectly formatted email id.";
        $this->_errors["ER02005"] = "Name can not be empty.";
        $this->_errors["ER02006"] = "Amount should contain only numeric values.";
        $this->_errors["ER02007"] = "Input should be digits.";
        $this->_errors["ER02008"] = "Text limit exceeds.";
        $this->_errors["ER02009"] = "From date should be greater than To date";
        $this->_errors["ER02010"] = "Difference between from-date and to-date not more than 31 days";
        $this->_errors["ER02011"] = "Customer code does not exist.";

        $this->_errors["ER02012"] = "Due date should not be less than current date.";
        $this->_errors["ER02013"] = "Expiry date should not be less than due date.";
        $this->_errors["ER02014"] = "Field can not be empty.";
        $this->_errors["ER02015"] = "Invalid content please enter valid value.";


        $this->_errors["ER02016"] = "Invalid coupon ID.";
        $this->_errors["ER02017"] = "Invalid coupon type.";
        $this->_errors["ER02018"] = "Invalid coupon code or already exist.";
        $this->_errors["ER02019"] = "Invalid coupon value.";

        $this->_errors["ER02020"] = "Invalid transaction type.";
        $this->_errors["ER02021"] = "transaction does not exist";

        $this->_errors["ER02030"] = "Api upload failed.";
        $this->_errors["ER02031"] = "Customer code not valid or already exist";


        $this->_errors["ER02032"] = "Package invoice limit has been exceeded. Please upgrade your package.";
        $this->_errors["ER02033"] = "Invalid franchise_id or does not exist.";
        $this->_errors["ER02035"] = "Invalid vendor_id or does not exist.";
        $this->_errors["ER02034"] = "Invalid customer ID.";
        $this->_errors["ER02035"] = "Invoice number not valid or already exist";
        $this->_errors["ER02036"] = "Invalid mobile number";
        $this->_errors["ER02037"] = "Sms pack is expired";
        $this->_errors["ER02038"] = "Refund failed. Please contact to swipez Support";
        $this->_errors["ER02039"] = "Login has been disabled. It will be enabled once user have reset password.";
        $this->_errors["ER02040"] = "User has not yet been verified. Please verify email before logging in.";
        $this->_errors["ER02041"] = "Invalid Username/Password combination. Please try again.";
        $this->_errors["ER02042"] = "Invalid partial amount";
        $this->_errors["ER02043"] = "Insufficient balance to refund this transaction. Please contact to support@swipez.in";
    }

    function fetch($key_) {
        $value = isset($this->_errors[$key_]) ? $this->_errors[$key_] : NULL;

        return $value;
    }

}
