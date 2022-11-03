CREATE DEFINER=`root`@`localhost` PROCEDURE `report_payment_received`(_merchant_id varchar(10),_from_date date , _to_date date,_customer_id INT,_column_name longtext,_base_where longtext,_where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
        show errors;
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_received (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NOT NULL ,
    `patron_id` varchar(10) NULL,
    `customer_id` INT NULL,
    `customer_group` varchar(45)  NULL default '',
    `customer_code` varchar(45)  NULL,
    `invoice_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 0,
    `amount` DECIMAL(11,2) not null ,
    `late_payment` varchar(5) not null DEFAULT '0',
    `received_cost` DECIMAL(11,2) not null DEFAULT '0.00',
    `deduct_amount` DECIMAL(11,2) not null DEFAULT '0.00',
    `status` varchar(50) not null,
    `billing_cycle_id` varchar(10) NULL,
	`cycle_name` varchar(250)  null,
    `date` DATETIME not null,
    `customer_name` varchar (100) null,
    `mode` varchar (20) null,
    `ref_no` varchar (45) null,
	`franchise_id` INT  NULL default 0,
    `vendor_id` INT  NULL default 0,
    `__Email` varchar (250) null,
	`__Mobile` varchar (20) null,
	`__Narrative` varchar (500) null,
    `__Franchise_name` varchar (100) null,
    `__Vendor_name` varchar (100) null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    
    if(_customer_id<>0) then
   insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,late_payment,status,date,mode,ref_no,__Narrative) select pay_transaction_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,late_payment,
    payment_transaction_status,created_date,'online',pg_ref_no,narrative from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id and 
customer_id=_customer_id  and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,status,date,mode,__Narrative) 
    select offline_response_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,offline_response_type,created_date,'offline',narrative from offline_response where merchant_id=_merchant_id and customer_id=_customer_id and is_active=1 and transaction_status=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    else
    
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,late_payment,status,date,mode,ref_no,__Narrative) select pay_transaction_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,late_payment,
    payment_transaction_status,created_date,'online',pg_ref_no,narrative from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,status,date,mode,__Narrative) 
    select offline_response_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,offline_response_type,created_date,'offline',narrative from offline_response where merchant_id=_merchant_id and is_active=1 and transaction_status=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    end if;
	
SET @last='ref_no';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_payment_received ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_payment_received t , invoice_column_values i ,invoice_column_metadata m  set t.',@col_name,' =i.value  where t.payment_request_id=i.payment_request_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
	
      
UPDATE temp_payment_received t,
    customer u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_group = u.customer_group,
    t.customer_code = u.customer_code,
    t.__Mobile = u.mobile,
    t.__Email = u.email
WHERE
    t.customer_id = u.customer_id;
UPDATE temp_payment_received t,
    user u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.user_id,
    t.__Email = u.email_id
WHERE
    t.patron_id = u.user_id
        AND customer_id = 0;
    
    UPDATE temp_payment_received 
SET 
    late_payment = 'Yes'
WHERE
         late_payment = '1';
         
         UPDATE temp_payment_received 
SET 
    late_payment = 'No'
WHERE
         late_payment = '0';
    
UPDATE temp_payment_received t,
    payment_request p 
SET 
    received_cost = p.grand_total - p.convenience_fee
WHERE
    t.payment_request_id = p.payment_request_id
        AND t.status = 1;
UPDATE temp_payment_received t,
    payment_request p 
SET 
    t.billing_cycle_id = p.billing_cycle_id,
    t.invoice_number = p.invoice_number,
    t.franchise_id = p.franchise_id,
    t.vendor_id=p.vendor_id,
    t.__Narrative=p.narrative
WHERE
    t.payment_request_id = p.payment_request_id;
    
    
UPDATE temp_payment_received t,
    event_request p 
SET 
    t.cycle_name = p.event_name,t.franchise_id=p.franchise_id,t.vendor_id=p.vendor_id
WHERE
    t.payment_request_id = p.event_request_id;
UPDATE temp_payment_received 
SET 
    received_cost = amount
WHERE
    status = 2;
    
	UPDATE temp_payment_received t,
    offline_response c 
SET 
    t.ref_no = c.bank_transaction_no
WHERE
    t.status = 1
        AND t.pay_transaction_id = c.offline_response_id;
UPDATE temp_payment_received t,
    offline_response c 
SET 
    t.ref_no = c.cheque_no
WHERE
    t.status = 2
        AND t.pay_transaction_id = c.offline_response_id;

UPDATE temp_payment_received t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.status = c.config_key
        AND c.config_type = 'payment_transaction_status'
        AND mode = 'online';
UPDATE temp_payment_received t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.status = c.config_key
        AND c.config_type = 'offline_response_type'
        AND mode = 'offline';
UPDATE temp_payment_received r,
    billing_cycle_detail b 
SET 
    r.cycle_name = b.cycle_name
WHERE
    r.billing_cycle_id = b.billing_cycle_id;
    
    
UPDATE temp_payment_received t,
    offline_response u 
SET 
    t.status = concat('Cheque ','(',u.cheque_status,')')
WHERE
    t.pay_transaction_id = u.offline_response_id and u.offline_response_type=2 and (cheque_status is not null and cheque_status<>'');
    
    
    
    
    
UPDATE temp_payment_received t,
    franchise u 
SET 
    t.__Franchise_name = u.franchise_name
WHERE
    t.franchise_id = u.franchise_id;
    
UPDATE temp_payment_received t,
    vendor u 
SET 
    t.__Vendor_name = u.vendor_name
WHERE
    t.vendor_id = u.vendor_id;

	SELECT 
    COUNT(invoice_number)
INTO @inv_count FROM
    temp_payment_received
WHERE
    invoice_number <> '';
    if(@inv_count>0)then
		update temp_payment_received set display_invoice_no = 1;
    end if;

    SET @where=REPLACE(_where,'~',"'");
	SET @where=REPLACE(@where,'WHERE  where',"where");
   	SET @where=REPLACE(@where,'WHERE  where',"where");
    SET @basewhere=REPLACE(_base_where,'~',"'");
    SET @basewhere=REPLACE(@basewhere,'WHERE  where',"where");


    SET @count=0;    
    SET @sql=concat('select count(pay_transaction_id),sum(amount) into @count,@totalSum from temp_payment_received ',@basewhere);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;    
    SET @sql=concat('select count(pay_transaction_id) ,sum(amount) into @fcount,@totalSum from temp_payment_received ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_payment_received ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

END
