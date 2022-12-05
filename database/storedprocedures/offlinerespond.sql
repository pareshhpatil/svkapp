CREATE DEFINER=`root`@`localhost` PROCEDURE `offlinerespond`(_amount decimal(11,2),_bank_name varchar(100),_payment_req_id varchar(11)
,_date DATETIME ,_bank_transaction_no varchar(20),_respond_type int,_cheque_no varchar(20),_cash_paidto nvarchar(100)
,_user_id varchar(10),_payment_request_status int,_coupon_id INT,_discount decimal(11,2),_deduct_amount decimal(11,2),_deduct_text varchar(100),_cheque_status varchar(10),_is_partial tinyint(1))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        SET @message = 'failed';
        	ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @merchant_user_id='';
SET @transaction_status=1;

SELECT merchant_id,user_id,customer_id,payment_request_type,franchise_id,vendor_id,paid_amount,grand_total into @merchant_id,@merchant_user_id,@customer_id,@payment_request_type,@franchise_id,@vendor_id,@paid_amount,@grand_total 
from payment_request where payment_request_id =_payment_req_id;

select generate_sequence('Offline_respond_id') into @offline_response_id;

if(_cheque_status='Bounced')then
SET _payment_request_status=4;
SET @transaction_status=0;
end if;

INSERT INTO `offline_response`(`offline_response_id`,`payment_request_id`,`payment_request_type`,`customer_id`,`merchant_user_id`,`merchant_id`,`offline_response_type`,
`settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`discount`,`coupon_id`,`cheque_no`,`cheque_status`,`transaction_status`,`cash_paid_to`,`deduct_amount`,`deduct_text`,`created_by`,`created_date`, `last_update_by`,
`last_update_date`,`franchise_id`,`vendor_id`,`is_partial_payment`)
VALUES (@offline_response_id,_payment_req_id,1,@customer_id,@merchant_user_id,@merchant_id,_respond_type,_date,_bank_transaction_no,_bank_name,
    _amount,_discount,_coupon_id,_cheque_no,_cheque_status,@transaction_status,_cash_paidto,_deduct_amount,_deduct_text,_user_id, CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP(),@franchise_id,@vendor_id,_is_partial);



update payment_request set payment_request_status=_payment_request_status where payment_request_id=_payment_req_id;

update customer set balance = balance - _amount where customer_id=@customer_id;
INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@customer_id,concat('Payment on ',DATE_FORMAT(NOW(),'%Y-%m-%d')),_amount,2,@offline_response_id,'CREDIT',@customer_id,CURRENT_TIMESTAMP(),@customer_id);


if(_is_partial=1)then
update payment_request set payment_request_status=7,paid_amount=@paid_amount+_amount where payment_request_id=_payment_req_id;
end if;

if(@merchant_user_id!=_user_id) then
select payment_request_type into @type from payment_request where payment_request_id=_payment_req_id;
if(@type=2)then
SET @link='/merchant/transaction/viewlist/event';

elseif(@type=3)then
SET @link='/merchant/transaction/viewlist/bulk';

elseif(@type=4)then
SET @link='/merchant/transaction/viewlist/subscription';

else
SET @link='/merchant/transaction/viewlist';

end if;
    if(_payment_request_status<>3)then
        SET @notification_type=1;
        SET @message='count payment request(s) have been settled by your patron';
        INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,
                `created_by`,`created_date`,`last_update_by`,`last_update_date`)
                VALUES(@merchant_user_id,@notification_type,@link,CURRENT_TIMESTAMP(),DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH),
                @message,'',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
    end if;
end if;

select customer_code,concat(first_name,' ',last_name),email,mobile into @user_code, @patron_name,@email_id,@mobile from customer where customer_id=@customer_id ;
commit;
        SET @message = 'success';
        select @message as 'message',@offline_response_id as 'offline_response_id',@user_code as 'user_code',@patron_name as 'patron_name',@email_id as 'email_id',@mobile as 'mobile';

END
