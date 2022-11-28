CREATE DEFINER=`root`@`localhost` PROCEDURE `temp_shengli_cc`()
BEGIN
DECLARE bDone INT;
DECLARE req_id CHAR(10);
DECLARE mid CHAR(10);
DECLARE muid CHAR(10);
DECLARE curs CURSOR FOR select payment_request_id FROM subscription where created_by='U000000569' and is_active=1;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
END;


Drop TEMPORARY  TABLE  IF EXISTS temp_req_list;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_req_list (
    `payment_request_id` varchar(10) NOT NULL,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;


    OPEN curs;
    SET bDone = 0;
    REPEAT
		FETCH curs INTO req_id;
	
    select count(payment_request_id) into @count from temp_req_list where payment_request_id=req_id;
    if(@count<1)then
    insert into temp_req_list(payment_request_id) values(req_id);
    
    select template_id into @template_id from payment_request where payment_request_id=req_id;
    
INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',0,'billing@shenglitelecom.in','CC',0,'','U000000569',CURRENT_TIMESTAMP(),'U000000569',CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
 INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (req_id,@column_id,'billing@shenglitelecom.in','U000000569',CURRENT_TIMESTAMP(),'U000000569',CURRENT_TIMESTAMP());

    select template_id into @template_id from payment_request where payment_request_id=req_id;
    
INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',0,'rupali@shenglitelecom.in','CC',0,'','U000000569',CURRENT_TIMESTAMP(),'U000000569',CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
 INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (req_id,@column_id,'rupali@shenglitelecom.in','U000000569',CURRENT_TIMESTAMP(),'U000000569',CURRENT_TIMESTAMP());

    select template_id into @template_id from payment_request where payment_request_id=req_id;
    
INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',0,'nodalofficer@shenglitelecom.in','CC',0,'','U000000569',CURRENT_TIMESTAMP(),'U000000569',CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
 INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (req_id,@column_id,'nodalofficer@shenglitelecom.in','U000000569',CURRENT_TIMESTAMP(),'U000000569',CURRENT_TIMESTAMP());
      end if;
      
      
    UNTIL bDone END REPEAT;
    CLOSE curs;

select * from temp_req_list;

END
