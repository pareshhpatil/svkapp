CREATE DEFINER=`root`@`localhost` PROCEDURE `report_refund_details`(_merchant_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;

		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_refund;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_refund (
    `refund_id` INT NOT NULL ,
    `transaction_id` varchar(10) NOT NULL ,
    `created_date` datetime null,
    `transaction_date` datetime null,
    `customer_id` INT  NULL,
    `customer_code` varchar(45)  NULL,
    `customer_name` varchar(100)  NULL,
    `transaction_amount` decimal(11,2)  NULL,
    `refund_amount` decimal(11,2) NULL,
    `refund_status` INT NULL,
    `refund_date` datetime null,
    `reason` varchar(250)  NULL,
    PRIMARY KEY (`refund_id`)) ENGINE=MEMORY;
    
    
    insert into temp_payment_refund(refund_id,transaction_id,created_date,transaction_amount,refund_amount,refund_status,refund_date,reason)
	select id,transaction_id,created_date,transaction_amount,refund_amount,refund_status,refund_date,reason from refund_request where merchant_id=_merchant_id 
    and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date;
    
    UPDATE temp_payment_refund t,
    payment_transaction u 
SET 
    t.customer_id = u.customer_id,
    t.transaction_date=u.created_date
WHERE
    t.transaction_id = u.pay_transaction_id;
    
UPDATE temp_payment_refund t,
    customer u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.customer_code
WHERE
    t.customer_id = u.customer_id;
    
    UPDATE temp_payment_refund t,
    xway_transaction u
SET 
    t.customer_name = u.name,
    t.customer_code = u.udf1,
    t.transaction_date=u.created_date
WHERE
    t.transaction_id = u.xway_transaction_id;
    

    select * from temp_payment_refund;
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_refund;

END
