CREATE DEFINER=`root`@`localhost` PROCEDURE `get_subscription_viewrequest`(_request_id varchar(10),_merchant_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_subscription_request;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_subscription_request (
    `payment_request_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `customer_id` int NOT NULL,
    `customer_code` varchar(45)  NULL,
    `invoice_type` INT  NULL default 1,
    `invoice_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 0,
    `billing_cycle_id` varchar(10) NOT NULL,
	`converted_request_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `paid_amount` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `send_date` datetime not null,
    `due_date` DATETIME not null,
    `name` varchar (100) null,
    `status` varchar(250) null,
    `billing_cycle_name` varchar(40) null,
	`count` int null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
              insert into temp_subscription_request(payment_request_id,user_id,customer_id,invoice_type,billing_cycle_id,converted_request_id,
    absolute_cost,paid_amount,payment_request_status,send_date,due_date,invoice_number) 
    select payment_request_id,user_id,customer_id,invoice_type,billing_cycle_id,converted_request_id,absolute_cost,paid_amount,
    payment_request_status,created_date,due_date,invoice_number from payment_request where merchant_id=_merchant_id and parent_request_id=_request_id and payment_request_type=5 and is_active=1 and payment_request_status <> 3;
    
    update temp_subscription_request r , customer u  set r.name = concat(u.first_name," ",u.last_name),r.customer_code=u.customer_code where r.customer_id=u.customer_id;
    
    update temp_subscription_request r , config c  set r.status = c.config_value where r.payment_request_status=c.config_key and c.config_type='payment_request_status';

    UPDATE temp_subscription_request r,payment_request c SET r.invoice_number = c.estimate_number WHERE r.invoice_type = 2 AND c.payment_request_id = r.payment_request_id; 
	
    UPDATE temp_subscription_request set payment_request_status=8 where payment_request_status in(0,4,5) and due_date < current_date(); 
    
    select count(invoice_number) into @inv_count from temp_subscription_request where invoice_number<>'';
    if(@inv_count>0)then
		update temp_subscription_request set display_invoice_no = 1;
    end if;

  
    select * from temp_subscription_request order by payment_request_id;

END
