CREATE DEFINER=`root`@`localhost` PROCEDURE `customer_save`(_user_id varchar(10),_merchant_id varchar(10),_customer_code varchar(45),_first_name nvarchar(50),_last_name nvarchar(50),
_email varchar(250),_mobile varchar(15),_address varchar(250),_address2 varchar(250),_city varchar(60),_state varchar(60),_zipcode varchar(10),_column_id longtext,_column_value longtext,_password varchar(20),_gst varchar(20),_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

SET @user_id=NULL;
SET @customer_status=0;
 
 if(_email<>'')then
 select user_id,2 into @user_id,@customer_status from user where email_id=_email and user_status=2 and email_id<>'' limit 1;
 end if;


 INSERT INTO `customer`(`merchant_id`,`customer_code`,`user_id`,`first_name`,`last_name`,`email`,`mobile`,`address`,`address2`,`city`,`state`,`zipcode`,`password`,`gst_number`,`bulk_id`,`customer_status`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
 VALUES(_merchant_id,_customer_code,@user_id,_first_name,_last_name,_email,_mobile,_address,_address2,_city,_state,_zipcode,_password,_gst,_bulk_id,@customer_status,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());


 SET @customer_id=LAST_INSERT_ID();

 
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
select @message as 'message' , @customer_id as 'customer_id', _customer_code as 'customer_code', concat(_first_name,' ',_last_name) as 'customer_name', _email as 'email';

END
