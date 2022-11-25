CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_mobile_template`(_merchant_id char(10),_user_id char(10),_free_sms INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
SET @merchant_id=_merchant_id;
SET @user_id=_user_id;
SET @tax_count=0;
SET @cgst_id=0;
SET @sgst_id=0;
SET @inv_seq=0;
SET @gst_meta=0;
SET @freesms=_free_sms;

select email_id,mobile_no,first_name,last_name into @email_id,@mobile,@first_name,@last_name 
from user where user_id=@user_id;



#invoice template

select count(*) into @tax_count from merchant_tax where merchant_id=@merchant_id;
if(@tax_count=0)then
	INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
	VALUES (@merchant_id, 'CGST@9%', '9.00', '1', '', '1',1, @user_id, now(), @user_id);
	select LAST_INSERT_ID() into @cgst_id;
	INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
	VALUES (@merchant_id, 'SGST@9%', '9.00', '2', '', '1',1, @user_id, now(), @user_id);
	select LAST_INSERT_ID() into @sgst_id;

	INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
	VALUES (@merchant_id, 'IGST@18%', '18.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@2.5%', '2.50', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@2.5%', '2.50', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@5%', '5.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@6%', '6.00', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@6%', '6.00', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@12%', '12.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@14%', '14.00', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@14%', '14.00', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@28%', '28.00', '3', '', '1',1, @user_id, now(), @user_id);
end if;

select count(*) into @inv_seq from merchant_auto_invoice_number where merchant_id=@merchant_id and is_active=1;
if(@inv_seq=0)then
	SET @sequence='INV-';
	INSERT INTO `merchant_auto_invoice_number` (`merchant_id`, `prefix`, `val`, `type`, `is_active`, `created_by`, `created_date`, `last_update_by`) 
	VALUES (@merchant_id, @sequence, '0', '1', '1', @merchant_id, now(), @merchant_id);
	select LAST_INSERT_ID() into @inv_id;
else
	select prefix,auto_invoice_id into @sequence,@inv_id from merchant_auto_invoice_number where merchant_id=@merchant_id and is_active=1 limit 1;
end if;

select count(*) into @gst_meta from customer_column_metadata where merchant_id=@merchant_id and is_active=1 and column_datatype='gst';
if(@gst_meta=0)then
	INSERT INTO `customer_column_metadata`(`merchant_id`,`column_datatype`,`position`,`column_name`,`column_type`,
	`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(@merchant_id,'gst','L','GST number','Custom',
	@merchant_id,CURRENT_TIMESTAMP(),@merchant_id,CURRENT_TIMESTAMP());
end if;

SET @templatename = 'App Invoice';
SET @template_type = 'isp';
SET @main_header_id = '9~10~11~12';
SET @main_header_default = 'Profile~Profile~Profile~Profile';
SET @customer_column = '1~2~3~4';
SET @custom_column = '';
SET @header = 'Invoice No.~Billing cycle name~Bill date~Due date';
SET @position = 'R~R~R~R';
SET @column_type = 'H~H~H~H';
SET @sort = 'MCompany name~MMerchant contact~MMerchant email~MMerchant address~CCustomer code~CCustomer name~CEmail ID~CMobile no~HInvoice No.~HBilling cycle name~HBill date~HDue date';
SET @column_position = '-1~4~5~6';
SET @function_id = '9~-1~5~7';
SET @function_param = 'system_generated~~~bill_date';
SET @function_val = concat(@inv_id,'~~~5');
SET @is_delete = '1~2~2~2';
SET @headerdatatype = 'text~text~date~date';
SET @headertablename = 'metadata~request~request~request';
SET @headermandatory = '2~1~1~1';
SET @particularname = 'Particular';
SET @pc = '{"sr_no":"#","item":"Description","qty":"Quantity","unit_type":"Unit type","rate":"Rate","gst":"GST","discount":"Discount","total_amount":"Absolute cost"}';
SET @pd = '';
SET @td = concat('["',@cgst_id,'","',@sgst_id,'"]');
SET @plugin = '';
SET @tnc = '';
SET @defaultValue = '';
SET @particular_total = 'Sub total';
SET @tax_total = 'Tax total';
SET @ext = '.';
SET @maxposition = '6';

call `template_save`(@templatename,@template_type,@merchant_id,@user_id,@main_header_id,@main_header_default,@customer_column,@custom_column,@header,@position,@column_type,@sort,@column_position,@function_id,@function_param,@function_val,@is_delete,@headerdatatype,@headertablename,@headermandatory,@tnc,@defaultValue,@particular_total,@tax_total,@ext,@maxposition,@pc,@pd,@td,@plugin,0,@user_id,@message,@template_id);


#Contact template
SET @templatename = 'Payment request';
SET @template_type = 'scan';
SET @main_header_id = '9~10~11~12';
SET @main_header_default = 'Profile~Profile~Profile~Profile';
SET @customer_column = '1~2~3~4';
SET @custom_column = '';
SET @header = 'Billing cycle name~Bill date~Due date~Payble amount';
SET @position = 'R~R~R~R';
SET @column_type = 'H~H~H~H';
SET @sort = 'MCompany name~MMerchant contact~MMerchant email~MMerchant address~CCustomer code~CCustomer name~CEmail ID~CMobile no~HBilling cycle name~HBill date~HDue date~HPayble amount';
SET @column_position = '4~5~6~10';
SET @function_id = '-1~5~5~13';
SET @function_param = '~~~';
SET @function_val = '~~~';
SET @is_delete = '2~2~2~1';
SET @headerdatatype = 'text~date~date~money';
SET @headertablename = 'request~request~request~metadata';
SET @headermandatory = '1~1~1~1';
SET @particularname = 'Particular';
SET @pc = '';
SET @pd = '';
SET @td = '';
SET @plugin = '';
SET @tnc = '';
SET @defaultValue = '';
SET @particular_total = 'Sub total';
SET @tax_total = 'Tax total';
SET @ext = '.';
SET @maxposition = '10';
select template_id into @invoicetemplate_id from invoice_template where merchant_id=@merchant_id order by template_id desc limit 1;
call `template_save`(@templatename,@template_type,@merchant_id,@user_id,@main_header_id,@main_header_default,@customer_column,@custom_column,@header,@position,@column_type,@sort,@column_position,@function_id,@function_param,@function_val,@is_delete,@headerdatatype,@headertablename,@headermandatory,@tnc,@defaultValue,@particular_total,@tax_total,@ext,@maxposition,@pc,@pd,@td,@plugin,0,@user_id,@message,@template_id);
select column_id into @column_id from invoice_column_metadata where template_id=@template_id and function_id=13 limit 1;
select column_id into @invoice_no_column_id from invoice_column_metadata where template_id=@invoicetemplate_id and function_id=9 limit 1;

SET @json=concat('{\"payment_request_format_id\":\"',@template_id,'\",\"payment_request_column_id\":\"',@column_id,'\",\"invoice_format_id\":\"',@invoicetemplate_id,'\",\"invoice_no_column_id\":\"',@invoice_no_column_id,'\",\"invoice_seq_id\":\"',@inv_id,'\"}');
INSERT INTO `merchant_data` (`merchant_id`, `user_id`, `key`, `value`,  `created_date`) VALUES (@merchant_id,@user_id, 'MOBILE_FORMAT_DETAILS', @json, now());
if(@freesms>0)then
SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
INSERT INTO `merchant_addon` (`package_id`, `merchant_id`, `package_transaction_id`, `license_bought`, `license_available`, `start_date`, `end_date`, `is_active`, `created_by`, `created_date`, `last_update_by`) 
VALUES ('7', @merchant_id, 'FREE', @freesms, @freesms, curdate(), @end_date, '1', 'System', now(), 'System');
end if;

END
