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
class Uploadvalidator extends Validator
{

    function __construct($model_)
    {
        parent::__construct($model_);
    }

    function validateExcelUpload()
    {
        $this->file('fileupload')
            ->val('isValidExcelsize', 'Invalid size', 5000000)
            ->val('isValidExcelExt', 'Invalid file');
    }

    function validateCsvUpload()
    {
        $this->file('fileupload')
            ->val('isValidExcelsize', 'Invalid size', 5000000)
            ->val('isValidCSVExt', 'Invalid file');
    }

    function validateBulkInvoice($merchant_id, $user_id = NULL, $currency = ['INR'], $einvoice_type = [], $billing_profile = [])
    {
        $this->post('invoice_narrative')
            ->val('maxlength', 'Narrative', 500)
            ->post('customer_code')
            ->val('required', 'Customer code')
            ->post('previous_dues')
            ->val('isamount', 'Previous dues')
            ->post('invoice_number')
            ->val('maxlength', 'Invoice number ', 45)
            ->val('isValidInvoiceNumber', 'Invoice number', $user_id)
            ->post('amount')
            ->val('isMaxAmount', 'Grand Total', $merchant_id)
            ->val('isamount', 'Amount')
            ->post('franchise_id')
            ->val('is_existFranchiseID', 'Franchise id', $merchant_id)
            ->post('vendor_id')
            ->val('is_existVendorID', 'Vendor id', $merchant_id)
            ->post('customer_code')
            ->val('is_existCustomerCode', 'Customer code', $merchant_id)
            ->post('due_date')
            ->val('required', 'Due date')
            ->val('validateDate', 'Due date')
            ->post('bill_date')
            ->val('required', 'Bill date')
            ->val('validateDate', 'Bill date')
            ->compairtwopost('compairBillDate', 'bill_date', 'due_date', 'Bill date & Due date')
            ->post('expiry_date')
            ->val('validateDate', 'Expiry date')
            ->compairtwopost('compairExpiryDate', 'expiry_date', 'due_date', 'Expiry date')
            ->post('bill_cycle_name')
            ->val('required', 'Billing cycle name')
            ->val('maxlength', 'Billing cycle name', 40)
            ->post('tax')
            ->val('isamount', 'Tax Amount')
            ->post('grand_total')
            ->val('isMoney', 'Grand Total')
            ->post('particular_column')
            ->val('isValidParticular', 'Particular')
            ->post('advance')
            ->val('isMoney', 'Advance')
            ->post('gst')
            ->val('ArrayValidate', 'GST', array(0, 5, 12, 18, 28))
            ->post('total_amount')
            ->val('ArrayValid', 'Amount', 'isamount')
            ->post('rate')
            ->val('ArrayValid', 'Rate', 'isamount')
            ->post('currency')
            ->val('inArray', 'Currency', $currency)
            ->post('einvoice_type')
            ->val('inArray', 'e-Invoice type', $einvoice_type)
            ->post('billing_profile')
            ->val('inArray', 'Billing profile', $billing_profile)
            ->post('commission_type')
            ->val('inArray', 'Commission type', array('Percentage', 'Fixed'))
            ->post('commission_value')
            ->val('isMoney', 'Commission value')
            ->post('totalcost')
            ->val('isMoney', 'Particular Total')
            ->val('isamount', 'Particular Total')
            ->compairthreepost('valuesWithdatatype', 'values', 'datatypes', 'column_name', 'Invoice values');
    }

    function validateBulkSubscription($merchant_id, $user_id = NULL, $currency = ['INR'], $einvoice_type = [], $billing_profile = [])
    {
        $this->post('invoice_narrative')
            ->val('maxlength', 'Narrative', 500)
            ->post('customer_code')
            ->val('required', 'Customer code')
            ->post('previous_dues')
            ->val('isamount', 'Previous dues')
            ->post('invoice_number')
            ->val('maxlength', 'Invoice number ', 45)
            ->val('isValidInvoiceNumber', 'Invoice number', $user_id)
            ->post('amount')
            ->val('isamount', 'Amount')
            ->post('franchise_id')
            ->val('is_existFranchiseID', 'Franchise id', $merchant_id)
            ->post('vendor_id')
            ->val('is_existVendorID', 'Vendor id', $merchant_id)
            ->post('customer_code')
            ->val('is_existCustomerCode', 'Customer code', $merchant_id)
            ->post('due_date')
            ->val('required', 'Due date')
            ->val('validateDate', 'Due date')
            ->post('bill_date')
            ->val('required', 'Bill date')
            ->val('CompairCurrentDate', 'Bill date')
            ->val('validateDate', 'Bill date')
            ->compairtwopost('compairBillDate', 'bill_date', 'due_date', 'Bill date & Due date')
            ->post('expiry_date')
            ->val('validateDate', 'Expiry date')
            ->compairtwopost('compairExpiryDate', 'expiry_date', 'due_date', 'Expiry date')
            ->post('bill_cycle_name')
            ->val('required', 'Billing cycle name')
            ->val('maxlength', 'Billing cycle name', 40)
            ->post('tax')
            ->val('isamount', 'Tax Amount')
            ->post('grand_total')
            ->val('isMoney', 'Grand Total')
            ->val('isMaxAmount', 'Grand Total', $merchant_id)
            ->post('particular_column')
            ->val('isValidParticular', 'Particular')
            ->post('advance')
            ->val('isMoney', 'Advance')
            ->post('gst')
            ->val('ArrayValidate', 'GST', array(0, 5, 12, 18, 28))
            ->post('total_amount')
            ->val('ArrayValid', 'Amount', 'isamount')
            ->post('rate')
            ->val('ArrayValid', 'Rate', 'isamount')
            ->post('currency')
            ->val('inArray', 'Currency', $currency)
            ->post('einvoice_type')
            ->val('inArray', 'e-Invoice type', $einvoice_type)
            ->post('billing_profile')
            ->val('inArray', 'Billing profile', $billing_profile)
            ->post('totalcost')
            ->val('isMoney', 'Particular Total')
            ->val('isamount', 'Particular Total')
            ->compairthreepost('valuesWithdatatype', 'values', 'datatypes', 'column_name', 'Invoice values')
            ->post('mode')
            ->val('required', 'Mode')
            ->val('inArray', 'Mode', array('month', 'day', 'week', 'year'))
            ->post('repeat_every')
            ->val('required', 'Repeat every')
            ->val('numberRange', 'Repeat every ', 1, 365)
            ->post('end_mode')
            ->val('required', 'End mode')
            ->val('inArray', 'End Mode', array('never', 'occurence', 'end date'))
            ->post('end_value')
            ->isValidEndValue('end_mode', 'end_value', 'End Value')
            ->post('billing_period_start_date')
            ->val('validateDate', 'Billing period start date')
            ->post('billing_period_duration')
            ->val('digit', 'Billing period duration')
            ->post('billing_period_type')
            ->val('inArray', 'Billing period type', array('days', 'month'));
    }

    function validateAmazonGST()
    {
        $this->post('seller_gstin')
            ->val('required', 'Seller GST')
            ->val('maxlength', 'Seller GST', 15)
            ->post('invoice_number')
            ->val('required', 'Invoice No')
            ->val('maxlength', 'Seller GST', 45)
            ->post('bill_from')
            ->val('required', 'Bill from')
            ->val('maxlength', 'Bill from', 45)
            ->post('invoice_date')
            ->val('required', 'Invoice date')
            ->val('validateDate', 'Invoice date')
            ->post('qty')
            ->val('isamount', 'Qty')
            ->post('description')
            ->post('invoice_amount')
            ->val('isamount', 'Invoice amount')
            ->post('exclusive_tax')
            ->val('isamount', 'Exclusive tax amount')
            ->post('total_tax_amount')
            ->val('isamount', 'Tax amount')
            ->post('cgst_rate')
            ->val('isamount', 'CGST rate')
            ->post('sgst_rate')
            ->val('isamount', 'SGST rate')
            ->post('igst_rate')
            ->val('isamount', 'IGST rate')
            ->post('cgst_tax')
            ->val('isamount', 'CGST tax')
            ->post('sgst_tax')
            ->val('isamount', 'SGST tax')
            ->post('igst_tax')
            ->val('isamount', 'IGST tax');
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

    public function compairthreepost($typeOfValidator, $firstpost_, $second_post, $third_post, $fieldname_)
    {
        $int = 0;
        while ($_POST[$second_post][$int] != '') {
            $error = $this->_val->{$typeOfValidator}($_POST[$firstpost_][$int], $_POST[$second_post][$int]);
            if ($error) {
                if (isset($this->_error[$_POST[$second_post][$int]])) {
                    $existingError = $this->_error[$_POST[$second_post][$int]];
                    $count = count($existingError);
                    $existingError[$count] = $error;
                    $this->_error[$_POST[$second_post][$int]] = $existingError;
                } else {
                    $existingError[0] = $_POST[$third_post][$int];
                    $existingError[1] = $error;
                    $this->_error[$_POST[$second_post][$int]] = $existingError;
                }
            }
            $int++;
        }
        return $this;
    }

    public function isValidEndValue($firstpost_, $second_post, $fieldname_)
    {
        $error = '';
        if (isset($_POST[$firstpost_]) && isset($_POST[$second_post])) {
            if ($_POST[$firstpost_] == 'never') {
            }
            if ($_POST[$firstpost_] == 'occurence') {
                if ($_POST[$second_post] == 0) {
                    $error = 'Please enter end value greater than 0';
                } else {
                    $error = $this->_val->{'digit'}($_POST[$second_post]);
                }
            }
            if ($_POST[$firstpost_] == 'end date') {
                $error = $this->_val->{'validateDate'}($_POST[$second_post]);
                if (!$error) {
                    $error = $this->_val->{'CompairCurrentDate'}($_POST[$second_post]);
                }
            }
        } else {
            if ($_POST[$firstpost_] == 'occurence' || $_POST[$firstpost_] == 'end date') {
                $error = $this->_val->{'required'}($_POST[$second_post]);
            }
        }

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
