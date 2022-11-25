CREATE DEFINER=`root`@`localhost` PROCEDURE `template_edit`(_template_id varchar(11),template_name varchar(45),_main_header_id longtext,_main_header_default longtext,_exist_header_default longtext,_exist_customer_column longtext,_customer_column longtext,
_custom_column longtext,_existheader longtext,_headerid longtext,_existheaderdatatype longtext,_existfunction_id longtext,_header longtext,_headerdatatype longtext,
_column_type longtext,_position longtext,_sort longtext,_function_id longtext,_exist_function_param longtext,_exist_function_val longtext,_function_param longtext,_function_val longtext,_tnc longtext,
_particular_total varchar(45),_tax_total varchar(45),ext varchar(10),_pc longtext,_pd varchar(500),_td varchar(45),_plugin longtext,_profile_id INT,_update_by varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
            
		END; 
START TRANSACTION;

set @template_id=_template_id;



if(ext!='.') then
SET @image_name = CONCAT(MD5(@template_id),ext); 
end if;



SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
 
 
update invoice_column_metadata set is_active=0 where `template_id` = @template_id and save_table_name='metadata' ;
update invoice_column_metadata set is_active=0 where `template_id` = @template_id and column_type='C' ;
update invoice_column_metadata set is_active=0 where `template_id` = @template_id and save_table_name='request' and function_id>0 ;

WHILE _exist_customer_column != '' > 0 DO
    SET @exist_customer_column  = SUBSTRING_INDEX(_exist_customer_column, @separator, 1);

update invoice_column_metadata set is_active=1 where `column_id`= @exist_customer_column;

    SET _exist_customer_column = SUBSTRING(_exist_customer_column, CHAR_LENGTH(@exist_customer_column) + @separatorLength + 1);

END WHILE;


update  `invoice_template` set  `template_name` = template_name, `default_particular`=_pd,`default_tax`=_td,`particular_column`=_pc
,`particular_total`=_particular_total,`tax_total`=_tax_total,plugin=_plugin,profile_id=_profile_id,tnc=_tnc
, `last_update_by` = _update_by, `last_update_date`=CURRENT_TIMESTAMP() 
where `template_id`= @template_id;

if(ext!='.')then
update  `invoice_template` set   `image_path`=@image_name,  `last_update_by` = _update_by, `last_update_date`=CURRENT_TIMESTAMP() 
where `template_id`= @template_id;
end if;




SET @_main_header_clone=_headerid;
WHILE _exist_header_default != '' > 0 DO
    SET @main_header_clone  = SUBSTRING_INDEX(@_main_header_clone, @separator, 1);
    SET @exist_header_default  = SUBSTRING_INDEX(_exist_header_default, @separator, 1);


update invoice_column_metadata set default_column_value=@exist_header_default,is_active=1 where column_id=@main_header_clone;

    SET @_main_header_clone = SUBSTRING(@_main_header_clone, CHAR_LENGTH(@main_header_clone) + @separatorLength + 1);
    SET _exist_header_default = SUBSTRING(_exist_header_default, CHAR_LENGTH(@exist_header_default) + @separatorLength + 1);

END WHILE;





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
,0,@main_header_default,@headerdatatype, @main_header_id,0,@column_name,'L','M',0,@is_delete,'metadata',Null,_update_by,CURRENT_TIMESTAMP(),_update_by,CURRENT_TIMESTAMP());

    SET _main_header_id = SUBSTRING(_main_header_id, CHAR_LENGTH(@main_header_id) + @separatorLength + 1);
    SET _main_header_default = SUBSTRING(_main_header_default, CHAR_LENGTH(@main_header_default) + @separatorLength + 1);

END WHILE;




select max(column_position) into @position from invoice_column_metadata where `template_id` = @template_id and column_type='C' ;

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
,@customer_column,@headerdatatype, @position,0,@column_name,'L','C',0,@is_delete,'customer',Null,_update_by,CURRENT_TIMESTAMP(),_update_by,CURRENT_TIMESTAMP());

    SET _customer_column = SUBSTRING(_customer_column, CHAR_LENGTH(@customer_column) + @separatorLength + 1);

END WHILE;


WHILE _custom_column != '' > 0 DO
    SET @custom_column  = SUBSTRING_INDEX(_custom_column, @separator, 1);

SET @position=@position+1;

select `column_name`,`column_datatype` into @column_name,@headerdatatype from customer_column_metadata where column_id=@custom_column;

INSERT INTO `invoice_column_metadata` (`template_id`,`customer_column_id`, `column_datatype`, `column_position`,`function_id`, `column_name`, `position`,
`column_type`,`is_mandatory`,`is_delete_allow`,`save_table_name`, `column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,@custom_column,@headerdatatype, @position,0,@column_name,'L','C',0,1,'customer_metadata',Null,_update_by,CURRENT_TIMESTAMP(),_update_by,CURRENT_TIMESTAMP());

    SET _custom_column = SUBSTRING(_custom_column, CHAR_LENGTH(@custom_column) + @separatorLength + 1);

END WHILE;









WHILE _headerid != '' > 0 DO
    SET @headerid  = SUBSTRING_INDEX(_headerid, @separator, 1);
    SET @existheader  = SUBSTRING_INDEX(_existheader, @separator, 1);
   	SET @existheaderdatatype  = SUBSTRING_INDEX(_existheaderdatatype, @separator, 1);
   	SET @existfunction_id  = SUBSTRING_INDEX(_existfunction_id, @separator, 1);
    SET @exist_function_param  = SUBSTRING_INDEX(_exist_function_param, @separator, 1);
    SET @exist_function_val  = SUBSTRING_INDEX(_exist_function_val, @separator, 1);
    
    update column_function_mapping set is_active=0 where column_id = @headerid;
    
if(@existfunction_id!='-1') then
SET @existfunction_=@existfunction_id;

	if(@exist_function_param<>'' and @exist_function_val<>'')then
	INSERT INTO `column_function_mapping`(`column_id`,`function_id`,`param`,`value`,`created_by`,`created_date`,`last_update_by`,
	`last_update_date`)VALUES(@headerid,@existfunction_,@exist_function_param,@exist_function_val,_update_by,CURRENT_TIMESTAMP(),_update_by,CURRENT_TIMESTAMP());
    
end if;
else
SET @existfunction_=0;
end if;

 



if(@existheader !='') then
update invoice_column_metadata set column_name= @existheader ,column_datatype=@existheaderdatatype,function_id=@existfunction_, is_active=1 where column_id = @headerid;
end if;
  
    SET _existheader = SUBSTRING(_existheader, CHAR_LENGTH(@existheader) + @separatorLength + 1);
    SET _headerid = SUBSTRING(_headerid, CHAR_LENGTH(@headerid) + @separatorLength + 1);
    SET _existheaderdatatype = SUBSTRING(_existheaderdatatype, CHAR_LENGTH(@existheaderdatatype) + @separatorLength + 1);
    SET _existfunction_id = SUBSTRING(_existfunction_id, CHAR_LENGTH(@existfunction_id) + @separatorLength + 1);
    SET _exist_function_param = SUBSTRING(_exist_function_param, CHAR_LENGTH(@exist_function_param) + @separatorLength + 1);
    SET _exist_function_val = SUBSTRING(_exist_function_val, CHAR_LENGTH(@exist_function_val) + @separatorLength + 1);

END WHILE;


select max(column_position) into @position from invoice_column_metadata where template_id=_template_id and column_type='H';

if(@position<10) then
SET @position=10;
end if;

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
 
WHILE _header != '' > 0 DO
    SET @headervalue  = SUBSTRING_INDEX(_header, @separator, 1);
     SET @displayposition  = SUBSTRING_INDEX(_position, @separator, 1);
     SET @column_type  = SUBSTRING_INDEX(_column_type, @separator, 1);
     SET @headerdatatype  = SUBSTRING_INDEX(_headerdatatype, @separator, 1);
    SET @function_id  = SUBSTRING_INDEX(_function_id, @separator, 1);
    SET @function_param  = SUBSTRING_INDEX(_function_param, @separator, 1);
    SET @function_val  = SUBSTRING_INDEX(_function_val, @separator, 1);

SET @headertablename='metadata';

if(@function_id!='-1') then
SET @function_=@function_id;
else
SET @function_=0;
end if;

if @headervalue <> '' then
SET	@position= @position + 1;

INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, `function_id`,`save_table_name`,
`position`,`column_type`, `column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,@headerdatatype, @position,@headervalue,@function_,@headertablename,@displayposition,@column_type,Null,_update_by,CURRENT_TIMESTAMP(),_update_by,CURRENT_TIMESTAMP());


SET @column_id=LAST_INSERT_ID();

if(@function_id!='-1') then
	if(@function_param<>'' and @function_val<>'')then
	INSERT INTO `column_function_mapping`(`column_id`,`function_id`,`param`,`value`,`created_by`,`created_date`,`last_update_by`,
	`last_update_date`)VALUES(@column_id,@function_,@function_param,@function_val,_update_by,CURRENT_TIMESTAMP(),_update_by,CURRENT_TIMESTAMP());
end if;
end if;

end if;

    SET _header = SUBSTRING(_header, CHAR_LENGTH(@headervalue) + @separatorLength + 1);
    SET _position = SUBSTRING(_position, CHAR_LENGTH(@displayposition) + @separatorLength + 1);
    SET _column_type = SUBSTRING(_column_type, CHAR_LENGTH(@column_type) + @separatorLength + 1);
	SET _headerdatatype = SUBSTRING(_headerdatatype, CHAR_LENGTH(@headerdatatype) + @separatorLength + 1);
    SET _function_id = SUBSTRING(_function_id, CHAR_LENGTH(@function_id) + @separatorLength + 1);
    SET _function_param = SUBSTRING(_function_param, CHAR_LENGTH(@function_param) + @separatorLength + 1);
    SET _function_val = SUBSTRING(_function_val, CHAR_LENGTH(@function_val) + @separatorLength + 1);

END WHILE;



SET @sortorder=0;
WHILE _sort != '' > 0 DO
    SET @sort  = SUBSTRING_INDEX(_sort, @separator, 1);

SET @sortorder=@sortorder+1;

SET @col_type= SUBSTRING(@sort,1,1);
SET @col_name= SUBSTRING(@sort,2);
if(@col_type='H')then
update `invoice_column_metadata` set sort_order=@sortorder where template_id=_template_id and `column_name`=@col_name and column_type in ('H','BDS');
else
update `invoice_column_metadata` set sort_order=@sortorder where template_id=_template_id and `column_name`=@col_name and column_type=@col_type;
end if;

    SET _sort = SUBSTRING(_sort, CHAR_LENGTH(@sort) + @separatorLength + 1);

END WHILE;



commit;
SET @message = 'success';
select @message as 'message';

END
