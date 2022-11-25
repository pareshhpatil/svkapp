<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class FormModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function saveFormbuilderTransaction($id, $enable_invoice, $merchant_id, $amount, $json) {
        try {
            $sql = "INSERT INTO `form_builder_transaction`(`request_id`,`merchant_id`,`invoice_enable`,`amount`,`json`,`created_by`,`created_date`,`last_update_by`)"
                    . "VALUES(:id,:merchant_id,:invoice_enable,:amount,:json,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(':id' => $id, ':merchant_id' => $merchant_id, ':invoice_enable' => $enable_invoice, ':amount' => $amount, ':json' => $json);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E161]Error while save form_builder_transaction Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getRequestDetail($req_id) {
        try {
            $sql = "select c.customer_code,r.customer_id,r.invoice_number,r.grand_total,c.first_name,c.last_name, c.address,c.city,c.zipcode from payment_request r inner join customer c on c.customer_id=r.customer_id where r.payment_request_id=:req_id";
            $params = array(':req_id' => $req_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            print "Stored procedure execution failed";
            throw new Exception("Stored procedure execution failed " . $ex->getMessage());
        }
    }

    public function updateForm($json, $zip_size, $zip_path, $id) {
        try {
            $sql = "update form_builder_transaction set json=:json,zip_size=:zip_size,zip_file_path=:zip_file_path where id=:id";
            $params = array(':json' => $json, ':zip_size' => $zip_size, ':zip_file_path' => $zip_path, ':id' => $id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E150]Error while fetching bank list Error:  for payment transaction id[' . $payment_transaction_id . ']' . $e->getMessage());
        }
    }

}
