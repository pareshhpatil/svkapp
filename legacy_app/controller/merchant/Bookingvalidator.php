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
class Bookingvalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validateCategorySave($merchant_id) {

        $this->post('category_name')
                ->val('required', 'Category name')
                ->val('maxlength', 'Category name', 250)
                ->val('isValidTemplatename', 'Category name', $merchant_id);
    }

    function validateMembershipSave($merchant_id) {

        $this->post('title')
                ->val('required', 'Title')
                ->val('maxlength', 'Category name', 100);
    }

    function validateCalendarSave($category_id) {
        $this->post('category')
                ->val('required', 'Category name')
                ->post('calendar_name')
                ->val('required', 'Calendar title')
                ->val('maxlength', 'Calendar title', 45)
                ->val('isValidCalendarname', 'Calendar title', $category_id)
                ->post('description')
                ->val('maxlength', 'Calendar description', 450)
                ->post('notification_email')
                ->val('multiEmail', 'Notification Emails')
                ->post('notification_mobile')
                ->val('multiMobile', 'Notification Mobiles')
                ->file('uploaded_file')
                ->val('isValidImagesize', 'Upload Image', 500000)
                ->val('isValidImageExt', 'Upload Image');
    }

    function validateCalendarUpdate() {
        $this->post('category')
                ->val('required', 'Category name')
                ->post('calendar_name')
                ->val('required', 'Calendar title')
                ->val('maxlength', 'Calendar title', 45)
                ->file('uploaded_file')
                ->val('isValidImagesize', 'Upload Image', 500000)
                ->val('isValidImageExt', 'Upload Image');
    }

    function validateNotificationUpdate() {
        $this->post('notification_email')
                ->val('multiEmail', 'Notification Emails')
                ->post('notification_mobile')
                ->val('multiMobile', 'Notification Mobiles');
    }

}
