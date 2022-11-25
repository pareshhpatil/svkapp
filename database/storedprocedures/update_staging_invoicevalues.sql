CREATE DEFINER=`root`@`localhost` PROCEDURE `update_staging_invoicevalues`(_payment_request_id varchar(10),_existid LONGTEXT,_existvalue LONGTEXT,invoice_values LONGTEXT,_column_id LONGTEXT,
_user_id varchar(10),_customer_id INT,_bill_date datetime,_due_date datetime,_bill_cycle_name varchar(40),_narrative varchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),
_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),_supplier_id INT ,_supplier varchar(100),_late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_expiry_date date,_notify_patron INT,_coupon_id INT,_franchise_id INT,_franchise_name_invoice INT,_vendor_id INT,_enable_partial_payment int ,_partial_min_amount decimal(11,2))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @patron_type=2;
SET @position=0;
Set @zero=0;
Set @one=1;
SET @bc_id='';
SET @patron_id='';
SET @ptColumn_id='';
SET @ttColumn_id='';
SET @account_id='';
SET @separator = '~';
SET @req_id=_payment_request_id;
set @pay_req_status=0;
SET @separatorLength = CHAR_LENGTH(@separator);
SET @swipez_fee=0;
SET @EBS_fee=0;
SET @pg_tax_val=0;
SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
SET @swipez_total=@invoice_total;
SET @convenience_fee=0;
SET @grand_total=@invoice_total;


select concat(first_name,' ',last_name),user_id,email,mobile into @patron_name,@customer_user_id,@customer_email,@customer_mobile from customer where customer_id=_customer_id;

if(@customer_user_id <>'')then
SET @patron_type=1;
end if;

update staging_invoice_values set `is_active`=0 where `payment_request_id`=_payment_request_id;
select template_id into @template_id from staging_payment_request where `payment_request_id`=_payment_request_id;



select merchant_id,merchant_domain,company_name,display_name into @merchat_id,@merchant_domain,@company,@display_name from merchant where user_id=_user_id;
select swipez_fee_type , swipez_fee_val,pg_fee_type,pg_fee_val,pg_tax_val,surcharge_enabled into @swipez_fee_type,@swipez_fee_val,@pg_fee_type,@pg_fee_val,@pg_tax,@surcharge from merchant_fee_detail where merchant_id=@merchat_id and is_active=1 order by pg_fee_val desc limit 1;

 if(@display_name<>'')then
 SET @company=@display_name;
 end if;

if(@swipez_fee_type='F') then 
set @swipez_fee=@swipez_fee_val;
end if;

if(@swipez_fee_type='P') then 
set @swipez_fee=@invoice_total * @swipez_fee_val/100;
end if;

if(@surcharge=1) then
SET @swipez_total= @invoice_total + @swipez_fee;
end if;

if(@pg_fee_type='F') then 
set @EBS_fee= @pg_fee_val;
end if;

if(@pg_fee_type='P') then 
set @EBS_fee=@swipez_total * @pg_fee_val/100;
end if;

if(@pg_tax>0) then 
set @pg_tax_val= @EBS_fee * @pg_tax/100;
end if;

SET @convenience_fee=@swipez_fee + @EBS_fee + @pg_tax_val;



select image_path,is_roundoff into @image,@is_roundoff from invoice_template where template_id=@template_id;
if(@is_roundoff=1)then
SET @grand_total=ROUND(@grand_total);
end if;

SET @grand_total=@grand_total-_advance;

SELECT DISTINCTROW `billing_cycle_id` into @bc_id FROM `billing_cycle_detail` where `_user_id`=_user_id and `cycle_name`=_bill_cycle_name limit 1;


if(@bc_id='') then
select generate_sequence('Billing_cycle_id') into @bc_id;

INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`, `is_active`, `created_by`, `created_date`, 
`last_update_by`, `last_update_date`)
			 VALUES (@bc_id,_user_id,_bill_cycle_name,@one,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
end if;

update staging_payment_request set `customer_id`=_customer_id ,`account_id`=@account_id,`absolute_cost`=@grand_total,`basic_amount`=_amount,`tax_amount`=_tax,
`invoice_total`=@invoice_total,`swipez_total`=@swipez_total,`convenience_fee`=@convenience_fee,`grand_total`=@grand_total,`bill_date`=_bill_date,`due_date`=_due_date,
`narrative`=_narrative,`billing_cycle_id`=@bc_id,`late_payment_fee`=_late_fee,`expiry_date`=_expiry_date,`notify_patron`=_notify_patron,`franchise_id`=_franchise_id,`franchise_name_invoice`=_franchise_name_invoice,`vendor_id`=_vendor_id,`advance_received`=_advance
,enable_partial_payment=_enable_partial_payment,partial_min_amount=_partial_min_amount
,`last_update_date`=CURRENT_TIMESTAMP() where `payment_request_id`=_payment_request_id;


WHILE _existid != '' > 0 DO
    SET @existid  = SUBSTRING_INDEX(_existid, @separator, 1);
    SET @existvalue  = SUBSTRING_INDEX(_existvalue, @separator, 1);

update staging_invoice_values set `value`= @existvalue,`last_update_date`=CURRENT_TIMESTAMP(),`is_active`=1 where `invoice_id` = @existid;
  
    SET _existid = SUBSTRING(_existid, CHAR_LENGTH(@existid) + @separatorLength + 1);
    SET _existvalue = SUBSTRING(_existvalue, CHAR_LENGTH(@existvalue) + @separatorLength + 1);
END WHILE;


SET @max_particular=0;
SET @max_tax=0;
select max(column_position) into @max_particular from invoice_column_metadata where template_id=@template_id and column_type='PF';
select max(column_position) into @max_tax from invoice_column_metadata where template_id=@template_id and column_type='TF';

if(@max_particular>0)then
SET @max_particular=@max_particular;
else
SET @max_particular=0;
end if;

if(@max_tax>0)then
SET @max_tax=@max_tax;
else
SET @max_tax=0;
end if;


SET @column_group_id='';
WHILE _column_id != '' > 0 DO
    SET @column_id_value  = SUBSTRING_INDEX(_column_id, @separator, 1);
	SET @invoice_values  = SUBSTRING_INDEX(invoice_values, @separator, 1);
		
 CASE @column_id_value
      WHEN 'P1' THEN 
 select generate_sequence('Column_group_id') into @column_group_id;  
 SET @max_particular= @max_particular + 1;
	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_particular,@invoice_values,'PF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();

      WHEN 'P2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_particular,'Unit Price','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'P3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'integer',@max_particular,'No of units','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'P4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_particular,'Absolute cost','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'P5' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_particular,'Narrative','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'T1' THEN 
 select generate_sequence('Column_group_id') into @column_group_id;  
 SET @max_tax=@max_tax + 1;
	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,@invoice_values,'TF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();

      WHEN 'T2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'percentage',@max_tax,'Percentage','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'T3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Applicable on (RS)','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'T4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Absolute cost','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
  
  WHEN 'T5' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,'Narrative','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
 WHEN 'CC' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',0,@invoice_values,'CC',0,'',_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
      
      ELSE
   SET @column_id=@column_id_value;
   if(@template_id='') then
   select template_id into @template_id from invoice_column_metadata where column_id=@column_id;
   end if;
    END CASE;

INSERT INTO `staging_invoice_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (@req_id,@column_id,@invoice_values,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
 

	 SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id_value) + @separatorLength + 1);
    SET invoice_values = SUBSTRING(invoice_values, CHAR_LENGTH(@invoice_values) + @separatorLength + 1);
END WHILE;



 select config_value into @domain from config where config_type='merchant_domain' and config_key=@merchant_domain;
  
  
commit;
SET @message = 'success';

select _notify_patron as 'notify_patron', @customer_name as 'customer_name',@customer_email as 'customer_email',@customer_mobile as 'customer_mobile',@company as 'company',@req_id as 'request_id',@message as 'message',@domain as 'merchant_domain',@image as 'image';



END
