CREATE DEFINER=`root`@`localhost` PROCEDURE `save_transaction_settlement`(_payment_id varchar(45),_transaction_id varchar(10),_transaction_date datetime,_settlement_date datetime,_bank_reff varchar(20),_settlement_id varchar(20),
_captured decimal(9,2),_tdr decimal(9,2),_service_tax decimal(9,2))
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
	show errors;
ROLLBACK;
END; 

SET @id=0;

SET @merchant_user_id='';

	select id into @id from payment_transaction_settlement where payment_id=_payment_id;

if(@id>0)then
	SET @message='success';
	select @message as 'message';
else

	select payment_request_id,merchant_user_id,patron_user_id into @payment_request_id,@merchant_user_id,@patron_user_id from payment_transaction where pay_transaction_id=_transaction_id;

	if(@merchant_user_id='')then
	select merchant_id into @merchant_id from xway_transaction where xway_transaction_id=_transaction_id;
	select user_id into @merchant_user_id from merchant where merchant_id=@merchant_id;
	end if;
    
	if(@merchant_user_id='')then
	SET @merchant_id = 'M000000151';
	SET @merchant_user_id ='U000002349';
	end if;

if(@merchant_user_id='')then
	SET @message='failed';
else
	INSERT INTO `payment_transaction_settlement`
	(`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,`merchant_id`,`patron_id`,`captured`,`tdr`,
	`service_tax`,`bank_reff`,`transaction_date`,`settlement_date`,`created_date`)VALUES(_settlement_id,_payment_id,_transaction_id,@payment_request_id,@merchant_user_id,@patron_user_id,_captured,_tdr,_service_tax,
	_bank_reff,_transaction_date,_settlement_date,CURRENT_TIMESTAMP());

	SET @id=LAST_INSERT_ID();

if(@id>0) then
	SET @message='success';
end if;
	select @message as 'message';
	end if;
end if;




END
