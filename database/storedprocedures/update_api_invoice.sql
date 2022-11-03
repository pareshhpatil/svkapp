CREATE DEFINER=`root`@`localhost` PROCEDURE `update_api_invoice`(_invoice_id varchar(10),_invoice_values LONGTEXT,_column_id LONGTEXT,_user_id varchar(10),_customer_id INT,
_bill_date datetime,_due_date datetime,_bill_cycle_name varchar(40),_narrative varchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),particular_total varchar(45),tax_total varchar(45),
_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),_supplier varchar(250),_late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_expiry_date date,_notify_patron INT,_coupon_id INT,_franchise_id INT,_webhook_id INT,_vendor_id INT,_type INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
        SET @message='failed';
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @p_type=2;
SET @position=0;
Set @zero=0;
Set @one=1;
SET @bc_id='';
SET @patron_id='';
SET @ptColumn_id='';
SET @ttColumn_id='';
SET @account_id='';
SET @separator = '~';
SET @req_id='';
set @pay_req_status=0;
SET @separatorLength = CHAR_LENGTH(@separator);
SET @swipez_fee=0;
SET @EBS_fee=0;
SET @pg_tax_val=0;
SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
SET @swipez_total=@invoice_total;
SET @convenience_fee=0;
SET @grand_total=@invoice_total;
SET @parent_request_id='';

SELECT 
    CONCAT(first_name, ' ', last_name), user_id, customer_code
INTO @patron_name , @customer_user_id , @customer_code FROM
    customer
WHERE
    customer_id = _customer_id;

if(@customer_user_id <>'')then
SET @p_type=1;
end if;

SELECT 
    merchant_id, merchant_domain, company_name
INTO @merchat_id , @merchant_domain , @company FROM
    merchant
WHERE
    user_id = _user_id;
SELECT 
    swipez_fee_type,
    swipez_fee_val,
    pg_fee_type,
    pg_fee_val,
    pg_tax_val,
    surcharge_enabled
INTO @swipez_fee_type , @swipez_fee_val , @pg_fee_type , @pg_fee_val , @pg_tax , @surcharge FROM
    merchant_fee_detail
WHERE
    merchant_id = @merchat_id
        AND is_active = 1
ORDER BY pg_fee_val DESC
LIMIT 1;

SELECT 
    template_id,short_url
INTO @template_id,@short_url FROM
    payment_request
WHERE
    payment_request_id = _invoice_id;

SELECT 
    image_path, is_roundoff
INTO @image , @is_roundoff FROM
    invoice_template
WHERE
    template_id = @template_id;
if(@is_roundoff=1)then
SET @grand_total=ROUND(@grand_total);
end if;

SET @grand_total=@grand_total-_advance;

UPDATE invoice_column_values 
SET 
    `is_active` = 0
WHERE
    `payment_request_id` = _invoice_id;


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




SELECT DISTINCTROW
    `billing_cycle_id`
INTO @bc_id FROM
    `billing_cycle_detail`
WHERE
    `_user_id` = _user_id
        AND `cycle_name` = _bill_cycle_name
LIMIT 1;


if(@bc_id='') then
select generate_sequence('Billing_cycle_id') into @bc_id;

INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`, `is_active`, `created_by`, `created_date`, 
`last_update_by`, `last_update_date`)
			 VALUES (@bc_id,_user_id,_bill_cycle_name,@one,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
end if;



INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,`created_by`,`created_date`,`last_update_by`,
`last_update_date`)
VALUES(@patron_id,1,'paymentrequest/viewlist',CURRENT_TIMESTAMP(),DATE_ADD(NOW(), INTERVAL 31 DAY),'You have received count payment request','',
_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
 


UPDATE payment_request 
SET 
    `customer_id` = _customer_id,
    `absolute_cost` = @grand_total,
    `basic_amount` = _amount,
    `tax_amount` = _tax,
    `invoice_total` = @invoice_total,
    `swipez_total` = @swipez_total,
    `convenience_fee` = @convenience_fee,
    `grand_total` = @grand_total,
    `late_payment_fee` = _late_fee,
    `bill_date` = _bill_date,
    `due_date` = _due_date,
    `narrative` = _narrative,
    `billing_cycle_id` = @bc_id,
    `notification_sent` = @zero,
    `expiry_date` = _expiry_date,
    `notify_patron` = _notify_patron,
    `coupon_id` = _coupon_id,
	`franchise_id`=_franchise_id,
    `vendor_id`=_vendor_id,
    `webhook_id`=_webhook_id,
    `advance_received`=_advance
WHERE
    payment_request_id = _invoice_id;


SET @max_particular=0;
SET @max_tax=0;
SELECT 
    MAX(column_position)
INTO @max_particular FROM
    invoice_column_metadata
WHERE
    template_id = @template_id
        AND column_type = 'PF';
SELECT 
    MAX(column_position)
INTO @max_tax FROM
    invoice_column_metadata
WHERE
    template_id = @template_id
        AND column_type = 'TF';

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


WHILE _column_id != '' > 0 DO
    SET @existid  = SUBSTRING_INDEX(_column_id, @separator, 1);
    SET @existvalue  = SUBSTRING_INDEX(_invoice_values, @separator, 1);

SET @is_new=0;
SET @invoice_values=@existvalue;
CASE @existid
      WHEN 'P1' THEN 
 select generate_sequence('Column_group_id') into @column_group_id;  
 SET @max_particular= @max_particular + 1;
	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_particular,@invoice_values,'PF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
SET @is_new=1;
      WHEN 'P2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_particular,'Unit Price','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'P3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'integer',@max_particular,'No of units','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'P4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_particular,'Absolute cost','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'P5' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_particular,'Narrative','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'T1' THEN 
 select generate_sequence('Column_group_id') into @column_group_id;  
 SET @max_tax=@max_tax + 1;
	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,@invoice_values,'TF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
SET @is_new=1;
      WHEN 'T2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'percentage',@max_tax,'Percentage','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'T3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Applicable on (RS)','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'T4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Absolute cost','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'T5' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,'Narrative','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 WHEN 'CC' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',0,@invoice_values,'CC',0,'',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 WHEN 'D1' THEN 
	select generate_sequence('Column_group_id') into @column_group_id;  
     SET @max_tax=@max_tax + 1;
       	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,@invoice_values,'DF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 
 WHEN 'D2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'percentage',@max_tax,'Percentage','DS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 WHEN 'D3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Applicable on (RS)','DS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 WHEN 'D4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Absolute cost','DS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
      SET @is_new=1;
      
    ELSE
   SET @is_new=0;
    END CASE;

if(@is_new=1)then
INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (_invoice_id,@column_id,@invoice_values,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
else
    update invoice_column_values set `value`= @existvalue,`last_update_date`=CURRENT_TIMESTAMP(),`is_active`=1 where `column_id` = @existid and `payment_request_id`=_invoice_id;
  end if;
  
    SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@existid) + @separatorLength + 1);
    SET _invoice_values = SUBSTRING(_invoice_values, CHAR_LENGTH(@existvalue) + @separatorLength + 1);
END WHILE;
 

SELECT 
    `value`
INTO @patron_email FROM
    customer_comm_detail
WHERE
    customer_id = _customer_id AND type = 1
        AND is_preferred = 1
LIMIT 1;
SELECT 
    `value`
INTO @patron_mobile FROM
    customer_comm_detail
WHERE
    customer_id = _customer_id AND type = 2
        AND is_preferred = 1
LIMIT 1;
 
commit;
SET @message = 'success';
SELECT 
    @customer_code AS 'code',
    @patron_name AS 'patron_name',
    @patron_email AS 'patron_email',
    @patron_mobile AS 'patron_mobile',
    _invoice_id AS 'invoice_id',
    @short_url as 'short_url',
    @message AS 'message';


END
