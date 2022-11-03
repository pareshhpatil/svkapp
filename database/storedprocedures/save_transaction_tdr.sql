CREATE DEFINER=`root`@`localhost` PROCEDURE `save_transaction_tdr`(_payment_id varchar(45),_auth_date datetime,_cap_date datetime,_payment_method varchar(100),
_bank_reff varchar(250),_transaction_id varchar(10),_patron_name varchar(250),_captured decimal(9,2),_refunded decimal(9,2),_charge_back decimal(9,2),
_tdr decimal(9,2),_service_tax decimal(9,2),_surcharge decimal(9,2),_surcharge_service_tax decimal(9,2),_emitdr decimal(9,2),_emi_service_tax decimal(9,2),_net_amount decimal(9,2))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 

SET @id=0;

SET @merchant_user_id='';

select tdr_id into @id from payment_transaction_tdr where transaction_id=_transaction_id;

if(@id>0)then
SET @message='success';
    select @message as 'message';
else
    
    select payment_request_id,merchant_user_id,patron_user_id into @payment_request_id,@merchant_user_id,@patron_user_id from payment_transaction where pay_transaction_id=_transaction_id;

if(@merchant_user_id='')Then
    select merchant_id into @merchant_id from xway_transaction where xway_transaction_id=_transaction_id;
    select user_id into @merchant_user_id from merchant where merchant_id=@merchant_id;
    SET @payment_request_id='';
    SET @patron_user_id='';
end if;

if(@merchant_user_id='')Then
    SET @merchant_id = 'M000000151';
	SET @merchant_user_id ='U000002349';
    SET @payment_request_id='';
    SET @patron_user_id='';
end if;

    INSERT INTO `payment_transaction_tdr`(`transaction_id`,`payment_request_id`,`merchant_id`,`patron_id`,`patron_name`,
    `payment_id`,`auth_date`,`cap_date`,`payment_method`,`bank_reff`,`captured`,`refunded`,`chargeback`,`tdr`,`service_tax`,`surcharge`,`surcharge_service_tax`,
    `emitdr`,`emi_service_tax`,`net_amount`,`created_date`,`last_update_date`)VALUES(_transaction_id,@payment_request_id,@merchant_user_id,@patron_user_id,
    _patron_name,_payment_id,_auth_date,_cap_date,_payment_method,_bank_reff,_captured,_refunded,_charge_back,_tdr,_service_tax,_surcharge,_surcharge_service_tax,
    _emitdr,_emi_service_tax,_net_amount,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

        SET @id=LAST_INSERT_ID();
        if(@id>0) then
        SET @message='success';
        select @message as 'message';
    end if;
end if;




END
