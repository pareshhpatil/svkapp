CREATE DEFINER=`root`@`localhost` PROCEDURE `save_invoice_particular`(_payment_request_id char(10),_particular_id longtext,_item longtext,_annual_recurring_charges longtext,_sac_code longtext,_description longtext,_qty longtext,_unit_type longtext,_rate longtext,_gst longtext,_tax_amount longtext,_discount longtext,_total_amount longtext,_narrative longtext,_user_id char(10),_merchant_id char(10),_staging tinyint(1),_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @particular_id = '';
SET @item = '';
SET @annual_recurring_charges = '';
SET @sac_code = '';
SET @description = '';
SET @unit_type=null;
SET @qty = 0;
SET @rate = 0;
SET @gst =0;
SET @tax_amount = 0;
SET @discount = 0;
SET @total_amount = 0;
SET @narrative = '';
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
if(_staging=1)then
select bulk_id into _bulk_id from staging_payment_request where payment_request_id=_payment_request_id;
update staging_invoice_particular set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
else
update invoice_particular set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
end if;
WHILE _item != '' > 0 DO
	SET @item  = SUBSTRING_INDEX(_item, @separator, 1);
	SET _item = SUBSTRING(_item, CHAR_LENGTH(@item) + @separatorLength + 1);
    
    SET @annual_recurring_charges=null;
    SET @sac_code=null;
    SET @unit_type=null;
    SET @description=null;
    
	if(_annual_recurring_charges!='')then
	SET @annual_recurring_charges  = SUBSTRING_INDEX(_annual_recurring_charges, @separator, 1);
	SET _annual_recurring_charges = SUBSTRING(_annual_recurring_charges, CHAR_LENGTH(@annual_recurring_charges) + @separatorLength + 1);
	end if;
    
	if(_sac_code!='')then
	SET @sac_code  = SUBSTRING_INDEX(_sac_code, @separator, 1);
	SET _sac_code = SUBSTRING(_sac_code, CHAR_LENGTH(@sac_code) + @separatorLength + 1);
	end if;
    
    if(_unit_type!='')then
	SET @unit_type  = SUBSTRING_INDEX(_unit_type, @separator, 1);
	SET _unit_type = SUBSTRING(_unit_type, CHAR_LENGTH(@unit_type) + @separatorLength + 1);
	end if;
    
	if(_description!='')then
        SET @description  = SUBSTRING_INDEX(_description, @separator, 1);
	SET _description = SUBSTRING(_description, CHAR_LENGTH(@description) + @separatorLength + 1);
	end if;
    
	if(_qty!='')then
	SET @qty  = SUBSTRING_INDEX(_qty, @separator, 1);
	SET _qty = SUBSTRING(_qty, CHAR_LENGTH(@qty) + @separatorLength + 1);
	end if;
    
    if(@qty='')then
    SET @qty=0;
    end if;
        
	if(_rate<>'')then
	SET @rate  = SUBSTRING_INDEX(_rate, @separator, 1);
	SET _rate = SUBSTRING(_rate, CHAR_LENGTH(@rate) + @separatorLength + 1);
	end if;
    
    if(@rate='')then
    SET @rate=0;
    end if;
    SET @gst=0;
	if(_gst<>'')then
        SET @gst  = SUBSTRING_INDEX(_gst, @separator, 1);
		SET _gst = SUBSTRING(_gst, CHAR_LENGTH(@gst) + @separatorLength + 1);
	end if;
    
    if(@gst='')then
    SET @gst=0;
    end if;
    
	if(_tax_amount<>'')then
        SET @tax_amount  = SUBSTRING_INDEX(_tax_amount, @separator, 1);
	SET _tax_amount = SUBSTRING(_tax_amount, CHAR_LENGTH(@tax_amount) + @separatorLength + 1);
	end if;
    
    if(@tax_amount='')then
    SET @tax_amount=0;
    end if;
    
	if(_discount<>'')then
        SET @discount  = SUBSTRING_INDEX(_discount, @separator, 1);
	SET _discount = SUBSTRING(_discount, CHAR_LENGTH(@discount) + @separatorLength + 1);
	end if;
    
    if(@discount='')then
    SET @discount=0;
    end if;
    
	if(_total_amount!='')then
        SET @total_amount  = SUBSTRING_INDEX(_total_amount, @separator, 1);
	SET _total_amount = SUBSTRING(_total_amount, CHAR_LENGTH(@total_amount) + @separatorLength + 1);
	end if;
    
    
    if(@total_amount='')then
    SET @total_amount=0;
    end if;
    
    if(@total_amount>0 and @qty=0)then
    SET @qty=1;
    end if;
    
    if(@total_amount>0 and @rate=0)then
    SET @rate=@total_amount;
    end if;
    
	if(_narrative!='')then
	SET @narrative  = SUBSTRING_INDEX(_narrative, @separator, 1);
	SET _narrative = SUBSTRING(_narrative, CHAR_LENGTH(@narrative) + @separatorLength + 1);
	end if;
    
	SET @particular_id  = SUBSTRING_INDEX(_particular_id, @separator, 1);
	SET _particular_id = SUBSTRING(_particular_id, CHAR_LENGTH(@particular_id) + @separatorLength + 1);
    
    SET @product_id=null;
    select product_id into @product_id from merchant_product where merchant_id=_merchant_id and product_name=@item limit 1;
    
    if(@product_id>0)then
    SET @product_id=@product_id;
    else
		if(@rate=0)then
        SET @product_rate=@total_amount;
        else
        SET @product_rate=@rate;
        end if;
        SET @unit_type_id=0;
        if(@unit_type<>'')then
        select id into @unit_type_id from merchant_unit_type where `name`=@unit_type and merchant_id in ('system',_merchant_id) limit 1;
        if(@unit_type_id>0)then
        SET @unit_type_id=@unit_type_id;
        else
        INSERT INTO `merchant_unit_type`(`name`,`merchant_id`,`created_by`,`created_date`,`last_update_by`)
		VALUES(@unit_type,_merchant_id,_user_id,CURRENT_TIMESTAMP(),_user_id);
        SET @unit_type_id=LAST_INSERT_ID();
        end if;
        end if;
		INSERT INTO `merchant_product`(`merchant_id`,`sac_code`,`product_name`,`description`,`gst_percent`,`price`,`unit_type_id`,
        `unit_type`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_merchant_id,@sac_code,@item,@description,@gst,@product_rate,@unit_type_id,@unit_type,_user_id,CURRENT_TIMESTAMP(),_user_id);
        SET @product_id=LAST_INSERT_ID();
    end if;

	if(@particular_id>0)then
		if(_staging=1)then
			UPDATE `staging_invoice_particular` SET `product_id` = @product_id,`item` = @item,`annual_recurring_charges` = @annual_recurring_charges,`sac_code` = @sac_code,`description` = @description,`qty` = @qty,`unit_type`=@unit_type,`rate` = @rate,`gst` = @gst,`tax_amount` = @tax_amount,`discount` = @discount,`total_amount` = @total_amount,`narrative` = @narrative,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @particular_id;
        else
			UPDATE `invoice_particular` SET `product_id` = @product_id,`item` = @item,`annual_recurring_charges` = @annual_recurring_charges,`sac_code` = @sac_code,`description` = @description,`qty` = @qty,`unit_type`=@unit_type,`rate` = @rate,`gst` = @gst,`tax_amount` = @tax_amount,`discount` = @discount,`total_amount` = @total_amount,`narrative` = @narrative,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @particular_id;
		end if;
	else
    if(@item<>'' or @total_amount<>0)then
		if(_staging=1)then
        INSERT INTO `staging_invoice_particular`(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,
			`qty`,`unit_type`,`rate`,`gst`,`tax_amount`,`discount`,`total_amount`,`narrative`,`bulk_id`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_payment_request_id,@product_id,@item,@annual_recurring_charges,@sac_code,@description,@qty,@unit_type,@rate,@gst,@tax_amount,@discount,@total_amount,@narrative,_bulk_id,_user_id,CURRENT_TIMESTAMP(),_user_id);
		else
				INSERT INTO `invoice_particular`(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,
			`qty`,`unit_type`,`rate`,`gst`,`tax_amount`,`discount`,`total_amount`,`narrative`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_payment_request_id,@product_id,@item,@annual_recurring_charges,@sac_code,@description,@qty,@unit_type,@rate,@gst,@tax_amount,@discount,@total_amount,@narrative,_user_id,CURRENT_TIMESTAMP(),_user_id);
		end if;
	end if;
    end if;

END WHILE;

if(_staging<>1)then
call `stock_management`(_merchant_id,_payment_request_id,3,1);
end if;

SET @message='success';
commit;

select @message as 'message';


END
