CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_bill_transaction`(_userid varchar(10),_from_date date , _to_date date,_user_name varchar(10) ,_status int,_payment_request_type INT,_franchise_id INT,_bulk_id INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 show errors;
		BEGIN
		END; 

Drop TEMPORARY  TABLE  IF EXISTS merchant_view_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS merchant_view_transaction (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NOT NULL ,
    `estimate_request_id` varchar(10) NULL ,
    `payment_request_type` INT NULL default 1,
    `user_id` varchar(10) NOT NULL,
    `franchise_id` INT NOT NULL default 0,
    `customer_id` INT NOT NULL,
    `customer_code` varchar(45)  NULL,
    `invoice_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 0,
    `patron_id` varchar(10) NULL,
    `bulk_id` INT  NULL DEFAULT '0',
    `absolute_cost` DECIMAL(11,2) not null ,
    `received_cost` DECIMAL(11,2) not null DEFAULT '0.00',
    `deduct_amount` DECIMAL(11,2) not null DEFAULT '0.00',
    `unit_price` DECIMAL(11,2) not null DEFAULT '0.00',
    `quantity` int not null default 1,
    `payment_transaction_status` int not null,
    `is_reject` int not null default 0,
    `narrative` varchar(500) null,
    `date` DATETIME not null,
    `last_update_on` DATETIME not null,
    `due_date` DATETIME  null,
    `name` varchar (100) null,
    `is_availed` int not null default 1,
    `mode` varchar (10) null,
    `payment_mode` varchar (20) null,
    `pg_id` INT null default 0,
    `offline_response_type` INT null default 0,
    `status` varchar(250) null,
    `billing_cycle_id` varchar(10) null,
    `cycle_name` varchar(250) null,
     `count` int null,
    
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    
if(_status <> -1)then
	if(_status=2) then
        if(_bulk_id>0)then
        insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,narrative,date,last_update_on,mode,quantity,offline_response_type) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		2,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type from offline_response where is_active=1 and merchant_id=_userid and offline_response_type<>4 and bulk_id=_bulk_id ;
        
        else
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,narrative,date,last_update_on,mode,quantity,offline_response_type) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		2,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type from offline_response where payment_request_type not in(5,2) and is_active=1 and merchant_id=_userid and offline_response_type<>4 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
        end if;
	else
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,patron_id,
		absolute_cost,deduct_amount,unit_price,is_availed,quantity,payment_transaction_status,narrative,date,last_update_on,mode,pg_id,payment_mode) 
		select pay_transaction_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,patron_user_id,amount,deduct_amount,unit_price,is_availed,quantity,
		payment_transaction_status,narrative,created_date,last_update_date,'online',pg_id,payment_mode from payment_transaction where  payment_transaction_status<>0 and payment_request_type not in(5,2) and merchant_id=_userid  and  payment_transaction_status=_status and DATE_FORMAT(created_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(created_date,'%Y-%m-%d') <= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
    end if;
	if(_status=3) then
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,is_reject,narrative,date,last_update_on,mode,quantity,offline_response_type) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		3,offline_response_type,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type from offline_response where payment_request_type not in(5,2) and is_active=1 and merchant_id=_userid and offline_response_type=4 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
	end if;

else

	insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,patron_id,
	absolute_cost,deduct_amount,unit_price,is_availed,quantity,payment_transaction_status,narrative,date,last_update_on,mode,pg_id,payment_mode) 
	select pay_transaction_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,patron_user_id,amount,deduct_amount,unit_price,is_availed,quantity,
	payment_transaction_status,narrative,created_date,last_update_date,'online',pg_id,payment_mode from payment_transaction where payment_transaction_status<>0 and payment_request_type not in(5,2) and merchant_id=_userid  and DATE_FORMAT(created_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(created_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;

	insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
	absolute_cost,deduct_amount,is_availed,payment_transaction_status,is_reject,narrative,date,last_update_on,mode,quantity,offline_response_type) 
	select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
	2,offline_response_type,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type from offline_response where payment_request_type not in(5,2) and is_active=1 and merchant_id=_userid  and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;

end if;
    
    

    update merchant_view_transaction t , payment_request r  set t.due_date = r.due_date ,
    t.payment_request_type=r.payment_request_type,t.bulk_id=r.bulk_id, t.billing_cycle_id=r.billing_cycle_id ,
    t.invoice_number=r.invoice_number,t.franchise_id=r.franchise_id where t.payment_request_id=r.payment_request_id ;
    
UPDATE merchant_view_transaction t,
    customer u 
SET 
    t.name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.customer_code
WHERE
    t.customer_id = u.customer_id;

UPDATE merchant_view_transaction t,
    payment_request p 
SET 
    received_cost = t.absolute_cost - p.convenience_fee
WHERE
    t.payment_request_id = p.payment_request_id
        AND t.payment_transaction_status = 1;
   
UPDATE merchant_view_transaction 
SET 
    received_cost = absolute_cost
WHERE
    payment_transaction_status = 2;
UPDATE merchant_view_transaction 
SET 
    received_cost = 0
WHERE
    payment_transaction_status = 4
        OR payment_transaction_status = 0;
   
UPDATE merchant_view_transaction t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.payment_transaction_status = c.config_key
        AND c.config_type = 'payment_transaction_status';
        
UPDATE merchant_view_transaction t,
    offline_response c 
SET 
    t.status = 'Offline failed'
WHERE
    t.pay_transaction_id = c.offline_response_id
        AND c.transaction_status = 0;
      
      
UPDATE merchant_view_transaction t,
    config c 
SET 
    t.payment_mode = c.config_value
WHERE
    t.offline_response_type = c.config_key
        AND c.config_type = 'offline_response_type'
        AND mode = 'offline';
    
UPDATE merchant_view_transaction SET payment_mode='IPG' where payment_mode is null or payment_mode='';

    
UPDATE merchant_view_transaction t,
    billing_cycle_detail b 
SET 
    t.cycle_name = b.cycle_name
WHERE
    t.billing_cycle_id = b.billing_cycle_id;
   
SELECT 
    COUNT(invoice_number)
INTO @inv_count FROM
    merchant_view_transaction
WHERE
    invoice_number <> '';
    if(@inv_count>0)then
		update merchant_view_transaction set display_invoice_no = 1;
    end if;
    
    if(_franchise_id>0)then
		delete from merchant_view_transaction where franchise_id<>_franchise_id;
    end if;
    
    
if(_user_name<>'') then

		select * from merchant_view_transaction where billing_cycle_id=_user_name  order by last_update_on;

else
		select * from merchant_view_transaction order by last_update_on desc;
end if;

END
