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
class Paymentvalidator extends Validator
{

    function __construct($model_)
    {
        parent::__construct($model_);
    }

    function validateInvoice($user_id, $payment_request_id = NULL)
    {

        $this->post('invoice_number')
            ->val('maxlength', 'Invoice number ', 45)
            ->val('isValidInvoiceNumber', 'Invoice number', $user_id, $payment_request_id)
            ->post('narrative')
            ->val('maxlength', 'Narrative', 500)
            ->post('amount')
            ->val('isMoney', 'Amount')
            ->post('due_date')
            ->val('required', 'Due date')
            ->val('validateDate', 'Due date')
            ->post('bill_date')
            ->val('required', 'Bill date')
            ->val('validateDate', 'Bill date')
            ->compairtwopost('compairBillDate', 'bill_date', 'due_date', 'Bill date & Due date')
            ->post('expiry_date')
            ->compairtwopost('compairExpiryDate', 'expiry_date', 'due_date', 'Expiry date')
            ->post('bill_cycle_name')
            //->val('required', 'Billing cycle name')
            ->val('maxlength', 'Billing cycle name', 100)
            ->post('tax')
            ->val('isMoney', 'Tax Amount')
            ->post('grand_total')
            ->val('isMoney', 'Grand Total')
            ->val('isMaxAmount', 'Grand Total', $user_id)
            ->post('late_fee')
            ->val('isMoney', 'Late fee')
            ->post('advance')
            ->val('isMoney', 'Advance')
            ->post('totalcost')
            ->val('isMoney', 'Particular Total');
    }

    function validateAPIInvoice($merchant_id, $user_id = null, $payment_request_id = null,$currency=['INR'])
    {
        $this->post('invoice_number')
            ->val('maxlength', 'Invoice number ', 45)
            ->val('isValidInvoiceNumber', 'Invoice number', $user_id, $payment_request_id)
            ->post('franchise_id')
            ->val('is_existFranchiseID', 'Franchise id', $merchant_id)
            ->post('webhook_id')
            ->val('is_existWebhookID', 'Webhook id', $merchant_id)
            ->post('customer_code')
            ->val('is_existCustomerCode', 'Customer code', $merchant_id)
            ->post('narrative')
            ->val('maxlength', 'Narrative', 500)
            ->post('due_date')
            ->val('validateAPIDate', 'Due date')
            ->post('bill_date')
            ->val('validateAPIDate', 'Bill date')
            ->compairtwopost('compairBillDate', 'bill_date', 'due_date', 'Bill date & Due date')
            ->post('expiry_date')
            ->compairtwopost('compairExpiryDate', 'expiry_date', 'due_date', 'Expiry date & Due date')
            ->post('bill_cycle_name')
            ->val('required', 'Billing cycle name')
            ->val('maxlength', 'Billing cycle name', 40)
            ->post('tax')
            ->val('isMoney', 'Tax Amount')
            ->post('grand_total')
            ->val('isMoney', 'Grand Total')
            ->val('isMaxAmount', 'Grand Total', $merchant_id)
            ->post('late_fee')
            ->val('isMoney', 'Late fee')
            ->post('currency')
            ->val('inArray', 'Currency', $currency)
            ->post('advance')
            ->val('isMoney', 'Advance')
            ->post('totalcost')
            ->val('isMoney', 'Particular Total');
    }

    function validateCableSubscription()
    {
        $this->post('due_date')
            ->val('CompairCurrentDate', 'Due date')
            ->post('bill_date')
            ->val('CompairCurrentDate', 'Bill date')
            ->compairtwopost('compairBillDate', 'bill_date', 'due_date', 'Bill date & Due date')
            ->post('settopbox')
            ->val('required', 'Set top box');
    }

    function validateCustomerRegister()
    {
        $this->post('sms')
            ->val('maxlength', 'SMS', 160)
            ->post('customer_id')
            ->val('required', 'Customer');
    }

    function validateSubscriptionAPIInvoice($merchant_id, $user_id = null)
    {
        $this->post('invoice_number')
            ->val('maxlength', 'Invoice number ', 45)
            ->val('isValidInvoiceNumber', 'Invoice number', $user_id, null)
            ->post('mode')
            ->val('existin', 'Mode', array('Monthly', 'Daily', 'Weekly', 'Yearly'))
            ->post('end_mode')
            ->val('existin', 'End Mode', array('Never', 'Occurences', 'End date'))
            ->post('carry_forword_dues')
            ->val('existin', 'Carry forword dues', array('0', '1'))
            ->post('repeat_every')
            ->val('required', 'Repeat Every')
            ->val('digit', 'Repeat Every')
            ->post('occurences')
            ->val('digit', 'Occurences')
            ->post('end_date')
            ->val('validateAPIDate', 'End date')
            ->post('start_date')
            ->val('validateAPIDate', 'Start date')
            ->compairtwopost('compairBillDate', 'start_date', 'due_date', 'Start date & Due date')
            ->post('franchise_id')
            ->val('is_existFranchiseID', 'Franchise id', $merchant_id)
            ->post('webhook_id')
            ->val('is_existWebhookID', 'Webhook id', $merchant_id)
            ->post('customer_code')
            ->val('is_existCustomerCode', 'Customer code', $merchant_id)
            ->post('narrative')
            ->val('maxlength', 'Narrative', 500)
            ->post('amount')
            ->val('isMoney', 'Amount')
            ->post('due_date')
            ->val('validateAPIDate', 'Due date')
            ->post('due_date')
            ->val('CompairCurrentDate', 'Due date')
            ->post('bill_date')
            ->val('validateAPIDate', 'Bill date')
            ->compairtwopost('compairBillDate', 'bill_date', 'due_date', 'Bill date & Due date')
            ->post('expiry_date')
            ->compairtwopost('compairExpiryDate', 'expiry_date', 'due_date', 'Expiry date & Due date')
            ->post('bill_cycle_name')
            ->val('required', 'Billing cycle name')
            ->val('maxlength', 'Billing cycle name', 40)
            ->post('tax')
            ->val('isMoney', 'Tax Amount')
            ->post('grand_total')
            ->val('isMoney', 'Grand Total')
            ->val('isMaxAmount', 'Grand Total', $merchant_id)
            ->post('late_fee')
            ->val('isMoney', 'Late fee')
            ->post('advance')
            ->val('isMoney', 'Advance')
            ->post('totalcost')
            ->val('isMoney', 'Particular Total');
    }

    function validateAPIBeforecustomer($merchant_id)
    {

        $this->post('narrative')
            ->val('maxlength', 'Narrative', 500)
            ->post('amount')
            ->val('isMoney', 'Amount')
            ->post('due_date')
            ->val('validateAPIDate', 'Due date')
            ->post('due_date')
            ->val('CompairCurrentDate', 'Due date')
            ->post('bill_date')
            ->val('validateAPIDate', 'Bill date')
            ->compairtwopost('compairBillDate', 'bill_date', 'due_date', 'Bill date & Due date')
            ->post('expiry_date')
            ->compairtwopost('compairExpiryDate', 'expiry_date', 'due_date', 'Expiry date & Due date')
            ->post('bill_cycle_name')
            ->val('required', 'Billing cycle name')
            ->val('maxlength', 'Billing cycle name', 100)
            ->post('tax')
            ->val('isMoney', 'Tax Amount')
            ->post('grand_total')
            ->val('isMoney', 'Grand Total')
            ->val('isMaxAmount', 'Grand Total', $merchant_id)
            ->post('late_fee')
            ->val('isMoney', 'Late fee')
            ->post('advance')
            ->val('isMoney', 'Advance')
            ->post('totalcost')
            ->val('isMoney', 'Particular Total');
    }

    function validateEventMerchantRespond()
    {
        $this->post('bank_transaction_no')
            ->val('maxlength', 'Bank refference number', 20)
            ->post('cheque_no')
            ->val('maxlength', 'Cheque number', 8)
            ->val('digit', 'Cheque number')
            ->post('cash_paid_to')
            ->val('maxlength', 'Cash paid to', 45)
            ->post('amount')
            ->val('isMoney', 'Amount')
            ->post('customer_id')
            ->val('required', 'Customer');
    }

    function validateEventBooking()
    {
        $this->post('grand_total')
            ->val('isMoney', 'Grand Total');
    }

    function validateEventPatronRespond()
    {
        $this->post('name')
            ->val('required', 'First name')
            ->val('maxlength', 'First name', 100)
            ->post('email')
            ->val('required', 'Email ID')
            ->val('maxlength', 'Email ID', 254)
            ->val('validEmail', 'Email ID')
            ->post('mobile')
            ->val('digit', 'Mobile ')
            ->val('maxlength', 'Mobile number', 13);
    }

    function validatePayment()
    {
        $this->post('customer_name')
            ->val('required', 'Customer name')
            ->val('maxlength', 'Customer name', 100)
            ->post('email')
            ->val('required', 'Email ID')
            ->val('maxlength', 'Email ID', 250)
            ->val('validEmail', 'Email ID')
            ->post('mobile')
            ->val('required', 'Mobile number')
            ->val('digit', 'Mobile number')
            ->val('maxlength', 'Mobile number', 13)
            ->post('amount')
            ->val('required', 'Amount')
            ->val('isMoney', 'Amount')
            ->post('amount')
            ->val('isValidDocs', 'Documents');
    }

    function validateRespond()
    {
        $this->post('bank_transaction_no')
            ->val('maxlength', 'Bank refference number', 20)
            ->post('cheque_no')
            ->val('maxlength', 'Cheque number', 8)
            ->val('digit', 'Cheque number')
            ->post('cash_paid_to')
            ->val('maxlength', 'Cash paid to', 100)
            ->post('mode')
            ->val('existin', 'Payment mode', array(1, 2, 3, 4, 5, 6))
            ->post('bank_name')
            ->val('maxlength', 'Bank name', 100)
            ->post('date')
            ->val('validateDate', 'Paid date')
            ->post('amount')
            ->val('isMoney', 'Amount');
    }

    function validateBillingDetails()
    {
        $this->post('email')
            ->val('required', 'Email ID')
            ->val('maxlength', 'Email ID', 254)
            ->val('validEmail', 'Email ID')
            ->post('name')
            ->val('required', 'Name')
            ->val('checkname', 'Name')
            ->val('maxlength', 'Name', 100)
            ->post('mobile')
            ->val('required', 'Mobile number')
            ->val('maxlength', 'Mobile number', 12)
            ->val('minlength', 'Mobile number', 10)
            ->val('digit', 'Mobile number')
            ->post('address')
            ->val('maxlength', 'Address', 500)
            ->post('city')
            ->val('maxlength', 'City', 45)
            ->post('state')
            ->val('maxlength', 'State', 45)
            ->post('zipcode')
            ->val('digit', 'Zip code')
            ->val('maxlength', 'Zip code', 10)
            ->val('minlength', 'Zip code', 4);
    }

    function validateBillingEventDetails()
    {
        $this->post('email')
            ->val('required', 'Email ID')
            ->val('maxlength', 'Email ID', 254)
            ->val('validEmail', 'Email ID')
            ->post('name')
            ->val('required', 'Name')
            ->val('checkname', 'Name')
            ->val('maxlength', 'Name', 100)
            ->post('mobile')
            ->val('required', 'Mobile number')
            ->val('maxlength', 'Mobile number', 12)
            ->val('minlength', 'Mobile number', 10)
            ->val('digit', 'Mobile number')
            ->post('address')
            ->val('maxlength', 'Address', 500)
            ->post('city')
            ->val('maxlength', 'City', 45)
            ->post('state')
            ->val('maxlength', 'State', 45)
            ->post('zipcode')
            ->val('digit', 'Zip code')
            ->val('maxlength', 'Zip code', 10)
            ->val('minlength', 'Zip code', 6);
    }

    function validatePaymentReceivedAPI()
    {
        $this->post('from_date')
            ->val('validateDate', 'From date')
            ->post('to_date')
            ->val('validateDate', 'To date')
            ->compairtwopost('compairBillDate', 'from_date', 'to_cdate', 'compair_todate');
    }

    /**
     * val - Compair two post values
     * 
     * @param string $typeOfValidator A method from the Form/Val class
     * @param string $arg A property to validate against
     */
    public function compairtwopost($typeOfValidator, $firstpost_, $second_post, $fieldname_)
    {
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
