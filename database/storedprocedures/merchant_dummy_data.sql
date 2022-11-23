CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_dummy_data`(_merchant_id char(10),_user_id char(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
SET @merchant_id=_merchant_id;
SET @user_id=_user_id;

select email_id,mobile_no,first_name,last_name into @email_id,@mobile,@first_name,@last_name from user where user_id=@user_id;

INSERT INTO `customer`
(`merchant_id`,`customer_code`,`first_name`,`last_name`,`email`,`mobile`,`address`,`city`,`state`,`zipcode`,`created_by`,`created_date`
,`last_update_by`)
VALUES(@merchant_id,'cust-1','Customer','Name',@email_id,@mobile,'','','','',@user_id,CURRENT_TIMESTAMP(),@user_id);

#added taxes
INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, 'CGST@9%', '9.00', '1', '', '1',1, @user_id, now(), @user_id);
select LAST_INSERT_ID() into @cgst_id;
INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, 'SGST@9%', '9.00', '2', '', '1',1, @user_id, now(), @user_id);
select LAST_INSERT_ID() into @sgst_id;

INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, 'IGST@18%', '18.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@2.5%', '2.50', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@2.5%', '2.50', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@5%', '5.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@6%', '6.00', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@6%', '6.00', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@12%', '12.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@14%', '14.00', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@14%', '14.00', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@28%', '28.00', '3', '', '1',1, @user_id, now(), @user_id);

SET @sequence='INV-';
INSERT INTO `merchant_auto_invoice_number` (`merchant_id`, `prefix`, `val`, `type`, `is_active`, `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, @sequence, '0', '1', '1', @merchant_id, now(), @merchant_id);
select LAST_INSERT_ID() into @inv_id;

INSERT INTO `customer_column_metadata`(`merchant_id`,`column_datatype`,`position`,`column_name`,`column_type`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(@merchant_id,'gst','L','GST number','Custom',
@merchant_id,CURRENT_TIMESTAMP(),@merchant_id,CURRENT_TIMESTAMP());

SET @templatename = 'Invoice';
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
SET @pc = '{"sr_no":"#","item":"Description","sac_code":"Sac Code","description":"Time period","gst":"GST","total_amount":"Absolute cost"}';
SET @pd = '["Particular"]';
SET @td = concat('["',@cgst_id,'","',@sgst_id,'"]');
SET @plugin = '';
SET @tnc = '';
SET @defaultValue = '';
SET @particular_total = 'Sub total';
SET @tax_total = 'Tax total';
SET @ext = '.';
SET @maxposition = '6';

call `template_save`(@templatename,@template_type,@merchant_id,@user_id,@main_header_id,@main_header_default,@customer_column,@custom_column,@header,@position,@column_type,@sort,@column_position,@function_id,@function_param,@function_val,@is_delete,@headerdatatype,@headertablename,@headermandatory,@tnc,@defaultValue,@particular_total,@tax_total,@ext,@maxposition,@pc,@pd,@td,@plugin,0,@user_id,@message,@template_id);
update invoice_template set image_path='demo-logo.png' where template_id=@template_id;

END
