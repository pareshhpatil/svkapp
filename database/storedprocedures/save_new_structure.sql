CREATE DEFINER=`root`@`localhost` PROCEDURE `save_new_structure`(_type tinyint(1),_template_id char(10),_request_id char(10),_template_type varchar(30),_merchant_id char(10))
BEGIN 
DECLARE bDone INT;
DECLARE col_id INT;
DECLARE mid CHAR(10);
DECLARE muid CHAR(10);
DECLARE curs CURSOR FOR SELECT v.column_id FROM  invoice_column_metadata m inner join invoice_column_values v on m.column_id=v.column_id  where m.template_id=_template_id and v.payment_request_id=_request_id and m.column_type in ('PF','TF') and v.is_active=1;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
END;

Drop TEMPORARY  TABLE  IF EXISTS temp_req_list;

if(_type=2)then
	update invoice_tax set is_active=0 where payment_request_id=_request_id;
	update invoice_particular set is_active=0 where payment_request_id=_request_id;
end if;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_req_list (
    `id` INT NOT NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    OPEN curs;
    SET bDone = 0;
    REPEAT
		FETCH curs INTO col_id;
      
	select count(id) into @count from temp_req_list where id=col_id;
    if(@count<1)then
		insert into temp_req_list(id) values(col_id);
        select column_type into @column_type from invoice_column_metadata where column_id=col_id;
 if(@column_type='PF') then      
	CASE _template_type
      WHEN 'society' THEN 
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        select value into @val5 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+4;
        INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`description`,`narrative`,`qty`,`rate`,`total_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@val1,'',@val5,@val3,@val2,@val4,@user_id,CURRENT_TIMESTAMP(),@user_id);

	WHEN 'school' THEN 
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`description`,`narrative`,`total_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@val1,@val2,@val4,@val3,@user_id,CURRENT_TIMESTAMP(),@user_id);
    WHEN 'hotel' THEN 
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`qty`,`rate`,`total_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@val1,@val3,@val2,@val4,@user_id,CURRENT_TIMESTAMP(),@user_id);
	WHEN 'isp' THEN 
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`description`,`annual_recurring_charges`,`total_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@val1,@val3,@val2,@val4,@user_id,CURRENT_TIMESTAMP(),@user_id);        

	END CASE;
        
        else
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        select value into @val5 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+4;

		SET @tax_id=0;  
        SELECT `tax_id` INTO @tax_id FROM `merchant_tax` WHERE `merchant_id` = _merchant_id  AND `tax_name` = @val1 and percentage=@val2 LIMIT 1;
        if(@tax_id=0) then
			INSERT INTO `merchant_tax`(`merchant_id`,`type`,`tax_name`,`percentage`,`description`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_merchant_id,1,@val1,@val2,@val5,@user_id,CURRENT_TIMESTAMP(),@user_id);
			SET @tax_id=LAST_INSERT_ID();
		end if;
        INSERT INTO `invoice_tax`(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@tax_id,@val2,@val3,@val4,@user_id,CURRENT_TIMESTAMP(),@user_id);
        end if;
	 end if;
      
    UNTIL bDone END REPEAT;
    CLOSE curs;
END
