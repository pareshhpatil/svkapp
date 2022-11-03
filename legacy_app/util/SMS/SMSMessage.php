<?php

/*
 * Stores list of SMS messages. This is hard coded in this class as a hash array
 * at the moment. This should eventually replaced by a caching solution.
 */

/**
 * Description of SMSMessage
 *
 * @author Shuhaid
 */
class SMSMessage {

    private $_messageList = array();

    function __construct() {
        $this->_messageList["m1"] = "Thank you for registering on Swipez. Please check your email Inbox OR Spam folder to verify your email address and complete the registration process.";
        $this->_messageList["m2"] = "We have received your papers and are processing them at our end. Please allow x business days to enable your account for online payment collections. Swipez";
        $this->_messageList["m3"] = "Thank you for subscribing to the paid version of Swipez. Login to www.swipez.in to start collecting payments online.";
        $this->_messageList["m4"] = "Your payment attempt to Swipez has failed due to <reason text>. Please re-try this again on www.swipez.in.";
        $this->_messageList["m5"] = "Your Swipez dashboard is now enabled for online payments. Please login to www.swipez.in to start collecting payments online.";
        $this->_messageList["m6"] = "Payment request has been sent to <Patron Name>. You will receive an SMS and E-Mail once the patron has settled this payment. Swipez";
        $this->_messageList["m7"] = "You have received xxxx amount from <Patron Name>. Amount will be credited your account in 2-3 business days, subject to clearing. Swipez";
        $this->_messageList["m8"] = "You have requested for a password reset on www.swipez.in. Please check your registered email id to reset password.";
        $this->_messageList["m9"] = "You have successfully changed your password on Swipez. Please contact support@swipez.in in case of any queries.";
        $this->_messageList["m10"] = "<COUNT> <UNIT> were booked by <Patron name> for <event title>. <URL> Swipez";
        $this->_messageList["m11"] = "<COMPANY_NAME> has added you as their vendor. Please click this link to verify your account <URL>";
        $this->_messageList["p1"] = "Thank you for registering on Swipez. Please check your email Inbox OR Spam folder to verify your email address and complete the registration process.";
        //$this->_messageList["p2"] = "You have received an invoice from <Merchant Name> for xxxx. Click <short-url> to pay and win BookMyShow voucher worth Rs.500.";
        $this->_messageList["p2"] = "Your latest invoice by <Merchant Name> for xxxx is ready. Click here to view your invoice and make the payment online - <short-url> Swipez";
        $this->_messageList["p3"] = "You have made a payment to <Merchant Name> for an amount of xxxx/-. Receipt link <URL>. Swipez";
        $this->_messageList["p4"] = "Payment to <Merchant Name> for an amount of xxxx has failed. Retry this payment using this link <URL>";

        $this->_messageList["p5"] = "You have requested for a password reset on www.swipez.in. Please check your registered email id to reset your password.";
        $this->_messageList["p6"] = "You have successfully changed your password on Swipez. Please contact support@swipez.in in case of any queries.";
        $this->_messageList["p7"] = "Your online payment to <Merchant name> was not completed. You can re-try your transaction using <URL>";
        $this->_messageList["p8"] = "Your <Merchant name> bill is<Due text>. Pay now via <URL> to <Late fee> enjoy uninterrupted services. Swipez";

        $this->_messageList["p9"] = "Your One-Time Password (OTP) is <OTP> to login into Swipez";
        $this->_messageList["p10"] = "You have booked <COUNT> <UNIT> for <event title>. Booking ID <Transaction id>. Amount received <AMOUNT>/-. <URL>";
        $this->_messageList["p11"] = "You have booked <COUNT> <UNIT> for <event title>. Booking ID <Transaction id>. <URL>";
        $this->_messageList["p12"] = "Refund initiated by <merchant name> for your payment of amount Rs. <amount>. It would take 7-10 working days for amount to reflect back into your bank.";
    }

    function fetch($key_) {
        $value = isset($this->_messageList[$key_]) ? $this->_messageList[$key_] : NULL;

        return $value;
    }

}
