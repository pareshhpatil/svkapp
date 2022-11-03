<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class TicketModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function insertUserCookie($user_id, $merchant_id, $cookie) {
        try {
            $sql = "INSERT INTO `user_cookie`(`user_id`,`merchant_id`,`cookie`,`cookie_expire`,`created_date`)"
                    . "VALUES(:user_id,:merchant_id,:cookie,DATE_ADD(now(), INTERVAL 15 MINUTE),CURRENT_TIMESTAMP());";
            $params = array(':user_id' => $user_id, ':merchant_id' => $merchant_id, ':cookie' => $cookie);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[T001]Error while insert User Cookie Error: ' . $e->getMessage());
        }
    }

    public function updateUserCookie($user_id, $cookie) {
        try {
            $sql = "update user_cookie set cookie=:cookie,cookie_expire=DATE_ADD(now(), INTERVAL 15 MINUTE) "
                    . "where user_id=:user_id;";
            $params = array(':user_id' => $user_id, ':cookie' => $cookie);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[T002]Error while update User Cookie Error: ' . $e->getMessage());
        }
    }

    public function existExistCookie($user_id, $merchant_id) {
        try {
            $sql = "select * from user_cookie where user_id=:user_id and merchant_id=:merchant_id";
            $params = array(':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return false;
            } else {
                return $row;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[T002]Error while update User Cookie Error: ' . $e->getMessage());
        }
    }

}
