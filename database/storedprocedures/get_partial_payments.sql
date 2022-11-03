CREATE DEFINER=`root`@`localhost` PROCEDURE `get_partial_payments`(_payment_request_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 
set @merchant_id = '';

Drop TEMPORARY  TABLE  IF EXISTS tmppartial_payments;

CREATE TEMPORARY TABLE IF NOT EXISTS tmppartial_payments ( 
`transaction_id` varchar(10) NOT NULL , 
`amount` DECIMAL(11,2), 
`payment_date`  DATETIME null, 
`payment_mode` varchar(20) null,
`mode` varchar(10) null,
PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;
    
insert into tmppartial_payments (transaction_id,amount,payment_date,payment_mode,mode)
select pay_transaction_id,amount,created_date,'Online','Online' from payment_transaction where payment_request_id=_payment_request_id 
and payment_transaction_status=1;

insert into tmppartial_payments (transaction_id,amount,payment_date,mode,payment_mode)
select offline_response_id,amount,created_date,'Offine',offline_response_type from offline_response where payment_request_id=_payment_request_id 
and is_active=1;

update tmppartial_payments s ,  config c  set s.payment_mode = c.config_value where s.payment_mode=c.config_key and c.config_type='offline_response_type'; 

 select * from tmppartial_payments order by payment_date;
         

END
