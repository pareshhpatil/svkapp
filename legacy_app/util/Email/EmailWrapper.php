<?php

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Wrapper class around PHP Mailer library
 *
 * @author Shuhaid
 */
class EmailWrapper {

    private $_cre1 = NULL;
    private $_cre2 = NULL;
    public $from_name_ = '';
    public $merchant_id = '';
    public $from_email_ = '';
    public $merchant_email_ = '';
    public $is_log = '';
    public $attachment = NULL;
    public $cc = NULL;
    public $bcc = NULL;

    function __construct() {
        $this->_cre1 = SESU;
        $this->_cre2 = SESP;
        $this->from_email_ = SENDER_EMAIL;
        $this->from_name_ = MAILER_SENDER_NAME;
    }

    function sendMail($toEmail_, $toName_, $subject_, $messageHTML_ = NULL, $messageText_ = NULL, $replyTo_ = NULL) {
        try {
            if ($toEmail_ == '') {
                
SwipezLogger::error(__CLASS__, 'Empty email request from Class: ' . debug_backtrace()[1]['class'] . ' Function: ' . debug_backtrace()[1]['function']);
                return;
            }
            $result = $this->ignoreEmail($toEmail_);
            if ($result == TRUE) {
                return;
            }

            $mail = new PHPMailer;
            #$mail->SMTPDebug = 3;                                // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'email-smtp.us-east-1.amazonaws.com';   // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $this->_cre1;                       // SMTP username
            $mail->Password = $this->_cre2;                       // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            $mail->Sender = SENDER_EMAIL;
            $mail->From = $this->from_email_;
            $mail->FromName = $this->from_name_;
            $mail->addAddress($toEmail_, $toName_);     // Adds a recipient
            //$mail->SetFrom($this->merchant_email_, $this->from_name_);
            $mail->Subject = $subject_;
            if ($this->attachment != NULL) {
                $mail->addAttachment($this->attachment);
            }
            if ($this->cc != NULL) {
                foreach ($this->cc as $cc_email) {
                    $mail->AddCC($cc_email);
                }
            }
            if ($this->bcc != NULL) {
                foreach ($this->bcc as $bcc_email) {
                    $mail->AddBCC($bcc_email);
                }
            }

            if (isset($messageHTML_)) {
                // Set email format to HTML
                $mail->Body = $messageHTML_;
                $mail->isHTML(true);
            }

            if (isset($messageText_)) {
                $mail->AltBody = $messageText_;
            }

            if (isset($replyTo_)) {
                $mail->addReplyTo($replyTo_);
            } else {
                //$mail->addReplyTo($this->from_email_, 'Swipez Support');
                if ($this->merchant_email_ <> $this->from_email_ && $this->merchant_email_ != '') {
                    $mail->addReplyTo($this->merchant_email_, $this->from_name_);
                }
            }

            if (!$mail->send()) {
                if ($this->is_log != 'off') {
                    
SwipezLogger::error(get_class($this), 'Email could not be sent to ' . $toEmail_ . ' ' . $mail->ErrorInfo);
                }
            } else {
                if ($this->is_log != 'off') {
                    SwipezLogger::info(get_class($this), 'Email sent to ' . $toEmail_);
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(get_class($this), 'Exception raised while sending email ' . $toEmail_ . ' ' . $e->getMessage());
        }
    }

    function fetchMailBody($key_) {
        $emailMessage = new EmailMessage();

        try {
            $mailcontents = $emailMessage->fetch($key_);
            if (!isset($mailcontents)) {
                SwipezLogger::warn("File contents found to be NULL for mail key : " . $key_);
                return NULL;
            }
            return $mailcontents;
        } catch (Exception $e_) {
            SwipezLogger::warn("Error occured while fetching mail file contents for " . $key_ . " " . $e_->getMessage());
        }
    }

    function ignoreEmail($email) {
        if (substr($email, -10) == '@swipez.in') {
            if (strpos($email, 'emailunavailable') !== false) {
                SwipezLogger::info(__CLASS__, 'Ignore Email exist: ' . $email);
                return TRUE;
            }
        }
        $model = new Model();
        $unsubEmail = $model->isUnsubscribe('email', $email, $this->merchant_id);
        if ($unsubEmail == TRUE) {
            SwipezLogger::info(__CLASS__, 'Unsubscribe Email exist: ' . $email);
            return TRUE;
        }
    }

}
