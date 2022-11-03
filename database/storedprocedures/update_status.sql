CREATE DEFINER=`root`@`localhost` PROCEDURE `update_status`(_payment_request_id varchar(10),_fee_transaction_id varchar(10),_amount decimal(11,2),_status int,_bankstatus int,_pg_ref_no varchar(40),
_pg_ref_1 varchar(40),_payment_mode varchar(45),_pg_ref_2 varchar(40), _message VARCHAR(100))
BEGIN 
   DECLARE patron_id VARCHAR(10) DEFAULT '';
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
		END; 

set @payment_request='';
set @payment_request=_payment_request_id;
SET @late_payment=0;
SET @user_id='';

SELECT due_date, customer_id, user_id,invoice_type INTO @due_date , @customer_id , @user_id,@invoice_type FROM payment_request WHERE payment_request_id = @payment_request;

 if(DATE_FORMAT(NOW(),'%Y-%m-%d') > DATE_FORMAT(@due_date,'%Y-%m-%d')) then
	SET @late_payment=1;
 end if;
 
 if(@invoice_type=2 and _status=1)then
	call convert_estimate_to_invoice(@payment_request,@new_request_id);
    
    UPDATE `payment_request` SET `payment_request_status` = _status WHERE
    payment_request_id = @new_request_id ;
    
    set @payment_request_id=@new_request_id;
    
    UPDATE `payment_transaction` SET payment_request_id=@new_request_id,estimate_request_id=@payment_request,payment_mode=_payment_mode,narrative=_message where pay_transaction_id = _fee_transaction_id;
end if;

UPDATE `payment_transaction` 
SET 
    `payment_transaction_status` = _status,
    `late_payment` = @late_payment,
    `amount` = _amount,
    bank_status = _bankstatus,
    pg_ref_no = _pg_ref_no,
    pg_ref_1 = _pg_ref_1,
    pg_ref_2 = _pg_ref_2,
    payment_mode=_payment_mode,
    narrative=_message,
    last_update_by = `patron_user_id`,
    last_update_date = CURRENT_TIMESTAMP()
WHERE
    pay_transaction_id = _fee_transaction_id;

	UPDATE `payment_request` SET `payment_request_status` = _status WHERE
    payment_request_id = @payment_request and payment_request_status not in (1,2);
	
END
