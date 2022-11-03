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

    function validateMerchantRegister() {

        $this->post('email')
                ->val('required', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->val('isValidEmail', 'Email ID', 'register')
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 14)
                ->val('validMobile', 'Mobile no')
                ->post('password', 'rpassword')
                ->val('required', 'Password')
                ->val('maxlength', 'Password', 40)
                ->post('company_name')
                ->val('required', 'Company name')
                ->val('maxlength', 'Company name', 100);
    }

    function validateCompanySave() {
        $this->post('entity_type')
                ->val('required', 'Entity type')
                ->val('digit', 'Entity type')
                ->val('maxlength', 'Entity type', 4)
                ->post('industry_type')
                ->val('required', 'Industry type')
                ->val('digit', 'Industry type')
                ->val('maxlength', 'Industry type', 4)
                ->post('company_registration_number')
                ->val('maxlength', 'Registration Number', 100)
                ->post('pan')
                ->val('maxlength', 'Pan number', 12)
                ->post('current_address');
    }

    function validateContactSave() {
        $this->post('first_name')
                ->val('required', 'First name')
                ->val('maxlength', 'First name', 50)
                ->post('last_name')
                ->val('required', 'Last name')
                ->val('maxlength', 'Last name', 50)
                ->post('address1')
                ->val('required', 'Address')
                ->val('maxlength', 'Address', 250)
                ->post('address2')
                ->val('maxlength', 'Address', 250)
                ->post('city')
                ->val('required', 'City')
                ->val('maxlength', 'City', 45)
                ->post('state')
                ->val('required', 'State')
                ->val('maxlength', 'State', 45)
                ->post('country')
                ->val('required', 'Country')
                ->val('maxlength', 'Country', 45)
                ->post('zipcode')
                ->val('required', 'Zip code')
                ->val('digit', 'Zip code')
                ->val('maxlength', 'Zip code', 8)
                ->post('business_contact')
                ->val('required', 'Business contact')
                ->val('maxlength', 'Business contact', 20)
                ->post('business_email')
                ->val('required', 'Business Email ID')
                ->val('maxlength', 'Business Email ID', 254);
    }

    function validateProfileDocumentSave() {

        $this->val('isValidImagesize', 'Adhar card document', 1000000)
                ->val('isValidImagePDFExt', 'Adhar card document')
                ->file('doc_pan_card')
                ->val('isValidImagesize', 'Pan card document', 1000000)
                ->val('isValidImagePDFExt', 'Pan card document')
                ->file('doc_cancelled_cheque')
                ->val('isValidImagesize', 'Cancelled cheque', 1000000)
                ->val('isValidImagePDFExt', 'Cancelled cheque');
    }

    function validateDocumentSave() {
        $this->post('account_holder_name')
                ->val('required', 'Account holder name')
                ->val('maxlength', 'Account holder name', 100)
                ->post('account_number')
                ->val('required', 'Account number')
                ->val('maxlength', 'Account number', 20)
                ->post('bank_name')
                ->val('required', 'Bank name')
                ->val('maxlength', 'Bank name', 45)
                ->post('ifsc_code')
                ->val('required', 'IFSC code')
                ->val('validifscCode', 'IFSC code')
                ->val('maxlength', 'IFSC code', 20);
    }

    function validateUpdate() {

        $this->post('email')
                ->val('required', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->val('validEmail', 'Email ID')
                ->post('first_name')
                ->val('required', 'First name')
                ->val('checkname', 'First name')
                ->val('maxlength', 'First name', 50)
                ->post('last_name')
                ->val('required', 'Last name')
                ->val('checkname', 'Last name')
                ->val('maxlength', 'Last name', 50)
                ->post('mob_country_code')
                ->val('maxlength', 'Mobile country code', 6)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 14)
                ->val('validMobile', 'Mobile no')
                ->post('ll_country_code')
                ->val('maxlength', 'Landline country code', 6)
                ->post('landline')
                ->val('maxlength', 'Landline no', 12)
                ->val('digit', 'Landline no')
                ->post('address')
                ->val('required', 'Address')
                ->val('maxlength', 'Address', 250)
                ->post('address2')
                ->val('maxlength', 'Address', 250)
                ->post('company_name')
                ->val('required', 'Company name')
                ->val('maxlength', 'Company name', 100)
                ->post('type')
                ->val('required', 'Entity type')
                ->val('digit', 'Entity type')
                ->val('maxlength', 'Entity type', 4)
                ->post('industry_type')
                ->val('required', 'Industry type')
                ->val('digit', 'Industry type')
                ->val('maxlength', 'Industry type', 4)
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
                ->val('digit', 'Zip code')
                ->val('maxlength', 'Zip code', 8)
                ->post('registration_no')
                ->val('maxlength', 'Registration Number', 100)
                ->post('pan')
                ->val('maxlength', 'Pan number', 12)
                ->post('current_address')
                ->val('required', 'Business Address')
                ->val('maxlength', 'Address', 250)
                ->post('current_address2')
                ->val('maxlength', 'Business Address', 250)
                ->post('current_city')
                ->val('required', 'City')
                ->val('maxlength', 'City', 45)
                ->post('current_state')
                ->val('required', 'Business State')
                ->val('maxlength', 'Business State', 45)
                ->post('current_country')
                ->val('required', 'Business Country')
                ->val('maxlength', 'Business Country', 45)
                ->post('current_zip')
                ->val('required', 'Business Zip code')
                ->val('digit', 'Business Zip code')
                ->val('maxlength', 'Business Zip code', 8)
                ->post('country_code')
                ->val('maxlength', 'Landline country code', 6)
                ->post('business_contact')
                ->val('required', 'Business contact')
                ->val('maxlength', 'Business contact', 40)
                ->post('business_email')
                ->val('required', 'Business Email ID')
                ->val('maxlength', 'Business Email ID', 254);
    }

    function validateHome($merchant_id) {
        $this->post('website')
                ->val('maxlength', 'Website', 100)
                ->post('display_url')
                ->val('maxlength', 'Display URL', 45)
                ->val('isValidUrl', 'Display URL', $merchant_id);
    }

    function validateContactus() {
        $this->post('email_id')
                ->val('required', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->val('validEmail', 'Email ID')
                ->post('contact_no')
                ->val('maxlength', 'Contact no', 13)
                ->post('location')
                ->val('maxlength', 'Location', 255);
    }

    function validateImageBanner() {
        $this->file('banner')
                ->val('isValidImagesize', 'Banner', 2000000)
                ->val('isValidImageExt', 'Banner')
                ->file('logo')
                ->val('isValidImagesize', 'Logo', 500000)
                ->val('isValidImageExt', 'Logo');
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

    public function compairtwopost($typeOfValidator, $firstpost_, $second_post, $displayname) {
        $error = $this->_val->{$typeOfValidator}($this->_postData[$firstpost_], $this->_postData[$second_post]);

        if ($error) {
            if (isset($this->_error[$this->_currentItem])) {
                $existingError = $this->_error[$this->_currentItem];
                $count = count($existingError);
                $existingError[$count] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            } else {
                $existingError[0] = $displayname;
                $existingError[1] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            }
        }

        return $this;
    }

}
