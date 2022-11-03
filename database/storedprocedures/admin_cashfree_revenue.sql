CREATE DEFINER=`swipez_admin`@`%` PROCEDURE `admin_cashfree_revenue`(_from_date date,_to_date date)
BEGIN
Drop TEMPORARY  TABLE  IF EXISTS temp_cf_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_cf_transaction (
    `payment_id` varchar(20) NOT NULL ,
    `captured` decimal(11,2) NULL ,
    `transaction_date` datetime  NULL,
    `transaction_id` varchar(10)  NULL,
    `company_name` varchar(100)  NULL,
    `net_amount` decimal(11,2) NULL,
    `settlement_date` datetime  NULL,
     `bank_reff` varchar(45)  NULL,
    `tdr` DECIMAL(11,2)  null ,
    `service_tax` DECIMAL(11,2)  null ,
     `payment_method` varchar(20)  NULL,
     `swipez_revenue` DECIMAL(11,2) NOT null default 0 ,
     `swipez_gst` DECIMAL(11,2) NOT null default 0,
    PRIMARY KEY (`payment_id`)) ENGINE=MEMORY;
    
 insert into temp_cf_transaction (payment_id,captured,transaction_date,transaction_id,company_name,net_amount,settlement_date,bank_reff,tdr,service_tax,payment_method)
 select s.payment_id,s.captured,s.transaction_date,s.transaction_id,m.company_name,t.net_amount,s.settlement_date,s.bank_reff,t.tdr,t.service_tax,t.payment_method 
from payment_transaction_settlement s
inner join payment_transaction_tdr t on s.transaction_id=t.transaction_id
inner join merchant m on t.merchant_id=m.user_id
where s.transaction_date>=_from_date and s.transaction_date<_to_date and s.payment_id like 'cashfree%';

update temp_cf_transaction set swipez_revenue=captured*0.005 where payment_method='NET_BANKING';
update temp_cf_transaction set swipez_revenue=captured*0.005 where payment_method='UPI' and captured>=2000;
update temp_cf_transaction set swipez_revenue=captured*0.0025 where payment_method='UPI' and captured<2000;
update temp_cf_transaction set swipez_revenue=captured*0.0025 where payment_method='CREDIT_CARD';
update temp_cf_transaction set swipez_revenue=captured*0.001,payment_method='Wallet' where payment_method not in ('CREDIT_CARD','DEBIT_CARD','NET_BANKING','UPI');
update temp_cf_transaction set swipez_gst=swipez_revenue*0.18;

select * from temp_cf_transaction;
#select sum(swipez_revenue),sum(swipez_gst) from temp_cf_transaction;

END
