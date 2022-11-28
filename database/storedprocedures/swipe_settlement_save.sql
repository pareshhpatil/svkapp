CREATE DEFINER=`root`@`localhost` PROCEDURE `swipe_settlement_save`(_settlement_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
            show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_swipe_settlement;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_swipe_settlement (
    `id` INT auto_increment ,
    `payu_settlement_id` varchar(40) NULL, 
    `transaction_id` varchar(10) NOT NULL,
    `payment_request_id` varchar(10) NULL,
    `merchant_id` varchar(10) NULL,
    `patron_id` varchar(10) NULL,
    `patron_name` varchar(100) NULL,
    `payment_id` varchar(40) NULL,
    `auth_date` datetime NULL,
    `cap_date` datetime NULL,
    `settlement_date` datetime NULL,
    `payment_method` varchar(20),
    `pg_bank_reff` varchar(100),
    `bank_reff` varchar(100) NULL,
    `captured` decimal(11,2) NULL,
	`tdr` decimal(11,2) NULL,
    `service_tax` decimal(11,2) NULL,
    `net_amount` decimal(11,2) NULL,
    `settlement_id` INT NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

insert into temp_swipe_settlement(payu_settlement_id,transaction_id,payment_method,captured,tdr,service_tax,net_amount,settlement_id) 
    select payu_settlement_id,transaction_id,payment_mode,amount,pg_tdr_amount+swipez_revenue,pg_service_tax+swipez_service_tax,swipez_amount,_settlement_id from revenue_detail where swipez_settlement_id=_settlement_id;
    
    update temp_swipe_settlement t, payu_settlement p set t.auth_date=p.added_on_date,t.cap_date=p.succeeded_on_date,t.patron_name=p.customer_name,t.payment_id=p.payment_id where t.payu_settlement_id=p.id;
    update temp_swipe_settlement t, payment_transaction p set t.merchant_id=p.merchant_user_id,t.patron_id=p.patron_user_id,t.payment_request_id=p.payment_request_id where t.transaction_id=p.pay_transaction_id;
    update temp_swipe_settlement t, xway_transaction p,merchant m set t.merchant_id=m.user_id where t.transaction_id=p.xway_transaction_id and p.merchant_id=m.merchant_id;
    update temp_swipe_settlement t, settlement_detail p set t.settlement_date=p.settlement_date,t.bank_reff=p.bank_reference where t.settlement_id=p.id;
	update temp_swipe_settlement t, pg_ret_bank4 p set t.pg_bank_reff=p.bank_ref_num where t.transaction_id=p.txnid;

	INSERT INTO `payment_transaction_tdr`(`transaction_id`,`payment_request_id`,`merchant_id`,`patron_id`,`patron_name`,`payment_id`,`auth_date`,`cap_date`,`payment_method`,`bank_reff`,
	`captured`,`tdr`,`service_tax`,`net_amount`,`created_date`,`last_update_date`)
	select transaction_id,payment_request_id,merchant_id,patron_id,patron_name,payment_id,auth_date,cap_date,payment_method,pg_bank_reff,captured,tdr,service_tax,net_amount
	,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP() from temp_swipe_settlement;
    
    
    INSERT INTO `payment_transaction_settlement`(`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,`merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,
    `transaction_date`,`settlement_date`,`created_date`)
	select settlement_id,payment_id,transaction_id,payment_request_id,merchant_id,patron_id,captured,concat('-',tdr),concat('-',service_tax),bank_reff,auth_date,settlement_date,CURRENT_TIMESTAMP()
    from temp_swipe_settlement;
SET @message='success';
select @message as 'message';
END
