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
class Registervalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validatePatronRegister() {

        $this->post('email')
                ->val('required', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->val('isValidEmail', 'Email ID','register')
                ->post('f_name')
                ->val('required', 'First name')
                ->val('checkname', 'First name')
                ->val('maxlength', 'First name', 50)
                ->post('l_name')
                ->val('required', 'Last name')
                ->val('checkname', 'Last name')
                ->val('maxlength', 'Last name', 50)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 14)
                ->val('validMobile', 'Mobile no')
                ->post('password', 'rpassword')
                ->val('required', 'Password')
                ->val('maxlength', 'Password', 40)
                ->valpasswd('isValidPassword', 'password', 'rpassword');
    }

    function validateUpdate() {

        $this->post('email')
                ->val('required', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->val('validEmail', 'Email ID')
                ->post('f_name')
                ->val('required', 'First name')
                ->val('checkname', 'First name')
                ->val('maxlength', 'First name', 50)
                ->post('l_name')
                ->val('required', 'Last name')
                ->val('checkname', 'Last name')
                ->val('maxlength', 'Last name', 50)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 14)
                ->val('validMobile', 'Mobile no')
                ->post('ll_country_code')
                ->val('maxlength', 'Landline area code', 6)
                ->post('landline')
                ->val('maxlength', 'Landline no', 12)
                ->val('digit', 'Landline no')
                ->post('address')
                ->val('required', 'Address')
                ->val('maxlength', 'Address', 500)
                ->post('city')
                ->val('required', 'City')
                ->val('maxlength', 'City', 45)
                ->post('state')
                ->val('required', 'State')
                ->val('maxlength', 'State', 45)
                ->post('country')
                ->val('required', 'Country')
                ->val('maxlength', 'Country', 45)
                ->post('zip')
                ->val('required', 'Zip code')
                ->val('maxlength', 'Zip code', 10);
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

    public function compairtwopost($typeOfValidator, $firstpost_, $second_post) {
        $error = $this->_val->{$typeOfValidator}($this->_postData[$firstpost_], $this->_postData[$second_post]);

        if ($error) {
            if (isset($this->_error[$this->_currentItem])) {
                $existingError = $this->_error[$this->_currentItem];
                $count = count($existingError);
                $existingError[$count] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            } else {
                $existingError[0] = 'Landline area code';
                $existingError[1] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            }
        }

        return $this;
    }

}
