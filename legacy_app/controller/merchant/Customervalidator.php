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
class Customervalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validateCustomerSave($merchant_id, $states = null, $countries=null,$country_name=null) {

        $this->post('customer_code')
                ->val('required', 'Customer code')
                ->val('maxlength', 'Customer code', 45)
                ->val('isValidCustomerCode', 'Customer code', $merchant_id)
                ->post('customer_name')
                ->val('required', 'Customer name')
                ->val('maxlength', 'Customer name', 100)
                ->post('email')
                ->val('validEmail', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->post('mobile')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('address')
                ->val('maxlength', 'Address', 250)
                ->post('address2')
                ->val('maxlength', 'Address', 250)
                ->post('country')
                ->val('maxlength', 'Country', 75)
                ->val('validateCountry', 'Country', $countries)
                ->post('city')
                ->val('maxlength', 'City', 45)
                ->post('state')
                ->val('maxlength', 'State', 45)
                ->val('validateState', 'State', $states, $country_name)
                ->post('password')
                ->val('maxlength', 'Password', 20)
                ->post('GST')
                ->val('maxlength', 'GST', 20)
                ->val('GSTNumber', 'GST')
                ->post('zipcode')
                ->val('digit', 'Zip code')
                ->val('maxlength', 'Zip code', 8);
    }

    function validateCustomerUpdate($merchant_id, $customer_id = NULL) {

        $this->post('customer_code')
                ->val('required', 'Customer code')
                ->val('maxlength', 'Customer code', 45)
                ->val('isValidCustomerCode', 'Customer code', $merchant_id, $customer_id)
                ->post('customer_name')
                ->val('required', 'Customer name')
                ->val('maxlength', 'Customer name', 150)
                ->post('email')
                ->val('validEmail', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->post('mobile')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('address')
                ->val('maxlength', 'Address', 250)
                ->post('address2')
                ->val('maxlength', 'Address', 250)
                ->post('country')
                ->val('maxlength', 'Country', 75)
                ->post('city')
                ->val('maxlength', 'City', 45)
                ->post('state')
                ->val('maxlength', 'State', 45)
                ->post('password')
                ->val('maxlength', 'Password', 20)
                ->post('GST')
                ->val('maxlength', 'GST', 20)
                ->val('GSTNumber', 'GST')
                ->post('zipcode')
                ->val('digit', 'Zip code')
                ->val('maxlength', 'Zip code', 8);
    }

    function validateCustomUpload($detail, $name) {
        $validation = '';
        switch ($detail['col_datatype']) {
            case 'number' :
                $validation = "digit";
                break;
            case 'money' :
                $validation = "isamount";
                break;
            case 'percent' :
                $validation = "isPercentage";
                break;
            case 'date' :
                $validation = "validateWEBDate";
                break;
            case 'apidate' :
                $validation = "validateAPIDate";
                break;
            default :
                $validation = '';
        }
        if ($validation != '') {
            $this->post($name)
                    ->val($validation, $detail['column_name']);
        }
    }

    function validateGroupSave($merchant_id) {

        $this->post('group_name')
                ->val('required', 'Group name')
                ->val('maxlength', 'Group name', 100)
                ->val('isValidTemplatename', 'Group name', $merchant_id);
    }

    function validateGroupCustomer() {
        $this->post('group_id')
                ->val('required', 'Group name');
    }

}
