CREATE DEFINER=`root`@`localhost` PROCEDURE `report_customer_balance`(_user_id varchar(10),_from_date date , _to_date date,_customer_id varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
		END;
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_customer_balance;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_balance (
    `payment_request_id` varchar(10) NOT NULL ,
    `customer_id` INT NOT NULL ,
    `payment_request_type` INT NOT NULL,
    `customer_name` varchar(250) NULL,
    `invoice_balance` DECIMAL(11,2) not null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    if(_customer_id<>'')then
    insert into temp_customer_balance(payment_request_id,customer_id,invoice_balance,payment_request_type) select payment_request_id,customer_id,grand_total ,payment_request_type from payment_request where  payment_request_status in(0,4,5) and DATE_FORMAT(due_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(due_date,'%Y-%m-%d') <= @to_date  and payment_request_type <> 4 and user_id=_user_id and customer_id=_customer_id;
    else
    insert into temp_customer_balance(payment_request_id,customer_id,invoice_balance,payment_request_type) select payment_request_id,customer_id,grand_total,payment_request_type from payment_request where payment_request_status in(0,4,5) and DATE_FORMAT(due_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(due_date,'%Y-%m-%d') <= @to_date and payment_request_type <> 4  and user_id=_user_id;
    end if;

    update temp_customer_balance b , customer u  set b.customer_name = concat(u.first_name,' ',u.last_name)  where b.customer_id=u.customer_id ;
    select * from temp_customer_balance;

    Drop TEMPORARY  TABLE  IF EXISTS temp_customer_balance;

commit;
END
