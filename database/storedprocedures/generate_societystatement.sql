CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_societystatement`(_payment_request_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 
set @merchant_id = '';

Drop TEMPORARY  TABLE  IF EXISTS societystatement;

CREATE TEMPORARY TABLE IF NOT EXISTS societystatement ( 
`payment_request_id` varchar(10) NOT NULL , 
`patron_id` varchar(10) NOT NULL,  
`user_id` varchar(10) NOT NULL,
`billing_cycle_id` varchar(10) NOT NULL, 
`request_amt` DECIMAL(11,2), 
`narrative` varchar(500) null,
`transaction_amt` DECIMAL(11,2),
`request_date` DATETIME null,
`payment_date`  DATETIME null, 
`transaction_id` varchar(10) null,
`payment_type_id` int null ,
`payment_mode` varchar(10) null,

PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
 select user_id,patron_id into @merchant_id,@patron_id from payment_request 
where payment_request_id=_payment_request_id ;


insert into societystatement (payment_request_id,patron_id,user_id,billing_cycle_id,request_amt,narrative,request_date)
select payment_request_id,patron_id,user_id,billing_cycle_id,absolute_cost,narrative,bill_date from payment_request where patron_id=@patron_id 
and user_id=@merchant_id and (payment_request_type<>4 or parent_request_id<> '0' ) order by last_update_date desc limit 5;

update societystatement s , payment_transaction pt set s.transaction_id = pt.pay_transaction_id , s.transaction_amt = pt.amount , s.payment_date = pt.last_update_date, s.payment_type_id = '0' , s.payment_mode = 'online' where s.payment_request_id = pt.payment_request_id; 

update societystatement s , offline_response op set s.transaction_id = op.offline_response_id , s.transaction_amt = op.amount , s.payment_date = op.last_update_date, s.payment_type_id = op.offline_response_type where s.payment_request_id = op.payment_request_id and op.is_active=1;

update societystatement s ,  config c  set s.payment_mode = c.config_value where s.payment_type_id=c.config_key and c.config_type='offline_response_type'; 

   
   select * from societystatement ;
         

END
