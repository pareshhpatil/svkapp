CREATE DEFINER=`root`@`localhost` PROCEDURE `get_staging_viewrequest`(_bulk_id int,_userid varchar(10),_where longtext,_order longtext,_limit longtext)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS import_view_request;

CREATE TEMPORARY TABLE IF NOT EXISTS import_view_request (
	`id` INT not null auto_increment,
    `payment_request_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `customer_id` INT NOT NULL,
    `customer_code` varchar(45) NULL,
    `billing_cycle_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `bill_date` date not null,
    `due_date` DATETIME not null,
    `name` varchar (100) null default '',
    `status` varchar(250) null,
    `billing_cycle_name` varchar(40) null,
     `count` int null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
              insert into import_view_request(payment_request_id,user_id,customer_id,billing_cycle_id,
    absolute_cost,payment_request_status,bill_date,due_date) 
    select payment_request_id,user_id,customer_id,billing_cycle_id,absolute_cost,
    payment_request_status,bill_date,due_date from staging_payment_request where bulk_id=_bulk_id and user_id=_userid  and payment_request_status in (0,4) ;
    
    update import_view_request r , customer u  set r.name = concat(u.first_name," ",u.last_name),r.customer_code=u.customer_code where r.customer_id=u.customer_id;
    
    update import_view_request r , config c  set r.status = c.config_value where r.payment_request_status=c.config_key and c.config_type='payment_request_status';
    
    update import_view_request r , billing_cycle_detail b  set r.billing_cycle_name = b.cycle_name where r.billing_cycle_id=b.billing_cycle_id ;
   
    
SET @where=REPLACE(_where,'~',"'");

SET @count=0;    
    SET @sql=concat('select count(id) into @count from import_view_request ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count from import_view_request ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

END
