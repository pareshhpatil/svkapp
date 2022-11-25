CREATE DEFINER=`root`@`localhost` PROCEDURE `swipez_report_month_wise`(_date DATE)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
show errors;
ROLLBACK;
END; 

#Get atom transaction count and sum 

Drop TEMPORARY  TABLE  IF EXISTS temp_rep_monthly;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_rep_monthly (
`user_id` varchar(10) NOT NULL ,
`merchant_id` varchar(10)  NULL ,
`payment_request` INT NULL,
`payment_request_amount` DECIMAL(11,2)  null ,
`payment_transaction` INT NULL,
`payment_transaction_amount` DECIMAL(11,2)  null ,
`xway_request` INT NULL,
`xway_request_amount` DECIMAL(11,2)  null ,
`company_name` varchar(100)  NULL ,
`contact` varchar(20)  NULL ,
PRIMARY KEY (`user_id`)) ENGINE=MEMORY;

Drop TEMPORARY  TABLE  IF EXISTS temp_rep_monthly_req;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_rep_monthly_req (
`user_id` varchar(10) NOT NULL ,
`payment_request` INT NULL,
`payment_request_amount` DECIMAL(11,2)  null ,
PRIMARY KEY (`user_id`)) ENGINE=MEMORY;

insert into temp_rep_monthly (user_id,merchant_id,company_name) select user_id,merchant_id,company_name from merchant;

insert into temp_rep_monthly_req (user_id,payment_request,payment_request_amount) 
select user_id,count(payment_request_id),sum(absolute_cost) 
from payment_request where DATE_FORMAT(bill_date,'%Y-%m') = DATE_FORMAT(_date,'%Y-%m') group by user_id;

update temp_rep_monthly r,`user` u set r.contact=u.mobile_no where r.user_id=u.user_id;

update temp_rep_monthly r , temp_rep_monthly_req c  set r.payment_request = c.payment_request,r.payment_request_amount = c.payment_request_amount where r.user_id=c.user_id;

select * from temp_rep_monthly;


END
