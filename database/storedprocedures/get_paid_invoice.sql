CREATE DEFINER=`root`@`localhost` PROCEDURE `get_paid_invoice`(_user_id varchar(10),_from_date datetime , _to_date datetime)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
show errors;
ROLLBACK;
END; 


SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_paid_invoice_details;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_paid_invoice_details (
`transaction_id` varchar(10) NOT NULL ,
`invoice_id` varchar(10) NOT NULL ,
`customer_id` INT NOT NULL,
`amount` DECIMAL(11,2) not null ,
`is_late` INT not null default 0 ,
`late_fee` DECIMAL(11,2) not null default 0 ,
`status` varchar(50) not null,
`paid_date` datetime not null,
`email` varchar (250) null,
`code` varchar (250) null,
`customer_name` varchar (100) null,
`payment_mode` varchar (45) null,
PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;


insert into temp_paid_invoice_details(transaction_id,invoice_id,customer_id,amount,is_late,status,paid_date,`payment_mode`) 
select pay_transaction_id,payment_request_id,customer_id,amount,late_payment,payment_transaction_status,last_update_date,5 
from payment_transaction where merchant_user_id=_user_id and paid_on >= @from_date  and paid_on <= @to_date and payment_transaction_status=1; 

insert into temp_paid_invoice_details(transaction_id,invoice_id,customer_id,amount,is_late,status,paid_date,`payment_mode`) 
select offline_response_id,payment_request_id,customer_id,amount,0,1,settlement_date,offline_response_type 
from offline_response where merchant_user_id=_user_id and settlement_date >= @from_date  and settlement_date <= @to_date and is_active=1;


UPDATE temp_paid_invoice_details r,
    customer p 
SET 
    r.customer_name = CONCAT(p.first_name, ' ', p.last_name),
    r.code = p.customer_code,
    r.email=p.email
WHERE
    r.customer_id = p.customer_id;
    
UPDATE temp_paid_invoice_details r,
    payment_request c 
SET 
    r.late_fee = c.late_payment_fee
WHERE
    r.invoice_id = c.payment_request_id
        AND r.is_late = 1;

UPDATE temp_paid_invoice_details t,
    config c 
SET 
    t.payment_mode = c.config_value
WHERE
    t.payment_mode = c.config_key
        AND c.config_type = 'offline_response_type';
        
SELECT 
    customer_name,
    email,
    code,
    transaction_id,
    invoice_id,
    amount,
    late_fee,
    paid_date,
    payment_mode
FROM
    temp_paid_invoice_details;


END
