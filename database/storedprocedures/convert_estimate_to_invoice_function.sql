CREATE DEFINER=`swipez_admin`@`%` FUNCTION `convert_estimate_to_invoice_function`(_estimate_request_id varchar(10)) RETURNS char(10) CHARSET latin1
BEGIN

SET @invoice_no_column_id=0;
SET @invoice_number='';
select template_id into @template_id from payment_request where payment_request_id=_estimate_request_id;



select column_id into @invoice_no_column_id from invoice_column_metadata where template_id=@template_id and function_id=9 and is_active=1 limit 1;

if(@invoice_no_column_id>0)then
	select `param`,`value` into @param,@seq_value from column_function_mapping where column_id=@invoice_no_column_id and is_active=1;
    if(@param='system_generated')then
		SELECT GENERATE_INVOICE_NUMBER(@seq_value) INTO @invoice_number;
    end if;
end if;

SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @req_id;

INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`created_by`,
`created_date`,`last_update_by`)
SELECT @req_id,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,1,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
@invoice_number,`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`
FROM `payment_request` where payment_request_id=_estimate_request_id;


INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
select @req_id,`column_id`,`value`,`is_active`,`created_by`,CURRENT_TIMESTAMP(),`last_update_by`,CURRENT_TIMESTAMP()
from invoice_column_values where payment_request_id=_estimate_request_id;

INSERT INTO `invoice_particular`
(`payment_request_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_particular` where payment_request_id=_estimate_request_id and is_active=1;

INSERT INTO `invoice_tax`
(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_tax` where payment_request_id=_estimate_request_id and is_active=1;


update payment_request set payment_request_status=6,converted_request_id=@req_id where payment_request_id=_estimate_request_id;

if(@invoice_no_column_id>0)then
	update invoice_column_values set `value`=@invoice_number where column_id=@invoice_no_column_id and payment_request_id=@req_id;
end if;
RETURN @req_id;

END
