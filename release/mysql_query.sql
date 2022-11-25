
 
 -- MySQL Workbench Synchronization
-- Generated: 2022-08-24 22:33
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Shuhaid

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

ALTER TABLE `swipez`.`invoice_travel_particular` 
ADD COLUMN `description` VARCHAR(500) NULL DEFAULT NULL AFTER `type`,
ADD COLUMN `information` VARCHAR(500) NULL DEFAULT NULL AFTER `description`,
CHANGE COLUMN `booking_date` `booking_date` DATETIME NULL DEFAULT NULL ,
CHANGE COLUMN `journey_date` `journey_date` DATETIME NULL DEFAULT NULL ;

ALTER TABLE `swipez`.`invoice_template` 
ADD COLUMN `setting` TEXT NULL DEFAULT NULL AFTER `footer_note`;

ALTER TABLE `swipez`.`invoice_template_mandatory_fields` 
ADD COLUMN `seq` INT(11) NOT NULL DEFAULT '0' AFTER `is_mandatory`;

ALTER TABLE `swipez`.`staging_invoice_travel_particular` 
ADD COLUMN `description` TEXT NULL DEFAULT NULL AFTER `type`,
ADD COLUMN `information` TEXT NULL DEFAULT NULL AFTER `description`,
CHANGE COLUMN `booking_date` `booking_date` DATETIME NULL DEFAULT NULL ,
CHANGE COLUMN `journey_date` `journey_date` DATETIME NULL DEFAULT NULL ;

ALTER TABLE `swipez`.`booking_calendars` 
ADD COLUMN `cancellation_type` VARCHAR(45) NOT NULL DEFAULT '0' AFTER `cancellation_policy`,
ADD COLUMN `cancellation_days` VARCHAR(45) NOT NULL DEFAULT '0' AFTER `cancellation_type`,
ADD COLUMN `cancellation_hours` VARCHAR(45) NOT NULL DEFAULT '0' AFTER `cancellation_days`;


USE `swipez`;
DROP procedure IF EXISTS `swipez`.`getinvoice_details`;

DELIMITER $$
USE `swipez`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getinvoice_details`(_merchant_id char(10), _paymentrequest_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
	show errors;
		BEGIN
		END; 

SET @payment_req=_paymentrequest_id;
set @patron_id='';
set @merchant_id='';
SET @patron_user_id='';
SET @domain='';
SET @display_url='';
SET @pg_count=0;
SET @main_company_name='';
SET @customer_id=0;
SET @registration_number='';

SELECT 
    invoice_number,
    invoice_total,
    grand_total,
    advance_received,
    swipez_total,
    DATE_FORMAT(bill_date, '%d %b %Y'),
    previous_due,
    basic_amount,
    tax_amount,
    narrative,
    absolute_cost,
    late_payment_fee,
    expiry_date,
    DATE_FORMAT(due_date, '%d %b %Y'),
    customer_id,
    merchant_id,
    user_id,
    billing_cycle_id,
    payment_request_status,
    payment_request_type,
    converted_request_id,
    invoice_type,
    template_id,
    notify_patron,
    has_custom_reminder,
    short_url,
    franchise_id,
    vendor_id,
    autocollect_plan_id,
    paid_amount,
    plugin_value,
    document_url,
    gst_number,
    billing_profile_id,
    generate_estimate_invoice,currency,einvoice_type, product_taxation_type
INTO  @invoice_number , @invoice_total , @grand_total , @advance , @swipez_total ,
 @bill_date , @previous_due , @basic_amount , @tax_amount ,  @narrative , @absolute_cost , 
 @late_fee , @expiry_date , @due_date , @customer_id , @merchant_id , @merchant_user_id , @cycle_id ,
 @status , @payment_request_type,@converted_request_id,@invoice_type , @template_id , @notify_patron , 
 @has_custom_reminder , @short_url , @franchise_id , @vendor_id,  @autocollect_plan_id,@paid_amount,@plugin_value,
 @document_url,@merchant_gst_number,@billing_profile_id,@generate_estimate_invoice,@currency,@einvoice_type , @invoice_product_taxation
 FROM payment_request
WHERE
    payment_request_id = @payment_req
        AND (_merchant_id = 'customer'
        OR merchant_id = _merchant_id);

if(@customer_id>0)then

    select customer_code, concat(first_name,' ',last_name),concat(address,address2),city,zipcode,state,user_id,email,mobile,gst_number,company_name,country into @customer_code,@customer_name,@customer_address,@customer_city
    ,@customer_zip,@customer_state,@customer_user_id,@customer_email,@customer_mobile,@customer_gst_number,@customer_company_name,@customer_country from customer where customer_id=@customer_id;
    
 
SELECT 
    image_path,
    banner_path,
    template_name,
    template_type,
    particular_column,
    particular_total,
    tax_total,
    properties,
    tnc,
    hide_invoice_summary,
    design_name,
        design_color,
        footer_note,
        setting
INTO @image_path , @banner_path , @template_name , @template_type,@particular_column,@particular_total,@tax_total,@properties,@tnc,@hide_invoice_summary,@design_name,@design_color,@footer_note,@setting  FROM
    invoice_template
WHERE
    template_id = @template_id;
 
SELECT 
    display_url,
    merchant_domain,
    merchant_type,
    is_legal_complete,
    disable_online_payment,
    merchant_website,
    merchant_plan
INTO @display_url , @merchant_domain , @merchant_type , @legal_complete , @disable_online_payment , @merchant_website, @merchant_plan   FROM
    merchant
WHERE
    merchant_id = @merchant_id;
 
SELECT 
    statement_enable,
    show_ad,
    promotion_id,
    from_email,
    settlements_to_franchise,
    sms_gateway,
    sms_gateway_type,
    sms_name,default_cust_column_renamed
INTO @statement_enable , @show_ad , @promotion_id , @from_email , @settlements_to_franchise , @sms_gateway , @sms_gateway_type , @sms_name,@default_cust_column_renamed FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
SET @customer_default_column=null;
if(@default_cust_column_renamed=1)then
select `value` into @customer_default_column from merchant_config_data where merchant_id=@merchant_id and `key`='CUSTOMER_DEFAULT_COLUMN';
end if;
 
 
SELECT 
    cycle_name
INTO @cycle_name FROM
    billing_cycle_detail
WHERE
    billing_cycle_id = @cycle_id;
 
SELECT 
    config_value
INTO @domain FROM
    config
WHERE
    config_type = 'merchant_domain'
        AND config_key = @merchant_domain;
 
if(@disable_online_payment=0 and @legal_complete=1)then 
SELECT 
    COUNT(fee_detail_id)
INTO @pg_count FROM
    merchant_fee_detail
WHERE
    merchant_id = @merchant_id and franchise_id=0
        AND is_active = 1;
 end if;
 
 
 if(@pg_count<1 or @disable_online_payment=1 or @legal_complete=0)then
 SET @legal_complete=0;
 end if;
 
 
 if(@billing_profile_id>0)then
 SELECT 
    address, business_email, business_contact,city,zipcode,state,company_name,company_name,gst_number, pan, tan, cin_no,sac_code,country
INTO @merchant_address , @business_email , @business_contact,@merchant_city,@merchant_zipcode,@merchant_state,@company_name,@display_name,@gst_number , @pan , @tan , @cin_no,@sac_code,@merchant_country  FROM
    merchant_billing_profile where id = @billing_profile_id;
 else
 SELECT 
    address, business_email, business_contact,city,zipcode,state,company_name,company_name,gst_number, pan, tan, cin_no,sac_code,country
INTO @merchant_address , @business_email , @business_contact,@merchant_city,@merchant_zipcode,@merchant_state,@company_name,@display_name,@gst_number , @pan , @tan , @cin_no,@sac_code,@merchant_country  FROM
    merchant_billing_profile
WHERE
    merchant_id = @merchant_id and is_default=1;
 end if;
 
 SET @sms_name=@company_name;
 
SET @is_expire= 0;
  if(DATE_FORMAT(NOW(),'%Y-%m-%d') >= @expiry_date) then
 SET @is_expire= 1;
 end if;

 if(DATE_FORMAT(NOW(),'%d %b %Y') > @due_date and @absolute_cost>0) then
 SET @absolute_cost= @absolute_cost + @late_fee;
 end if;
 
 select icon into @currency_icon from currency where `code`=@currency;

if(@merchant_plan IN (3, 4, 9, 12, 13, 14,15)) then
	SET @paid_user = 1;
else 
	SET @paid_user = 0;
end if;
 
SET @message= 'success';
SELECT 
    _paymentrequest_id AS 'payment_request_id',
    @is_expire AS 'is_expire',
    @invoice_number AS 'invoice_number',
    @expiry_date AS 'expiry_date',
    @notify_patron AS 'notify_patron',
    @domain AS 'merchant_domain',
    @display_url AS 'display_url',
    @merchant_id AS 'merchant_id',
    @merchant_user_id AS 'merchant_user_id',
    @particular_column as 'particular_column',
    @particular_total as 'particular_total',
    @tax_total as 'tax_total',
    @plugin_value as 'plugin_value',
    @invoice_product_taxation as 'invoice_product_taxation',
    @status AS 'status',
    @narrative AS 'narrative',
    @absolute_cost AS 'absolute_cost',
    @advance AS 'advance',
    @previous_due AS 'previous_due',
    @cycle_name AS 'cycle_name',
    @grand_total AS 'grand_total',
    @due_date AS 'due_date',
    @Previous_dues AS 'Previous_dues',
    @statement_enable AS 'statement_enable',
    @show_ad AS 'show_ad',
    @bill_date AS 'bill_date',
    @basic_amount AS 'basic_amount',
    @tax_amount AS 'tax_amount',
    @swipez_total AS 'swipez_total',
    @invoice_total AS 'invoice_total',
    @message AS 'message',
    @company_name AS 'company_name',
    @merchant_type AS 'merchant_type',
    @paid_user AS 'paid_user',
    @legal_complete AS 'legal_complete',
    @image_path AS 'image_path',
    @banner_path AS 'banner_path',
    @template_name AS 'template_name',
    @template_type AS 'template_type',
    @merchant_address AS 'merchant_address',
    @merchant_state as 'merchant_state',
    @merchant_city as 'merchant_city',
    @merchant_zipcode as 'merchant_zipcode',
    @merchant_country AS 'merchant_country',
    @business_email AS 'business_email',
    @business_contact AS 'business_contact',
    @late_fee AS 'late_fee',
    @status AS 'payment_request_status',
    @payment_request_type AS 'payment_request_type',
    @invoice_type AS 'invoice_type',
    @template_id AS 'template_id',
    @coupon_id AS 'coupon_id',
    @is_coupon AS 'is_coupon',
    @promotion_id AS 'promotion_id',
    @customer_id AS 'customer_id',
    @customer_code AS 'customer_code',
    @customer_name AS 'customer_name',
    @customer_address AS 'customer_address',
    @customer_city AS 'customer_city',
    @customer_zip AS 'customer_zip',
    @customer_state AS 'customer_state',
    @customer_country AS 'customer_country',
    @customer_email AS 'customer_email',
    @customer_mobile AS 'customer_mobile',
    @customer_user_id AS 'customer_user_id',
    @custom_subject AS 'custom_subject',
    @custom_sms AS 'custom_sms',
    @has_custom_reminder AS 'has_custom_reminder',
    @covering_id AS 'covering_id',
    @franchise_id AS 'franchise_id',
    @vendor_id as 'vendor_id',
    @is_franchise AS 'is_franchise',
    @has_vendor as 'has_vendor',
    @merchant_website AS 'merchant_website',
    @gst_number AS 'gst_number',
    @pan AS 'pan',
    @tan AS 'tan',
    @cin_no AS 'cin_no',
    @registration_number AS 'registration_number',
    @from_email AS 'from_email',
    @settlements_to_franchise AS 'pg_to_franchise',
    @is_prepaid AS 'is_prepaid',
    @has_acknowledgement AS 'has_acknowledgement',
    @main_company_name AS 'main_company_name',
    @sms_gateway AS 'sms_gateway',
    @sms_gateway_type AS 'sms_gateway_type',
    @sms_name AS 'sms_name',
    @autocollect_plan_id as 'autocollect_plan_id',
    @paid_amount as 'paid_amount',
    @converted_request_id AS 'converted_request_id',
    @short_url AS 'short_url',
    @document_url AS 'document_url',
    @tnc as 'tnc',
    @hide_invoice_summary as 'hide_invoice_summary',
    @properties as 'properties',
    @billing_profile_id as 'billing_profile_id',
    @generate_estimate_invoice as 'generate_estimate_invoice',
    @customer_default_column as 'customer_default_column',
	@sac_code as 'sac_code',
    @currency as 'currency',
    @einvoice_type as 'einvoice_type',
    @currency_icon as `currency_icon`,
    @customer_gst_number as 'customer_gst_number',
    @customer_company_name as 'customer_company_name',
    @design_name as 'design_name',
    @design_color as 'design_color',
    @footer_note as 'footer_note',
     @setting as 'setting',
    @message as 'message';
    else
    SET @message='Invalid';
    select @message as 'message';
end if;

END$$

DELIMITER ;

USE `swipez`;
DROP procedure IF EXISTS `swipez`.`save_travel_particular`;

DELIMITER $$
USE `swipez`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_travel_particular`(_payment_request_id varchar(10),_texistid LONGTEXT,_btype LONGTEXT,_booking_date LONGTEXT,_journey_date LONGTEXT,_b_name LONGTEXT,_b_type LONGTEXT,_unit_type LONGTEXT,_sac_code LONGTEXT,_b_from LONGTEXT,_b_to LONGTEXT,_b_amt LONGTEXT,_b_charge LONGTEXT,_b_unit LONGTEXT,_b_rate LONGTEXT,_b_mrp LONGTEXT,_b_product_expiry_date LONGTEXT,_b_product_number LONGTEXT,_b_discount_perc LONGTEXT,_b_discount LONGTEXT,_b_gst LONGTEXT,_b_total LONGTEXT,_b_description LONGTEXT,_b_information LONGTEXT,_user_id varchar(10),_staging tinyint(1),_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;


SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

if(_staging=1)then
select bulk_id into _bulk_id from staging_payment_request where payment_request_id=_payment_request_id;
update staging_invoice_travel_particular set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
else
update invoice_travel_particular set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
end if;


WHILE _btype != '' > 0 DO
    SET @btype  = SUBSTRING_INDEX(_btype, @separator, 1);
    SET @texistid  = SUBSTRING_INDEX(_texistid, @separator, 1);
	SET @booking_date  = SUBSTRING_INDEX(_booking_date, @separator, 1);
	SET @journey_date  = SUBSTRING_INDEX(_journey_date, @separator, 1);
	SET @b_name  = SUBSTRING_INDEX(_b_name, @separator, 1);
	SET @b_type  = SUBSTRING_INDEX(_b_type, @separator, 1);
    SET @sac_code  = SUBSTRING_INDEX(_sac_code, @separator, 1);
    SET @unit_type  = SUBSTRING_INDEX(_unit_type, @separator, 1);
	SET @b_from  = SUBSTRING_INDEX(_b_from, @separator, 1);
	SET @b_to  = SUBSTRING_INDEX(_b_to, @separator, 1);
	SET @b_amt  = SUBSTRING_INDEX(_b_amt, @separator, 1);
	SET @b_charge  = SUBSTRING_INDEX(_b_charge, @separator, 1);
    SET @b_unit  = SUBSTRING_INDEX(_b_unit, @separator, 1);
    SET @b_rate  = SUBSTRING_INDEX(_b_rate, @separator, 1);
    SET @b_mrp  = SUBSTRING_INDEX(_b_mrp, @separator, 1);
    SET @b_product_expiry_date  = SUBSTRING_INDEX(_b_product_expiry_date, @separator, 1);
    SET @b_product_number  = SUBSTRING_INDEX(_b_product_number, @separator, 1);
    SET @b_discount_perc  = SUBSTRING_INDEX(_b_discount_perc, @separator, 1);
    SET @b_discount  = SUBSTRING_INDEX(_b_discount, @separator, 1);
    SET @b_gst  = SUBSTRING_INDEX(_b_gst, @separator, 1);
	SET @b_total  = SUBSTRING_INDEX(_b_total, @separator, 1);
    SET @b_description  = SUBSTRING_INDEX(_b_description, @separator, 1);
	SET @b_information  = SUBSTRING_INDEX(_b_information, @separator, 1);
    if(@b_mrp>0)then
    SET @b_mrp=@b_mrp;
    else
    SET @b_mrp=0;
    end if;

	if(@btype='b')then
	SET @type=1;
	elseif(@btype='c')then
	SET @type=2;
    elseif(@btype='hb')then
    SET @type=3;
    elseif(@btype='fs')then
    SET @type=4;
	end if;
    if(@b_total>0)then
	if(@texistid>0)then
		if(_staging=1)then
			UPDATE `staging_invoice_travel_particular` SET `booking_date` = @booking_date,`journey_date` = @journey_date,`name` = @b_name,`vehicle_type` = @b_type,`unit_type`=@unit_type,`sac_code`=@sac_code,`from_station` = @b_from,
			`to_station` = @b_to,`amount` = @b_amt ,`charge` = @b_charge,`total` = @b_total,`units`=@b_unit,`rate`=@b_rate,`mrp`=@b_mrp,`product_expiry_date`=@b_product_expiry_date,`product_number`=@b_product_number,`discount_perc`=@b_discount_perc,`discount`=@b_discount,`gst`=@b_gst,`description` = @b_description,`information` = @b_information,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @texistid;
		else
			UPDATE `invoice_travel_particular` SET `booking_date` = @booking_date,`journey_date` = @journey_date,`name` = @b_name,`vehicle_type` = @b_type,`unit_type`=@unit_type,`sac_code`=@sac_code,`from_station` = @b_from,
			`to_station` = @b_to,`amount` = @b_amt ,`charge` = @b_charge,`total` = @b_total,`units`=@b_unit,`rate`=@b_rate,`mrp`=@b_mrp,`product_expiry_date`=@b_product_expiry_date,`product_number`=@b_product_number,`discount_perc`=@b_discount_perc,`discount`=@b_discount,`gst`=@b_gst,`description` = @b_description,`information` = @b_information,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @texistid;
		end if;
	else
		if(_staging=1)then
		INSERT INTO `staging_invoice_travel_particular`(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,
	`created_by`,`created_date`,`last_update_by`,`bulk_id`)VALUES(_payment_request_id,@booking_date,@journey_date,@b_name,@b_type,@unit_type,@sac_code,@b_from,@b_to,@b_amt,@b_charge,@b_unit,@b_rate,@b_mrp,@b_product_expiry_date,@b_product_number,@b_discount_perc,@b_discount,@b_gst,@b_total,@type,@b_description,@b_information,_user_id,CURRENT_TIMESTAMP(),_user_id,_bulk_id);
		else
        INSERT INTO `invoice_travel_particular`(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,
	`created_by`,`created_date`,`last_update_by`)VALUES(_payment_request_id,@booking_date,@journey_date,@b_name,@b_type,@unit_type,@sac_code,@b_from,@b_to,@b_amt,@b_charge,@b_unit,@b_rate,@b_mrp,@b_product_expiry_date,@b_product_number,@b_discount_perc,@b_discount,@b_gst,@b_total,@type,@b_description,@b_information,_user_id,CURRENT_TIMESTAMP(),_user_id);
		end if;
	end if;
    end if;

    SET _btype = SUBSTRING(_btype, CHAR_LENGTH(@btype) + @separatorLength + 1);
    SET _texistid = SUBSTRING(_texistid, CHAR_LENGTH(@texistid) + @separatorLength + 1);
	SET _booking_date = SUBSTRING(_booking_date, CHAR_LENGTH(@booking_date) + @separatorLength + 1);
	SET _journey_date = SUBSTRING(_journey_date, CHAR_LENGTH(@journey_date) + @separatorLength + 1);
	SET _b_name = SUBSTRING(_b_name, CHAR_LENGTH(@b_name) + @separatorLength + 1);
	SET _b_type = SUBSTRING(_b_type, CHAR_LENGTH(@b_type) + @separatorLength + 1);
    SET _unit_type = SUBSTRING(_unit_type, CHAR_LENGTH(@unit_type) + @separatorLength + 1);
    SET _sac_code = SUBSTRING(_sac_code, CHAR_LENGTH(@sac_code) + @separatorLength + 1);
	SET _b_from = SUBSTRING(_b_from, CHAR_LENGTH(@b_from) + @separatorLength + 1);
	SET _b_to = SUBSTRING(_b_to, CHAR_LENGTH(@b_to) + @separatorLength + 1);
	SET _b_amt = SUBSTRING(_b_amt, CHAR_LENGTH(@b_amt) + @separatorLength + 1);
	SET _b_charge = SUBSTRING(_b_charge, CHAR_LENGTH(@b_charge) + @separatorLength + 1);
    
    SET _b_unit = SUBSTRING(_b_unit, CHAR_LENGTH(@b_unit) + @separatorLength + 1);
    SET _b_rate = SUBSTRING(_b_rate, CHAR_LENGTH(@b_rate) + @separatorLength + 1);
    SET _b_mrp = SUBSTRING(_b_rate, CHAR_LENGTH(@b_mrp) + @separatorLength + 1);
    SET _b_product_expiry_date = SUBSTRING(_b_rate, CHAR_LENGTH(@b_product_expiry_date) + @separatorLength + 1);
    SET _b_product_number = SUBSTRING(_b_rate, CHAR_LENGTH(@b_product_number) + @separatorLength + 1);
    SET _b_discount_perc = SUBSTRING(_b_discount_perc, CHAR_LENGTH(@b_discount_perc) + @separatorLength + 1);
    SET _b_discount = SUBSTRING(_b_discount, CHAR_LENGTH(@b_discount) + @separatorLength + 1);
    SET _b_gst = SUBSTRING(_b_gst, CHAR_LENGTH(@b_gst) + @separatorLength + 1);
    
	SET _b_total = SUBSTRING(_b_total, CHAR_LENGTH(@b_total) + @separatorLength + 1);
    SET _b_description = SUBSTRING(_b_description, CHAR_LENGTH(@b_description) + @separatorLength + 1);
    SET _b_information = SUBSTRING(_b_information, CHAR_LENGTH(@b_information) + @separatorLength + 1);

END WHILE;

###############################################
SET @message='success';
commit;

select @message as 'message';


END$$

DELIMITER ;

USE `swipez`;
DROP procedure IF EXISTS `swipez`.`savebulkUpload_invoice`;

DELIMITER $$
USE `swipez`$$
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

/*for saving staging_subscriptions into subscription table */
SELECT `invoice_number`,`merchant_id`,`template_id`,`invoice_type`,`payment_request_type` into @invoice_numstring,@merchant_id,@template_id,@invoice_type,@payment_request_type FROM staging_payment_request where bulk_id=_bulk_upload_id limit 1;

if(@payment_request_type=4) then
	INSERT INTO `subscription`(`payment_request_id`,`merchant_id`,`mode`,`repeat_every`,`repeat_on`,`start_date`,`due_date`,`due_diff`,
	`carry_due`,`last_sent_date`,`next_bill_date`,`end_mode`,`occurrences`,`end_date`,`display_text`,`billing_period_start_date`,
	`billing_period_duration`,`billing_period_type`,`service_id`,`is_active`,`created_by`,`created_date`,`last_updated_by`,`last_updated_date`)
	SELECT `staging_subscription`.`payment_request_id`,`merchant_id`,`mode`,`repeat_every`,`repeat_on`,`start_date`,`due_date`,`due_diff`,
	`carry_due`,`last_sent_date`,`next_bill_date`,`end_mode`,`occurrences`,`end_date`,`display_text`,`billing_period_start_date`,
	`billing_period_duration`,`billing_period_type`,`service_id`,`is_active`,`created_by`
	,CURRENT_TIMESTAMP(),`last_updated_by`,CURRENT_TIMESTAMP() FROM `staging_subscription` where bulk_id=_bulk_upload_id and is_active=1;
end if;

SET @numstring=SUBSTRING(@invoice_numstring,1,16);
if(@numstring='System generated' and @invoice_type=1)then

	SET @autoval=SUBSTRING(@invoice_numstring,17);

	INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`,
`created_date`,`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`)
SELECT `staging_payment_request`.`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,`invoice_type`,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
generate_invoice_number(@autoval),`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`
FROM `staging_payment_request` where bulk_id=_bulk_upload_id and is_active=1 and invoice_type=1;

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
	
if(@invoice_type=2)then
INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`,
`created_date`,`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`)
SELECT `staging_payment_request`.`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,`invoice_type`,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
`invoice_number`,generate_estimate_number(merchant_id),`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`
FROM `staging_payment_request` where bulk_id=_bulk_upload_id and is_active=1;
else
INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`,
`created_date`,`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`)
SELECT `staging_payment_request`.`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,`invoice_type`,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
`invoice_number`,`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`
FROM `staging_payment_request` where bulk_id=_bulk_upload_id and is_active=1;
end if;
	end if;
end if;


INSERT INTO `invoice_particular`
(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`unit_type`,`qty`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT `payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`unit_type`,`qty`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `staging_invoice_particular` where bulk_id=_bulk_upload_id and is_active=1;

INSERT INTO `invoice_travel_particular`
(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT `payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `staging_invoice_travel_particular` where bulk_id=_bulk_upload_id and is_active=1;


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
    payment_request_id,payment_request_type,invoice_type,notify_patron,due_date,has_custom_reminder,template_id,customer_id,merchant_id,user_id,plugin_value,@due_mode as 'due_mode'
FROM
    payment_request where bulk_id=_bulk_upload_id;
end if;
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;






DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '1');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '2');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '3');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '4');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '5');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '6');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '7');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '8');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '9');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '10');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '11');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '12');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '13');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '14');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '15');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '16');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '17');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '18');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '19');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '20');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '21');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '22');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '23');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '24');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '25');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '26');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '27');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '28');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '29');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '30');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '31');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '32');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '33');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '34');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '35');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '36');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '37');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '38');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '39');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '40');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '41');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '42');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '43');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '44');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '45');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '46');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '47');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '48');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '49');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '50');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '51');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '52');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '53');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '54');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '55');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '56');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '57');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '58');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '59');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '60');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '61');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '62');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '63');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '64');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '65');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '66');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '67');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '68');
DELETE FROM `swipez`.`invoice_template_mandatory_fields` WHERE (`id` = '69');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('1', 'Percentage', '', 'percentage', 'T', '1', '1', '1');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('2', 'Applicable on (RS)', '', 'money', 'T', '1', '1', '2');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('3', 'Absolute cost', '', 'money', 'T', '1', '1', '3');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('4', 'Narrative', '', 'text', 'T', '1', '1', '4');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('5', 'Company name', '', 'text', 'M', '1', '1', '1');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('6', 'Merchant contact', '', 'number', 'M', '1', '0', '2');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('7', 'Merchant email', '', 'email', 'M', '1', '0', '3');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('8', 'Merchant address', '', 'textarea', 'M', '1', '0', '4');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('9', 'Merchant website', '', 'link', 'M', '0', '0', '5');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('10', 'Company pan', '', 'pan', 'M', '0', '0', '6');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('11', 'GSTIN Number', '', 'text', 'M', '0', '0', '7');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('12', 'Company TAN', '', 'text', 'M', '0', '0', '8');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('13', '#', 'sr_no', 'text', 'P', '0', '0', '1');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('14', 'Item', 'item', 'text', 'P', '1', '1', '2');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('15', 'Annual recurring charges', 'annual_recurring_charges', 'text', 'P', '0', '0', '3');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('16', 'Sac Code', 'sac_code', 'text', 'P', '0', '0', '4');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('17', 'Description', 'description', 'textarea', 'P', '0', '0', '5');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('18', 'Quantity', 'qty', 'money', 'P', '0', '0', '6');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('19', 'Unit type', 'unit_type', 'text', 'P', '0', '0', '7');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('20', 'Rate', 'rate', 'money', 'P', '0', '0', '8');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('21', 'GST', 'gst', 'money', 'P', '0', '0', '9');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('22', 'Tax amount', 'tax_amount', 'money', 'P', '0', '0', '10');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('23', 'Discount %', 'discount_perc', 'money', 'P', '0', '0', '11');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('24', 'Discount', 'discount', 'money', 'P', '0', '0', '12');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('25', 'Amount', 'total_amount', 'money', 'P', '0', '1', '13');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('26', 'Narrative', 'narrative', 'textarea', 'P', '0', '0', '14');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('27', '#', 'sr_no', 'text', 'TB', '1', '0', '1');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('28', 'Booking date', 'booking_date', 'date', 'TB', '1', '1', '2');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('29', 'Journey date', 'journey_date', 'date', 'TB', '1', '0', '3');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('30', 'Name', 'name', 'text', 'TB', '1', '1', '4');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('31', 'Type', 'type', 'text', 'TB', '1', '0', '6');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('32', 'From', 'from', 'text', 'TB', '1', '0', '7');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('33', 'To', 'to', 'text', 'TB', '1', '0', '8');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('34', 'Amt', 'amount', 'money', 'TB', '1', '1', '10');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('35', 'Service charge', 'charge', 'money', 'TB', '1', '0', '11');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('36', 'Discount %', 'discount_perc', 'money', 'TB', '0', '0', '12');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('37', 'Discount', 'discount', 'money', 'TB', '0', '0', '13');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('38', 'GST', 'gst', 'money', 'TB', '0', '0', '14');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('39', 'Total', 'total_amount', 'money', 'TB', '1', '1', '15');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('40', '#', 'sr_no', 'text', 'HB', '1', '0', '1');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('41', 'Description', 'item', 'text', 'HB', '1', '1', '2');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('42', 'From date', 'from_date', 'date', 'HB', '1', '0', '3');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('43', 'To date', 'to_date', 'date', 'HB', '1', '0', '4');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('44', 'Units', 'qty', 'money', 'HB', '1', '0', '5');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('45', 'Rate', 'rate', 'money', 'HB', '1', '0', '6');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('46', 'Discount %', 'discount_perc', 'money', 'HB', '0', '0', '7');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('47', 'Discount', 'discount', 'money', 'HB', '0', '0', '8');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('48', 'GST', 'gst', 'money', 'HB', '1', '0', '9');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('49', 'Absolute cost', 'total_amount', 'money', 'HB', '1', '1', '10');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('50', '#', 'sr_no', 'text', 'FS', '1', '0', '1');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('51', 'Description', 'item', 'text', 'FS', '1', '1', '2');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('52', 'Date', 'from_date', 'date', 'FS', '1', '0', '3');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('53', 'To date', 'to_date', 'date', 'FS', '0', '0', '4');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('54', 'Units', 'qty', 'money', 'FS', '1', '0', '5');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('55', 'Rate', 'rate', 'money', 'FS', '1', '0', '6');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('56', 'Discount %', 'discount_perc', 'money', 'FS', '0', '0', '8');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('57', 'Discount', 'discount', 'money', 'FS', '0', '0', '9');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('58', 'GST', 'gst', 'money', 'FS', '1', '0', '10');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('59', 'Absolute cost', 'total_amount', 'money', 'FS', '1', '1', '11');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('60', 'CIN Number', 'cin_no', 'text', 'M', '0', '0', '9');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('62', 'Description', 'description', 'text', 'TB', '0', '0', '5');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('63', 'Information', 'information', 'text', 'TB', '0', '0', '9');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('65', 'Narrative', 'description', 'text', 'HB', '0', '0', '11');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('66', 'Information', 'information', 'text', 'HB', '0', '0', '5');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('67', 'Narrative', 'information', 'text', 'FS', '0', '0', '4');
INSERT INTO `swipez`.`invoice_template_mandatory_fields` (`id`, `column_name`, `system_col_name`, `datatype`, `type`, `is_default`, `is_mandatory`, `seq`) VALUES ('68', 'Information', 'description', 'text', 'FS', '0', '0', '2');