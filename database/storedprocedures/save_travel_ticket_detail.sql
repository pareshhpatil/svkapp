CREATE DEFINER=`root`@`localhost` PROCEDURE `save_travel_ticket_detail`(_payment_request_id varchar(10),_texistid LONGTEXT,_btype LONGTEXT,_booking_date LONGTEXT,_journey_date LONGTEXT,_b_name LONGTEXT,_b_type LONGTEXT,_b_from LONGTEXT,_b_to LONGTEXT,_b_amt LONGTEXT,_b_charge LONGTEXT,_b_total LONGTEXT,_user_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;


SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);



update invoice_travel_ticket_detail set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;

WHILE _btype != '' > 0 DO
    SET @btype  = SUBSTRING_INDEX(_btype, @separator, 1);
    SET @texistid  = SUBSTRING_INDEX(_texistid, @separator, 1);
	SET @booking_date  = SUBSTRING_INDEX(_booking_date, @separator, 1);
	SET @journey_date  = SUBSTRING_INDEX(_journey_date, @separator, 1);
	SET @b_name  = SUBSTRING_INDEX(_b_name, @separator, 1);
	SET @b_type  = SUBSTRING_INDEX(_b_type, @separator, 1);
	SET @b_from  = SUBSTRING_INDEX(_b_from, @separator, 1);
	SET @b_to  = SUBSTRING_INDEX(_b_to, @separator, 1);
	SET @b_amt  = SUBSTRING_INDEX(_b_amt, @separator, 1);
	SET @b_charge  = SUBSTRING_INDEX(_b_charge, @separator, 1);
	SET @b_total  = SUBSTRING_INDEX(_b_total, @separator, 1);

	if(@btype='b')then
	SET @type=1;
	else
	SET @type=2;
	end if;
    
	if(@texistid>0)then
	
	UPDATE `invoice_travel_ticket_detail` SET `booking_date` = @booking_date,`journey_date` = @journey_date,`name` = @b_name,`vehicle_type` = @b_type,`from_station` = @b_from,
	`to_station` = @b_to,`amount` = @b_amt ,`charge` = @b_charge,`total` = @b_total,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @texistid;
	
	else
	
	INSERT INTO `invoice_travel_ticket_detail`(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`from_station`,`to_station`,`amount`,`charge`,`total`,`type`,
`created_by`,`created_date`,`last_update_by`)VALUES(_payment_request_id,@booking_date,@journey_date,@b_name,@b_type,@b_from,@b_to,@b_amt,@b_charge,@b_total,@type,_user_id,CURRENT_TIMESTAMP(),_user_id);
	
	end if;

    SET _btype = SUBSTRING(_btype, CHAR_LENGTH(@btype) + @separatorLength + 1);
    SET _texistid = SUBSTRING(_texistid, CHAR_LENGTH(@texistid) + @separatorLength + 1);
	SET _booking_date = SUBSTRING(_booking_date, CHAR_LENGTH(@booking_date) + @separatorLength + 1);
	SET _journey_date = SUBSTRING(_journey_date, CHAR_LENGTH(@journey_date) + @separatorLength + 1);
	SET _b_name = SUBSTRING(_b_name, CHAR_LENGTH(@b_name) + @separatorLength + 1);
	SET _b_type = SUBSTRING(_b_type, CHAR_LENGTH(@b_type) + @separatorLength + 1);
	SET _b_from = SUBSTRING(_b_from, CHAR_LENGTH(@b_from) + @separatorLength + 1);
	SET _b_to = SUBSTRING(_b_to, CHAR_LENGTH(@b_to) + @separatorLength + 1);
	SET _b_amt = SUBSTRING(_b_amt, CHAR_LENGTH(@b_amt) + @separatorLength + 1);
	SET _b_charge = SUBSTRING(_b_charge, CHAR_LENGTH(@b_charge) + @separatorLength + 1);
	SET _b_total = SUBSTRING(_b_total, CHAR_LENGTH(@b_total) + @separatorLength + 1);

END WHILE;


SET @message='success';
commit;

select @message as 'message';


END