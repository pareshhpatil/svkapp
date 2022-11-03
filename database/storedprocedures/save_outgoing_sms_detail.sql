CREATE DEFINER=`root`@`localhost` PROCEDURE `save_outgoing_sms_detail`(_promotion_id INT,_customer_id longtext,_mobile_numbers longtext,_user_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);


WHILE _customer_id != '' > 0 DO
    SET @customer_id  = SUBSTRING_INDEX(_customer_id, @separator, 1);

	select customer_code,concat(first_name,' ',last_name),mobile into @customer_code,@customer_name,@customer_mobile from customer where customer_id=@customer_id;

INSERT INTO `merchant_outgoing_sms_detail`(`promotion_id`,`customer_id`,`customer_code`,`customer_name`,`mobile`,`created_by`,`created_date`,
`last_update_by`)VALUES(_promotion_id,@customer_id,@customer_code,@customer_name,@customer_mobile,_user_id,CURRENT_TIMESTAMP(),_user_id);

    SET _customer_id = SUBSTRING(_customer_id, CHAR_LENGTH(@customer_id) + @separatorLength + 1);

END WHILE;




WHILE _mobile_numbers != '' > 0 DO
    SET @mobile_number  = SUBSTRING_INDEX(_mobile_numbers, @separator, 1);

INSERT INTO `merchant_outgoing_sms_detail`(`promotion_id`,`customer_id`,`customer_code`,`customer_name`,`mobile`,`created_by`,`created_date`,
`last_update_by`)VALUES(_promotion_id,0,'','',@mobile_number,_user_id,CURRENT_TIMESTAMP(),_user_id);

    SET _mobile_numbers = SUBSTRING(_mobile_numbers, CHAR_LENGTH(@mobile_number) + @separatorLength + 1);

END WHILE;


commit;
SET @message = 'success';
select @message as 'message'; 


END
