<?php

class FranchiseModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function createFranchise($pg_vendor_id, $merchant_id, $franchise_name, $email, $email2, $mobile1, $address, $mobile2, $contact_person_name, $contact_person_name2, $account_holder_name, $account_number, $bank_name, $account_type, $ifsc_code,$pan,$adhar,$gst, $status, $data, $user_id, $bulk_id = 0)
    {
        try {
            $commision_type = ($data['commision_type'] > 0) ? $data['commision_type'] : 0;
            $commision_percent = ($data['commision_percent'] > 0) ? $data['commision_percent'] : 0;
            $commision_amount = ($data['commision_amount'] > 0) ? $data['commision_amount'] : 0;
            $online_settlement = (isset($data['online_settlement'])) ? $data['online_settlement'] : 0;
            $settlement_type = ($data['settlement_type'] > 0) ? $data['settlement_type'] : 0;

            $sql = "INSERT INTO `franchise`(`pg_vendor_id`,`online_pg_settlement`,`settlement_type`,`commision_type`,`commission_percentage`,`commision_amount`,`merchant_id`,`franchise_name`,`contact_person_name`,`email_id`,`mobile`,`address`,`pan`,`adhar_card`,`gst_number`,`contact_person_name2`,
`email_id2`,`mobile2`,`bank_holder_name`,`bank_account_no`,`bank_name`,`ifsc_code`,`account_type`,`status`,`bulk_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(:pg_vendor_id,:online_pg_settlement,:settlement_type,:commision_type,:commission_percentage,:commision_amount,:merchant_id,:franchise_name,:contact_person_name,:email1,:mobile1,:address,:pan,:adhar_card,:gst_number,:contact_person_name2,:email2,:mobile2,:bank_holder_name, :bank_account_no,:bank_name ,:ifsc_code ,:account_type, :status,:bulk_id,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':pg_vendor_id' => $pg_vendor_id, ':online_pg_settlement' => $online_settlement, ':settlement_type' => $settlement_type,
                ':commision_type' => $commision_type, ':commission_percentage' => $commision_percent, ':commision_amount' => $commision_amount, ':merchant_id' => $merchant_id, ':franchise_name' => $franchise_name, ':contact_person_name' => $contact_person_name, ':email1' => $email, ':mobile1' => $mobile1, ':address' => $address,':pan' => $pan,':adhar_card' => $adhar,':gst_number' => $gst, ':contact_person_name2' => $contact_person_name2, ':email2' => $email2, ':mobile2' => $mobile2, ':bank_holder_name' => $account_holder_name, ':bank_account_no' => $account_number, ':bank_name' => $bank_name, ':ifsc_code' => $ifsc_code, ':account_type' => $account_type, ':status' => $status, ':bulk_id' => $bulk_id, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E295]Error while creating Franchise Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateFranchise($franchise_id, $pg_vendor_id, $data, $franchise_name, $email, $email2, $mobile1, $address, $mobile2, $contact_person_name, $contact_person_name2, $account_holder_name, $account_number, $bank_name, $account_type, $ifsc_code,$pan,$adhar,$gst, $user_id)
    {
        try {
            $commision_type = ($data['commision_type'] > 0) ? $data['commision_type'] : 0;
            $commision_percent = ($data['commision_percent'] > 0) ? $data['commision_percent'] : 0;
            $commision_amount = ($data['commision_amount'] > 0) ? $data['commision_amount'] : 0;
            $online_settlement = (isset($data['online_settlement'])) ? $data['online_settlement'] : 0;
            $settlement_type = ($data['settlement_type'] > 0) ? $data['settlement_type'] : 0;
            $sql = "UPDATE `franchise` SET `pg_vendor_id`=:pg_vendor_id,`franchise_name` = :franchise_name ,`contact_person_name` = :contact_person_name,`email_id` = :email1,`mobile` = :mobile1,`address` = :address,`contact_person_name2` =:contact_person_name2 ,`email_id2` = :email2,`mobile2` = :mobile2,`bank_holder_name` = :bank_holder_name,`bank_account_no` = :bank_account_no,`bank_name` =:bank_name,`ifsc_code` = :ifsc_code,pan=:pan,adhar_card=:adhar_card,gst_number=:gst_number,`account_type`=:account_type,"
                . "online_pg_settlement=:online_settlement,commision_type=:commision_type,commission_percentage=:commision_percent,commision_amount=:commision_amount,"
                . "settlement_type=:settlement_type,`last_update_by` =:user_id  WHERE `franchise_id` = :franchise_id ;";
            $params = array(
                ':franchise_id' => $franchise_id, ':pg_vendor_id' => $pg_vendor_id, ':franchise_name' => $franchise_name, ':contact_person_name' => $contact_person_name, ':email1' => $email, ':mobile1' => $mobile1, ':address' => $address, ':contact_person_name2' => $contact_person_name2, ':email2' => $email2, ':mobile2' => $mobile2, ':bank_holder_name' => $account_holder_name, ':bank_account_no' => $account_number, ':bank_name' => $bank_name, ':ifsc_code' => $ifsc_code,':pan' => $pan,':adhar_card' => $adhar,':gst_number' => $gst, ':account_type' => $account_type,
                ':online_settlement' => $online_settlement, ':commision_type' => $commision_type, ':commision_percent' => $commision_percent,
                ':commision_amount' => $commision_amount, ':settlement_type' => $settlement_type,
                ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E298]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateFranchiseCustomer($customer_id, $first_name, $last_name, $email, $mobile, $address, $user_id)
    {
        try {
            $sql = "UPDATE `customer` SET `first_name` = :first_name ,`last_name` = :last_name,`email` = :email,`mobile` = :mobile,`address` = :address,`last_update_by` =:user_id  WHERE `customer_id` = :customer_id ;";
            $params = array(':customer_id' => $customer_id, ':first_name' => $first_name, ':last_name' => $last_name, ':email' => $email, ':mobile' => $mobile, ':address' => $address, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E298]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }
    public function updateStagingFranchise($franchise_id, $franchise_name, $email, $email2, $mobile1, $address, $mobile2, $contact_person_name, $contact_person_name2, $account_holder_name, $account_number, $bank_name, $account_type, $ifsc_code, $user_id)
    {
        try {
            $sql = "UPDATE `staging_franchise` SET `franchise_name` = :franchise_name ,`contact_person_name` = :contact_person_name,`email_id` = :email1,`mobile` = :mobile1,`address` = :address,`contact_person_name2` =:contact_person_name2 ,`email_id2` = :email2,`mobile2` = :mobile2,`bank_holder_name` = :bank_holder_name,`bank_account_no` = :bank_account_no,`bank_name` =:bank_name,`ifsc_code` = :ifsc_code,`account_type`=:account_type,`last_update_by` =:user_id  WHERE `franchise_id` = :franchise_id ;";
            $params = array(':franchise_id' => $franchise_id, ':franchise_name' => $franchise_name, ':contact_person_name' => $contact_person_name, ':email1' => $email, ':mobile1' => $mobile1, ':address' => $address, ':contact_person_name2' => $contact_person_name2, ':email2' => $email2, ':mobile2' => $mobile2, ':bank_holder_name' => $account_holder_name, ':bank_account_no' => $account_number, ':bank_name' => $bank_name, ':ifsc_code' => $ifsc_code, ':account_type' => $account_type, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E298]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateSaleConfig($franchise_id, $franchise_code, $franchise_fee_comm, $franchise_fee_waiver,$non_brand_fee_comm , $non_brand_fee_waiver, $due_penalty, $min_penalty, $default_sale, $customer_id, $user_id)
    {
        try {
            $sql = "UPDATE `franchise` SET `franchise_code`=:franchise_code,`franchise_fee_commission`=:franchise_fee_comm,`franchise_fee_waiver` = :franchise_fee_waiver ,`franchise_net_commission` = :franchise_net_commission,`non_brand_fee_commission`=:non_brand_fee_comm,`non_brand_fee_waiver` = :non_brand_fee_waiver ,`non_brand_net_commission` = :non_brand_net_commission,`penalty_percentage` = :penalty_percentage,`penalty_min_amt` = :penalty_min_amt,`default_sale` = :default_sale,`customer_id` =:customer_id ,`last_update_by` =:user_id,enable_franchise_sale=1  WHERE `franchise_id` = :franchise_id ;";
            $params = array(
                ':franchise_id' => $franchise_id, ':franchise_code' => $franchise_code, ':franchise_fee_comm' => $franchise_fee_comm, ':franchise_fee_waiver' => $franchise_fee_waiver, ':franchise_net_commission' => $franchise_fee_comm - $franchise_fee_waiver,':non_brand_fee_comm' => $non_brand_fee_comm, ':non_brand_fee_waiver' => $non_brand_fee_waiver, ':non_brand_net_commission' => $non_brand_fee_comm - $non_brand_fee_waiver, ':penalty_percentage' => $due_penalty, ':penalty_min_amt' => $min_penalty, ':default_sale' => $default_sale, ':customer_id' => $customer_id,
                ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E298]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPendingSettlement($merchant_id)
    {
        $sql = "select s.id,settlement_amount,total_capture,total_tdr,total_service_tax,f.franchise_name,v.vendor_name,s.vendor_id,s.franchise_id from settlement_detail s left outer join franchise f on f.franchise_id=s.franchise_id left outer join vendor v on v.vendor_id=s.vendor_id where s.merchant_id=:merchant_id and s.status=-1 and s.type=2";
        $params = array(':merchant_id' => $merchant_id);
        $this->db->exec($sql, $params);
        $rows = $this->db->resultset();
        return $rows;
    }

    function insertBespokeSettlemet($merchant_id, $franchise_id, $vendor_id, $merchant_name, $bank_reff, $date, $requested_amount, $transfer_id, $narrative = '')
    {
        try {
            $sql = "INSERT INTO `settlement_detail`(`settlement_amount`,`requested_settlement_amount`,`total_capture`"
                . ",`total_tdr`,`total_service_tax`,`bank_reference`,`settlement_date`,`status`,`merchant_id`,"
                . "`franchise_id`,`vendor_id`,`cashfree_transfer_id`,`merchant_name`,`narrative`,`created_date`)"
                . "VALUES(0,:requested_amount,0,0,0,:bank_reference,:settlement_date,1,:merchant_id,:franchise_id,:vendor_id,:transfer_id,:merchant_name,:narrative,CURRENT_TIMESTAMP());";
            $params = array(
                ':bank_reference' => $bank_reff, ':settlement_date' => $date, ':requested_amount' => $requested_amount,
                ':merchant_id' => $merchant_id, ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id, ':transfer_id' => $transfer_id, ':merchant_name' => $merchant_name, ':narrative' => $narrative
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E5]Error while insertBespokeSettlemet. param :' . json_encode($params) . ' Error-' . $e->getMessage());
        }
    }
}
