CREATE DEFINER=`root`@`localhost` PROCEDURE `get_invoice_breckup`(_payment_request_id varchar(10),_type varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_breckup;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_breckup (
    `id` int NOT NULL AUTO_INCREMENT ,
    `invoice_id` INT NULL,
    `column_id` INT  NULL,
     `customer_column_id` INT  NULL,
    `value` varchar(500) NULL,
    `is_delete_allow` INT NULL,
    `save_table_name` varchar(45)  NULL,
    `column_name` varchar(500) NULL,
     `default_column_value` varchar(500) NULL,
    `position` varchar(5) NULL,
    `column_type` varchar(5) NULL,
    `column_datatype` varchar(10) NULL,
    `is_mandatory` INT NULL default 0,
    `column_position` INT NULL default 0,
    `sort_order` INT NULL default 0,
    `function_id` INT NULL default 0,
    `column_group_id` varchar(10)  NULL,
    `template_id` varchar(10)  NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    
Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_col_id;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_col_id (
	 `id` int NOT NULL AUTO_INCREMENT ,
    `column_id` INT  NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    
    if(_type='Bulkupload')then
		   insert into temp_invoice_breckup(invoice_id,column_id,sort_order,default_column_value,customer_column_id,value,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,function_id,column_group_id,template_id)  SELECT icv.invoice_id,icv.column_id,sort_order,default_column_value,customer_column_id,icv.value,icm.is_delete_allow,icm.save_table_name,icm.column_name,icm.position,icm.column_type,icm.column_datatype,icm.is_mandatory,icm.column_position,icm.function_id,icm.column_group_id,
	icm.template_id from staging_invoice_values as icv right outer join invoice_column_metadata as icm on icv.column_id = icm.column_id where icv.payment_request_id=_payment_request_id and icm.save_table_name<>'request'  and icv.is_active=1 order by icm.sort_order,icv.invoice_id;
    select template_id into @template_id from staging_payment_request where payment_request_id=_payment_request_id;
    else
		   insert into temp_invoice_breckup(invoice_id,column_id,sort_order,default_column_value,customer_column_id,value,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,function_id,column_group_id,template_id)  SELECT icv.invoice_id,icv.column_id,sort_order,default_column_value,customer_column_id,icv.value,icm.is_delete_allow,icm.save_table_name,icm.column_name,icm.position,icm.column_type,icm.column_datatype,icm.is_mandatory,icm.column_position,icm.function_id,icm.column_group_id,
	icm.template_id from invoice_column_values as icv right outer join invoice_column_metadata as icm on icv.column_id = icm.column_id where icv.payment_request_id=_payment_request_id and icm.save_table_name<>'request'  and icv.is_active=1 order by icm.sort_order,icv.invoice_id;
    select template_id into @template_id from payment_request where payment_request_id=_payment_request_id;
    end if;
    
    
     insert into temp_invoice_col_id(column_id)
     select column_id from temp_invoice_breckup;
    
    insert into temp_invoice_breckup(column_id,default_column_value,sort_order,customer_column_id,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,function_id,column_group_id) 
    SELECT column_id,default_column_value,sort_order,customer_column_id,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,function_id,column_group_id
	 from invoice_column_metadata  where template_id=@template_id and is_active=1 and is_template_column=1 and column_id not in (select column_id from temp_invoice_col_id)   order by sort_order,column_id;
     

    
select * from temp_invoice_breckup order by sort_order,column_id;
END
