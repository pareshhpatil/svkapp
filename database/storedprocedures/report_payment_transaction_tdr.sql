CREATE DEFINER=`root`@`localhost` PROCEDURE `report_payment_transaction_tdr`(_userid varchar(10),_from_date date , _to_date date,_column_name longtext,_franchise_id INT,_where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;

		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_transaction_tdr;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_transaction_tdr (
    `tdr_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NULL ,
    `franchise_id` int NOT NULL default 0,
    `transaction_id` varchar(10) NOT NULL ,
    `patron_id` varchar(10)  NULL,
    `patron_name` varchar(100)  NULL,
    `payment_id` varchar(50)  NULL,
    `payment_method` varchar(45)  NULL,
    `bank_reff` varchar(45)  NULL,
    `auth_date` datetime,
    `cap_date` datetime,
    `captured` DECIMAL(11,2)  null ,
    `refunded` DECIMAL(11,2)  null ,
    `chargeback` DECIMAL(11,2)  null ,
    `tdr` DECIMAL(11,2)  null ,
    `service_tax` DECIMAL(11,2)  null ,
    `surcharge` DECIMAL(11,2)  null ,
    `surcharge_service_tax` DECIMAL(11,2)  null ,
    `emitdr` DECIMAL(11,2)  null ,
    `emi_service_tax` DECIMAL(11,2)  null ,
    `net_amount` DECIMAL(11,2)  null ,
    PRIMARY KEY (`tdr_id`)) ENGINE=MEMORY;
    
    

    
      insert into temp_payment_transaction_tdr(tdr_id,payment_request_id,transaction_id,patron_id,patron_name,payment_id,payment_method,bank_reff,auth_date,
	cap_date,captured,refunded,chargeback,tdr,service_tax,surcharge,surcharge_service_tax,emitdr,emi_service_tax,net_amount) select tdr_id,payment_request_id,transaction_id,patron_id,patron_name,payment_id,payment_method,bank_reff,auth_date,
	cap_date,captured,refunded,chargeback,tdr,service_tax,surcharge,surcharge_service_tax,emitdr,emi_service_tax,net_amount from payment_transaction_tdr where DATE_FORMAT(cap_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(cap_date,'%Y-%m-%d')<= @to_date and merchant_id=_userid;

	
SET @last='net_amount';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_payment_transaction_tdr ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_payment_transaction_tdr t , invoice_column_values i ,invoice_column_metadata m  set t.',@col_name,' =i.value  where t.payment_request_id=i.payment_request_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
	
    
   

    if(_franchise_id>0)then
    update temp_payment_transaction_tdr t,payment_request r set t.franchise_id=r.franchise_id where t.payment_request_id=r.payment_request_id;
UPDATE temp_payment_transaction_tdr t,
    event_request r 
SET 
    t.franchise_id = r.franchise_id
WHERE
    t.payment_request_id = r.event_request_id;
UPDATE temp_payment_transaction_tdr t,
    xway_transaction r 
SET 
    t.franchise_id = r.franchise_id
WHERE
    t.transaction_id = r.xway_transaction_id;
    end if;
    
    SET @where=REPLACE(_where,'~',"'");

    SET @fcount=0;    
    SET @sql=concat('select count(tdr_id),count(tdr_id),sum(captured) into @fcount,@count,@totalSum from temp_payment_transaction_tdr ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_payment_transaction_tdr ',@where,' ' ,_order,' ',_limit);
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_transaction_tdr;

END
