CREATE DEFINER=`root`@`localhost` PROCEDURE `saveSubscription_invoice`(_payment_request_id LONGTEXT, _due_date LONGTEXT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;
Drop TEMPORARY  TABLE  IF EXISTS sendingInvoiceEmail;

CREATE TEMPORARY TABLE IF NOT EXISTS sendingInvoiceEmail (
    `request_id` varchar(10) NOT NULL ,
    `parent_request_id` varchar(10) NOT NULL ,
    `due_date` date NULL,
     `customer_id` INT  NOT NULL,
	`user_id` varchar(10)  NOT NULL,
     `merchant_id` varchar(10)  NULL,
	`template_id` varchar(10)  NULL,
    `merchant_type` int  NULL,
    `company_name` varchar(250)  NULL,
    `display_name` varchar(250)  default '',
    `sms_gateway_type` int Null default 1,
    `sms_gateway` int Null default 1,
    `profile_id` INT  NOT NULL default 0,
    `image` varchar(200)  NULL,
    `plugin_value` varchar(1000)  NULL,
    `merchant_domain` INT  NULL,
    `merchant_domain_name` varchar(45)  NULL,
    `grand_total` decimal(11,2)  NULL,
    `email` varchar(250)  NULL,
	`franchise_id` INT  NULL,
    `has_custom_reminder` INT NULL,
    `mobile` varchar(13)  NULL,
    `sms_name` varchar(50) default '',
    PRIMARY KEY (`request_id`)) ENGINE=MEMORY;
    
    
    SET @separator = '~';
    SET @separatorLength = CHAR_LENGTH(@separator);
    SET @estimate_number='';

    WHILE _payment_request_id != '' > 0 DO
    SET @payment_request_id  = SUBSTRING_INDEX(_payment_request_id, @separator, 1);
	SET @due_date  = SUBSTRING_INDEX(_due_date, @separator, 1);
SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @req_id;
    
SELECT 
    `invoice_number`,
    `user_id`,
    `merchant_id`,
    `invoice_type`,
    `customer_id`,
    `template_id`,
    `billing_cycle_id`,
    absolute_cost - previous_due,
    `basic_amount`,
    `advance_received`,
    `tax_amount`,
    invoice_total - previous_due,
    swipez_total - previous_due,
    previous_due,
    `convenience_fee`,
    grand_total - previous_due,
    `late_payment_fee`,
    `payment_request_status`,
    `narrative`,
    `notify_patron`,
    `franchise_id`,
    `vendor_id`,
	has_custom_reminder,
    billing_profile_id

INTO @invoice_numstring , @_user_id,@merchant_id,@invoice_type , @_customer_id ,   @_template_id , @_billing_cycle_id , @_absolute_cost ,
 @_basic_amount ,@_advance, @_tax_amount , @_invoice_total , @_swipez_total , @previous_due , @_convenience_fee , @_grand_total ,
 @_late_payment_fee , @_payment_request_status , @_narrative , @_notify_patron , @franchise_id ,@vendor_id,@has_custom_reminder,@billing_profile_id
     FROM
    payment_request
WHERE
    payment_request_id = @payment_request_id;


SELECT 
	`mode`,
    `repeat_every`,
    `end_date`,
    `end_mode`,
    carry_due,
    billing_period_start_date,
    billing_period_duration,
    billing_period_type
INTO @smode,@repeat_every,@end_date,@end_mode, @carry_due , @period_start_date , @period , @period_type FROM
    subscription
WHERE
    payment_request_id = @payment_request_id;

if(@invoice_type=1)then
   
	if(@previous_due=0)then
		if(@carry_due=1)then
        update payment_request p , payment_transaction t set p.payment_request_status=1 where p.payment_request_id=t.payment_request_id and p.parent_request_id=@payment_request_id and t.payment_transaction_status=1;
		select sum(invoice_total) into @previous_due from payment_request where parent_request_id=@payment_request_id and payment_request_status in (0,5,4) and (expiry_date is null or expiry_date > CURDATE());
UPDATE payment_request 
SET 
    expiry_date = CURDATE()
WHERE
    parent_request_id = @payment_request_id
        AND payment_request_status IN (0 , 5, 4)
        AND (expiry_date IS NULL
        OR expiry_date > CURDATE());
		end if;
        else
			update payment_request set absolute_cost=absolute_cost-previous_due,invoice_total=invoice_total-previous_due,grand_total=grand_total-previous_due,swipez_total=swipez_total-previous_due,previous_due=0 where payment_request_id=@payment_request_id;
	  end if;
end if;


 if(@previous_due>0)then
    SET @previous_due=@previous_due;
    else
    SET @previous_due=0;
    end if;

SET @_absolute_cost=@_absolute_cost + @previous_due;
SELECT GET_SURCHARGE_AMOUNT(@merchant_id, @_absolute_cost, 0) INTO @_convenience_fee;

SET @autoval=0;

if(@billing_profile_id>0)then
	select invoice_seq_id into @autoval from merchant_billing_profile where id=@billing_profile_id;
end if;

if(@invoice_type=2)then
SELECT generate_estimate_number(@merchant_id) INTO @estimate_number;
SET @invoice_number='';
else
SET @numstring=SUBSTRING(@invoice_numstring,1,16);
if(@numstring='System generated')then
	if(@autoval=0)then
		SET @autoval=SUBSTRING(@invoice_numstring,17);
    end if;
SELECT GENERATE_INVOICE_NUMBER(@autoval) INTO @invoice_number;
else
    SET @invoice_number=@invoice_numstring;
end if;
    
    end if;
    
    INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`document_url`,`created_by`,
`created_date`,`last_update_by`)
SELECT @req_id,`user_id`,`merchant_id`,`customer_id`,5,`invoice_type`,`template_id`,`billing_cycle_id`,
@_absolute_cost,@_basic_amount,@_tax_amount,@_invoice_total + @previous_due,@_swipez_total+@previous_due,@_convenience_fee,
`late_payment_fee`,@_grand_total+@previous_due,@previous_due,`advance_received`,`paid_amount`,
@invoice_number,`estimate_number`,`payment_request_status`,CURDATE(),@due_date,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,@payment_request_id,`notify_patron`,`notification_sent`,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`document_url`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`
FROM `payment_request` where payment_request_id = @payment_request_id;
    
 INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
 SELECT @req_id,s.`column_id`,s.`value`,s.`is_active`,s.`created_by`,CURRENT_TIMESTAMP(),s.`last_update_by`,CURRENT_TIMESTAMP()
FROM `invoice_column_values` s  where s.payment_request_id = @payment_request_id;

INSERT INTO `invoice_particular`
(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_particular` where payment_request_id = @payment_request_id;

INSERT INTO `invoice_tax`
(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_tax` where payment_request_id = @payment_request_id;


INSERT INTO sendingInvoiceEmail(`request_id`,`parent_request_id`,`due_date`,`template_id`,`customer_id`,`user_id`,`grand_total`,`franchise_id`,`has_custom_reminder`,plugin_value,profile_id)
SELECT `payment_request_id`,@payment_request_id,@due_date,`template_id`,`customer_id`,`user_id`,@_absolute_cost+@_convenience_fee,franchise_id,@has_custom_reminder,plugin_value,billing_profile_id FROM `payment_request`  
where payment_request_id = @req_id and notify_patron=1;

if(@smode=1)then
SET @nextbill_date= DATE_ADD(CURDATE(), INTERVAL @repeat_every DAY);
elseif(@smode=2)then
SET @nextbill_date= DATE_ADD(CURDATE(), INTERVAL @repeat_every*7 DAY);
elseif(@smode=3)then
SET @nextbill_date= DATE_ADD(CURDATE(), INTERVAL @repeat_every MONTH);
else
SET @nextbill_date= DATE_ADD(CURDATE(), INTERVAL @repeat_every YEAR);
end if;

if(@end_mode<>1 and @end_date<@nextbill_date)then
SET @nextbill_date=CURDATE();
end if;

UPDATE subscription 
SET 
    last_sent_date = CURDATE(),
    next_bill_date=@nextbill_date,
    last_updated_date = CURRENT_TIMESTAMP()
WHERE
    payment_request_id = @payment_request_id;
    
if(@period_start_date is not NULL)then
SET  @total_req_sent=0;
SELECT 
    COUNT(payment_request_id) - 1
INTO @total_req_sent FROM
    payment_request
WHERE
    parent_request_id = @payment_request_id;
SET @from_int=@total_req_sent*@period;
if(@period_type='month')then
SET @period_from_date= DATE_ADD(@period_start_date,INTERVAL @from_int MONTH);
SET @period_to_date= DATE_ADD(@period_from_date,INTERVAL @period MONTH);
SET @period_to_date= DATE_ADD(@period_to_date,INTERVAL -1 DAY);
else
SET @period_from_date= DATE_ADD(@period_start_date,INTERVAL @from_int DAY);
SET @period_to_date= DATE_ADD(@period_from_date,INTERVAL @period DAY);
end if;
SET @period_from_date=DATE_FORMAT(@period_from_date,'%d %b %Y');
SET @period_to_date=DATE_FORMAT(@period_to_date,'%d %b %Y');
UPDATE invoice_column_values v,
    invoice_column_metadata m 
SET 
    `value` = CONCAT(@period_from_date,
            ' To ',
            @period_to_date)
WHERE
    v.payment_request_id = @req_id
        AND m.function_id = 10
        AND v.column_id = m.column_id;
end if;
UPDATE invoice_column_values v,
    invoice_column_metadata m 
SET 
    `value` = @previous_due
WHERE
    v.payment_request_id = @req_id
        AND m.function_id = 4
        AND v.column_id = m.column_id;

UPDATE invoice_column_values 
SET 
    `value` = @invoice_number
WHERE
    payment_request_id = @req_id
        AND `value` = @invoice_numstring;
SET _payment_request_id = SUBSTRING(_payment_request_id, CHAR_LENGTH(@payment_request_id) + @separatorLength + 1);
    SET _due_date = SUBSTRING(_due_date, CHAR_LENGTH(@due_date) + @separatorLength + 1);
    

update customer set balance = balance + @_grand_total where customer_id=@_customer_id;

INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@_customer_id,concat('Invoice for bill date ',curdate()),@_grand_total,1,@req_id,'DEBIT',@_user_id,CURRENT_TIMESTAMP(),@_user_id);
       

call `stock_management`(@merchant_id,@req_id,3,1);
END WHILE;
	
UPDATE sendingInvoiceEmail r,
    merchant m 
SET 
    r.merchant_id = m.merchant_id,
    r.merchant_domain = m.merchant_domain,
    r.merchant_type = m.merchant_type
WHERE
    r.user_id = m.user_id;
    

UPDATE sendingInvoiceEmail r,
    config c 
SET 
    r.merchant_domain_name = c.config_value
WHERE
    r.merchant_domain = c.config_key
        AND c.config_type = 'merchant_domain';
        

call `stock_management`(@merchant_id,@req_id,3,1);

    

    
commit;
SELECT 
    *
FROM
    sendingInvoiceEmail;
END
