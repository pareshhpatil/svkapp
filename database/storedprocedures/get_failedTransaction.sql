CREATE DEFINER=`root`@`localhost` PROCEDURE `get_failedTransaction`(_day INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
            show errors;
		END;
        
SET @date_diff=DATE_ADD(NOW(), INTERVAL -_day DAY);

Drop TEMPORARY  TABLE  IF EXISTS temp_falied_transaction;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_falied_transaction (
    `payment_request_id` varchar(10) NOT NULL ,
    `template_id` varchar(10)  NULL ,
    `user_id` varchar(10)  NULL,
    `merchant_id` varchar(10)  default '',
    `customer_id` INT  NULL,
    `payment_request_status` int Null ,
    `short_url` varchar(100)  NULL default '',
    `email` varchar(250)  NULL default '',
    `mobile` varchar(15)  NULL default '',
    `merchant_domain` int Null ,
    `merchant_domain_name` varchar(45)  null default '',
    `merchant_logo` varchar (100)  null default '',
    `logo` varchar (100)  null default '',
    `pre_email` INT not null default 1,
    `pre_sms` INT not null default 1,
    `sms_gateway` INT not null default 1,
    `sms_gateway_type` INT not null default 1,   
    `name` varchar(250)  null default '',
    `display_name` varchar(250)  null default '',
    `sms_name` varchar(50) default '',
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;


    insert into temp_falied_transaction(payment_request_id,merchant_id,user_id,customer_id)
    select distinct payment_request_id,merchant_id,merchant_user_id,customer_id from payment_transaction where payment_transaction_status in(0,4) and DATE_FORMAT(created_date,'%Y-%m-%d')= DATE_FORMAT(@date_diff,'%Y-%m-%d');
    
    
    update temp_falied_transaction r , payment_request m  set r.short_url=m.short_url,r.template_id = m.template_id,r.payment_request_status=m.payment_request_status  where r.payment_request_id=m.payment_request_id ;

    
    update temp_falied_transaction r , invoice_template m  set r.logo = m.image_path  where r.template_id=m.template_id ;

   update temp_falied_transaction r , merchant m  set r.name = m.company_name,r.display_name = m.display_name,r.merchant_id=m.merchant_id , r.merchant_domain=m.merchant_domain where r.merchant_id=m.merchant_id ;
   update temp_falied_transaction r , merchant_setting m  set r.sms_gateway = m.sms_gateway,r.sms_gateway_type=m.sms_gateway_type,r.sms_name=m.sms_name where r.merchant_id=m.merchant_id ;

   update temp_falied_transaction r , config c  set r.merchant_domain_name = c.config_value where r.merchant_domain=c.config_key and c.config_type='merchant_domain' ;
    

   update temp_falied_transaction r , merchant_landing m set r.merchant_logo = m.logo where r.merchant_id=m.merchant_id and m.logo is not null;

    update temp_falied_transaction t, customer c set t.email=c.email,t.mobile=c.mobile where t.customer_id=c.customer_id; 
   
   
   update temp_falied_transaction  set name = display_name where display_name<>'' or display_name is null;
	update temp_falied_transaction  set sms_name = name where sms_name='' or sms_name is null;
    
        
    update temp_falied_transaction t,unsubscribe u set t.mobile='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.mobile=t.mobile;
	update temp_falied_transaction t,unsubscribe u set t.email='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.email=t.email;

   select * from temp_falied_transaction where payment_request_status in(4,5);


END
