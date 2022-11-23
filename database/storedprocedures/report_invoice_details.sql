CREATE DEFINER=`root`@`localhost` PROCEDURE `report_invoice_details`(_merchant_id varchar(10),_from_date date , _to_date date,_invoice_type INT,
_billing_cycle_id varchar(11),_customer_id int(11), _status varchar(50),_aging_by varchar(50),_column_name longtext,_is_setteled INT,_franchise_id INT,_vendor_id INT,_where longtext,_order longtext,_limit longtext)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @merchant_id=_merchant_id;
SET @billing_cycle_id=_billing_cycle_id;
SET @customer_id=_customer_id;
SET @aging=_aging_by;
SET @status=_status;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

if(_invoice_type=2)then
SET @number_type='estimate_number';
else
SET @number_type='invoice_number';
end if;
set max_heap_table_size = 33554432;
Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_details (
	`invoice_id` varchar(10) NOT NULL ,
	`customer_id` INT NOT NULL,
	`customer_code` varchar(45)  NULL,
	`invoice_number` varchar(45)  NULL default '',
    `customer_group` varchar(45)  NULL default '',
  	`payment_request_type` INT  NULL default 0,
   	`__Type` varchar(20)  NULL default '',
	`display_invoice_no` INT null default 0,
	`invoice_amount` DECIMAL(11,2) not null ,
    `paid_amount` DECIMAL(11,2) not null ,
	`status` varchar(50) not null,
	`billing_cycle_id` varchar(10) NOT NULL,
	`cycle_name` varchar(250)  null,
	`sent_date` datetime not null,
	`bill_date` datetime not null,
	`due_date` DATETIME not null,
	`customer_name` varchar (100) null,
    `franchise_id` INT NULL default 0,
    `vendor_id` INT NULL default 0,
    `billing_profile_id` INT NULL default 0,
    `merchant_gst_number` char (15) null,
	`__Email` varchar (250) null,
	`__Mobile` varchar (20) null,
    `__Address` varchar (250) null,
    `__City` varchar (45) null,
    `__State` varchar (45) null,
    `__Zipcode` varchar (20) null,
    `created_by` varchar (10) null,
    `__Setteled_by` varchar (100) null,
    `__Created_by` varchar (100) null,
    `__Franchise_name` varchar (100) null,
    `__Vendor_name` varchar (100) null,
	PRIMARY KEY (`invoice_id`)) ENGINE=MEMORY;

SET @franchise_search='';
if(_franchise_id>0)then
SET @franchise_search=concat(' and franchise_id=',_franchise_id);
end if;

if(_vendor_id>0)then
SET @franchise_search=concat(@franchise_search,' and vendor_id=',_vendor_id);
end if;

    if(_billing_cycle_id <>'') then

        if(_status<>'') then
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount from payment_request where is_active=1 and merchant_id='",@merchant_id,"' and invoice_type=",_invoice_type," and payment_request_type <> 4 and billing_cycle_id='",@billing_cycle_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,")  ",@franchise_search," and is_active=1;");
        else
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and billing_cycle_id='",@billing_cycle_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1;");
        end if;

    else
        if(_status<>'') then
               SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount) 
                select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,") ",@franchise_search," and is_active=1;");
                else
               SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount) 
                select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1; ");
        end if;
    end if;


    if(_customer_id<>0) then
        if(_status<>'') then
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and customer_id='",@customer_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,") ",@franchise_search," and is_active=1;");
        else
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and customer_id='",@customer_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1;");
        end if;
    end if;

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

SET @last='customer_name';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_invoice_details ADD  ', @col_name , ' varchar(250) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
        SET @sql=concat('update temp_invoice_details t , invoice_column_values i ,invoice_column_metadata m  set t.',@col_name,' =i.value  where t.invoice_id=i.payment_request_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
		
        SET @sql=concat('update temp_invoice_details t , customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and t.',@col_name,' is null and m.column_name=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;


	if(_is_setteled=1)then
		update temp_invoice_details r,offline_response c,user u set  __Setteled_by=concat(u.first_name,' ',u.last_name) where u.user_id=c.created_by and r.status=2 and r.invoice_id=c.payment_request_id and c.merchant_id=_merchant_id;
	end if;
UPDATE temp_invoice_details r,  customer u 
SET 
    r.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_group = u.customer_group,
    r.customer_code = u.customer_code,
    r.__Address = u.address,
    r.__City = u.city,
    r.__State = u.state,
    r.__Zipcode = u.zipcode,
	r.__Mobile = u.mobile,
    r.__Email = u.email
WHERE
    r.customer_id = u.customer_id;

UPDATE temp_invoice_details set `status`=0 where `status`=5;

UPDATE temp_invoice_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.status = c.config_key
        AND c.config_type = 'payment_request_status';
    
UPDATE temp_invoice_details r,
    config c 
SET 
    r.__Type = c.config_value
WHERE
    r.payment_request_type = c.config_key
        AND c.config_type = 'payment_request_type';


UPDATE temp_invoice_details r,  billing_cycle_detail b SET r.cycle_name = b.cycle_name WHERE r.billing_cycle_id = b.billing_cycle_id;

	SELECT COUNT(invoice_number) INTO @inv_count FROM  temp_invoice_details WHERE  invoice_number <> '';
    if(@inv_count>0)then
		update temp_invoice_details set display_invoice_no = 1;
    end if;

	UPDATE temp_invoice_details t, user u SET  t.__Created_by = CONCAT(u.first_name, ' ', u.last_name) WHERE  t.created_by = u.user_id;
    
    UPDATE temp_invoice_details t,  franchise u SET t.__Franchise_name = u.franchise_name WHERE  t.franchise_id = u.franchise_id;
    
  UPDATE temp_invoice_details t, vendor u SET t.__Vendor_name = u.vendor_name WHERE  t.vendor_id = u.vendor_id;
    
SET @where=REPLACE(_where,'~',"'");

	SET @count=0;    
    SET @sql=concat('select count(invoice_id) into @count from temp_invoice_details ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;   
    SET @sql=concat('select count(invoice_id),sum(invoice_amount) into @fcount,@totalSum from temp_invoice_details ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_invoice_details ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_details;

END
