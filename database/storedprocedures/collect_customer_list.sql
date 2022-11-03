CREATE DEFINER=`root`@`localhost` PROCEDURE `collect_customer_list`(_merchant_id CHAR(10),_template_id char(10), _start int, _limit int, _search nvarchar(45),_group varchar(10),_status varchar(20))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
 		BEGIN
        show errors;
		END;
Drop TEMPORARY  TABLE  IF EXISTS temp_request;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_request (
	`id` int NOT NULL auto_increment,
    `payment_request_id` char(10)  NULL ,
    `customer_id` INT NOT NULL ,
    `customer_code` nvarchar(45) NULL ,
	`first_name` nvarchar(50) NULL ,
    `last_name` nvarchar(50) NULL ,
    `email` nvarchar(250) NULL,
    `mobile` nvarchar(20) NULL,
    `short_url` varchar(45) NULL,
    `customer_group` varchar(45) NULL,
    `bill_date` DATE  NULL ,
    `due_date` DATE NULL,
    `absolute_cost` DECIMAL(11,2) NULL ,
    `payment_mode` varchar(45) NULL ,
    `payment_date` varchar(45) NULL ,
    `transaction_id` char(10) NULL ,
    `paid_amount` DECIMAL(11,2) NULL ,
    `invoice_number` nvarchar(45) NULL,
    `payment_request_status` int  NULL,
    `narrative` nvarchar(500) NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    insert into temp_request (payment_request_id, customer_id, bill_date,due_date,absolute_cost, payment_request_status, invoice_number,narrative,short_url)
    select payment_request_id, customer_id, bill_date,due_date,absolute_cost, payment_request_status, invoice_number ,narrative,short_url
    from payment_request where NOT FIND_IN_SET(payment_request_status, _status) and payment_request_type <> 4 and is_active=1 and merchant_id=_merchant_id  and template_id=_template_id
    order by created_date desc;
    
	update temp_request t , customer c set t.customer_code=c.customer_code, t.mobile=c.mobile,t.first_name=c.first_name,t.last_name=c.last_name,t.email=c.email where t.customer_id=c.customer_id;

	Drop TEMPORARY  TABLE  IF EXISTS temp_collect_customer;
	CREATE TEMPORARY TABLE IF NOT EXISTS temp_collect_customer (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    insert into temp_collect_customer(customer_id)
	select distinct customer_id from temp_request;
    
    update temp_request t,offline_response o set payment_date=settlement_date,transaction_id=offline_response_id,paid_amount=amount,payment_mode=offline_response_type where t.payment_request_id=o.payment_request_id and o.is_active=1 and t.payment_request_status=2;
    update temp_request t,config o set payment_mode=config_value where t.payment_mode=o.config_key and o.config_type='offline_response_type' and t.payment_request_status=2;
	update temp_request set payment_request_status=0 where payment_request_status=4;


	insert into temp_request (customer_id, customer_code,email,mobile, first_name,last_name,customer_group)
    select customer_id, customer_code,email,mobile, first_name,last_name,customer_group 
    from customer where merchant_id=_merchant_id  and is_active=1 and customer_id not in (select customer_id from temp_collect_customer)
    order by last_update_date;
    
	SET @group_search='';
    
    
	    update temp_request t,payment_transaction o set payment_date=o.paid_on,transaction_id=pay_transaction_id,paid_amount=amount,t.payment_mode=o.payment_mode where t.payment_request_id=o.payment_request_id and o.payment_transaction_status=1 ;


	IF(_search <> '') THEN
    if(_group<>'')then
    SET @group_search=concat(" and customer_group like '%",_group,"%' ");
    end if;
	SET @sql=concat("select * from temp_request where (`first_name` LIKE '",concat('%',_search,'%'),"' or `last_name` LIKE '", concat('%',_search,'%') ,"' or invoice_number LIKE '",concat('%',_search,'%') ,"' or mobile LIKE '",concat('%',_search,'%'),"')",@group_search," limit ",_start,',',_limit);
	else
    if(_group<>'')then
    SET @group_search=concat(" customer_group like '%",_group,"%' ");
    end if;
	SET @sql=concat('select * from temp_request',@group_search,' limit ',_start,',',_limit);
    end if;
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

END
