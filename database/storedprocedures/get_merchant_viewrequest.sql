CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_viewrequest`(_merchant_id char(10),_from_date date, _to_date date,
_user_name varchar(11),_bulk_upload INT , _where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
 		BEGIN
        show errors;
		END;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_request;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_request (
    `payment_request_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `bulk_id` INT NOT NULL,
    `franchise_id` INT NOT NULL,
    `customer_id` int NOT NULL,
    `customer_code` varchar(45)  NULL,
    `mobile` varchar(15)  NULL,
    `customer_group` varchar(45)  NULL default '',
    `invoice_number` varchar(45)  NULL default '',
    `estimate_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 1,
    `billing_cycle_id` varchar(10) NOT NULL,
    `parent_request_id` varchar(10) NOT NULL,
	`converted_request_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `invoice_type` tinyint(1) null default 1,
    `payment_request_type` tinyint(1) null default 1,
    `is_paid` tinyint(1) null default 0,
    `send_date` datetime not null,
    `due_date` DATETIME not null,
    `name` varchar (100) null,
    `status` varchar(25) null,
    `billing_cycle_name` varchar(100) null,
    `short_url` varchar(30) null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
if(_bulk_upload>0)then
	insert into temp_merchant_view_request(payment_request_id,invoice_type,payment_request_type,user_id,bulk_id,customer_id,billing_cycle_id,parent_request_id,converted_request_id,
	absolute_cost,payment_request_status,send_date,due_date,invoice_number,estimate_number,franchise_id,short_url)
	select payment_request_id,invoice_type,payment_request_type,user_id,bulk_id,customer_id,billing_cycle_id,parent_request_id,converted_request_id,absolute_cost,
	payment_request_status,created_date,due_date,invoice_number,estimate_number,franchise_id,short_url from payment_request where merchant_id=_merchant_id and payment_request_status not in (3,8)  and payment_request_type <>4
	and bulk_id=_bulk_upload and is_active=1 and (expiry_date is null or expiry_date>curdate());
else
	insert into temp_merchant_view_request(payment_request_id,invoice_type,payment_request_type,user_id,bulk_id,customer_id,billing_cycle_id,parent_request_id,converted_request_id,
	absolute_cost,payment_request_status,send_date,due_date,invoice_number,estimate_number,franchise_id,short_url)
	select payment_request_id,invoice_type,payment_request_type,user_id,bulk_id,customer_id,billing_cycle_id,parent_request_id,converted_request_id,absolute_cost,
	payment_request_status,created_date,due_date,invoice_number,estimate_number,franchise_id,short_url from payment_request where merchant_id=_merchant_id and payment_request_status not in (3,8) and payment_request_type <>4 
    and DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date and is_active=1 and (expiry_date is null or expiry_date>curdate());
end if;
UPDATE temp_merchant_view_request r,
    customer u
SET
    r.name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_code = u.customer_code,
    r.mobile=u.mobile,
    r.customer_group = u.customer_group
WHERE
    r.customer_id = u.customer_id;

UPDATE temp_merchant_view_request set is_paid=1 where payment_request_status in(1,2,7);  
#UPDATE temp_merchant_view_request set payment_request_status=8 where payment_request_status in(0,4,5) and due_date < current_date(); 
UPDATE temp_merchant_view_request SET invoice_number=estimate_number WHERE  invoice_type =2;
SET @where=REPLACE(_where,'~',"'");
	SET @count=0;
    SET @sql=concat('select count(payment_request_id) into @count from temp_merchant_view_request ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET @fcount=0;
    SET @sql=concat('select count(payment_request_id),sum(absolute_cost) into @fcount,@totalSum from temp_merchant_view_request ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_merchant_view_request ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END