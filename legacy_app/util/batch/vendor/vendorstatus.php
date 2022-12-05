<?php

include('../config.php');

class VendorStatus extends Batch
{

    public $logger = NULL;

    function __construct()
    {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->updatePayoutTransferStatus();
        $this->updateVendorStatus();
        $this->refundStatus();
        //$this->updateTransferStatus();
    }

    function getPendingVendors()
    {
        try {
            $sql = "select vendor_id,pg_vendor_id,v.merchant_id,k.cashfree_client_id,k.cashfree_client_secret from vendor v inner join merchant_security_key k on v.merchant_id=k.merchant_id"
                . " where v.status=0 and v.is_active=1 and v.online_pg_settlement=1";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function getPendingFranchise()
    {
        try {
            $sql = "select franchise_id,pg_vendor_id,v.merchant_id,k.cashfree_client_id,k.cashfree_client_secret from franchise v inner join merchant_security_key k on v.merchant_id=k.merchant_id"
                . " where v.status=0 and v.is_active=1 and v.online_pg_settlement=1";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function getPendingTransfer()
    {
        try {
            $sql = "select cashfree_transfer_id,transfer_id,v.merchant_id,k.cashfree_client_id,k.cashfree_client_secret from vendor_transfer v inner join merchant_security_key k on v.merchant_id=k.merchant_id"
                . " where v.status=0 and v.is_active=1 and v.type=1";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function getPendingPayoutTransfer()
    {
        try {
            $sql = "select cashfree_transfer_id,transfer_id,v.merchant_id,k.value from vendor_transfer v inner join merchant_config_data k on v.merchant_id=k.merchant_id and k.key='PAYOUT_KEY_DETAILS'"
                . " where v.status=0 and v.is_active=1 and v.beneficiary_type=3 and DATE_FORMAT(v.created_date,'%Y-%m-%d')=CURDATE()";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function updateVendorStatus()
    {
        try {
            $rows = $this->getPendingVendors();
            require_once UTIL . 'CashfreeAPI.php';
            foreach ($rows as $row) {
                $cashfree = new CashfreeAPI($row['cashfree_client_id'], $row['cashfree_client_secret']);
                $response = $cashfree->getVendor($row['pg_vendor_id']);
                $this->logger->info(__CLASS__, 'Cashfree Response[VAPI2] : ' . json_encode($response));
                if ($response['status'] == 1) {
                    if ($response['response']['data']['status'] == 'VERIFIED') {
                        $this->updateStatus('vendor', 'vendor_id', $row['vendor_id']);
                    }
                }
            }
            $rows = $this->getPendingFranchise();
            foreach ($rows as $row) {
                $cashfree = new CashfreeAPI($row['cashfree_client_id'], $row['cashfree_client_secret']);
                $response = $cashfree->getVendor($row['pg_vendor_id']);
                $this->logger->info(__CLASS__, 'Cashfree Response[VAPI2] : ' . json_encode($response));
                if ($response['status'] == 1) {
                    if ($response['response']['data']['status'] == 'VERIFIED') {
                        $this->updateStatus('franchise', 'franchise_id', $row['franchise_id']);
                    }
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI3]Error update vendor status Error: ' . $e->getMessage());
        }
    }

    function updateTransferStatus()
    {
        $rows = $this->getPendingTransfer();
        require_once UTIL . 'CashfreeAPI.php';
        foreach ($rows as $row) {
            $cashfree = new CashfreeAPI($row['cashfree_client_id'], $row['cashfree_client_secret']);
            $response = $cashfree->transferStatus($row['cashfree_transfer_id']);
            $this->logger->info(__CLASS__, 'Cashfree Transfer Response[VAPI3] : ' . json_encode($response));
            if ($response['status'] == 1) {
                if (isset($response['response']['data']['vendorId'])) {
                    if ($response['response']['data']['status'] == 'SUCCESS') {
                        $this->updateTransferUTR($response['response']['data']['utr'], $row['transfer_id']);
                        $this->updateSettlementUTR($response['response']['data']['utr'], $row['transfer_id']);
                    }
                }
            }
        }
    }

    function updatePayoutTransferStatus()
    {
        $rows = $this->getPendingPayoutTransfer();
        require_once UTIL . 'CashfreePayoutAPI.php';
        foreach ($rows as $row) {
            $keys = json_decode($row['value'], 1);
            $cashfree = new CashfreePayoutAPI($keys['key'], $keys['secret'], $keys['mode']);
            $response = $cashfree->transferStatus($row['cashfree_transfer_id']);
            $this->logger->info(__CLASS__, 'Cashfree Transfer Response[VAPI3] : ' . json_encode($response));
            if ($response['status'] == 1) {
                if (isset($response['response']['data']['transfer']['beneId'])) {
                    if ($response['response']['data']['transfer']['status'] == 'SUCCESS') {
                        $this->updateTransferUTR($response['response']['data']['transfer']['utr'], $row['transfer_id']);
                    } elseif ($response['response']['data']['transfer']['status'] == 'PENDING') {
                    } else {
                        $utr = (isset($response['response']['data']['transfer']['utr'])) ? $response['response']['data']['transfer']['utr'] : '';
                        $this->updateTransferUTR($utr, $row['transfer_id'], 2);
                    }
                }
            } else {
                $utr = (isset($response['response']['data']['transfer']['utr'])) ? $response['response']['data']['transfer']['utr'] : '';
                $this->updateTransferUTR($utr, $row['transfer_id'], 2);
            }
        }
    }

    function updateStatus($table, $column_name, $id)
    {
        try {
            $sql = "update " . $table . " set status=1 where " . $column_name . "=" . $id;
            $params = array();
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function updateTransferUTR($utr, $id, $status = 1)
    {
        try {
            $sql = "update vendor_transfer set status=:status, utr_number=:utr where transfer_id=:id";
            $params = array(':status' => $status, ':utr' => $utr, ':id' => $id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while update Transfer UTR Error: ' . $e->getMessage());
        }
    }

    function updateSettlementUTR($utr, $id)
    {
        try {
            $sql = "update settlement_detail set status=1, bank_reference=:utr where cashfree_transfer_id=:id";
            $params = array(':utr' => $utr, ':id' => $id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while update Settlement UTR Error: ' . $e->getMessage());
        }
    }

    function updateRefundStatus($refund_status, $refund_date, $id)
    {
        try {
            $sql = "update refund_request set refund_status=:refund_status, refund_date=:refund_date,last_update_by='System' where id=:id";
            $params = array(':refund_status' => $refund_status, ':refund_date' => $refund_date, ':id' => $id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function getPendingRefunds()
    {
        try {
            $sql = "select r.id,refund_id,r.transaction_id,r.id,pg_val1,pg_val2,pg_type from refund_request r inner join payment_gateway p on r.pg_id=p.pg_id  where refund_status=0";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function refundStatus()
    {
        try {
            $rows = $this->getPendingRefunds();
            require_once UTIL . 'CashfreeAPI.php';
            foreach ($rows as $row) {
                if ($row['pg_type'] == 7) {
                    $cashfree = new CashfreeAPI($row['pg_val1'], $row['pg_val2']);
                    $response = $cashfree->refundStatus($row['pg_val1'], $row['pg_val2'], $row['refund_id']);
                    $status = $response['response']['refund'][0]['processed'];
                    if ($status == 'YES') {
                        $refund_date = $response['response']['refund'][0]['processedOn'];
                        $this->updateRefundStatus(1, $refund_date, $row['id']);
                    }
                } 
                // else if ($row['pg_type'] == 13){

                // }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[VAPI3]Error update vendor status Error: ' . $e->getMessage());
        }
    }
}

$ab = new VendorStatus();
