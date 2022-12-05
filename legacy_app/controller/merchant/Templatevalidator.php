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
class Templatevalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validateTemplateSave($user_id) {

        $this->post('template_name')
                ->val('required', 'Template name')
                ->val('maxlength', 'Template name', 45)
                ->val('isValidTemplatename', 'Template name', $user_id)
                ->post('particularname')
                ->val('isValidParticularTax', 'Particular name')
                ->post('taxx')
                ->val('isValidParticularTax', 'Tax name')
                ->file('uploaded_file')
                ->post('defaultValue')
                ->val('isValidParticularTax', 'Tax percentage')
                ->post('particular_total')
                ->val('required', 'Particular total')
                ->val('maxlength', 'Particular total', 45)
                ->post('tax_total')
                ->val('required', 'Tax total')
                ->val('maxlength', 'Tax total', 45)
                ->file('uploaded_file')
                ->val('isValidImagesize', 'Upload Logo', 500000)
                ->val('isValidImageExt', 'Upload Logo');
    }

    function validateSimpleTemplateSave($user_id) {

        $this->post('template_name')
                ->val('required', 'Template name')
                ->val('maxlength', 'Template name', 45)
                ->val('isValidTemplatename', 'Template name', $user_id)
                ->post('particularname')
                ->val('isValidParticularTax', 'Particular name')
                ->post('taxx')
                ->val('isValidParticularTax', 'Tax name')
                ->post('defaultValue')
                ->val('isValidParticularTax', 'Tax percentage')
                ->file('uploaded_file')
                ->val('isValidImagesize', 'Upload Logo', 500000)
                ->val('isValidImageExt', 'Upload Logo');
    }

    function validateCouponSave($merchant_id) {
        $this->post('coupon_code')
                ->val('required', 'Coupon code')
                ->val('maxlength', 'Coupon code', 45)
                ->val('isValidCouponCode', 'Coupon code', $merchant_id)
                ->post('start_date')
                ->val('required', 'Start date')
                ->val('validateDate', 'Start date')
                ->post('end_date')
                ->val('required', 'End date')
                ->compairtwopost('compairEndDate', 'end_date', 'start_date', 'End date')
                ->val('validateDate', 'End date')
                ->post('fixed_amount')
                ->val('isMoney', 'Value')
                ->post('percent')
                ->val('isMoney', 'Value');
    }

    function validateEventSave($user_id) {

        $this->post('event_name')
                ->val('required', 'Event name')
                ->val('maxlength', 'Event name', 250)
                ->file('banner')
                ->val('isValidImagesize', 'Banner image', 2000000)
                ->val('isValidImageExt', 'Banner image');
    }

    function validateEventPackage() {
        $this->post('_package_name')
                ->val('required', 'Package name')
                ->val('maxlength', 'Package name', 250)
                ->post('_unitcost')
                ->val('isMoney', 'Seat price')
                ->post('_tax')
                ->val('isMoney', 'Tax');
    }

    function validateTemplateUpdate() {

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

}
