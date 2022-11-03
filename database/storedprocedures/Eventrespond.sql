-- CREATE DEFINER=`root`@`localhost` PROCEDURE `Eventrespond`(_amount decimal(11,2),_bank_name int,_payment_req_id varchar(11)
-- ,_date DATETIME ,_bank_transaction_no varchar(20),_respond_type int,_cheque_no int,_cash_paidto varchar(45),_patron_id varchar(10),_payment_request_status int)
-- BEGIN 
--  DECLARE EXIT HANDLER FOR SQLEXCEPTION 
-- 		BEGIN
--         SET @message = 'failed';
--         	ROLLBACK;
-- 		END; 
-- START TRANSACTION;

-- SET @patron_id=_patron_id;
-- SELECT user_id into @merchant_id from payment_request where payment_request_id =_payment_req_id;
-- select generate_sequence('Offline_respond_id') into @offline_response_id;

-- INSERT INTO `offline_response`(`offline_response_id`,`payment_request_id`,`patron_user_id`,`merchant_user_id`,`offline_response_type`,
-- `settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`cheque_no`,`cash_paid_to`,`created_by`,`created_date`, `last_update_by`,
-- `last_update_date`)
-- 	VALUES (@offline_response_id,_payment_req_id,@patron_id,@merchant_id,_respond_type,_date,_bank_transaction_no,_bank_name,
--     _amount,_cheque_no,_cash_paidto,_user_id, CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());


-- if(@merchant_id!=_user_id) then
-- SET @notification_type=1;
-- SET @link='/merchant/transaction/viewlist/event/event';
-- SET @message='count Event(s) have been settled by your patron';
-- INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,
--         `created_by`,`created_date`,`last_update_by`,`last_update_date`)
--         VALUES(@merchant_id,@notification_type,@link,CURRENT_TIMESTAMP(),DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH),
--         @message,'',@patron_id,CURRENT_TIMESTAMP(),@patron_id,CURRENT_TIMESTAMP());
-- end if;


-- commit;
--         SET @message = 'success';
--         select @message as 'message',@offline_response_id as 'offline_response_id';

-- END
