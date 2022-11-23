<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class ProfileModel extends Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Fetch list of merchant associated with a user id
     * 
     * @return type
     */
    public function isExistPassword($password, $user_id) {
        try {
            $sql = "select password from user where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (password_verify($password, $row['password'])) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E188]Error while checking password exist Error: for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function resetPassword($password, $user_id) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "update user set password=:password , last_updated_by=:user_id where user_id=:user_id";
            $params = array(':password' => $password_hash, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $sql = "select email_id,mobile_no from user where user_id=:user_id ";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E189]Error while reset password Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Method is used to sending email
     * 
     * @param type $str
     */
    public function sendMail($toEmail_, $toName_) {
        try {
            $baseurl = $this->host . '://' . $_SERVER['SERVER_NAME'];
            $forgoturl = $baseurl . '/login/forgot/';
            $emailWrapper = new EmailWrapper();
            $mailcontents = $emailWrapper->fetchMailBody("user.passwordreset");

            if (isset($mailcontents[0]) && isset($mailcontents[1])) {
                $message = $mailcontents[0];
                $message = str_replace('__EMAILID__', $toEmail_, $message);
                $message = str_replace('__NAME__', $toName_, $message);
                $message = str_replace('__LINK__', $forgoturl, $message);
                $message = str_replace('__BASEURL__', $baseurl, $message);

                #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
                $emailWrapper->sendMail($toEmail_, $toName_, $mailcontents[1], $message);
            } else {
                SwipezLogger::warn("Mail could not be sent for password reset confirmation to : " . $toEmail_);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E190]Error while sending mail Error: ' . $e->getMessage());
        }
    }

    public function getUserTimeStamp($userid) {
        try {
            $sql = "select  CONCAT(user_id, last_updated_date) as username from user where user_id=:user_id";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['username'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E191]Error while getting user timespam Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function updatepreferences($user_id, $sms, $email) {
        try {
            $sql = "update preferences set send_sms=:sms,send_email=:email where user_id=:user_id";
            $params = array(':user_id' => $user_id, ':sms' => $sms, ':email' => $email);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E192]Error while updating preferences Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

}
