<?php

class Suppliervalidator extends Validator {

    function __construct($model_) {
        parent::__construct($model_);
    }

    function validateSupplierSave() {

        $this->post('email')
                ->val('required', 'Email ID1')
                ->val('maxlength', 'Email ID1', 254)
                ->post('email2')
                ->val('maxlength', 'Email ID2', 254)
                ->post('mob_country_code1')
                ->val('maxlength', 'Mobile country code', 6)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('mob_country_code2')
                ->val('maxlength', 'Landline country code', 6)
                ->post('mobile2')
                ->val('maxlength', 'Landline no', 12)
                ->val('digit', 'Landline no')
                ->post('contact_person_name')
                ->val('required', 'Contact person name')
                ->val('maxlength', 'Contact person name', 100)
                ->post('contact_person_name2')
                ->val('maxlength', 'Contact person name2', 100)
                ->post('supplier_company_name')
                ->val('required', 'Supplier company name')
                ->val('maxlength', 'Supplier company name', 100)
                ->post('industry_type')
                ->val('required', 'Industry type')
                ->val('digit', 'Industry type')
                ->val('maxlength', 'Industry type', 4);
    }

    function validateFranchiseSave() {

        $this->post('franchise_name')
                ->val('required', 'Franchise name')
                ->val('maxlength', 'Franchise name', 100)
                ->post('email')
                ->val('maxlength', 'Email ID', 250)
                ->post('email2')
                ->val('maxlength', 'Email ID2', 250)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('mobile2')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('contact_person_name')
                ->val('required', 'Contact person name')
                ->val('maxlength', 'Contact person name', 100)
                ->post('contact_person_name2')
                ->val('maxlength', 'Contact person name2', 100)
                ->post('account_holder_name')
                ->val('maxlength', 'Account holder name', 100)
                ->post('account_number')
                ->val('maxlength', 'Account number', 20)
                ->post('bank_name')
                ->val('maxlength', 'Bank name', 45)
                ->post('ifsc_code')
                ->val('validifscCode', 'IFSC code')
                ->val('maxlength', 'IFSC code', 20);
    }

    function validateBulkFranchiseSave() {

        $this->post('franchise_name')
                ->val('required', 'Franchise name')
                ->val('maxlength', 'Franchise name', 100)
                ->post('email')
                ->val('maxlength', 'Email ID', 250)
                ->post('email2')
                ->val('maxlength', 'Email ID2', 250)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('minlength', 'Mobile no', 10)
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('mobile2')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('contact_person_name')
                ->val('required', 'Contact person name')
                ->val('maxlength', 'Contact person name', 100)
                ->post('contact_person_name2')
                ->val('maxlength', 'Contact person name2', 100)
                ->post('account_holder_name')
                ->val('required', 'Account holder name')
                ->val('maxlength', 'Account holder name', 100)
                ->post('account_number')
                ->val('required', 'Account number')
                ->val('maxlength', 'Account number', 20)
                ->post('bank_name')
                ->val('required', 'Bank name')
                ->val('maxlength', 'Bank name', 45)
                ->post('account_type')
                ->val('required', 'Account type')
                ->val('inArray', 'Account type', array('Saving', 'Current'))
                ->post('ifsc_code')
                ->val('required', 'IFSC code')
                ->val('validifscCode', 'IFSC code')
                ->val('maxlength', 'IFSC code', 20)
                ->post('online_settlement')
                ->val('inArray', 'Online Settlement', array('1', '0'))
                ->post('commision_type')
                ->val('inArray', 'Commision type', array('0', '1', '2'))
                ->post('commision_percent')
                ->val('isPercentage', 'Commision percentage')
                ->post('commision_amount')
                ->val('isamount', 'Commision amount')
                ->post('settlement_type')
                ->val('inArray', 'Settlement type', array('0', '1', '2'));
    }

    function validateVendorSave($merchant_id) {

        $this->post('vendor_name')
                ->val('required', 'Vendor name')
                ->val('maxlength', 'Vendor name', 100)
                ->val('maxlength', 'Vendor code', 45)
                ->val('isValidCustomerCode', 'Vendor code', $merchant_id)
                ->post('email')
                ->val('validEmail', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('address')
                ->val('required', 'Address')
                ->val('maxlength', 'Address', 250)
                ->post('city')
                ->val('maxlength', 'City', 45)
                ->post('state')
                ->val('required', 'State')
                ->val('maxlength', 'State', 45)
                ->post('zipcode')
                ->val('maxlength', 'Zipcode', 6)
                ->post('pan')
                ->val('maxlength', 'PAN', 20)
                ->post('adhar_card')
                ->val('maxlength', 'Adhar Card', 20)
                ->post('gst')
                ->val('maxlength', 'GSTIN', 20)
                ->post('account_holder_name')
                ->val('maxlength', 'Account holder name', 100)
                ->post('account_number')
                ->val('maxlength', 'Account number', 20)
                ->post('bank_name')
                ->val('maxlength', 'Bank name', 45)
                ->post('account_type')
                ->val('inArray', 'Account type', array('Saving', 'Current'))
                ->post('ifsc_code')
                ->val('validifscCode', 'IFSC code')
                ->val('maxlength', 'IFSC code', 20);
    }

    function validateBulkVendorSave() {

        $this->post('vendor_name')
                ->val('required', 'Vendor name')
                ->val('maxlength', 'Vendor name', 100)
                ->post('email')
                ->val('validEmail', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('address')
                ->val('required', 'Address')
                ->val('maxlength', 'Address', 250)
                ->post('city')
                ->val('maxlength', 'City', 45)
                ->post('state')
                ->val('maxlength', 'State', 45)
                ->post('zipcode')
                ->val('maxlength', 'Zipcode', 6)
                ->post('pan')
                ->val('maxlength', 'PAN', 20)
                ->post('adhar_card')
                ->val('maxlength', 'Adhar Card', 20)
                ->post('gst')
                ->val('maxlength', 'GSTIN', 20)
                ->post('account_holder_name')
                ->val('required', 'Account holder name')
                ->val('maxlength', 'Account holder name', 100)
                ->post('account_number')
                ->val('required', 'Account number')
                ->val('maxlength', 'Account number', 20)
                ->post('bank_name')
                ->val('required', 'Bank name')
                ->val('maxlength', 'Bank name', 45)
                ->post('account_type')
                ->val('required', 'Account type')
                ->val('inArray', 'Account type', array('Saving', 'Current'))
                ->post('ifsc_code')
                ->val('required', 'IFSC code')
                ->val('validifscCode', 'IFSC code')
                ->val('maxlength', 'IFSC code', 20)
                ->post('online_settlement')
                ->val('inArray', 'Online Settlement', array('1', '0'))
                ->post('commision_type')
                ->val('inArray', 'Commision type', array('0', '1', '2'))
                ->post('commision_percent')
                ->val('isPercentage', 'Commision percentage')
                ->post('commision_amount')
                ->val('isamount', 'Commision amount')
                ->post('settlement_type')
                ->val('inArray', 'Settlement type', array('0', '1', '2'));
    }

    function validateBulkBeneficiarySave($merchant_id = null) {

        $this->post('name')
                ->val('required', 'Beneficiary name')
                ->val('maxlength', 'Beneficiary name', 100)
                ->post('beneficiary_code')
                ->val('isValidBeneficiaryCode', 'Beneficiary code', $merchant_id)
                ->post('email')
                ->val('validEmail', 'Email ID')
                ->val('maxlength', 'Email ID', 254)
                ->post('mobile')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('address')
                ->val('required', 'Address')
                ->val('maxlength', 'Address', 250)
                ->post('city')
                ->val('maxlength', 'City', 45)
                ->post('state')
                ->val('maxlength', 'State', 45)
                ->post('zipcode')
                ->val('maxlength', 'Zipcode', 6)
                ->post('account_number')
                ->val('required', 'Account number')
                ->val('maxlength', 'Account number', 20)
                ->val('isValidBeneficiery', 'Account details', $merchant_id)
                ->post('ifsc_code')
                ->val('required', 'IFSC code')
                ->val('validifscCode', 'IFSC code')
                ->val('maxlength', 'IFSC code', 20)
                ->post('type')
                ->val('inArray', 'Type', array('Customer', 'Vendor', 'Franchise', 'Employee', 'Other'));
    }

    function validateVendorUpdate() {

        $this->post('pan')
                ->val('maxlength', 'PAN', 20)
                ->post('adhar_card')
                ->val('maxlength', 'Adhar Card', 20)
                ->post('gst')
                ->val('maxlength', 'GSTIN', 20)
                ->post('account_holder_name');
    }

    function validateVendorTransfer($merchant_id) {
        $this->post('transfer_type')
                ->val('inArray', 'Type', array('1', '2'))
                ->post('vendor_id')
                ->val('required', 'Vendor ID')
                ->val('isValidVendorID', 'Vendor ID', $merchant_id)
                ->val('isValidTrasferVendor', 'Vendor ID', $merchant_id)
                ->post('franchise_id')
                ->val('required', 'Franchise ID')
                ->val('is_existFranchiseID', 'Franchise ID', $merchant_id)
                ->post('amount')
                ->val('required', 'Amount')
                ->val('isMinAmount', 'Amount', 1)
                ->val('isMoney', 'Amount')
                ->val('isamount', 'Amount')
                ->post('bank_transaction_no')
                ->val('maxlength', 'Bank refference number', 20)
                ->post('cheque_no')
                ->val('maxlength', 'Cheque number', 8)
                ->val('digit', 'Cheque number')
                ->post('cash_paid_to')
                ->val('maxlength', 'Cash paid to', 100)
                ->post('mode')
                ->val('existin', 'Payment mode', array(0, 1, 2, 3, 4, 5))
                ->post('bank_name')
                ->val('maxlength', 'Bank name', 100)
                ->post('date')
                ->val('validateDate', 'Paid date');
    }

    function validateExpense($merchant_id) {
        $this->post('payment_status')
                ->val('inArray', 'Payment status', array('', 'paid', 'unpaid', 'refunded', 'cancelled'))
                ->post('payment_mode')
                ->val('inArray', 'Payment mode', array('', 'online', 'cash', 'neft', 'cheque'))
                ->post('expense_no')
                ->val('required', 'Expense No')
                ->post('category')
                ->val('required', 'Category')
                ->post('department')
                ->val('required', 'Department')
                ->post('vendor_id')
                ->val('required', 'Vendor ID')
                ->val('isValidVendorID', 'Vendor ID', $merchant_id)
                ->post('bill_date')
                ->val('validateDate', 'Bill date')
                ->post('due_date')
                ->val('validateDate', 'Due date')
                ->post('discount')
                ->val('isMoney', 'Amount')
                ->val('isamount', 'Amount')
                ->post('adjustment')
                ->val('isMoney', 'Adjustment')
                ->val('isamount', 'Adjustment')
                ->post('tds')
                ->val('existin', 'TDS', array(1, 5, 10));
    }

    function validateExpenseDetail() {
        $this->post('particular')
                ->val('required', 'Particular')
                ->post('unit')
                ->val('required', 'Unit')
                ->val('isMoney', 'Unit')
                ->val('isamount', 'Unit')
                ->post('rate')
                ->val('required', 'Rate')
                ->val('isMoney', 'Rate')
                ->val('isamount', 'Rate')
                ->post('gst')
                ->val('existin', 'GST', array('Non Taxable', 0, 5, 12, 18, 28));
    }

    function validatePayoutTransfer($merchant_id) {
        $this->post('beneficiary_id')
                ->val('required', 'Beneficiary ID')
                ->val('isValidBeneficiaryID', 'Beneficiary ID', $merchant_id)
                ->post('mode')
                ->val('inArray', 'Mode', array('banktransfer', 'upi'))
                ->post('amount')
                ->val('required', 'Amount')
                ->val('isMinAmount', 'Amount', 1)
                ->val('isMoney', 'Amount')
                ->val('isamount', 'Amount');
    }

    function validateSupplierUpdate() {

        $this->post('email1')
                ->val('required', 'Email ID1')
                ->val('maxlength', 'Email ID1', 254)
                ->post('email2')
                ->val('maxlength', 'Email ID2', 254)
                ->post('mob_country_code1')
                ->val('maxlength', 'Mobile country code', 6)
                ->post('mobile1')
                ->val('required', 'Mobile no')
                ->val('maxlength', 'Mobile no', 12)
                ->val('digit', 'Mobile no')
                ->post('mob_country_code2')
                ->val('maxlength', 'Landline country code', 6)
                ->post('mobile2')
                ->val('maxlength', 'Mobile2', 12)
                ->val('digit', 'Landline no')
                ->post('contact_person_name')
                ->val('required', 'Contact person name')
                ->val('maxlength', 'Contact person name', 100)
                ->post('contact_person_name2')
                ->val('maxlength', 'Contact person name2', 100)
                ->post('supplier_company_name')
                ->val('required', 'Supplier company name')
                ->val('maxlength', 'Supplier company name', 100)
                ->post('industry_type')
                ->val('required', 'Industry type')
                ->val('digit', 'Industry type')
                ->val('maxlength', 'Industry type', 4);
    }

    function validatePlanSave($merchant_id) {
        $this->post('category_name')
                ->val('required', 'Category name')
                ->val('maxlength', 'Category name', 100)
                ->post('v_plan_name')
                ->val('required', 'Plan name')
                ->val('isValidTemplatename', 'Plan name', $merchant_id)
                ->val('maxlength', 'Plan name', 100)
                ->post('v_speed')
                ->val('required', 'Speed')
                ->post('v_duration')
                ->val('required', 'Duration')
                ->val('digit', 'Duration')
                ->post('v_price')
                ->val('required', 'Price')
                ->val('isMoney', 'Price');
    }

    function validateTaxSave($merchant_id) {
        $this->post('tax_name')
                ->val('required', 'Tax name')
                ->val('isValidTemplatename', 'Tax name', $merchant_id)
                ->val('maxlength', 'Tax name', 100)
                ->post('percentage')
                ->post('tax_type')
                ->val('required', 'Tax type')
                ->val('isMoney', 'Percentage');
    }

    function validateTaxUpdate($merchant_id, $id) {

        $this->post('tax_name')
                ->val('required', 'Tax name')
                ->val('isValidUpdateUniquename', 'Tax name', $merchant_id, $id)
                ->post('price')
                ->val('isMoney', 'Percentage');
    }

    function validateProductSave($merchant_id) {
        $this->post('product_name')
                ->val('required', 'Product name')
                ->val('isValidTemplatename', 'Product name', $merchant_id)
                ->val('maxlength', 'Product name', 100)
                ->post('price')
                ->val('isMoney', 'Price');
    }

    function validateProductUpdate($merchant_id, $id) {

        $this->post('product_name')
                ->val('required', 'Product name')
                ->val('isValidUpdateUniquename', 'Product name', $merchant_id, $id)
                ->post('price')
                ->val('isMoney', 'Price');
    }

    function validateCoveringnoteSave($merchant_id) {

        $this->post('template_name')
                ->val('required', 'Template name')
                ->val('maxlength', 'Template name', 45)
                ->val('isValidTemplatename', 'Template name', $merchant_id)
                ->post('body')
                ->val('required', 'Mail Body')
                ->post('subject')
                ->val('required', 'Mail Subject')
                ->post('invoice_label')
                ->val('required', 'Invoice Label');
    }

    function validateCoveringnoteUpdate($merchant_id, $id) {

        $this->post('template_name')
                ->val('required', 'Template name')
                ->val('maxlength', 'Template name', 45)
                ->val('isValidUpdateUniquename', 'Template name', $merchant_id, $id)
                ->post('body')
                ->val('required', 'Mail Body')
                ->post('subject')
                ->val('required', 'Mail Subject')
                ->val('maxlength', 'Mail Subject', 100)
                ->post('invoice_label')
                ->val('required', 'Invoice Label')
                ->val('maxlength', 'Invoice Label', 20);
    }

}
