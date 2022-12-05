CREATE DEFINER=`root`@`localhost` PROCEDURE `get_staging_customer_list`(_bulk_id INT,_merchant_id varchar(10),_column_name longtext)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_staging_view_customer;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_staging_view_customer (
    `customer_id` varchar(10) NOT NULL ,
    `customer_code` varchar(45) NOT NULL,
    `email` varchar(250) NULL,
    `mobile` varchar(20) NULL,
    `name` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
	`address` varchar(250) NOT NULL,
  	`address2` varchar(250) NOT NULL,
	`city` varchar(50) NOT NULL,
	`state` varchar(50) NOT NULL,
	`zipcode` varchar(50) NOT NULL,
    `password` varchar(20) NULL,
    `gst` varchar(20) NULL,
	`created_date` datetime null,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
   
      
       insert into temp_staging_view_customer(customer_id,customer_code,name,first_name,last_name,email,mobile,address2,address,city,state,zipcode,`password`,`gst`,created_date) 
    select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,address2,address,city,state,zipcode,`password`,`gst_number`,created_date from staging_customer where bulk_id=_bulk_id and merchant_id=_merchant_id;
    
    
    SET @last='zipcode';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_staging_view_customer ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_staging_view_customer t , staging_customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
    

select * from temp_staging_view_customer;
     
     

END
