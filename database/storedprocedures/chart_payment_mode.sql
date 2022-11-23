CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_payment_mode`(_merchant_id varchar(10),_from_date datetime , _to_date datetime)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_mode;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_mode (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `mode` varchar(20) not null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    insert into temp_payment_mode(pay_transaction_id,mode) select pay_transaction_id,'Online' from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_mode(pay_transaction_id,mode) 
    select offline_response_id,'Offline' from offline_response where offline_response_type <> 4 and is_active=1 and merchant_id=_merchant_id  and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    select mode as name,count(pay_transaction_id) as value from temp_payment_mode group by mode;

    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_mode;
    
commit;
END
