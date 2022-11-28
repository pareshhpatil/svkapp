CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_tax_summary`(_user_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
        show errors;
			ROLLBACK;
		END;
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_tax_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_tax_summary (
    `tax_id` INT NOT NULL AUTO_INCREMENT,
    `column_id` INT NOT NULL,
    `percent_id` INT NOT NULL,
    `applicable_id` INT NOT NULL,
    `amount_id` INT NOT NULL,
    `tax_percentage` decimal(11,2)  NULL default 0,
    `tax_name` varchar(250)  NULL ,
    `applicable` decimal(11,2) NULL default 0,
    `amount` decimal(11,2) NULL default 0,
    PRIMARY KEY (`tax_id`)) ENGINE=MEMORY;

    insert into temp_tax_summary(tax_name,column_id,percent_id,applicable_id,amount_id)
    select column_name,m.column_id,v.invoice_id + 1,v.invoice_id + 2,v.invoice_id + 3 from invoice_column_metadata m  
    inner join invoice_column_values v on m.column_id=v.column_id inner join payment_request p on v.payment_request_id=p.payment_request_id 
    where m.column_type='TF' and v.created_by=_user_id and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') >= @from_date  
    and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') <= @to_date and p.parent_request_id <> '0'; 
    
    update temp_tax_summary t , invoice_column_values v  set t.tax_name = concat(t.tax_name,' (',v.value,'%)') , t.tax_percentage = v.value where t.percent_id=v.invoice_id and v.value>0;
    update temp_tax_summary t , invoice_column_values v  set t.applicable = v.value   where t.applicable_id=v.invoice_id and v.value>0;
    update temp_tax_summary t , invoice_column_values v  set t.amount = v.value   where t.amount_id=v.invoice_id and v.value>0;

    select tax_name as name,sum(amount) as value from temp_tax_summary where tax_name <> '' and tax_percentage <> '' and amount > 0 group by tax_percentage order by amount desc;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_tax_summary;
    
commit;
END
