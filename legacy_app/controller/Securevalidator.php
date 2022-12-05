<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Registervalidator
 *
 * @author Paresh
 */
class Securevalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validatexway($merchant_id) {

        $this->post('account_id')
                ->val('required', 'Account id')
                ->val('maxlength', 'Account id', 10)
                ->post('franchise_id')
                ->val('is_existFranchiseID', 'Franchise id', $merchant_id)
                ->post('return_url')
                ->val('required', 'Return url')
                ->val('maxlength', 'Return url', 250)
                ->post('reference_no')
                ->val('required', 'Reference no')
                ->val('maxlength', 'Reference no', 20)
                ->post('amount')
                ->val('required', 'Amount')
                ->val('maxlength', 'Amount', 11)
                ->val('isMoney', 'Amount')
                ->val('isMinAmount', 'Amount', 1)
                ->post('description')
                ->val('maxlength', 'Description', 250)
                ->post('name')
                ->val('required', 'Name')
                ->val('maxlength', 'Name', 128)
                ->post('address')
                ->val('maxlength', 'Address', 255)
                ->post('city')
                ->val('maxlength', 'City', 32)
                ->post('postal_code')
                ->val('maxlength', 'Postal code', 10)
                ->post('phone')
                ->val('required', 'Mobile')
                ->val('maxlength', 'Mobile', 12)
                ->val('minlength', 'Mobile', 10)
                ->post('email')
                ->val('required', 'Email')
                ->val('maxlength', 'Email', 100)
                ->val('validEmail', 'Email ID')
                ->post('udf1')
                ->val('maxlength', 'Udf1', 250)
                ->post('udf2')
                ->val('maxlength', 'Udf2', 250)
                ->post('udf3')
                ->val('maxlength', 'Udf3', 250)
                ->post('udf4')
                ->val('maxlength', 'Udf4', 250)
                ->post('udf5')
                ->val('maxlength', 'Udf5', 250)
                ->post('mdd')
                ->val('maxlength', 'mdd', 250)
                ->post('secure_hash')
                ->val('required', 'Secure hash')
                ->val('maxlength', 'Secure hash', 255)
                ->post('invoice_api_request');
    }

    /**
     * val - Compair two post values
     * 
     * @param string $typeOfValidator A method from the Form/Val class
     * @param string $arg A property to validate against
     */
    public function compairtwopost($typeOfValidator, $firstpost_, $second_post, $fieldname_) {
        $error = $this->_val->{$typeOfValidator}($this->_postData[$firstpost_], $this->_postData[$second_post]);

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

}
