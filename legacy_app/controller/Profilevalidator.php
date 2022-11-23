<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Registervalidator
 *
 * @author Shuhaid
 */
class Profilevalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validateResetPassword($user_id) {

        $this->post('oldpassword')
                ->val('required', 'Current password')
                ->val('maxlength', 'Current password', 254)
                ->val('isPasswordExist', 'Current password', $user_id)
                ->post('password', 'rpassword')
                ->val('required', 'Password')
                ->val('maxlength', 'Password', 40)
                ->valpasswd('isValidPassword', 'password', 'rpassword')
                ->valpasswd('isOldPassword', 'oldpassword', 'password');
    }

    function ValidateLoginInfo($password) {
        $this->post('username')
                ->val('isValidLogin', 'Login', $password);
    }

    function ValidateLoginEmail() {
        $this->post('username')
                ->val('validEmailMobile', 'User name')
                ->val('maxlength', 'User name', 250)
                ->post('password')
                ->val('maxlength', 'Password', 40);
    }

    function ValidateLoginInfoWithcaptcha($password) {
        $this->post('username')
                ->val('validEmailMobile', 'Login', $password)
                ->post('captcha')
                ->val('captcha', 'Captcha');
    }

    function validateForgotResetPassword() {

        $this->post('password', 'verifypassword')
                ->val('required', 'Password')
                ->val('maxlength', 'Password', 40)
                ->valpasswd('isValidPassword', 'password', 'verifypassword');
    }

    function validateForgotPasswordRequest() {
        $this->post('username')
                ->val('required', 'Email Or Mobile')
                ->val('validEmailMobile', 'Email Or Mobile');
    }

    function validatehelpdeskwithlogin() {
        $this->post('captcha')
                ->val('captcha', 'Captcha')
                ->post('subject')
                ->val('required', 'Subject')
                ->val('maxlength', 'Subject', 40)
                ->post('message')
                ->val('required', 'Message');
    }

    function validatehelpdesknonlogin() {
        $this->post('email')
                ->val('required', 'Email')
                ->post('name')
                ->val('required', 'Name')
                ->val('maxlength', 'Password', 100)
                ->post('subject')
                ->val('required', 'Subject')
                ->val('maxlength', 'Subject', 40)
                ->post('message')
                ->val('required', 'Message')
                ->post('captcha')
                ->val('captcha', 'Captcha');
    }

    function validatesuggestmerchant() {

        $this->post('name')
                ->val('required', 'Merchant Name')
                ->val('maxlength', 'Merchant Name', 255)
                ->post('email')
                ->val('validEmail', 'Merchant email')
                ->val('maxlength', 'Merchant email', 255)
                ->post('contact_no')
                ->val('maxlength', 'Contact no', 20)
                ->post('business_nature')
                ->val('required', 'Business nature')
                ->val('maxlength', 'Business nature', 255)
        ;
    }

    /**
     * val - This is to validate passwords specifically
     * 
     * @param string $typeOfValidator A method from the Form/Val class
     * @param string $arg A property to validate against
     */
    public function valpasswd($typeOfValidator, $password_, $verifypassword_) {
        $error = $this->_val->{$typeOfValidator}($this->_postData[$password_], $this->_postData[$verifypassword_]);

        if ($error) {
            if (isset($this->_error[$this->_currentItem])) {
                $existingError = $this->_error[$this->_currentItem];
                $count = count($existingError);
                $existingError[$count] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            } else {
                $existingError[0] = 'Password';
                $existingError[1] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            }
        }

        return $this;
    }

}
