CREATE DEFINER=`root`@`localhost` PROCEDURE `save_invoice_reminder`(_payment_request_id char(10),_due_date date,_reminders longtext,_reminder_subject longtext,_reminder_sms longtext,_merchant_id char(10),_user_id char(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;


SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

update invoice_custom_reminder set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;

WHILE _reminders != '' > 0 DO

	SET @reminders  = SUBSTRING_INDEX(_reminders, @separator, 1);
	SET _reminders = SUBSTRING(_reminders, CHAR_LENGTH(@reminders) + @separatorLength + 1);
    
    if(@reminders>0)then
    SELECT DATE_SUB(_due_date, INTERVAL @reminders DAY) into @reminder_date;
    else
    SET @reminder_date=_due_date;
    end if;
    
	SET @reminder_subject  = SUBSTRING_INDEX(_reminder_subject, @separator, 1);
	SET _reminder_subject = SUBSTRING(_reminder_subject, CHAR_LENGTH(@reminder_subject) + @separatorLength + 1);
    
    SET @reminder_sms  = SUBSTRING_INDEX(_reminder_sms, @separator, 1);
	SET _reminder_sms = SUBSTRING(_reminder_sms, CHAR_LENGTH(@reminder_sms) + @separatorLength + 1);
	select id into @r_id from invoice_custom_reminder where payment_request_id=_payment_request_id and date=@reminder_date;
	if(@r_id>0)then
	UPDATE `invoice_custom_reminder` SET `subject` = @reminder_subject,`sms` = @reminder_sms,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @r_id;
	else
	INSERT INTO `invoice_custom_reminder`(`payment_request_id`,`date`,`subject`,`sms`,`merchant_id`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_payment_request_id,@reminder_date,@reminder_subject,@reminder_sms,_merchant_id,_user_id,CURRENT_TIMESTAMP(),_user_id);
	end if;
END WHILE;


SET @message='success';
commit;

select @message as 'message';


END
