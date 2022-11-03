CREATE DEFINER=`root`@`localhost` PROCEDURE `savebulkUpload_invoice`(_bulk_upload_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @inv_count=0;


    
SELECT 
    COUNT(bulk_upload_id)
INTO @count FROM
    bulk_upload
WHERE
    bulk_upload_id = _bulk_upload_id;
if(@count>0) then

SELECT `invoice_number`,`merchant_id`,template_id into @invoice_numstring,@merchant_id,@template_id FROM staging_payment_request where bulk_id=_bulk_upload_id limit 1;

SET @numstring=SUBSTRING(@invoice_numstring,1,16);
if(@numstring='System generated')then

	SET @autoval=SUBSTRING(@invoice_numstring,17);

	INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`created_by`,
`created_date`,`last_update_by`)
SELECT `staging_payment_request`.`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,`invoice_type`,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
generate_invoice_number(@autoval),`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`
FROM `staging_payment_request` where bulk_id=_bulk_upload_id;

	UPDATE staging_invoice_values s,
    payment_request p 
SET 
    s.value = p.invoice_number
WHERE
    s.payment_request_id = p.payment_request_id
        AND s.value = @invoice_numstring;

else
	SET @fun_count=0;
		SELECT 
    COUNT(column_id)
INTO @fun_count FROM
    invoice_column_metadata
WHERE
    template_id = @template_id
        AND function_id = 9;
	if(@fun_count>0)then
		select count(s.payment_request_id) into @inv_count from payment_request p inner join staging_payment_request s on  s.invoice_number=p.invoice_number and s.merchant_id=p.merchant_id
		where  p.invoice_number<>'' and s.bulk_id=_bulk_upload_id and s.invoice_number is not null;    
	end if;
 
 if(@inv_count=0)then
	INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`created_by`,
`created_date`,`last_update_by`)
SELECT `staging_payment_request`.`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,`invoice_type`,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
`invoice_number`,`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`
FROM `staging_payment_request` where bulk_id=_bulk_upload_id;
	end if;
end if;


INSERT INTO `invoice_particular`
(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`unit_type`,`qty`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT `payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`unit_type`,`qty`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `staging_invoice_particular` where bulk_id=_bulk_upload_id and is_active=1;

INSERT INTO `invoice_tax`
(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT `payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `staging_invoice_tax` where bulk_id=_bulk_upload_id and is_active=1;




if(@inv_count=0)then
	 INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
	 SELECT s.`payment_request_id`,s.`column_id`,s.`value`,s.`is_active`,s.`created_by`,CURRENT_TIMESTAMP(),s.`last_update_by`,CURRENT_TIMESTAMP()
	FROM `staging_invoice_values` s inner join staging_payment_request on s.payment_request_id=staging_payment_request.payment_request_id inner join bulk_upload b on b.bulk_upload_id= staging_payment_request.bulk_id
	where b.bulk_upload_id=_bulk_upload_id;


	UPDATE bulk_upload 
SET 
    status = 5
WHERE
    bulk_upload_id = _bulk_upload_id;
    
	
    
    
update customer c,payment_request p set c.balance = balance + p.grand_total where c.customer_id=p.customer_id and p.bulk_id=_bulk_upload_id and p.is_active=1;

INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
select customer_id,concat('Invoice for bill date ',bill_date),grand_total,1,payment_request_id,'DEBIT',created_by,CURRENT_TIMESTAMP(),created_by from payment_request where bulk_id=_bulk_upload_id and is_active=1;

    SET @duecolumn_id=0;

select column_id into @duecolumn_id from invoice_column_metadata where template_id=@template_id and function_id=4 and is_active=1; 
if(@duecolumn_id>0)then
SET @due_mode='';
select param into @due_mode from column_function_mapping where column_id=@duecolumn_id and function_id=4 and is_active=1;
end if;
	commit;
else
	ROLLBACK;
    SET @message ='Invoice number already exist';
SELECT @message AS 'message';
end if;
SELECT 
    payment_request_id,notify_patron,due_date,has_custom_reminder,template_id,customer_id,merchant_id,user_id,plugin_value,@due_mode as 'due_mode'
FROM
    payment_request where bulk_id=_bulk_upload_id;
end if;
END