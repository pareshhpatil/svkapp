<?php

/**
 * Stores list of email messages. This is hard coded in this class as a hash array
 * at the moment. This should eventually replaced by a caching solution.
 *
 * @author Shuhaid
 */
class EmailMessage {

    private $_messageList = array();

    function __construct() {
        $this->_messageList["user.verifyemail"] = array(VIEW . 'mailer/email_verification.php', 'Please verify your email address with Swipez');
        $this->_messageList["user.forgotpassword"] = array(VIEW . 'mailer/forgot_password.php', 'Reset your Swipez password');
        $this->_messageList["user.passwordreset"] = array(VIEW . 'mailer/password_reset.php', 'Your Swipez password has been reset');
        $this->_messageList["patron.paymentrequest"] = array(VIEW . 'mailer/email_paymentrequest.php', 'Payment request from __COMPANY_NAME__');
        $this->_messageList["patron.failed_payment"] = array(VIEW . 'mailer/failed_payment.php', 'Your online payment to  __COMPANY_NAME__ was not completed.');
        $this->_messageList["patron.simpleInvoice"] = array(VIEW . 'mailer/simple_invoice.html', 'Payment request from __COMPANY_NAME__');
        $this->_messageList["patron.societyInvoice"] = array(VIEW . 'mailer/society_invoice.html', 'Payment request from __COMPANY_NAME__');
        $this->_messageList["patron.hotelInvoice"] = array(VIEW . 'mailer/hotel_invoice.html', 'Payment request from __COMPANY_NAME__');
        $this->_messageList["patron.ispInvoice"] = array(VIEW . 'mailer/isp_invoice.html', 'Payment request from __COMPANY_NAME__');
        $this->_messageList["patron.schoolInvoice"] = array(VIEW . 'mailer/school_invoice.html', 'Payment request from __COMPANY_NAME__');
        $this->_messageList["merchant.welcomemail"] = array(VIEW . 'mailer/welcome_merchant.php', 'Welcome to Swipez');
        $this->_messageList["merchant.package_receipt"] = array(VIEW . 'mailer/package_receipt.php', 'Payment receipt from Swipez');
        $this->_messageList["patron.payment_receipt"] = array(VIEW . 'mailer/payment_receipt.php', 'Payment receipt from ');
        $this->_messageList["patron.event_receipt"] = array(VIEW . 'mailer/event_receipt.html', 'Payment receipt from ');

        $this->_messageList["merchant.package_expired"] = array(VIEW . 'mailer/package_expired.html', 'Package has been expired');
        $this->_messageList["merchant.welcome"] = array(VIEW . 'mailer/welcome.html', 'Welcome to Swipez');
        $this->_messageList["merchant.download_file"] = array(VIEW . 'mailer/form-builder-file-download.html', 'Your Swipez file export is ready for download');
        $this->_messageList["merchant.license"] = array(VIEW . 'mailer/license.html', 'License Purchased');
        $this->_messageList["patron.refund"] = array(VIEW . 'mailer/refund.html', 'Refund initiated by __COMPANY_NAME__');
    }

    function fetch($key_) {

        $value = isset($this->_messageList[$key_]) ? $this->_messageList[$key_] : NULL;

        if (!isset($value[0]) || $value[0] == NULL) {
            SwipezLogger::warn(__CLASS__,"Requested key $key_ not found in email message hash");
            throw new Exception("Requested key $key_ not found in email message hash");
        }
        if (!isset($value[1]) || $value[1] == NULL) {
            SwipezLogger::warn(__CLASS__,"Requested key $key_ not found in email message hash");
            throw new Exception("Requested key $key_ not found in email message hash");
        }

        $filecontents = @file_get_contents($value[0]);

        if ($filecontents == FALSE) {
            SwipezLogger::warn(__CLASS__,"Unable to read email template file : $filename");
            return NULL;
        }
        $mailcontents[0] = $filecontents;
        $mailcontents[1] = $value[1];

        return $mailcontents;
    }

}
