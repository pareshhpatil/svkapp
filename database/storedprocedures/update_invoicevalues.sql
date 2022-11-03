CREATE DEFINER=`root`@`localhost` PROCEDURE `update_invoicevalues`(_payment_request_id char(10),_user_id char(10),_customer_id INT,_invoice_number varchar(45),invoice_values LONGTEXT,
_column_id LONGTEXT,_bill_date date,_due_date date,_bill_cycle_name varchar(100),_narrative nvarchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),
_late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_notify_patron INT,_franchise_id INT,_vendor_id INT
,_expiry_date date,_created_by varchar(10),_auto_collect_plan_id INT,_plugin_value longtext,_staging tinyint(1),_billing_profile INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        SET @message='failed';
        show errors;
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @bc_id='';
SET @separator = '~';
SET @req_id=_payment_request_id;
set @pay_req_status=0;
SET @separatorLength = CHAR_LENGTH(@separator);
SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
SET @swipez_total=@invoice_total;
SET @convenience_fee=0;
SET @grand_total=@invoice_total;
SET @parent_request_id='';
SET @estimate_number='';



select get_surcharge_amount(@merchant_id ,@invoice_total,0) into @convenience_fee;

SET @grand_total=@grand_total-_advance;

SELECT `billing_cycle_id` INTO @bc_id FROM `billing_cycle_detail` WHERE `_user_id` = _user_id AND `cycle_name` = _bill_cycle_name LIMIT 1;

if(@bc_id='') then
select generate_sequence('Billing_cycle_id') into @bc_id;
INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`,  `created_by`, `created_date`, 
`last_update_by`) VALUES (@bc_id,_user_id,_bill_cycle_name,_created_by,CURRENT_TIMESTAMP(),_created_by);
end if;

if(_staging=1)then
update staging_payment_request set customer_id=_customer_id,`invoice_number`=_invoice_number,`previous_due`=_previous_dues,`absolute_cost`=@grand_total
,basic_amount=_amount,tax_amount=_tax,invoice_total=@invoice_total,swipez_total=@swipez_total,
convenience_fee=@convenience_fee,grand_total=@grand_total,late_payment_fee=_late_fee,advance_received=_advance,
bill_date=_bill_date,due_date=_due_date,narrative=_narrative,billing_cycle_id=@bc_id,notify_patron=_notify_patron,
franchise_id=_franchise_id,vendor_id=_vendor_id,expiry_date=_expiry_date,plugin_value=_plugin_value,last_update_by=_created_by
where payment_request_id=_payment_request_id;
else
update payment_request set customer_id=_customer_id,`invoice_number`=_invoice_number,`previous_due`=_previous_dues,`absolute_cost`=@grand_total
,basic_amount=_amount,tax_amount=_tax,invoice_total=@invoice_total,swipez_total=@swipez_total,
convenience_fee=@convenience_fee,grand_total=@grand_total,late_payment_fee=_late_fee,advance_received=_advance,
bill_date=_bill_date,due_date=_due_date,narrative=_narrative,billing_cycle_id=@bc_id,notify_patron=_notify_patron,
franchise_id=_franchise_id,vendor_id=_vendor_id,expiry_date=_expiry_date,plugin_value=_plugin_value,last_update_by=_created_by
where payment_request_id=_payment_request_id;
end if;


if(_billing_profile>0)then
	SET @autoval=0;
	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where id=_billing_profile;
    if(_staging=1)then
		update staging_payment_request set gst_number=@gst_number,billing_profile_id=_billing_profile where payment_request_id=_payment_request_id;
    else
		select billing_profile_id into @exist_gst_id from payment_request where payment_request_id=_payment_request_id;
        if(@exist_gst_id<>_billing_profile and @autoval>0)then
			SELECT GENERATE_INVOICE_NUMBER(@autoval) INTO @invoice_number;
			update payment_request set invoice_number=@invoice_number where payment_request_id=_payment_request_id;
            update invoice_column_values v , invoice_column_metadata m set `value`=@invoice_number where v.column_id=m.column_id and m.function_id=9 and v.payment_request_id=_payment_request_id;
        end if;
		update payment_request set gst_number=@gst_number,billing_profile_id=_billing_profile where payment_request_id=_payment_request_id;
    end if;
end if;



WHILE _column_id != '' > 0 DO
    SET @column_id  = SUBSTRING_INDEX(_column_id, @separator, 1);
    SET @invoice_values  = SUBSTRING_INDEX(invoice_values, @separator, 1);
 if(_staging=1)then
 update staging_invoice_values set value=@invoice_values , last_update_by=_created_by where invoice_id=@column_id;
else
 update invoice_column_values set value=@invoice_values , last_update_by=_created_by where invoice_id=@column_id;
end if;

    SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id) + @separatorLength + 1);
    SET invoice_values = SUBSTRING(invoice_values, CHAR_LENGTH(@invoice_values) + @separatorLength + 1);
END WHILE;

select id,amount into @ledger_id,@ledger_amount from contact_ledger where customer_id=_customer_id and reference_no=_payment_request_id;
if(@ledger_id>0)then
update customer set balance = balance - @ledger_amount + @grand_total where customer_id=_customer_id;
update contact_ledger set amount= @grand_total where id=@ledger_id;
else 
update customer set balance = balance + @grand_total where customer_id=_customer_id;
INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
VALUES(_customer_id,concat('Invoice for bill date ',_bill_date),@grand_total,1,@req_id,'DEBIT',_created_by,CURRENT_TIMESTAMP(),_created_by);
end if;

commit;
SET @message = 'success';

SELECT 
    _notify_patron AS 'notify_patron',
    _payment_request_id AS 'request_id',
    @message AS 'message';

END
