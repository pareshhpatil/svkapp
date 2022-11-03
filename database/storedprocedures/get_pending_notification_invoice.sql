CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pending_notification_invoice`()
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
        show errors;
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_pending_notification;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_pending_notification (
    `payment_request_id` varchar(10) NOT NULL ,
    `merchant_id` varchar(10) NULL default '',
    `customer_id` INT NOT NULL,
    `payment_request_status` int Null ,
    `email` varchar(250) NOT NULL default '',
    `mobile` varchar(20) NOT NULL default '',
    `merchant_domain` int Null ,
    `absolute_cost` decimal(11,2) NOT NULL default 0,
    `merchant_domain_name` varchar(45) not null default '',
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;

    insert into temp_pending_notification(payment_request_id,merchant_id,customer_id,payment_request_status,absolute_cost)
    select payment_request_id,merchant_id,customer_id,payment_request_status,absolute_cost from payment_request 
    where payment_request_type=1 and notification_sent=0 and notify_patron=1 and payment_request_status in(0,5,4) and due_date>=curdate();

	update temp_pending_notification t, merchant c set t.merchant_domain=c.merchant_domain where t.merchant_id=c.merchant_id; 
   update temp_pending_notification r , config c  set r.merchant_domain_name = c.config_value where r.merchant_domain=c.config_key and c.config_type='merchant_domain' ;

   update temp_pending_notification t, customer c set t.email=c.email, t.mobile=c.mobile where t.customer_id=c.customer_id; 
   
   update temp_pending_notification t,unsubscribe u set t.mobile='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.mobile=t.mobile;
	update temp_pending_notification t,unsubscribe u set t.email='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.email=t.email;
   select * from temp_pending_notification where email <>'' or mobile <>'';
   
   Drop TEMPORARY  TABLE  IF EXISTS temp_pending_notification;

END