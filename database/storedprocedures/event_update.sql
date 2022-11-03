CREATE DEFINER=`root`@`localhost` PROCEDURE `event_update`(_payment_request_id varchar(10),_event_name varchar(250),_title varchar(200),_short_description varchar(300),_venue varchar(250),_description longtext,_duration INT,_occurence INT,_column longtext,_column_value longtext,
_mandatory longtext,_datatype longtext,_position longtext,_exist_value longtext,_invoice_id longtext,_bannerext varchar(10),_start_date longtext,_end_date longtext,_start_time longtext,_end_time longtext,_epackage_id longtext,_epackage_name longtext,_epackage_desc longtext,_eunitavailable longtext,_esold_out longtext,_eunitcost longtext,_emin_price longtext,_emax_price longtext,_emin_seat longtext,_emax_seat longtext,_epackage_coupon longtext,
_ecategory_name longtext,_category_name longtext, _epackage_type longtext,_eis_flexible longtext,_epackage_occurence longtext,_etax_text longtext,_etax longtext,_package_type longtext,_package_occurence longtext,_tax_text longtext,_tax longtext,
_package_name longtext,_package_desc longtext,_unitavailable longtext,_unitcost longtext,_min_price longtext,_max_price longtext,_min_seat longtext,_max_seat longtext,_package_coupon longtext,_franchise_id INT,_vendor_id INT,_is_flexible longtext,_payee_capture longtext,_attendees_capture longtext,_coupon_code INT,_event_type INT,_unit_type varchar(20),_artist varchar(500),_artist_label varchar(45),_tnc longtext,_privacy longtext,_stop_booking_string varchar(10))
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
SET @request_id=_payment_request_id;

select template_id,user_id into @template_id,@user_id from event_request where event_request_id=_payment_request_id;

update invoice_template set template_name =_event_name,`last_update_date`=CURRENT_TIMESTAMP() where template_id=@template_id;

UPDATE `event_request` SET `event_name` = _event_name,`title`=_title,`short_description`=_short_description,`venue` = _venue,`description` = _description,`duration` = _duration
,`occurence` = _occurence,`franchise_id`=_franchise_id,`vendor_id`=_vendor_id,`unit_type`=_unit_type,`artist`=_artist,`artist_label`=_artist_label,`tnc`=_tnc,`cancellation_policy`=_privacy
,`coupon_code`=_coupon_code,`payee_capture`=_payee_capture,`attendees_capture`=_attendees_capture,`stop_booking_time`=_stop_booking_string
,`last_update_date` = CURRENT_TIMESTAMP()WHERE event_request_id=_payment_request_id;


update invoice_column_values set `is_active`=0 where `payment_request_id`=_payment_request_id;
WHILE _invoice_id != '' > 0 DO
    SET @existid  = SUBSTRING_INDEX(_invoice_id, @separator, 1);
    SET @existvalue  = SUBSTRING_INDEX(_exist_value, @separator, 1);

update invoice_column_values set `value`= @existvalue,`last_update_date`=CURRENT_TIMESTAMP(),`is_active`=1 where `invoice_id` = @existid;
  
    SET _invoice_id = SUBSTRING(_invoice_id, CHAR_LENGTH(@existid) + @separatorLength + 1);
    SET _exist_value = SUBSTRING(_exist_value, CHAR_LENGTH(@existvalue) + @separatorLength + 1);
END WHILE;

select max(column_position) into @column_position from invoice_column_metadata where template_id=@template_id;


SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
if(@column_position<5)then
SET @column_position=5;
end if;
if(@column_position>0)then
SET @column_position=@column_position + 1;
else
SET @column_position=3;
end if;
WHILE _column != '' > 0 DO
    SET @column_name  = SUBSTRING_INDEX(_column, @separator, 1);
    SET @column_value  = SUBSTRING_INDEX(_column_value, @separator, 1);
   
Set @mandatory=0;
SET @datatype='text';
SET @position=@column_position;

SET @is_delete=1;


INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, `position`,
`column_type`,`is_mandatory`,`is_delete_allow`,`save_table_name`, `column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,@datatype, @position,@column_name,'L','H',@mandatory,@is_delete,'metadata',Null,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();


INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (_payment_request_id,@column_id,@column_value,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
    
        SET @column_position=@column_position + 1;

    SET _column = SUBSTRING(_column, CHAR_LENGTH(@column_name) + @separatorLength + 1);
    SET _column_value = SUBSTRING(_column_value, CHAR_LENGTH(@column_value) + @separatorLength + 1);

END WHILE;



update event_occurence set `is_active`=0 where `event_request_id`=_payment_request_id;

WHILE _start_date != '' > 0 DO
    SET @start_date  = SUBSTRING_INDEX(_start_date, @separator, 1);
	SET @end_date  = SUBSTRING_INDEX(_end_date, @separator, 1);
    SET @start_time  = SUBSTRING_INDEX(_start_time, @separator, 1);
    SET @end_time  = SUBSTRING_INDEX(_end_time, @separator, 1);
	SET @occurence_id=0;
     if(@start_time ='')then
    set @start_time=NULL;
    set @end_time=NULL;
    end if;
    select occurence_id into @occurence_id from event_occurence where start_date=@start_date and (start_time is null OR start_time=@start_time) and event_request_id=@request_id;
    if(@occurence_id=0)then
INSERT INTO `event_occurence`(`event_request_id`,`start_date`,`end_date`,`start_time`,`end_time`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
values(@request_id,@start_date,@end_date,@start_time,@end_time,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
  else
  update event_occurence set start_date=@start_date , end_date=@end_date,`start_time`=@start_time,`end_time` =@end_time, is_active=1 where occurence_id=@occurence_id;
  end if;
    SET _start_date = SUBSTRING(_start_date, CHAR_LENGTH(@start_date) + @separatorLength + 1);
    SET _end_date = SUBSTRING(_end_date, CHAR_LENGTH(@end_date) + @separatorLength + 1);
	SET _start_time = SUBSTRING(_start_time, CHAR_LENGTH(@start_time) + @separatorLength + 1);
	SET _end_time = SUBSTRING(_end_time, CHAR_LENGTH(@end_time) + @separatorLength + 1);
END WHILE;



SELECT min(start_date) into @start_date FROM event_occurence where event_request_id=@request_id and is_active=1;
SELECT max(end_date) into @end_date FROM event_occurence where event_request_id=@request_id and is_active=1;

update event_request set event_from_date=@start_date , event_to_date=@end_date where event_request_id=@request_id;



update event_package set `is_active`=0 where `event_request_id`=_payment_request_id;


WHILE _epackage_name != '' > 0 DO
    SET @epackage_id  = SUBSTRING_INDEX(_epackage_id, @separator, 1);
    SET @epackage_name  = SUBSTRING_INDEX(_epackage_name, @separator, 1);
    SET @epackage_desc  = SUBSTRING_INDEX(_epackage_desc, @separator, 1);
    SET @eunitavailable  = SUBSTRING_INDEX(_eunitavailable, @separator, 1);
    SET @esold_out  = SUBSTRING_INDEX(_esold_out, @separator, 1);
    SET @eunitcost  = SUBSTRING_INDEX(_eunitcost, @separator, 1);
    SET @emin_price  = SUBSTRING_INDEX(_emin_price, @separator, 1);
    SET @emax_price  = SUBSTRING_INDEX(_emax_price, @separator, 1);
    SET @emin_seat  = SUBSTRING_INDEX(_emin_seat, @separator, 1);
	SET @emax_seat  = SUBSTRING_INDEX(_emax_seat, @separator, 1);
    SET @epackage_coupon  = SUBSTRING_INDEX(_epackage_coupon, @separator, 1);
    SET @ecategory_name  = SUBSTRING_INDEX(_ecategory_name, @separator, 1);
    SET @eis_flexible  = SUBSTRING_INDEX(_eis_flexible, @separator, 1);
	
    
    SET @epackage_type  = SUBSTRING_INDEX(_epackage_type, @separator, 1);
    SET @epackage_occurence  = SUBSTRING_INDEX(_epackage_occurence, @separator, 1);
    SET @etax_text  = SUBSTRING_INDEX(_etax_text, @separator, 1);
    SET @etax  = SUBSTRING_INDEX(_etax, @separator, 1);
    
    if(@emin_price='') then
    SET @emin_price =0;
    end if;
    
    if(@emax_price='') then
    SET @emax_price =0;
    end if;
    
    if(@emax_price =0)then
    SET @is_flexible =0;
    end if;
    
    
    if(@eunitcost='') then
    SET @eunitcost =0;
    end if;
    
    if(@epackage_coupon='') then
    SET @epackage_coupon =0;
    end if;
    
        
    if(@etax='') then
    SET @etax =0;
    end if;
    
   

   UPDATE `event_package` SET `package_name` = @epackage_name,`package_description` = @epackage_desc,`seats_available` = @eunitavailable,`sold_out`=@esold_out
   ,`min_seat` = @emin_seat,`max_seat` = @emax_seat,`price` = @eunitcost,`min_price` = @emin_price
   ,`max_price` = @emax_price,`coupon_code` = @epackage_coupon,`is_flexible` = @eis_flexible
   ,`package_type` = @epackage_type,`occurence` = @epackage_occurence,`tax_text` = @etax_text,`tax` = @etax,`category_name`=@ecategory_name
   ,`is_active` = 1 WHERE package_id=@epackage_id;



  
    SET _epackage_id = SUBSTRING(_epackage_id, CHAR_LENGTH(@epackage_id) + @separatorLength + 1);
    SET _epackage_name = SUBSTRING(_epackage_name, CHAR_LENGTH(@epackage_name) + @separatorLength + 1);
    SET _epackage_desc = SUBSTRING(_epackage_desc, CHAR_LENGTH(@epackage_desc) + @separatorLength + 1);
    SET _eunitavailable = SUBSTRING(_eunitavailable, CHAR_LENGTH(@eunitavailable) + @separatorLength + 1);
    SET _esold_out = SUBSTRING(_esold_out, CHAR_LENGTH(@esold_out) + @separatorLength + 1);
    SET _eunitcost = SUBSTRING(_eunitcost, CHAR_LENGTH(@eunitcost) + @separatorLength + 1);
    SET _emin_price = SUBSTRING(_emin_price, CHAR_LENGTH(@emin_price) + @separatorLength + 1);
    SET _emax_price = SUBSTRING(_emax_price, CHAR_LENGTH(@emax_price) + @separatorLength + 1);
    SET _emin_seat = SUBSTRING(_emin_seat, CHAR_LENGTH(@emin_seat) + @separatorLength + 1);
    SET _emax_seat = SUBSTRING(_emax_seat, CHAR_LENGTH(@emax_seat) + @separatorLength + 1);
    SET _epackage_coupon = SUBSTRING(_epackage_coupon, CHAR_LENGTH(@epackage_coupon) + @separatorLength + 1);
    SET _epackage_type = SUBSTRING(_epackage_type, CHAR_LENGTH(@epackage_type) + @separatorLength + 1);
    SET _epackage_occurence = SUBSTRING(_epackage_occurence, CHAR_LENGTH(@epackage_occurence) + @separatorLength + 1);
    SET _etax_text = SUBSTRING(_etax_text, CHAR_LENGTH(@etax_text) + @separatorLength + 1);
    SET _etax = SUBSTRING(_etax, CHAR_LENGTH(@etax) + @separatorLength + 1);
	SET _ecategory_name = SUBSTRING(_ecategory_name, CHAR_LENGTH(@ecategory_name) + @separatorLength + 1);
    SET _eis_flexible = SUBSTRING(_eis_flexible, CHAR_LENGTH(@eis_flexible) + @separatorLength + 1);

	
END WHILE;



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
    
    SET @package_type  = SUBSTRING_INDEX(_package_type, @separator, 1);
    SET @package_occurence  = SUBSTRING_INDEX(_package_occurence, @separator, 1);
    SET @tax_text  = SUBSTRING_INDEX(_tax_text, @separator, 1);
    SET @tax  = SUBSTRING_INDEX(_tax, @separator, 1);
    SET @category_name  = SUBSTRING_INDEX(_category_name, @separator, 1);

    
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
    
     
	if(@tax='') then
    SET @tax =0;
    end if;
 

	INSERT INTO `event_package`(`event_request_id`,`package_name`,`package_description`,`seats_available`,`min_seat`,`max_seat`,`price`,`coupon_code`,`min_price`,`max_price`,`is_flexible`,
    `package_type`,`occurence`,`tax_text`,`tax`,`category_name`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)values(@request_id,@package_name,@package_desc,@unitavailable,@min_seat,@max_seat,@unitcost,@package_coupon,@min_price,@max_price,@is_flexible
,@package_type, @package_occurence,@tax_text,@tax,@category_name,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

  
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
    
	SET _package_type = SUBSTRING(_package_type, CHAR_LENGTH(@package_type) + @separatorLength + 1);
    SET _package_occurence = SUBSTRING(_package_occurence, CHAR_LENGTH(@package_occurence) + @separatorLength + 1);
    SET _tax_text = SUBSTRING(_tax_text, CHAR_LENGTH(@tax_text) + @separatorLength + 1);
    SET _tax = SUBSTRING(_tax, CHAR_LENGTH(@tax) + @separatorLength + 1);
	SET _category_name = SUBSTRING(_category_name, CHAR_LENGTH(@category_name) + @separatorLength + 1);

	
END WHILE;


commit;
SET @message = 'success';
select @request_id as 'request_id',@message as 'message',@template_id as 'template_id';



END
