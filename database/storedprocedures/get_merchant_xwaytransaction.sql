CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_xwaytransaction`(_merchant_id varchar(10),_from_date date , _to_date date,_status INT,_franchise_id INT,_type INT,_where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

SET @type=_type;

Drop TEMPORARY  TABLE  IF EXISTS temp_xway_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_xway_transaction (
    `xway_transaction_id` varchar(10) NOT NULL ,
    `amount` DECIMAL(11,2) not null ,
    `status` varchar(50) not null,
    `reference_no` varchar(50) not null,
    `franchise_id` INT not null default 0,
    `payment_request_id` varchar(10) null,
    `form_transaction_id` INT null,
    `franchise_name` varchar(100) null,
    `date` DATETIME not null,
    `customer_name` varchar (100) null,
    `email` varchar (250) null,
	`mobile` varchar (20) null,
    `ref_no` varchar (100) null,
    `payment_mode` varchar (20) null,
    `udf1` varchar (100) null,
    `udf2` varchar (100) null,
    `udf3` varchar (100) null,
    `udf4` varchar (100) null,
    `udf5` varchar (100) null,
    `description` varchar(500) null,
	`narrative` varchar (100) null,
    PRIMARY KEY (`xway_transaction_id`)) ENGINE=MEMORY;
    
    if(_franchise_id>0)then
    
    if(_status<>-1) then
   insert into temp_xway_transaction(xway_transaction_id,amount,status,reference_no,
    date,customer_name,email,mobile,ref_no,franchise_id,udf1,udf2,udf3,udf4,udf5,description,payment_request_id,payment_mode,narrative) 
    select xway_transaction_id,absolute_cost,xway_transaction_status,reference_no,created_date,name,email,phone,pg_ref_no1,franchise_id,LEFT(udf1 , 100),LEFT(udf2 , 100),LEFT(udf3 , 100),LEFT(udf4 , 100),LEFT(udf5 , 100),description,payment_request_id,payment_mode,narrative
    from xway_transaction where xway_transaction_status=_status and merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date and `type`=@type and franchise_id=_franchise_id;
     
    else
    
    insert into temp_xway_transaction(xway_transaction_id,amount,status,reference_no,
    date,customer_name,email,mobile,ref_no,franchise_id,udf1,udf2,udf3,udf4,udf5,description,payment_request_id,payment_mode,narrative) 
    select xway_transaction_id,absolute_cost,xway_transaction_status,reference_no,created_date,name,email,phone,pg_ref_no1,franchise_id,LEFT(udf1 , 100),LEFT(udf2 , 100),LEFT(udf3 , 100),LEFT(udf4 , 100),LEFT(udf5 , 100),description,payment_request_id,payment_mode,narrative
    from xway_transaction where  xway_transaction_status<>0 and merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date  and `type`=@type and franchise_id=_franchise_id;
     
    end if;
    
    else
    if(_status<>-1) then
   insert into temp_xway_transaction(xway_transaction_id,amount,status,reference_no,
    date,customer_name,email,mobile,ref_no,franchise_id,udf1,udf2,udf3,udf4,udf5,description,payment_request_id,payment_mode,narrative) 
    select xway_transaction_id,absolute_cost,xway_transaction_status,reference_no,created_date,name,email,phone,pg_ref_no1,franchise_id,LEFT(udf1 , 100),LEFT(udf2 , 100),LEFT(udf3 , 100),LEFT(udf4 , 100),LEFT(udf5 , 100),description,payment_request_id,payment_mode,narrative
    from xway_transaction where xway_transaction_status=_status and merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date and `type`=@type;
     
    else
    
    insert into temp_xway_transaction(xway_transaction_id,amount,status,reference_no,
    date,customer_name,email,mobile,ref_no,franchise_id,udf1,udf2,udf3,udf4,udf5,description,payment_request_id,payment_mode,narrative) 
    select xway_transaction_id,absolute_cost,xway_transaction_status,reference_no,created_date,name,email,phone,pg_ref_no1,franchise_id,LEFT(udf1 , 100),LEFT(udf2 , 100),LEFT(udf3 , 100),LEFT(udf4 , 100),LEFT(udf5 , 100),description,payment_request_id,payment_mode,narrative
    from xway_transaction where  xway_transaction_status<>0 and merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date  and `type`=@type;
     
    end if;
    
     end if;
	

UPDATE temp_xway_transaction t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.status = c.config_key
        AND c.config_type = 'payment_transaction_status';
        
 UPDATE temp_xway_transaction SET payment_mode='IPG' where payment_mode is null or payment_mode='';
  
UPDATE temp_xway_transaction t,
    franchise c 
SET 
    t.franchise_name = c.franchise_name
WHERE
    t.franchise_id = c.franchise_id;
    
if(_type=2)then
    UPDATE temp_xway_transaction t,
    form_builder_transaction c 
SET 
    t.payment_request_id = c.payment_request_id,t.form_transaction_id = c.id
WHERE
    t.xway_transaction_id = c.transaction_id;
    end if;
    
SET @where=REPLACE(_where,'~',"'");


		
    SET @count=0;    
    SET @sql=concat('select count(xway_transaction_id),sum(amount) into @count,@totalSum from temp_xway_transaction ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;    
    SET @sql=concat('select count(xway_transaction_id),sum(amount) into @fcount,@totalSum from temp_xway_transaction ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_xway_transaction ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
        
Drop TEMPORARY  TABLE  IF EXISTS temp_xway_transaction;

END
