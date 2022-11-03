-- CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_invoicevalues`(_merchant_id char(10),_user_id char(10),_customer_id INT,_invoice_number varchar(45),_template_id varchar(10),invoice_values LONGTEXT,
-- _column_id LONGTEXT,_bill_date date,_due_date date,_bill_cycle_name varchar(100),_narrative nvarchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),
-- _late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_notify_patron INT,_franchise_id INT,_vendor_id INT
-- ,_expiry_date date,_created_by varchar(10),_invoice_type INT,_type INT,_request_type INT,_custom_reminder tinyint(1),_plugin_value longtext,_billing_profile INT)
-- BEGIN 
--  DECLARE EXIT HANDLER FOR SQLEXCEPTION 
-- 		BEGIN
--         SET @message='failed';
--         show errors;
--         	ROLLBACK;
-- 		END; 
-- START TRANSACTION;

-- SET @bc_id='';
-- SET @separator = '~';
-- SET @req_id='';
-- set @pay_req_status=0;
-- SET @separatorLength = CHAR_LENGTH(@separator);
-- SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
-- SET @swipez_total=@invoice_total;
-- SET @convenience_fee=0;
-- SET @grand_total=@invoice_total;
-- SET @parent_request_id='';
-- SET @estimate_number='';
-- SET @autoval=0;

-- if(_billing_profile>0)then
-- 	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where id=_billing_profile;
-- else
-- 	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where merchant_id=_merchant_id and is_default=1;
-- end if;

-- if(_request_type<>2)then
-- SET _request_type=1;
-- end if;

-- if(_type<>4)then
-- 	SET @numstring=SUBSTRING(_invoice_number,1,16);
-- 	if(@numstring='System generated')then
--         if(_invoice_type<>2)then
-- 			if(@autoval=0)then
--             SET @autoval=SUBSTRING(_invoice_number,17);
--             end if;
--             SELECT GENERATE_INVOICE_NUMBER(@autoval) INTO @invoice_number;
--             SET invoice_values=REPLACE(invoice_values, _invoice_number, @invoice_number);
--             SET _invoice_number= @invoice_number;
--         else
--             SET @invoice_number='';
--             SET invoice_values=REPLACE(invoice_values, _invoice_number, @invoice_number);
--             SET _invoice_number= @invoice_number;
--         end if;
-- 	end if;
-- end if;

-- if(_invoice_type=2)then
-- SELECT generate_estimate_number(_merchant_id) INTO @estimate_number;
-- end if;

-- select get_surcharge_amount(_merchant_id ,@invoice_total,0) into @convenience_fee;

-- SET @grand_total=@grand_total-_advance;

-- SELECT `billing_cycle_id` INTO @bc_id FROM `billing_cycle_detail` WHERE `_user_id` = _user_id AND `cycle_name` = _bill_cycle_name LIMIT 1;

-- if(@bc_id='') then
-- select generate_sequence('Billing_cycle_id') into @bc_id;
-- INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`,  `created_by`, `created_date`, 
-- `last_update_by`) VALUES (@bc_id,_user_id,_bill_cycle_name,_created_by,CURRENT_TIMESTAMP(),_created_by);
-- end if;


-- SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @req_id;

-- if(_type=4)then
-- SET @parent_request_id=0;
-- end if;

-- INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`invoice_type`,`payment_request_type`,`template_id`,`invoice_number`,
-- `estimate_number`,`previous_due`,`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`grand_total`,`late_payment_fee`,
-- `advance_received`,`payment_request_status`,`bill_date`,`due_date`,`narrative`,`parent_request_id`,`billing_cycle_id`,`notification_sent`,`notify_patron`,
-- `franchise_id`,`vendor_id`,`expiry_date`,`has_custom_reminder`,`plugin_value`,`gst_number`,`billing_profile_id`,`request_type`,`created_by`,`created_date`,`last_update_by`)
-- VALUES(@req_id,_user_id,_merchant_id,_customer_id,_invoice_type,_type,_template_id,_invoice_number,@estimate_number,_previous_dues,@grand_total,_amount,
-- _tax,@invoice_total,@swipez_total,@convenience_fee,@grand_total,_late_fee,_advance,@pay_req_status,_bill_date,_due_date,_narrative,@parent_request_id,
-- @bc_id,0,_notify_patron,_franchise_id,_vendor_id,_expiry_date,_custom_reminder,_plugin_value,@gst_number,_billing_profile,_request_type,_created_by,
-- CURRENT_TIMESTAMP(),_created_by);

-- WHILE _column_id != '' > 0 DO
--     SET @column_id  = SUBSTRING_INDEX(_column_id, @separator, 1);
--     SET @invoice_values  = SUBSTRING_INDEX(invoice_values, @separator, 1);
 
-- INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
-- values (@req_id,@column_id,@invoice_values,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());
--     SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id) + @separatorLength + 1);
--     SET invoice_values = SUBSTRING(invoice_values, CHAR_LENGTH(@invoice_values) + @separatorLength + 1);
-- END WHILE;

-- if(_type<>4)then
-- update customer set balance = balance + @grand_total where customer_id=_customer_id;

-- INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
-- `ledger_type`,`created_by`,`created_date`,`last_update_by`)
-- VALUES(_customer_id,concat('Invoice for bill date ',_bill_date),@grand_total,1,@req_id,'DEBIT',_created_by,CURRENT_TIMESTAMP(),_created_by);
-- end if;

-- SET @duecolumn_id=0;

-- select column_id into @duecolumn_id from invoice_column_metadata where template_id=_template_id and function_id=4 and is_active=1; 
-- if(@duecolumn_id>0)then
-- SET @due_mode='';
-- select param into @due_mode from column_function_mapping where column_id=@duecolumn_id and function_id=4 and is_active=1;
-- if(@due_mode='auto_calculate')then
-- update payment_request p,contact_ledger l set p.payment_request_status=8, p.expiry_date=SUBDATE(CURDATE(),1),l.is_active=0 where p.merchant_id=_merchant_id and p.customer_id=_customer_id and  p.payment_request_status in(0,5,4) and p.payment_request_id<>@req_id and p.payment_request_id=l.reference_no and p.customer_id=l.customer_id and l.type=1;
-- select sum(amount) into @debit from contact_ledger where customer_id=_customer_id and ledger_type='DEBIT' and is_active=1;
-- select sum(amount) into @credit from contact_ledger where customer_id=_customer_id and ledger_type='CREDIT' and is_active=1;
-- if(@credit>0)then
-- update customer set balance=@debit-@credit where customer_id=_customer_id;
-- else
-- update customer set balance=@debit where customer_id=_customer_id;
-- end if;
-- end if;
-- end if;

-- commit;
-- SET @message = 'success';

-- SELECT 
--     _notify_patron AS 'notify_patron',
--     @req_id AS 'request_id',
--     @message AS 'message';

-- END$$

-- DELIMITER ;