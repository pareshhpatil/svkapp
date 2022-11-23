CREATE DEFINER=`root`@`localhost` PROCEDURE `report_taxdetails_metadata`(_merchant_id varchar(10),_template_id varchar(10),_from_date date , _to_date date,
_status varchar(50))
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @status=_status;
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');
Drop TEMPORARY  TABLE  IF EXISTS temp_col_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_col_details (
	`column_id` INT NOT NULL ,
	PRIMARY KEY (`column_id`)) ENGINE=MEMORY;

        if(_status<>'') then
			insert into temp_col_details(column_id) 
			select distinct column_id from invoice_column_values v inner join payment_request p on v.payment_request_id=p.payment_request_id where  p.merchant_id=_merchant_id and p.is_active=1 and p.payment_request_type <> 4 and DATE_FORMAT(p.created_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(p.created_date,'%Y-%m-%d') <= @to_date and p.payment_request_status = _status and p.template_id=_template_id;
		else
			insert into temp_col_details(column_id) 
			select distinct column_id from invoice_column_values v inner join payment_request p on v.payment_request_id=p.payment_request_id where  p.merchant_id=_merchant_id and p.is_active=1 and p.payment_request_type <> 4 and DATE_FORMAT(p.created_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(p.created_date,'%Y-%m-%d') <= @to_date  and p.template_id=_template_id;
        end if;
SELECT sort_order,column_id,default_column_value,position,is_mandatory,column_group_id,is_active,is_delete_allow,save_table_name,column_datatype,column_position,column_name,column_type,column_group_id,customer_column_id,template_id,function_id 
from invoice_column_metadata  where template_id=_template_id and is_active=1 and column_id in (select column_id from temp_col_details) order by sort_order,column_id;

END
