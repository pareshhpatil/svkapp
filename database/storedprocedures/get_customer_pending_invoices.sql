CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_pending_invoices`(_customer_id INT,_start_date DATETIME)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


Drop TEMPORARY  TABLE  IF EXISTS temp_customer_pending_bill;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_pending_bill (
    `payment_request_id` varchar(10)  NULL ,
    `merchant_user_id` varchar(10)  NULL,
	`merchant_id` varchar(10) NOT NULL,
  	`merchant_domain` varchar(45) NULL,
    `customer_id` int NOT NULL,
    `customer_name` varchar(100)  NULL,
    `email` varchar(250)  NULL,
    `mobile` varchar(250)  NULL,
    `absolute_cost` DECIMAL(11,2)  null ,
    `payment_request_type` int null,
    `pg_id` int  null,
    `fee_id` int  null,
    `franchise_id` int  null,
    `vendor_id` int  null,
    `mid` varchar(100)  NULL,
    `paytm_key` varchar(100)  NULL,
    `post_url` varchar(250)  NULL,
    `txn_url` varchar(250)  NULL,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
    insert into temp_customer_pending_bill (payment_request_id,merchant_user_id,customer_id,absolute_cost,payment_request_type,franchise_id,vendor_id)
    select payment_request_id,user_id,customer_id,absolute_cost,payment_request_type,franchise_id,vendor_id from payment_request 
    where customer_id=_customer_id and payment_request_status in(0,4,5) and payment_request_type<>4 and created_date>_start_date ;
    
    update temp_customer_pending_bill r , customer u  set r.customer_name = concat(u.first_name," ",u.last_name),r.email=u.email,r.mobile=u.mobile where r.customer_id=u.customer_id;
        
    update temp_customer_pending_bill r , merchant u  set r.merchant_id = u.merchant_id,r.merchant_domain=u.merchant_domain where r.merchant_user_id=u.user_id;
    update temp_customer_pending_bill r , merchant_fee_detail u , payment_gateway p  set r.fee_id = u.fee_detail_id,r.pg_id = u.pg_id,r.mid=p.pg_val2,r.paytm_key=p.pg_val1,r.post_url=p.pg_val5,r.txn_url=p.req_url where r.merchant_id=u.merchant_id and p.pg_id=u.pg_id and p.pg_type=5 and p.is_active=1 and u.is_active=1;
	
    update temp_customer_pending_bill r , config u  set r.merchant_domain=u.config_value where r.merchant_domain=u.config_key and u.config_type='merchant_domain';

    select * from temp_customer_pending_bill;
    
     
     

END
