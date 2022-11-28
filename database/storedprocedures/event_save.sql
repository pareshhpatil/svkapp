CREATE DEFINER=`root`@`localhost` PROCEDURE `event_save`(_user_id char(10),_merchant_id char(10),_event_name varchar(250),_title varchar(200),_short_description varchar(300),_venue varchar(500),_description longtext,_duration INT,_occurence INT,_column longtext,_column_value longtext,_mandatory longtext,_datatype longtext
,_position longtext,_bannerext varchar(10),_start_date longtext,_end_date longtext,_start_time longtext,_end_time longtext,_package_name longtext,_package_desc longtext,_unitavailable longtext,
_unitcost longtext,_min_price longtext,_max_price longtext,_min_seat longtext,_max_seat longtext,_tax_text longtext,_tax longtext,_package_coupon longtext,_is_flexible longtext,_payee_capture longtext,_attendees_capture longtext,_coupon_code INT,_franchise_id INT,_vendor_id INT,_event_type INT
,_booking_unit varchar(20),_artist varchar(250),_artist_label varchar(45),_category_name longtext,_event_tnc longtext,_cancellation_policy longtext,_package_type longtext,_package_occurence longtext,_stop_booking_string varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
        show errors;
		END; 
START TRANSACTION;


set @pay_req_status=0;
Set @zero=0;
Set @one=1;
SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

SELECT GENERATE_SEQUENCE('Template_id') INTO @template_id;
SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @request_id;



INSERT INTO `invoice_template` (`template_id`, `user_id`,`merchant_id`, `template_name`,`template_type`, `banner_path`,`created_by`, `created_date`, `last_update_by`, `last_update_date`) 
VALUES (@template_id,_user_id,_merchant_id,_event_name,'event','',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());


INSERT INTO `event_request`(`event_request_id`,`user_id`,`merchant_id`,`template_id`,`event_name`,`title`,`short_description`,`event_type`,`venue`,`description`,`duration`,`occurence`,`coupon_code`
,`payee_capture`,`attendees_capture`,`franchise_id`,`vendor_id`,`unit_type`,`artist`,`artist_label`,`tnc`,`cancellation_policy`,`stop_booking_time`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@request_id,_user_id,_merchant_id,@template_id,_event_name,_title,_short_description,_event_type,_venue,_description,_duration,_occurence,_coupon_code,_payee_capture
,_attendees_capture,_franchise_id,_vendor_id,_booking_unit,_artist,_artist_label,_event_tnc,_cancellation_policy,_stop_booking_string,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());



SET @column_position=5;

WHILE _column != '' > 0 DO
    SET @column_name  = SUBSTRING_INDEX(_column, @separator, 1);
    SET @column_value  = SUBSTRING_INDEX(_column_value, @separator, 1);
    SET @mandatory  = SUBSTRING_INDEX(_mandatory, @separator, 1);
    SET @datatype  = SUBSTRING_INDEX(_datatype, @separator, 1);
    SET @position  = SUBSTRING_INDEX(_position, @separator, 1);
    
SET @is_delete=0;   
if(@mandatory=2) then
Set @mandatory=0;
SET @is_delete=1;
end if;

SET @col_position=@position;

if(@position=-1)then
SET @column_position=@column_position + 1;
SET @col_position=@column_position;
end if;


INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, `position`,
`column_type`,`is_mandatory`,`is_delete_allow`,`save_table_name`, `column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,@datatype, @col_position,@column_name,'L','H',@mandatory,@is_delete,'metadata',Null,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();


INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (@request_id,@column_id,@column_value,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
    
   
    SET _column = SUBSTRING(_column, CHAR_LENGTH(@column_name) + @separatorLength + 1);
    SET _column_value = SUBSTRING(_column_value, CHAR_LENGTH(@column_value) + @separatorLength + 1);
    SET _mandatory = SUBSTRING(_mandatory, CHAR_LENGTH(@mandatory) + @separatorLength + 1);
    SET _datatype = SUBSTRING(_datatype, CHAR_LENGTH(@datatype) + @separatorLength + 1);
    SET _position = SUBSTRING(_position, CHAR_LENGTH(@position) + @separatorLength + 1);

END WHILE;





WHILE _start_date != '' > 0 DO
    SET @start_date  = SUBSTRING_INDEX(_start_date, @separator, 1);
	SET @end_date  = SUBSTRING_INDEX(_end_date, @separator, 1);
    SET @start_time  = SUBSTRING_INDEX(_start_time, @separator, 1);
    SET @end_time  = SUBSTRING_INDEX(_end_time, @separator, 1);
    
    if(@start_time ='')then
    set @start_time=NULL;
    set @end_time=NULL;
    end if;
	
INSERT INTO `event_occurence`(`event_request_id`,`start_date`,`end_date`,`start_time`,`end_time`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
values(@request_id,@start_date,@end_date,@start_time,@end_time,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
  
    SET _start_date = SUBSTRING(_start_date, CHAR_LENGTH(@start_date) + @separatorLength + 1);
    SET _end_date = SUBSTRING(_end_date, CHAR_LENGTH(@end_date) + @separatorLength + 1);
	SET _start_time = SUBSTRING(_start_time, CHAR_LENGTH(@start_time) + @separatorLength + 1);
	SET _end_time = SUBSTRING(_end_time, CHAR_LENGTH(@end_time) + @separatorLength + 1);
END WHILE;

SELECT 
    MIN(start_date)
INTO @start_date FROM
    event_occurence
WHERE
    event_request_id = @request_id;
SELECT 
    MAX(end_date)
INTO @end_date FROM
    event_occurence
WHERE
    event_request_id = @request_id;

UPDATE event_request 
SET 
    event_from_date = @start_date,
    event_to_date = @end_date
WHERE
    event_request_id = @request_id;

WHILE _package_name != '' > 0 DO
    SET @package_name  = SUBSTRING_INDEX(_package_name, @separator, 1);
    SET @package_desc  = SUBSTRING_INDEX(_package_desc, @separator, 1);
    SET @unitavailable  = SUBSTRING_INDEX(_unitavailable, @separator, 1);
    SET @unitcost  = SUBSTRING_INDEX(_unitcost, @separator, 1);
    SET @min_price  = SUBSTRING_INDEX(_min_price, @separator, 1);
    SET @max_price  = SUBSTRING_INDEX(_max_price, @separator, 1);
    SET @min_seat  = SUBSTRING_INDEX(_min_seat, @separator, 1);
	SET @max_seat  = SUBSTRING_INDEX(_max_seat, @separator, 1);
    SET @package_coupon  = SUBSTRING_INDEX(_package_coupon, @separator, 1);
    SET @is_flexible  = SUBSTRING_INDEX(_is_flexible, @separator, 1);
    
    SET @category_name  = SUBSTRING_INDEX(_category_name, @separator, 1);
	SET @package_type  = SUBSTRING_INDEX(_package_type, @separator, 1);
	SET @package_occurence  = SUBSTRING_INDEX(_package_occurence, @separator, 1);
	SET @tax_text  = SUBSTRING_INDEX(_tax_text, @separator, 1);
	SET @tax  = SUBSTRING_INDEX(_tax, @separator, 1);
    
    if(@min_price='') then
    SET @min_price =0;
    end if;
    
    if(@max_price='') then
    SET @max_price =0;
    end if;
    
    
    if(@unitcost='') then
    SET @unitcost =0;
    end if;
    
    if(@package_coupon='') then
    SET @package_coupon =0;
    end if;
    

    

    
    SET @total=@unitcost+@tax_amount;
    
	INSERT INTO `event_package`(`event_request_id`,`package_name`,`package_description`,`seats_available`,`min_seat`,`max_seat`,`price`,`coupon_code`,`min_price`,`max_price`,`is_flexible`
    ,`tax_text`,`tax`,`category_name`,`package_type`,`occurence`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
values(@request_id,@package_name,@package_desc,@unitavailable,@min_seat,@max_seat,@unitcost,@package_coupon,@min_price,@max_price,@is_flexible
,@tax_text,@tax,@category_name,@package_type,@package_occurence,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

  
    SET _package_name = SUBSTRING(_package_name, CHAR_LENGTH(@package_name) + @separatorLength + 1);
    SET _package_desc = SUBSTRING(_package_desc, CHAR_LENGTH(@package_desc) + @separatorLength + 1);
    SET _unitavailable = SUBSTRING(_unitavailable, CHAR_LENGTH(@unitavailable) + @separatorLength + 1);
    SET _unitcost = SUBSTRING(_unitcost, CHAR_LENGTH(@unitcost) + @separatorLength + 1);
    SET _min_price = SUBSTRING(_min_price, CHAR_LENGTH(@min_price) + @separatorLength + 1);
    SET _max_price = SUBSTRING(_max_price, CHAR_LENGTH(@max_price) + @separatorLength + 1);
    SET _min_seat = SUBSTRING(_min_seat, CHAR_LENGTH(@min_seat) + @separatorLength + 1);
    SET _max_seat = SUBSTRING(_max_seat, CHAR_LENGTH(@max_seat) + @separatorLength + 1);
    SET _package_coupon = SUBSTRING(_package_coupon, CHAR_LENGTH(@package_coupon) + @separatorLength + 1);
    SET _is_flexible = SUBSTRING(_is_flexible, CHAR_LENGTH(@is_flexible) + @separatorLength + 1);
    
	SET _category_name = SUBSTRING(_category_name, CHAR_LENGTH(@category_name) + @separatorLength + 1);
    SET _package_type = SUBSTRING(_package_type, CHAR_LENGTH(@package_type) + @separatorLength + 1);
    SET _package_occurence = SUBSTRING(_package_occurence, CHAR_LENGTH(@package_occurence) + @separatorLength + 1);
    SET _tax_text = SUBSTRING(_tax_text, CHAR_LENGTH(@tax_text) + @separatorLength + 1);
    SET _tax = SUBSTRING(_tax, CHAR_LENGTH(@tax) + @separatorLength + 1);
	
END WHILE;


commit;
SET @message = 'success';
SELECT 
    @request_id AS 'request_id',
    @message AS 'message',
    @template_id AS 'template_id';



END
