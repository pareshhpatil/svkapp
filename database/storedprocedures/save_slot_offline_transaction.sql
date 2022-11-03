CREATE DEFINER=`root`@`localhost` PROCEDURE `save_slot_offline_transaction`(_type INT,_customer_id INT,_merchant_id varchar(10),_merchant_user_id varchar(10),
_amount decimal(11,2),_price longtext, _bank_id varchar(200), _date datetime,_bank_ref_no varchar(20),_cheque_no INT,_cash_paid_to varchar(100),_response_type INT,_seat INT,_narrative varchar(500))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;


select generate_sequence('Offline_respond_id') into @transaction_id;

SET @grand_total=_amount;

INSERT INTO `offline_response`(`offline_response_id`,`payment_request_id`,`payment_request_type`,`customer_id`,`merchant_id`,`merchant_user_id`,`offline_response_type`,
`settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`tax`,`discount`,`grand_total`,`quantity`,`cheque_no`,`cash_paid_to`,`narrative`,`created_by`,`created_date`, `last_update_by`,
`last_update_date`)
	VALUES (@transaction_id,0,_type,_customer_id,_merchant_id,_merchant_user_id,_response_type,_date,_bank_ref_no,_bank_id,_amount,0,0,@grand_total,_seat,_cheque_no,_cash_paid_to,_narrative,_merchant_id, CURRENT_TIMESTAMP(),_merchant_id,CURRENT_TIMESTAMP());

SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

commit;

select @transaction_id as 'transaction_id';

END
