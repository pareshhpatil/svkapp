<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class MybillsModel extends Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Fetch list of patron bills
     * 
     * @return type
     */
    public function getMybills($user_id, $type, $merchant_id = '') {
        try {
            $sql = "call get_mybills(:user_id,:type,:merchant_id);";
            $params = array(':user_id' => $user_id, ':type' => $type, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E601]Error while getting my bills Error: for user Input[' . $user_id . '] and type [' . $type . '] ' . $e->getMessage());
        }
    }

    public function getMyPlanDetails($merchant_id, $category, $source, $speed, $data, $duration) {
        try {
            if ($source == '' || $source == null) {
                $sql = "select price,tax1_percent,tax2_percent,plan_id from prepaid_plan where merchant_id=:merchant_id and category=:category and (source='' or source is null) and speed=:speed and data=:data and duration=:duration and is_active=1;";
                $params = array(':merchant_id' => $merchant_id, ':category' => $category, ':speed' => $speed, ':data' => $data, ':duration' => $duration);
            } else {
                $sql = "select price,tax1_percent,tax2_percent,plan_id from prepaid_plan where merchant_id=:merchant_id and category=:category and source=:source and speed=:speed and data=:data and duration=:duration and is_active=1;";
                $params = array(':merchant_id' => $merchant_id, ':category' => $category, ':source' => $source, ':speed' => $speed, ':data' => $data, ':duration' => $duration);
            }

            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E605]Error while getMyPlanDetails Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

}
