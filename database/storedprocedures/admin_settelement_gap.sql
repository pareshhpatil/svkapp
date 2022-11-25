CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_settelement_gap`(_from_date date , _to_date date)
BEGIN
Drop TEMPORARY  TABLE  IF EXISTS temp_transgap_detail;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_transgap_detail (
   `transaction_id` varchar(10) NOT NULL,
   `revenue_id` INT  NULL default 0 ,
   `date` datetime NULL,
   `settlement_id` INT NULL,
   `amount` decimal(11,2) not null,
   `merchant_id` varchar (10) not null ,
  `merchant_name` varchar (100)  null ,
   `pg_name` varchar (45)  null ,
   PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;
   
   insert into temp_transgap_detail(transaction_id,amount,merchant_id,date,pg_name)
   select pay_transaction_id,amount,merchant_id,t.created_date,p.pg_name from payment_transaction t 
   inner join payment_gateway p on t.pg_id=p.pg_id where payment_transaction_status=1 
   and p.pg_id in (6,15,22,26,34) and t.created_date >= _from_date and t.created_date <= _to_date;
   
   insert into temp_transgap_detail(transaction_id,amount,merchant_id,date,pg_name)
   select xway_transaction_id,amount,merchant_id,t.created_date,p.pg_name from xway_transaction t 
   inner join payment_gateway p on t.pg_id=p.pg_id where xway_transaction_status=1 
   and p.pg_id in (6,15,22,26,34) and t.created_date >= _from_date and t.created_date <= _to_date;
   
   update temp_transgap_detail r , revenue_detail u  set r.revenue_id = u.id where r.transaction_id=u.transaction_id;
   
   update temp_transgap_detail r , payment_transaction_settlement u  set r.settlement_id = u.id where r.transaction_id=u.transaction_id;
  
   update temp_transgap_detail r , merchant u  set r.merchant_name = u.company_name where r.merchant_id=u.merchant_id;
  
   select transaction_id,date,amount,merchant_id,merchant_name,pg_name from temp_transgap_detail where revenue_id = 0 and settlement_id is null;

END
