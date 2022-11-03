CREATE DEFINER=`root`@`localhost` PROCEDURE `customer_update`(_user_id varchar(10),_customer_id INT,_customer_code varchar(45),_first_name varchar(50),_last_name varchar(50),
_email varchar(250),_mobile varchar(15),_address varchar(250),_address2 varchar(250),_city varchar(60),_state varchar(60),_zipcode varchar(10),_column_id longtext,_column_value longtext,_exist_column_id longtext,_exist_column_value longtext,_password varchar(20),_gst varchar(20))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);


UPDATE `customer` SET `customer_code` = _customer_code,`first_name` = _first_name,`last_name` = _last_name,email=_email,mobile=_mobile,`address` = _address,`address2` = _address2,
`city` = _city,`state` = _state,`zipcode` = _zipcode,`password`=_password,`gst_number`=_gst,`last_update_by` = _user_id,`last_update_date` = CURRENT_TIMESTAMP() WHERE `customer_id` = _customer_id;


 SET @customer_id=_customer_id;

WHILE _exist_column_id != '' > 0 DO
    SET @column_id  = SUBSTRING_INDEX(_exist_column_id, @separator, 1);
    SET @column_value  = SUBSTRING_INDEX(_exist_column_value, @separator, 1);

	    update customer_column_values set value= @column_value where id=@column_id;
  
    SET _exist_column_id = SUBSTRING(_exist_column_id, CHAR_LENGTH(@column_id) + @separatorLength + 1);
    SET _exist_column_value = SUBSTRING(_exist_column_value, CHAR_LENGTH(@column_value) + @separatorLength + 1);

END WHILE;


WHILE _column_id != '' > 0 DO
    SET @column_id  = SUBSTRING_INDEX(_column_id, @separator, 1);
    SET @column_value  = SUBSTRING_INDEX(_column_value, @separator, 1);


INSERT INTO `customer_column_values`(`customer_id`,`column_id`,`value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@customer_id,@column_id,@column_value,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
  
    SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id) + @separatorLength + 1);
    SET _column_value = SUBSTRING(_column_value, CHAR_LENGTH(@column_value) + @separatorLength + 1);

END WHILE;


commit;
SET @message = 'success';
select @message as 'message';

END
