CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_payment_received`(_merchant_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');
Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_received (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `date` DATE not null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;

    
    insert into temp_payment_received(pay_transaction_id,date) select pay_transaction_id,DATE_FORMAT(last_update_date,'%Y-%m-%d') from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_received(pay_transaction_id,date) 
    select offline_response_id,DATE_FORMAT(created_date,'%Y-%m-%d') from offline_response where offline_response_type<>4 and is_active=1 and post_paid_invoice=0 and merchant_id=_merchant_id  and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
   
	insert into temp_payment_received(pay_transaction_id,date) select xway_transaction_id,DATE_FORMAT(last_update_date,'%Y-%m-%d') from xway_transaction where xway_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d')<= @to_date ;
   
    select date as name,count(pay_transaction_id) as value from temp_payment_received group by date;

    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;
    
commit;
END
