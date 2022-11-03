<?php

/**
 * Login model works for patron and merchant login
 *
 * @author Paresh
 */
class LoginModel extends Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Queries database and fetches user id and status from user table
     * 
     * @param type $email
     * @param type $password
     * @param type $usertype
     * @return type associate array of user_id and user_status values if fetched from DB
     * 
     */
    public function queryLoginInfo($email, $password) {
        try {
            if (isset($_POST['login_token'])) {
                $password = $this->getLoginToken($email);
            } else {
                if (preg_match("/^(\+[\d]{1,5}|0)?[1-9]\d{9}$/", $email)) {
                    if (strlen($email) == 10) {
                        $email = '+91' . $email;
                    } else {
                        if (substr($email, 0, 1) == '+') {
                            
                        } else {
                            $email = '+' . $email;
                        }
                    }
                }
                $rows = $this->getUserLogin($email);

                foreach ($rows as $row) {
                    if ($row['login_type'] == 2) {
                        $email = $row['mobile'];
                    }
                    if (password_verify($password, $row['password'])) {
                        $password = $row['password'];
                        break;
                    }
                }
            }

            $sql = "call loginCheck(:email_id,:password)";
            $params = array(':email_id' => $email, ':password' => $password);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            return $data;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E175]Error while checking login Error:  for email [' . $email . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getUserLogin($email, $group_id = 0) {
        try {
            $sql = "select u.email_id,u.login_type,concat(mob_country_code,u.mobile_no) as mobile,u.password from user u where (u.email_id = :email_id or concat(u.mob_country_code,u.mobile_no)=:email_id) and `user_status` IN (2 , 12, 13, 14, 15, 16, 20, 11, 1, 19) ";
            if ($group_id != 0) {
                $sql .= " and u.group_id='" . $group_id . "'";
            }
            $params = array(':email_id' => $email);
            $this->db->exec($sql, $params);
            $data = $this->db->resultset();
            return $data;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E176]Error while get Master Login Error:  for group_id [' . $group_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getMasterLogin($group_id) {
        try {
            $sql = "select u.email_id,m.company_name as display_name,ml.user_id,ml.merchant_id from master_login ml inner join user u on u.user_id=ml.user_id inner join merchant m on m.merchant_id=ml.merchant_id where ml.group_id=:group_id";
            $params = array(':group_id' => $group_id);
            $this->db->exec($sql, $params);
            $data = $this->db->resultset();
            return $data;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E177]Error while get Master Login Error:  for group_id [' . $group_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getLoginToken($email) {
        try {
            $sql = "select * from login_token where email=:email and status=0";
            $params = array(':email' => $email);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                $sql = "select password from user where user_id=:user_id";
                $params = array(':user_id' => $data['user_id']);
                $this->db->exec($sql, $params);
                $data2 = $this->db->single();
                $sql = "update login_token set status=1 where id=:id";
                $params = array(':id' => $data['id']);
                $this->db->exec($sql, $params);
            }
            return $data2['password'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E177]Error while get Master Login Error:  for group_id [' . $group_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function queryGroupLoginInfo($email, $password, $group_id) {
        try {
            $rows = $this->getUserLogin($email, $group_id);
            foreach ($rows as $row) {
                if (password_verify($password, $row['password'])) {
                    $password = $row['password'];
                }
            }
            $sql = "call GrouploginCheck(:email_id,:password,:group_id)";
            $params = array(':email_id' => $email, ':password' => $password, ':group_id' => $group_id);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E178]Error while checking Group login Error:  for email [' . $email . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function forgotPasswordRequest($email, $group_id = '') {
        try {
            $sql = "call forgotPassword(:email_id,:group_id);";
            $params = array(':email_id' => $email, ':group_id' => $group_id);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            return $data;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E179]Error while forgot password request Error: for email [' . $email . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function isValidforgotpasswordlink($link) {
        try {
            $converter = new Encryption;
            $link = $converter->decode($link);
            $sql = "SELECT email_id,user_id FROM forgot_password where concat(last_update_date,'',email_id)=:link";
            $params = array(':link' => $link);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                $link = $converter->encode($data['user_id']);
            }
            return !empty($data) ? $link : 'failed';
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E180]Error while validating forgot password link Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Method is used to sending email
     * 
     * @param type $str
     */
    public function sendMail($concatStr_, $toEmail_) {
        try {
            $converter = new Encryption;
            $encoded = $converter->encode($concatStr_);
            $baseurl = $this->host . '://' . $_SERVER['SERVER_NAME'];
            $forgotpasswdurl = $baseurl . '/login/forgotpassword/' . $encoded . '';

            $emailWrapper = new EmailWrapper();
            $mailcontents = $emailWrapper->fetchMailBody("user.forgotpassword");

            if (isset($mailcontents[0]) && isset($mailcontents[1])) {
                $message = $mailcontents[0];
                $message = str_replace('__EMAILID__', $toEmail_, $message);
                $message = str_replace('__LINK__', $forgotpasswdurl, $message);
                $message = str_replace('__BASEURL__', $baseurl, $message);

                #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
                $emailWrapper->sendMail($toEmail_, "", $mailcontents[1], $message);
            } else {
                SwipezLogger::warn("Mail could not be sent with forgot password link to : " . $toEmail_);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E178]Error while sending mail Error: for email [' . $toEmail_ . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function resetPassword($password, $user_id) {
        try {
            if (strlen($user_id) == 10 && substr($user_id, 0, 1) == 'U') {
                $sql = "update user set password=:password , last_updated_by=:user_id where user_id=:user_id";
                $params = array(':password' => $password, ':user_id' => $user_id);
                $this->db->exec($sql, $params);

                $sql = "update forgot_password set is_active=0 , last_update_date=CURRENT_TIMESTAMP() where user_id=:user_id";
                $params = array(':user_id' => $user_id);
                $this->db->exec($sql, $params);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E179]Error while resetting password Error: for email [' . $email_id . ']' . $e->getMessage());
            $this->setGenericError();
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
            
SwipezLogger::error(__CLASS__, '[E180]Error while getting user timespam Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function saveLoginToken($user_id, $email) {
        try {
            $token = md5(uniqid(rand(), true));
            $sql = "INSERT INTO `login_token`(`email`,`user_id`,`token`,`status`,`created_by`,`created_date`)"
                    . "VALUES(:email,:user_id,:token,0,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':user_id' => $user_id, ':email' => $email, ':token' => $token);
            $this->db->exec($sql, $params);
            return $token;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E180]Error while getting user timespam Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

}
