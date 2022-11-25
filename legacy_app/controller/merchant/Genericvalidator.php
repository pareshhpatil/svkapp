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
class Genericvalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validatePromoTemplate($merchant_id) {

        $this->post('template_name')
                ->val('required', 'Template name')
                ->val('maxlength', 'Template name', 45)
                ->val('isValidTemplatename', 'Template name', $merchant_id)
                ->post('sms')
                ->val('required', 'SMS')
                ->val('maxlength', 'SMS', 160);
    }

    function validatePromotionSave($merchant_id) {

        $this->post('promotion_name')
                ->val('required', 'Promotion name')
                ->val('maxlength', 'Promotion name', 45)
                ->val('isValidCalendarname', 'Promotion name', $merchant_id)
                ->post('total_records')
                ->val('isValidSMSCOUNT', 'SMS package count', $merchant_id)
                ->post('sms')
                ->val('required', 'SMS')
                ->val('maxlength', 'SMS', 160)
                ->post('numbers')
                ->val('multiMobile', 'Mobile numbers');
    }

}
