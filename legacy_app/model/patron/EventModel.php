<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class EventModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getPaymentDetails($payment_req, $user_id)
    {
        try {
            $sql = "call `get_Eventuser_details`(:user_id,:payment_req_id)";
            $params = array(':user_id' => $user_id, ':payment_req_id' => $payment_req);
            $this->db->exec($sql, $params);
            $user_detail = $this->db->single();
            return $user_detail;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E155-1]Error while get event details Error: for Req_id : ' . $payment_req . ' user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function intiatePaymentTransaction($payment_req, $customer_id, $patron_id, $user_id, $amount, $tax_amount, $discount, $pg_id, $fee_id, $seat, $occurence_id, $package_id, $attendee_customer_id, $coupon_id, $narrative = '', $custom_column_id, $custom_column_value, $franchise_id, $vendor_id = 0,$currency='INR')
    {
        try {
            //TODO : This is a workaround to the problem of not being able to perform insert's or updates.
            $this->closeConnection();
            $this->db = new DBWrapper();
            $attendee_customer_id = implode('~', $attendee_customer_id);
            $package_id = implode('~', $package_id);
            $occurence_id = implode('~', $occurence_id);
            $custom_column_id = implode('~', $custom_column_id);
            $custom_column_value = implode('~', $custom_column_value);
            $coupon_id = ($coupon_id > 0) ? $coupon_id : 0;
            $fee_id = ($fee_id > 0) ? $fee_id : 0;
            $tax_amount = ($tax_amount > 0) ? $tax_amount : 0;
            $discount = ($discount > 0) ? $discount : 0;
            $sql = "call save_event_transaction(:payment_request_id,:customer_id,:patron_id,:merchant_id,:amount,:tax_amount,:discount_amount,:pg_id,:fee_id,:seat,:occurence_id,:package_id,:attendee_customer_id,:coupon_id,:narrative,:custom_column_id,:custom_column_value,:franchise_id,:vendor_id,:currency)";
            $params = array(':payment_request_id' => $payment_req, ':customer_id' => $customer_id, ':patron_id' => $patron_id, ':merchant_id' => $user_id, ':amount' => $amount, ':tax_amount' => $tax_amount, ':discount_amount' => $discount, ':pg_id' => $pg_id, ':fee_id' => $fee_id, ':seat' => $seat, ':occurence_id' => $occurence_id, ':package_id' => $package_id, ':attendee_customer_id' => $attendee_customer_id, ':coupon_id' => $coupon_id, ':narrative' => $narrative, ':custom_column_id' => $custom_column_id, ':custom_column_value' => $custom_column_value, ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id,':currency'=>$currency);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (isset($row['transaction_id'])) {
                return $row['transaction_id'];
            } else {
                throw new Exception(json_encode($row));
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E156]Error while initiated payment request Error: for patron id[' . $patron_id . '] and for payment request id [' . $payment_req . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updatePaymentRequestStatus($payment_request_id, $status)
    {
        try {
            $this->closeConnection();
            $this->db = new DBWrapper();

            $sql = "update payment_request set payment_request_status=:status where payment_request_id=:request_id";
            $params = array(':status' => $status, ':request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161]Error while updating payment request status Error: for payment request id[' . $payment_request_id . ']' . $e->getMessage());
        }
    }

    public function getEventPackageDetails($package_id)
    {
        try {
            $sql = "select seats_available from event_package where package_id=:package_id and is_active=1 and sold_out=0";
            $params = array(':package_id' => $package_id);
            $this->db->exec($sql, $params);
            $list = $this->db->single();
            return $list['seats_available'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E234+1]Error while fetching Society Statement Error:  for user id[' . $user_id . '] and  for payment request id[' . $payment_request_id . '] ' . $e->getMessage());
        }
    }

    public function gettransactionCoupon($transaction_id)
    {
        try {
            $sql = "select coupon_code ,count(coupon_code) as countc from event_transaction_detail where transaction_id=:pay_transaction_id and coupon_code<>0";
            $params = array(':pay_transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E234+2]Error while fetching Society Statement Error:  for user id[' . $user_id . '] and  for payment request id[' . $payment_request_id . '] ' . $e->getMessage());
        }
    }

    public function updatetransactionCoupon($count, $coupon_id)
    {
        try {
            $sql = "update coupon   set available = available - :count  where coupon_id=:coupon_id and `limit` <> 0 ;";
            $params = array(':count' => $count, ':coupon_id' => $coupon_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161]Error while updating payment request status Error: for payment request id[' . $payment_request_id . ']' . $e->getMessage());
        }
    }

    public function updatetransactionSeat($transaction_id)
    {
        try {
            $sql = "update event_transaction_detail set is_paid=1 where transaction_id=:pay_transaction_id;";
            $params = array(':pay_transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161]Error while updating payment request status Error: for payment request id[' . $payment_request_id . ']' . $e->getMessage());
        }
    }

    public function getEventPackageBookCount($package_id, $occur_id)
    {
        try {
            $sql = "select count(*) as ccount from event_transaction_detail where package_id=:package_id and occurence_id=:occr_id and is_paid=1";
            $params = array(':package_id' => $package_id, ':occr_id' => $occur_id);
            $this->db->exec($sql, $params);
            $list = $this->db->single();
            return $list['ccount'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E234+3]Error while fetching Society Statement Error:  for user id[' . $user_id . '] and  for payment request id[' . $payment_request_id . '] ' . $e->getMessage());
        }
    }
}
