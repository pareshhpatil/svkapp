CREATE DEFINER=`root`@`localhost` PROCEDURE `save_invoice_tax`(_payment_request_id char(10),_tax_id longtext,_tax_percent longtext,_tax_applicable longtext,_tax_amt longtext,_tax_detail_id longtext,_user_id varchar(10),_staging tinyint(1),_bulk_id INT)
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
update staging_invoice_tax set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
else
update invoice_tax set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
end if;

WHILE _tax_id != '' > 0 DO

	SET @tax_id  = SUBSTRING_INDEX(_tax_id, @separator, 1);
	SET _tax_id = SUBSTRING(_tax_id, CHAR_LENGTH(@tax_id) + @separatorLength + 1);
    
	SET @tax_percent  = SUBSTRING_INDEX(_tax_percent, @separator, 1);
	SET _tax_percent = SUBSTRING(_tax_percent, CHAR_LENGTH(@tax_percent) + @separatorLength + 1);
    
    SET @tax_applicable  = SUBSTRING_INDEX(_tax_applicable, @separator, 1);
	SET _tax_applicable = SUBSTRING(_tax_applicable, CHAR_LENGTH(@tax_applicable) + @separatorLength + 1);
    
    if(@tax_applicable>0)then
    SET @tax_applicable=@tax_applicable;
    else
    SET @tax_applicable=0;
    end if;
    
    
    SET @tax_amt  = SUBSTRING_INDEX(_tax_amt, @separator, 1);
	SET _tax_amt = SUBSTRING(_tax_amt, CHAR_LENGTH(@tax_amt) + @separatorLength + 1);
    
    if(@tax_amt>0)then
    SET @tax_amt=@tax_amt;
    else
    SET @tax_amt=0;
    end if;
    
	SET @tax_detail_id  = SUBSTRING_INDEX(_tax_detail_id, @separator, 1);
	SET _tax_detail_id = SUBSTRING(_tax_detail_id, CHAR_LENGTH(@tax_detail_id) + @separatorLength + 1);

if(@tax_id>0)then
	if(@tax_detail_id>0)then
		if(_staging=1)then
			UPDATE `staging_invoice_tax` SET `tax_id` = @tax_id,`tax_percent` = @tax_percent,`applicable` = @tax_applicable,`tax_amount` = @tax_amt,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @tax_detail_id;
		else
			UPDATE `invoice_tax` SET `tax_id` = @tax_id,`tax_percent` = @tax_percent,`applicable` = @tax_applicable,`tax_amount` = @tax_amt,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @tax_detail_id;
		end if;
	else
    if(_staging=1)then
			INSERT INTO `staging_invoice_tax`(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`bulk_id`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_payment_request_id,@tax_id,@tax_percent,@tax_applicable,@tax_amt,_bulk_id,_user_id,CURRENT_TIMESTAMP(),_user_id);
		else
			INSERT INTO `invoice_tax`(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_payment_request_id,@tax_id,@tax_percent,@tax_applicable,@tax_amt,_user_id,CURRENT_TIMESTAMP(),_user_id);
		end if;
	
	end if;
end if;
END WHILE;

SET @message='success';
SET @has_expense=0;
commit;

if(_staging=0)then
	select customer_merchant_id into @customer_merchant_id from payment_request p inner join customer c on p.customer_id=c.customer_id  where p.payment_request_id=_payment_request_id;
    if(@customer_merchant_id is not null)then
		call convert_invoice_to_expense(_payment_request_id,@customer_merchant_id);
        SET @has_expense=1;
    end if;
end if;

select @message as 'message',@has_expense as 'has_expense';


END
