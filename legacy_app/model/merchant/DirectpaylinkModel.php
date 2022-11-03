<?php

class DirectpaylinkModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function saveDirectPayRequest($merchant_id, $data, $user_id) {
        try {
            $data['amount'] = ($data['amount'] > 0) ? $data['amount'] : 0;
            $sql = "INSERT INTO `direct_pay_request`(`merchant_id`,`amount`,`narrative`,`name`,`email`,`mobile`,`country`,`customer_code`,currency,"
                    . "`created_by`,`created_date`,`last_update_by`)"
                    . "VALUES(:merchant_id,:amount,:purpose,:name,:email,:mobile,:country,:customer_code,:currency,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':merchant_id' => $merchant_id, ':name' => $data['name'], ':email' => $data['email'], ':mobile' => $data['mobile'], ':country' => $data['country'],
                ':amount' => $data['amount'], ':purpose' => $data['purpose'], ':customer_code' => $data['customer_code'],':currency' => $data['currency'], ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating Franchise Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}

?>
