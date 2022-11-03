CREATE DEFINER=`root`@`localhost` PROCEDURE `template_save`(template_name varchar(45),_template_type varchar(45),_merchant_id varchar(10),user_id varchar(10)
,_main_header_id longtext,_main_header_default longtext,_customer_column longtext,_custom_column longtext,_header longtext,_position longtext,_column_type longtext,_sort longtext
,_column_position longtext,_function_id longtext,_function_param longtext,_function_val longtext,_is_delete longtext,_headerdatatype longtext,_headertablename longtext,_headermandatory longtext,_tnc longtext,_default_value longtext,_particular_total varchar(45)
,_tax_total varchar(45),ext varchar(10),_maxposition varchar(10),_pc longtext,_pd varchar(500),_td varchar(45),_plugin longtext,_profile_id INT,_created_by varchar(10),OUT message VARCHAR(200),OUT _template_id VARCHAR(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		SET message = 'failed';
			ROLLBACK;
		END; 
START TRANSACTION;

select generate_sequence('Template_id') into @template_id;
if(ext!='.')then
SET @image_name = CONCAT(MD5(@template_id),ext); 
end if;

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

INSERT INTO `invoice_template` (`template_id`,`merchant_id`, `user_id`, `template_name`,`template_type`,`image_path`,`plugin`,`profile_id`,`particular_total`,`tax_total`,`particular_column`,`default_particular`,`default_tax`,`tnc`,`created_by`, `created_date`, `last_update_by`, `last_update_date`) 
VALUES (@template_id,_merchant_id,user_id,template_name,_template_type,@image_name,_plugin,_profile_id,_particular_total,_tax_total,_pc,_pd,_td,_tnc,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());



WHILE _main_header_id != '' > 0 DO
    SET @main_header_id  = SUBSTRING_INDEX(_main_header_id, @separator, 1);
    SET @main_header_default  = SUBSTRING_INDEX(_main_header_default, @separator, 1);

SET @position=0;
select `column_name`,`datatype`,is_mandatory into @column_name,@headerdatatype,@is_mandatory from invoice_template_mandatory_fields where id=@main_header_id;

if(@is_mandatory<>1)then
SET @is_delete=1;
else
SET @is_delete=0;
end if;

INSERT INTO `invoice_column_metadata` (`template_id`,`customer_column_id`, `default_column_value`,`column_datatype`, `column_position`,`function_id`, `column_name`, `position`,
`column_type`,`is_mandatory`,`is_delete_allow`,`save_table_name`, `column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,0,@main_header_default,@headerdatatype, @main_header_id,0,@column_name,'L','M',0,@is_delete,'metadata',Null,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());

    SET _main_header_id = SUBSTRING(_main_header_id, CHAR_LENGTH(@main_header_id) + @separatorLength + 1);
    SET _main_header_default = SUBSTRING(_main_header_default, CHAR_LENGTH(@main_header_default) + @separatorLength + 1);

END WHILE;



SET @position=0;

WHILE _customer_column != '' > 0 DO
    SET @customer_column  = SUBSTRING_INDEX(_customer_column, @separator, 1);

SET @position=@position+1;

select `column_name`,`datatype`,`is_default` into @column_name,@headerdatatype,@is_default from customer_mandatory_column where id=@customer_column;

if(@is_default<>1)then
SET @is_delete=1;
else
SET @is_delete=0;
end if;

INSERT INTO `invoice_column_metadata` (`template_id`,`customer_column_id`, `column_datatype`, `column_position`,`function_id`, `column_name`, `position`,
`column_type`,`is_mandatory`,`is_delete_allow`,`save_table_name`, `column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,@customer_column,@headerdatatype, @position,0,@column_name,'L','C',0,@is_delete,'customer',Null,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());

    SET _customer_column = SUBSTRING(_customer_column, CHAR_LENGTH(@customer_column) + @separatorLength + 1);

END WHILE;


WHILE _custom_column != '' > 0 DO
    SET @custom_column  = SUBSTRING_INDEX(_custom_column, @separator, 1);

SET @position=@position+1;

select `column_name`,`column_datatype` into @column_name,@headerdatatype from customer_column_metadata where column_id=@custom_column;

INSERT INTO `invoice_column_metadata` (`template_id`,`customer_column_id`, `column_datatype`, `column_position`,`function_id`, `column_name`, `position`,
`column_type`,`is_mandatory`,`is_delete_allow`,`save_table_name`, `column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,@custom_column,@headerdatatype, @position,0,@column_name,'L','C',0,1,'customer_metadata',Null,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());

    SET _custom_column = SUBSTRING(_custom_column, CHAR_LENGTH(@custom_column) + @separatorLength + 1);

END WHILE;





SET @position=0;
SET @function_=0;
SET @max=_maxposition;
if(@max<10) then
SET @max=10;
end if;
WHILE _header != '' > 0 DO
    SET @headervalue  = SUBSTRING_INDEX(_header, @separator, 1);
    SET @displayposition  = SUBSTRING_INDEX(_position, @separator, 1);
    SET @column_type  = SUBSTRING_INDEX(_column_type, @separator, 1);
    SET @headerdatatype  = SUBSTRING_INDEX(_headerdatatype, @separator, 1);
    SET @headertablename  = SUBSTRING_INDEX(_headertablename, @separator, 1);
    SET @headermandatory  = SUBSTRING_INDEX(_headermandatory, @separator, 1);
    SET @column_position  = SUBSTRING_INDEX(_column_position, @separator, 1);
    SET @function_id  = SUBSTRING_INDEX(_function_id, @separator, 1);
    SET @function_param  = SUBSTRING_INDEX(_function_param, @separator, 1);
    SET @function_val  = SUBSTRING_INDEX(_function_val, @separator, 1);
    SET @is_delete  = SUBSTRING_INDEX(_is_delete, @separator, 1);
if(@headervalue='') then
Set @headervalue='NULL';
end if;
	
if(@column_position!='-1') then
SET @position=@column_position;
else
SET	@max= @max + 1;
SET @position=@max;
end if;

if(@is_delete=2) then
SET @is_delete=0;
end if;


if(@function_id!='-1') then
SET @function_=@function_id;
else
SET @function_=0;
end if;



if(@headermandatory=2) then
SET @headermandatory=0;
end if;


INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`,`function_id`, `column_name`, `position`,
`column_type`,`is_mandatory`,`is_delete_allow`,`save_table_name`, `column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,@headerdatatype, @position,@function_,@headervalue,@displayposition,@column_type,@headermandatory,@is_delete,@headertablename,Null,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());

SET @column_id=LAST_INSERT_ID();

if(@function_id!='-1') then
	if(@function_param<>'' and @function_val<>'')then
	INSERT INTO `column_function_mapping`(`column_id`,`function_id`,`param`,`value`,`created_by`,`created_date`,`last_update_by`,
	`last_update_date`)VALUES(@column_id,@function_,@function_param,@function_val,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());
end if;

end if;

    SET _header = SUBSTRING(_header, CHAR_LENGTH(@headervalue) + @separatorLength + 1);
    SET _headerdatatype = SUBSTRING(_headerdatatype, CHAR_LENGTH(@headerdatatype) + @separatorLength + 1);
    SET _headertablename = SUBSTRING(_headertablename, CHAR_LENGTH(@headertablename) + @separatorLength + 1);
    SET _headermandatory = SUBSTRING(_headermandatory, CHAR_LENGTH(@headermandatory) + @separatorLength + 1);
    SET _column_position = SUBSTRING(_column_position, CHAR_LENGTH(@column_position) + @separatorLength + 1);
    SET _function_id = SUBSTRING(_function_id, CHAR_LENGTH(@function_id) + @separatorLength + 1);
    SET _function_param = SUBSTRING(_function_param, CHAR_LENGTH(@function_param) + @separatorLength + 1);
    SET _function_val = SUBSTRING(_function_val, CHAR_LENGTH(@function_val) + @separatorLength + 1);
    SET _position = SUBSTRING(_position, CHAR_LENGTH(@displayposition) + @separatorLength + 1);
    SET _column_type = SUBSTRING(_column_type, CHAR_LENGTH(@column_type) + @separatorLength + 1);
    SET _is_delete = SUBSTRING(_is_delete, CHAR_LENGTH(@is_delete) + @separatorLength + 1);

END WHILE;





SET @sortorder=0;
WHILE _sort != '' > 0 DO
    SET @sort  = SUBSTRING_INDEX(_sort, @separator, 1);

SET @sortorder=@sortorder+1;

SET @col_type= SUBSTRING(@sort,1,1);
SET @col_name= SUBSTRING(@sort,2);

update `invoice_column_metadata` set sort_order=@sortorder where template_id=@template_id and `column_name`=@col_name and column_type=@col_type;

    SET _sort = SUBSTRING(_sort, CHAR_LENGTH(@sort) + @separatorLength + 1);

END WHILE;



commit;
SET message = 'success';
SET _template_id=@template_id;



END
