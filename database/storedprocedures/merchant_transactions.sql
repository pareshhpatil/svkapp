CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_transactions`(_merchant_id CHAR(10), _start int, _limit int, _search varchar(45),_group varchar(10))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
 		BEGIN
        show errors;
		END;
Drop TEMPORARY  TABLE  IF EXISTS temp_transaction;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_transaction (
    `payment_request_id` char(10) NOT NULL ,
    `customer_id` INT NOT NULL ,
    `customer_code` varchar(45) NULL ,
	`name` nvarchar(100) NULL ,
    `mobile` varchar(20) NULL,
    `bill_date` DATE NOT NULL ,
    `transaction_date` DATETIME NULL,
    `amount` DECIMAL(11,2) NULL ,
    `invoice_number` varchar(45) NULL,
    `payment_mode` varchar(45) NULL,
    `transaction_id` char(10) NULL,
    `payment_request_status` int NOT NULL,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
    IF(_search <> '' or _group<>'') THEN
    Drop TEMPORARY  TABLE  IF EXISTS temp_filter_customer;
	CREATE TEMPORARY TABLE IF NOT EXISTS temp_filter_customer (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    SET @group_id=0;
    if(_group <> '')then
	SET @group_id=_group;
    end if;
    
    if(_search <> '')then
    insert into temp_filter_customer (customer_id)
	select customer_id from customer where merchant_id=_merchant_id and `customer_group` LIKE concat('%{',@group_id,'}%') and (`first_name` LIKE concat('%',_search,'%') or `last_name` LIKE concat('%',_search,'%') or customer_code LIKE concat('%',_search,'%') or mobile LIKE concat('%',_search,'%') );
    else
     insert into temp_filter_customer (customer_id)
	select customer_id from customer where merchant_id=_merchant_id and `customer_group` LIKE concat('%{',@group_id,'}%');
 
    end if;
	SET @sql=concat('insert into temp_transaction (payment_request_id, customer_id, bill_date, payment_request_status, invoice_number)select payment_request_id, p.customer_id, bill_date, payment_request_status, invoice_number from payment_request p inner join temp_filter_customer c on c.customer_id=p.customer_id  where payment_request_status in (1, 2) and merchant_id="', _merchant_id , '"  order by last_update_date desc limit ',_start,',',_limit);
	else
	SET @sql=concat('insert into temp_transaction (payment_request_id, customer_id, bill_date, payment_request_status, invoice_number)select payment_request_id, customer_id, bill_date, payment_request_status, invoice_number from payment_request where payment_request_status in (1, 2) and merchant_id="', _merchant_id , '"  order by last_update_date desc limit ',_start,',',_limit);
    end if;
    
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
	update temp_transaction t , payment_transaction p set t.amount=p.amount, t.transaction_id=p.pay_transaction_id, t.payment_mode=p.payment_mode, t.transaction_date=p.created_date where t.payment_request_id=p.payment_request_id and t.payment_request_status=1;
	update temp_transaction t , offline_response o set t.amount=o.amount, t.transaction_id=o.offline_response_id, t.payment_mode=o.offline_response_type, t.transaction_date=o.created_date where t.payment_request_id=o.payment_request_id and t.payment_request_status=2;
    update temp_transaction t , customer c set t.customer_code=c.customer_code, t.mobile=c.mobile, t.name=concat(c.first_name, ' ',c.last_name) where t.customer_id=c.customer_id;
    update temp_transaction t , config c set t.payment_mode=c.config_value where t.payment_mode=c.config_key and c.config_type='offline_response_type' and t.payment_request_status=2;

	select * from temp_transaction;

END
