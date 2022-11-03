<?php

/**
 * Register patron model works on tables required for Patron registration
 *
 * @author Shuhaid
 */
class RegisterModel extends Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Method saves a new patron to the database
     * 
     * @param type $email
     * @param type $fname
     * @param type $lname
     * @param type $mobile
     * @param type $landline
     * @param type $password
     * @param type $dob
     * @param type $address
     * @param type $city
     * @param type $state
     * @param type $zip
     * @return string
     */
    public function createPatron($email, $fname, $lname, $mobile_code, $mobile, $password, $payment_request_id) {
        try {
            $fname = trim($fname);
            $lname = trim($lname);
            $sql = "call `patron_register`('','',:fname,:lname,:email,:mobile_code,:mobile,:password,0,:payment_request_id,:login_type);";
            $params = array(':email' => trim($email), ':fname' => ucfirst($fname), ':lname' => ucfirst($lname), ':mobile_code' => $mobile_code,
                ':mobile' => $mobile, ':password' => $password, ':payment_request_id' => $payment_request_id, ':login_type' => 1);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E165]Error while creating patron Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Method is used to sending email
     * 
     * @param type $str
     */
    public function sendMail($concatStr_, $toEmail_, $type = 'patron') {
        try {
            $converter = new Encryption;
            $encoded = $converter->encode($concatStr_);
            $baseurl = $this->host . '://' . $_SERVER['SERVER_NAME'];
            $verifyemailurl = $baseurl . '/' . $type . '/register/verifyemail/' . $encoded . '';

            $emailWrapper = new EmailWrapper();
            $mailcontents = $emailWrapper->fetchMailBody("user.verifyemail");

            if (isset($mailcontents[0]) && isset($mailcontents[1])) {
                $message = $mailcontents[0];
                $message = str_replace('__EMAILID__', $toEmail_, $message);
                $message = str_replace('__LINK__', $verifyemailurl, $message);
                $message = str_replace('__BASEURL__', $baseurl, $message);

                #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
                $emailWrapper->sendMail($toEmail_, "", $mailcontents[1], $message);
            } else {
                SwipezLogger::warn("Mail could not be sent with verify email link to : " . $toEmail_);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E166]Error while sending mail Error: email link to : ' . $toEmail_ . $e->getMessage());
        }
    }

    /**
     * Method is used to fetch concatenated userid and created_date from DB 
     * 
     * @param type $userid
     * @return userid in string format 
     */
    public function getUserTimeStamp($userid) {
        try {
            $sql = "select  CONCAT(user_id, created_date) as username from user where user_id=:user_id";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['username'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E167]Error while getting user timespam Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    /**
     * Checks patrons user id and created date against the link clicked by the user
     * 
     * @param type $url
     * @return userid in string format
     */
    public function validateEmailVerificationLink($link) {
        try {
            $converter = new Encryption;
            $url = $converter->decode($link);
            $user = substr($url, 0, 10);
            $credate = substr($url, 10, 19);
            $returnurl = substr($url, 29);
            $sql = "SELECT `user_id` FROM `user` where user_id=:user_id and created_date=:credate and user_status=1";
            $params = array(':user_id' => $user, ':credate' => $credate);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($returnurl != '') {
                $row['returnurl'] = $returnurl;
            }
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E168]Error while verifying email id Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * method is used to update user status 
     * 
     * @param type $status int
     * @param type $username string
     */
    public function updatePatronUserStatus($status, $username) {
        try {
            $sql = 'UPDATE `user` SET `user_status`=:status,last_updated_by=`user_id`'
                    . ', last_updated_date=CURRENT_TIMESTAMP() WHERE user_id=:user_id';
            $params = array(':status' => $status, ':user_id' => $username);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169]Error while updating patron user status Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function emailVerify($email) {
        try {
            $sql = "update customer_comm_detail set is_verified=1 where value=:email and type=1";
            $params = array(':email' => $email);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-1]Error while updating email verify user status Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateCustomer_userid($email, $user_id) {
        try {
            $sql = "select distinct customer_id from customer where email=:email";
            $params = array(':email' => $email);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            $this->update_userid($rows, $user_id);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-2]Error while updating customer user id Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function update_userid($rows, $user_id) {
        try {
            foreach ($rows as $row) {
                $sql = "update customer set user_id=:user_id,customer_status=2 where customer_id =:customer_id";
                $params = array(':user_id' => $user_id, ':customer_id' => $row['customer_id']);
                $this->db->exec($sql, $params);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-2]Error while updating customer user id Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateCustomer_payment($user_id) {
        try {
            $sql = "update payment_request set patron_type=1 where customer_id in (select distinct customer_id from customer where user_id=:user_id)";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-2]Error while updating customer user id Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updatePending_change($user_id) {
        try {
            $sql = "select pending_change_id,change_id from pending_change where source_id=:user_id and source_type=1 and status=0";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (!empty($row)) {
                $this->updatePending_changestatus($row['pending_change_id']);
                $this->updateData_changestatus($row['change_id']);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-4]Error while updating customer user id Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updatePending_changestatus($pending_change_id) {
        try {
            $sql = "update pending_change set status=1 where pending_change_id=:pending_change_id";
            $params = array(':pending_change_id' => $pending_change_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-7]Error while updating customer user id Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateCustomerStatus($user_id, $status) {
        try {
            $sql = "update customer set customer_status=:status,last_update_by=:user_id where user_id=:user_id";
            $params = array(':status' => $status, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-5]Error while updating customer status: ' . $status . ' user id: ' . $user_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateData_changestatus($change_id) {
        try {
            $sql = "update customer_data_change set status=1 where change_id=:change_id";
            $params = array(':change_id' => $change_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-6]Error while updating customer user id Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getUserList($user_id) {
        try {
            $sql = "select email_id,user.user_id,first_name,last_name from user where user.user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E131]Error while fetching user list data Error:  for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function emailAlreadyExists($emailId_, $user_id = null) {
        try {
            $sql = "SELECT `user_id` FROM `user` where email_id=:email";
            $params = array(':email' => $emailId_);

            $this->db->exec($sql, $params);
            $row = $this->db->single();

            if (isset($row['user_id'])) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E170]Error while checking email exist Error: for email id [' . $emailId_ . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
