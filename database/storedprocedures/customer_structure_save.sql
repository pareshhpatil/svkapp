CREATE DEFINER=`root`@`localhost` PROCEDURE `customer_structure_save`(_user_id varchar(10),_merchant_id varchar(10),_prefix varchar(10),_auto_generate INT,_position longtext,_column_name longtext,
_datatype longtext,_exist_col_id longtext,_exist_col_name longtext,_exist_datatype longtext)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;


SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @seq_id=0;

 
	

 

update merchant_setting set customer_auto_generate=_auto_generate,prefix=_prefix where merchant_id=_merchant_id;


update customer_column_metadata set is_active=0 ,last_update_by=_user_id where `merchant_id` = _merchant_id;

 
WHILE _exist_col_id != '' > 0 DO
    SET @exist_col_id  = SUBSTRING_INDEX(_exist_col_id, @separator, 1);
    SET @exist_col_name  = SUBSTRING_INDEX(_exist_col_name, @separator, 1);
   	SET @exist_datatype  = SUBSTRING_INDEX(_exist_datatype, @separator, 1);


if(@exist_col_name !='') then

update customer_column_metadata set column_name=@exist_col_name,column_datatype=@exist_datatype,is_active= 1,last_update_by=_user_id where column_id = @exist_col_id;

   end if;
  
    SET _exist_col_id = SUBSTRING(_exist_col_id, CHAR_LENGTH(@exist_col_id) + @separatorLength + 1);
    SET _exist_col_name = SUBSTRING(_exist_col_name, CHAR_LENGTH(@exist_col_name) + @separatorLength + 1);
    SET _exist_datatype = SUBSTRING(_exist_datatype, CHAR_LENGTH(@exist_datatype) + @separatorLength + 1);

END WHILE;


update invoice_column_metadata i, customer_column_metadata c set i.is_active=c.is_active,i.column_name=c.column_name where i.customer_column_id=c.column_id and i.is_active =1 and i.save_table_name='customer_metadata' and c.merchant_id=_merchant_id;

 
WHILE _column_name != '' > 0 DO
    SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
    SET @position  = SUBSTRING_INDEX(_position, @separator, 1);
   	SET @datatype  = SUBSTRING_INDEX(_datatype, @separator, 1);


if(@column_name !='') then

INSERT INTO `customer_column_metadata`(`merchant_id`,`column_datatype`,`position`,`column_name`,`column_type`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(_merchant_id,@datatype,@position,@column_name,'Custom',
_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

   end if;
  
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
    SET _position = SUBSTRING(_position, CHAR_LENGTH(@position) + @separatorLength + 1);
    SET _datatype = SUBSTRING(_datatype, CHAR_LENGTH(@datatype) + @separatorLength + 1);

END WHILE;



commit;
SET @message = 'success';
select @message as 'message';

END
