CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_list`(_merchant_id varchar(10),_column_name longtext,_where longtext,_order longtext,_limit longtext,_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_customer;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_customer (
    `customer_id` varchar(10) NOT NULL ,
    `customer_code` varchar(45) NOT NULL,
    `email` varchar(250) NULL,
    `mobile` varchar(20) NULL,
    `name` varchar(100) NOT NULL,
    `customer_group` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
	`__Address` varchar(250) NOT NULL,
  	`address2` varchar(250) NOT NULL,
	`__City` varchar(50) NOT NULL,
    `customer_status` INT NULL,
    `payment_status` INT NULL,
	`__State` varchar(50) NOT NULL,
	`__Zipcode` varchar(50) NOT NULL,
    `balance` decimal(11,2) NULL,
	`created_date` datetime null,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
   

		if(_bulk_id>0)then
			insert into temp_merchant_view_customer(customer_id,customer_code,name,first_name,last_name,email,mobile,customer_group,address2,__Address,__City,__State,__Zipcode,created_date,customer_status,payment_status,balance) 
			select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,customer_group,address2,address,city,state,zipcode,created_date,customer_status,payment_status,balance from customer where merchant_id=_merchant_id and bulk_id=_bulk_id and is_active=1;
		else
			insert into temp_merchant_view_customer(customer_id,customer_code,name,first_name,last_name,email,mobile,customer_group,address2,__Address,__City,__State,__Zipcode,created_date,customer_status,payment_status,balance) 
			select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,customer_group,address2,address,city,state,zipcode,created_date,customer_status,payment_status,balance from customer where merchant_id=_merchant_id  and is_active=1;
		end if;
    
    
    SET @last='payment_status';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_merchant_view_customer ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_merchant_view_customer t , customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
    
    

SET @where=REPLACE(_where,'~',"'");
SET @count=0;    
    SET @sql='select count(customer_id) into @totalcount from temp_merchant_view_customer';
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @sql=concat('select count(customer_id) into @count from temp_merchant_view_customer ',@where );
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@totalcount from temp_merchant_view_customer ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
     
     

END
