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
class Subuservalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validateRoleSave($user_id) {

        $this->post('role_name')
                ->val('required', 'Role name')
                ->val('maxlength', 'Role name', 50)
                ->val('isValidRoleCount', 'Role limit', $user_id)
                ->val('isValidTemplatename', 'Role name', $user_id);
    }

    function validateSubmerchantSave($user_id) {
        $this->post('email')
                ->val('required', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->val('isValidEmail', 'Email ID', 'sub-user',$user_id)
                ->post('first_name')
                ->val('required', 'First name')
                ->val('maxlength', 'First name', 50)
                ->post('last_name')
                ->val('required', 'Last name')
                ->val('maxlength', 'Last name', 50)
                ->post('mob_country_code')
                ->val('maxlength', 'Mobile country code', 6)
                ->post('mobile')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('password', 'rpassword')
                ->val('required', 'Password')
                ->val('maxlength', 'Password', 40)
                ->valpasswd('isValidPassword', 'password', 'rpassword');
    }
    
    function validateApiSubmerchantSave($user_id) {
        $this->post('email')
                ->val('required', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->val('isValidEmail', 'Email ID', 'sub-user',$user_id)
                ->post('first_name')
                ->val('required', 'First name')
                ->val('maxlength', 'First name', 50)
                ->post('last_name')
                ->val('required', 'Last name')
                ->val('maxlength', 'Last name', 50)
                ->post('mobile')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no');
    }

    function validateSimpleTemplateUpdate($user_id) {

        $this->post('template_name')
                ->val('required', 'Template name')
                ->val('maxlength', 'Template name', 45)
                ->file('uploaded_file')
                ->val('isValidImagesize', 'Upload Logo', 500000)
                ->val('isValidImageExt', 'Upload Logo');
    }

    public function compairtwopost($typeOfValidator, $firstpost_, $second_post, $fieldname_) {
        $error = $this->_val->{$typeOfValidator}($_POST[$firstpost_], $_POST[$second_post]);
        if ($error) {
            if (isset($this->_error[$this->_currentItem])) {
                $existingError = $this->_error[$this->_currentItem];
                $count = count($existingError);
                $existingError[$count] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            } else {
                $existingError[0] = $fieldname_;
                $existingError[1] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            }
        }

        return $this;
    }

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
