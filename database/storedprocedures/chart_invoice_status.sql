CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_invoice_status`(_user_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_status;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_status (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `status` varchar(100) not null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    insert into temp_invoice_status(status)select payment_request_status   from payment_request where  DATE_FORMAT(last_update_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= @to_date  and user_id=_user_id and is_active=1 and payment_request_type <> 2 and parent_request_id <> '0';
    
    update temp_invoice_status set `status`=1 where `status`=2; 
    update temp_invoice_status b , config c  set b.status = c.config_value where b.status=c.config_key and c.config_type='payment_request_status';
    update temp_invoice_status set status='Paid' where status='Paid online' ; 
    update temp_invoice_status set status='Pending' where status='Submitted';
    select status as name,count(status) as value from temp_invoice_status group by status;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_status;

commit;
END
