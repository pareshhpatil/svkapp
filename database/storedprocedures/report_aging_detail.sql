CREATE DEFINER=`root`@`localhost` PROCEDURE `report_aging_detail`(_user_id varchar(10),_from_date date , _to_date date,_customer_id varchar(10),_aging_by varchar(50))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
        show errors;
			ROLLBACK;
		END;
START TRANSACTION;
SET @aging = _aging_by;
SET @user_id = _user_id;
SET @customer_id = _customer_id;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(NOW(),'%Y-%m-%d');
set max_heap_table_size = 33554432;
Drop TEMPORARY  TABLE  IF EXISTS temp_aging_detail;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_aging_detail (
    `aging_id` INT NOT NULL AUTO_INCREMENT,
    `customer_id` INT  NULL ,
    `customer_code` varchar(45)  NULL,
    `email` varchar(250)  NULL,
    `mobile` varchar(20)  NULL,
	`invoice_number` varchar(45)  NULL default '',
	`display_invoice_no` INT null default 0,
    `customer_name` varchar(100) NULL,
    `date` datetime not null,
    `payment_request_id` char(10)  NULL,
    `status` varchar(20) NOT NULL,
    `age` varchar(20) not NULL,
    `amount` DECIMAL(11,2) NULL default 0,
    `balance_due` DECIMAL(11,2) NULL default 0,
    PRIMARY KEY (`aging_id`)) ENGINE=MEMORY;

    if(@customer_id<>0)then
    SET @sql=concat("insert into temp_aging_detail(payment_request_id,customer_id,date,status,amount,balance_due,age,invoice_number) select payment_request_id,customer_id,",@aging,",payment_request_status,grand_total,grand_total-paid_amount,concat(DATEDIFF(NOW(),",@aging,"),' days'),invoice_number from payment_request where payment_request_status in(0,4,5) and payment_request_type<>4 and user_id='",@user_id,"' and customer_id='",@customer_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and is_active=1;");
    else
    SET @sql=concat("insert into temp_aging_detail(payment_request_id,customer_id,date,status,amount,balance_due,age,invoice_number) select payment_request_id,customer_id,",@aging,",payment_request_status,grand_total,grand_total-paid_amount,concat(DATEDIFF(NOW(),",@aging,"),' days'),invoice_number from payment_request where payment_request_status in(0,4,5) and payment_request_type<>4 and user_id='",@user_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and is_active=1;");
    end if;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    
    update temp_aging_detail b , customer u  set b.customer_name = concat(u.first_name,' ',u.last_name),b.customer_code=u.customer_code,b.email=u.email,b.mobile=u.mobile  where b.customer_id=u.customer_id ;
    update temp_aging_detail b , config c  set b.status = c.config_value where b.status=c.config_key and c.config_type='payment_request_status';
	select count(invoice_number) into @inv_count from temp_aging_detail where invoice_number<>'';
    if(@inv_count>0)then
		update temp_aging_detail set display_invoice_no = 1;
    end if;

    select * from temp_aging_detail where customer_id is not null ;

    Drop TEMPORARY  TABLE  IF EXISTS temp_aging_detail;
commit;
END
