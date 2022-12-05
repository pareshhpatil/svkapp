CREATE DEFINER=`root`@`localhost` PROCEDURE `report_tax_details`(_merchant_id varchar(10),_template_id varchar(10),_from_date date , _to_date date,
_status varchar(50),_column_name longtext,_customer_column_name longtext,_billing_profile_id INT)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @status=_status;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_tax_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_tax_details (
	`invoice_id` varchar(10) NOT NULL ,
	`customer_id` INT NOT NULL,
	`customer_code` varchar(45)  NULL,
	`invoice_number` varchar(45)  NULL default '',
   	`basic_amount` DECIMAL(11,2) not null ,
	`invoice_amount` DECIMAL(11,2) not null ,
    `offline_response_type` INT default 0 not null,
    `merchant_gst_number` char (15) null,
    `billing_profile_id` INT default 0 not null,
	`status` varchar(50) not null,
	`sent_date` datetime not null,
	`bill_date` date not null,
	`due_date` DATE not null,
	`customer_name` varchar (100) null,
     `__Transaction_id` varchar(10)  null,
    `__Transaction_ref_no` varchar(50)  null,
	`__Email` varchar (250) null,
    `__Gst_Number` varchar (20) null,
	`__Mobile` varchar (20) null,
    `__Address` varchar (250) null,
    `__City` varchar (45) null,
    `__State` varchar (45) null,
    `__Zipcode` varchar (20) null,
    `created_by` varchar (10) null,
    `__Settlement_ref_no` varchar(50)  null,
    `__Settlement_date` datetime null,
    `__Transaction_amount` decimal(11,2) default 0 not null,
    `__TDR` decimal(5,2) default 0 not null,
    `__TDR_GST` decimal(5,2) default 0 not null,
    `__Settled_Amount` decimal(11,2) default 0 not null,
    `__Advance_Received` decimal(11,2) default 0 null,
	PRIMARY KEY (`invoice_id`)) ENGINE=MEMORY;

if(_billing_profile_id>0)then

if(_status<>'') then
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date and payment_request_status = _status and template_id=_template_id and billing_profile_id=_billing_profile_id;
		else
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date  and template_id=_template_id and billing_profile_id=_billing_profile_id and payment_request_status<>3;
        end if;
else
if(_status<>'') then
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date and payment_request_status = _status and template_id=_template_id;
		else
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date  and template_id=_template_id and payment_request_status<>3;
        end if;
end if;
        
SET @last='customer_name';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('META__',@column_name);	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_tax_details ADD  ', @col_name , ' varchar(100) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET @sql=concat('update temp_tax_details t , invoice_column_values i   set t.',@col_name,' =i.value  where t.invoice_id=i.payment_request_id and i.column_id=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;

SET @last='customer_name';
WHILE _customer_column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_customer_column_name, @separator, 1);
        SET @col_name=concat('CUST__',@column_name);	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_tax_details ADD  ', @col_name , ' varchar(250) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET @sql=concat('update temp_tax_details t , customer_column_values i   set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
	
	SET @last=@col_name;
    SET _customer_column_name = SUBSTRING(_customer_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;


UPDATE temp_tax_details r,
    customer u 
SET 
    r.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_code = u.customer_code,
    r.__Address = u.address,
    r.__City = u.city,
    r.__State = u.state,
    r.__Zipcode = u.zipcode,
	r.__Mobile = u.mobile,
    r.__Email = u.email,
    r.__Gst_Number = u.gst_number
WHERE
    r.customer_id = u.customer_id;
    

UPDATE temp_tax_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.status = c.config_key
        AND c.config_type = 'payment_request_status';

        
UPDATE temp_tax_details r,
    payment_transaction b 
SET 
    r.__Transaction_ref_no = b.pg_ref_no,r.__Transaction_id=b.pay_transaction_id
    ,r.__Transaction_amount=b.amount
WHERE
    r.invoice_id = b.payment_request_id;   

UPDATE temp_tax_details r,
    offline_response b 
SET 
    r.__Transaction_ref_no = b.bank_transaction_no,r.__Transaction_id=b.offline_response_id,r.offline_response_type=b.offline_response_type
    ,r.__Transaction_amount=b.amount
WHERE
    r.invoice_id = b.payment_request_id;   
UPDATE temp_tax_details r,
    payment_transaction_settlement b 
SET 
    r.__Settlement_ref_no = b.bank_reff,r.__Settlement_date=b.settlement_date,
    r.__TDR=replace(b.tdr,'-',''),r.__TDR_GST=replace(b.service_tax,'-',''),r.__Settled_Amount=b.captured+b.tdr+b.service_tax
WHERE
    r.__Transaction_id = b.transaction_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= @to_date;  
UPDATE temp_tax_details r,
    payment_transaction_settlement b 
SET 
    r.__Settlement_ref_no = b.bank_reff,r.__Settlement_date=b.settlement_date,
    r.__TDR=replace(b.tdr,'-',''),r.__TDR_GST=replace(b.service_tax,'-',''),r.__Settled_Amount=b.captured+b.tdr+b.service_tax
WHERE
    r.__Transaction_ref_no = b.transaction_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= @to_date;  


UPDATE temp_tax_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.offline_response_type = c.config_key AND c.config_type = 'offline_response_type' AND r.offline_response_type>0;
select * from temp_tax_details;
Drop TEMPORARY  TABLE  IF EXISTS temp_tax_details;

END
