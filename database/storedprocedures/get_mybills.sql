CREATE DEFINER=`root`@`localhost` PROCEDURE `get_mybills`(_user_input varchar(250),_type INT,_merchant_id varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
            show errors;
		END;

SET @count=0;
SET @message='empty';

Drop TEMPORARY  TABLE  IF EXISTS temp_customer_ids;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_ids (
	`ID` INT auto_increment,
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`ID`)) ENGINE=MEMORY;
if(_merchant_id='')then
	if(_type=1) then
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `email`= _user_input;
	elseif(_type=2) then
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `mobile`= _user_input;
	else
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `customer_code`= _user_input;
	end if;
else
	if(_type=1) then
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `email`= _user_input and merchant_id=_merchant_id;
	elseif(_type=2) then
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `mobile`= _user_input and merchant_id=_merchant_id;
	else
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `customer_code`= _user_input and merchant_id=_merchant_id;
	end if;
end if;

select count(`customer_id`) into @count from temp_customer_ids;

if(@count <> 0) then
Drop TEMPORARY  TABLE  IF EXISTS temp_patron_bills;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_bills (
    `payment_request_id` varchar(10) NOT NULL ,
    `merchant_id` char(10) NOT NULL,
    `user_id` char(10) NOT NULL,
    `customer_id` varchar(10) NOT NULL,
    `customer_code` varchar(45) NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `received_date` datetime not null,
    `expiry_date` date null,
    `due_date` DATE not null,
    `name` varchar (100) null default '',
    `display_name` varchar (100) null default '',
    `patron_name` varchar (100) null default '',
    `status` varchar(20) null default '',
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
    if(_merchant_id='')then
		insert into temp_customer_bills(payment_request_id,merchant_id,user_id,customer_id,
		absolute_cost,payment_request_status,received_date,due_date,expiry_date)
		select payment_request_id,merchant_id,user_id,customer_id,absolute_cost,
		payment_request_status,last_update_date,due_date,expiry_date from payment_request where payment_request.customer_id in 
		(select distinct `customer_id` from temp_customer_ids) and payment_request_type<>4 and  payment_request_status in(0,4,5) ;
	else
		insert into temp_customer_bills(payment_request_id,merchant_id,user_id,customer_id,
		absolute_cost,payment_request_status,received_date,due_date,expiry_date)
		select payment_request_id,merchant_id,user_id,customer_id,absolute_cost,
		payment_request_status,last_update_date,due_date,expiry_date from payment_request where merchant_id=_merchant_id and payment_request.customer_id in 
		(select distinct `customer_id` from temp_customer_ids) and payment_request_type<>4 and  payment_request_status in(0,4,5) ;
    end if;
    update temp_customer_bills r , merchant_billing_profile m  set r.name = m.company_name  , r.display_name = m.company_name where r.merchant_id=m.merchant_id and m.is_default=1;
    update temp_customer_bills r , config c  set r.status = c.config_value where r.payment_request_status=c.config_key and c.config_type='payment_request_status';
    update temp_customer_bills r , customer u  set r.patron_name = concat(u.first_name," ",u.last_name),r.customer_code=u.customer_code where r.customer_id=u.customer_id;
    
    delete from temp_customer_bills where expiry_date is not NULL and expiry_date<>'' and expiry_date<NOW();
        
    select count(*) into @count from temp_customer_bills;
    if(@count>0) then
    select * from temp_customer_bills order by payment_request_id desc limit 10;
    else
    select @message as 'message';
    end if;
    else
    select @message as 'message';
end if;
END
