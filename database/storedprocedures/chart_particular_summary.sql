CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_particular_summary`(_user_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
		END;
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_particular_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_particular_summary (
    `p_id` INT NOT NULL AUTO_INCREMENT,
    `template_id` varchar(10) NULL,
    `template_type` varchar(10) NULL,
    `particular_name` varchar(250)  NULL ,
    `amount` decimal(11,2) NOT NULL default 0 ,
    `column_id` INT NOT NULL,
    `invoice_id` INT NOT NULL,
    PRIMARY KEY (`p_id`)) ENGINE=MEMORY;


    insert into temp_particular_summary(template_id,particular_name,column_id,invoice_id) select m.template_id,column_name,m.column_id,v.invoice_id from invoice_column_metadata m  inner join invoice_column_values v on m.column_id=v.column_id inner join payment_request p on v.payment_request_id=p.payment_request_id where m.column_type='PF' and v.created_by=_user_id and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') <= @to_date and p.parent_request_id <> '0'; 
    
    update temp_particular_summary t , invoice_template v  set t.template_type = v.template_type   where t.template_id=v.template_id ;
    update temp_particular_summary t , invoice_column_values v  set t.amount = v.value   where v.value>0 and v.invoice_id=t.invoice_id + 3 and template_type='society' ;
    update temp_particular_summary t , invoice_column_values v  set t.amount = v.value   where v.value>0 and v.invoice_id=t.invoice_id + 2 and template_type='school' ;
    update temp_particular_summary t , invoice_column_values v  set t.amount = v.value   where v.value>0 and v.invoice_id=t.invoice_id + 3 and template_type='hotel' ;
    select particular_name as name,sum(amount) as value from temp_particular_summary where amount>0 group by particular_name order by amount desc;

    Drop TEMPORARY  TABLE  IF EXISTS temp_particular_summary;

commit;
END
