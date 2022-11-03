<?php

class LoyaltyModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getCustomerPoints($customer_id) {
        try {
            $sql = "select sum(balance_points) as points from customer_loyalty_points where customer_id=:customer_id and type=1 and is_active=1";
            $params = array(':customer_id' => $customer_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['points'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E29496]Error while fetching Category  list Error: ' . $e->getMessage());
        }
    }

    function getPointList($merchant_id, $from_date, $to_date, $type) {
        try {
            $sql = "select s.id,s.created_date,concat(c.first_name,' ',c.last_name) as customer_name,c.customer_code,s.amount,s.points,s.narrative,s.status from customer_loyalty_points s"
                    . " inner join customer c on c.customer_id=s.customer_id where  DATE_FORMAT(s.created_date,'%Y-%m-%d') >= :from_date and DATE_FORMAT(s.created_date,'%Y-%m-%d') <= :to_date and s.merchant_id=:merchant_id and s.is_active=1 and s.type=:type";

            $params = array(':merchant_id' => $merchant_id, ':from_date' => $from_date, ':to_date' => $to_date, ':type' => $type);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E145]Error while delete sms Template Error: for bulk id [' . $id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function savePoints($merchant_id, $user_id, $customer_id, $type, $amount, $points, $balance_point, $status, $narrative, $created_by) {
        try {
            $sql = "INSERT INTO `customer_loyalty_points`(`customer_id`,`user_id`,`merchant_id`,`type`,`amount`,`points`,`balance_points`,`status`"
                    . ",`narrative`,`created_by`,`created_date`,`updated_by`)"
                    . "VALUES(:customer_id,:user_id,:merchant_id,:type,:amount,:points,:balance_points,:status,:narrative,:created_by,CURRENT_TIMESTAMP(),:created_by);";
            $params = array(':merchant_id' => $merchant_id, ':user_id' => $user_id, ':customer_id' => $customer_id, ':type' => $type,
                ':amount' => $amount, ':points' => $points, ':balance_points' => $balance_point, ':status' => $status, ':narrative' => $narrative, ':created_by' => $created_by);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updatePoints($id, $balance_point, $status, $created_by) {
        try {
            $sql = "update customer_loyalty_points set balance_points=:points,status=:status ,updated_by=:created_by where id=:id";
            $params = array(':id' => $id, ':points' => $balance_point, ':status' => $status, ':created_by' => $created_by);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updatesettings($merchant_id, $nomenclature, $earning_logic_rs, $earning_logic_points, $redeem_logic_points, $redeem_logic_rs, $threshold, $expiry) {
        try {
            $sql = "update merchant_loyalty_setting set points_nomenclature=:nomenclature,earning_logic_rs=:earning_logic_rs,earning_logic_points=:earning_logic_points,redeeming_logic_points=:redeeming_logic_points ,redeeming_logic_rs=:redeeming_logic_rs ,redemption_threshold=:redemption_threshold ,expiry=:expiry where merchant_id=:id";
            $params = array(':id' => $merchant_id, ':nomenclature' => $nomenclature, ':earning_logic_points' => $earning_logic_points, ':earning_logic_rs' => $earning_logic_rs, ':redeeming_logic_points' => $redeem_logic_points, ':redeeming_logic_rs' => $redeem_logic_rs, ':redemption_threshold' => $threshold, ':expiry' => $expiry);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }
    
    public function savesettings($merchant_id, $nomenclature, $earning_logic_rs, $earning_logic_points, $redeem_logic_points, $redeem_logic_rs, $threshold, $expiry) {
        try {
            $sql = "INSERT INTO`merchant_loyalty_setting`(`merchant_id`,`title`,`description`,`points_nomenclature`,`earning_logic_rs`,`earning_logic_points`,`redeeming_logic_points`,`redeeming_logic_rs`,`redemption_threshold`,`expiry`,`created_date`)VALUES(:id,'Setup your loyalty points program','Run your own customized loyalty program. Allow your customers to earn points on every purchase and redeem them while making payments to you.',:nomenclature,:earning_logic_points,:earning_logic_rs,:redeeming_logic_points,:redeeming_logic_rs,:redemption_threshold,:expiry,CURRENT_TIMESTAMP());";
            $params = array(':id' => $merchant_id, ':nomenclature' => $nomenclature, ':earning_logic_points' => $earning_logic_points, ':earning_logic_rs' => $earning_logic_rs, ':redeeming_logic_points' => $redeem_logic_points, ':redeeming_logic_rs' => $redeem_logic_rs, ':redemption_threshold' => $threshold, ':expiry' => $expiry);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}

?>
