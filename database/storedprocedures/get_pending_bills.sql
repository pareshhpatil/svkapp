CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pending_bills`(_days INT,_request_type INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
            show errors;
		END;
SET @date_match= DATE_ADD(CURDATE(), INTERVAL _days DAY);
SET @date_diff=DATE_ADD(NOW(), INTERVAL -24 HOUR);

Drop TEMPORARY  TABLE  IF EXISTS temp_pendings_bills;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_pendings_bills (
    `payment_request_id` varchar(10) NOT NULL ,
    `template_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `merchant_id` varchar(10) NULL default '',
    `customer_id` INT NOT NULL,
    `payment_request_status` int Null ,
    `payment_transaction_status` int Null ,
    `short_url` varchar(100)  NULL default '',
    `late_fee` decimal(11,2) Null ,
    `absolute_cost` decimal(11,2) Null ,
    `email` varchar(250) NOT NULL default '',
    `mobile` varchar(15)  NULL default '',
    `is_reminder` int Null default 1,
	`sms_gateway` int Null default 1,
    `sms_available` int Null default 0,
    `sms_gateway_type` int Null default 1,
    `sent_date` DATE not null,
    `due_date` DATE not null,
    `merchant_domain` int Null ,
    `merchant_domain_name` varchar(45)  null default '',
    `narrative` varchar (500)  null default '',
    `pre_email` INT not null default 1,
	`pre_sms` INT not null default 1,
    `name` varchar(250)  null default '',
    `company_name` varchar(250)  null default '',
    `reminder_day` INT NULL  default 0,
    `custom_subject` varchar(250) default '',
    `custom_sms` varchar(200) default '',
    `sms_name` varchar(250) default '',
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
    insert into temp_pendings_bills(payment_request_id,template_id,user_id,customer_id,payment_request_status,sent_date,due_date,late_fee,short_url,narrative,absolute_cost)
    select payment_request_id,template_id,user_id,customer_id,payment_request_status,last_update_date,due_date,late_payment_fee,short_url,narrative,absolute_cost 
    from payment_request where payment_request_status in(0,4,5) and absolute_cost>0 and notify_patron=1 and payment_request_type<>4 
    and DATE_FORMAT(created_date,'%Y-%m-%d')< @date_diff and DATE_FORMAT(due_date,'%Y-%m-%d')=@date_match and has_custom_reminder=0 and request_type=_request_type;




   update temp_pendings_bills r , merchant m  set r.name = m.company_name,r.company_name = m.company_name,r.merchant_id=m.merchant_id 
   , r.merchant_domain=m.merchant_domain where r.user_id=m.user_id ;
   
   update temp_pendings_bills r , merchant_setting m  set r.sms_gateway = m.sms_gateway,r.is_reminder=m.is_reminder,r.sms_gateway_type=m.sms_gateway_type ,r.sms_name=m.sms_name where r.merchant_id=m.merchant_id ;
   
   update temp_pendings_bills r , config c  set r.merchant_domain_name = c.config_value where r.merchant_domain=c.config_key and c.config_type='merchant_domain' ;
    
   update temp_pendings_bills t, payment_transaction c set t.payment_transaction_status=c.payment_transaction_status where t.payment_request_id=c.payment_request_id and c.payment_transaction_status=1; 

   update temp_pendings_bills t, customer c set t.email=c.email, t.mobile=c.mobile where t.customer_id=c.customer_id; 
  
	delete from temp_pendings_bills where payment_transaction_status=1;
  
	update temp_pendings_bills  set name = company_name where name='' or name is null;
	update temp_pendings_bills  set sms_name = name where sms_name='' OR sms_name is null;
    
    update temp_pendings_bills t,unsubscribe u set t.mobile='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.mobile=t.mobile;
	update temp_pendings_bills t,unsubscribe u set t.email='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.email=t.email;

	update temp_pendings_bills t,merchant_addon m set t.sms_available=1 where t.merchant_id=m.merchant_id and package_id=7 and license_available>0 and m.is_active=1 and end_date>curdate();

    select * from temp_pendings_bills where is_reminder=1;


END
