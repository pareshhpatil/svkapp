CREATE DEFINER=`root`@`localhost` PROCEDURE `get_settopbox_list`(_merchant_id varchar(10),_where longtext,_order longtext,_limit longtext)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_settopbox;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_settopbox (
    `id` varchar(10) NOT NULL ,
    `customer_name` varchar(250) NOT NULL,
    `customer_code` varchar(45) NOT NULL,
    `name` varchar(100) NOT NULL,
    `narrative` varchar(250) NULL,
    `cost` decimal(11,2) NULL default 0,
    `status` INT NOT NULL,
   	`updated_date` datetime null,
    `expiry_date` date null,
    `action` varchar(10) null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
   
insert into temp_merchant_view_settopbox(id,updated_date,customer_name,customer_code,name,narrative,cost,status,expiry_date)
select s.id,s.updated_date,concat(c.first_name,' ',c.last_name) as customer_name,c.customer_code,s.name,s.narrative,s.cost,s.status,s.expiry_date 
from customer_service s inner join customer c on c.customer_id=s.customer_id where  s.merchant_id=_merchant_id and s.is_active=1;
    
   SET @where=REPLACE(_where,'~',"'");
   SET @count=0;    
    SET @sql=concat('select count(id) into @count from temp_merchant_view_settopbox ',@where );
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET @sql=concat('select *,@count from temp_merchant_view_settopbox ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
     
     

END
