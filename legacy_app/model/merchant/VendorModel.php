<?php

class VendorModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function createVendor($pg_vendor_id, $merchant_id, $data, $status, $user_id, $bulk_id = 0)
    {
        try {
            $commision_type = ($data['commision_type'] > 0) ? $data['commision_type'] : 0;
            $commision_percent = ($data['commision_percent'] > 0) ? $data['commision_percent'] : 0;
            $commision_amount = ($data['commision_amount'] > 0) ? $data['commision_amount'] : 0;
            $online_settlement = (isset($data['online_settlement'])) ? $data['online_settlement'] : 0;
            $settlement_type = ($data['settlement_type'] > 0) ? $data['settlement_type'] : 0;

            $sql = "INSERT INTO `vendor`(`pg_vendor_id`,`vendor_code`,`online_pg_settlement`,`settlement_type`,`commision_type`,`commission_percentage`,`commision_amount`,`merchant_id`,`vendor_name`,`email_id`,`mobile`,`address`,`city`,
`state`,`zipcode`,`pan`,`adhar_card`,`gst_number`,`bank_holder_name`,`bank_account_no`,`bank_name`,`ifsc_code`,`account_type`,`status`,`bulk_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(:pg_vendor_id,:vendor_code,:online_pg_settlement,:settlement_type,:commision_type,:commission_percentage,:commision_amount,:merchant_id,:name,:email,:mobile,:address,:city,:state,:zipcode,:pan,:adhar_card,:gst,:bank_holder_name,
:bank_account_no,:bank_name ,:ifsc_code ,:account_type, :status,:bulk_id,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':pg_vendor_id' => $pg_vendor_id, ':vendor_code' => $data['vendor_code'], ':online_pg_settlement' => $online_settlement, ':settlement_type' => $settlement_type,
                ':commision_type' => $commision_type, ':commission_percentage' => $commision_percent, ':commision_amount' => $commision_amount, ':merchant_id' => $merchant_id, ':name' => $data['vendor_name'], ':email' => $data['email'], ':mobile' => $data['mobile'],
                ':address' => $data['address'], ':city' => $data['city'], ':state' => $data['state'], ':zipcode' => $data['zipcode'],
                ':pan' => $data['pan'], ':adhar_card' => $data['adhar_card'], ':gst' => $data['gst'],
                ':bank_holder_name' => $data['account_holder_name'], ':bank_account_no' => $data['account_number'], ':bank_name' => $data['bank_name'],
                ':ifsc_code' => $data['ifsc_code'], ':account_type' => $data['account_type'], ':status' => $status, ':bulk_id' => $bulk_id, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E295]Error while creating Franchise Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateVendor($vendor_id, $pg_vendor_id, $data, $status, $user_id)
    {
        try {
            $commision_type = ($data['commision_type'] > 0) ? $data['commision_type'] : 0;
            $commision_percent = ($data['commision_percent'] > 0) ? $data['commision_percent'] : 0;
            $commision_amount = ($data['commision_amount'] > 0) ? $data['commision_amount'] : 0;
            $online_settlement = (isset($data['online_settlement'])) ? $data['online_settlement'] : 0;
            $settlement_type = ($data['settlement_type'] > 0) ? $data['settlement_type'] : 0;
            $sql = "UPDATE `vendor` SET `vendor_name`=:vendor_name,`vendor_code`=:vendor_code,`vendor_name`=:vendor_name,`vendor_name`=:vendor_name,`email_id`=:email_id,mobile=:mobile,address=:address,city=:city,state=:state,zipcode=:zipcode, `pg_vendor_id`=:pg_vendor_id,`pan` = :pan,`adhar_card` = :adhar_card,`gst_number` = :gst,`bank_holder_name`=:bank_holder_name,`bank_account_no`=:bank_account_no,`bank_name`=:bank_name,`ifsc_code`=:ifsc_code,`account_type`=:account_type,"
                . "online_pg_settlement=:online_settlement,commision_type=:commision_type,commission_percentage=:commision_percent,commision_amount=:commision_amount,"
                . "settlement_type=:settlement_type,`status`=:status,`last_update_by` =:user_id  WHERE `vendor_id` = :vendor_id ;";
            $params = array(
                ':vendor_id' => $vendor_id, ':vendor_code' => $data['vendor_code'], ':vendor_name' => $data['vendor_name'], ':email_id' => $data['email'], ':mobile' => $data['mobile'], ':address' => $data['address'], ':city' => $data['city'], ':state' => $data['state'], ':zipcode' => $data['zipcode'], ':pg_vendor_id' => $pg_vendor_id, ':pan' => $data['pan'], ':adhar_card' => $data['adhar_card'], ':gst' => $data['gst'],
                ':bank_holder_name' => $data['account_holder_name'], ':bank_account_no' => $data['account_number'], ':bank_name' => $data['bank_name'],
                ':ifsc_code' => $data['ifsc_code'], ':account_type' => $data['account_type'], ':online_settlement' => $online_settlement, ':commision_type' => $commision_type, ':commision_percent' => $commision_percent,
                ':commision_amount' => $commision_amount, ':settlement_type' => $settlement_type, ':status' => $status, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E298]Error while update vendor Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateStagingVendor($vendor_id, $data, $user_id)
    {
        try {
            $commision_type = ($data['commision_type'] > 0) ? $data['commision_type'] : 0;
            $commision_percent = ($data['commision_percent'] > 0) ? $data['commision_percent'] : 0;
            $commision_amount = ($data['commision_amount'] > 0) ? $data['commision_amount'] : 0;
            $online_settlement = (isset($data['online_settlement'])) ? $data['online_settlement'] : 0;
            $settlement_type = ($data['settlement_type'] > 0) ? $data['settlement_type'] : 0;
            $sql = "UPDATE `staging_vendor` SET `vendor_name` = :name ,`email_id` = :email,`mobile` = :mobile,`address` = :address,"
                . "`city` =:city ,`state` = :state,`zipcode` = :zipcode,`pan` = :pan,`adhar_card` = :adhar_card,`gst_number` = :gst,"
                . "online_pg_settlement=:online_settlement,commision_type=:commision_type,commission_percentage=:commision_percent,commision_amount=:commision_amount,"
                . "settlement_type=:settlement_type,`bank_holder_name` = :bank_holder_name,"
                . "`bank_account_no` = :bank_account_no,`bank_name` =:bank_name,`ifsc_code` = :ifsc_code,`account_type`=:account_type,"
                . "`last_update_by` =:user_id  WHERE `vendor_id` = :vendor_id ;";
            $params = array(
                ':vendor_id' => $vendor_id, ':name' => $data['vendor_name'], ':email' => $data['email'], ':mobile' => $data['mobile'],
                ':address' => $data['address'], ':city' => $data['city'], ':state' => $data['state'], ':zipcode' => $data['zipcode'],
                ':pan' => $data['pan'], ':adhar_card' => $data['adhar_card'], ':gst' => $data['gst'],
                ':online_settlement' => $online_settlement, ':commision_type' => $commision_type, ':commision_percent' => $commision_percent,
                ':commision_amount' => $commision_amount, ':settlement_type' => $settlement_type,
                ':bank_holder_name' => $data['account_holder_name'], ':bank_account_no' => $data['account_number'], ':bank_name' => $data['bank_name'],
                ':ifsc_code' => $data['ifsc_code'], ':account_type' => $data['account_type'], ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E298]Error while update vendor Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveTransfer($type, $merchant_id, $data, $cashfree_transfer_id, $user_id, $bulk_id = 0)
    {
        try {
            $datet = new DateTime($data['date']);
            $date = $datet->format('Y-m-d');
            $data['vendor_id'] = ($data['vendor_id'] > 0) ? $data['vendor_id'] : 0;
            $data['franchise_id'] = ($data['franchise_id'] > 0) ? $data['franchise_id'] : 0;
            $data['response_type'] = ($data['response_type'] > 0) ? $data['response_type'] : 0;
            $status = ($type == 1) ? 0 : 1;
            $sql = "INSERT INTO `vendor_transfer`(`type`,`beneficiary_type`,`merchant_id`,`vendor_id`,`franchise_id`,`amount`,`narrative`,`status`,`offline_response_type`,`transfer_date`,
                `bank_transaction_no`,`cheque_no`,`cash_paid_to`,`bank_name`,`cashfree_transfer_id`,`bulk_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(:type,:beneficiary_type,:merchant_id,:vendor_id,:franchise_id,:amount,:narrative,:status,:response_type,:date,:bank_transaction_no,:cheque_no,:cash_paid_to,:bank_name,:cashfree_transfer_id,:bulk_id,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':type' => $type, ':beneficiary_type' => $data['transfer_type'], ':merchant_id' => $merchant_id, ':vendor_id' => $data['vendor_id'], ':franchise_id' => $data['franchise_id'], ':amount' => $data['amount'],
                ':narrative' => $data['narrative'], ':status' => $status, ':response_type' => $data['response_type'], ':date' => $date, ':bank_transaction_no' => $data['bank_transaction_no'],
                ':cheque_no' => $data['cheque_no'], ':cash_paid_to' => $data['cash_paid_to'], ':bank_name' => $data['bank_name'], ':cashfree_transfer_id' => $cashfree_transfer_id, ':bulk_id' => $bulk_id, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E109-8]Error whil eoffline seat booking Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveAdjustment($merchant_id, $data, $type, $pg_vendor_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `vendor_adjustment`(`vendor_id`,`merchant_id`,`type`,`amount`,`narrative`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:vendor_id,:merchant_id,:type,:amount,:narrative,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':vendor_id' => $pg_vendor_id, ':merchant_id' => $merchant_id, ':type' => $type, ':amount' => $data['amount'], ':narrative' => $data['narrative'], ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E295]Error while save Adjustment Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function unsettleBalance($merchant_id, $fee_id)
    {
        try {
            $sql = "select sum(amount) as balance from payment_transaction where merchant_id=:merchant_id and fee_id=:fee_id and is_settled=0";
            $params = array(':merchant_id' => $merchant_id, ':fee_id' => $fee_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['balance'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, __METHOD__ . 'Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getTransferList($merchant_id, $from_date, $to_date, $bulk_id)
    {
        try {
            $sql = "select t.*,vendor_name,franchise_name,cg.name "
                . " from vendor_transfer t left outer join vendor v on t.vendor_id=v.vendor_id left outer join franchise f on t.franchise_id=f.franchise_id left outer join cashgram cg on cg.cashgram_id=t.cashgram_id where t.merchant_id=:merchant_id"
                . " and DATE_FORMAT(t.created_date,'%Y-%m-%d')>=:from_date and DATE_FORMAT(t.created_date,'%Y-%m-%d')<=:to_date";
            if ($bulk_id > 0) {
                $sql .= " and t.bulk_id=" . $bulk_id;
            }
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-12]Error while List value ' . $sql . 'Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getBulkTransferList($merchant_id, $bulk_id, $table = 'vendor_transfer')
    {
        try {
            $sql = "select t.*,bf.name,bf.beneficiary_code,vendor_name,franchise_name from " . $table . " t left outer join vendor v on t.vendor_id=v.vendor_id left outer join franchise f on t.franchise_id=f.franchise_id left outer join beneficiary bf on t.beneficiary_id=bf.beneficiary_id where t.merchant_id=:merchant_id and t.bulk_id=:bulk_id";
            $params = array(':merchant_id' => $merchant_id, ':bulk_id' => $bulk_id);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-12]Error while List value ' . $sql . 'Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getBulkuploadList($merchant_id, $type)
    {
        try {
            $sql = "select bulk_upload_id,merchant_id,merchant_filename,total_rows,system_filename,status,bulk_upload.created_date,config_value from bulk_upload inner join config on bulk_upload.status = config.config_key where merchant_id=:merchant_id and config.config_type='bulk_upload_status' and status not in (6,7)  and type=:type order by bulk_upload_id desc";
            $params = array(':merchant_id' => $merchant_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getVendorCode($auto_invoice_id)
    {
        try {
            $sql = 'select generate_invoice_number(:id) as auto_invoice_number';
            $params = array(':id' => $auto_invoice_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['auto_invoice_number'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-78]Error while get auto Vendor code auto id ' . $auto_invoice_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function isExistCustomerCode($merchant_id, $data, $vendor_id = NULL)
    {
        try {
            $sql = 'select distinct vendor_id from vendor where merchant_id=:merchant_id and vendor_code=:vendor_code and is_active=1';
            if ($vendor_id != NULL) {
                $sql .= " and vendor_id<>" . $vendor_id . " ;";
            }
            $params = array(':merchant_id' => $merchant_id, ':vendor_code' => $data);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                return $data['vendor_id'];
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function isValidBeneficiaryCode($merchant_id, $data, $vendor_id = NULL)
    {
        try {
            $sql = 'select distinct beneficiary_id from beneficiary where merchant_id=:merchant_id and beneficiary_code=:vendor_code and is_active=1';
            if ($vendor_id != NULL) {
                $sql .= " and beneficiary_id<>" . $vendor_id . " ;";
            }
            $params = array(':merchant_id' => $merchant_id, ':vendor_code' => $data);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                return $data['beneficiary_id'];
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function saveFeeDetail($merchant_id, $vendor_id, $pg_id, $tdr = 2.1)
    {
        try {
            $sql = "INSERT INTO `merchant_fee_detail`(`merchant_id`,`vendor_id`,`pg_id`,`swipez_fee_type`,`swipez_fee_val`,`pg_fee_type`,`pg_fee_val`,`pg_tax_type`,`pg_tax_val`,`surcharge_enabled`,`created_date`)VALUES(:merchant_id,:vendor_id,:pg_id,'P',:tdr,'F',0,'GST',18,0,CURRENT_TIMESTAMP());";
            $params = array(':merchant_id' => $merchant_id, ':vendor_id' => $vendor_id, ':pg_id' => $pg_id, ':tdr' => $tdr);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+90]Error while save Bank Detail Error: for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function isExistBeneficiaryAccount($merchant_id, $account, $ifsc)
    {
        try {
            $sql = 'select beneficiary_id from beneficiary where merchant_id=:merchant_id and bank_account_no=:account_no and ifsc_code=:ifsc';
            $params = array(':merchant_id' => $merchant_id, ':account_no' => $account, ':ifsc' => $ifsc);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                return $data['beneficiary_id'];
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking beneficiary exist Error: for Merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }
}
