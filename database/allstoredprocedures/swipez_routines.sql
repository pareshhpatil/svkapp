-- MySQL dump 10.13  Distrib 8.0.26, for Win64 (x86_64)
--
-- Host: 35.154.123.239    Database: swipez
-- ------------------------------------------------------
-- Server version	5.5.59-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping routines for database 'swipez'
--
/*!50003 DROP FUNCTION IF EXISTS `carry_forward_dues` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `carry_forward_dues`(_merchant_id char(10),_customer_id INT,_req_id char(10)) RETURNS int(11)
BEGIN

update payment_request p,contact_ledger l set p.payment_request_status=8, p.expiry_date=SUBDATE(CURDATE(),1),l.is_active=0 
where p.merchant_id=_merchant_id and p.customer_id=_customer_id and  p.payment_request_status in(0,5,4) 
and p.payment_request_id<>_req_id and p.payment_request_id=l.reference_no and p.customer_id=l.customer_id and l.type=1;

select sum(amount) into @debit from contact_ledger where customer_id=_customer_id and ledger_type='DEBIT' and is_active=1;
select sum(amount) into @credit from contact_ledger where customer_id=_customer_id and ledger_type='CREDIT' and is_active=1;
if(@credit>0)then
update customer set balance=@debit-@credit where customer_id=_customer_id;
else
update customer set balance=@debit where customer_id=_customer_id;
end if;

RETURN 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `convert_estimate_to_invoice_function` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`swipez_admin`@`%` FUNCTION `convert_estimate_to_invoice_function`(_estimate_request_id varchar(10)) RETURNS char(10) CHARSET latin1
BEGIN

SET @invoice_no_column_id=0;
SET @invoice_number='';
select template_id into @template_id from payment_request where payment_request_id=_estimate_request_id;



select column_id into @invoice_no_column_id from invoice_column_metadata where template_id=@template_id and function_id=9 and is_active=1 limit 1;

if(@invoice_no_column_id>0)then
	select `param`,`value` into @param,@seq_value from column_function_mapping where column_id=@invoice_no_column_id and is_active=1;
    if(@param='system_generated')then
		SELECT GENERATE_INVOICE_NUMBER(@seq_value) INTO @invoice_number;
    end if;
end if;

SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @req_id;

INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`created_by`,
`created_date`,`last_update_by`)
SELECT @req_id,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,1,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
@invoice_number,`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`
FROM `payment_request` where payment_request_id=_estimate_request_id;


INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
select @req_id,`column_id`,`value`,`is_active`,`created_by`,CURRENT_TIMESTAMP(),`last_update_by`,CURRENT_TIMESTAMP()
from invoice_column_values where payment_request_id=_estimate_request_id;

INSERT INTO `invoice_particular`
(`payment_request_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_particular` where payment_request_id=_estimate_request_id and is_active=1;

INSERT INTO `invoice_tax`
(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_tax` where payment_request_id=_estimate_request_id and is_active=1;


update payment_request set payment_request_status=6,converted_request_id=@req_id where payment_request_id=_estimate_request_id;

if(@invoice_no_column_id>0)then
	update invoice_column_values set `value`=@invoice_number where column_id=@invoice_no_column_id and payment_request_id=@req_id;
end if;
RETURN @req_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `delete_ledger` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `delete_ledger`(_ref varchar(10),_type tinyint(1)) RETURNS tinyint(1)
BEGIN

SELECT id, customer_id, amount,ledger_type INTO @id , @customer_id , @amount,@ledger_type FROM contact_ledger WHERE reference_no = _ref and `type`=_type and is_active=1 limit 1;

if(@ledger_type='CREDIT')then
update customer set balance = balance + @amount where customer_id=@customer_id;
else
update customer set balance = balance - @amount where customer_id=@customer_id;
end if;

update contact_ledger set is_active = 0 where id=@id;

RETURN 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `generate_customer_sequence` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_customer_sequence`(_merchant_id varchar(10)) RETURNS char(20) CHARSET latin1
begin


    UPDATE customer_sequence SET val=last_insert_id(val+1) WHERE merchant_id=_merchant_id;


    SET @seqval = last_insert_id();
    SET @len = length(@seqval);
    
    SET @subscript = (SELECT prefix FROM merchant_setting WHERE merchant_id=_merchant_id);
    
    IF @len < 6 THEN 
        SET @returnval = CONCAT(@subscript, LPAD(@seqval, 6, '0'));
    ELSE
        SET @returnval = CONCAT(@subscript, @seqval);
    END IF;
    
    RETURN @returnval;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `generate_estimate_number` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_estimate_number`(_merchant_id varchar(10)) RETURNS char(45) CHARSET latin1
begin


    UPDATE merchant_auto_invoice_number SET val=last_insert_id(val+1) WHERE merchant_id=_merchant_id and type=2;

    SET @subscript = (SELECT concat(prefix,val) FROM merchant_auto_invoice_number WHERE merchant_id=_merchant_id and type=2);
    
    
    RETURN @subscript;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `generate_invoice_number` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_invoice_number`(_auto_invoice_id varchar(10)) RETURNS char(45) CHARSET latin1
begin


    UPDATE merchant_auto_invoice_number SET val=last_insert_id(val+1) WHERE auto_invoice_id=_auto_invoice_id;

    SELECT prefix,val,length into @subscript,@seqval,@length  FROM merchant_auto_invoice_number WHERE auto_invoice_id=_auto_invoice_id;
    
    SET @len = length(@seqval);
    
    IF @len < @length THEN 
        SET @returnval = CONCAT(@subscript, LPAD(@seqval, @length, '0'));
    ELSE
        SET @returnval = CONCAT(@subscript, @seqval);
    END IF;
    
    RETURN @returnval;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `generate_random_id` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_random_id`(_type char (20)) RETURNS char(10) CHARSET latin1
begin

if(_type='xway')then    
SET @chars1='ABCDEGIJKLMNOPQRSVWXYZ';
else
SET @chars1='ABCDEGIJKLMNOPQRSTVWYZ';
end if;

SET @chars2='ABCDEFGHIJKLMNOPQRSTVWXYZ';
SET @numbers='0123456789';

SET @exist_count=1;
while @exist_count>0 DO
select concat(substring(@chars1, rand()*23+1, 1),
              substring(@chars2, rand()*24+1, 1),
              substring(@chars2, rand()*24+1, 1),
              substring(@chars2, rand()*24+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1)
             ) into @random_id;
 
	if(_type='xway')then  
		select count(xway_transaction_id) into @exist_count from xway_transaction where xway_transaction_id = @random_id;
	else
		select count(pay_transaction_id) into @exist_count from payment_transaction where pay_transaction_id = @random_id;
	end if;
    
    IF (length(@random_id) <> 10) THEN
		SET @exist_count=1;
    END IF;

 END WHILE;
    
    RETURN @random_id;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `generate_sequence` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_sequence`(seq_name char (20)) RETURNS char(10) CHARSET latin1
begin
    UPDATE sequence SET val=last_insert_id(val+1) WHERE seqname=seq_name;


    SET @seqval = last_insert_id();
    SET @len = length(@seqval);
    SET @subscript = (SELECT subscript FROM sequence WHERE seqname=seq_name);
    
    IF @len < 9 THEN 
        SET @returnval = CONCAT(@subscript, LPAD(@seqval, 9, '0'));
    ELSE
        SET @returnval = CONCAT(@subscript, @seqval);
    END IF;
    

    RETURN @returnval;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `get_customer_id` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `get_customer_id`(_customer_code varchar(45),_merchant_id varchar(10)) RETURNS char(45) CHARSET latin1
begin

select customer_id into @customer_id from customer where customer_code=_customer_code and merchant_id=_merchant_id;
    
    RETURN @customer_id;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `get_surcharge_amount` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `get_surcharge_amount`(_merchant_id varchar(10),_amount decimal(11,2),_fee_detail_id INT) RETURNS decimal(11,2)
BEGIN

SET @invoice_total=_amount;

if(_fee_detail_id=0)then
select swipez_fee_type , swipez_fee_val,pg_fee_type,pg_fee_val,pg_tax_val,surcharge_enabled,pg_surcharge_enabled,merchant_id into @swipez_fee_type,@swipez_fee_val,@pg_fee_type,@pg_fee_val,@pg_tax,@surcharge,@pg_surcharge_enabled,@merchant_id
from merchant_fee_detail where merchant_id=_merchant_id and is_active=1 order by pg_fee_val desc limit 1;
else
select swipez_fee_type , swipez_fee_val,pg_fee_type,pg_fee_val,pg_tax_val,surcharge_enabled,pg_surcharge_enabled,merchant_id into @swipez_fee_type,@swipez_fee_val,@pg_fee_type,@pg_fee_val,@pg_tax,@surcharge,@pg_surcharge_enabled,@merchant_id
from merchant_fee_detail where fee_detail_id=_fee_detail_id;
end if;

set @pg_tax_val=0;
set @swipez_fee=0;
set @EBS_fee=0;
set @pg_rate=0;
SET @convenience_fee=0;

if(@swipez_fee_type='F') then 
set @swipez_fee=@swipez_fee_val;
end if;

if(@swipez_fee_type='P') then 
set @swipez_fee=@invoice_total * @swipez_fee_val/100;
end if;

if(@surcharge=1) then
SET @swipez_total= @invoice_total + @swipez_fee;
end if;

if(@pg_fee_type='F') then 
set @EBS_fee= @pg_fee_val;
end if;

if(@pg_fee_type='P') then 
set @EBS_fee=@swipez_total * @pg_fee_val/100;
end if;

if(@pg_tax>0) then 
set @pg_tax_val= @EBS_fee * @pg_tax/100;
end if;

if(@surcharge=1 and @pg_surcharge_enabled=0)then
SET @convenience_fee=@swipez_fee + @EBS_fee + @pg_tax_val;
end if;

RETURN @convenience_fee;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `get_xway_surcharge_amount` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `get_xway_surcharge_amount`(_amount decimal(11,2),_fee_detail_id INT) RETURNS decimal(11,2)
BEGIN

SET @invoice_total=_amount;
SET @convenience_fee=0;

select surcharge_enable,pg_surcharge_enabled  into @surcharge,@pg_surcharge_enabled from xway_merchant_detail where xway_merchant_detail_id=_fee_detail_id;

if(@surcharge=1 and @pg_surcharge_enabled=0)then

select swipez_fee_type , swipez_fee_val,pg_fee_type,pg_fee_val,pg_tax_val,merchant_id
into @swipez_fee_type,@swipez_fee_val,@pg_fee_type,@pg_fee_val,@pg_tax,@merchant_id
from xway_fee_detail where xway_id=_fee_detail_id;

set @pg_tax_val=0;
set @swipez_fee=0;
set @EBS_fee=0;
set @pg_rate=0;
SET @convenience_fee=0;

if(@swipez_fee_type='F') then 
set @swipez_fee=@swipez_fee_val;
end if;

if(@swipez_fee_type='P') then 
set @swipez_fee=@invoice_total * @swipez_fee_val/100;
end if;

SET @swipez_total= @invoice_total + @swipez_fee;

if(@pg_fee_type='F') then 
set @EBS_fee= @pg_fee_val;
end if;

if(@pg_fee_type='P') then 
set @EBS_fee=@swipez_total * @pg_fee_val/100;
end if;

if(@pg_tax>0) then 
set @pg_tax_val= @EBS_fee * @pg_tax/100;
end if;


SET @convenience_fee=@swipez_fee + @EBS_fee + @pg_tax_val;
end if;

RETURN @convenience_fee;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_cashfree_revenue` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`swipez_admin`@`%` PROCEDURE `admin_cashfree_revenue`(_from_date date,_to_date date)
BEGIN
Drop TEMPORARY  TABLE  IF EXISTS temp_cf_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_cf_transaction (
    `payment_id` varchar(20) NOT NULL ,
    `captured` decimal(11,2) NULL ,
    `transaction_date` datetime  NULL,
    `transaction_id` varchar(10)  NULL,
    `company_name` varchar(100)  NULL,
    `net_amount` decimal(11,2) NULL,
    `settlement_date` datetime  NULL,
     `bank_reff` varchar(45)  NULL,
    `tdr` DECIMAL(11,2)  null ,
    `service_tax` DECIMAL(11,2)  null ,
     `payment_method` varchar(20)  NULL,
     `swipez_revenue` DECIMAL(11,2) NOT null default 0 ,
     `swipez_gst` DECIMAL(11,2) NOT null default 0,
    PRIMARY KEY (`payment_id`)) ENGINE=MEMORY;
    
 insert into temp_cf_transaction (payment_id,captured,transaction_date,transaction_id,company_name,net_amount,settlement_date,bank_reff,tdr,service_tax,payment_method)
 select s.payment_id,s.captured,s.transaction_date,s.transaction_id,m.company_name,t.net_amount,s.settlement_date,s.bank_reff,t.tdr,t.service_tax,t.payment_method 
from payment_transaction_settlement s
inner join payment_transaction_tdr t on s.transaction_id=t.transaction_id
inner join merchant m on t.merchant_id=m.user_id
where s.transaction_date>=_from_date and s.transaction_date<_to_date and s.payment_id like 'cashfree%';

update temp_cf_transaction set swipez_revenue=captured*0.005 where payment_method='NET_BANKING';
update temp_cf_transaction set swipez_revenue=captured*0.005 where payment_method='UPI' and captured>=2000;
update temp_cf_transaction set swipez_revenue=captured*0.0025 where payment_method='UPI' and captured<2000;
update temp_cf_transaction set swipez_revenue=captured*0.0025 where payment_method='CREDIT_CARD';
update temp_cf_transaction set swipez_revenue=captured*0.001,payment_method='Wallet' where payment_method not in ('CREDIT_CARD','DEBIT_CARD','NET_BANKING','UPI');
update temp_cf_transaction set swipez_gst=swipez_revenue*0.18;

select * from temp_cf_transaction;
#select sum(swipez_revenue),sum(swipez_gst) from temp_cf_transaction;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_gst_calculation_monthwise` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_gst_calculation_monthwise`(_from_date date , _to_date date,_merchant_id char(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_gst_month_set;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_month_set (
    `settelement_id` INT NOT NULL ,
PRIMARY KEY (`settelement_id`)) ENGINE=MEMORY;

if(_merchant_id<>'')then
insert into temp_gst_month_set
select id from settlement_detail where  DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date and merchant_id=_merchant_id;
else
insert into temp_gst_month_set
select id from settlement_detail where  DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date;
end if;

Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation_month;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation_month (
    `id` INT NOT NULL ,
    `settelement_id` INT NOT NULL ,
    `date` date  NULL,
    `merchant_id` varchar(20)  NULL,
    `pg_tdr_amount` decimal(11,2) NULL,
    `pg_service_tax` decimal(11,2) NULL,
    `swipez_revenue` DECIMAL(11,2)  null ,
    `swipez_service_tax` DECIMAL(11,2)  null ,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    

	insert into temp_gst_calculation_month(id,date,settelement_id,merchant_id,pg_tdr_amount,pg_service_tax,swipez_revenue,swipez_service_tax) 
	 select id,DATE_FORMAT(created_date,'%Y-%m-1'),swipez_settlement_id,merchant_id,pg_tdr_amount,pg_service_tax,swipez_revenue,swipez_service_tax
	 from revenue_detail where swipez_settlement_id in(select settelement_id from temp_gst_month_set);
       
       update temp_gst_calculation_month t, settlement_detail d set t.date = DATE_FORMAT(d.settlement_date,'%Y-%m-1') where t.settelement_id=d.id;
       
 Drop TEMPORARY  TABLE  IF EXISTS temp_caltdr;

   CREATE TEMPORARY TABLE IF NOT EXISTS temp_caltdr (
    `id` INT NOT NULL auto_increment ,
    `merchant_id` varchar(20)  NULL,
    `date` date  NULL,
    `case1_pgrev` decimal(11,2) NOT NULL default 0,
    `case1_pgtx` decimal(11,2) NOT NULL default 0,
    `case1_swrev` decimal(11,2) NOT NULL default 0,
    `case1_swtx` decimal(11,2) NOT NULL default 0,
    
    `case2_pgrev` decimal(11,2) NOT NULL default 0,
    `case2_pgtx` decimal(11,2) NOT NULL default 0,
    `case2_swrev` decimal(11,2) NOT NULL default 0,
    `case2_swtx` decimal(11,2) NOT NULL default 0,
    
    `case3_pgrev` decimal(11,2) NOT NULL default 0,
    `case3_pgtx` decimal(11,2) NOT NULL default 0,
    `case3_swrev` decimal(11,2) NOT NULL default 0,
    `case3_swtx` decimal(11,2) NOT NULL default 0,
    
    `case4_pgrev` decimal(11,2) NOT NULL default 0,
    `case4_pgtx` decimal(11,2) NOT NULL default 0,
    `case4_swrev` decimal(11,2) NOT NULL default 0,
    `case4_swtx` decimal(11,2) NOT NULL default 0,
    
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
       
         insert into temp_caltdr(merchant_id,`date`)
         select distinct merchant_id,`date` from temp_gst_calculation_month;


UPDATE temp_caltdr o 
INNER JOIN
(
   SELECT merchant_id,date,pg_service_tax,swipez_service_tax,
   SUM(pg_tdr_amount) 'pgrev',SUM(pg_service_tax) 'pgtax', SUM(swipez_revenue) 'swrev',SUM(swipez_service_tax) 'swtax'
   FROM temp_gst_calculation_month 
   where pg_service_tax>0 and swipez_service_tax>0
   GROUP BY merchant_id,date
) i ON o.merchant_id = i.merchant_id and  o.date = i.date
SET o.case1_pgrev = i.pgrev,o.case1_pgtx = i.pgtax,o.case1_swrev = i.swrev,o.case1_swtx = i.swtax
WHERE o.merchant_id = i.merchant_id and  o.date = i.date;


UPDATE temp_caltdr o 
INNER JOIN
(
   SELECT merchant_id,date,pg_service_tax,swipez_service_tax,
   SUM(pg_tdr_amount) 'pgrev',SUM(pg_service_tax) 'pgtax', SUM(swipez_revenue) 'swrev',SUM(swipez_service_tax) 'swtax'
   FROM temp_gst_calculation_month 
   where pg_service_tax>0 and swipez_service_tax=0
   GROUP BY merchant_id,date
) i ON o.merchant_id = i.merchant_id and  o.date = i.date
SET o.case2_pgrev = i.pgrev,o.case2_pgtx = i.pgtax,o.case2_swrev = i.swrev,o.case2_swtx = i.swtax
WHERE o.merchant_id = i.merchant_id and  o.date = i.date;


UPDATE temp_caltdr o 
INNER JOIN
(
   SELECT merchant_id,date,pg_service_tax,swipez_service_tax,
   SUM(pg_tdr_amount) 'pgrev',SUM(pg_service_tax) 'pgtax', SUM(swipez_revenue) 'swrev',SUM(swipez_service_tax) 'swtax'
   FROM temp_gst_calculation_month 
   where pg_service_tax=0 and swipez_service_tax>0
   GROUP BY merchant_id,date
) i ON o.merchant_id = i.merchant_id and  o.date = i.date
SET o.case3_pgrev = i.pgrev,o.case3_pgtx = i.pgtax,o.case3_swrev = i.swrev,o.case3_swtx = i.swtax
WHERE o.merchant_id = i.merchant_id and  o.date = i.date;


UPDATE temp_caltdr o 
INNER JOIN
(
   SELECT merchant_id,date,pg_service_tax,swipez_service_tax,
   SUM(pg_tdr_amount) 'pgrev',SUM(pg_service_tax) 'pgtax', SUM(swipez_revenue) 'swrev',SUM(swipez_service_tax) 'swtax'
   FROM temp_gst_calculation_month 
   where pg_service_tax=0 and swipez_service_tax=0
   GROUP BY merchant_id,date
) i ON o.merchant_id = i.merchant_id and  o.date = i.date
SET o.case4_pgrev = i.pgrev,o.case4_pgtx = i.pgtax,o.case4_swrev = i.swrev,o.case4_swtx = i.swtax
WHERE o.merchant_id = i.merchant_id and  o.date = i.date;


Drop TEMPORARY  TABLE  IF EXISTS temp_caltdr_output;

   CREATE TEMPORARY TABLE IF NOT EXISTS temp_caltdr_output (
    `id` INT NOT NULL auto_increment ,
    `merchant_id` varchar(20)  NULL,
    `merchant_name` varchar(100)  NULL,
    `customer_code` varchar(100)  NULL,
    `city_state` varchar(100)  NULL,
    `date` date  NULL,
    `tdr_with_tax` decimal(11,2) NOT NULL default 0,
    `tax` decimal(11,2) NOT NULL default 0,
    `tdr_without_tax` decimal(11,2) NOT NULL default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

insert temp_caltdr_output (merchant_id,date,tdr_with_tax,tax,tdr_without_tax)
select merchant_id,date,case1_pgrev+case2_pgrev+case1_swrev+case3_swrev,
case1_pgtx+case1_swtx+case2_pgtx+case3_swtx,case3_pgrev+case4_pgrev from temp_caltdr;

update temp_caltdr_output o,merchant m set merchant_name=company_name,customer_code=m.admin_customer_code where o.merchant_id=m.merchant_id;

update temp_caltdr_output o,merchant_billing_profile m set city_state=m.state where o.merchant_id=m.merchant_id and is_default=1;

select merchant_id,customer_code,merchant_name,city_state,DATE_FORMAT(date,'%b-%Y') as `Month`,tdr_with_tax,tax,tdr_without_tax from temp_caltdr_output;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_gst_settelement_dump` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_gst_settelement_dump`(_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');


Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation_set;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation_set (
    `settelement_id` INT NOT NULL ,
PRIMARY KEY (`settelement_id`)) ENGINE=MEMORY;

insert into temp_gst_calculation_set
select id from settlement_detail where  DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date;

Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation (
    `transaction_id` varchar(10) NOT NULL ,
    `payment_id` varchar(45)  NULL ,
    `amount` DECIMAL(11,2)  null ,
    `transaction_date` datetime  NULL,
    `customer_name` varchar(70) NULL,
    `customer_email` varchar(100) NULL,
    `customer_phone` varchar(20)  NULL,
    `settlement_amount` DECIMAL(11,2)  null ,
    `settlement_date` datetime,
    `payu_tdr_charge`  DECIMAL(11,2) null ,
    `payu_service_tax`  DECIMAL(11,2) null ,
    `payment_mode` varchar(10)  NULL,
    `utr_no` varchar(20)  NULL,
    `merchant_id` varchar(20)  NULL,
    `merchant_name` varchar(45) NULL,
    `base_tdr` decimal(11,2) NULL,
    `merchant_tdr` decimal(11,2) NULL,
    `differential` decimal(11,2) NULL,
    `swipez_revenue` DECIMAL(11,2)  null ,
    `swipez_service_tax` DECIMAL(11,2)  null ,
    `swipez_settlement_amount` DECIMAL(11,2) null ,
    `total_swipez_settlement_amount` DECIMAL(11,2)  null ,
    `swipez_bank_reff`  varchar(20)  NULL,
    `swipez_settlement_date`datetime null ,
    `swipez_settlement_id` INT NULL,
    PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;
    
    
    
    
		 insert into temp_gst_calculation(transaction_id,settlement_amount,payu_tdr_charge,payu_service_tax,merchant_id,swipez_settlement_amount,swipez_settlement_id
         ,merchant_name,swipez_revenue,merchant_tdr,base_tdr,swipez_service_tax) 
		 select transaction_id,pg_settlement_amount,pg_tdr_amount,pg_service_tax,merchant_id,swipez_amount,swipez_settlement_id,merchant_name,swipez_revenue,swipez_tdr
         ,(pg_tdr_amount*100)/amount,swipez_service_tax
         from revenue_detail where swipez_settlement_id in(select settelement_id from temp_gst_calculation_set);
    
		update temp_gst_calculation t, payu_settlement r set t.payment_id=r.payment_id,
         t.amount=r.amount,t.customer_name=r.customer_name,t.transaction_date=r.succeeded_on_date,
         t.payment_mode=r.payment_mode,t.customer_email=r.customer_email,
         t.customer_phone=r.customer_phone,t.settlement_date=r.settlement_date,t.utr_no=r.utr_number
         where t.transaction_id=r.transaction_id;
         
        
    
    
        
         
         
         update temp_gst_calculation t, settlement_detail r set t.total_swipez_settlement_amount=r.settlement_amount,
         t.swipez_bank_reff=r.bank_reference,t.swipez_settlement_date=r.settlement_date
         where t.swipez_settlement_id=r.id;
         
         update temp_gst_calculation set differential=merchant_tdr-base_tdr;
         
         
         select * from temp_gst_calculation;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_gst_tally_report` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_gst_tally_report`(_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');


Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation_set;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation_set (
    `settelement_id` INT NOT NULL ,
PRIMARY KEY (`settelement_id`)) ENGINE=MEMORY;

insert into temp_gst_calculation_set
select id from settlement_detail where  settlement_date >= @from_date and settlement_date<= @to_date;

Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation (
	`id` INT auto_increment,
    `transaction_id` varchar(10) NOT NULL ,
    `payment_id` varchar(45)  NULL ,
    `amount` DECIMAL(11,2)  null ,
    `transaction_date` datetime  NULL,
    `customer_name` varchar(70) NULL,
    `customer_email` varchar(100) NULL,
    `customer_phone` varchar(20)  NULL,
    `settlement_amount` DECIMAL(11,2)  null ,
    `settlement_date` datetime,
    `payu_tdr_charge`  DECIMAL(11,2) null ,
    `payu_service_tax`  DECIMAL(11,2) null ,
    `payment_mode` varchar(10)  NULL,
    `utr_no` varchar(20)  NULL,
    `merchant_id` varchar(20)  NULL,
    `merchant_name` varchar(45) NULL,
    `base_tdr` varchar(45) NULL,
    `merchant_tdr` decimal(11,2) NULL,
    `differential` decimal(11,2) NULL,
    `swipez_revenue` DECIMAL(11,2)  null ,
    `swipez_service_tax` DECIMAL(11,2)  null ,
    `swipez_settlement_amount` DECIMAL(11,2) null ,
    `total_swipez_settlement_amount` DECIMAL(11,2)  null ,
    `swipez_bank_reff`  varchar(20)  NULL,
    `swipez_settlement_date`datetime null ,
    `swipez_settlement_id` INT NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    
    
    
		 insert into temp_gst_calculation(transaction_id,settlement_amount,payu_tdr_charge,payu_service_tax,merchant_id,swipez_settlement_amount,swipez_settlement_id
         ,merchant_name,swipez_revenue,merchant_tdr,base_tdr,swipez_service_tax) 
		 select transaction_id,pg_settlement_amount,pg_tdr_amount,pg_service_tax,merchant_id,swipez_amount,swipez_settlement_id,merchant_name,swipez_revenue,swipez_tdr
         ,(pg_tdr_amount*100)/amount,swipez_service_tax
         from revenue_detail where swipez_settlement_id in(select settelement_id from temp_gst_calculation_set);
    
		update temp_gst_calculation t, payu_settlement r set t.payment_id=r.payment_id,
         t.amount=r.amount,t.customer_name=r.customer_name,t.transaction_date=r.succeeded_on_date,
         t.payment_mode=r.payment_mode,t.customer_email=r.customer_email,
         t.customer_phone=r.customer_phone,t.settlement_date=r.settlement_date,t.utr_no=r.utr_number
         where t.transaction_id=r.transaction_id;
         
         update temp_gst_calculation t, settlement_detail r set t.total_swipez_settlement_amount=r.settlement_amount,
         t.swipez_bank_reff=r.bank_reference,t.swipez_settlement_date=r.settlement_date
         where t.swipez_settlement_id=r.id;
         
         update temp_gst_calculation set differential=merchant_tdr-base_tdr;
         
         
         
         
         
         
         
         
         
         
         Drop TEMPORARY  TABLE  IF EXISTS temp_gst_tally;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_tally (
    `id` INT NOT NULL auto_increment,
    `transaction_type` varchar(45)  NULL ,
    `date` datetime  null ,
    `ledger_name` varchar(250) NULL,
    `amount` DECIMAL(11,2)  null ,
	`merchant_cust_id` INT NULL,
	`merchant_cust_code` varchar(45) NULL,
	`merchant_name` varchar(100) NULL,
	`merchant_id` varchar(10) NULL,
    `cr_dr` varchar(10)  NULL,
    `narration` varchar(45)  NULL,
    `sort` varchar(45)  NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
         
    insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Receipt',settlement_date,'HDFC Bank (50200002538452)',sum(settlement_amount),'Dr',utr_no,'A'
         from temp_gst_calculation group by utr_no; 
    
    insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Receipt',settlement_date,'PG Vendor',sum(amount)-sum(settlement_amount),'Dr',utr_no,'B'
         from temp_gst_calculation group by utr_no; 
         
    insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Receipt',settlement_date,'Nodal Account',sum(amount),'Cr',utr_no,'C'
         from temp_gst_calculation group by utr_no; 
         
         
         
         
      insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Payment',settlement_date,'Nodal Account',sum(total_capture),'Dr',bank_reference,'A'
         from settlement_detail where settlement_date >= @from_date and settlement_date<= @to_date group by bank_reference;   
         
         insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Payment',settlement_date,'HDFC Bank (50200002538452)',sum(settlement_amount),'Cr',bank_reference,'B'
         from settlement_detail where settlement_date >= @from_date and settlement_date<= @to_date group by bank_reference;
         
         insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort,merchant_id,merchant_name) 
		 select 'Payment',settlement_date,concat(merchant_name ,' (',merchant_id,')'),sum(total_capture)-sum(settlement_amount),'Cr',bank_reference,'C',merchant_id,merchant_name
         from settlement_detail where settlement_date >= @from_date and settlement_date<= @to_date group by bank_reference;
         
         update temp_gst_tally t,customer_column_values v set t.merchant_cust_id=v.customer_id where v.value=t.merchant_id;
         update temp_gst_tally t,customer v set t.merchant_cust_code=v.customer_code where t.merchant_cust_id=v.customer_id;
         
         update temp_gst_tally set ledger_name=concat(merchant_name,' (',merchant_cust_code,')') where sort='C' and transaction_type='Payment';

         
   select transaction_type,date,ledger_name,amount,cr_dr,narration from temp_gst_tally order by date,sort;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_merchant_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_merchant_transaction`()
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_tmerchant;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_tmerchant (
	`id` INT NOT NULL auto_increment,
	`merchant_id` char(10) NOT NULL ,
	`tcount` INT default 0,
    `xcount` INT default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

	insert into temp_tmerchant (merchant_id,tcount)
    select merchant_id,count(pay_transaction_id) from payment_transaction where created_date>='2021-01-01' and created_date<='2021-04-27' group by merchant_id;


	insert into temp_tmerchant (merchant_id,xcount)
    select merchant_id,count(xway_transaction_id) from xway_transaction where created_date>='2021-01-01' and created_date<='2021-04-27' group by merchant_id;

select t.merchant_id,company_name,sum(tcount) transaction_count,sum(xcount) xway_count from temp_tmerchant t inner join merchant m on m.merchant_id=t.merchant_id
group by t.merchant_id;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_monthly_payment_trends` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_monthly_payment_trends`()
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


Drop TEMPORARY  TABLE  IF EXISTS temp_inv;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_inv (
    `merchant_id` varchar(10) NOT NULL ,
    `company_name` varchar(100) NULL ,
    `march` INT NOT NULL default 0,
    `march_sum` decimal(11,2) NOT NULL default 0,
    `april` INT NOT NULL default 0,
    `april_sum` decimal(11,2) NOT NULL default 0,
    `may` INT NOT NULL default 0,
    `may_sum` decimal(11,2) NOT NULL default 0,
    `june` INT NOT  NULL default 0,
    `june_sum` decimal(11,2) NOT  NULL default 0,
    `july` INT NOT  NULL default 0,
    `july_sum` decimal(11,2) NOT  NULL default 0,
    `aug` INT NOT  NULL default 0,
    `aug_sum` decimal(11,2) NOT  NULL default 0,
    `sep` INT NOT  NULL default 0,
	`sep_sum`decimal(11,2) NOT  NULL default 0,
	`oct` INT NOT  NULL default 0,
    `oct_sum` decimal(11,2) NOT  NULL default 0,
    PRIMARY KEY (`merchant_id`)) ENGINE=MEMORY;
    

insert into temp_inv(merchant_id)    
select distinct merchant_id FROM payment_request where created_date>'2020-03-01' and created_date<'2020-12-01' ;

update temp_inv t, merchant m set t.company_name=m.company_name where t.merchant_id=m.merchant_id;


UPDATE temp_inv o 
INNER JOIN
(
   SELECT merchant_id, count(payment_request_id) 'cnt',sum(grand_total) 'sumu'
   FROM payment_request where 
DATE_FORMAT(created_date, "%Y-%m")='2020-03'
   GROUP BY merchant_id
) i ON o.merchant_id = i.merchant_id
SET o.march = i.cnt, o.march_sum = i.sumu;




Drop TEMPORARY  TABLE  IF EXISTS temp_inv_val;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_inv_val (
	`id` int NOT NULL auto_increment,
    `ttype` varchar(10) NOT NULL ,
    `merchant_id` varchar(10) NOT NULL ,
    `company_name` varchar(100) NULL ,
     `march` INT NOT NULL default 0,
    `april` INT NOT NULL default 0,
    `may` INT NOT NULL default 0,
    `june` INT NOT  NULL default 0,
    `july` INT NOT  NULL default 0,
    `aug` INT NOT  NULL default 0,
    `sep` INT NOT  NULL default 0,
	`oct` INT NOT  NULL default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

	insert into temp_inv_val(merchant_id,ttype,company_name,march,april,may,june,july,aug,sep,oct)  
     select merchant_id,'Count',company_name,march,april,may,june,july,aug,sep,oct from temp_inv order by merchant_id;
     
	 insert into temp_inv_val(merchant_id,ttype,company_name,march,april,may,june,july,aug,sep,oct)  
     select merchant_id,'Sum',company_name,march_sum,april_sum,may_sum,june_sum,july_sum,aug_sum,sep_sum,oct_sum from temp_inv order by merchant_id;

     select merchant_id,company_name,march,april,may,june,july,aug,sep,oct from temp_inv_val where ttype='Count' order by merchant_id;
     select merchant_id,company_name,march,april,may,june,july,aug,sep,oct from temp_inv_val where ttype='Sum' order by merchant_id;
     select * from temp_inv_val order by merchant_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_paid_merchant_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_paid_merchant_transaction`(_start_date DATE, _end_date DATE)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
 		BEGIN
        show errors;
		END;
Drop TEMPORARY  TABLE  IF EXISTS temp_tmerchant;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_tmerchant (
	`id` INT NOT NULL auto_increment,
	`merchant_id` char(10) NOT NULL ,
	`tcount` INT default 0,
    `xcount` INT default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
	insert into temp_tmerchant (merchant_id,tcount)
    select p.merchant_id,count(p.pay_transaction_id) from payment_transaction p
    inner join merchant m on m.merchant_id=p.merchant_id
    where m.merchant_plan != 2 and m.merchant_plan != 1 and DATE_FORMAT(p.created_date,'%Y-%m-%d')>=_start_date and DATE_FORMAT(p.created_date,'%Y-%m-%d')<=_end_date group by p.merchant_id;
	insert into temp_tmerchant (merchant_id,xcount)
    select x.merchant_id,count(x.xway_transaction_id) from xway_transaction x
    inner join merchant m on m.merchant_id=x.merchant_id
    where m.merchant_plan != 2 and m.merchant_plan != 1 and DATE_FORMAT(x.created_date,'%Y-%m-%d')>=_start_date and DATE_FORMAT(x.created_date,'%Y-%m-%d')<=_end_date group by x.merchant_id;
select t.merchant_id,company_name,sum(tcount) transaction_count,sum(xcount) xway_count from temp_tmerchant t inner join merchant m on m.merchant_id=t.merchant_id
group by t.merchant_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_save_merchant_package` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_save_merchant_package`(_merchant_id char(10),_plan INT,_amount decimal(11,2),_ref_no varchar(20),_sms INT,_expiry_date date)
BEGIN

if(_plan=10)then
SET @trial=1;
SET @trans_id='NA';
SET _plan=9;
SET @end_date=_expiry_date;
else
SET @trial=0;
SET @end_date=DATE_ADD(now(), INTERVAL 12 MONTH);
end if;
SET @merchant_plan=_plan;
SET @merchant_id=_merchant_id;
SET @base_amount=_amount*100/118;
SET @tax_amount=_amount-@base_amount;
SET @ref_no=_ref_no;
SET @sms_bought=_sms;

select package_name into @package_name from package where package_id=@merchant_plan;
select user_id into @user_id from merchant where merchant_id=@merchant_id;
IF(@user_id IS NULL OR @user_id = '') then
  SET @trans_id = null;
else
	select email_id,concat(first_name,' ',last_name),mobile_no  into @email,@name,@mobile from user where user_id=@user_id;
	select address,city,state,zipcode into @address,@city,@state,@zipcode from merchant_billing_profile where merchant_id=@merchant_id and is_default=1;
	
    if(@trial=0)then
    select generate_sequence('Fee_transaction_id') into @trans_id;
	INSERT INTO `package_transaction` (`package_transaction_id`, `user_id`, `merchant_id`, `payment_transaction_status`, `package_id`, `base_amount`,tax_amount,tax_text, `amount`, `narrative`, `pg_type`, `pg_ref_no`, `pg_ref_1`, `created_by`, `created_date`, `last_update_by`, `last_update_date`)
	VALUES (@trans_id, @user_id, @merchant_id, '1', @merchant_plan, @base_amount,@tax_amount,'["SGST@9%","CGST@9%"]', @base_amount+@tax_amount, @package_name, '3', @ref_no, @ref_no, 'ADMIN', current_timestamp(), 'ADMIN', current_timestamp());
	INSERT INTO `package_transaction_details`(`package_transaction_id`,`name`,`email`,`mobile`,`address`,`city`,`state`,`zipcode`,`created_date`)
	VALUES(@trans_id,@name,@email,@mobile,'','','','',current_timestamp());
    end if;
    
    if(@trial=0)then
    select end_date into @package_end_date from account WHERE merchant_id = @merchant_id AND is_active = 1 and amount_paid>0 and end_date>curdate();
	if(@package_end_date is not null)then
	SET @end_date=DATE_ADD(@package_end_date, INTERVAL 12 MONTH);
	end if;
    end if;
    
	update account set is_active=0 where merchant_id=@merchant_id;
    update merchant set merchant_plan=@merchant_plan,package_expiry_date=@end_date where merchant_id=@merchant_id;
	INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,total_invoices,`bulk_invoice`,`free_sms`,`merchant_role`,coupon,supplier,
	`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
	select @merchant_id,package_id,@trans_id,_amount,individual_invoice,total_invoices,bulk_invoice,@sms_bought,merchant_role,coupon,supplier,now(),@end_date,1,@merchant_id,now(),@merchant_id from package where package_id=@merchant_plan;
	if(@sms_bought>0)then
    INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
	`last_update_by`)VALUES(7,@merchant_id,@trans_id,@sms_bought,@sms_bought,now(),@end_date,1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');
    end if;
	if(@merchant_plan=3)then
	INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
	`last_update_by`)VALUES(6,@merchant_id,@trans_id,1,1,now(),DATE_ADD(now(), INTERVAL 12 MONTH),1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');
	INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
	`last_update_by`)VALUES(8,@merchant_id,@trans_id,1,1,now(),DATE_ADD(now(), INTERVAL 12 MONTH),1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');
	end if;
end if;
	select @trans_id as 'transaction_id';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_save_payment_gateway` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_save_payment_gateway`(_merchant_id char(10),_type INT,_pg_type INT(10),_secret_key varchar(255),_access_key varchar(255),_razorpay_account_id varchar(255),_whitelist_url varchar(255),_created_by varchar(10),_last_update_by varchar(10))
BEGIN
SET @merchant_id=_merchant_id;
SET @p_type=_type;
SET @pg_type=_pg_type;
SET @pg_val1=_access_key;
SET @pg_val2=_secret_key;
SET @pg_val3="Live";
SET @pg_val7=_razorpay_account_id;
SET @whitelist_url=_whitelist_url;
SET @pg_val8 = null;
SET @pg_val9 = null;
SET @created_by=_created_by;

SET @last_update_by = _last_update_by;
select company_name into @company_name from merchant where merchant_id=@merchant_id;
IF(@company_name IS NULL OR @company_name = '') then
   SET @pg_id = null;
else
	/*pg_type = 10 Razorpay , 7 = Cashfree */
	if(@pg_type=10)then
		SET @pg_val4 = "Swipez";
		SET @pg_val5 = @company_name;
		SET @pg_val6 = @merchant_id;
		SET @req_url = null;
		SET @ret_tname = "pg_ret_bank9";
		SET @pg_name = CONCAT('RP-',@company_name);
	elseif(@pg_type=13)then
		SET @pg_val4 = "https://prod.setu.co/api/v2/auth/token";
		SET @pg_val5 = 'https://prod.setu.co/api/v2/payment-links';
		SET @pg_val6 = _access_key;
		SET @req_url = 'https://prod.setu.co/api/v2';
		SET @pg_val7 = 'https://prod.setu.co/api/v2/refund/batch';        
		SET @ret_tname = "pg_ret_bank11";
		SET @pg_name = CONCAT('SETU-',@company_name);
        SET @pg_val1='f59d53da-9301-4f74-b308-c414f2bce473';
		SET @pg_val2='6d13d414-0838-42a3-b312-43344ab22d7d';
	else
		SET @pg_val4 = null;
		SET @pg_val5 = "https://www.swipez.in/secure/invoke/cashfree";
		SET @pg_val6 = null;
		SET @req_url = "https://www.cashfree.com/checkout/post/submit";
		SET @ret_tname = "pg_ret_bank7";
		SET @pg_name = CONCAT('CF-',@company_name);
	end if;
	if(@p_type=1) then
		if(@pg_type=7) then
			SET @status_url = "https://www.swipez.in/secure/cashfreeresponse";
		else
			SET @status_url= "https://www.swipez.in/secure/razorpayresponse";
		end if;
	else
		if(@pg_type=7) then
			SET @status_url = "https://www.swipez.in/xway/cashfreeresponse";
		else
			SET @status_url = "https://www.swipez.in/xway/razorpayresponse";
		end if;
	end if;
	INSERT INTO `payment_gateway` (`pg_name`, `pg_type`, `is_active`, `pg_val1`, `pg_val2`, `pg_val3`, `pg_val4`, `pg_val5`, `pg_val6`, `pg_val7`, `pg_val8`, `pg_val9`, `req_url` , `status_url`, `nodal_settlement`, `type`, `ret_tname`, `created_by`, `created_date`, `last_update_by`, `last_update_date`)
	VALUES (@pg_name, @pg_type, '1', @pg_val1, @pg_val2, @pg_val3, @pg_val4, @pg_val5, @pg_val6, @pg_val7, @pg_val8, @pg_val9, @req_url, @status_url, '0', '1', @ret_tname, @created_by, current_timestamp(), @last_update_by, current_timestamp());
	/*select last_insert_id() into InsertId ;*/
	SET @pg_id = LAST_INSERT_ID();
	if(@p_type=1)then
		update merchant_fee_detail set is_active=0 where merchant_id=@merchant_id;
		INSERT INTO `merchant_fee_detail` (`merchant_id`, `franchise_id`, `vendor_id`, `pg_id`, `swipez_fee_type`, `swipez_fee_val`, `pg_fee_type` , `pg_fee_val`, `pg_tax_type`, `pg_tax_val`, `surcharge_enabled`, `pg_surcharge_enabled`, `enable_tnc`, `is_active`, `created_date`, `last_update_date`)
		VALUES (@merchant_id, '0', '0', @pg_id, 'F', 0, 'F', 0, 'GST', 18.00, '0', '0', '0', '1', current_timestamp(), current_timestamp());
	else
		SET @xway_security_key = md5(concat(@merchant_id, @company_name));
		INSERT INTO `xway_merchant_detail` (`merchant_id`, `franchise_id`, `vendor_id`, `xway_security_key`, `pg_id`, `logging_status`, `referrer_url` , `return_url`, `notify_merchant`, `notify_patron`, `surcharge_enable`, `pg_surcharge_enabled`, `ga_tag`, `created_date`, `last_update_date`)
		VALUES (@merchant_id, '0', '0', @xway_security_key, @pg_id, '1', @whitelist_url, @whitelist_url, '1', '1', '0', '0', null, current_timestamp(), current_timestamp());
		
		update merchant_setting set xway_enable=1 where merchant_id=@merchant_id;
	end if;
    update merchant set is_legal_complete=1 where merchant_id=@merchant_id;
	select @pg_id as 'pg_id';
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_settelement_gap` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_settelement_gap`(_from_date date , _to_date date)
BEGIN
Drop TEMPORARY  TABLE  IF EXISTS temp_transgap_detail;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_transgap_detail (
   `transaction_id` varchar(10) NOT NULL,
   `revenue_id` INT  NULL default 0 ,
   `date` datetime NULL,
   `settlement_id` INT NULL,
   `amount` decimal(11,2) not null,
   `merchant_id` varchar (10) not null ,
  `merchant_name` varchar (100)  null ,
   `pg_name` varchar (45)  null ,
   PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;
   
   insert into temp_transgap_detail(transaction_id,amount,merchant_id,date,pg_name)
   select pay_transaction_id,amount,merchant_id,t.created_date,p.pg_name from payment_transaction t 
   inner join payment_gateway p on t.pg_id=p.pg_id where payment_transaction_status=1 
   and p.pg_id in (6,15,22,26,34) and t.created_date >= _from_date and t.created_date <= _to_date;
   
   insert into temp_transgap_detail(transaction_id,amount,merchant_id,date,pg_name)
   select xway_transaction_id,amount,merchant_id,t.created_date,p.pg_name from xway_transaction t 
   inner join payment_gateway p on t.pg_id=p.pg_id where xway_transaction_status=1 
   and p.pg_id in (6,15,22,26,34) and t.created_date >= _from_date and t.created_date <= _to_date;
   
   update temp_transgap_detail r , revenue_detail u  set r.revenue_id = u.id where r.transaction_id=u.transaction_id;
   
   update temp_transgap_detail r , payment_transaction_settlement u  set r.settlement_id = u.id where r.transaction_id=u.transaction_id;
  
   update temp_transgap_detail r , merchant u  set r.merchant_name = u.company_name where r.merchant_id=u.merchant_id;
  
   select transaction_id,date,amount,merchant_id,merchant_name,pg_name from temp_transgap_detail where revenue_id = 0 and settlement_id is null;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `admin_tdr_revenue_calc` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_tdr_revenue_calc`(_pg_type TINYINT, _from_date DATETIME, _to_date DATETIME)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		show errors;
	END;

SET sql_safe_updates=0;
SET @swipez_merchant_id = 'M000000151';
DROP TEMPORARY  TABLE  IF EXISTS temp_tdr_diff_calculation;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_tdr_diff_calculation (
	`payment_id` VARCHAR(45) NOT NULL,
    `transaction_id` VARCHAR(10) NOT NULL,
    `merchant_user_id` VARCHAR(10) NULL,
    `merchant_id` VARCHAR(10) NULL,
    `company_name` VARCHAR(50) NULL,
    `pg_id` TINYINT NULL,
    `pg_type` TINYINT NULL,
    `payment_mode` varchar(55) NOT NULL,
    `amount` decimal(9,2),
    `swipez_tdr_share` decimal(9,2),
    `gst` decimal(9,2),
    `cap_date` timestamp DEFAULT '2014-01-01',
PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;

INSERT INTO temp_tdr_diff_calculation (payment_id, transaction_id, merchant_user_id, payment_mode, amount,cap_date)  
	SELECT payment_id, transaction_id, merchant_id, payment_method, captured, cap_date FROM payment_transaction_tdr
    WHERE cap_date >= _from_date
    AND cap_date <= _to_date;


UPDATE temp_tdr_diff_calculation t, merchant m SET
t.merchant_id = m.merchant_id,
t.company_name = m.company_name
WHERE t.merchant_user_id=m.user_id;


UPDATE temp_tdr_diff_calculation t, payment_transaction p SET
t.pg_id = p.pg_id
WHERE t.transaction_id = p.pay_transaction_id;


UPDATE temp_tdr_diff_calculation t, xway_transaction x SET
t.pg_id = x.pg_id
WHERE t.transaction_id = x.xway_transaction_id;


UPDATE temp_tdr_diff_calculation t, payment_gateway p SET
t.pg_type = p.pg_type
WHERE t.pg_id = p.pg_id;


DELETE FROM temp_tdr_diff_calculation WHERE pg_type <> _pg_type;


DELETE FROM temp_tdr_diff_calculation WHERE merchant_id = @swipez_merchant_id;


UPDATE temp_tdr_diff_calculation SET payment_mode = 10 WHERE payment_mode='UPI';


UPDATE temp_tdr_diff_calculation t, config c SET
t.payment_mode = c.config_key
WHERE config_type='payment_mode'
AND t.payment_mode = c.config_value;


DELETE FROM temp_tdr_diff_calculation WHERE payment_mode = "9";


UPDATE temp_tdr_diff_calculation t, merchant_tdr m 
SET t.swipez_tdr_share = t.amount * ((m.swipez_rate-m.pg_rate)/100)
WHERE t.merchant_id = m.merchant_id
AND t.payment_mode = m.mode;

SELECT t.payment_id, t.transaction_id, t.company_name, c.config_value as 'payment_mode', t.amount, t.swipez_tdr_share, t.cap_date
FROM temp_tdr_diff_calculation t, config c
WHERE c.config_type='payment_mode'
AND c.config_key = t.payment_mode;

DROP TABLE temp_tdr_diff_calculation;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `auto_aprove_customer_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `auto_aprove_customer_details`(_customer_id INT,_transaction_id varchar(10))
BEGIN

SET @customer_id=_customer_id;
set @change_id=0;
select change_id,pending_change_id into @change_id,@pending_change_id from pending_change where source_id=_transaction_id and status=0 order by pending_change_id desc limit 1;

if(@change_id>0)then

select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=1 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set first_name=@changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;
select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=2 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set last_name= @changed_value where customer_id=_customer_id;
end if;

SET @change_det_id=0;


select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=3 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set `email`= @changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;


select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=4 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set `mobile`= @changed_value where customer_id=_customer_id;
end if;

SET @change_det_id=0;

select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=5 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `status`=1 where `change_detail_id`=@change_det_id;
update customer set address= @changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;

select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=6 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set city= @changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;

select  change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=7 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set state= @changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;

select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=8 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set zipcode= @changed_value where customer_id=_customer_id;
end if;

update customer_data_change_detail set `status`= 1 where change_id=@change_id;
update pending_change set status=1 where pending_change_id=@pending_change_id;
update customer_data_change set status=1 where change_id=@change_id;

end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `chart_invoice_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_invoice_status`(_user_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_status;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_status (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `status` varchar(100) not null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    insert into temp_invoice_status(status)select payment_request_status   from payment_request where  DATE_FORMAT(last_update_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= @to_date  and user_id=_user_id and is_active=1 and payment_request_type <> 2 and parent_request_id <> '0';
    
    update temp_invoice_status set `status`=1 where `status`=2; 
    update temp_invoice_status b , config c  set b.status = c.config_value where b.status=c.config_key and c.config_type='payment_request_status';
    update temp_invoice_status set status='Paid' where status='Paid online' ; 
    update temp_invoice_status set status='Pending' where status='Submitted';
    select status as name,count(status) as value from temp_invoice_status group by status;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_status;

commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `chart_particular_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_particular_summary`(_user_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
		END;
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_particular_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_particular_summary (
    `p_id` INT NOT NULL AUTO_INCREMENT,
    `template_id` varchar(10) NULL,
    `template_type` varchar(10) NULL,
    `particular_name` varchar(250)  NULL ,
    `amount` decimal(11,2) NOT NULL default 0 ,
    `column_id` INT NOT NULL,
    `invoice_id` INT NOT NULL,
    PRIMARY KEY (`p_id`)) ENGINE=MEMORY;


    insert into temp_particular_summary(template_id,particular_name,column_id,invoice_id) select m.template_id,column_name,m.column_id,v.invoice_id from invoice_column_metadata m  inner join invoice_column_values v on m.column_id=v.column_id inner join payment_request p on v.payment_request_id=p.payment_request_id where m.column_type='PF' and v.created_by=_user_id and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') <= @to_date and p.parent_request_id <> '0'; 
    
    update temp_particular_summary t , invoice_template v  set t.template_type = v.template_type   where t.template_id=v.template_id ;
    update temp_particular_summary t , invoice_column_values v  set t.amount = v.value   where v.value>0 and v.invoice_id=t.invoice_id + 3 and template_type='society' ;
    update temp_particular_summary t , invoice_column_values v  set t.amount = v.value   where v.value>0 and v.invoice_id=t.invoice_id + 2 and template_type='school' ;
    update temp_particular_summary t , invoice_column_values v  set t.amount = v.value   where v.value>0 and v.invoice_id=t.invoice_id + 3 and template_type='hotel' ;
    select particular_name as name,sum(amount) as value from temp_particular_summary where amount>0 group by particular_name order by amount desc;

    Drop TEMPORARY  TABLE  IF EXISTS temp_particular_summary;

commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `chart_payment_mode` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_payment_mode`(_merchant_id varchar(10),_from_date datetime , _to_date datetime)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_mode;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_mode (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `mode` varchar(20) not null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    insert into temp_payment_mode(pay_transaction_id,mode) select pay_transaction_id,'Online' from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_mode(pay_transaction_id,mode) 
    select offline_response_id,'Offline' from offline_response where offline_response_type <> 4 and is_active=1 and merchant_id=_merchant_id  and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    select mode as name,count(pay_transaction_id) as value from temp_payment_mode group by mode;

    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_mode;
    
commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `chart_payment_received` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_payment_received`(_merchant_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');
Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_received (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `date` DATE not null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;

    
    insert into temp_payment_received(pay_transaction_id,date) select pay_transaction_id,DATE_FORMAT(last_update_date,'%Y-%m-%d') from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_received(pay_transaction_id,date) 
    select offline_response_id,DATE_FORMAT(created_date,'%Y-%m-%d') from offline_response where offline_response_type<>4 and is_active=1 and post_paid_invoice=0 and merchant_id=_merchant_id  and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
   
	insert into temp_payment_received(pay_transaction_id,date) select xway_transaction_id,DATE_FORMAT(last_update_date,'%Y-%m-%d') from xway_transaction where xway_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d')<= @to_date ;
   
    select date as name,count(pay_transaction_id) as value from temp_payment_received group by date;

    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;
    
commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `chart_tax_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `chart_tax_summary`(_user_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
        show errors;
			ROLLBACK;
		END;
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_tax_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_tax_summary (
    `tax_id` INT NOT NULL AUTO_INCREMENT,
    `column_id` INT NOT NULL,
    `percent_id` INT NOT NULL,
    `applicable_id` INT NOT NULL,
    `amount_id` INT NOT NULL,
    `tax_percentage` decimal(11,2)  NULL default 0,
    `tax_name` varchar(250)  NULL ,
    `applicable` decimal(11,2) NULL default 0,
    `amount` decimal(11,2) NULL default 0,
    PRIMARY KEY (`tax_id`)) ENGINE=MEMORY;

    insert into temp_tax_summary(tax_name,column_id,percent_id,applicable_id,amount_id)
    select column_name,m.column_id,v.invoice_id + 1,v.invoice_id + 2,v.invoice_id + 3 from invoice_column_metadata m  
    inner join invoice_column_values v on m.column_id=v.column_id inner join payment_request p on v.payment_request_id=p.payment_request_id 
    where m.column_type='TF' and v.created_by=_user_id and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') >= @from_date  
    and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') <= @to_date and p.parent_request_id <> '0'; 
    
    update temp_tax_summary t , invoice_column_values v  set t.tax_name = concat(t.tax_name,' (',v.value,'%)') , t.tax_percentage = v.value where t.percent_id=v.invoice_id and v.value>0;
    update temp_tax_summary t , invoice_column_values v  set t.applicable = v.value   where t.applicable_id=v.invoice_id and v.value>0;
    update temp_tax_summary t , invoice_column_values v  set t.amount = v.value   where t.amount_id=v.invoice_id and v.value>0;

    select tax_name as name,sum(amount) as value from temp_tax_summary where tax_name <> '' and tax_percentage <> '' and amount > 0 group by tax_percentage order by amount desc;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_tax_summary;
    
commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `clone_insert_payment_response` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `clone_insert_payment_response`(_type varchar(20),_transaction_id varchar(10),_payment_id varchar(40),_pg_transaction_id varchar(40),_amount decimal(11,2),_payment_method INT,_payment_mode varchar(45),_message varchar(250),_status varchar(20),_user_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
	show errors;
    ROLLBACK;
		END; 
START TRANSACTION;

SET @plugin_value='';
SET @image='';
SET @patron_mobile='';
SET @pay_transaction_id=_transaction_id;
SET @payment_id=_payment_id;
SET @transaction_id=_pg_transaction_id;
SET @message=_message;
SET @payment_method=_payment_method;
SET @template_id='';
SET @payment_status=3;
SET @invoice_type=1;
SET @sms_gateway=1;
SET @from_email='';
SET @sms_gateway_type=1;
SET @sms_name=NULL;
SET @short_url='';
SET @franchise_id=0;
SET @main_company_name='';
SET @webhook_id=0;
SET @event_name='';
SET @unit_type='';
SET @type='';
SET @quantity =0;
SET @profile_id=0;
SET @request_type=1;
SET @sms_available=0;
if(_status='success') then
set @status=1;
SET @response_code=0;
else
set @status=4;
SET @response_code=1;
end if;	

if(_type='package') then
	update package_transaction set payment_transaction_status =@status,pg_ref_no=@payment_id,pg_ref_1=@transaction_id,payment_mode=_payment_mode,payment_info=_message where package_transaction_id=_transaction_id;

	SELECT user_id,merchant_id, package_id, custom_package_id INTO @merchant_user_id,@merchant_id,@package_id,@custom_package_id FROM
	package_transaction WHERE package_transaction_id = _transaction_id;
    
    select concat(first_name,' ',last_name),email_id,mobile_no into @patron_name,@patron_email,@patron_mobile from user where user_id=@merchant_user_id;

	if(_status='success') then
		if(@custom_package_id>0)then
			select 1,package_cost,12,0,0,0,free_sms,invoice,invoice,invoice,event_booking,0,0,0
			into @package_type,@package_cost,@duration,@pg_integration,@site_builder,@brand_keyword,@free_sms,@individual_invoice,
			@bulk_invoice,@total_invoices,@event_booking,@merchant_role,@coupon,@supplier from custom_package where package_id=@custom_package_id;
			UPDATE custom_package SET status = 1 WHERE package_id = @custom_package_id;
			SET @package_id=12;
		else
			select `type`,package_cost,duration,pg_integration,site_builder,brand_keyword,free_sms,individual_invoice,bulk_invoice,total_invoices,merchant_role,coupon,supplier,0
			into @package_type,@package_cost,@duration,@pg_integration,@site_builder,@brand_keyword,@free_sms,@individual_invoice,@bulk_invoice,@total_invoices,@merchant_role,@coupon,@supplier,@event_booking from package where package_id=@package_id;
		end if;

		if(@package_type=1)then
			select end_date into @package_end_date from account WHERE merchant_id = @merchant_id AND is_active = 1 and amount_paid>0 and end_date>curdate();
            if(@package_end_date is not null)then
            SET @end_date=DATE_ADD(@package_end_date, INTERVAL @duration MONTH);
            else
            SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);
            end if;
		
			UPDATE account SET is_active = 0 WHERE merchant_id = @merchant_id AND is_active = 1;

			INSERT INTO `account`(`merchant_id`,`package_id`,`custom_package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,`bulk_invoice`,`event_booking`,`free_sms`,
			`merchant_role`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`coupon`,`supplier`,`start_date`,`end_date`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@merchant_id,@package_id,@custom_package_id,_transaction_id,_amount,@individual_invoice,@bulk_invoice,@event_booking,@free_sms,@merchant_role,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@coupon,@supplier
			,NOW(),@end_date,_user_id,CURRENT_TIMESTAMP(),_user_id);


			UPDATE merchant SET merchant_plan = @package_id,package_expiry_date=@end_date WHERE merchant_id = @merchant_id;

			if(@pg_integration=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 0 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,5,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;

			if(@site_builder=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,6,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
				UPDATE merchant_setting 
				SET 
				site_builder = 1
				WHERE
				merchant_id = @merchant_id;
			end if;

			if(@brand_keyword=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 0 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,8,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;

			if(@free_sms>0)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,7,_transaction_id,@free_sms,@free_sms,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
				UPDATE merchant SET merchant_type = 2 WHERE	merchant_id = @merchant_id;
			end if;
		end if;

		if(@package_type=2)then
			SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);
			if(@package_id=7)then
			select base_amount into @base_amount from package_transaction where package_transaction_id=_transaction_id;
			SET @licence_bought=@base_amount/@package_cost;
			UPDATE merchant 
			SET 
			merchant_type = 2
			WHERE
			merchant_id = @merchant_id;
			else
			SET @licence_bought=1;
			end if;
			if(@package_id=6)then
			update merchant_setting set site_builder=1 where merchant_id=@merchant_id;
			end if;
			INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
			`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@package_id,_transaction_id,@licence_bought,@licence_bought,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;
		end if;
else
select 1;
		SELECT payment_request_id,customer_id,merchant_user_id,merchant_id,payment_request_type,discount,coupon_id,quantity,narrative,deduct_amount,deduct_text,fee_id,is_partial_payment,amount-convenience_fee,fee_id,vendor_id,franchise_id
		into @payment_request_id,@customer_id,@merchant_user_id,@merchant_id,@type,@discount,@coupon_id,@quantity,
		@narrative,@deduct_amount,@deduct_text,@fee_detail_id,@is_partial_payment,@inv_transaction_amount,@fee_id,@vendor_id,@franchise_id from payment_transaction where pay_transaction_id=_transaction_id;
		select 2;
		SET @pg_surcharge_enabled=0;

		SELECT pg_surcharge_enabled INTO @pg_surcharge_enabled FROM merchant_fee_detail WHERE fee_detail_id = @fee_detail_id;
select 3;
		if(@pg_surcharge_enabled=1)then
			update payment_transaction set convenience_fee=_amount-amount,`amount`=_amount where pay_transaction_id=_transaction_id;
		end if;

		if(@type=2)then
			SELECT short_url,franchise_id,event_name,unit_type,template_id into @short_url,@franchise_id,@event_name,@unit_type,@template_id from event_request 
			where event_request_id=@payment_request_id;
			else
			SELECT invoice_number,@invoice_type, short_url, franchise_id, webhook_id,template_id,paid_amount,grand_total,payment_request_status,plugin_value,billing_profile_id,request_type
            INTO @invoice_number ,@invoice_type, @short_url , @franchise_id , @webhook_id,@template_id,@paid_amount,@invgrand_total,@payment_request_status,@plugin_value,@profile_id,@request_type FROM
			payment_request WHERE payment_request_id = @payment_request_id;
		end if;
select 4;
		if(_status='success') then
			SET @payment_status=2;
            if(@vendor_id=0 and @franchise_id=0)then
			select vendor_id,franchise_id into @vendor_id,@franchise_id from merchant_fee_detail where fee_detail_id=@fee_id;            
            end if;
            
            if(@vendor_id>0 or @franchise_id>0)then
            call split_transaction(_transaction_id,_amount,@vendor_id,@franchise_id,_user_id);
            end if;
            
            if(@coupon_id>0) then
					SET @coupon_availed=1;
                    if(@type=2)then
						select count(coupon_code) into @coupon_availed from event_transaction_detail where transaction_id=@pay_transaction_id and coupon_code=@coupon_id;
                    end if;
					
					UPDATE coupon r SET r.available = r.available - @coupon_availed WHERE r.coupon_id = @coupon_id
					AND r.`limit` <> 0;
              end if;
            
			if(@type=2)then
				SET @link='/merchant/transaction/viewlist/event';

				UPDATE event_transaction_detail SET is_paid = 1 WHERE transaction_id = @pay_transaction_id;
				elseif(@type=3)then
				SET @link='/merchant/transaction/viewlist/bulk';
				elseif(@type=4)then
				SET @link='/merchant/transaction/viewlist/subscription';
				elseif(@type=5)then
				update booking_transaction_detail set is_paid=1 where transaction_id=@pay_transaction_id;
				call update_booking_status(@pay_transaction_id);
				SET @link='/merchant/transaction/viewlist/booking';
				elseif(@type=6)then
				update customer_membership set status=1 where transaction_id=@pay_transaction_id;
				SET @link='/merchant/transaction/viewlist/booking';
				else
				SET @link='/merchant/transaction/viewlist';
				end if;

				
				if(@template_id<>'')then
				select image_path into @image from invoice_template where template_id=@template_id;
				end if;

				INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,
				`created_by`,`created_date`,`last_update_by`,`last_update_date`)
				VALUES(@merchant_user_id,1,@link,CURRENT_TIMESTAMP(),DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH),
				'Payment request(s) have been settled by your patron','',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

				UPDATE customer SET payment_status = @payment_status WHERE customer_id = @customer_id AND payment_status <> 2;

			else
				update customer set payment_status=@payment_status where customer_id=@customer_id and payment_status in(0,1);
			end if;

			SET @suppliers='';
            UPDATE `payment_request` SET `payment_request_status` = @status WHERE  payment_request_id = @payment_request_id and payment_request_status not in (1,2);
            call `update_status`(@payment_request_id,@pay_transaction_id,_amount,@status,@response_code,@payment_id,@transaction_id,_payment_mode,0,@message);
		
			update customer set balance = balance - _amount where customer_id=@customer_id;
			INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
			`ledger_type`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@customer_id,concat('Payment on ',DATE_FORMAT(NOW(),'%Y-%m-%d')),_amount,2,_transaction_id,'CREDIT',@customer_id,CURRENT_TIMESTAMP(),@customer_id);

        
			if(@is_partial_payment=1 and _status='success')then
				SET @paid_amount=@paid_amount+@inv_transaction_amount;

				if(@invgrand_total>@paid_amount)then
				SET @inv_status=7;
				else
				SET @inv_status=1;
				end if;

				update payment_request set payment_request_status=@inv_status,paid_amount=@paid_amount where payment_request_id= @payment_request_id;
			end if;
            
            if(@payment_request_status=7 and _status<>'success')then
				update payment_request set payment_request_status=7 where payment_request_id= @payment_request_id;
            end if;

select 5;
		SELECT sms_gateway,from_email,sms_gateway_type,sms_name,auto_approve
		INTO @sms_gateway , @from_email , @sms_gateway_type , @sms_name , @auto_approve FROM
		merchant_setting WHERE	merchant_id = @merchant_id;

		SET @pending_change_id='';
		SELECT pending_change_id, change_id INTO @pending_change_id , @change_id FROM pending_change 
		WHERE source_id = @pay_transaction_id AND source_type = 2 AND status = - 1; 
select 6;
		UPDATE pending_change SET status = 0 WHERE pending_change_id = @pending_change_id;
		UPDATE customer_data_change SET status = 0 WHERE change_id = @change_id;
		UPDATE customer_data_change_detail SET status = 0 WHERE	change_id = @change_id;
		if(@pending_change_id<>'') then
			if(@auto_approve>0) then
				call `auto_aprove_customer_details`(@customer_id,@pay_transaction_id);
			end if;
		end if;
select 7;
			SELECT customer_code, CONCAT(first_name, ' ', last_name),email,mobile INTO @customer_code , @patron_name,@patron_email,@patron_mobile FROM customer
			WHERE customer_id = @customer_id; 
            select 8;
			SELECT  `logo` INTO @merchant_logo FROM merchant_landing  WHERE merchant_id = @merchant_id;
            select 9;
            
end if;
select 7;
	SELECT merchant_domain INTO @merchant_domain FROM merchant WHERE merchant_id = @merchant_id;	
    select 7;
 if(@profile_id>0)then
 select company_name,company_name,address,business_email,business_contact into @display_name,@company_name,@merchant_address,@merchant_email_id,@business_contact
 from merchant_billing_profile where id = @billing_profile_id;
 else
  select company_name,company_name,address,business_email,business_contact into @display_name,@company_name,@merchant_address,@merchant_email_id,@business_contact
 from merchant_billing_profile where merchant_id = @merchant_id and is_default=1;
 end if;
select 8;
	SET @sms_name=@company_name;

	SELECT mobile_no INTO @merchant_mobile_no FROM  `user` WHERE user_id = @merchant_user_id;
	SELECT config_value INTO @payment_Mode FROM config WHERE config_key = @payment_method AND config_type = 'transaction mode'; 

SET @franchise_email_id='';
SET @franchise_mobile_no='';

if(@request_type=2)then
	if(@sms_gateway_type=1)then
		SET @sms_available=1;
	else
		select count(id) into @sms_count from merchant_addon where merchant_id=@merchant_id and package_id=7 and license_available >0 and end_date>curdate();
		if(@sms_count>0)then
			SET @sms_available=1;
		end if;
	end if;
end if;

if(@franchise_id>0)then
	SET @main_company_name=@company_name;
	SET @merchant_email_id_=@merchant_email_id;
	SELECT franchise_name, email_id, mobile
	INTO @company_name , @franchise_email_id , @franchise_mobile_no FROM
	franchise WHERE franchise_id = @franchise_id;
end if;
SET @message='success';
SELECT 
@message AS 'message',
@merchant_domain as 'merchant_domain',
@payment_request_id AS 'payment_request_id',
@invoice_number AS 'invoice_number',
@merchant_user_id AS 'merchant_user_id',
@merchant_id AS 'merchant_id',
@image AS 'image',
@merchant_logo AS 'merchant_logo',
@sms_gateway AS 'sms_gateway',
@company_name AS 'company_name',
@merchant_email_id AS 'merchant_email',
@merchant_mobile_no AS 'mobile_no',
@payment_Mode AS 'payment_mode',
@suppliers AS 'suppliers',
@discount AS 'discount',
@deduct_amount AS 'deduct_amount',
@deduct_text AS 'deduct_text',
@quantity AS 'quantity',
_message AS 'narrative',
@customer_code AS 'customer_code',
@patron_name AS 'patron_name',
@patron_name AS 'BillingName',
@patron_email AS 'patron_email',
@patron_mobile as 'patron_mobile',
@patron_mobile as 'billing_mobile',
@from_email AS 'from_email',
@sms_gateway_type AS 'sms_gateway_type',
@sms_name AS 'sms_name',
@short_url AS 'short_url',
@webhook_id AS 'webhook_id',
@main_company_name AS 'main_company_name',
@franchise_email_id AS 'franchise_email',
@franchise_mobile_no AS 'franchise_mobile',
@plugin_value as 'plugin_value',
@event_name AS 'event_name',
@unit_type AS 'unit_type',
@type AS 'type',
@request_type as 'request_type',
@sms_available as 'sms_available',
@invoice_type as 'invoice_type';

commit;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `collect_customer_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `collect_customer_list`(_merchant_id CHAR(10),_template_id char(10), _start int, _limit int, _search nvarchar(45),_group varchar(10),_status varchar(20))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
 		BEGIN
        show errors;
		END;
Drop TEMPORARY  TABLE  IF EXISTS temp_request;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_request (
	`id` int NOT NULL auto_increment,
    `payment_request_id` char(10)  NULL ,
    `customer_id` INT NOT NULL ,
    `customer_code` nvarchar(45) NULL ,
	`first_name` nvarchar(50) NULL ,
    `last_name` nvarchar(50) NULL ,
    `email` nvarchar(250) NULL,
    `mobile` nvarchar(20) NULL,
    `short_url` varchar(45) NULL,
    `customer_group` varchar(45) NULL,
    `bill_date` DATE  NULL ,
    `due_date` DATE NULL,
    `absolute_cost` DECIMAL(11,2) NULL ,
    `payment_mode` varchar(45) NULL ,
    `payment_date` varchar(45) NULL ,
    `transaction_id` char(10) NULL ,
    `paid_amount` DECIMAL(11,2) NULL ,
    `invoice_number` nvarchar(45) NULL,
    `payment_request_status` int  NULL,
    `narrative` nvarchar(500) NULL,
	`is_settled` int NOT NULL default 0,
    `settled_on` datetime NULL,
    `settlement_id` int NOT NULL default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    insert into temp_request (payment_request_id, customer_id, bill_date,due_date,absolute_cost, payment_request_status, invoice_number,narrative,short_url)
    select payment_request_id, customer_id, bill_date,due_date,absolute_cost, payment_request_status, invoice_number ,narrative,short_url
    from payment_request where NOT FIND_IN_SET(payment_request_status, _status) and payment_request_type <> 4 and is_active=1 and merchant_id=_merchant_id  and template_id=_template_id
    order by created_date desc;
    
	update temp_request t , customer c set t.customer_code=c.customer_code, t.mobile=c.mobile,t.first_name=c.first_name,t.last_name=c.last_name,t.email=c.email where t.customer_id=c.customer_id;

	Drop TEMPORARY  TABLE  IF EXISTS temp_collect_customer;
	CREATE TEMPORARY TABLE IF NOT EXISTS temp_collect_customer (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    insert into temp_collect_customer(customer_id)
	select distinct customer_id from temp_request;
    
    update temp_request t,offline_response o set payment_date=settlement_date,transaction_id=offline_response_id,paid_amount=amount,payment_mode=offline_response_type where t.payment_request_id=o.payment_request_id and o.is_active=1 and t.payment_request_status=2;
    update temp_request t,config o set payment_mode=config_value where t.payment_mode=o.config_key and o.config_type='offline_response_type' and t.payment_request_status=2;
	update temp_request set payment_request_status=0 where payment_request_status=4;


	insert into temp_request (customer_id, customer_code,email,mobile, first_name,last_name,customer_group)
    select customer_id, customer_code,email,mobile, first_name,last_name,customer_group 
    from customer where merchant_id=_merchant_id  and is_active=1 and customer_id not in (select customer_id from temp_collect_customer)
    order by last_update_date;
    
	SET @group_search='';
    
    
	  update temp_request t,payment_transaction o set payment_date=o.paid_on,transaction_id=pay_transaction_id,paid_amount=amount,t.payment_mode=o.payment_mode,t.is_settled=o.is_settled where t.payment_request_id=o.payment_request_id and o.payment_transaction_status=1 ;
	  update temp_request t , payment_transaction_settlement s set t.settled_on=s.settlement_date,t.settlement_id=s.settlement_id where t.transaction_id=s.transaction_id and t.is_settled=1;


	IF(_search <> '') THEN
    if(_group<>'')then
    SET @group_search=concat(" and customer_group like '%",_group,"%' ");
    end if;
	SET @sql=concat("select * from temp_request where (`first_name` LIKE '",concat('%',_search,'%'),"' or `last_name` LIKE '", concat('%',_search,'%') ,"' or invoice_number LIKE '",concat('%',_search,'%') ,"' or mobile LIKE '",concat('%',_search,'%'),"')",@group_search," limit ",_start,',',_limit);
	else
    if(_group<>'')then
    SET @group_search=concat(" customer_group like '%",_group,"%' ");
    end if;
	SET @sql=concat('select * from temp_request',@group_search,' limit ',_start,',',_limit);
    end if;
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `convert_draft_invoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `convert_draft_invoice`(_merchant_id char(10),_user_id char(10),_payment_request_id char(10),_invoice_type INT,invoice_values LONGTEXT,_invoice_number varchar(45),_payment_request_status INT,_payment_request_type INT)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;
SET @autoval=0;

if(_payment_request_type<>4 and _payment_request_status!=11)then
	SET @numstring=SUBSTRING(_invoice_number,1,16);
	if(@numstring='System generated')then
        if(_invoice_type<>2)then
			if(@autoval=0)then
            SET @autoval=SUBSTRING(_invoice_number,17);
            end if;
            SELECT GENERATE_INVOICE_NUMBER(@autoval) INTO @invoice_number;
            SET invoice_values=REPLACE(invoice_values, _invoice_number, @invoice_number);
            SET _invoice_number= @invoice_number;
        else
            SET @invoice_number='';
            SET invoice_values=REPLACE(invoice_values, _invoice_number, @invoice_number);
            SET _invoice_number= @invoice_number;
        end if;
	end if;
end if;

update payment_request set invoice_number=_invoice_number where payment_request_id=_payment_request_id;
update invoice_column_values v , invoice_column_metadata m set `value`=_invoice_number where v.column_id=m.column_id and m.function_id=9 and v.payment_request_id=_payment_request_id;

if(_payment_request_status!=11)then
call `stock_management`(_merchant_id,_payment_request_id,3,1);
end if;

SET @message='success';
commit;

select @message AS 'message';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `convert_estimate_to_invoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `convert_estimate_to_invoice`(_estimate_request_id char(10),OUT _req_id VARCHAR(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
		ROLLBACK;
		END; 
START TRANSACTION;
SET @invoice_no_column_id=0;
SET @invoice_number='';
select template_id,merchant_id,vendor_id into @template_id,@merchant_id,@vendor_id from payment_request where payment_request_id=_estimate_request_id;



select column_id into @invoice_no_column_id from invoice_column_metadata where template_id=@template_id and function_id=9 and is_active=1 limit 1;

if(@invoice_no_column_id>0)then
	select `param`,`value` into @param,@seq_value from column_function_mapping where column_id=@invoice_no_column_id and is_active=1;
    if(@param='system_generated')then
		SELECT GENERATE_INVOICE_NUMBER(@seq_value) INTO @invoice_number;
    end if;
end if;

SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @req_id;

INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`currency`,`created_by`,
`created_date`,`last_update_by`)
SELECT @req_id,`user_id`,`merchant_id`,`customer_id`,1,1,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
@invoice_number,`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,'',`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`currency`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`
FROM `payment_request` where payment_request_id=_estimate_request_id;


INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
select @req_id,`column_id`,`value`,`is_active`,`created_by`,CURRENT_TIMESTAMP(),`last_update_by`,CURRENT_TIMESTAMP()
from invoice_column_values where payment_request_id=_estimate_request_id;

INSERT INTO `invoice_particular`
(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_particular` where payment_request_id=_estimate_request_id and is_active=1;

INSERT INTO `invoice_travel_particular`
(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_travel_particular` where payment_request_id=_estimate_request_id and is_active=1;

INSERT INTO `invoice_tax`
(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_tax` where payment_request_id=_estimate_request_id and is_active=1;

INSERT INTO `invoice_food_franchise_summary`(`payment_request_id`,`commision_fee_percent`,`commision_waiver_percent`,`commision_net_percent`,`gross_sale`,`net_sale`,`gross_fee`,`waiver_fee`,`net_fee`,`non_brand_commision_fee_percent`,`non_brand_commision_waiver_percent`,`non_brand_commision_net_percent`,`non_brand_gross_sale`,`non_brand_net_sale`,`non_brand_gross_fee`,`non_brand_waiver_fee`,`non_brand_net_fee`,`penalty`,`bill_period`,`is_active`,`created_by`,`created_date`,`last_update_by`)
SELECT @req_id,`commision_fee_percent`,`commision_waiver_percent`,`commision_net_percent`,`gross_sale`,`net_sale`,`gross_fee`,`waiver_fee`,`net_fee`,`non_brand_commision_fee_percent`,`non_brand_commision_waiver_percent`,`non_brand_commision_net_percent`,`non_brand_gross_sale`,`non_brand_net_sale`,`non_brand_gross_fee`,`non_brand_waiver_fee`,`non_brand_net_fee`,`penalty`,`bill_period`,`is_active`,`created_by`,CURRENT_TIMESTAMP(),`last_update_by`
FROM `invoice_food_franchise_summary` where payment_request_id=_estimate_request_id and is_active=1;

INSERT INTO `invoice_food_franchise_sales`(`payment_request_id`,`customer_id`,`date`,`gross_sale`,`tax`,`billable_sale`,`non_brand_gross_sale`,`non_brand_tax`,`non_brand_billable_sale`,`delivery_partner_commission`,`non_brand_delivery_partner_commission`,`status`,`is_active`,`created_by`,`created_date`,`last_update_by`)
SELECT @req_id,`customer_id`,`date`,`gross_sale`,`tax`,`billable_sale`,`non_brand_gross_sale`,`non_brand_tax`,`non_brand_billable_sale`,`delivery_partner_commission`,`non_brand_delivery_partner_commission`,`status`,`is_active`,`created_by`,CURRENT_TIMESTAMP(),`last_update_by`
FROM `invoice_food_franchise_sales` where payment_request_id=_estimate_request_id and is_active=1;

if(@vendor_id>0)then
INSERT INTO `invoice_vendor_commission`(`merchant_id`,`payment_request_id`,`vendor_id`,`amount`,`type`,`commission_value`,`is_active`,`created_by`,`created_date`,`last_update_by`)
select `merchant_id`,@req_id,`vendor_id`,`amount`,`type`,`commission_value`,`is_active`,`created_by`,`created_date`,`last_update_by` from invoice_vendor_commission where payment_request_id = _estimate_request_id;
end if;

update payment_request set payment_request_status=6,converted_request_id=@req_id where payment_request_id=_estimate_request_id;

if(@invoice_no_column_id>0)then
	update invoice_column_values set `value`=@invoice_number where column_id=@invoice_no_column_id and payment_request_id=@req_id;
end if;

call `stock_management`(@merchant_id,@req_id,3,1);


SET _req_id=@req_id;

commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `convert_invoice_to_expense` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `convert_invoice_to_expense`(_payment_request_id char(10),_customer_merchant_id char(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
            
		END; 
START TRANSACTION;

if(_customer_merchant_id!='')then
	select merchant_id,basic_amount,grand_total,tax_amount,bill_date,due_date,invoice_number,narrative 
	into @merchant_id,@basic_amount,@grand_total,@tax_amount,@bill_date,@due_date,@invoice_number,@narrative 
	from payment_request where payment_request_id=_payment_request_id;

	select vendor_id,category_id,sub_category_id into @vendor_id,@v_category_id,@v_sub_category_id from vendor where merchant_id=_customer_merchant_id and vendor_merchant_id=@merchant_id;

	select expense_auto_generate into @expense_auto_generate from merchant_setting where merchant_id=_customer_merchant_id;
    
    select `name` into @category_name from vendor_category where id=@v_category_id and merchant_id=_customer_merchant_id;
    select `name` into @sub_category_name from vendor_category where id=@v_sub_category_id and merchant_id=_customer_merchant_id;

	if(@vendor_id>0)then
	SET @vendor_id=@vendor_id;
	else
	SET @vendor_id=0;
	end if;
	SET @expense_no='';

	if(@expense_auto_generate=1)then
	SET @expense_no='Auto generate';
	end if;

	SET @igst_amount=0;
	SET @cgst_amount=0;
	SET @cgst_percent=0;
	SET @igst_percent=0;
	SET @expense_id=0;
    SET @category_id=0;
    SET @sub_category_id=0;
    
    select `id` into @category_id from expense_category where `name`=@category_name and merchant_id=_customer_merchant_id;
    select `id` into @sub_category_id from expense_department where `name`=@sub_category_name and merchant_id=_customer_merchant_id;
    

	SET @ccount=0;
	select count(id) into @ccount from invoice_tax t inner join merchant_tax m on t.tax_id=m.tax_id where payment_request_id=_payment_request_id and m.tax_type=1 and t.is_active=1;
	if(@ccount>0)then	
		select sum(tax_amount),tax_percent into @cgst_amount,@cgst_percent from invoice_tax t inner join merchant_tax m on t.tax_id=m.tax_id where payment_request_id=_payment_request_id and m.tax_type=1 and t.is_active=1;
	end if;

	SET @icount=0;
	select count(id) into @icount from invoice_tax t inner join merchant_tax m on t.tax_id=m.tax_id where payment_request_id=_payment_request_id and m.tax_type=3 and t.is_active=1;
	if(@icount>0)then	
		select sum(tax_amount),tax_percent into @igst_amount,@igst_percent from invoice_tax t inner join merchant_tax m on t.tax_id=m.tax_id where payment_request_id=_payment_request_id and m.tax_type=3 and t.is_active=1;
	end if;



	select expense_id into @expense_id from staging_expense where payment_request_id=_payment_request_id limit 1;

	if(@expense_id>0)then
		update staging_expense set invoice_no=@invoice_number,bill_date=@bill_date,due_date=@due_date,base_amount=@basic_amount,cgst_amount=@cgst_amount,sgst_amount=@cgst_amount
		,igst_amount=@igst_amount,total_amount=@grand_total,narrative=@narrative,`type`=3 where expense_id=@expense_id;
		update staging_expense_detail set is_active=0 where expense_id=@expense_id; 
	else
		INSERT INTO `staging_expense`
		(`type`,`merchant_id`,`vendor_id`,`category_id`,`department_id`,`expense_no`,`invoice_no`,`bill_date`,`due_date`,`tds`,`discount`,`adjustment`,`payment_status`,
		`payment_mode`,`base_amount`,`cgst_amount`,`sgst_amount`,`igst_amount`,`total_amount`,`notify`,`narrative`,`bulk_id`,`payment_request_id`,`created_by`,`created_date`,
		`last_update_by`)
		VALUES (3,_customer_merchant_id,@vendor_id,@category_id,@sub_category_id,@expense_no,@invoice_number,@bill_date,@due_date,0,0,0,0,0,@basic_amount,
		@cgst_amount,@cgst_amount,@igst_amount,@grand_total,0,@narrative,0,_payment_request_id,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
		select LAST_INSERT_ID() into @expense_id;
	end if;

	if(@cgst_amount>0)then
	SET @gst_type=1;
	else
	SET @gst_type=2;
	end if;

	select count(id) into @p_count from invoice_particular where payment_request_id=_payment_request_id and is_active=1; 

if(@p_count>0)then	
	if(@gst_type=1)then
		INSERT INTO `staging_expense_detail`
		(`expense_id`,`particular_name`,`sac_code`,`qty`,`rate`,`amount`,`tax`,`cgst_amount`,
		`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`created_by`,`created_date`,
		`last_update_by`)
		select @expense_id,item,sac_code,qty,rate,total_amount,c.config_key,(total_amount*gst/100)/2,(total_amount*gst/100)/2,0,gst,total_amount+(total_amount*gst/100),@merchant_id,CURRENT_TIMESTAMP(),@merchant_id
		from invoice_particular p inner join config c on p.gst=c.config_value and c.config_type='expense_tax_type'
		where payment_request_id=_payment_request_id and p.is_active=1;
	else
		INSERT INTO `staging_expense_detail`
		(`expense_id`,`particular_name`,`sac_code`,`qty`,`rate`,`amount`,`tax`,`cgst_amount`,
		`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`created_by`,`created_date`,
		`last_update_by`)
		select @expense_id,item,sac_code,qty,rate,total_amount,c.config_key,0,0,(total_amount*gst/100),gst,total_amount+(total_amount*gst/100),@merchant_id,CURRENT_TIMESTAMP(),@merchant_id
		from invoice_particular p inner join config c on p.gst=c.config_value and c.config_type='expense_tax_type'
		where payment_request_id=_payment_request_id and p.is_active=1;
	end if;
	 update staging_expense_detail set qty=1 where qty=0  and  expense_id=@expense_id; 
	 update staging_expense_detail set rate=`amount` where qty=1  and  expense_id=@expense_id; 
else
	INSERT INTO `staging_expense_detail`
		(`expense_id`,`particular_name`,`sac_code`,`qty`,`rate`,`amount`,`tax`,`cgst_amount`,
		`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`created_by`,`created_date`,
		`last_update_by`)values(@expense_id,'Particular','',1,@basic_amount,@basic_amount,0,0,0,0,0,@basic_amount,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
end if;

end if;
commit;




END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `customer_save` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `customer_save`(_user_id varchar(10),_merchant_id varchar(10),_customer_code varchar(45),_first_name nvarchar(50),_last_name nvarchar(50),
_email varchar(250),_mobile varchar(15),_address varchar(250),_address2 varchar(250),_city varchar(60),_state varchar(60),_zipcode varchar(10),_column_id longtext,_column_value longtext,_password varchar(20),_gst varchar(20),_bulk_id INT,_company_name varchar(250),_country varchar(75))
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


 INSERT INTO `customer`(`merchant_id`,`customer_code`,`user_id`,`first_name`,`last_name`,`email`,`mobile`,`address`,`address2`,`city`,`state`,`country`,`zipcode`,`password`,`company_name`,`gst_number`,`bulk_id`,`customer_status`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
 VALUES(_merchant_id,_customer_code,@user_id,_first_name,_last_name,_email,_mobile,_address,_address2,_city,_state,_country,_zipcode,_password,_company_name,_gst,_bulk_id,@customer_status,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());


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

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `customer_staging_save` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `customer_staging_save`(_bulk_id INT,_user_id varchar(10),_merchant_id varchar(10),_customer_code varchar(45),_first_name varchar(50),_last_name varchar(50),
_email varchar(250),_mobile varchar(15),_address varchar(250),_address2 varchar(250),_city varchar(60),_state varchar(60),_zipcode varchar(10),_column_id longtext,_column_value longtext,_password varchar(20),_gst varchar(20),_company_name varchar(250),_country varchar(75))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

 INSERT INTO `staging_customer`(`bulk_id`,`merchant_id`,`user_id`,`customer_code`,`first_name`,`last_name`,`email`,`mobile`,`address`,`address2`,`country`,`city`,`state`,`zipcode`,`password`,`company_name`,`gst_number`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
 VALUES(_bulk_id,_merchant_id,_user_id,_customer_code,_first_name,_last_name,_email,_mobile,_address,_address2,_country,_city,_state,_zipcode,_password,_company_name,_gst,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());


 SET @customer_id=LAST_INSERT_ID();



 
WHILE _column_id != '' > 0 DO
    SET @column_id  = SUBSTRING_INDEX(_column_id, @separator, 1);
    SET @column_value  = SUBSTRING_INDEX(_column_value, @separator, 1);


INSERT INTO `staging_customer_column_values`(`customer_id`,`column_id`,`value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@customer_id,@column_id,@column_value,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
  
    SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id) + @separatorLength + 1);
    SET _column_value = SUBSTRING(_column_value, CHAR_LENGTH(@column_value) + @separatorLength + 1);

END WHILE;


commit;
SET @message = 'success';
select @message as 'message';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `customer_structure_save` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
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

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `customer_update` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `customer_update`(_user_id varchar(10),_customer_id INT,_customer_code varchar(45),_first_name varchar(50),_last_name varchar(50),
_email varchar(250),_mobile varchar(15),_address varchar(250),_address2 varchar(250),_city varchar(60),_state varchar(60),_zipcode varchar(10),_column_id longtext,_column_value longtext,_exist_column_id longtext,_exist_column_value longtext,_password varchar(20),_gst varchar(20),_company_name varchar(250),_country varchar(75))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);


UPDATE `customer` SET `customer_code` = _customer_code,`first_name` = _first_name,`last_name` = _last_name,email=_email,mobile=_mobile,`address` = _address,`address2` = _address2,`country` = _country,
`city` = _city,`state` = _state,`zipcode` = _zipcode,`password`=_password,`company_name`= _company_name,`gst_number`=_gst,`last_update_by` = _user_id,`last_update_date` = CURRENT_TIMESTAMP() WHERE `customer_id` = _customer_id;


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

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Eventrespond` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Eventrespond`(_amount decimal(11,2),_bank_name int,_payment_req_id varchar(11)
,_date DATETIME ,_bank_transaction_no varchar(20),_respond_type int,_cheque_no int,_cash_paidto varchar(45),_patron_id varchar(10),_payment_request_status int)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        SET @message = 'failed';
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @patron_id=_patron_id;
SELECT user_id into @merchant_id from payment_request where payment_request_id =_payment_req_id;
select generate_sequence('Offline_respond_id') into @offline_response_id;

INSERT INTO `offline_response`(`offline_response_id`,`payment_request_id`,`patron_user_id`,`merchant_user_id`,`offline_response_type`,
`settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`cheque_no`,`cash_paid_to`,`created_by`,`created_date`, `last_update_by`,
`last_update_date`)
	VALUES (@offline_response_id,_payment_req_id,@patron_id,@merchant_id,_respond_type,_date,_bank_transaction_no,_bank_name,
    _amount,_cheque_no,_cash_paidto,@patron_id, CURRENT_TIMESTAMP(),@patron_id,CURRENT_TIMESTAMP());


if(@merchant_id!=_user_id) then
SET @notification_type=1;
SET @link='/merchant/transaction/viewlist/event/event';
SET @message='count Event(s) have been settled by your patron';
INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,
        `created_by`,`created_date`,`last_update_by`,`last_update_date`)
        VALUES(@merchant_id,@notification_type,@link,CURRENT_TIMESTAMP(),DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH),
        @message,'',@patron_id,CURRENT_TIMESTAMP(),@patron_id,CURRENT_TIMESTAMP());
end if;


commit;
        SET @message = 'success';
        select @message as 'message',@offline_response_id as 'offline_response_id';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `event_save` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `event_save`(_user_id char(10),_merchant_id char(10),_event_name varchar(250),_title varchar(200),_short_description varchar(300),_venue varchar(500),_description longtext,_duration INT,_occurence INT,_column longtext,_column_value longtext,_mandatory longtext,_datatype longtext
,_position longtext,_bannerext varchar(10),_start_date longtext,_end_date longtext,_start_time longtext,_end_time longtext,_package_name longtext,_package_desc longtext,_unitavailable longtext,
_unitcost longtext,_min_price longtext,_max_price longtext,_min_seat longtext,_max_seat longtext,_tax_text longtext,_tax longtext,_package_coupon longtext,_is_flexible longtext,_payee_capture longtext,_attendees_capture longtext,_coupon_code INT,_franchise_id INT,_vendor_id INT,_event_type INT
,_booking_unit varchar(20),_artist varchar(250),_artist_label varchar(45),_category_name longtext,_event_tnc longtext,_cancellation_policy longtext,_package_type longtext,_package_occurence longtext,_stop_booking_string varchar(10),_currency varchar(45),_currency_price longtext)
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
,`payee_capture`,`attendees_capture`,`franchise_id`,`vendor_id`,`unit_type`,`artist`,`artist_label`,`tnc`,`cancellation_policy`,`stop_booking_time`,currency,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@request_id,_user_id,_merchant_id,@template_id,_event_name,_title,_short_description,_event_type,_venue,_description,_duration,_occurence,_coupon_code,_payee_capture
,_attendees_capture,_franchise_id,_vendor_id,_booking_unit,_artist,_artist_label,_event_tnc,_cancellation_policy,_stop_booking_string,_currency,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());



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
    SET @currency_price  = SUBSTRING_INDEX(_currency_price, @separator, 1);
    
    
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
    ,`tax_text`,`tax`,`category_name`,`package_type`,`occurence`,`currency_price`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
values(@request_id,@package_name,@package_desc,@unitavailable,@min_seat,@max_seat,@unitcost,@package_coupon,@min_price,@max_price,@is_flexible
,@tax_text,@tax,@category_name,@package_type,@package_occurence,@currency_price,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

  
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
    SET _currency_price = SUBSTRING(_currency_price, CHAR_LENGTH(@currency_price) + @separatorLength + 1);

    
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



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `event_update` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `event_update`(_payment_request_id varchar(10),_event_name varchar(250),_title varchar(200),_short_description varchar(300),_venue varchar(250),_description longtext,_duration INT,_occurence INT,_column longtext,_column_value longtext,
_mandatory longtext,_datatype longtext,_position longtext,_exist_value longtext,_invoice_id longtext,_bannerext varchar(10),_start_date longtext,_end_date longtext,_start_time longtext,_end_time longtext,_epackage_id longtext,_epackage_name longtext,_epackage_desc longtext,_eunitavailable longtext,_esold_out longtext,_eunitcost longtext,_emin_price longtext,_emax_price longtext,_emin_seat longtext,_emax_seat longtext,_epackage_coupon longtext,
_ecategory_name longtext,_category_name longtext, _epackage_type longtext,_eis_flexible longtext,_epackage_occurence longtext,_etax_text longtext,_etax longtext,_package_type longtext,_package_occurence longtext,_tax_text longtext,_tax longtext,
_package_name longtext,_package_desc longtext,_unitavailable longtext,_unitcost longtext,_min_price longtext,_max_price longtext,_min_seat longtext,_max_seat longtext,_package_coupon longtext,_franchise_id INT,_vendor_id INT,_is_flexible longtext,_payee_capture longtext,_attendees_capture longtext,_coupon_code INT,_event_type INT,_unit_type varchar(20),_artist varchar(500),_artist_label varchar(45),_tnc longtext,_privacy longtext,_stop_booking_string varchar(10),_currency varchar(45),_currency_price longtext,_ecurrency_price longtext)
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
,`coupon_code`=_coupon_code,`payee_capture`=_payee_capture,`attendees_capture`=_attendees_capture,`stop_booking_time`=_stop_booking_string,`currency`=_currency
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
	SET @ecurrency_price  = SUBSTRING_INDEX(_ecurrency_price, @separator, 1);

    
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
   ,`max_price` = @emax_price,`coupon_code` = @epackage_coupon,`is_flexible` = @eis_flexible,
   `currency_price`=@ecurrency_price
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
    SET _ecurrency_price = SUBSTRING(_ecurrency_price, CHAR_LENGTH(@ecurrency_price) + @separatorLength + 1);

	
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
	SET @currency_price  = SUBSTRING_INDEX(_currency_price, @separator, 1);

    
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
`created_by`,`created_date`,`last_update_by`,`last_update_date`,`currency_price`)values(@request_id,@package_name,@package_desc,@unitavailable,@min_seat,@max_seat,@unitcost,@package_coupon,@min_price,@max_price,@is_flexible
,@package_type, @package_occurence,@tax_text,@tax,@category_name,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP(),@currency_price);

  
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
	SET _currency_price = SUBSTRING(_currency_price, CHAR_LENGTH(@currency_price) + @separatorLength + 1);

	
END WHILE;


commit;
SET @message = 'success';
select @request_id as 'request_id',@message as 'message',@template_id as 'template_id';



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `forgotPassword` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `forgotPassword`(_email varchar(254),_group_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;
if(_group_id<>'') then
select count(user_id),user_id into @user_count,@user_id from user where email_id=_email and group_id=_group_id;
else 
select count(user_id),user_id into @user_count,@user_id from user where (email_id = _email or concat(mob_country_code,mobile_no)=_email);
end if;
if(@user_count>0) then
select count(email_id),id into @count,@id from forgot_password where user_id=@user_id and is_active=1;
if(@count>0) then
update forgot_password set last_update_date = CURRENT_TIMESTAMP() where id=@id;
else
INSERT INTO `forgot_password`(`email_id`,`user_id`, `create_date`, `last_update_date`)
VALUES (_email,@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());
SET @id=LAST_INSERT_ID();
end if;	
select concat(last_update_date,'',email_id) into @string from forgot_password where id=@id;
commit;
else
set @string='error';
end if;
select @string,@id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_societystatement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_societystatement`(_payment_request_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 
set @merchant_id = '';

Drop TEMPORARY  TABLE  IF EXISTS societystatement;

CREATE TEMPORARY TABLE IF NOT EXISTS societystatement ( 
`payment_request_id` varchar(10) NOT NULL , 
`patron_id` varchar(10) NOT NULL,  
`user_id` varchar(10) NOT NULL,
`billing_cycle_id` varchar(10) NOT NULL, 
`request_amt` DECIMAL(11,2), 
`narrative` varchar(500) null,
`transaction_amt` DECIMAL(11,2),
`request_date` DATETIME null,
`payment_date`  DATETIME null, 
`transaction_id` varchar(10) null,
`payment_type_id` int null ,
`payment_mode` varchar(10) null,

PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
 select user_id,patron_id into @merchant_id,@patron_id from payment_request 
where payment_request_id=_payment_request_id ;


insert into societystatement (payment_request_id,patron_id,user_id,billing_cycle_id,request_amt,narrative,request_date)
select payment_request_id,patron_id,user_id,billing_cycle_id,absolute_cost,narrative,bill_date from payment_request where patron_id=@patron_id 
and user_id=@merchant_id and (payment_request_type<>4 or parent_request_id<> '0' ) order by last_update_date desc limit 5;

update societystatement s , payment_transaction pt set s.transaction_id = pt.pay_transaction_id , s.transaction_amt = pt.amount , s.payment_date = pt.last_update_date, s.payment_type_id = '0' , s.payment_mode = 'online' where s.payment_request_id = pt.payment_request_id; 

update societystatement s , offline_response op set s.transaction_id = op.offline_response_id , s.transaction_amt = op.amount , s.payment_date = op.last_update_date, s.payment_type_id = op.offline_response_type where s.payment_request_id = op.payment_request_id and op.is_active=1;

update societystatement s ,  config c  set s.payment_mode = c.config_value where s.payment_type_id=c.config_key and c.config_type='offline_response_type'; 

   
   select * from societystatement ;
         

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `geteventinvoice_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `geteventinvoice_details`(_paymentrequest_id varchar(20))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
		BEGIN
            
		END; 

SET @payment_req=_paymentrequest_id;
set @merchant_id='';
SET @patron_user_id='';
SET @count_transaction=0;
SET @domain='';
SET @display_url='';
SET @coupon_code='';
SET @c_descreption='';
SET @c_type='';
SET @c_percent='';
SET @c_fixed_amount='';
SET @c_start_date='';
SET @c_end_date='';
SET @c_limit='';
SET @c_available='';
SET @pg_count=0;

SELECT 
    duration,
    occurence,
    franchise_id,
    vendor_id,
    user_id,
    template_id,
    event_name,
    venue,
    description,
    event_from_date,
    event_to_date,
    tax_text,
    tax,
    mobile_capture,
    age_capture,
    event_type,
    coupon_code,
    is_active,
    capture_attendee_details,
    custom_capture_detail,
    custom_capture_title,
    short_url,
    has_season_package,
    tnc,
    cancellation_policy,
    artist,
	artist_label,
    unit_type,
    title,
    short_description,
    payee_capture,
    stop_booking_time,
    currency,
    attendees_capture    
INTO @duration , @occurence , @franchise_id ,@vendor_id, @merchant_user_id , @template_id , @event_name , @venue , @description , @event_from_date 
, @event_to_date ,@tax_text, @tax , @mobile_capture , @age_capture , @event_type , @coupon_id , @is_active , @capture_attendee_details 
, @custom_capture_detail , @custom_capture_title , @short_url,@has_season_package,@terms,@privacy,@artist,@artist_label,@unit_type,@title
,@short_description,@payee_capture,@stop_booking_time,@currency,@attendees_capture FROM
    event_request
WHERE
    event_request_id = @payment_req;


 
SELECT 
    image_path, banner_path, template_name, template_type
INTO @image_path , @banner_path , @template_name , @template_type FROM
    invoice_template
WHERE
    template_id = @template_id;
 
SELECT 
    display_url,
    merchant_domain,
    merchant_type,
    is_legal_complete,
	disable_online_payment,
    merchant_id
INTO @display_url , @merchant_domain , @merchant_type , @legal_complete,@disable_online_payment , @merchant_id FROM
    merchant
WHERE
    user_id = @merchant_user_id;
 
 
SELECT 
    settlements_to_franchise
INTO @settlements_to_franchise FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
 
SELECT 
    address, business_email, business_contact,company_name,company_name
INTO @merchant_address , @business_email , @business_contact,@company_name,@display_name FROM
    merchant_billing_profile
WHERE
    merchant_id = @merchant_id and is_default=1;
 
SELECT 
    config_value
INTO @domain FROM
    config
WHERE
    config_type = 'merchant_domain'
        AND config_key = @merchant_domain;
  
SELECT 
    coupon_code,
    descreption,
    type,
    percent,
    fixed_amount,
    start_date,
    end_date,
    `limit`,
    available
INTO @coupon_code , @c_descreption , @c_type , @c_percent , @c_fixed_amount , @c_start_date , @c_end_date , @c_limit , @c_available FROM
    coupon
WHERE
    coupon_id = @coupon_id
        AND start_date <= CURDATE()
        AND end_date >= CURDATE()
        AND (available > 0 OR `limit` = 0);


 
 if(@disable_online_payment=1)then
 SET @legal_complete=0;
 end if;

 if(@display_name<>'')then
 SET @company_name=@display_name;
 end if;
 
 if(@franchise_id>0)then
 SET @main_company_name=@company_name;
SELECT 
    franchise_name
INTO @company_name FROM
    franchise
WHERE
    franchise_id = @franchise_id;
    
    if(@settlements_to_franchise=1)then
	SELECT 
    COUNT(fee_detail_id)
INTO @pg_count FROM
    merchant_fee_detail
WHERE
    merchant_id = @merchant_id and franchise_id=@franchise_id
	AND is_active = 1;
 end if;
 
 end if;
 
 
SELECT 
    @domain AS 'merchant_domain',
    @display_url AS 'display_url',
    @merchant_user_id AS 'merchant_user_id',
    @merchant_id AS 'merchant_id',
    @event_name AS 'event_name',
    @venue AS 'venue',
    @description AS 'description',
    @event_from_date AS 'event_from_date',
    @event_to_date AS 'event_to_date',
    @tax_text AS 'tax_text',
    @tax AS 'tax',
    @main_company_name AS 'main_company_name',
    @mobile_capture AS 'mobile_capture',
    @age_capture AS 'age_capture',
    @company_name AS 'company_name',
    @merchant_type AS 'merchant_type',
    @legal_complete AS 'legal_complete',
    @image_path AS 'image_path',
    @banner_path AS 'banner_path',
    @event_name AS 'event_name',
    @merchant_address AS 'merchant_address',
    @business_email AS 'business_email',
    @business_contact AS 'business_contact',
    @seats_available AS 'seats_available',
    @coupon_code AS 'coupon_code',
    @c_descreption AS 'c_descreption',
    @c_type AS 'c_type',
    @c_percent AS 'c_percent',
    @c_fixed_amount AS 'c_fixed_amount',
    @c_start_date AS 'c_start_date',
    @c_end_date AS 'c_end_date',
    @c_limit AS 'c_limit',
    @c_available AS 'c_available',
    @coupon_id AS 'coupon_id',
    @duration AS 'duration',
    @occurence AS 'occurence',
    @event_type AS 'event_type',
    @is_active AS 'is_active',
    @capture_attendee_details AS 'capture_details',
    @short_url AS 'short_url',
    @custom_capture_detail AS 'custom_capture_detail',
    @custom_capture_title AS 'custom_capture_title',
    @settlements_to_franchise as 'pg_to_franchise',
    @franchise_id AS 'franchise_id',
    @vendor_id as 'vendor_id',
    @has_season_package as 'has_season_package',
    @terms as 'terms',
    @artist as 'artist',
    @artist_label as 'artist_label',
    @unit_type as 'unit_type',
    @privacy as 'privacy',
    @title as 'title',
    @payee_capture as 'payee_capture',
    @attendees_capture as 'attendees_capture',
    @stop_booking_time as 'stop_booking_time',
    @currency as 'currency',
	@short_description as 'short_description';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getinvoice_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getinvoice_details`(_merchant_id char(10), _paymentrequest_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
	show errors;
		BEGIN
		END; 

SET @payment_req=_paymentrequest_id;
set @patron_id='';
set @merchant_id='';
SET @patron_user_id='';
SET @domain='';
SET @display_url='';
SET @pg_count=0;
SET @main_company_name='';
SET @customer_id=0;
SET @registration_number='';

SELECT 
    invoice_number,
    invoice_total,
    grand_total,
    advance_received,
    swipez_total,
    DATE_FORMAT(bill_date, '%d %b %Y'),
    previous_due,
    basic_amount,
    tax_amount,
    narrative,
    absolute_cost,
    late_payment_fee,
    expiry_date,
    DATE_FORMAT(due_date, '%d %b %Y'),
    customer_id,
    merchant_id,
    user_id,
    billing_cycle_id,
    payment_request_status,
    payment_request_type,
    converted_request_id,
    invoice_type,
    template_id,
    notify_patron,
    has_custom_reminder,
    short_url,
    franchise_id,
    vendor_id,
    autocollect_plan_id,
    paid_amount,
    plugin_value,
    document_url,
    gst_number,
    billing_profile_id,
    generate_estimate_invoice,currency,einvoice_type, product_taxation_type
INTO  @invoice_number , @invoice_total , @grand_total , @advance , @swipez_total ,
 @bill_date , @previous_due , @basic_amount , @tax_amount ,  @narrative , @absolute_cost , 
 @late_fee , @expiry_date , @due_date , @customer_id , @merchant_id , @merchant_user_id , @cycle_id ,
 @status , @payment_request_type,@converted_request_id,@invoice_type , @template_id , @notify_patron , 
 @has_custom_reminder , @short_url , @franchise_id , @vendor_id,  @autocollect_plan_id,@paid_amount,@plugin_value,
 @document_url,@merchant_gst_number,@billing_profile_id,@generate_estimate_invoice,@currency,@einvoice_type , @invoice_product_taxation
 FROM payment_request
WHERE
    payment_request_id = @payment_req
        AND (_merchant_id = 'customer'
        OR merchant_id = _merchant_id);

if(@customer_id>0)then

    select customer_code, concat(first_name,' ',last_name),concat(address,address2),city,zipcode,state,user_id,email,mobile,gst_number,company_name,country into @customer_code,@customer_name,@customer_address,@customer_city
    ,@customer_zip,@customer_state,@customer_user_id,@customer_email,@customer_mobile,@customer_gst_number,@customer_company_name,@customer_country from customer where customer_id=@customer_id;
    
 
SELECT 
    image_path,
    banner_path,
    template_name,
    template_type,
    particular_column,
    particular_total,
    tax_total,
    properties,
    tnc,
    hide_invoice_summary,
    design_name,
        design_color,
        footer_note,
        setting
INTO @image_path , @banner_path , @template_name , @template_type,@particular_column,@particular_total,@tax_total,@properties,@tnc,@hide_invoice_summary,@design_name,@design_color,@footer_note,@setting  FROM
    invoice_template
WHERE
    template_id = @template_id;
 
SELECT 
    display_url,
    merchant_domain,
    merchant_type,
    is_legal_complete,
    disable_online_payment,
    merchant_website,
    merchant_plan
INTO @display_url , @merchant_domain , @merchant_type , @legal_complete , @disable_online_payment , @merchant_website, @merchant_plan   FROM
    merchant
WHERE
    merchant_id = @merchant_id;
 
SELECT 
    statement_enable,
    show_ad,
    promotion_id,
    from_email,
    settlements_to_franchise,
    sms_gateway,
    sms_gateway_type,
    sms_name,default_cust_column_renamed
INTO @statement_enable , @show_ad , @promotion_id , @from_email , @settlements_to_franchise , @sms_gateway , @sms_gateway_type , @sms_name,@default_cust_column_renamed FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
SET @customer_default_column=null;
if(@default_cust_column_renamed=1)then
select `value` into @customer_default_column from merchant_config_data where merchant_id=@merchant_id and `key`='CUSTOMER_DEFAULT_COLUMN';
end if;
 
 
SELECT 
    cycle_name
INTO @cycle_name FROM
    billing_cycle_detail
WHERE
    billing_cycle_id = @cycle_id;
 
SELECT 
    config_value
INTO @domain FROM
    config
WHERE
    config_type = 'merchant_domain'
        AND config_key = @merchant_domain;
 
if(@disable_online_payment=0 and @legal_complete=1)then 
SELECT 
    COUNT(fee_detail_id)
INTO @pg_count FROM
    merchant_fee_detail
WHERE
    merchant_id = @merchant_id and franchise_id=0
        AND is_active = 1;
 end if;
 
 
 if(@pg_count<1 or @disable_online_payment=1 or @legal_complete=0)then
 SET @legal_complete=0;
 end if;
 
 
 if(@billing_profile_id>0)then
 SELECT 
    address, business_email, business_contact,city,zipcode,state,company_name,company_name,gst_number, pan, tan, cin_no,sac_code,country
INTO @merchant_address , @business_email , @business_contact,@merchant_city,@merchant_zipcode,@merchant_state,@company_name,@display_name,@gst_number , @pan , @tan , @cin_no,@sac_code,@merchant_country  FROM
    merchant_billing_profile where id = @billing_profile_id;
 else
 SELECT 
    address, business_email, business_contact,city,zipcode,state,company_name,company_name,gst_number, pan, tan, cin_no,sac_code,country
INTO @merchant_address , @business_email , @business_contact,@merchant_city,@merchant_zipcode,@merchant_state,@company_name,@display_name,@gst_number , @pan , @tan , @cin_no,@sac_code,@merchant_country  FROM
    merchant_billing_profile
WHERE
    merchant_id = @merchant_id and is_default=1;
 end if;
 
 SET @sms_name=@company_name;
 
SET @is_expire= 0;
  if(DATE_FORMAT(NOW(),'%Y-%m-%d') >= @expiry_date) then
 SET @is_expire= 1;
 end if;

 if(DATE_FORMAT(NOW(),'%d %b %Y') > @due_date and @absolute_cost>0) then
 SET @absolute_cost= @absolute_cost + @late_fee;
 end if;
 
 select icon into @currency_icon from currency where `code`=@currency;

if(@merchant_plan IN (3, 4, 9, 12, 13, 14,15)) then
	SET @paid_user = 1;
else 
	SET @paid_user = 0;
end if;
 
SET @message= 'success';
SELECT 
    _paymentrequest_id AS 'payment_request_id',
    @is_expire AS 'is_expire',
    @invoice_number AS 'invoice_number',
    @expiry_date AS 'expiry_date',
    @notify_patron AS 'notify_patron',
    @domain AS 'merchant_domain',
    @display_url AS 'display_url',
    @merchant_id AS 'merchant_id',
    @merchant_user_id AS 'merchant_user_id',
    @particular_column as 'particular_column',
    @particular_total as 'particular_total',
    @tax_total as 'tax_total',
    @plugin_value as 'plugin_value',
    @invoice_product_taxation as 'invoice_product_taxation',
    @status AS 'status',
    @narrative AS 'narrative',
    @absolute_cost AS 'absolute_cost',
    @advance AS 'advance',
    @previous_due AS 'previous_due',
    @cycle_name AS 'cycle_name',
    @grand_total AS 'grand_total',
    @due_date AS 'due_date',
    @Previous_dues AS 'Previous_dues',
    @statement_enable AS 'statement_enable',
    @show_ad AS 'show_ad',
    @bill_date AS 'bill_date',
    @basic_amount AS 'basic_amount',
    @tax_amount AS 'tax_amount',
    @swipez_total AS 'swipez_total',
    @invoice_total AS 'invoice_total',
    @message AS 'message',
    @company_name AS 'company_name',
    @merchant_type AS 'merchant_type',
    @paid_user AS 'paid_user',
    @legal_complete AS 'legal_complete',
    @image_path AS 'image_path',
    @banner_path AS 'banner_path',
    @template_name AS 'template_name',
    @template_type AS 'template_type',
    @merchant_address AS 'merchant_address',
    @merchant_state as 'merchant_state',
    @merchant_city as 'merchant_city',
    @merchant_zipcode as 'merchant_zipcode',
    @merchant_country AS 'merchant_country',
    @business_email AS 'business_email',
    @business_contact AS 'business_contact',
    @late_fee AS 'late_fee',
    @status AS 'payment_request_status',
    @payment_request_type AS 'payment_request_type',
    @invoice_type AS 'invoice_type',
    @template_id AS 'template_id',
    @coupon_id AS 'coupon_id',
    @is_coupon AS 'is_coupon',
    @promotion_id AS 'promotion_id',
    @customer_id AS 'customer_id',
    @customer_code AS 'customer_code',
    @customer_name AS 'customer_name',
    @customer_address AS 'customer_address',
    @customer_city AS 'customer_city',
    @customer_zip AS 'customer_zip',
    @customer_state AS 'customer_state',
    @customer_country AS 'customer_country',
    @customer_email AS 'customer_email',
    @customer_mobile AS 'customer_mobile',
    @customer_user_id AS 'customer_user_id',
    @custom_subject AS 'custom_subject',
    @custom_sms AS 'custom_sms',
    @has_custom_reminder AS 'has_custom_reminder',
    @covering_id AS 'covering_id',
    @franchise_id AS 'franchise_id',
    @vendor_id as 'vendor_id',
    @is_franchise AS 'is_franchise',
    @has_vendor as 'has_vendor',
    @merchant_website AS 'merchant_website',
    @gst_number AS 'gst_number',
    @pan AS 'pan',
    @tan AS 'tan',
    @cin_no AS 'cin_no',
    @registration_number AS 'registration_number',
    @from_email AS 'from_email',
    @settlements_to_franchise AS 'pg_to_franchise',
    @is_prepaid AS 'is_prepaid',
    @has_acknowledgement AS 'has_acknowledgement',
    @main_company_name AS 'main_company_name',
    @sms_gateway AS 'sms_gateway',
    @sms_gateway_type AS 'sms_gateway_type',
    @sms_name AS 'sms_name',
    @autocollect_plan_id as 'autocollect_plan_id',
    @paid_amount as 'paid_amount',
    @converted_request_id AS 'converted_request_id',
    @short_url AS 'short_url',
    @document_url AS 'document_url',
    @tnc as 'tnc',
    @hide_invoice_summary as 'hide_invoice_summary',
    @properties as 'properties',
    @billing_profile_id as 'billing_profile_id',
    @generate_estimate_invoice as 'generate_estimate_invoice',
    @customer_default_column as 'customer_default_column',
	@sac_code as 'sac_code',
    @currency as 'currency',
    @einvoice_type as 'einvoice_type',
    @currency_icon as `currency_icon`,
    @customer_gst_number as 'customer_gst_number',
    @customer_company_name as 'customer_company_name',
    @design_name as 'design_name',
    @design_color as 'design_color',
    @footer_note as 'footer_note',
     @setting as 'setting',
    @message as 'message';
    else
    SET @message='Invalid';
    select @message as 'message';
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getPayment_receipt` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getPayment_receipt`(_payment_transaction_id varchar(10),_type varchar(20))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
		END; 
SET @patron_email='';
SET @transaction_id='';
SET @settlement_date='';
SET @patron_user_id='';
SET @patron_name='';
SET @pay_mode='';
SET @success='';
SET @cheque_status='';
SET @franchise_id=0;
SET @customer_id=0;
SET @event_name='';
SET @unit_type='';
SET @type='';
SET @quantity =0;
SET @xway_type =0;
SET @franchise_name_invoice=1;
SET @estimate_req_id='';
SET @customer_code='';
SET @invoice_number='';
SET @profile_id =0;
SET @currency='INR';
if(_type='Online' or _type='Offline') then
		if(_type='Offline') then
			SELECT 'success',payment_request_id,customer_id,settlement_date,last_update_date,bank_transaction_no,amount,cheque_no,cheque_status,cash_paid_to,narrative,merchant_id,offline_response_type,bank_name,payment_request_type,tax,discount,is_availed,quantity,narrative,deduct_amount,deduct_text,created_date,quantity,currency
			into @success,@payment_request_id,@customer_id,@settlement_date,@update_on,@bank_transaction_no,@amount,@cheque_no,@cheque_status,@cash_paid_to,@narrative,@merchant_id,@offline_response_type,@bank_name,@payment_request_type,@tax,@discount,@is_availed,@quantity,@narrative,@deduct_amount,@deduct_text,@transaction_date,@quantity,@currency
			FROM offline_response where offline_response_id=_payment_transaction_id and is_active=1;
		
			SELECT config_value INTO @payment_mode FROM config WHERE config_key = @offline_response_type AND config_type = 'offline_response_type';
		else
			select 'success',pay_transaction_id,estimate_request_id,payment_request_id,customer_id,patron_user_id,customer_id,merchant_id,pg_ref_no,amount,payment_request_type,tax,discount,is_availed,quantity,narrative,deduct_amount,deduct_text,created_date,quantity,payment_mode,currency into 
			@success,@transaction_id,@estimate_req_id,@payment_request_id,@customer_id,@patron_user_id,@customer_id,@merchant_id,@pg_ref_no,@amount,@payment_request_type,@tax,@discount,@is_availed,@quantity,@narrative,@deduct_amount,@deduct_text,@transaction_date,@quantity,@pay_mode,@currency from
			 payment_transaction where pay_transaction_id=_payment_transaction_id;
             
		end if;
        
		select concat(first_name,' ',last_name),email,mobile,customer_code into @patron_name,@patron_email,@patron_mobile,@customer_code from customer where customer_id=@customer_id ;
        
        if(@payment_request_type=2)then
		select template_id,franchise_id,event_name,unit_type,'event' into @template_id,@franchise_id,@event_name,@unit_type,@type from event_request where event_request_id=@payment_request_id;
		else
		select template_id,franchise_id,billing_profile_id,invoice_number into @template_id,@franchise_id,@profile_id,@invoice_number from payment_request where payment_request_id=@payment_request_id;
		end if;
        
        SELECT image_path INTO @image FROM invoice_template WHERE    template_id = @template_id;
        
else
		select 'success',name,email,phone,merchant_id,amount,description,customer_code,created_date,payment_mode,`type` into @success,@patron_name,@patron_email,@patron_mobile,@merchant_id,@amount,@narrative,@customer_code,
            @transaction_date,@pay_mode,@xway_type from xway_transaction where xway_transaction_id= _payment_transaction_id;
end if;

 SET @settlement_date=@transaction_date;
	
SELECT 
    user_id
INTO @merchant_user_id FROM
    merchant
WHERE
    merchant_id = @merchant_id;
    
if(@profile_id>0)then
	select business_email,company_name,company_name into 
   @merchant_email, @display_name , @company_name from merchant_billing_profile where id=@profile_id;
else
	select business_email,company_name,company_name into 
   @merchant_email, @display_name , @company_name from merchant_billing_profile where merchant_id = @merchant_id and is_default=1;
end if;
    
SELECT 
    `logo`
INTO @merchant_logo FROM
    merchant_landing
WHERE
    merchant_id = @merchant_id;
    
SELECT 
    `default_cust_column_renamed`
INTO @default_cust_column_renamed FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
    
SET @customer_default_column=null;
if(@default_cust_column_renamed=1)then
select `value` into @customer_default_column from merchant_config_data where merchant_id=@merchant_id and `key`='CUSTOMER_DEFAULT_COLUMN';
end if; 

if(@patron_name='')then
SET @patron_name='Guest patron';
end if;

select icon into @currency_icon from currency where code=@currency;

SET @main_company_name='';

  if(@franchise_id>0)then
SET @merchant_email_id=@merchant_email;
SET @main_company_name=@company_name;
SELECT 
    franchise_name, email_id, mobile
INTO @company_name , @merchant_email , @merchant_mobile_no FROM
    franchise
WHERE
    franchise_id = @franchise_id;
 SET @display_name=@company_name;
 
   if(@merchant_email='')then
 SET @merchant_email=@merchant_email_id;
 end if;
 end if;

SELECT 
    @success AS 'success',
    @image AS 'image',
    @invoice_number AS 'invoice_number',
    @merchant_logo AS 'merchant_logo',
    @company_name AS 'company_name',
    @main_company_name AS 'main_company_name',
    @display_name AS 'display_name',
    _payment_transaction_id AS 'transaction_id',
    @patron_email AS 'patron_email',
    @patron_mobile AS 'patron_mobile',
    @patron_name AS 'patron_name',
    @transaction_date AS 'date',
    @update_on AS 'update_on',
    @transaction_date AS 'create_date',
    @bank_transaction_no AS 'transaction_no',
    @amount AS 'amount',
    @cheque_no AS 'cheque_no',
    @cheque_status AS 'cheque_status',
    @cash_paid_to AS 'cash_paid_to',
    @offline_response_type AS 'type',
    @bank_name AS 'bank_name',
    @pay_mode AS 'payment_mode',
    @ref_no AS 'ref_no',
    @payment_request_type AS 'payment_request_type',
    @tax AS 'tax',
    @discount AS 'discount',
    @merchant_user_id AS 'merchant_user_id',
    @deduct_amount AS 'deduct_amount',
    @deduct_text AS 'deduct_text',
    @is_availed AS 'is_availed',
    @quantity AS 'quantity',
    @narrative AS 'narrative',
    @customer_code AS 'customer_code',
    @customer_id AS 'customer_id',
    @merchant_email AS 'merchant_email',
    @payment_request_id AS 'payment_request_id',
    @estimate_req_id as 'estimate_req_id',
    @event_name AS 'event_name',
    @unit_type AS 'unit_type',
    @type AS 'type',
    @customer_id as 'customer_id',
    @xway_type as 'xway_type',
    @customer_default_column as 'customer_default_column',
    @currency as 'currency',
    @currency_icon as 'currency_icon',
    @quantity AS 'quantity';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getuser_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getuser_details`(_customer_id varchar(10), _paymentrequest_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 show errors;
		BEGIN
		END; 
SET @payment_req=_paymentrequest_id;
set @user_id='';
set @company_merchant_id='';
SET @swipez_total=0;
SET @patron_email='';
SET @merchant_address2='';
SET @customer_id=_customer_id;

select user_id,merchant_id,payment_request_type,paid_amount,narrative,absolute_cost,late_payment_fee,due_date,swipez_total,currency into @user_id,@company_merchant_id,@payment_request_type,@paid_amount,@narrative,@absolute_cost,@late_fee,@due_date,@swipez_total,@currency from payment_request where payment_request_id=@payment_req and customer_id=_customer_id 
and payment_request_status in (0,4,5,7);

if (@user_id <> '') then
	select  email_id,first_name,last_name,mobile_no into @merchant_email,@merchant_first_name,@merchant_last_name,@merchant_mobile_no from user where user_id=@user_id;
	select company_name,address,city,zipcode,`state` into @company_name,@merchant_address1,@merchant_city,@merchant_zipcode,
	@merchant_state from merchant_billing_profile where merchant_id=@company_merchant_id and is_default=1;

	SET @display_name=@company_name;

	select customer_code, first_name,last_name,address,address2,city,zipcode,state,email,mobile into @customer_code,@patron_first_name,@patron_last_name,
	@patron_address1,@patron_address2,@patron_city,@patron_zipcode,@patron_state,@patron_email,@patron_mobile_no from customer where customer_id=@customer_id;

	SET @absolute_cost= @absolute_cost - @paid_amount;

	 if(DATE_FORMAT(NOW(),'%Y-%m-%d') > @due_date) then
	 SET @absolute_cost= @absolute_cost + @late_fee;
	 end if;

	set @message='success';
	select @message,@merchant_email,@merchant_first_name,@merchant_last_name,@merchant_mobile_no,@merchant_address1,@merchant_address2,@merchant_city,@merchant_zipcode,
	@merchant_state,@patron_email,@patron_first_name,@patron_last_name,@patron_mobile_no,@patron_address1,@patron_address2,@patron_city,@patron_zipcode,
	@patron_state,@narrative,@absolute_cost,@swipez_total,@user_id,@company_name,@company_merchant_id,@message,@payment_request_type,@currency as 'currency';
end if ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_all_customer_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_customer_list`(_start INT,_limit INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_customer;
set tmp_table_size=333554432;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_customer (
    `customer_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NULL,
    `merchant_id` varchar(10) NOT NULL,
    `merchant_name` varchar(100) NULL,
    `email` varchar(100) NULL,
    `mobile` varchar(15) NULL,
    `name` varchar(100) NOT NULL,
    `password` varchar(100)  NULL,
    `customer_status` tinyint(1) NULL,
    `payment_status` tinyint(1) NULL,
    `created_date` datetime null,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
       		insert into temp_merchant_view_customer(customer_id,user_id,name,email,mobile,customer_status,payment_status,merchant_id) 
			select customer_id,user_id,concat(first_name,' ',last_name),email,mobile,customer_status,payment_status,merchant_id 
            from customer where merchant_id not in('M000000737','M000000750','M000000753','M000000798','M000000471','M000000473','M000000475','M000000507','M000000474','M000000508','M000000512','M000000215','M000000472','M000000200','M000000801','M000000338','M000000001')  and is_active=1 limit _start,_limit;
    

    update temp_merchant_view_customer t, merchant c set t.merchant_name=c.company_name where t.merchant_id=c.merchant_id; 
    update temp_merchant_view_customer t, user c set t.created_date=c.created_date,t.password=c.password where t.user_id=c.user_id; 

    
    select * from temp_merchant_view_customer where email<>'';

     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_approval` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_approval`(_merchant_id varchar(10),_from_date datetime , _to_date datetime,_type varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


Drop TEMPORARY  TABLE  IF EXISTS temp_approval;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_approval (
    `change_detail_id` INT NOT NULL ,
    `change_id` INT NOT NULL,
    `column_type` INT NOT NULL,
    `column_value_id` int NOT NULL,
	`status` int NOT NULL,
    `column_name` varchar(20) NULL,
    `customer_id` INT NOT NULL,
    `current_value` varchar(500)  NULL,
    `changed_value` varchar(500)  NULL,
    `date` DATETIME not null,
    `update_date` DATETIME not null,
    `customer_code` varchar (100) null,
    `name` varchar (250) null,
	`email` varchar (100) null,
    `mobile` varchar (15) null,
    
    PRIMARY KEY (`change_detail_id`)) ENGINE=MEMORY;
    
    if(_type='pending') then  
	insert into temp_approval(change_detail_id,change_id,column_type,column_value_id,customer_id,current_value,changed_value,status,
    date,update_date) 
    select change_detail_id,d.change_id,column_type,column_value_id,c.customer_id,current_value,changed_value,d.status,c.created_date,d.last_update_date
    from customer_data_change c inner join customer_data_change_detail d on c.change_id=d.change_id where c.merchant_id=_merchant_id and d.status=0
    and DATE_FORMAT(c.created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(c.created_date,'%Y-%m-%d') <= _to_date ;
        else
        insert into temp_approval(change_detail_id,change_id,column_type,column_value_id,customer_id,current_value,changed_value,status,
    date,update_date) 
    select change_detail_id,d.change_id,column_type,column_value_id,c.customer_id,current_value,changed_value,d.status,c.created_date,d.last_update_date
    from customer_data_change c inner join customer_data_change_detail d on c.change_id=d.change_id where c.merchant_id=_merchant_id and d.status<>0
    and DATE_FORMAT(c.created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(c.created_date,'%Y-%m-%d') <= _to_date ;
        end if;
    
    
    update temp_approval r , customer u  set r.customer_code = u.customer_code,r.name=concat(u.first_name,' ',u.last_name),r.email=u.email,r.mobile=u.mobile  where r.customer_id=u.customer_id;
    
    update temp_approval r , config c  set r.column_name = c.config_value where r.column_type=c.config_key and c.config_type='change_column_type' ;

    
    select * from temp_approval order by date desc ;
    
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_attendees_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_attendees_details`(_event_id varchar(10),_from_date date,_to_date date,_package_id INT,_occurence_id INT)
BEGIN
DECLARE bDone INT;
DECLARE col_id INT;
DECLARE curs CURSOR FOR select `column_id` FROM event_capture_metadata where event_id=_event_id limit 3;
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_attendees_table;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_attendees_table (
    `id` varchar(10) NOT NULL ,
    `package_id` INT NOT NULL,
    `occurence_id` INT NOT NULL,
    `transaction_id` varchar(10) NOT NULL,
    `transaction_date` datetime null,
    `attendee_customer_id` INT null,
    `mobile` varchar(15) null,
    `amount` DECIMAL(11,2) not null ,
    `customer_id` INT NULL,
    `is_availed` tinyint(1) NULL,
    `user_id` varchar(10) NULL,
    `package_name` varchar(250) NULL,
    `paid_by` varchar(250) NULL,
    `customer_code` varchar(50) NULL,
    `email` varchar(250) NULL,
    `event_name` varchar(250) NULL,
    `event_date` DATE null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

	if(_from_date is null)then
		
        if(_occurence_id>0)then 
			insert into temp_attendees_table(id,package_id,occurence_id,transaction_id,attendee_customer_id,amount,transaction_date,is_availed)
    select event_transaction_detail_id,package_id,occurence_id,transaction_id,customer_id,amount,created_date,is_availed
    from event_transaction_detail where event_request_id =_event_id and is_paid=1 and occurence_id=_occurence_id;
        else
			insert into temp_attendees_table(id,package_id,occurence_id,transaction_id,attendee_customer_id,amount,transaction_date,is_availed)
    select event_transaction_detail_id,package_id,occurence_id,transaction_id,customer_id,amount,created_date ,is_availed
    from event_transaction_detail where event_request_id =_event_id and is_paid=1;
        end if;
            
    else
    
    if(_occurence_id>0)then 
			insert into temp_attendees_table(id,package_id,occurence_id,transaction_id,attendee_customer_id,amount,transaction_date,is_availed)
    select event_transaction_detail_id,package_id,occurence_id,transaction_id,customer_id,amount,created_date,is_availed
    from event_transaction_detail where event_request_id =_event_id and is_paid=1 and 
    DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date and occurence_id=_occurence_id;
        else
			insert into temp_attendees_table(id,package_id,occurence_id,transaction_id,attendee_customer_id,amount,transaction_date,is_availed)
    select event_transaction_detail_id,package_id,occurence_id,transaction_id,customer_id,amount,created_date,is_availed
    from event_transaction_detail where event_request_id =_event_id and is_paid=1 and 
    DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date;
        end if;
		
    end if;

UPDATE temp_attendees_table r,
    event_request c 
SET 
    r.event_name = c.event_name
WHERE
    c.event_request_id = _event_id;
UPDATE temp_attendees_table r,
    event_occurence m 
SET 
    r.event_date = m.start_date
WHERE
    r.occurence_id = m.occurence_id;
UPDATE temp_attendees_table r,
    event_package c 
SET 
    r.package_name = c.package_name
WHERE
    r.package_id = c.package_id;
UPDATE temp_attendees_table r,
    payment_transaction c 
SET 
    r.customer_id = c.customer_id
WHERE
    r.transaction_id = c.pay_transaction_id;
UPDATE temp_attendees_table r,
    offline_response c 
SET 
    r.customer_id = c.customer_id
WHERE
    r.transaction_id = c.offline_response_id;

	UPDATE temp_attendees_table r,
    customer b 
SET 
    r.paid_by = CONCAT(b.first_name, ' ', b.last_name),
    r.customer_code = b.customer_code,
    r.email=b.email,
    r.mobile=b.mobile

WHERE
    r.customer_id = b.customer_id;
	

	if(_package_id>0)then
    	select id,transaction_id,transaction_date,event_name,package_name,amount,event_date,paid_by,customer_code,email,attendee_customer_id,is_availed from temp_attendees_table where package_id=_package_id;

    else
			select id,transaction_id,transaction_date,event_name,package_name,amount,event_date,paid_by,customer_code,email,attendee_customer_id,is_availed from temp_attendees_table;

    end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_calendar_reservation` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_calendar_reservation`(_merchant_id varchar(10),_calendar_id INT,_from_date date,_to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_reservation_table;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_reservation_table (
    `id` INT NOT NULL ,
    `slot_id` INT NOT NULL,
    `transaction_id` varchar(10) NOT NULL,
    `is_active` INT NOT NULL DEFAULT 1,
    `amount` DECIMAL(11,2) not null ,
    `customer_id` INT NULL,
    `category_name` varchar(250) NULL,
    `calendar_title` varchar(250) NULL,
    `paid_by` varchar(250) NULL,
    `customer_code` varchar(50) NULL,
    `email` varchar(250) NULL,
	`mobile` varchar(15) null,
    `slot` varchar(250) NULL,
    `calendar_date` DATE null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

	if(_calendar_id>0)then

        insert into temp_reservation_table(id,slot_id,calendar_date,transaction_id,slot,amount,category_name,calendar_title)
    select booking_transaction_detail_id,slot_id,calendar_date,transaction_id,slot,amount,category_name,calendar_title
    from booking_transaction_detail where calendar_id =_calendar_id and is_paid=1;
    else
		insert into temp_reservation_table(id,slot_id,calendar_date,transaction_id,slot,amount,category_name,calendar_title)
		select booking_transaction_detail_id,slot_id,calendar_date,transaction_id,slot,amount,category_name,d.calendar_title
		from booking_transaction_detail d inner join booking_calendars c on d.calendar_id=c.calendar_id  
        where c.merchant_id =_merchant_id and d.is_paid=1 and DATE_FORMAT(d.calendar_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(d.calendar_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
    end if;

    update temp_reservation_table r , payment_transaction c  set r.customer_id = c.customer_id where r.transaction_id=c.pay_transaction_id;
    update temp_reservation_table r , offline_response c  set r.customer_id = c.customer_id,r.is_active = c.is_active where r.transaction_id=c.offline_response_id;
	
	update temp_reservation_table r , customer b  set r.paid_by = concat(b.first_name,' ',b.last_name) , r.customer_code=b.customer_code,r.email=b.email,r.mobile=b.mobile where r.customer_id=b.customer_id ;


	select * from temp_reservation_table where is_active=1;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_customer_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_details`(_customer_id INT,_merchant_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


select customer_id,customer_code,concat(first_name,' ',last_name) as name,first_name,last_name,email,mobile,address2,address,city,state,zipcode,customer_group,created_date 
    from customer where customer_id=_customer_id and merchant_id=_merchant_id;
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_customer_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_list`(_merchant_id varchar(10),_column_name longtext,_where longtext,_order longtext,_limit longtext,_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_customer;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_customer (
    `customer_id` varchar(10) NOT NULL ,
    `customer_code` varchar(45) NOT NULL,
    `email` varchar(250) NULL,
    `mobile` varchar(20) NULL,
    `name` varchar(100) NOT NULL,
    `customer_group` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
	`__Address` varchar(250) NOT NULL,
  	`address2` varchar(250) NOT NULL,
	`__City` varchar(50) NOT NULL,
    `customer_status` INT NULL,
    `payment_status` INT NULL,
	`__State` varchar(50) NOT NULL,
	`__Zipcode` varchar(50) NOT NULL,
    `balance` decimal(11,2) NULL,
	`created_date` datetime null,
    `company_name` varchar(100) NULL,
    `__Country` varchar(75) NULL,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
   

		if(_bulk_id>0)then
			insert into temp_merchant_view_customer(customer_id,customer_code,name,first_name,last_name,email,mobile,customer_group,address2,__Address,__City,__State,__Zipcode,created_date,customer_status,payment_status,balance,company_name,__Country) 
			select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,customer_group,address2,address,city,state,zipcode,created_date,customer_status,payment_status,balance,company_name,country from customer where merchant_id=_merchant_id and bulk_id=_bulk_id and is_active=1;
		else
			insert into temp_merchant_view_customer(customer_id,customer_code,name,first_name,last_name,email,mobile,customer_group,address2,__Address,__City,__State,__Zipcode,created_date,customer_status,payment_status,balance,company_name,__Country) 
			select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,customer_group,address2,address,city,state,zipcode,created_date,customer_status,payment_status,balance,company_name,country from customer where merchant_id=_merchant_id  and is_active=1;
		end if;
    
    
    SET @last='payment_status';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_merchant_view_customer ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_merchant_view_customer t , customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
    
    

SET @where=REPLACE(_where,'~',"'");
SET @count=0;    
    SET @sql='select count(customer_id) into @totalcount from temp_merchant_view_customer';
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @sql=concat('select count(customer_id) into @count from temp_merchant_view_customer ',@where );
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@totalcount from temp_merchant_view_customer ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_customer_pending_invoices` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_pending_invoices`(_customer_id INT,_start_date DATETIME)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


Drop TEMPORARY  TABLE  IF EXISTS temp_customer_pending_bill;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_pending_bill (
    `payment_request_id` varchar(10)  NULL ,
    `merchant_user_id` varchar(10)  NULL,
	`merchant_id` varchar(10) NOT NULL,
  	`merchant_domain` varchar(45) NULL,
    `customer_id` int NOT NULL,
    `customer_name` varchar(100)  NULL,
    `email` varchar(250)  NULL,
    `mobile` varchar(250)  NULL,
    `absolute_cost` DECIMAL(11,2)  null ,
    `payment_request_type` int null,
    `pg_id` int  null,
    `fee_id` int  null,
    `franchise_id` int  null,
    `vendor_id` int  null,
    `mid` varchar(100)  NULL,
    `paytm_key` varchar(100)  NULL,
    `post_url` varchar(250)  NULL,
    `txn_url` varchar(250)  NULL,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
    insert into temp_customer_pending_bill (payment_request_id,merchant_user_id,customer_id,absolute_cost,payment_request_type,franchise_id,vendor_id)
    select payment_request_id,user_id,customer_id,absolute_cost,payment_request_type,franchise_id,vendor_id from payment_request 
    where customer_id=_customer_id and payment_request_status in(0,4,5) and payment_request_type<>4 and created_date>_start_date ;
    
    update temp_customer_pending_bill r , customer u  set r.customer_name = concat(u.first_name," ",u.last_name),r.email=u.email,r.mobile=u.mobile where r.customer_id=u.customer_id;
        
    update temp_customer_pending_bill r , merchant u  set r.merchant_id = u.merchant_id,r.merchant_domain=u.merchant_domain where r.merchant_user_id=u.user_id;
    update temp_customer_pending_bill r , merchant_fee_detail u , payment_gateway p  set r.fee_id = u.fee_detail_id,r.pg_id = u.pg_id,r.mid=p.pg_val2,r.paytm_key=p.pg_val1,r.post_url=p.pg_val5,r.txn_url=p.req_url where r.merchant_id=u.merchant_id and p.pg_id=u.pg_id and p.pg_type=5 and p.is_active=1 and u.is_active=1;
	
    update temp_customer_pending_bill r , config u  set r.merchant_domain=u.config_value where r.merchant_domain=u.config_key and u.config_type='merchant_domain';

    select * from temp_customer_pending_bill;
    
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_customer_register_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_register_list`(_merchant_id varchar(10),_column_name longtext,_where longtext,_order longtext,_limit longtext,_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_rcustomer;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_rcustomer (
    `customer_id` varchar(10) NOT NULL ,
    `customer_code` varchar(45) NOT NULL,
    `user_id` varchar(45) NOT NULL,
    `email` varchar(250) NULL,
    `mobile` varchar(20)  null ,
    `name` varchar(100) NOT NULL,
    `password` varchar(100) NULL,
    `customer_group` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
	`__Address` varchar(250) NOT NULL,
  	`address2` varchar(250) NOT NULL,
	`__City` varchar(50) NOT NULL,
    `customer_status` INT NULL,
    `payment_status` INT NULL,
	`__State` varchar(50) NOT NULL,
	`__Zipcode` varchar(50) NOT NULL,
	`created_date` datetime null,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
			insert into temp_merchant_view_rcustomer(customer_id,user_id,customer_code,name,first_name,last_name,email,mobile,customer_group,address2,__Address,__City,__State,__Zipcode,created_date,customer_status,payment_status) 
			select customer_id,user_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,customer_group,address2,address,city,state,zipcode,created_date,customer_status,payment_status from customer where merchant_id=_merchant_id  and is_active=1 and (user_id is not null and user_id<>'');
    
    SET @last='payment_status';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_merchant_view_rcustomer ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_merchant_view_rcustomer t , customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
    
    
    update temp_merchant_view_rcustomer t, user c set t.password=c.password where t.user_id=c.user_id; 

SET @where=REPLACE(_where,'~',"'");
SET @count=0;    
    SET @sql=concat('select count(customer_id) into @count from temp_merchant_view_rcustomer ',@where );
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count from temp_merchant_view_rcustomer ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_customer_service_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_service_list`(_merchant_id varchar(10),_column_name longtext,_where longtext,_order longtext,_limit longtext,_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_customer;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_customer (
	`id` INT NOT NULL auto_increment,
    `customer_id` INT NOT NULL ,
    `service_id` INT NOT NULL default 0,
    `subscription_exist` INT NOT NULL default 0,
    `amount` decimal(11,2) NOT NULL default 0,
     `service_name` varchar(100) NULL,
    `customer_code` varchar(45) NOT NULL,
    `email` varchar(250) NULL,
    `mobile` varchar(20) NULL,
    `name` varchar(100) NOT NULL,
    `customer_group` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
	`__Address` varchar(250) NOT NULL,
  	`address2` varchar(250) NOT NULL,
	`__City` varchar(50) NOT NULL,
    `customer_status` INT NULL,
    `payment_status` INT NULL,
	`__State` varchar(50) NOT NULL,
	`__Zipcode` varchar(50) NOT NULL,
	`created_date` datetime null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
		if(_bulk_id>0)then
			insert into temp_merchant_view_customer(customer_id,customer_code,name,first_name,last_name,email,mobile,customer_group,address2,__Address,__City,__State,__Zipcode,created_date,customer_status,payment_status) 
			select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,customer_group,address2,address,city,state,zipcode,created_date,customer_status,payment_status from customer where merchant_id=_merchant_id and bulk_id=_bulk_id and is_active=1;
		else
			insert into temp_merchant_view_customer(customer_id,customer_code,name,first_name,last_name,email,mobile,customer_group,address2,__Address,__City,__State,__Zipcode,created_date,customer_status,payment_status) 
			select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,customer_group,address2,address,city,state,zipcode,created_date,customer_status,payment_status from customer where merchant_id=_merchant_id  and is_active=1;
		end if;
    
    
    SET @last='payment_status';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_merchant_view_customer ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_merchant_view_customer t , customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
    
    
    update temp_merchant_view_customer t, customer_service c set t.service_name=c.name,t.service_id=c.id,t.amount=c.cost where t.customer_id=c.customer_id and is_active=1 and cost>0; 
	
    update temp_merchant_view_customer t, subscription c set t.subscription_exist=1
    where t.service_id=c.service_id and c.is_active=1 and t.service_id>0; 
    
    update temp_merchant_view_customer t, jobs c set t.subscription_exist=1
    where t.service_id=c.service_id and c.attempts=0; 



SET @where=REPLACE(_where,'~',"'");
SET @count=0;    

	SET @sql=concat('select count(customer_id) into @count from temp_merchant_view_customer ',@where );
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count from temp_merchant_view_customer ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_customer_unregister_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_unregister_list`(_merchant_id varchar(10),_column_name longtext,_where longtext,_order longtext,_limit longtext,_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_urcustomer;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_urcustomer (
    `customer_id` varchar(10) NOT NULL ,
    `customer_code` varchar(45) NOT NULL,
    `email` varchar(250) NULL,
    `mobile` varchar(20)  null ,
    `name` varchar(100) NOT NULL,
    `customer_group` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
	`__Address` varchar(250) NOT NULL,
  	`address2` varchar(250) NOT NULL,
	`__City` varchar(50) NOT NULL,
    `customer_status` INT NULL,
    `payment_status` INT NULL,
	`__State` varchar(50) NOT NULL,
	`__Zipcode` varchar(50) NOT NULL,
	`created_date` datetime null,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    

			insert into temp_merchant_view_urcustomer(customer_id,customer_code,name,first_name,last_name,email,mobile,customer_group,address2,__Address,__City,__State,__Zipcode,created_date,customer_status,payment_status) 
			select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,customer_group,address2,address,city,state,zipcode,created_date,customer_status,payment_status from customer where merchant_id=_merchant_id  and is_active=1 and (user_id is null or user_id='');
    
    
    SET @last='payment_status';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_merchant_view_urcustomer ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_merchant_view_urcustomer t , customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
    
    

SET @where=REPLACE(_where,'~',"'");
SET @count=0;    
    SET @sql=concat('select count(customer_id) into @count from temp_merchant_view_urcustomer ',@where );
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count from temp_merchant_view_urcustomer ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_EventPatronId` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_EventPatronId`(_first_name varchar(100),_last_name varchar(100),_email varchar(250),_mobile varchar(13),_city varchar(45),_address varchar(500),_state varchar(45),_zipcode varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 

		BEGIN
			ROLLBACK;
            
		END; 
START TRANSACTION;

SET @pid='';

SELECT  user.user_id into @pid FROM `user` where `email_id`= _email limit 1 ;

if(@pid='') then
select generate_sequence('Patron_id') into @pid;
INSERT INTO `non_registered_patron` (`patron_id`, `first_name`,`last_name`, `email_id`, `mobile_no`,`city`,`address1`,`state`,`zipcode`,`is_registered`, `created_by`, `created_date`, `last_update_by`, `last_update_date`)
 VALUES (@pid,_first_name,_last_name,_email,_mobile,_city,_address,_state,_zipcode,0,@pid,CURRENT_TIMESTAMP() ,@pid,CURRENT_TIMESTAMP());
end if;
commit;

select @pid as 'patron_id';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_Eventuser_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_Eventuser_details`(_userid char(10), _paymentrequest_id char(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 
SET @payment_req=_paymentrequest_id;
set @merchant_id='';
SET @patron_id=_userid;
SET @patron_email='';
SET @merchant_address2='';
SET @patron_address1='';
SET @patron_address2='';
SET @patron_city='';
SET @patron_zipcode='';
SET @patron_state='';

select user_id,merchant_id into @merchant_id,@company_merchant_id  from event_request where event_request_id=@payment_req ;

if (@merchant_id <> '') then
	select  email_id,first_name,last_name,mobile_no into @merchant_email,@merchant_first_name,@merchant_last_name,@merchant_mobile_no from user where user_id=@merchant_id;
	select address,city,zipcode,`state`,company_name into @merchant_address1,@merchant_city,@merchant_zipcode,@merchant_state,@company_name from merchant_billing_profile where merchant_id=@company_merchant_id and is_default=1;
	
    SET @display_name=@company_name;
    
	select  email_id,first_name,last_name,mobile_no into @patron_email,@patron_first_name,@patron_last_name,@patron_mobile_no from user where user_id=@patron_id;

	set @message='success';
	select @merchant_email,@merchant_first_name,@merchant_last_name,@merchant_mobile_no,@merchant_address1,@merchant_address2,@merchant_city,@merchant_zipcode,
	@merchant_state,@patron_email,@patron_first_name,@patron_last_name,@patron_mobile_no,@patron_address1,@patron_address2,@patron_city,@patron_zipcode,
	@patron_state,@patron_id,@merchant_id,@company_name,@company_merchant_id,@display_name,@message;
end if ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_event_package_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_event_package_summary`(_event_id varchar(10))
BEGIN
DECLARE bDone INT;
DECLARE occ_id INT;
DECLARE mid CHAR(10);
DECLARE muid CHAR(10);
DECLARE curs CURSOR FOR select occurence_id FROM event_occurence where event_request_id=_event_id and is_active=1;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
 show errors;
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_package_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_package_summary (
    `id` INT auto_increment NOT NULL ,
    `package_id` INT NOT NULL,
    `occurence_id` INT NOT NULL,
    `package_type` INT NOT NULL,
    `date` date null,
    `start_time` time null,
    `end_time` time null,
    `package_name` varchar(250) null,
    `total_qty` INT not NULL default 0,
    `reserv_qty` INT not NULL default 0,
    `available_qty` INT  NULL,
    `price` decimal(11,2)  NULL,
    `available_amount` decimal(11,2)  not NULL default 0,
    `sold_amount` decimal(11,2) not NULL default 0,
    `unpaid_amount` decimal(11,2)  not NULL default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;


 OPEN curs;
    SET bDone = 0;
    REPEAT
		FETCH curs INTO occ_id;
	
    insert into temp_package_summary(package_id,package_type,package_name,total_qty,price,available_amount,occurence_id)
    select package_id,package_type,package_name,seats_available,price,seats_available*price,occ_id from event_package where event_request_id =_event_id ;
          
    UNTIL bDone END REPEAT;
    CLOSE curs;
    
    update temp_package_summary s,event_occurence o set s.date=o.start_date,s.start_time=o.start_time,s.end_time=o.end_time where s.occurence_id=o.occurence_id;
    
    
    UPDATE temp_package_summary o 
INNER JOIN
(
   SELECT occurence_id,package_id,
   count(event_transaction_detail_id) 'bookedqty',SUM(amount) 'bookedamt'
   FROM event_transaction_detail 
   where event_request_id=_event_id and is_paid=1
   GROUP BY occurence_id,package_id
) i ON o.occurence_id = i.occurence_id and  o.package_id = i.package_id
SET o.reserv_qty = i.bookedqty,o.sold_amount = i.bookedamt
WHERE o.occurence_id = i.occurence_id and  o.package_id = i.package_id;

update temp_package_summary  set available_qty=total_qty-reserv_qty,unpaid_amount=available_amount-sold_amount;


update  temp_package_summary s,event_package p set s.date=null ,s.package_name='' where p.package_type<>2 
AND occurence not like CONCAT('%', s.date , '%') and p.package_id=s.package_id;


select distinct package_name,date,start_time,end_time,total_qty,reserv_qty,available_qty,price,available_amount,sold_amount,unpaid_amount from temp_package_summary;





END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_failedTransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_failedTransaction`(_day INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
            show errors;
		END;
        
SET @date_diff=DATE_ADD(NOW(), INTERVAL -_day DAY);

Drop TEMPORARY  TABLE  IF EXISTS temp_falied_transaction;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_falied_transaction (
    `payment_request_id` varchar(10) NOT NULL ,
    `template_id` varchar(10)  NULL ,
    `user_id` varchar(10)  NULL,
    `merchant_id` varchar(10)  default '',
    `customer_id` INT  NULL,
    `payment_request_status` int Null ,
    `short_url` varchar(100)  NULL default '',
    `email` varchar(250)  NULL default '',
    `mobile` varchar(15)  NULL default '',
    `merchant_domain` int Null ,
    `merchant_domain_name` varchar(45)  null default '',
    `merchant_logo` varchar (100)  null default '',
    `logo` varchar (100)  null default '',
    `pre_email` INT not null default 1,
    `pre_sms` INT not null default 1,
    `sms_gateway` INT not null default 1,
    `sms_gateway_type` INT not null default 1,   
    `name` varchar(250)  null default '',
    `display_name` varchar(250)  null default '',
    `sms_name` varchar(50) default '',
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;


    insert into temp_falied_transaction(payment_request_id,merchant_id,user_id,customer_id)
    select distinct payment_request_id,merchant_id,merchant_user_id,customer_id from payment_transaction where payment_transaction_status in(0,4) and DATE_FORMAT(created_date,'%Y-%m-%d')= DATE_FORMAT(@date_diff,'%Y-%m-%d');
    
    
    update temp_falied_transaction r , payment_request m  set r.short_url=m.short_url,r.template_id = m.template_id,r.payment_request_status=m.payment_request_status  where r.payment_request_id=m.payment_request_id ;

    
    update temp_falied_transaction r , invoice_template m  set r.logo = m.image_path  where r.template_id=m.template_id ;

   update temp_falied_transaction r , merchant m  set r.name = m.company_name,r.display_name = m.display_name,r.merchant_id=m.merchant_id , r.merchant_domain=m.merchant_domain where r.merchant_id=m.merchant_id ;
   update temp_falied_transaction r , merchant_setting m  set r.sms_gateway = m.sms_gateway,r.sms_gateway_type=m.sms_gateway_type,r.sms_name=m.sms_name where r.merchant_id=m.merchant_id ;

   update temp_falied_transaction r , config c  set r.merchant_domain_name = c.config_value where r.merchant_domain=c.config_key and c.config_type='merchant_domain' ;
    

   update temp_falied_transaction r , merchant_landing m set r.merchant_logo = m.logo where r.merchant_id=m.merchant_id and m.logo is not null;

    update temp_falied_transaction t, customer c set t.email=c.email,t.mobile=c.mobile where t.customer_id=c.customer_id; 
   
   
   update temp_falied_transaction  set name = display_name where display_name<>'' or display_name is null;
	update temp_falied_transaction  set sms_name = name where sms_name='' or sms_name is null;
    
        
    update temp_falied_transaction t,unsubscribe u set t.mobile='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.mobile=t.mobile;
	update temp_falied_transaction t,unsubscribe u set t.email='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.email=t.email;

   select * from temp_falied_transaction where payment_request_status in(4,5);


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_gstr2a_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_gstr2a_summary`(_job_id INT, _where varchar(200))
BEGIN

Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_comparision;
set max_heap_table_size = 33554432;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_comparision (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `vendor_gstin` varchar(50) DEFAULT NULL,
  `vendor_name` varchar(50)  DEFAULT NULL,
  `status` varchar(50)  DEFAULT NULL,
  `purch_request_total_amount`  decimal(11,2) DEFAULT '0.00',
  `gst_request_total_amount`  decimal(11,2) DEFAULT '0.00',
  `diff_total_amount` decimal(11,2) DEFAULT '0.00',
  `tax_value_purch` decimal(11,2) DEFAULT '0.00',
  `tax_value_gst` decimal(11,2) DEFAULT '0.00',
  `tax_value`  decimal(11,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `job_idx` (`job_id`),
  KEY `id_idx` (`id`),
  KEY `vendor_gstin_idx` (`vendor_gstin`)
  ) ENGINE=MEMORY;

	if(_job_id>0) then
		insert into temp_invoice_comparision( job_id, vendor_gstin, vendor_name, status, purch_request_total_amount, gst_request_total_amount, diff_total_amount, tax_value_purch, tax_value_gst, tax_value)
		SELECT 
        job_id, 
        vendor_gstin, 
        vendor_name,
        status,
         SUM(purch_request_total_amount) purch_request_total_amount,
        SUM(gst_request_total_amount) gst_request_total_amount,
        (SUM(purch_request_total_amount) - SUM(gst_request_total_amount)) diff_total_amount,
        (SUM(purch_request_cgst) + SUM(purch_request_sgst) + SUM(purch_request_igst)) tax_value_purch,
        (SUM(gst_request_cgst) + SUM(gst_request_sgst) + SUM(gst_request_igst)) tax_value_gst,
        ((SUM(purch_request_cgst) + SUM(purch_request_sgst) + SUM(purch_request_igst)) - (SUM(gst_request_cgst) + SUM(gst_request_sgst) + SUM(gst_request_igst))) tax_value
        FROM
            invoice_comparision
        WHERE
            job_id = _job_id
        GROUP BY vendor_gstin;
	end if;
    
	SET @sql=concat('select * from temp_invoice_comparision',_where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_invoice_breckup` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_invoice_breckup`(_payment_request_id varchar(10),_type varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_breckup;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_breckup (
    `id` int NOT NULL AUTO_INCREMENT ,
    `invoice_id` INT NULL,
    `column_id` INT  NULL,
     `customer_column_id` INT  NULL,
    `value` varchar(500) NULL,
    `is_delete_allow` INT NULL,
    `save_table_name` varchar(45)  NULL,
    `column_name` varchar(500) NULL,
     `default_column_value` varchar(500) NULL,
    `position` varchar(5) NULL,
    `column_type` varchar(5) NULL,
    `column_datatype` varchar(45) NULL,
    `is_mandatory` INT NULL default 0,
    `column_position` INT NULL default 0,
    `sort_order` INT NULL default 0,
    `function_id` INT NULL default 0,
    `column_group_id` varchar(10)  NULL,
    `template_id` varchar(10)  NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    
Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_col_id;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_col_id (
	 `id` int NOT NULL AUTO_INCREMENT ,
    `column_id` INT  NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    
    if(_type='Bulkupload')then
		   insert into temp_invoice_breckup(invoice_id,column_id,sort_order,default_column_value,customer_column_id,value,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,function_id,column_group_id,template_id)  SELECT icv.invoice_id,icv.column_id,sort_order,default_column_value,customer_column_id,icv.value,icm.is_delete_allow,icm.save_table_name,icm.column_name,icm.position,icm.column_type,icm.column_datatype,icm.is_mandatory,icm.column_position,icm.function_id,icm.column_group_id,
	icm.template_id from staging_invoice_values as icv right outer join invoice_column_metadata as icm on icv.column_id = icm.column_id where icv.payment_request_id=_payment_request_id and icm.save_table_name<>'request'  and icv.is_active=1 order by icm.sort_order,icv.invoice_id;
    select template_id into @template_id from staging_payment_request where payment_request_id=_payment_request_id;
    else
		   insert into temp_invoice_breckup(invoice_id,column_id,sort_order,default_column_value,customer_column_id,value,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,function_id,column_group_id,template_id)  SELECT icv.invoice_id,icv.column_id,sort_order,default_column_value,customer_column_id,icv.value,icm.is_delete_allow,icm.save_table_name,icm.column_name,icm.position,icm.column_type,icm.column_datatype,icm.is_mandatory,icm.column_position,icm.function_id,icm.column_group_id,
	icm.template_id from invoice_column_values as icv right outer join invoice_column_metadata as icm on icv.column_id = icm.column_id where icv.payment_request_id=_payment_request_id and icm.save_table_name<>'request'  and icv.is_active=1 order by icm.sort_order,icv.invoice_id;
    select template_id into @template_id from payment_request where payment_request_id=_payment_request_id;
    end if;
    
    
     insert into temp_invoice_col_id(column_id)
     select column_id from temp_invoice_breckup;
    
    insert into temp_invoice_breckup(column_id,default_column_value,sort_order,customer_column_id,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,function_id,column_group_id) 
    SELECT column_id,default_column_value,sort_order,customer_column_id,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,function_id,column_group_id
	 from invoice_column_metadata  where template_id=@template_id and is_active=1 and is_template_column=1 and column_id not in (select column_id from temp_invoice_col_id)   order by sort_order,column_id;
     

    
select * from temp_invoice_breckup order by sort_order,column_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_membership_bookings` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_membership_bookings`(_merchant_id varchar(10),_from_date date,_to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
 show errors;
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_membership_table;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_membership_table (
    `id` INT NOT NULL ,
    `days` INT NOT NULL,
    `transaction_id` varchar(10) NOT NULL,
    `is_active` INT NOT NULL DEFAULT 1,
    `amount` DECIMAL(11,2) not null ,
    `customer_id` INT NULL,
    `category_id` INT NOT NULL,
    `category_name` varchar(250) NULL,
    `description` varchar(250) NULL,
    `paid_by` varchar(250) NULL,
    `customer_code` varchar(50) NULL,
    `email` varchar(250) NULL,
	`mobile` varchar(15) null,
    `slot` varchar(250) NULL,
    `date` DATE null,
    `start_date` DATE null,
    `end_date` DATE null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;


		insert into temp_membership_table(id,days,start_date,end_date,transaction_id,category_id,amount,description,customer_id,`date`)
		select id,days,start_date,end_date,transaction_id,category_id,amount,description,customer_id,created_date
		from customer_membership   
        where merchant_id =_merchant_id and status=1 and DATE_FORMAT(created_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(created_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;


	update temp_membership_table r , customer b  set r.paid_by = concat(b.first_name,' ',b.last_name) , r.customer_code=b.customer_code,r.email=b.email,r.mobile=b.mobile where r.customer_id=b.customer_id ;
    update temp_membership_table t, booking_categories c set t.category_name=c.category_name where t.category_id=c.category_id; 
    


	select * from temp_membership_table where is_active=1;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_merchant_bill_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_bill_transaction`(_userid varchar(10),_from_date date , _to_date date,_user_name varchar(10) ,_status int,_payment_request_type INT,_franchise_id INT,_bulk_id INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 show errors;
		BEGIN
		END; 

Drop TEMPORARY  TABLE  IF EXISTS merchant_view_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS merchant_view_transaction (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NOT NULL ,
    `estimate_request_id` varchar(10) NULL ,
    `payment_request_type` INT NULL default 1,
    `user_id` varchar(10) NOT NULL,
    `franchise_id` INT NOT NULL default 0,
    `customer_id` INT NOT NULL,
    `customer_code` varchar(45)  NULL,
    `invoice_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 0,
    `patron_id` varchar(10) NULL,
    `bulk_id` INT  NULL DEFAULT '0',
    `absolute_cost` DECIMAL(11,2) not null ,
    `received_cost` DECIMAL(11,2) not null DEFAULT '0.00',
    `deduct_amount` DECIMAL(11,2) not null DEFAULT '0.00',
    `unit_price` DECIMAL(11,2) not null DEFAULT '0.00',
    `quantity` int not null default 1,
    `payment_transaction_status` int not null,
    `is_reject` int not null default 0,
    `narrative` varchar(500) null,
    `date` DATETIME not null,
    `last_update_on` DATETIME not null,
    `due_date` DATETIME  null,
    `name` varchar (100) null,
    `is_availed` int not null default 1,
    `mode` varchar (10) null,
    `payment_mode` varchar (20) null,
    `pg_id` INT null default 0,
    `offline_response_type` INT null default 0,
    `status` varchar(250) null,
    `billing_cycle_id` varchar(10) null,
    `cycle_name` varchar(250) null,
     `count` int null,
    `currency` char(3) null,
	`currency_icon` nvarchar(5) null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    
if(_status <> -1)then
	if(_status=2) then
        if(_bulk_id>0)then
        insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,narrative,date,last_update_on,mode,quantity,offline_response_type) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		2,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type from offline_response where is_active=1 and merchant_id=_userid and offline_response_type<>4 and bulk_id=_bulk_id ;
        
        else
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,narrative,date,last_update_on,mode,quantity,offline_response_type) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		2,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type from offline_response where payment_request_type not in(5,2) and is_active=1 and merchant_id=_userid and offline_response_type<>4 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
        end if;
	else
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,patron_id,
		absolute_cost,deduct_amount,unit_price,is_availed,quantity,payment_transaction_status,narrative,date,last_update_on,mode,pg_id,payment_mode) 
		select pay_transaction_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,patron_user_id,amount,deduct_amount,unit_price,is_availed,quantity,
		payment_transaction_status,narrative,created_date,last_update_date,'online',pg_id,payment_mode from payment_transaction where  payment_transaction_status<>0 and payment_request_type not in(5,2) and merchant_id=_userid  and  payment_transaction_status=_status and DATE_FORMAT(created_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(created_date,'%Y-%m-%d') <= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
    end if;
	if(_status=3) then
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,is_reject,narrative,date,last_update_on,mode,quantity,offline_response_type) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		3,offline_response_type,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type from offline_response where payment_request_type not in(5,2) and is_active=1 and merchant_id=_userid and offline_response_type=4 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
	end if;

else

	insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,patron_id,
	absolute_cost,deduct_amount,unit_price,is_availed,quantity,payment_transaction_status,narrative,date,last_update_on,mode,pg_id,payment_mode) 
	select pay_transaction_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,patron_user_id,amount,deduct_amount,unit_price,is_availed,quantity,
	payment_transaction_status,narrative,created_date,last_update_date,'online',pg_id,payment_mode from payment_transaction where payment_transaction_status<>0 and payment_request_type not in(5,2) and merchant_id=_userid  and DATE_FORMAT(created_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(created_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;

	insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
	absolute_cost,deduct_amount,is_availed,payment_transaction_status,is_reject,narrative,date,last_update_on,mode,quantity,offline_response_type) 
	select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
	2,offline_response_type,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type from offline_response where payment_request_type not in(5,2) and is_active=1 and merchant_id=_userid  and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;

end if;
    
    

    update merchant_view_transaction t , payment_request r  set t.due_date = r.due_date ,t.currency=r.currency,
    t.payment_request_type=r.payment_request_type,t.bulk_id=r.bulk_id, t.billing_cycle_id=r.billing_cycle_id ,
    t.invoice_number=r.invoice_number,t.franchise_id=r.franchise_id where t.payment_request_id=r.payment_request_id ;
    
UPDATE merchant_view_transaction t,
    customer u 
SET 
    t.name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.customer_code
WHERE
    t.customer_id = u.customer_id;

UPDATE merchant_view_transaction t,
    payment_request p 
SET 
    received_cost = t.absolute_cost - p.convenience_fee
WHERE
    t.payment_request_id = p.payment_request_id
        AND t.payment_transaction_status = 1;
   
UPDATE merchant_view_transaction 
SET 
    received_cost = absolute_cost
WHERE
    payment_transaction_status = 2;
UPDATE merchant_view_transaction 
SET 
    received_cost = 0
WHERE
    payment_transaction_status = 4
        OR payment_transaction_status = 0;
   
UPDATE merchant_view_transaction t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.payment_transaction_status = c.config_key
        AND c.config_type = 'payment_transaction_status';
        
UPDATE merchant_view_transaction t,
    offline_response c 
SET 
    t.status = 'Offline failed'
WHERE
    t.pay_transaction_id = c.offline_response_id
        AND c.transaction_status = 0;
      
      
UPDATE merchant_view_transaction t,
    config c 
SET 
    t.payment_mode = c.config_value
WHERE
    t.offline_response_type = c.config_key
        AND c.config_type = 'offline_response_type'
        AND mode = 'offline';
    
UPDATE merchant_view_transaction SET payment_mode='IPG' where payment_mode is null or payment_mode='';

update merchant_view_transaction t,currency c set t.currency_icon=c.icon where c.code=t.currency;

    
UPDATE merchant_view_transaction t,
    billing_cycle_detail b 
SET 
    t.cycle_name = b.cycle_name
WHERE
    t.billing_cycle_id = b.billing_cycle_id;
   
SELECT 
    COUNT(invoice_number)
INTO @inv_count FROM
    merchant_view_transaction
WHERE
    invoice_number <> '';
    if(@inv_count>0)then
		update merchant_view_transaction set display_invoice_no = 1;
    end if;
    
    if(_franchise_id>0)then
		delete from merchant_view_transaction where franchise_id<>_franchise_id;
    end if;
    
    
if(_user_name<>'') then

		select * from merchant_view_transaction where billing_cycle_id=_user_name  order by last_update_on;

else
		select * from merchant_view_transaction order by last_update_on desc;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_merchant_cycledetail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_cycledetail`(_userid varchar(250),_type varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

Drop TEMPORARY  TABLE  IF EXISTS merchant_cycle_detail;

CREATE TEMPORARY TABLE IF NOT EXISTS merchant_cycle_detail (
    `merchant_id` varchar(10) NOT NULL,
    `name` varchar (100) null,
    PRIMARY KEY (`merchant_id`)) ENGINE=MEMORY;
    
    
    if(_type='Request')then
     insert into merchant_cycle_detail(merchant_id)  select distinct merchant_id
    from customer where email=_userid ;
    
    else
			insert into merchant_cycle_detail(merchant_id)  select distinct merchant_id
			from customer where email=_userid  ;
    end if;
   
    update merchant_cycle_detail t , merchant u  set t.name = u.company_name where t.merchant_id=u.merchant_id ;

 
    select distinct name,merchant_id as id from merchant_cycle_detail;
   
    

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_merchant_viewevent` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_viewevent`(_userid varchar(10),_from_date datetime , _to_date datetime)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS merchant_view_event;

CREATE TEMPORARY TABLE IF NOT EXISTS merchant_view_event (
    `payment_request_id` varchar(10) NOT NULL ,
    `template_id` varchar(10) NULL,
    `event_name` varchar(250) NULL,
    `user_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `send_date` datetime not null,
    `due_date` DATETIME not null,
    `status` varchar(250) null,
     `count` int null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
      
       insert into merchant_view_event(payment_request_id,template_id,user_id,
    absolute_cost,payment_request_status,send_date,due_date) 
    select payment_request_id,template_id,user_id,absolute_cost,
    payment_request_status,bill_date,due_date from payment_request where user_id=_userid and payment_request_status=0 and payment_request_type=2 and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date;
        
   
    update merchant_view_event r , invoice_template u  set r.event_name = u.template_name where r.template_id=u.template_id ;
    
    select count(payment_request_id) into @count from merchant_view_event;
    update merchant_view_event r , invoice_template u  set r.count = @count;

    select *  from merchant_view_event order by payment_request_id desc;
     
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_merchant_viewrequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_viewrequest`(_merchant_id char(10),_from_date date, _to_date date,
_user_name varchar(11),_bulk_upload INT , _where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
 		BEGIN
        show errors;
		END;
SET @separator = '~';
set max_heap_table_size = 83554432;
SET @separatorLength = CHAR_LENGTH(@separator);
Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_request;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_request (
    `payment_request_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `bulk_id` INT NOT NULL,
    `franchise_id` INT NOT NULL,
    `customer_id` int NOT NULL,
    `customer_code` varchar(45)  NULL,
    `mobile` varchar(15)  NULL,
    `customer_group` varchar(45)  NULL default '',
    `invoice_number` varchar(45)  NULL default '',
    `estimate_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 1,
    `billing_cycle_id` varchar(10) NOT NULL,
    `parent_request_id` varchar(10) NOT NULL,
	`converted_request_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `invoice_type` tinyint(1) null default 1,
    `payment_request_type` tinyint(1) null default 1,
    `is_paid` tinyint(1) null default 0,
    `send_date` datetime not null,
    `due_date` DATETIME not null,
    `name` varchar (100) null,
    `status` varchar(25) null,
    `billing_cycle_name` varchar(100) null,
    `short_url` varchar(30) null,
    `currency` char(3) null,
	`currency_icon` nvarchar(5) null,
    `company_name` varchar (250) null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
if(_bulk_upload>0)then
	insert into temp_merchant_view_request(payment_request_id,invoice_type,payment_request_type,user_id,bulk_id,customer_id,billing_cycle_id,parent_request_id,converted_request_id,
	absolute_cost,payment_request_status,send_date,due_date,invoice_number,estimate_number,franchise_id,short_url,currency)
	select payment_request_id,invoice_type,payment_request_type,user_id,bulk_id,customer_id,billing_cycle_id,parent_request_id,converted_request_id,absolute_cost,
	payment_request_status,created_date,due_date,invoice_number,estimate_number,franchise_id,short_url,currency from payment_request where merchant_id=_merchant_id and payment_request_status not in (3,8)  and payment_request_type <>4
	and bulk_id=_bulk_upload and is_active=1 and (expiry_date is null or expiry_date>curdate());
else
	insert into temp_merchant_view_request(payment_request_id,invoice_type,payment_request_type,user_id,bulk_id,customer_id,billing_cycle_id,parent_request_id,converted_request_id,
	absolute_cost,payment_request_status,send_date,due_date,invoice_number,estimate_number,franchise_id,short_url,currency)
	select payment_request_id,invoice_type,payment_request_type,user_id,bulk_id,customer_id,billing_cycle_id,parent_request_id,converted_request_id,absolute_cost,
	payment_request_status,created_date,due_date,invoice_number,estimate_number,franchise_id,short_url,currency from payment_request where merchant_id=_merchant_id and payment_request_status not in (3,8) and payment_request_type <>4 
    and DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date and is_active=1 and (expiry_date is null or expiry_date>curdate());
end if;
UPDATE temp_merchant_view_request r,
    customer u
SET
    r.name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_code = u.customer_code,
    r.mobile=u.mobile,
    r.customer_group = u.customer_group,
    r.company_name = u.company_name
WHERE
    r.customer_id = u.customer_id;

update temp_merchant_view_request t,currency c set t.currency_icon=c.icon where c.code=t.currency;

UPDATE temp_merchant_view_request set is_paid=1 where payment_request_status in(1,2,7);  
#UPDATE temp_merchant_view_request set payment_request_status=8 where payment_request_status in(0,4,5) and due_date < current_date(); 
UPDATE temp_merchant_view_request SET invoice_number=estimate_number WHERE  invoice_type =2;
SET @where=REPLACE(_where,'~',"'");
	SET @count=0;
    SET @sql=concat('select count(payment_request_id) into @count from temp_merchant_view_request ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET @fcount=0;
    SET @sql=concat('select count(payment_request_id),sum(absolute_cost) into @fcount,@totalSum from temp_merchant_view_request ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_merchant_view_request ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_merchant_viewtransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_viewtransaction`(_merchant_id varchar(10),_from_date date , _to_date date,_user_name varchar(10) ,_status int,_payment_request_type INT,_franchise_id INT,_bulk_id INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 show errors;
		BEGIN
		END; 

Drop TEMPORARY  TABLE  IF EXISTS merchant_view_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS merchant_view_transaction (
    `pay_transaction_id` char(10) NOT NULL ,
    `payment_request_id` char(10) NOT NULL ,
    `estimate_request_id` varchar(10) NULL ,
    `payment_request_type` INT NULL default 1,
    `user_id` varchar(10) NOT NULL,
    `franchise_id` INT NOT NULL default 0,
    `customer_id` INT NOT NULL,
    `customer_code` varchar(45)  NULL,
    `invoice_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 0,
    `patron_id` varchar(10) NULL,
    `bulk_id` INT  NULL DEFAULT '0',
    `absolute_cost` DECIMAL(11,2) not null ,
    `received_cost` DECIMAL(11,2) not null DEFAULT '0.00',
    `deduct_amount` DECIMAL(11,2) not null DEFAULT '0.00',
    `unit_price` DECIMAL(11,2) not null DEFAULT '0.00',
    `quantity` int not null default 1,
    `payment_transaction_status` int not null,
    `is_reject` int not null default 0,
    `narrative` varchar(500) null,
    `date` DATETIME not null,
    `last_update_on` DATETIME not null,
    `due_date` DATETIME  null,
    `name` varchar (100) null,
    `is_availed` int not null default 1,
    `mode` varchar (10) null,
    `payment_mode` varchar (20) null,
    `pg_id` INT null default 0,
    `offline_response_type` INT null default 0,
    `status` varchar(250) null,
    `billing_cycle_id` varchar(10) null,
    `cycle_name` varchar(250) null,
     `count` int null,
     `currency` char(3) null,
	`currency_icon` nvarchar(5) null,
    PRIMARY KEY (`pay_transaction_id`),
    KEY `payment_request_idx` (`payment_request_id`)) ENGINE=MEMORY;
    
    
if(_status <> -1)then
	if(_status=2) then
        if(_bulk_id>0)then
        insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,narrative,date,last_update_on,mode,quantity,offline_response_type,currency) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		2,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type,currency from offline_response where is_active=1 and merchant_id=_merchant_id and offline_response_type<>4 and bulk_id=_bulk_id ;
        
        else
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,narrative,date,last_update_on,mode,quantity,offline_response_type,currency) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		2,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type,currency from offline_response where  is_active=1 and merchant_id=_merchant_id and offline_response_type not in (4,6) and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
        end if;
	else
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,patron_id,
		absolute_cost,deduct_amount,unit_price,is_availed,quantity,payment_transaction_status,narrative,date,last_update_on,mode,pg_id,payment_mode,currency) 
		select pay_transaction_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,patron_user_id,amount,deduct_amount,unit_price,is_availed,quantity,
		payment_transaction_status,narrative,created_date,last_update_date,'online',pg_id,payment_mode,currency from payment_transaction where payment_transaction_status<>0 and merchant_id=_merchant_id  and  payment_transaction_status=_status and DATE_FORMAT(created_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(created_date,'%Y-%m-%d') <= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
    end if;
	if(_status=3) then
		insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
		absolute_cost,deduct_amount,is_availed,payment_transaction_status,is_reject,narrative,date,last_update_on,mode,quantity,offline_response_type,currency) 
		select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
		3,offline_response_type,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type,currency from offline_response where is_active=1 and merchant_id=_merchant_id and offline_response_type=4 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
	end if;

else

	insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,patron_id,
	absolute_cost,deduct_amount,unit_price,is_availed,quantity,payment_transaction_status,narrative,date,last_update_on,mode,pg_id,payment_mode,currency) 
	select pay_transaction_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,patron_user_id,amount,deduct_amount,unit_price,is_availed,quantity,
	payment_transaction_status,narrative,created_date,last_update_date,'online',pg_id,payment_mode,currency from payment_transaction where payment_transaction_status<>0 and merchant_id=_merchant_id  and DATE_FORMAT(created_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(created_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;

	insert into merchant_view_transaction(pay_transaction_id,payment_request_id,estimate_request_id,user_id,customer_id,
	absolute_cost,deduct_amount,is_availed,payment_transaction_status,is_reject,narrative,date,last_update_on,mode,quantity,offline_response_type,currency) 
	select offline_response_id,payment_request_id,estimate_request_id,merchant_user_id,customer_id,amount,deduct_amount,is_availed,
	2,offline_response_type,narrative,settlement_date,last_update_date,'offline',quantity,offline_response_type,currency from offline_response where is_active=1 and merchant_id=_merchant_id and offline_response_type<>6  and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;

end if;
    
   
    
   if(_payment_request_type=2)then
    update merchant_view_transaction t , event_request r  set t.due_date = r.event_from_date,t.cycle_name=r.event_name,
    t.received_cost=t.absolute_cost,t.payment_request_type=2,t.franchise_id = r.franchise_id where t.payment_request_id=r.event_request_id ;
UPDATE merchant_view_transaction 
SET 
    received_cost = unit_price * quantity
WHERE
    payment_transaction_status = 1;
UPDATE merchant_view_transaction t,
    user u 
SET 
    t.name = CONCAT(u.first_name, ' ', u.last_name)
WHERE
    t.patron_id = u.user_id
        AND customer_id = 0;
    elseif(_payment_request_type=5)then
    update merchant_view_transaction t , booking_transaction_detail r  set t.due_date = r.calendar_date,t.cycle_name=concat(r.category_name,'-',r.calendar_title) 
    ,t.received_cost=t.absolute_cost,t.payment_request_type=5 where t.pay_transaction_id=r.transaction_id ;
UPDATE merchant_view_transaction t,
    user u 
SET 
    t.name = CONCAT(u.first_name, ' ', u.last_name)
WHERE
    t.patron_id = u.user_id
        AND customer_id = 0;
        elseif(_payment_request_type=6)then
    update merchant_view_transaction t , customer_membership r  set t.due_date = r.start_date,t.cycle_name=r.description 
    ,t.received_cost=t.absolute_cost,t.payment_request_type=6 where t.pay_transaction_id=r.transaction_id ;
    else
    update merchant_view_transaction t , payment_request r  set t.due_date = r.due_date ,t.currency=r.currency,
    t.payment_request_type=r.payment_request_type,t.bulk_id=r.bulk_id, t.billing_cycle_id=r.billing_cycle_id ,
    t.invoice_number=r.invoice_number,t.franchise_id=r.franchise_id where t.payment_request_id=r.payment_request_id ;
    end if;
    
UPDATE merchant_view_transaction t,
    customer u 
SET 
    t.name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.customer_code
WHERE
    t.customer_id = u.customer_id;

UPDATE merchant_view_transaction t,
    payment_request p 
SET 
    received_cost = t.absolute_cost - p.convenience_fee
WHERE
    t.payment_request_id = p.payment_request_id
        AND t.payment_transaction_status = 1;
   
UPDATE merchant_view_transaction 
SET 
    received_cost = absolute_cost
WHERE
    payment_transaction_status = 2;
UPDATE merchant_view_transaction 
SET 
    received_cost = 0
WHERE
    payment_transaction_status = 4
        OR payment_transaction_status = 0;
   
UPDATE merchant_view_transaction t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.payment_transaction_status = c.config_key
        AND c.config_type = 'payment_transaction_status';
        
UPDATE merchant_view_transaction t,
    offline_response c 
SET 
    t.status = 'Offline failed'
WHERE
    t.pay_transaction_id = c.offline_response_id
        AND c.transaction_status = 0;
        
update merchant_view_transaction t,currency c set t.currency_icon=c.icon where c.code=t.currency;
   
      
UPDATE merchant_view_transaction t,
    config c 
SET 
    t.payment_mode = c.config_value
WHERE
    t.offline_response_type = c.config_key
        AND c.config_type = 'offline_response_type'
        AND mode = 'offline';
    
UPDATE merchant_view_transaction SET payment_mode='IPG' where payment_mode is null or payment_mode='';

    
UPDATE merchant_view_transaction t,
    billing_cycle_detail b 
SET 
    t.cycle_name = b.cycle_name
WHERE
    t.billing_cycle_id = b.billing_cycle_id;
    
   
SELECT 
    COUNT(invoice_number)
INTO @inv_count FROM
    merchant_view_transaction
WHERE
    invoice_number <> '';
    if(@inv_count>0)then
		update merchant_view_transaction set display_invoice_no = 1;
    end if;
    
    if(_franchise_id>0)then
		delete from merchant_view_transaction where franchise_id<>_franchise_id;
    end if;
    

     
    
if(_user_name<>'') then
if(_payment_request_type=2)then
		select * from merchant_view_transaction where payment_request_id=_user_name and payment_request_type =_payment_request_type order by last_update_on;
else
		select * from merchant_view_transaction where billing_cycle_id=_user_name  order by last_update_on;
end if;                

else
		select * from merchant_view_transaction order by last_update_on desc;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_merchant_xwaytransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_xwaytransaction`(_merchant_id varchar(10),_from_date date , _to_date date,_status INT,_franchise_id INT,_type INT,_where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

SET @type=_type;

Drop TEMPORARY  TABLE  IF EXISTS temp_xway_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_xway_transaction (
    `xway_transaction_id` varchar(10) NOT NULL ,
    `amount` DECIMAL(11,2) not null ,
    `status` varchar(50) not null,
    `reference_no` varchar(50) not null,
    `franchise_id` INT not null default 0,
    `payment_request_id` varchar(10) null,
    `form_transaction_id` INT null,
    `franchise_name` varchar(100) null,
    `date` DATETIME not null,
    `customer_name` varchar (100) null,
    `email` varchar (250) null,
	`mobile` varchar (20) null,
    `ref_no` varchar (100) null,
    `payment_mode` varchar (20) null,
    `udf1` varchar (100) null,
    `udf2` varchar (100) null,
    `udf3` varchar (100) null,
    `udf4` varchar (100) null,
    `udf5` varchar (100) null,
    `description` varchar(500) null,
	`narrative` varchar (100) null,
    `currency` char(3) null,
	`currency_icon` nvarchar(5) null,
    PRIMARY KEY (`xway_transaction_id`)) ENGINE=MEMORY;
    
    if(_franchise_id>0)then
    
    if(_status<>-1) then
   insert into temp_xway_transaction(xway_transaction_id,amount,status,reference_no,
    date,customer_name,email,mobile,ref_no,franchise_id,udf1,udf2,udf3,udf4,udf5,description,payment_request_id,payment_mode,narrative,currency) 
    select xway_transaction_id,absolute_cost,xway_transaction_status,reference_no,created_date,name,email,phone,pg_ref_no1,franchise_id,LEFT(udf1 , 100),LEFT(udf2 , 100),LEFT(udf3 , 100),LEFT(udf4 , 100),LEFT(udf5 , 100),description,payment_request_id,payment_mode,narrative,currency
    from xway_transaction where xway_transaction_status=_status and merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date and `type`=@type and franchise_id=_franchise_id;
     
    else
    
    insert into temp_xway_transaction(xway_transaction_id,amount,status,reference_no,
    date,customer_name,email,mobile,ref_no,franchise_id,udf1,udf2,udf3,udf4,udf5,description,payment_request_id,payment_mode,narrative,currency) 
    select xway_transaction_id,absolute_cost,xway_transaction_status,reference_no,created_date,name,email,phone,pg_ref_no1,franchise_id,LEFT(udf1 , 100),LEFT(udf2 , 100),LEFT(udf3 , 100),LEFT(udf4 , 100),LEFT(udf5 , 100),description,payment_request_id,payment_mode,narrative,currency
    from xway_transaction where  xway_transaction_status<>0 and merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date  and `type`=@type and franchise_id=_franchise_id;
     
    end if;
    
    else
    if(_status<>-1) then
   insert into temp_xway_transaction(xway_transaction_id,amount,status,reference_no,
    date,customer_name,email,mobile,ref_no,franchise_id,udf1,udf2,udf3,udf4,udf5,description,payment_request_id,payment_mode,narrative,currency) 
    select xway_transaction_id,absolute_cost,xway_transaction_status,reference_no,created_date,name,email,phone,pg_ref_no1,franchise_id,LEFT(udf1 , 100),LEFT(udf2 , 100),LEFT(udf3 , 100),LEFT(udf4 , 100),LEFT(udf5 , 100),description,payment_request_id,payment_mode,narrative,currency
    from xway_transaction where xway_transaction_status=_status and merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date and `type`=@type;
     
    else
    
    insert into temp_xway_transaction(xway_transaction_id,amount,status,reference_no,
    date,customer_name,email,mobile,ref_no,franchise_id,udf1,udf2,udf3,udf4,udf5,description,payment_request_id,payment_mode,narrative,currency) 
    select xway_transaction_id,absolute_cost,xway_transaction_status,reference_no,created_date,name,email,phone,pg_ref_no1,franchise_id,LEFT(udf1 , 100),LEFT(udf2 , 100),LEFT(udf3 , 100),LEFT(udf4 , 100),LEFT(udf5 , 100),description,payment_request_id,payment_mode,narrative,currency
    from xway_transaction where  xway_transaction_status<>0 and merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date  and `type`=@type;
     
    end if;
    
     end if;
	

UPDATE temp_xway_transaction t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.status = c.config_key
        AND c.config_type = 'payment_transaction_status';
        
 UPDATE temp_xway_transaction SET payment_mode='IPG' where payment_mode is null or payment_mode='';
        update temp_xway_transaction t,currency c set t.currency_icon=c.icon where c.code=t.currency;

UPDATE temp_xway_transaction t,
    franchise c 
SET 
    t.franchise_name = c.franchise_name
WHERE
    t.franchise_id = c.franchise_id;
    
if(_type=2)then
    UPDATE temp_xway_transaction t,
    form_builder_transaction c 
SET 
    t.payment_request_id = c.payment_request_id,t.form_transaction_id = c.id
WHERE
    t.xway_transaction_id = c.transaction_id;
    end if;
    
SET @where=REPLACE(_where,'~',"'");


		
    SET @count=0;    
    SET @sql=concat('select count(xway_transaction_id),sum(amount) into @count,@totalSum from temp_xway_transaction ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;    
    SET @sql=concat('select count(xway_transaction_id),sum(amount) into @fcount,@totalSum from temp_xway_transaction ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_xway_transaction ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
        
Drop TEMPORARY  TABLE  IF EXISTS temp_xway_transaction;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_mybills` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_mybills`(_user_input varchar(250),_type INT,_merchant_id varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
            show errors;
		END;

SET @count=0;
SET @message='empty';

Drop TEMPORARY  TABLE  IF EXISTS temp_customer_ids;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_ids (
	`ID` INT auto_increment,
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`ID`)) ENGINE=MEMORY;
if(_merchant_id='')then
	if(_type=1) then
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `email`= _user_input;
	elseif(_type=2) then
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `mobile`= _user_input;
	else
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `customer_code`= _user_input;
	end if;
else
	if(_type=1) then
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `email`= _user_input and merchant_id=_merchant_id;
	elseif(_type=2) then
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `mobile`= _user_input and merchant_id=_merchant_id;
	else
		insert into temp_customer_ids(`customer_id`)  SELECT distinct customer_id FROM `customer` where `customer_code`= _user_input and merchant_id=_merchant_id;
	end if;
end if;

select count(`customer_id`) into @count from temp_customer_ids;

if(@count <> 0) then
Drop TEMPORARY  TABLE  IF EXISTS temp_patron_bills;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_bills (
    `payment_request_id` varchar(10) NOT NULL ,
    `merchant_id` char(10) NOT NULL,
    `user_id` char(10) NOT NULL,
    `customer_id` varchar(10) NOT NULL,
    `customer_code` varchar(45) NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `received_date` datetime not null,
    `expiry_date` date null,
    `due_date` DATE not null,
    `name` varchar (100) null default '',
    `display_name` varchar (100) null default '',
    `patron_name` varchar (100) null default '',
    `status` varchar(20) null default '',
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
    if(_merchant_id='')then
		insert into temp_customer_bills(payment_request_id,merchant_id,user_id,customer_id,
		absolute_cost,payment_request_status,received_date,due_date,expiry_date)
		select payment_request_id,merchant_id,user_id,customer_id,absolute_cost,
		payment_request_status,last_update_date,due_date,expiry_date from payment_request where payment_request.customer_id in 
		(select distinct `customer_id` from temp_customer_ids) and payment_request_type<>4 and  payment_request_status in(0,4,5) ;
	else
		insert into temp_customer_bills(payment_request_id,merchant_id,user_id,customer_id,
		absolute_cost,payment_request_status,received_date,due_date,expiry_date)
		select payment_request_id,merchant_id,user_id,customer_id,absolute_cost,
		payment_request_status,last_update_date,due_date,expiry_date from payment_request where merchant_id=_merchant_id and payment_request.customer_id in 
		(select distinct `customer_id` from temp_customer_ids) and payment_request_type<>4 and  payment_request_status in(0,4,5) ;
    end if;
    update temp_customer_bills r , merchant_billing_profile m  set r.name = m.company_name  , r.display_name = m.company_name where r.merchant_id=m.merchant_id and m.is_default=1;
    update temp_customer_bills r , config c  set r.status = c.config_value where r.payment_request_status=c.config_key and c.config_type='payment_request_status';
    update temp_customer_bills r , customer u  set r.patron_name = concat(u.first_name," ",u.last_name),r.customer_code=u.customer_code where r.customer_id=u.customer_id;
    
    delete from temp_customer_bills where expiry_date is not NULL and expiry_date<>'' and expiry_date<NOW();
        
    select count(*) into @count from temp_customer_bills;
    if(@count>0) then
    select * from temp_customer_bills order by payment_request_id desc limit 10;
    else
    select @message as 'message';
    end if;
    else
    select @message as 'message';
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_paid_invoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_paid_invoice`(_user_id varchar(10),_from_date datetime , _to_date datetime)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
show errors;
ROLLBACK;
END; 


SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_paid_invoice_details;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_paid_invoice_details (
`transaction_id` varchar(10) NOT NULL ,
`invoice_id` varchar(10) NOT NULL ,
`customer_id` INT NOT NULL,
`amount` DECIMAL(11,2) not null ,
`is_late` INT not null default 0 ,
`late_fee` DECIMAL(11,2) not null default 0 ,
`status` varchar(50) not null,
`paid_date` datetime not null,
`email` varchar (250) null,
`code` varchar (250) null,
`customer_name` varchar (100) null,
`payment_mode` varchar (45) null,
PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;


insert into temp_paid_invoice_details(transaction_id,invoice_id,customer_id,amount,is_late,status,paid_date,`payment_mode`) 
select pay_transaction_id,payment_request_id,customer_id,amount,late_payment,payment_transaction_status,last_update_date,5 
from payment_transaction where merchant_user_id=_user_id and paid_on >= @from_date  and paid_on <= @to_date and payment_transaction_status=1; 

insert into temp_paid_invoice_details(transaction_id,invoice_id,customer_id,amount,is_late,status,paid_date,`payment_mode`) 
select offline_response_id,payment_request_id,customer_id,amount,0,1,settlement_date,offline_response_type 
from offline_response where merchant_user_id=_user_id and settlement_date >= @from_date  and settlement_date <= @to_date and is_active=1;


UPDATE temp_paid_invoice_details r,
    customer p 
SET 
    r.customer_name = CONCAT(p.first_name, ' ', p.last_name),
    r.code = p.customer_code,
    r.email=p.email
WHERE
    r.customer_id = p.customer_id;
    
UPDATE temp_paid_invoice_details r,
    payment_request c 
SET 
    r.late_fee = c.late_payment_fee
WHERE
    r.invoice_id = c.payment_request_id
        AND r.is_late = 1;

UPDATE temp_paid_invoice_details t,
    config c 
SET 
    t.payment_mode = c.config_value
WHERE
    t.payment_mode = c.config_key
        AND c.config_type = 'offline_response_type';
        
SELECT 
    customer_name,
    email,
    code,
    transaction_id,
    invoice_id,
    amount,
    late_fee,
    paid_date,
    payment_mode
FROM
    temp_paid_invoice_details;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_partial_payments` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_partial_payments`(_payment_request_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 
set @merchant_id = '';

Drop TEMPORARY  TABLE  IF EXISTS tmppartial_payments;

CREATE TEMPORARY TABLE IF NOT EXISTS tmppartial_payments ( 
`transaction_id` varchar(10) NOT NULL , 
`amount` DECIMAL(11,2), 
`payment_date`  DATETIME null, 
`payment_mode` varchar(20) null,
`mode` varchar(10) null,
PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;
    
insert into tmppartial_payments (transaction_id,amount,payment_date,payment_mode,mode)
select pay_transaction_id,amount,created_date,'Online','Online' from payment_transaction where payment_request_id=_payment_request_id 
and payment_transaction_status=1;

insert into tmppartial_payments (transaction_id,amount,payment_date,mode,payment_mode)
select offline_response_id,amount,created_date,'Offine',offline_response_type from offline_response where payment_request_id=_payment_request_id 
and is_active=1;

update tmppartial_payments s ,  config c  set s.payment_mode = c.config_value where s.payment_mode=c.config_key and c.config_type='offline_response_type'; 

 select * from tmppartial_payments order by payment_date;
         

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_patron_viewrequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_patron_viewrequest`(_userid varchar(250),_from_date date , _to_date date,_user_name varchar(10)
)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
 show errors;
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS patron_view_request;

CREATE TEMPORARY TABLE IF NOT EXISTS patron_view_request (
    `payment_request_id` varchar(10) NOT NULL ,
    `merchant_id` varchar(10) NOT NULL,
    `user_id` varchar(10) NOT NULL,
    `customer_id` INT NOT NULL,
    `billing_cycle_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `received_date` datetime not null,
    `due_date` DATETIME not null,
    `merchant_type` int  null,
    `merchant_domain` int Null,
    `merchant_domain_name` varchar(100) null,
    `name` varchar (100) null,
    `status` varchar(250) null,
    `billing_cycle_name` varchar(40) null,
    `count` int  null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;


Drop TEMPORARY  TABLE  IF EXISTS temp_customer_ids;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_ids (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
insert into temp_customer_ids(customer_id)
select customer_id from customer where email=_userid;
    if(_user_name<>'')then
        insert into patron_view_request(payment_request_id,user_id,merchant_id,customer_id,billing_cycle_id,
    absolute_cost,payment_request_status,received_date,due_date)
    select payment_request_id,user_id,merchant_id,r.customer_id,billing_cycle_id,absolute_cost,
    payment_request_status,last_update_date,due_date from payment_request r inner join temp_customer_ids c  where r.customer_id =c.customer_id and 
    (payment_request_type<>4 OR parent_request_id<>'0') and payment_request_status in (0,4,5) and merchant_id = _user_name 
    and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date  ;

    else
        insert into patron_view_request(payment_request_id,user_id,merchant_id,customer_id,billing_cycle_id,
    absolute_cost,payment_request_status,received_date,due_date)
    select payment_request_id,user_id,merchant_id,r.customer_id,billing_cycle_id,absolute_cost,
    payment_request_status,last_update_date,due_date from payment_request r inner join temp_customer_ids c  where r.customer_id =c.customer_id and 
    (payment_request_type<>4 OR parent_request_id<>'0') and payment_request_status in(0,4,5) and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and 
    DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date;



    end if;
    update patron_view_request r , merchant m  set r.name = m.company_name , r.merchant_type=m.merchant_type,r.merchant_domain=m.merchant_domain where r.merchant_id=m.merchant_id ;
    update patron_view_request r , config c  set r.merchant_domain_name = c.config_value where r.merchant_domain=c.config_key and c.config_type='merchant_domain' ;


    update patron_view_request r , config c  set r.status = c.config_value where r.payment_request_status=c.config_key and c.config_type='payment_request_status';

     update patron_view_request r , billing_cycle_detail b  set r.billing_cycle_name = b.cycle_name where r.billing_cycle_id=b.billing_cycle_id ;

	select * from patron_view_request order by payment_request_id desc ;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_patron_viewtransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_patron_viewtransaction`(_userid varchar(250),_from_date date , _to_date date,_user_name varchar(10) ,_status int)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 show errors;
		BEGIN
		END; 

Drop TEMPORARY  TABLE  IF EXISTS patron_view_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS patron_view_transaction (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NOT NULL ,
    `payment_request_type` INT NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `customer_id` INT NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_transaction_status` int not null,
    `is_reject` int not null default 0,
    `narrative` varchar(500) null,
    `date` DATETIME not null,
    `last_update_on` DATETIME not null,
    `due_date` DATETIME  null,
    `name` varchar (100) null,
    `mode` varchar (10) null,
    `status` varchar(250) null,
    `count` int null,
    
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    
    Drop TEMPORARY  TABLE  IF EXISTS tempt_customer_ids;
CREATE TEMPORARY TABLE IF NOT EXISTS tempt_customer_ids (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
insert into tempt_customer_ids(customer_id)
select customer_id from customer where email=_userid;
    
    if(_user_name<>'' and _status <> -1)then
        
        if(_status=4) then
        insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and merchant_id=_user_name and payment_transaction_status in (4,0) and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    else        
          insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and merchant_id=_user_name and payment_transaction_status= _status and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    
    end if;
    
    if(_status=2) then
    
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and is_active=1 and offline_response_type <> 4 and  merchant_id=_user_name and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
    end if;
    
     if(_status=3) then
   insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and is_active=1 and offline_response_type = 4 and  merchant_id=_user_name and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
        end if;
    
    elseif(_user_name='' and _status <> -1)then
    
     if(_status=4) then
     
      insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and payment_transaction_status in (4,0) and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    
    else
    insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and payment_transaction_status=_status and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    end if;
    
     if(_status=2) then
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and offline_response_type <> 4 and is_active=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
   
   elseif(_status=3) then
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    3,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and offline_response_type=4 and is_active=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
  
    end if;
    
    elseif(_user_name<>'' and _status = -1) then
    
    insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and merchant_id=_user_name and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and is_active=1 and merchant_id=_user_name and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
    
    else
    
    insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and is_active=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
    
    end if;
    
    update patron_view_transaction t , payment_request r  set t.due_date = r.due_date where t.payment_request_id=r.payment_request_id ;
    
    update patron_view_transaction t , event_request r  set t.due_date = r.event_from_date where t.payment_request_id=r.event_request_id ;

   
    update patron_view_transaction t , merchant m  set t.name = m.company_name where t.user_id=m.user_id ;
    
   
    update patron_view_transaction t , config c  set t.status = c.config_value where t.payment_transaction_status=c.config_key and c.config_type='payment_transaction_status';
    select * from patron_view_transaction order by last_update_on desc;
          
    
    

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_pending_approval` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pending_approval`(_merchant_id varchar(10),_from_date datetime , _to_date datetime)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


Drop TEMPORARY  TABLE  IF EXISTS temp_pending_approval;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_pending_approval (
    `change_detail_id` INT NOT NULL ,
    `change_id` INT NOT NULL,
    `column_type` INT NOT NULL,
    `column_value_id` int NOT NULL,
    `column_name` varchar(20) NULL,
    `customer_id` INT NOT NULL,
    `current_value` varchar(500)  NULL,
    `changed_value` varchar(500)  NULL,
    `date` DATETIME not null,
    `customer_code` varchar (100) null,
    `name` varchar (250) null,
	`email` varchar (100) null,
    `mobile` varchar (15) null,
    
    PRIMARY KEY (`change_detail_id`)) ENGINE=MEMORY;
    
      
	insert into temp_pending_approval(change_detail_id,change_id,column_type,column_value_id,customer_id,current_value,changed_value,
    date) 
    select change_detail_id,d.change_id,column_type,column_value_id,c.customer_id,current_value,changed_value,c.created_date
    from customer_data_change c inner join customer_data_change_detail d on c.change_id=d.change_id where c.merchant_id=_merchant_id and d.status=0
    and DATE_FORMAT(c.created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(c.created_date,'%Y-%m-%d') <= _to_date ;
        
    
    
    update temp_pending_approval r , customer u  set r.customer_code = u.customer_code,r.name=concat(u.first_name,' ',u.last_name),r.email=u.email,r.mobile=u.mobile  where r.customer_id=u.customer_id;
    
    update temp_pending_approval r , config c  set r.column_name = c.config_value where r.column_type=c.config_key and c.config_type='change_column_type' ;

    
    select * from temp_pending_approval order by date desc ;
    
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_pending_bills` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pending_bills`(_days INT,_request_type INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
            show errors;
		END;
SET @date_match= DATE_ADD(CURDATE(), INTERVAL _days DAY);
SET @date_diff=DATE_ADD(NOW(), INTERVAL -24 HOUR);

Drop TEMPORARY  TABLE  IF EXISTS temp_pendings_bills;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_pendings_bills (
    `payment_request_id` varchar(10) NOT NULL ,
    `template_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `merchant_id` varchar(10) NULL default '',
    `customer_id` INT NOT NULL,
    `payment_request_status` int Null ,
    `payment_transaction_status` int Null ,
    `short_url` varchar(100)  NULL default '',
    `late_fee` decimal(11,2) Null ,
    `absolute_cost` decimal(11,2) Null ,
    `email` varchar(250) NOT NULL default '',
    `mobile` varchar(15)  NULL default '',
    `is_reminder` int Null default 1,
	`sms_gateway` int Null default 1,
    `sms_available` int Null default 0,
    `sms_gateway_type` int Null default 1,
    `sent_date` DATE not null,
    `due_date` DATE not null,
    `merchant_domain` int Null ,
    `merchant_domain_name` varchar(45)  null default '',
    `narrative` varchar (500)  null default '',
    `pre_email` INT not null default 1,
	`pre_sms` INT not null default 1,
    `name` varchar(250)  null default '',
    `company_name` varchar(250)  null default '',
    `reminder_day` INT NULL  default 0,
    `custom_subject` varchar(250) default '',
    `custom_sms` varchar(200) default '',
    `sms_name` varchar(250) default '',
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
    insert into temp_pendings_bills(payment_request_id,template_id,user_id,customer_id,payment_request_status,sent_date,due_date,late_fee,short_url,narrative,absolute_cost)
    select payment_request_id,template_id,user_id,customer_id,payment_request_status,last_update_date,due_date,late_payment_fee,short_url,narrative,absolute_cost 
    from payment_request where payment_request_status in(0,4,5) and absolute_cost>0 and notify_patron=1 and payment_request_type<>4 
    and DATE_FORMAT(created_date,'%Y-%m-%d')< @date_diff and DATE_FORMAT(due_date,'%Y-%m-%d')=@date_match and has_custom_reminder=0 and request_type=_request_type;




   update temp_pendings_bills r , merchant m  set r.name = m.company_name,r.company_name = m.company_name,r.merchant_id=m.merchant_id 
   , r.merchant_domain=m.merchant_domain where r.user_id=m.user_id ;
   
   update temp_pendings_bills r , merchant_setting m  set r.sms_gateway = m.sms_gateway,r.is_reminder=m.is_reminder,r.sms_gateway_type=m.sms_gateway_type ,r.sms_name=m.sms_name where r.merchant_id=m.merchant_id ;
   
   update temp_pendings_bills r , config c  set r.merchant_domain_name = c.config_value where r.merchant_domain=c.config_key and c.config_type='merchant_domain' ;
    
   update temp_pendings_bills t, payment_transaction c set t.payment_transaction_status=c.payment_transaction_status where t.payment_request_id=c.payment_request_id and c.payment_transaction_status=1; 

   update temp_pendings_bills t, customer c set t.email=c.email, t.mobile=c.mobile where t.customer_id=c.customer_id; 
  
	delete from temp_pendings_bills where payment_transaction_status=1;
  
	update temp_pendings_bills  set name = company_name where name='' or name is null;
	update temp_pendings_bills  set sms_name = name where sms_name='' OR sms_name is null;
    
    update temp_pendings_bills t,unsubscribe u set t.mobile='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.mobile=t.mobile;
	update temp_pendings_bills t,unsubscribe u set t.email='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.email=t.email;

	update temp_pendings_bills t,merchant_addon m set t.sms_available=1 where t.merchant_id=m.merchant_id and package_id=7 and license_available>0 and m.is_active=1 and end_date>curdate();

    select * from temp_pendings_bills where is_reminder=1;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_pending_notification_invoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pending_notification_invoice`()
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
        show errors;
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_pending_notification;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_pending_notification (
    `payment_request_id` varchar(10) NOT NULL ,
    `merchant_id` varchar(10) NULL default '',
    `customer_id` INT NOT NULL,
    `payment_request_status` int Null ,
    `email` varchar(250) NOT NULL default '',
    `mobile` varchar(20) NOT NULL default '',
    `merchant_domain` int Null ,
    `absolute_cost` decimal(11,2) NOT NULL default 0,
    `merchant_domain_name` varchar(45) not null default '',
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;

    insert into temp_pending_notification(payment_request_id,merchant_id,customer_id,payment_request_status,absolute_cost)
    select payment_request_id,merchant_id,customer_id,payment_request_status,absolute_cost from payment_request 
    where payment_request_type in (1,3) and notification_sent=0 and notify_patron=1 and payment_request_status in(0,5,4) and due_date>=curdate();

	update temp_pending_notification t, merchant c set t.merchant_domain=c.merchant_domain where t.merchant_id=c.merchant_id; 
   update temp_pending_notification r , config c  set r.merchant_domain_name = c.config_value where r.merchant_domain=c.config_key and c.config_type='merchant_domain' ;

   update temp_pending_notification t, customer c set t.email=c.email, t.mobile=c.mobile where t.customer_id=c.customer_id; 
   
   update temp_pending_notification t,unsubscribe u set t.mobile='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.mobile=t.mobile;
	update temp_pending_notification t,unsubscribe u set t.email='' where t.payment_request_id=u.payment_request_id and u.is_active=1 and u.email=t.email;
   select * from temp_pending_notification where email <>'' or mobile <>'';
   
   Drop TEMPORARY  TABLE  IF EXISTS temp_pending_notification;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_settopbox_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_settopbox_list`(_merchant_id varchar(10),_where longtext,_order longtext,_limit longtext)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_view_settopbox;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_view_settopbox (
    `id` varchar(10) NOT NULL ,
    `customer_name` varchar(250) NOT NULL,
    `customer_code` varchar(45) NOT NULL,
    `name` varchar(100) NOT NULL,
    `narrative` varchar(250) NULL,
    `cost` decimal(11,2) NULL default 0,
    `status` INT NOT NULL,
   	`updated_date` datetime null,
    `expiry_date` date null,
    `action` varchar(10) null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
   
insert into temp_merchant_view_settopbox(id,updated_date,customer_name,customer_code,name,narrative,cost,status,expiry_date)
select s.id,s.updated_date,concat(c.first_name,' ',c.last_name) as customer_name,c.customer_code,s.name,s.narrative,s.cost,s.status,s.expiry_date 
from customer_service s inner join customer c on c.customer_id=s.customer_id where  s.merchant_id=_merchant_id and s.is_active=1;
    
   SET @where=REPLACE(_where,'~',"'");
   SET @count=0;    
    SET @sql=concat('select count(id) into @count from temp_merchant_view_settopbox ',@where );
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET @sql=concat('select *,@count from temp_merchant_view_settopbox ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_staging_customer_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_staging_customer_list`(_bulk_id INT,_merchant_id varchar(10),_column_name longtext)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_staging_view_customer;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_staging_view_customer (
    `customer_id` varchar(10) NOT NULL ,
    `customer_code` varchar(45) NOT NULL,
    `email` varchar(250) NULL,
    `mobile` varchar(20) NULL,
    `name` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
	`address` varchar(250) NULL,
  	`address2` varchar(250) NULL,
    `country` varchar(75) NULL,
	`city` varchar(50) NULL,
	`state` varchar(50) NULL,
	`zipcode` varchar(50) NULL,
    `password` varchar(20) NULL,
    `gst` varchar(20) NULL,
	`created_date` datetime null,
    `company_name` varchar (250) null,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
   
      
       insert into temp_staging_view_customer(customer_id,customer_code,name,first_name,last_name,email,mobile,address2,address,country,city,state,zipcode,`password`,company_name,`gst`,created_date) 
    select customer_id,customer_code,concat(first_name,' ',last_name),first_name,last_name,email,mobile,address2,address,country,city,state,zipcode,`password`,company_name,`gst_number`,created_date from staging_customer where bulk_id=_bulk_id and merchant_id=_merchant_id;
    
    
    SET @last='zipcode';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_staging_view_customer ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_staging_view_customer t , staging_customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
    

select * from temp_staging_view_customer;
     
     

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_staging_invoice_breckup` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_staging_invoice_breckup`(_staging_payment_request_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_breckup;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_breckup (
    `id` int NOT NULL AUTO_INCREMENT ,
    `invoice_id` varchar(10) NULL,
    `column_id` varchar(10)  NULL,
    `value` varchar(500) NULL,
    `is_delete_allow` INT NULL,
    `save_table_name` varchar(10)  NULL,
    `column_name` varchar(500) NULL,
    `position` varchar(5) NULL,
    `column_type` varchar(5) NULL,
    `column_datatype` varchar(10) NULL,
    `is_mandatory` INT NULL default 0,
    `function_id` INT NULL default 0,
    `column_position` INT NULL default 0,
    `column_group_id` varchar(10)  NULL,
    `template_id` varchar(10)  NULL,

    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    
   insert into temp_invoice_breckup(invoice_id,column_id,value,is_delete_allow,save_table_name,column_name,position,column_type,column_datatype,is_mandatory,column_position,column_group_id,template_id,function_id)  
   SELECT icv.invoice_id,icv.column_id,icv.value,icm.is_delete_allow,icm.save_table_name,icm.column_name,icm.position,icm.column_type,icm.column_datatype,icm.is_mandatory,icm.column_position,icm.column_group_id,
	icm.template_id,function_id from staging_invoice_values as icv right outer join invoice_column_metadata as icm on icv.column_id = icm.column_id
    where icv.payment_request_id=_staging_payment_request_id and icm.save_table_name<>'request'  and icv.is_active=1 order by icm.sort_order,icv.invoice_id;
    
    select template_id into @template_id from temp_invoice_breckup limit 1;
    
    
    insert into temp_invoice_breckup(column_type,column_id,column_position,position,column_name,column_datatype,function_id) 
    SELECT 'R',column_id,column_position,position,column_name,column_datatype,function_id
	 from invoice_column_metadata  where template_id=@template_id and is_active=1 and save_table_name='request'  order by sort_order,column_id;
    
select * from temp_invoice_breckup;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_staging_invoice_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_staging_invoice_details`(_userid varchar(10), _paymentrequest_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
	show errors;
		BEGIN
		END; 

SET @payment_req=_paymentrequest_id;
set @patron_id='';
set @merchant_id='';
SET @patron_user_id='';
SET @domain='';
SET @display_url='';
SET @pg_count=0;
SET @main_company_name='';
SET @customer_id=0;
SELECT 
    template_id,
    invoice_number,
    merchant_id,
    invoice_total,
    grand_total,
    advance_received,
    swipez_total,
    DATE_FORMAT(bill_date, '%d %b %Y'),
    previous_due,
    basic_amount,
    tax_amount,
    narrative,
    absolute_cost,
    late_payment_fee,
    expiry_date,
    DATE_FORMAT(due_date, '%d %b %Y'),
    customer_id,
    merchant_id,
    user_id,
    billing_cycle_id,
    payment_request_type,
    payment_request_status,
    payment_request_status,
    converted_request_id,
    invoice_type,
    template_id,
    notify_patron,
    has_custom_reminder,
    short_url,
    franchise_id,
    vendor_id,
    autocollect_plan_id,
    paid_amount,
    plugin_value,
    gst_number,
    billing_profile_id,
    currency,
   
    
    generate_estimate_invoice
INTO @template_id , @invoice_number ,@merchant_id, @invoice_total , @grand_total , @advance , @swipez_total , @bill_date , @previous_due , @basic_amount , @tax_amount ,  @narrative , @absolute_cost , @late_fee , @expiry_date , @due_date , @customer_id , @merchant_id , @merchant_user_id , @cycle_id ,@payment_request_type, @status , @payment_request_status,@converted_request_id,@invoice_type , @template_id , @notify_patron ,   @has_custom_reminder , @short_url , @franchise_id , @vendor_id,  @autocollect_plan_id,@paid_amount,@plugin_value,@gst_number,@billing_profile_id,@currency, @generate_estimate_invoice FROM
    staging_payment_request
WHERE
    payment_request_id = @payment_req  ;

if(@customer_id>0)then

    select customer_code, concat(first_name,' ',last_name),concat(address,address2),city,zipcode,state,user_id,email,mobile,company_name,country into @customer_code,@customer_name,@customer_address,@customer_city
    ,@customer_zip,@customer_state,@customer_user_id,@customer_email,@customer_mobile,@customer_company_name,@customer_country from customer where customer_id=@customer_id;
    
 
SELECT 
    image_path,
    banner_path,
    template_name,
    template_type,
    particular_column,
    particular_total,
    tax_total,
    properties,
    design_name,
     tnc,
        design_color,
        footer_note
INTO @image_path , @banner_path , @template_name , @template_type,@particular_column,@particular_total,@tax_total,@properties,@design_name,@tnc,@design_color,@footer_note  FROM
    invoice_template
WHERE
    template_id = @template_id;
 
SELECT 
    display_url,
    merchant_domain,
    merchant_type,
    is_legal_complete,
    disable_online_payment,
    merchant_website
INTO @display_url , @merchant_domain , @merchant_type , @legal_complete , @disable_online_payment , @merchant_website  FROM
    merchant
WHERE
    merchant_id = @merchant_id;
 
SELECT 
    statement_enable,
    show_ad,
    promotion_id,
    from_email,
    settlements_to_franchise,
    sms_gateway,
    sms_gateway_type,
    sms_name
INTO @statement_enable , @show_ad , @promotion_id , @from_email , @settlements_to_franchise , @sms_gateway , @sms_gateway_type , @sms_name FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
 
 
SELECT 
    cycle_name
INTO @cycle_name FROM
    billing_cycle_detail
WHERE
    billing_cycle_id = @cycle_id;
 
SELECT 
    config_value
INTO @domain FROM
    config
WHERE
    config_type = 'merchant_domain'
        AND config_key = @merchant_domain;
 
if(@disable_online_payment=0 and @legal_complete=1)then 
SELECT 
    COUNT(fee_detail_id)
INTO @pg_count FROM
    merchant_fee_detail
WHERE
    merchant_id = @merchant_id and franchise_id=0
        AND is_active = 1;
 end if;
 
   if(@display_name<>'')then
 SET @company_name=@display_name;
 end if;
 
if(@sms_name is NULL or @sms_name='')then
 SET @sms_name=@company_name;
 end if;
  
 
 if(@pg_count<1 or @disable_online_payment=1 or @legal_complete=0)then
 SET @legal_complete=0;
 end if;
 
 if(@billing_profile_id>0)then
 select company_name,company_name,address,business_email,business_contact,state into @display_name,@company_name,@merchant_address,@business_email,@business_contact,@merchant_state
 from merchant_billing_profile where id = @billing_profile_id;
 else
  select company_name,company_name,address,business_email,business_contact,state into @display_name,@company_name,@merchant_address,@business_email,@business_contact,@merchant_state
 from merchant_billing_profile where merchant_id=@merchant_id and is_default = 1 limit 1;
 end if;
 
 
SET @is_expire= 0;
  if(DATE_FORMAT(NOW(),'%Y-%m-%d') >= @expiry_date) then
 SET @is_expire= 1;
 end if;

 if(DATE_FORMAT(NOW(),'%d %b %Y') > @due_date and @absolute_cost>0) then
 SET @absolute_cost= @absolute_cost + @late_fee;
 end if;
 
 select icon into @currency_icon from currency where `code`=@currency;


 
SET @message= 'success';
SELECT 
    _paymentrequest_id AS 'payment_request_id',
    @is_expire AS 'is_expire',
    @invoice_number AS 'invoice_number',
    @expiry_date AS 'expiry_date',
    @notify_patron AS 'notify_patron',
    @domain AS 'merchant_domain',
    @display_url AS 'display_url',
    @merchant_id AS 'merchant_id',
    @merchant_user_id AS 'merchant_user_id',
    @particular_column as 'particular_column',
    @particular_total as 'particular_total',
    @tax_total as 'tax_total',
    @plugin_value as 'plugin_value',
    @status AS 'status',
    @narrative AS 'narrative',
    @absolute_cost AS 'absolute_cost',
    @advance AS 'advance',
    @previous_due AS 'previous_due',
    @cycle_name AS 'cycle_name',
    @grand_total AS 'grand_total',
    @due_date AS 'due_date',
    @Previous_dues AS 'Previous_dues',
    @statement_enable AS 'statement_enable',
    @show_ad AS 'show_ad',
    @bill_date AS 'bill_date',
    @basic_amount AS 'basic_amount',
    @tax_amount AS 'tax_amount',
    @swipez_total AS 'swipez_total',
    @invoice_total AS 'invoice_total',
    @message AS 'message',
    @company_name AS 'company_name',
    @merchant_type AS 'merchant_type',
    @legal_complete AS 'legal_complete',
    @image_path AS 'image_path',
    @banner_path AS 'banner_path',
    @template_name AS 'template_name',
    @template_type AS 'template_type',
    @merchant_address AS 'merchant_address',
    @business_email AS 'business_email',
    @business_contact AS 'business_contact',
    @merchant_state as 'merchant_state',
    @late_fee AS 'late_fee',
    @payment_request_status AS 'payment_request_status',
    @invoice_type AS 'invoice_type',
    @template_id AS 'template_id',
    @coupon_id AS 'coupon_id',
    @is_coupon AS 'is_coupon',
    @promotion_id AS 'promotion_id',
    @customer_id AS 'customer_id',
    @customer_code AS 'customer_code',
    @customer_name AS 'customer_name',
    @customer_address AS 'customer_address',
    @customer_city AS 'customer_city',
    @customer_zip AS 'customer_zip',
    @customer_state AS 'customer_state',
    @customer_email AS 'customer_email',
    @customer_mobile AS 'customer_mobile',
    @customer_user_id AS 'customer_user_id',
    @customer_country AS 'customer_country',
    @custom_subject AS 'custom_subject',
    @custom_sms AS 'custom_sms',
    @has_custom_reminder AS 'has_custom_reminder',
    @covering_id AS 'covering_id',
    @franchise_id AS 'franchise_id',
    @vendor_id as 'vendor_id',
    @is_franchise AS 'is_franchise',
    @has_vendor as 'has_vendor',
    @merchant_website AS 'merchant_website',
    @gst_number AS 'gst_number',
    @pan AS 'pan',
    @tan AS 'tan',
    @cin_no AS 'cin_no',
    @registration_number AS 'registration_number',
    @gst_number AS 'gst_number',
    @from_email AS 'from_email',
    @settlements_to_franchise AS 'pg_to_franchise',
    @is_prepaid AS 'is_prepaid',
    @has_acknowledgement AS 'has_acknowledgement',
    @main_company_name AS 'main_company_name',
    @sms_gateway AS 'sms_gateway',
    @sms_gateway_type AS 'sms_gateway_type',
    @sms_name AS 'sms_name',
    @autocollect_plan_id as 'autocollect_plan_id',
    @paid_amount as 'paid_amount',
    @converted_request_id AS 'converted_request_id',
    @short_url AS 'short_url',
    @billing_profile_id as 'billing_profile_id',
    @payment_request_type as 'payment_request_type',
    @properties as 'properties',
    @currency as 'currency',
    @generate_estimate_invoice as 'generate_estimate_invoice',
    @customer_company_name as 'customer_company_name',
    @message as 'message',
    @tnc as 'tnc',
    @design_name as 'design_name',
    @design_color as 'design_color',
    @footer_note as 'footer_note',
      @currency_icon as 'currency_icon';
else
SET @message= 'success';
select @message as 'message';
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_staging_viewrequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_staging_viewrequest`(_bulk_id int,_userid varchar(10),_where longtext,_order longtext,_limit longtext)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS import_view_request;

CREATE TEMPORARY TABLE IF NOT EXISTS import_view_request (
	`id` INT not null auto_increment,
    `payment_request_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `customer_id` INT NOT NULL,
    `customer_code` varchar(45) NULL,
    `billing_cycle_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `bill_date` date not null,
    `due_date` DATETIME not null,
    `name` varchar (100) null default '',
    `status` varchar(250) null,
    `billing_cycle_name` varchar(40) null,
     `count` int null,
     `currency` char(3) null,
	`currency_icon` nvarchar(5) null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
              insert into import_view_request(payment_request_id,user_id,customer_id,billing_cycle_id,
    absolute_cost,payment_request_status,bill_date,due_date,currency) 
    select payment_request_id,user_id,customer_id,billing_cycle_id,absolute_cost,
    payment_request_status,bill_date,due_date,currency from staging_payment_request where bulk_id=_bulk_id and user_id=_userid  and payment_request_status in (0,4) and is_active=1;
    
    update import_view_request r , customer u  set r.name = concat(u.first_name," ",u.last_name),r.customer_code=u.customer_code where r.customer_id=u.customer_id;
    
    update import_view_request r , config c  set r.status = c.config_value where r.payment_request_status=c.config_key and c.config_type='payment_request_status';
    
    update import_view_request r , billing_cycle_detail b  set r.billing_cycle_name = b.cycle_name where r.billing_cycle_id=b.billing_cycle_id ;
   
          update import_view_request t,currency c set t.currency_icon=c.icon where c.code=t.currency;

SET @where=REPLACE(_where,'~',"'");

SET @count=0;    
    SET @sql=concat('select count(id) into @count from import_view_request ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count from import_view_request ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_subscription_viewlist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_subscription_viewlist`(_merchant_id char(10),_where longtext,_order longtext,_limit longtext)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
 		BEGIN
        show errors;
		END;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
Drop TEMPORARY  TABLE  IF EXISTS temp_merchant_subscription_viewlist;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_merchant_subscription_viewlist (
	`subscription_id` int(11) not null,
    `payment_request_id` varchar(10) NOT NULL,
    `customer_id` int NULL,
    `customer_code` varchar(45)  NULL,
    `payment_request_status` int null,
    `invoice_type` tinyint(1) null default 1,
    `start_date` datetime not null,
    `due_date` DATETIME not null,
    `name` varchar (100) null,
    `mode` tinyint(1) null,
    `repeat_every` int(11) null,
    `repeat_on` int(11) null,
    `last_sent_date` date null,
    `end_mode` int(11) null,
    `occurrences` int (11) null,
    `end_date` date null,
    `display_text` varchar(100) null,
    `company_name` varchar (250) null,
    `next_bill_date` date null,
    `created_date` DATETIME null,
    PRIMARY KEY (`subscription_id`),
    INDEX `customer_idx` (`customer_id` ASC),
    INDEX `payment_request_idx` (`payment_request_id` ASC)) ENGINE=MEMORY;

	insert into temp_merchant_subscription_viewlist(
    subscription_id,payment_request_id,start_date,due_date,mode,
    repeat_every,repeat_on,last_sent_date,end_mode,occurrences,end_date,display_text,next_bill_date,created_date
    )
	select subscription_id,payment_request_id,start_date,due_date,mode,
    repeat_every,repeat_on,last_sent_date,end_mode,occurrences,end_date,display_text,next_bill_date,created_date from subscription where merchant_id=_merchant_id and is_active=1;

UPDATE temp_merchant_subscription_viewlist r,
    payment_request p
SET
    r.invoice_type = p.invoice_type,
    r.payment_request_status = p.payment_request_status,
    r.customer_id = p.customer_id
WHERE
    r.payment_request_id = p.payment_request_id;

UPDATE temp_merchant_subscription_viewlist r,
    customer u
SET
    r.name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_code = u.customer_code,
    r.company_name = u.company_name
WHERE
    r.customer_id = u.customer_id;

SET @where=REPLACE(_where,'~',"'");
	SET @count=0;
    SET @sql=concat('select count(subscription_id) into @count from temp_merchant_subscription_viewlist ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET @fcount=0;
    SET @sql=concat('select count(subscription_id) into @fcount from temp_merchant_subscription_viewlist ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_merchant_subscription_viewlist ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_subscription_viewrequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_subscription_viewrequest`(_request_id varchar(10),_merchant_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_subscription_request;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_subscription_request (
    `payment_request_id` varchar(10) NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `customer_id` int NOT NULL,
    `customer_code` varchar(45)  NULL,
    `invoice_type` INT  NULL default 1,
    `invoice_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 0,
    `billing_cycle_id` varchar(10) NOT NULL,
	`converted_request_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `paid_amount` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `send_date` datetime not null,
    `due_date` DATETIME not null,
    `name` varchar (100) null,
    `status` varchar(250) null,
    `billing_cycle_name` varchar(40) null,
	`count` int null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
              insert into temp_subscription_request(payment_request_id,user_id,customer_id,invoice_type,billing_cycle_id,converted_request_id,
    absolute_cost,paid_amount,payment_request_status,send_date,due_date,invoice_number) 
    select payment_request_id,user_id,customer_id,invoice_type,billing_cycle_id,converted_request_id,absolute_cost,paid_amount,
    payment_request_status,created_date,due_date,invoice_number from payment_request where merchant_id=_merchant_id and parent_request_id=_request_id and payment_request_type=5 and is_active=1 and payment_request_status <> 3;
    
    update temp_subscription_request r , customer u  set r.name = concat(u.first_name," ",u.last_name),r.customer_code=u.customer_code where r.customer_id=u.customer_id;
    
    update temp_subscription_request r , config c  set r.status = c.config_value where r.payment_request_status=c.config_key and c.config_type='payment_request_status';

    UPDATE temp_subscription_request r,payment_request c SET r.invoice_number = c.estimate_number WHERE r.invoice_type = 2 AND c.payment_request_id = r.payment_request_id; 
	
    UPDATE temp_subscription_request set payment_request_status=8 where payment_request_status in(0,4,5) and due_date < current_date(); 
    
    select count(invoice_number) into @inv_count from temp_subscription_request where invoice_number<>'';
    if(@inv_count>0)then
		update temp_subscription_request set display_invoice_no = 1;
    end if;

  
    select * from temp_subscription_request order by payment_request_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_sub_userlist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_sub_userlist`(_userid varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_sub_userlist;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_sub_userlist (
    `user_id` varchar(10) NOT NULL ,
    `name` varchar(100) NOT NULL,
    `email` varchar(250) NOT NULL,
    `user_status` varchar(10) NOT NULL,
    `role_id` int not null default 0,
    `role` varchar(50) Null,
    `config_value` varchar(100) NULL,
    PRIMARY KEY (`user_id`)) ENGINE=MEMORY;


select group_id into @group_id from user where user_id=_userid and user_group_type=1;

insert temp_sub_userlist(`user_id`,`name`,`email`,`user_status`) select user_id,concat(first_name,' ',last_name),email_id,user_status from user where group_id=@group_id and user_group_type=2  and user_status in (19,20);

update temp_sub_userlist t , user_privileges p set t.role_id = p.role_id where t.user_id=p.user_id;

update temp_sub_userlist t , roles r set t.role = r.name where t.role_id=r.role_id;
update temp_sub_userlist t , config r set t.config_value = r.config_value where t.user_status=r.config_key and r.config_type='user_status';


select * from temp_sub_userlist;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_transaction_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_transaction_status`(_merchant_id varchar(10),_user_id varchar(10),_transaction_id varchar(20),_type varchar(20))
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
END; 

SET @settlement_date ='';
SET @name='';
SET @udf1='';
SET @udf2='';
SET @udf3='';
SET @udf4='';
SET @udf5='';
if(_type=1) then
    select xway_transaction_id,reference_no,xway_transaction_status,amount,name,email,phone,address,city,state,postal_code,udf1,udf2,udf3,udf4,udf5,created_date,payment_Mode into 
    @transaction_id,@reference_no,@status,@amount,@name,@email,@phone,@address,@city,@state,@postal_code,@udf1,@udf2,@udf3,@udf4,@udf5,@date,@payment_Mode from
     xway_transaction where reference_no=_transaction_id and merchant_id=_merchant_id order by created_date desc limit 1;
elseif(_type=2) then
    SET @transaction_id =_transaction_id;
    select xway_transaction_id,reference_no,payment_mode,pg_ref_no1,xway_transaction_status,amount,name,email,phone,address,city,state,postal_code,udf1,udf2,udf3,udf4,udf5,created_date,payment_Mode into 
    @transaction_id,@reference_no,@payment_Mode,@ref_no,@status,@amount,@name,@email,@phone,@address,@city,@state,@postal_code,@udf1,@udf2,@udf3,@udf4,@udf5,@date,@payment_Mode from
     xway_transaction where xway_transaction_id=_transaction_id and merchant_id=_merchant_id order by created_date desc limit 1;
else
    
    select payment_request_status,customer_id into @payment_request_status,@customer_id  from payment_request where payment_request_id=_transaction_id; 

    if(@payment_request_status=1)then
        select pay_transaction_id,pg_ref_no,payment_transaction_status,amount,created_date,payment_mode into 
	@transaction_id,@reference_no,@status,@amount,@date,@payment_Mode from
	 payment_transaction where pay_transaction_id=@transaction_id and merchant_id=_merchant_id;
    elseif(@payment_request_status=2)then
        select offline_response_id,bank_transaction_no,offline_response_type,bank_name,cheque_no,cash_paid_to,1,amount,customer_id,patron_user_id,created_date into 
	@transaction_id,@bank_transaction,@offline_type,@bank_name,@cheque_no,@cash_paid_to,@status,@amount,@customer_id,@patron_user_id,@date from
	 offline_response where offline_response_id=@transaction_id and merchant_id=_merchant_id;
         
        if(@offline_type=1)then
           SET @payment_mode='NEFT';
           SET @reference_no=@bank_transaction;
        elseif(@offline_type=2)then
           SET @payment_mode='Cheque';
           SET @reference_no=@cheque_no;
        elseif(@offline_type=3)then
           SET @payment_mode='Cash';
           SET @reference_no=@cash_paid_to;
        elseif(@offline_type=4)then
           SET @payment_mode='Online';
           SET @reference_no=@bank_transaction;
        end if;
    end if;
    
    select concat(first_name,' ',last_name),address,city,state,zipcode,email,mobile into @name,@address,@city,@state,@postal_code,@email,@phone from customer where customer_id=@customer_id and merchant_id=_merchant_id ;
    
end if;

if(@status=0)then
SET @status='initiated';
elseif(@status=1) then
SET @status='success';
elseif(@status=4) then
SET @status='failed';
end if;

select @transaction_id as 'transaction_id',@reference_no as 'reference_no',@status as 'status',@date as 'date',@ref_no as 'bank_ref_no',@payment_Mode as 'mode',@amount as 'amount',@name as 'billing_name',@email as 'billing_email',@phone as 'billing_mobile',@address as 'billing_address'
,@city as 'billing_city',@state as 'billing_state',@postal_code as 'billing_postal_code',@udf1 as 'udf1',@udf2 as 'udf2',@udf3 as 'udf3',@udf4 as 'udf4',@udf5 as 'udf5';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GrouploginCheck` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GrouploginCheck`(_email varchar(254),_password varchar(60),_group_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @user_id='';
SET @status=0;
SET @name='';
SET @firstName='';
SET @email_id='';
SET @loginattempt=0;
SET @merchant_type=0;
SET @patron_has_payreq=0;
SET @merchant_status=0;
SET @merchant_id='';
SET @bulk_upload_limit='';
SET @display_url='';
SET @merchant_domain_name='';
SET @company_name='';
SET @view_roles='';
SET @update_roles='';
SET @sub_user_id='';
SET @directpay_enable=0;
SET @franchise_id=0;
SET @ticket_enable=0;

SELECT 
    u.user_id,
    u.user_status,
    CONCAT(first_name, ' ', last_name) AS name,
    first_name,
    email_id,
    franchise_id,
    group_id
INTO @user_id , @status , @name , @firstName , @email_id ,@franchise_id, @group_id FROM
    `user` u
        INNER JOIN
    user_cred c ON u.user_id = c.user_id
WHERE
    u.email_id = _email
        AND `user_status` IN (2 , 12, 13, 14, 15, 16, 20, 19)
        AND c.password = _password
        AND u.group_id = _group_id;
            
if(@status=2 or (@status>11 and @status<17) or @status=20)then
	update user_cred set login_attempt=0, last_login=CURRENT_TIMESTAMP() where user_id=@user_id;
	SET @isValid=1;
	if(@status=2) then
		select patron_id into @patron_id from payment_request where patron_id=@user_id limit 1;
			if(@patron_id!='') then
				SET @patron_has_payreq=1;
			end if;
	else
		if(@status=20)then
        SET @sub_user_id=@user_id;
SELECT 
    view_controllers,update_controllers
INTO @view_roles,@update_roles FROM
    roles
        INNER JOIN
    user_privileges ON user_privileges.role_id = roles.role_id
WHERE
    user_privileges.user_id = @user_id
LIMIT 1;
			SELECT 
    user_id
INTO @user_id FROM
    user
WHERE
    group_id = @group_id
        AND user_group_type = 1;
		end if;
		SELECT 
    merchant_type,
    company_name,
    merchant_domain,
    display_url,
    merchant_status,
    merchant_id
INTO @merchant_type , @company_name , @merchant_domain , @display_url , @merchant_status , @merchant_id FROM
    merchant
WHERE
    user_id = @user_id
LIMIT 1;
SELECT 
    xway_enable, directpay_enable,ticket_enable
INTO @xway_enable , @directpay_enable,@ticket_enable FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
		SELECT 
    config_value
INTO @merchant_domain_name FROM
    config
WHERE
    config_key = @merchant_domain
        AND config_type = 'merchant_domain';
	end if;
	else
		select login_attempt,user.user_id into @attempt,@userId from user_cred inner join user on user.user_id=user_cred.user_id where user.email_id=_email and group_id=_group_id;
		UPDATE user_cred 
SET 
    login_attempt = login_attempt + 1
WHERE
    user_id = @userId;
		SET @loginattempt=@attempt+1;
		if(@loginattempt>9) then
			update user set prev_status=`user_status`,`user_status`=18 where user_id=@userId and `user_status`<>18;
		SET @status=18;
		end if;
		SET @isValid=0;
end if;

if(@directpay_enable=1)then
SET @xway_enable=1;
end if;

SELECT 
    @merchant_domain_name AS 'merchant_domain',
    @company_name AS 'company_name',
    @display_url AS 'display_url',
    @user_id AS 'user_id',
    @status AS 'status',
    @name AS 'name',
    @firstName AS 'firstName',
    @email_id AS 'email_id',
    @loginattempt AS 'loginattempt',
    @isValid AS 'isValid',
    @merchant_id AS 'merchant_id',
    @merchant_type AS 'merchant_type',
    @merchant_status AS 'merchant_status',
    @patron_has_payreq AS 'patron_has_payreq',
	@view_roles AS 'view_roles',
    @update_roles AS 'update_roles',
    @sub_user_id AS 'sub_user_id',
    @xway_enable AS 'xway_enable',
    @franchise_id as 'franchise_id',
	@ticket_enable as 'ticket_enable';

commit;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_invoicevalues` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_invoicevalues`(_merchant_id char(10),_user_id char(10),_customer_id INT,_invoice_number varchar(45),_template_id varchar(10),invoice_values LONGTEXT,
_column_id LONGTEXT,_bill_date date,_due_date date,_bill_cycle_name varchar(100),_narrative nvarchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),
_late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_notify_patron INT,_payment_request_status INT,_franchise_id INT,_vendor_id INT
,_expiry_date date,_created_by varchar(10),_invoice_type INT,_type INT,_request_type INT,_custom_reminder tinyint(1),_plugin_value longtext,_billing_profile INT,_generate_estimate_invoice tinyint(1),_parent_request_id char(10),_currency char(3),_einvoice_type varchar(20),_product_taxation_type INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        SET @message='failed';
        show errors;
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @bc_id='';
SET @separator = '~';
SET @req_id='';
set @pay_req_status=_payment_request_status;
SET @separatorLength = CHAR_LENGTH(@separator);
SET @convenience_fee=0;
SET @parent_request_id=_parent_request_id;
SET @estimate_number='';
SET @autoval=0;
SET @tax = 0;

if(_product_taxation_type = 2)then
SET @invoice_total=_amount +_previous_dues -_last_payment - _adjustment;
else
SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
SET @tax = _tax;
end if;

SET @swipez_total=@invoice_total;
SET @grand_total=@invoice_total;


if(_billing_profile>0)then
	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where id=_billing_profile;
else
	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where merchant_id=_merchant_id and is_default=1;
end if;

if(_billing_profile>0)then
	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where id=_billing_profile;
else
	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where merchant_id=_merchant_id and is_default=1;
end if;

if(_request_type<>2)then
SET _request_type=1;
end if;

if(_type<>4 and _payment_request_status!=11)then
	SET @numstring=SUBSTRING(_invoice_number,1,16);
	if(@numstring='System generated')then
        if(_invoice_type<>2)then
			if(@autoval=0)then
            SET @autoval=SUBSTRING(_invoice_number,17);
            end if;
            SELECT GENERATE_INVOICE_NUMBER(@autoval) INTO @invoice_number;
            SET invoice_values=REPLACE(invoice_values, _invoice_number, @invoice_number);
            SET _invoice_number= @invoice_number;
        else
            SET @invoice_number='';
            SET invoice_values=REPLACE(invoice_values, _invoice_number, @invoice_number);
            SET _invoice_number= @invoice_number;
        end if;
	end if;
end if;

if(_invoice_type=2)then
SELECT generate_estimate_number(_merchant_id) INTO @estimate_number;
end if;

select get_surcharge_amount(_merchant_id ,@invoice_total,0) into @convenience_fee;

SET @grand_total=@grand_total-_advance;

SELECT `billing_cycle_id` INTO @bc_id FROM `billing_cycle_detail` WHERE `_user_id` = _user_id AND `cycle_name` = _bill_cycle_name LIMIT 1;

if(@bc_id='') then
select generate_sequence('Billing_cycle_id') into @bc_id;
INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`,  `created_by`, `created_date`, 
`last_update_by`) VALUES (@bc_id,_user_id,_bill_cycle_name,_created_by,CURRENT_TIMESTAMP(),_created_by);
end if;


SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @req_id;

if(_type=4)then
SET @parent_request_id=0;
end if;

if(_currency='')then
SET _currency='INR';
end if;

INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`invoice_type`,`payment_request_type`,`template_id`,`invoice_number`,
`estimate_number`,`previous_due`,`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`grand_total`,`late_payment_fee`,
`advance_received`,`payment_request_status`,`bill_date`,`due_date`,`narrative`,`parent_request_id`,`billing_cycle_id`,`notification_sent`,`notify_patron`,
`franchise_id`,`vendor_id`,`expiry_date`,`has_custom_reminder`,`plugin_value`,`gst_number`,`billing_profile_id`,`request_type`,`generate_estimate_invoice`,`currency`,
`einvoice_type`,`created_by`,`created_date`,`last_update_by`,`product_taxation_type` )
VALUES(@req_id,_user_id,_merchant_id,_customer_id,_invoice_type,_type,_template_id,_invoice_number,@estimate_number,_previous_dues,@grand_total,_amount,
@tax,@invoice_total,@swipez_total,@convenience_fee,@grand_total,_late_fee,_advance,@pay_req_status,_bill_date,_due_date,_narrative,@parent_request_id,
@bc_id,0,_notify_patron,_franchise_id,_vendor_id,_expiry_date,_custom_reminder,_plugin_value,@gst_number,_billing_profile,_request_type,_generate_estimate_invoice,_currency,
_einvoice_type,_created_by,CURRENT_TIMESTAMP(),_created_by,_product_taxation_type );

WHILE _column_id != '' > 0 DO
    SET @column_id  = SUBSTRING_INDEX(_column_id, @separator, 1);
    SET @invoice_values  = SUBSTRING_INDEX(invoice_values, @separator, 1);
 
INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (@req_id,@column_id,@invoice_values,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());
    SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id) + @separatorLength + 1);
    SET invoice_values = SUBSTRING(invoice_values, CHAR_LENGTH(@invoice_values) + @separatorLength + 1);
END WHILE;

if(_type<>4)then
update customer set balance = balance + @grand_total where customer_id=_customer_id;

INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
VALUES(_customer_id,concat('Invoice for bill date ',_bill_date),@grand_total,1,@req_id,'DEBIT',_created_by,CURRENT_TIMESTAMP(),_created_by);
end if;

SET @duecolumn_id=0;

select column_id into @duecolumn_id from invoice_column_metadata where template_id=_template_id and function_id=4 and is_active=1; 
if(@duecolumn_id>0)then
SET @due_mode='';
select param into @due_mode from column_function_mapping where column_id=@duecolumn_id and function_id=4 and is_active=1;
if(@due_mode='auto_calculate')then
update payment_request p,contact_ledger l set p.payment_request_status=8, p.expiry_date=SUBDATE(CURDATE(),1),l.is_active=0 where p.merchant_id=_merchant_id and p.customer_id=_customer_id and  p.payment_request_status in(0,5,4) and p.payment_request_id<>@req_id and p.payment_request_id=l.reference_no and p.customer_id=l.customer_id and l.type=1;
select sum(amount) into @debit from contact_ledger where customer_id=_customer_id and ledger_type='DEBIT' and is_active=1;
select sum(amount) into @credit from contact_ledger where customer_id=_customer_id and ledger_type='CREDIT' and is_active=1;
if(@credit>0)then
update customer set balance=@debit-@credit where customer_id=_customer_id;
else
update customer set balance=@debit where customer_id=_customer_id;
end if;
end if;
end if;

commit;
SET @message = 'success';

SELECT 
    _notify_patron AS 'notify_patron',
    @req_id AS 'request_id',
    @message AS 'message';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_payment_response` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_payment_response`(_type varchar(20),_transaction_id varchar(10),_payment_id varchar(40),_pg_transaction_id varchar(40),_amount decimal(11,2),_payment_method INT,_payment_mode varchar(45),_message varchar(250),_status varchar(20),_user_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
	show errors;
    ROLLBACK;
		END; 
START TRANSACTION;

SET @plugin_value='';
SET @image='';
SET @patron_mobile='';
SET @pay_transaction_id=_transaction_id;
SET @payment_id=_payment_id;
SET @transaction_id=_pg_transaction_id;
SET @message=_message;
SET @payment_method=_payment_method;
SET @template_id='';
SET @payment_status=3;
SET @invoice_type=1;
SET @sms_gateway=1;
SET @from_email='';
SET @sms_gateway_type=1;
SET @sms_name=NULL;
SET @short_url='';
SET @franchise_id=0;
SET @main_company_name='';
SET @webhook_id=0;
SET @event_name='';
SET @unit_type='';
SET @type='';
SET @quantity =0;
SET @profile_id=0;
SET @request_type=1;
SET @sms_available=0;
if(_status='success') then
set @status=1;
SET @response_code=0;
else
set @status=4;
SET @response_code=1;
end if;	

if(_type='package') then
	update package_transaction set payment_transaction_status =@status,pg_ref_no=@payment_id,pg_ref_1=@transaction_id,payment_mode=_payment_mode,payment_info=_message where package_transaction_id=_transaction_id;

	SELECT user_id,merchant_id, package_id, custom_package_id INTO @merchant_user_id,@merchant_id,@package_id,@custom_package_id FROM
	package_transaction WHERE package_transaction_id = _transaction_id;
    
    select concat(first_name,' ',last_name),email_id,mobile_no into @patron_name,@patron_email,@patron_mobile from user where user_id=@merchant_user_id;

	if(_status='success') then
		if(@custom_package_id>0)then
			select 1,package_cost,12,0,0,0,free_sms,invoice,invoice,invoice,event_booking,0,0,0
			into @package_type,@package_cost,@duration,@pg_integration,@site_builder,@brand_keyword,@free_sms,@individual_invoice,
			@bulk_invoice,@total_invoices,@event_booking,@merchant_role,@coupon,@supplier from custom_package where package_id=@custom_package_id;
			UPDATE custom_package SET status = 1 WHERE package_id = @custom_package_id;
			SET @package_id=12;
		else
			select `type`,package_cost,duration,pg_integration,site_builder,brand_keyword,free_sms,individual_invoice,bulk_invoice,total_invoices,merchant_role,coupon,supplier,0
			into @package_type,@package_cost,@duration,@pg_integration,@site_builder,@brand_keyword,@free_sms,@individual_invoice,@bulk_invoice,@total_invoices,@merchant_role,@coupon,@supplier,@event_booking from package where package_id=@package_id;
		end if;

		if(@package_type=1)then
			select end_date into @package_end_date from account WHERE merchant_id = @merchant_id AND is_active = 1 and amount_paid>0 and end_date>curdate();
            if(@package_end_date is not null)then
            SET @end_date=DATE_ADD(@package_end_date, INTERVAL @duration MONTH);
            else
            SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);
            end if;
		
			UPDATE account SET is_active = 0 WHERE merchant_id = @merchant_id AND is_active = 1;

			INSERT INTO `account`(`merchant_id`,`package_id`,`custom_package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,`bulk_invoice`,`event_booking`,`free_sms`,
			`merchant_role`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`coupon`,`supplier`,`start_date`,`end_date`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@merchant_id,@package_id,@custom_package_id,_transaction_id,_amount,@individual_invoice,@bulk_invoice,@event_booking,@free_sms,@merchant_role,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@coupon,@supplier
			,NOW(),@end_date,_user_id,CURRENT_TIMESTAMP(),_user_id);


			UPDATE merchant SET merchant_plan = @package_id,package_expiry_date=@end_date WHERE merchant_id = @merchant_id;

			if(@pg_integration=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 0 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,5,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;

			if(@site_builder=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,6,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
				UPDATE merchant_setting 
				SET 
				site_builder = 1
				WHERE
				merchant_id = @merchant_id;
			end if;

			if(@brand_keyword=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 0 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,8,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;

			if(@free_sms>0)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,7,_transaction_id,@free_sms,@free_sms,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
				UPDATE merchant SET merchant_type = 2 WHERE	merchant_id = @merchant_id;
			end if;
		end if;

		if(@package_type=2)then
			SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);
			if(@package_id=7)then
			select base_amount into @base_amount from package_transaction where package_transaction_id=_transaction_id;
			SET @licence_bought=@base_amount/@package_cost;
			UPDATE merchant 
			SET 
			merchant_type = 2
			WHERE
			merchant_id = @merchant_id;
			else
			SET @licence_bought=1;
			end if;
			if(@package_id=6)then
			update merchant_setting set site_builder=1 where merchant_id=@merchant_id;
			end if;
			INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
			`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@package_id,_transaction_id,@licence_bought,@licence_bought,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;
		end if;
else
		SELECT payment_request_id,customer_id,merchant_user_id,merchant_id,payment_request_type,discount,coupon_id,quantity,narrative,deduct_amount,deduct_text,fee_id,is_partial_payment,amount-convenience_fee,fee_id,vendor_id,franchise_id
		into @payment_request_id,@customer_id,@merchant_user_id,@merchant_id,@type,@discount,@coupon_id,@quantity,
		@narrative,@deduct_amount,@deduct_text,@fee_detail_id,@is_partial_payment,@inv_transaction_amount,@fee_id,@vendor_id,@franchise_id from payment_transaction where pay_transaction_id=_transaction_id;
		
		SET @pg_surcharge_enabled=0;

		SELECT pg_surcharge_enabled INTO @pg_surcharge_enabled FROM merchant_fee_detail WHERE fee_detail_id = @fee_detail_id;

		if(@pg_surcharge_enabled=1)then
			update payment_transaction set convenience_fee=_amount-amount,`amount`=_amount where pay_transaction_id=_transaction_id;
		end if;

		if(@type=2)then
			SELECT short_url,franchise_id,event_name,unit_type,template_id into @short_url,@franchise_id,@event_name,@unit_type,@template_id from event_request 
			where event_request_id=@payment_request_id;
			else
			SELECT invoice_number,@invoice_type, short_url, franchise_id, webhook_id,template_id,paid_amount,grand_total,payment_request_status,plugin_value,billing_profile_id,request_type
            INTO @invoice_number ,@invoice_type, @short_url , @franchise_id , @webhook_id,@template_id,@paid_amount,@invgrand_total,@payment_request_status,@plugin_value,@profile_id,@request_type FROM
			payment_request WHERE payment_request_id = @payment_request_id;
		end if;

		if(_status='success') then
			SET @payment_status=2;
            if(@vendor_id=0 and @franchise_id=0)then
			select vendor_id,franchise_id into @vendor_id,@franchise_id from merchant_fee_detail where fee_detail_id=@fee_id;            
            end if;
            
            if(@vendor_id>0 or @franchise_id>0)then
            call split_transaction(_transaction_id,_amount,@vendor_id,@franchise_id,_user_id);
            end if;
            
            if(@coupon_id>0) then
					SET @coupon_availed=1;
                    if(@type=2)then
						select count(coupon_code) into @coupon_availed from event_transaction_detail where transaction_id=@pay_transaction_id and coupon_code=@coupon_id;
                    end if;
					
					UPDATE coupon r SET r.available = r.available - @coupon_availed WHERE r.coupon_id = @coupon_id
					AND r.`limit` <> 0;
              end if;
            
			if(@type=2)then
				SET @link='/merchant/transaction/viewlist/event';

				UPDATE event_transaction_detail SET is_paid = 1 WHERE transaction_id = @pay_transaction_id;
				elseif(@type=3)then
				SET @link='/merchant/transaction/viewlist/bulk';
				elseif(@type=4)then
				SET @link='/merchant/transaction/viewlist/subscription';
				elseif(@type=5)then
				update booking_transaction_detail set is_paid=1 where transaction_id=@pay_transaction_id;
				call update_booking_status(@pay_transaction_id);
				SET @link='/merchant/transaction/viewlist/booking';
				elseif(@type=6)then
				update customer_membership set status=1 where transaction_id=@pay_transaction_id;
				SET @link='/merchant/transaction/viewlist/booking';
				else
				SET @link='/merchant/transaction/viewlist';
				end if;

				
				if(@template_id<>'')then
				select image_path into @image from invoice_template where template_id=@template_id;
				end if;

				INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,
				`created_by`,`created_date`,`last_update_by`,`last_update_date`)
				VALUES(@merchant_user_id,1,@link,CURRENT_TIMESTAMP(),DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH),
				'Payment request(s) have been settled by your patron','',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

				UPDATE customer SET payment_status = @payment_status WHERE customer_id = @customer_id AND payment_status <> 2;

			else
				update customer set payment_status=@payment_status where customer_id=@customer_id and payment_status in(0,1);
			end if;

			SET @suppliers='';
            UPDATE `payment_request` SET `payment_request_status` = @status WHERE  payment_request_id = @payment_request_id and payment_request_status not in (1,2);
            call `update_status`(@payment_request_id,@pay_transaction_id,_amount,@status,@response_code,@payment_id,@transaction_id,_payment_mode,0,@message);
		
			SET @has_ledger=0;
			select count(customer_id) into @has_ledger from contact_ledger where customer_id=@customer_id and reference_no=_transaction_id limit 1;
            if(@has_ledger=0)then
			update customer set balance = balance - _amount where customer_id=@customer_id;
			INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
			`ledger_type`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@customer_id,concat('Payment on ',DATE_FORMAT(NOW(),'%Y-%m-%d')),_amount,2,_transaction_id,'CREDIT',@customer_id,CURRENT_TIMESTAMP(),@customer_id);
			end if;
        
			if(@is_partial_payment=1 and _status='success')then
				SET @paid_amount=@paid_amount+@inv_transaction_amount;

				if(@invgrand_total>@paid_amount)then
				SET @inv_status=7;
				else
				SET @inv_status=1;
				end if;

				update payment_request set payment_request_status=@inv_status,paid_amount=@paid_amount where payment_request_id= @payment_request_id;
			end if;
            
            if(@payment_request_status=7 and _status<>'success')then
				update payment_request set payment_request_status=7 where payment_request_id= @payment_request_id;
            end if;


		SELECT sms_gateway,from_email,sms_gateway_type,sms_name,auto_approve
		INTO @sms_gateway , @from_email , @sms_gateway_type , @sms_name , @auto_approve FROM
		merchant_setting WHERE	merchant_id = @merchant_id;

		SET @pending_change_id='';
		SELECT pending_change_id, change_id INTO @pending_change_id , @change_id FROM pending_change 
		WHERE source_id = @pay_transaction_id AND source_type = 2 AND status = - 1; 

		UPDATE pending_change SET status = 0 WHERE pending_change_id = @pending_change_id;
		UPDATE customer_data_change SET status = 0 WHERE change_id = @change_id;
		UPDATE customer_data_change_detail SET status = 0 WHERE	change_id = @change_id;
		if(@pending_change_id<>'') then
			if(@auto_approve>0) then
				call `auto_aprove_customer_details`(@customer_id,@pay_transaction_id);
			end if;
		end if;

			SELECT customer_code, CONCAT(first_name, ' ', last_name),email,mobile INTO @customer_code , @patron_name,@patron_email,@patron_mobile FROM customer
			WHERE customer_id = @customer_id; 
			SELECT  `logo` INTO @merchant_logo FROM merchant_landing  WHERE merchant_id = @merchant_id;
end if;

	SELECT merchant_domain INTO @merchant_domain FROM merchant WHERE merchant_id = @merchant_id;	
    
 if(@profile_id>0)then
 select company_name,company_name,address,business_email,business_contact into @display_name,@company_name,@merchant_address,@merchant_email_id,@business_contact
 from merchant_billing_profile where id = @billing_profile_id;
 else
  select company_name,company_name,address,business_email,business_contact into @display_name,@company_name,@merchant_address,@merchant_email_id,@business_contact
 from merchant_billing_profile where merchant_id = @merchant_id and is_default=1;
 end if;

	SET @sms_name=@company_name;

	SELECT mobile_no INTO @merchant_mobile_no FROM  `user` WHERE user_id = @merchant_user_id;
	SELECT config_value INTO @payment_Mode FROM config WHERE config_key = @payment_method AND config_type = 'transaction mode'; 

SET @franchise_email_id='';
SET @franchise_mobile_no='';

if(@request_type=2)then
	if(@sms_gateway_type=1)then
		SET @sms_available=1;
	else
		select count(id) into @sms_count from merchant_addon where merchant_id=@merchant_id and package_id=7 and license_available >0 and end_date>curdate();
		if(@sms_count>0)then
			SET @sms_available=1;
		end if;
	end if;
end if;

if(@franchise_id>0)then
	SET @main_company_name=@company_name;
	SET @merchant_email_id_=@merchant_email_id;
	SELECT franchise_name, email_id, mobile
	INTO @company_name , @franchise_email_id , @franchise_mobile_no FROM
	franchise WHERE franchise_id = @franchise_id;
end if;
SET @message='success';
SELECT 
@message AS 'message',
@merchant_domain as 'merchant_domain',
@payment_request_id AS 'payment_request_id',
@invoice_number AS 'invoice_number',
@merchant_user_id AS 'merchant_user_id',
@merchant_id AS 'merchant_id',
@image AS 'image',
@merchant_logo AS 'merchant_logo',
@sms_gateway AS 'sms_gateway',
@company_name AS 'company_name',
@merchant_email_id AS 'merchant_email',
@merchant_mobile_no AS 'mobile_no',
@payment_Mode AS 'payment_mode',
@suppliers AS 'suppliers',
@discount AS 'discount',
@deduct_amount AS 'deduct_amount',
@deduct_text AS 'deduct_text',
@quantity AS 'quantity',
_message AS 'narrative',
@customer_code AS 'customer_code',
@patron_name AS 'patron_name',
@patron_name AS 'BillingName',
@patron_email AS 'patron_email',
@patron_mobile as 'patron_mobile',
@patron_mobile as 'billing_mobile',
@from_email AS 'from_email',
@sms_gateway_type AS 'sms_gateway_type',
@sms_name AS 'sms_name',
@short_url AS 'short_url',
@webhook_id AS 'webhook_id',
@main_company_name AS 'main_company_name',
@franchise_email_id AS 'franchise_email',
@franchise_mobile_no AS 'franchise_mobile',
@plugin_value as 'plugin_value',
@event_name AS 'event_name',
@unit_type AS 'unit_type',
@type AS 'type',
@request_type as 'request_type',
@sms_available as 'sms_available',
@invoice_type as 'invoice_type';

commit;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_payment_response_temp` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_payment_response_temp`(_type varchar(20),_transaction_id varchar(10),_payment_id varchar(40),_pg_transaction_id varchar(40),_amount decimal(11,2),_payment_method INT,_payment_mode varchar(45),_message varchar(250),_status varchar(20),_user_id varchar(10))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
BEGIN
show errors;
ROLLBACK;
END;
START TRANSACTION;

SET @plugin_value='';
SET @image='';
SET @patron_mobile='';
SET @pay_transaction_id=_transaction_id;
SET @payment_id=_payment_id;
SET @transaction_id=_pg_transaction_id;
SET @message=_message;
SET @payment_method=_payment_method;
SET @template_id='';
SET @payment_status=3;
SET @invoice_type=1;
SET @sms_gateway=1;
SET @from_email='';
SET @sms_gateway_type=1;
SET @sms_name=NULL;
SET @short_url='';
SET @franchise_id=0;
SET @main_company_name='';
SET @webhook_id=0;
SET @event_name='';
SET @unit_type='';
SET @type='';
SET @quantity =0;
SET @profile_id=0;
SET @request_type=1;
SET @sms_available=0;
if(_status='success') then
set @status=1;
SET @response_code=0;
else
set @status=4;
SET @response_code=1;
end if;

if(_type='package') then
update package_transaction set payment_transaction_status =@status,pg_ref_no=@payment_id,pg_ref_1=@transaction_id,payment_mode=_payment_mode,payment_info=_message where package_transaction_id=_transaction_id;

SELECT user_id,merchant_id, package_id, custom_package_id INTO @merchant_user_id,@merchant_id,@package_id,@custom_package_id FROM
package_transaction WHERE package_transaction_id = _transaction_id;

select concat(first_name,' ',last_name),email_id,mobile_no into @patron_name,@patron_email,@patron_mobile from user where user_id=@merchant_user_id;

if(_status='success') then
if(@custom_package_id>0)then
select 1,package_cost,12,0,0,0,free_sms,invoice,invoice,invoice,event_booking,0,0,0
into @package_type,@package_cost,@duration,@pg_integration,@site_builder,@brand_keyword,@free_sms,@individual_invoice,
@bulk_invoice,@total_invoices,@event_booking,@merchant_role,@coupon,@supplier from custom_package where package_id=@custom_package_id;
UPDATE custom_package SET status = 1 WHERE package_id = @custom_package_id;
SET @package_id=12;
else
select `type`,package_cost,duration,pg_integration,site_builder,brand_keyword,free_sms,individual_invoice,bulk_invoice,total_invoices,merchant_role,coupon,supplier,0
into @package_type,@package_cost,@duration,@pg_integration,@site_builder,@brand_keyword,@free_sms,@individual_invoice,@bulk_invoice,@total_invoices,@merchant_role,@coupon,@supplier,@event_booking from package where package_id=@package_id;
end if;

if(@package_type=1)then
select end_date into @package_end_date from account WHERE merchant_id = @merchant_id AND is_active = 1 and amount_paid>0 and end_date>curdate();
if(@package_end_date is not null)then
SET @end_date=DATE_ADD(@package_end_date, INTERVAL @duration MONTH);
else
SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);
end if;

UPDATE account SET is_active = 0 WHERE merchant_id = @merchant_id AND is_active = 1;

INSERT INTO `account`(`merchant_id`,`package_id`,`custom_package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,`bulk_invoice`,`event_booking`,`free_sms`,
`merchant_role`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`coupon`,`supplier`,`start_date`,`end_date`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@package_id,@custom_package_id,_transaction_id,_amount,@individual_invoice,@bulk_invoice,@event_booking,@free_sms,@merchant_role,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@coupon,@supplier
,NOW(),@end_date,_user_id,CURRENT_TIMESTAMP(),_user_id);


UPDATE merchant SET merchant_plan = @package_id,package_expiry_date=@end_date WHERE merchant_id = @merchant_id;

if(@pg_integration=1)then
SET @end_date=DATE_ADD(NOW(), INTERVAL 0 MONTH);
INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,5,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
end if;

if(@site_builder=1)then
SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,6,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
UPDATE merchant_setting
SET
site_builder = 1
WHERE
merchant_id = @merchant_id;
end if;

if(@brand_keyword=1)then
SET @end_date=DATE_ADD(NOW(), INTERVAL 0 MONTH);
INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,8,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
end if;

if(@free_sms>0)then
SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,7,_transaction_id,@free_sms,@free_sms,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
UPDATE merchant SET merchant_type = 2 WHERE merchant_id = @merchant_id;
end if;
end if;

if(@package_type=2)then
SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);
if(@package_id=7)then
select base_amount into @base_amount from package_transaction where package_transaction_id=_transaction_id;
SET @licence_bought=@base_amount/@package_cost;
UPDATE merchant
SET
merchant_type = 2
WHERE
merchant_id = @merchant_id;
else
SET @licence_bought=1;
end if;
if(@package_id=6)then
update merchant_setting set site_builder=1 where merchant_id=@merchant_id;
end if;
INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@package_id,_transaction_id,@licence_bought,@licence_bought,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
end if;
end if;
else
SELECT payment_request_id,customer_id,merchant_user_id,merchant_id,payment_request_type,discount,coupon_id,quantity,narrative,deduct_amount,deduct_text,fee_id,is_partial_payment,amount-convenience_fee,fee_id,vendor_id,franchise_id
into @payment_request_id,@customer_id,@merchant_user_id,@merchant_id,@type,@discount,@coupon_id,@quantity,
@narrative,@deduct_amount,@deduct_text,@fee_detail_id,@is_partial_payment,@inv_transaction_amount,@fee_id,@vendor_id,@franchise_id from payment_transaction where pay_transaction_id=_transaction_id;
select 1;
SET @pg_surcharge_enabled=0;

SELECT pg_surcharge_enabled INTO @pg_surcharge_enabled FROM merchant_fee_detail WHERE fee_detail_id = @fee_detail_id;

if(@pg_surcharge_enabled=1)then
update payment_transaction set convenience_fee=_amount-amount,`amount`=_amount where pay_transaction_id=_transaction_id;
end if;

if(@type=2)then
SELECT short_url,franchise_id,event_name,unit_type,template_id into @short_url,@franchise_id,@event_name,@unit_type,@template_id from event_request
where event_request_id=@payment_request_id;
else
SELECT invoice_number,@invoice_type, short_url, franchise_id, webhook_id,template_id,paid_amount,grand_total,payment_request_status,plugin_value,billing_profile_id,request_type
INTO @invoice_number ,@invoice_type, @short_url , @franchise_id , @webhook_id,@template_id,@paid_amount,@invgrand_total,@payment_request_status,@plugin_value,@profile_id,@request_type FROM
payment_request WHERE payment_request_id = @payment_request_id;
end if;
select 2;
if(_status='success') then
SET @payment_status=2;
if(@vendor_id=0 and @franchise_id=0)then
select vendor_id,franchise_id into @vendor_id,@franchise_id from merchant_fee_detail where fee_detail_id=@fee_id;
end if;

if(@vendor_id>0 or @franchise_id>0)then
call split_transaction(_transaction_id,_amount,@vendor_id,@franchise_id,_user_id);
end if;

if(@coupon_id>0) then
SET @coupon_availed=1;
if(@type=2)then
select count(coupon_code) into @coupon_availed from event_transaction_detail where transaction_id=@pay_transaction_id and coupon_code=@coupon_id;
end if;

UPDATE coupon r SET r.available = r.available - @coupon_availed WHERE r.coupon_id = @coupon_id
AND r.`limit` <> 0;
	end if;
	select 3;
	select @type;
	if(@type=2)then
	select 31;
	SET @link='/merchant/transaction/viewlist/event';

	UPDATE event_transaction_detail SET is_paid = 1 WHERE transaction_id = @pay_transaction_id;
	elseif(@type=3)then
	SET @link='/merchant/transaction/viewlist/bulk';
	elseif(@type=4)then
	SET @link='/merchant/transaction/viewlist/subscription';
	elseif(@type=5)then
    select 31;
	update booking_transaction_detail set is_paid=1 where transaction_id=@pay_transaction_id;
	call update_booking_status(@pay_transaction_id);
	SET @link='/merchant/transaction/viewlist/booking';
	elseif(@type=6)then
	update customer_membership set status=1 where transaction_id=@pay_transaction_id;
	SET @link='/merchant/transaction/viewlist/booking';
	else
	SET @link='/merchant/transaction/viewlist';
	end if;

	select 8;
	if(@template_id<>'')then
		select image_path into @image from invoice_template where template_id=@template_id;
		end if;

		INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,
		`created_by`,`created_date`,`last_update_by`,`last_update_date`)
		VALUES(@merchant_user_id,1,@link,CURRENT_TIMESTAMP(),DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH),
		'Payment request(s) have been settled by your patron','',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

		UPDATE customer SET payment_status = @payment_status WHERE customer_id = @customer_id AND payment_status <> 2;
			select 9;
			else
			update customer set payment_status=@payment_status where customer_id=@customer_id and payment_status in(0,1);
			end if;
			select 10;
			SET @suppliers='';
			UPDATE `payment_request` SET `payment_request_status` = @status WHERE payment_request_id = @payment_request_id and payment_request_status not in (1,2);
			call `update_status`(@payment_request_id,@pay_transaction_id,_amount,@status,@response_code,@payment_id,@transaction_id,_payment_mode,0,@message);
			select 11;
			update customer set balance = balance - _amount where customer_id=@customer_id;
			INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
			`ledger_type`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@customer_id,concat('Payment on ',DATE_FORMAT(NOW(),'%Y-%m-%d')),_amount,2,_transaction_id,'CREDIT',@customer_id,CURRENT_TIMESTAMP(),@customer_id);


			if(@is_partial_payment=1 and _status='success')then
			SET @paid_amount=@paid_amount+@inv_transaction_amount;

			if(@invgrand_total>@paid_amount)then
			SET @inv_status=7;
			else
			SET @inv_status=1;
			end if;

			update payment_request set payment_request_status=@inv_status,paid_amount=@paid_amount where payment_request_id= @payment_request_id;
			end if;

			if(@payment_request_status=7 and _status<>'success')then
				update payment_request set payment_request_status=7 where payment_request_id= @payment_request_id;
				end if;

				select 4;
				SELECT sms_gateway,from_email,sms_gateway_type,sms_name,auto_approve
				INTO @sms_gateway , @from_email , @sms_gateway_type , @sms_name , @auto_approve FROM
				merchant_setting WHERE merchant_id = @merchant_id;

				SET @pending_change_id='';
				SELECT pending_change_id, change_id INTO @pending_change_id , @change_id FROM pending_change
				WHERE source_id = @pay_transaction_id AND source_type = 2 AND status = - 1;

				UPDATE pending_change SET status = 0 WHERE pending_change_id = @pending_change_id;
				UPDATE customer_data_change SET status = 0 WHERE change_id = @change_id;
				UPDATE customer_data_change_detail SET status = 0 WHERE change_id = @change_id;
				if(@pending_change_id<>'') then
					if(@auto_approve>0) then
					call `auto_aprove_customer_details`(@customer_id,@pay_transaction_id);
					end if;
					end if;

					SELECT customer_code, CONCAT(first_name, ' ', last_name),email,mobile INTO @customer_code , @patron_name,@patron_email,@patron_mobile FROM customer
					WHERE customer_id = @customer_id;
					SELECT `logo` INTO @merchant_logo FROM merchant_landing WHERE merchant_id = @merchant_id limit 1;
					end if;
					select 4;
					SELECT merchant_domain INTO @merchant_domain FROM merchant WHERE merchant_id = @merchant_id;

					if(@profile_id>0)then
					select company_name,company_name,address,business_email,business_contact into @display_name,@company_name,@merchant_address,@merchant_email_id,@business_contact
					from merchant_billing_profile where id = @billing_profile_id;
					else
					select company_name,company_name,address,business_email,business_contact into @display_name,@company_name,@merchant_address,@merchant_email_id,@business_contact
					from merchant_billing_profile where merchant_id = @merchant_id and is_default=1;
					end if;
					select 5;
					SET @sms_name=@company_name;

					SELECT mobile_no INTO @merchant_mobile_no FROM `user` WHERE user_id = @merchant_user_id;
					SELECT config_value INTO @payment_Mode FROM config WHERE config_key = @payment_method AND config_type = 'transaction mode';

					SET @franchise_email_id='';
					SET @franchise_mobile_no='';

					if(@request_type=2)then
					if(@sms_gateway_type=1)then
					SET @sms_available=1;
					else
					select count(id) into @sms_count from merchant_addon where merchant_id=@merchant_id and package_id=7 and license_available >0 and end_date>curdate();
					if(@sms_count>0)then
					SET @sms_available=1;
					end if;
					end if;
					end if;

					if(@franchise_id>0)then
					SET @main_company_name=@company_name;
					SET @merchant_email_id_=@merchant_email_id;
					SELECT franchise_name, email_id, mobile
					INTO @company_name , @franchise_email_id , @franchise_mobile_no FROM
					franchise WHERE franchise_id = @franchise_id;
					end if;
					SET @message='success';
					SELECT
					@message AS 'message',
					@merchant_domain as 'merchant_domain',
					@payment_request_id AS 'payment_request_id',
					@invoice_number AS 'invoice_number',
					@merchant_user_id AS 'merchant_user_id',
					@merchant_id AS 'merchant_id',
					@image AS 'image',
					@merchant_logo AS 'merchant_logo',
					@sms_gateway AS 'sms_gateway',
					@company_name AS 'company_name',
					@merchant_email_id AS 'merchant_email',
					@merchant_mobile_no AS 'mobile_no',
					@payment_Mode AS 'payment_mode',
					@suppliers AS 'suppliers',
					@discount AS 'discount',
					@deduct_amount AS 'deduct_amount',
					@deduct_text AS 'deduct_text',
					@quantity AS 'quantity',
					_message AS 'narrative',
					@customer_code AS 'customer_code',
					@patron_name AS 'patron_name',
					@patron_name AS 'BillingName',
					@patron_email AS 'patron_email',
					@patron_mobile as 'patron_mobile',
					@patron_mobile as 'billing_mobile',
					@from_email AS 'from_email',
					@sms_gateway_type AS 'sms_gateway_type',
					@sms_name AS 'sms_name',
					@short_url AS 'short_url',
					@webhook_id AS 'webhook_id',
					@main_company_name AS 'main_company_name',
					@franchise_email_id AS 'franchise_email',
					@franchise_mobile_no AS 'franchise_mobile',
					@plugin_value as 'plugin_value',
					@event_name AS 'event_name',
					@unit_type AS 'unit_type',
					@type AS 'type',
					@request_type as 'request_type',
					@sms_available as 'sms_available',
					@invoice_type as 'invoice_type';

					commit;


					END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_statging_invoicevalues` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_statging_invoicevalues`(_bulk_id int,_merchant_id char(10),_user_id char(10),_customer_id INT,_invoice_number varchar(45),_template_id varchar(10),invoice_values LONGTEXT,
_column_id LONGTEXT,_bill_date date,_due_date date,_bill_cycle_name varchar(100),_narrative varchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),
_late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_notify_patron INT,_franchise_id INT,_vendor_id INT
,_expiry_date date,_created_by varchar(10),_invoice_type INT,_type INT,_auto_collect_plan_id INT,_custom_reminder tinyint(1),_plugin_value longtext,
_billing_profile INT,_carry_forward_due tinyint(1),_generate_estimate_invoice tinyint(1),_currency char(3),_einvoice_type varchar(20),_product_taxation_type INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        SET @message='failed';
        show errors;
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @bc_id='';
SET @separator = '~';
SET @req_id='';
set @pay_req_status=0;
SET @separatorLength = CHAR_LENGTH(@separator);
SET @convenience_fee=0;
SET @parent_request_id='';
SET @estimate_number='';
SET @tax = 0;

if(_product_taxation_type = 2)then
SET @invoice_total=_amount +_previous_dues -_last_payment - _adjustment;
else
SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
SET @tax = _tax;
end if;

SET @swipez_total=@invoice_total;
SET @grand_total=@invoice_total;

update bulk_upload set `status`=8 where bulk_upload_id=_bulk_id;
select profile_id into _billing_profile from invoice_template where template_id=_template_id;

if(_billing_profile>0)then
	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where id=_billing_profile;
else
	select gst_number into @gst_number from merchant_billing_profile where merchant_id=_merchant_id and is_default=1;
end if;

select get_surcharge_amount(@merchant_id ,@invoice_total,0) into @convenience_fee;

SET @grand_total=@grand_total-_advance;

SELECT `billing_cycle_id` INTO @bc_id FROM `billing_cycle_detail` WHERE `_user_id` = _user_id AND `cycle_name` = _bill_cycle_name LIMIT 1;

if(@bc_id='') then
select generate_sequence('Billing_cycle_id') into @bc_id;
INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`,  `created_by`, `created_date`, 
`last_update_by`) VALUES (@bc_id,_user_id,_bill_cycle_name,_created_by,CURRENT_TIMESTAMP(),_created_by);
end if;


SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @req_id;

if(_type=4)then
SET @parent_request_id=0;
end if;

INSERT INTO `staging_payment_request`(`bulk_id`,`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`invoice_type`,`payment_request_type`,`template_id`,`invoice_number`,
`estimate_number`,`previous_due`,`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`grand_total`,`late_payment_fee`,
`advance_received`,`payment_request_status`,`bill_date`,`due_date`,`narrative`,`parent_request_id`,`billing_cycle_id`,`notification_sent`,`notify_patron`,
`franchise_id`,`vendor_id`,`expiry_date`,`has_custom_reminder`,`plugin_value`,`gst_number`,`billing_profile_id`,`carry_forward_due`,`generate_estimate_invoice`,`created_by`,`created_date`,`last_update_by`,`currency`,`einvoice_type`,`product_taxation_type`)
VALUES(_bulk_id,@req_id,_user_id,_merchant_id,_customer_id,_invoice_type,_type,_template_id,_invoice_number,@estimate_number,_previous_dues,@grand_total,_amount,
@tax,@invoice_total,@swipez_total,@convenience_fee,@grand_total,_late_fee,_advance,@pay_req_status,_bill_date,_due_date,_narrative,@parent_request_id,
@bc_id,0,_notify_patron,_franchise_id,_vendor_id,_expiry_date,_custom_reminder,_plugin_value,@gst_number,_billing_profile,_carry_forward_due,_generate_estimate_invoice,_created_by,
CURRENT_TIMESTAMP(),_created_by,_currency,_einvoice_type,_product_taxation_type);

WHILE _column_id != '' > 0 DO
    SET @column_id  = SUBSTRING_INDEX(_column_id, @separator, 1);
    SET @invoice_values  = SUBSTRING_INDEX(invoice_values, @separator, 1);
 
INSERT INTO `staging_invoice_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (@req_id,@column_id,@invoice_values,_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());
    SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id) + @separatorLength + 1);
    SET invoice_values = SUBSTRING(invoice_values, CHAR_LENGTH(@invoice_values) + @separatorLength + 1);
END WHILE;

commit;
SET @message = 'success';

SELECT 
    _notify_patron AS 'notify_patron',
    @req_id AS 'request_id',
    @message AS 'message';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `mailer_monthly_payment_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `mailer_monthly_payment_summary`(_from_date date , _to_date date)
BEGIN
DECLARE bDone INT;
DECLARE uid CHAR(10);
DECLARE mid CHAR(10);
DECLARE muid CHAR(10);
DECLARE curs CURSOR FOR select user_id, merchant_id, merchant_user_id from tmp_mail_mnthly_pymt_summary;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
END;

Drop TEMPORARY  TABLE  IF EXISTS tmp_mail_mnthly_pymt_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS tmp_mail_mnthly_pymt_summary (
    `user_id` varchar(10) NOT NULL,
    `merchant_id` varchar(10) NOT NULL ,
    `merchant_user_id` varchar(10) NULL ,
    `company_name` varchar(100) NULL ,
    `merchant_fname` varchar(10) NULL,
    `merchant_email` varchar(254) NULL,
    `invoices_sent` INT NULL,
    `invoices_sent_amount` DECIMAL(11,2) DEFAULT 0,
    `pymt_online_number` INT NULL,
    `pymt_online_amount` DECIMAL(11,2) DEFAULT 0,
    `pymt_offline_number` INT NULL,
    `pymt_offline_amount` DECIMAL(11,2) DEFAULT 0,
    `invoices_pending_amount` DECIMAL(11,2) DEFAULT 0,
    PRIMARY KEY (`user_id`)) ENGINE=MEMORY;

    insert into tmp_mail_mnthly_pymt_summary (user_id, merchant_id, merchant_user_id)
      select user_id, merchant_id, merchant_user_id from merchant_notification_preferences
      where weekly_summary=1;

    UPDATE tmp_mail_mnthly_pymt_summary t, user u
    SET merchant_fname = u.first_name,
    merchant_email = u.email_id
    WHERE t.user_id = u.user_id;

    UPDATE tmp_mail_mnthly_pymt_summary t, merchant m
    SET t.company_name = m.company_name
    WHERE t.user_id = m.user_id;

    OPEN curs;
    SET bDone = 0;
    REPEAT
		FETCH curs INTO uid,mid,muid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @invoice_count, @invoices_sent_amount FROM payment_request
      WHERE user_id = muid
      AND payment_request_type<>4 and created_date >= _from_date
      AND created_date <= _to_date;

      IF @invoices_sent_amount IS NULL
      THEN
        SET @invoices_sent_amount = 0;
      END IF;

      UPDATE tmp_mail_mnthly_pymt_summary SET
      invoices_sent = @invoice_count,
      invoices_sent_amount = @invoices_sent_amount
      WHERE user_id = uid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @pymt_online_number, @pymt_online_amount FROM payment_request
      WHERE user_id = muid
      AND created_date >= _from_date
      AND payment_request_type<>4 and created_date <= _to_date
      AND payment_request_status = 1;

      IF @pymt_online_amount IS NULL
      THEN
        SET @pymt_online_amount = 0;
      END IF;

      UPDATE tmp_mail_mnthly_pymt_summary SET
      pymt_online_number = @pymt_online_number,
      pymt_online_amount = @pymt_online_amount
      WHERE user_id = uid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @pymt_offline_number, @pymt_offline_amount FROM payment_request
      WHERE user_id = muid
      AND created_date >= _from_date
      AND payment_request_type<>4 and created_date <= _to_date
      AND payment_request_status = 2;

      IF @pymt_offline_amount IS NULL
      THEN
        SET @pymt_offline_amount = 0;
      END IF;

      UPDATE tmp_mail_mnthly_pymt_summary SET
      pymt_offline_number = @pymt_offline_number,
      pymt_offline_amount = @pymt_offline_amount
      WHERE user_id = uid;
    UNTIL bDone END REPEAT;
    CLOSE curs;

    UPDATE tmp_mail_mnthly_pymt_summary SET
    invoices_pending_amount = invoices_sent_amount - (pymt_online_amount + pymt_offline_amount);
    
    select * from tmp_mail_mnthly_pymt_summary order by invoices_sent desc;
    Drop TEMPORARY  TABLE  IF EXISTS tmp_mail_mnthly_pymt_summary;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `mailer_rpt_payment_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `mailer_rpt_payment_summary`(_from_date date , _to_date date)
BEGIN
DECLARE bDone INT;
DECLARE uid CHAR(10);
DECLARE mid CHAR(10);
DECLARE muid CHAR(10);
DECLARE curs CURSOR FOR select user_id, merchant_id, merchant_user_id from tmp_mail_rpt_pymt_summary;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
END;

Drop TEMPORARY  TABLE  IF EXISTS tmp_mail_rpt_pymt_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS tmp_mail_rpt_pymt_summary (
    `user_id` varchar(10) NOT NULL,
    `merchant_id` varchar(10) NOT NULL ,
    `merchant_user_id` varchar(10) NULL ,
    `company_name` varchar(100) NULL ,
    `merchant_fname` varchar(10) NULL,
    `merchant_email` varchar(254) NULL,
    `invoices_sent` INT NULL,
    `invoices_sent_amount` DECIMAL(11,2) DEFAULT 0,
    `pymt_online_number` INT NULL,
    `pymt_online_amount` DECIMAL(11,2) DEFAULT 0,
    `pymt_offline_number` INT NULL,
    `pymt_offline_amount` DECIMAL(11,2) DEFAULT 0,
    `invoices_pending_amount` DECIMAL(11,2) DEFAULT 0,
    PRIMARY KEY (`user_id`)) ENGINE=MEMORY;

    insert into tmp_mail_rpt_pymt_summary (user_id, merchant_id, merchant_user_id)
      select user_id, merchant_id, merchant_user_id from merchant_notification_preferences
      where weekly_summary=1;

    UPDATE tmp_mail_rpt_pymt_summary t, user u
    SET merchant_fname = u.first_name,
    merchant_email = u.email_id
    WHERE t.user_id = u.user_id;

    UPDATE tmp_mail_rpt_pymt_summary t, merchant m
    SET t.company_name = m.company_name
    WHERE t.user_id = m.user_id;

    OPEN curs;
    SET bDone = 0;
    REPEAT
		FETCH curs INTO uid,mid,muid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @invoice_count, @invoices_sent_amount FROM payment_request
      WHERE user_id = muid
      AND payment_request_type<>4 
      and created_date >= _from_date
      AND created_date <= _to_date;

      IF @invoices_sent_amount IS NULL
      THEN
        SET @invoices_sent_amount = 0;
      END IF;

      UPDATE tmp_mail_rpt_pymt_summary SET
      invoices_sent = @invoice_count,
      invoices_sent_amount = @invoices_sent_amount
      WHERE user_id = uid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @pymt_online_number, @pymt_online_amount FROM payment_request
      WHERE user_id = muid
      AND created_date >= _from_date
      AND payment_request_type<>4 
      and created_date <= _to_date
      AND payment_request_status = 1;

      IF @pymt_online_amount IS NULL
      THEN
        SET @pymt_online_amount = 0;
      END IF;

      UPDATE tmp_mail_rpt_pymt_summary SET
      pymt_online_number = @pymt_online_number,
      pymt_online_amount = @pymt_online_amount
      WHERE user_id = uid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @pymt_offline_number, @pymt_offline_amount FROM payment_request
      WHERE user_id = muid
      AND payment_request_type<>4
      and created_date >= _from_date
      AND created_date <= _to_date
      AND payment_request_status = 2;

      IF @pymt_offline_amount IS NULL
      THEN
        SET @pymt_offline_amount = 0;
      END IF;

      UPDATE tmp_mail_rpt_pymt_summary SET
      pymt_offline_number = @pymt_offline_number,
      pymt_offline_amount = @pymt_offline_amount
      WHERE user_id = uid;
    UNTIL bDone END REPEAT;
    CLOSE curs;

    UPDATE tmp_mail_rpt_pymt_summary SET
    invoices_pending_amount = invoices_sent_amount - (pymt_online_amount + pymt_offline_amount);
    
    select * from tmp_mail_rpt_pymt_summary order by invoices_sent desc;
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `merchantDashboardSummary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `merchantDashboardSummary`(_merchant_id char(10),_days int)
BEGIN


select  count(customer_id) into @customer_count from  customer where merchant_id=_merchant_id and is_active=1;
select  count(payment_request_id) into @invoicecount from  payment_request where merchant_id=_merchant_id and is_active=1;

select count(merchant_id) into @transaction1 from payment_transaction where merchant_id=_merchant_id and payment_transaction_status=1;
select count(merchant_id) into @transaction2 from xway_transaction where merchant_id=_merchant_id and xway_transaction_status=1;
select count(merchant_id) into @transaction3 from offline_response where merchant_id=_merchant_id and transaction_status=1;

SET @total_transaction_count=@transaction1+@transaction2+@transaction3;

if(_days=1)then
	select  sum(grand_total) into @invsum from  payment_request where merchant_id=_merchant_id and is_active=1 	and payment_request_status<>3 and payment_request_type<>4 and DATE_FORMAT(created_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m');
    select  sum(grand_total) into @paidinvsum from  payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_status =1 and DATE_FORMAT(last_update_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m');
    select  sum(amount) into @sumamount from  payment_transaction where merchant_id=_merchant_id and payment_transaction_status=1 and DATE_FORMAT(created_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m');
    select  sum(amount) into @xwaysumamount from  xway_transaction where merchant_id=_merchant_id and xway_transaction_status=1 and DATE_FORMAT(last_update_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m');
    SELECT sum(requested_settlement_amount) ,sum(total_tdr) ,sum(total_service_tax) into @settelementsumamount,@totaltdr,@totaltax  FROM settlement_detail where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m');
else
	select  sum(grand_total) as invsum from  payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_status<>3 and payment_request_type<>4 and DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL _days DAY);
    select  sum(grand_total) into @paidinvsum from  payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_status =1 and DATE_FORMAT(last_update_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL _days DAY);
    select  sum(amount) into @sumamount from  payment_transaction where merchant_id=_merchant_id and payment_transaction_status=1 and DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL _days DAY);
    select  sum(amount) into @xwaysumamount from  xway_transaction where merchant_id=_merchant_id and xway_transaction_status=1 and DATE_FORMAT(last_update_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL _days DAY);
    SELECT sum(requested_settlement_amount) ,sum(total_tdr) ,sum(total_service_tax) into @settelementsumamount,@totaltdr,@totaltax FROM settlement_detail where merchant_id=_merchant_id and  DATE_FORMAT(settlement_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL _days DAY);
end if;

select @customer_count as 'customer_count',
@invoicecount as 'invoice_count',
@total_transaction_count as 'transaction_count',
@invsum as 'invoice_sum',
@paidinvsum as 'paid_invoice_sum',
@sumamount as 'transaction_sum',
@xwaysumamount as 'xway_sum',
@settelementsumamount as 'total_settelement',
@totaltdr as 'total_tdr',
@totaltax as 'total_tax';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `merchantProfileUpdate` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `merchantProfileUpdate`(_user_id char(10),_merchant_id char(10),_fname varchar(50),
_lname varchar(50),_mobile varchar(15),
_address varchar(250),_city varchar(45),_state varchar(45),_country varchar(45),
_zip varchar(11),_type int,_industry_type int,_company varchar(100),_company_registration_number varchar(20),_gst_number varchar(45),_cin_no varchar(45),_pan varchar(12),_tan varchar(45),_current_address varchar(250),
_current_city varchar(45),_current_state varchar(45),_current_country varchar(45),_current_zip varchar(11),
_business_email varchar(254),_country_code varchar(6),_business_contact varchar(45))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

update user set first_name=_fname,last_name=_lname,mobile_no=_mobile where user_id=_user_id;

update merchant_billing_profile set reg_address=_address,reg_city=_city,reg_state=_state,reg_country=_country,reg_zipcode=_zip,
`address` = _current_address, `city` = _current_city,`zipcode` = _current_zip,
`state` = _current_state,`country` = _current_country,`business_email` = _business_email,
`business_contact` = _business_contact,`gst_number`=_gst_number,`cin_no` = _cin_no,`pan` = _pan,`tan` = _tan,
`last_update_by` = _user_id WHERE merchant_id=_merchant_id and is_default=1;

UPDATE `merchant` SET `entity_type` = _type,`industry_type` = _industry_type,`last_update_by` = _user_id WHERE merchant_id=_merchant_id;

commit;
SET @message='success';
select @message as 'message';


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `merchant_dummy_data` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_dummy_data`(_merchant_id char(10),_user_id char(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
SET @merchant_id=_merchant_id;
SET @user_id=_user_id;

select email_id,mobile_no,first_name,last_name into @email_id,@mobile,@first_name,@last_name from user where user_id=@user_id;

INSERT INTO `customer`
(`merchant_id`,`customer_code`,`first_name`,`last_name`,`email`,`mobile`,`address`,`city`,`state`,`zipcode`,`created_by`,`created_date`
,`last_update_by`)
VALUES(@merchant_id,'cust-1','Customer','Name',@email_id,@mobile,'','','','',@user_id,CURRENT_TIMESTAMP(),@user_id);

#added taxes
INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, 'CGST@9%', '9.00', '1', '', '1',1, @user_id, now(), @user_id);
select LAST_INSERT_ID() into @cgst_id;
INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, 'SGST@9%', '9.00', '2', '', '1',1, @user_id, now(), @user_id);
select LAST_INSERT_ID() into @sgst_id;

INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, 'IGST@18%', '18.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@2.5%', '2.50', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@2.5%', '2.50', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@5%', '5.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@6%', '6.00', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@6%', '6.00', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@12%', '12.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@14%', '14.00', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@14%', '14.00', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@28%', '28.00', '3', '', '1',1, @user_id, now(), @user_id);

SET @sequence='INV-';
INSERT INTO `merchant_auto_invoice_number` (`merchant_id`, `prefix`, `val`, `type`, `is_active`, `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, @sequence, '0', '1', '1', @merchant_id, now(), @merchant_id);
select LAST_INSERT_ID() into @inv_id;

INSERT INTO `customer_column_metadata`(`merchant_id`,`column_datatype`,`position`,`column_name`,`column_type`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(@merchant_id,'gst','L','GST number','Custom',
@merchant_id,CURRENT_TIMESTAMP(),@merchant_id,CURRENT_TIMESTAMP());

INSERT INTO `customer_column_metadata`(`merchant_id`,`column_datatype`,`position`,`column_name`,`column_type`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(@merchant_id,'company_name','L','Company name','Custom',
@merchant_id,CURRENT_TIMESTAMP(),@merchant_id,CURRENT_TIMESTAMP());

SET @templatename = 'Invoice';
SET @template_type = 'isp';
SET @main_header_id = '9~10~11~12';
SET @main_header_default = 'Profile~Profile~Profile~Profile';
SET @customer_column = '1~2~3~4';
SET @custom_column = '';
SET @header = 'Invoice No.~Billing cycle name~Bill date~Due date';
SET @position = 'R~R~R~R';
SET @column_type = 'H~H~H~H';
SET @sort = 'MCompany name~MMerchant contact~MMerchant email~MMerchant address~CCustomer code~CCustomer name~CEmail ID~CMobile no~HInvoice No.~HBilling cycle name~HBill date~HDue date';
SET @column_position = '-1~4~5~6';
SET @function_id = '9~-1~5~7';
SET @function_param = 'system_generated~~~bill_date';
SET @function_val = concat(@inv_id,'~~~5');
SET @is_delete = '1~2~2~2';
SET @headerdatatype = 'text~text~date~date';
SET @headertablename = 'metadata~request~request~request';
SET @headermandatory = '2~1~1~1';
SET @particularname = 'Particular';
SET @pc = '{"sr_no":"#","item":"Description","sac_code":"Sac Code","description":"Time period","gst":"GST","total_amount":"Absolute cost"}';
SET @pd = '["Particular"]';
SET @td = concat('["',@cgst_id,'","',@sgst_id,'"]');
SET @plugin = '';
SET @tnc = '';
SET @defaultValue = '';
SET @particular_total = 'Sub total';
SET @tax_total = 'Tax total';
SET @ext = '.';
SET @maxposition = '6';

call `template_save`(@templatename,@template_type,@merchant_id,@user_id,@main_header_id,@main_header_default,@customer_column,@custom_column,@header,@position,@column_type,@sort,@column_position,@function_id,@function_param,@function_val,@is_delete,@headerdatatype,@headertablename,@headermandatory,@tnc,@defaultValue,@particular_total,@tax_total,@ext,@maxposition,@pc,@pd,@td,@plugin,0,@user_id,@message,@template_id);
update invoice_template set image_path='demo-logo.png' where template_id=@template_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `merchant_info_saved` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_info_saved`(_user_id varchar(10),_merchant_id varchar(10),_f_name varchar(50),_l_name varchar(50),_address varchar(250),
_city varchar(45),_state varchar(45),_country varchar(45),
_zip varchar(11),_type int,_industry_type int,_company_registration_number varchar(20),_pan varchar(20),_current_address varchar(250),
_current_city varchar(45),_current_state varchar(45),_current_country varchar(45),_current_zip varchar(11),
_business_email varchar(254),_business_contact varchar(20))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

UPDATE user 
SET 
    first_name = _f_name,
    user_status = 16,
    last_name = _l_name
WHERE
    user_id = _user_id;

UPDATE `merchant` 
SET 
    `entity_type` = _type,
    `industry_type` = _industry_type,
    `last_update_by` = _user_id
WHERE
    user_id = _user_id;

update merchant_billing_profile set reg_address=_address,reg_city=_city,reg_state=_state,reg_country=_country,
reg_zipcode=_zip,`address` = _current_address, `city` = _current_city,`zipcode` = _current_zip,
`state` = _current_state,`country` = _current_country,`business_email` = _business_email,
`business_contact` = _business_contact,`gst_number`=_gst_number,`cin_no` = _cin_no,`pan` = _pan,`tan` = _tan,
`last_update_by` = _user_id WHERE merchant_id=_merchant_id and is_default=1;


commit;
SET @message='success';
SELECT @message AS 'message';


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `merchant_mobile_template` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_mobile_template`(_merchant_id char(10),_user_id char(10),_free_sms INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
SET @merchant_id=_merchant_id;
SET @user_id=_user_id;
SET @tax_count=0;
SET @cgst_id=0;
SET @sgst_id=0;
SET @inv_seq=0;
SET @gst_meta=0;
SET @freesms=_free_sms;

select email_id,mobile_no,first_name,last_name into @email_id,@mobile,@first_name,@last_name 
from user where user_id=@user_id;



#invoice template

select count(*) into @tax_count from merchant_tax where merchant_id=@merchant_id;
if(@tax_count=0)then
	INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
	VALUES (@merchant_id, 'CGST@9%', '9.00', '1', '', '1',1, @user_id, now(), @user_id);
	select LAST_INSERT_ID() into @cgst_id;
	INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
	VALUES (@merchant_id, 'SGST@9%', '9.00', '2', '', '1',1, @user_id, now(), @user_id);
	select LAST_INSERT_ID() into @sgst_id;

	INSERT INTO `merchant_tax` (`merchant_id`, `tax_name`, `percentage`, `tax_type`, `description`, `is_active`,`is_default`, `created_by`, `created_date`, `last_update_by`) 
	VALUES (@merchant_id, 'IGST@18%', '18.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@2.5%', '2.50', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@2.5%', '2.50', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@5%', '5.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@6%', '6.00', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@6%', '6.00', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@12%', '12.00', '3', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'CGST@14%', '14.00', '1', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'SGST@14%', '14.00', '2', '', '1',1, @user_id, now(), @user_id),(@merchant_id, 'IGST@28%', '28.00', '3', '', '1',1, @user_id, now(), @user_id);
end if;

select count(*) into @inv_seq from merchant_auto_invoice_number where merchant_id=@merchant_id and is_active=1;
if(@inv_seq=0)then
	SET @sequence='INV-';
	INSERT INTO `merchant_auto_invoice_number` (`merchant_id`, `prefix`, `val`, `type`, `is_active`, `created_by`, `created_date`, `last_update_by`) 
	VALUES (@merchant_id, @sequence, '0', '1', '1', @merchant_id, now(), @merchant_id);
	select LAST_INSERT_ID() into @inv_id;
else
	select prefix,auto_invoice_id into @sequence,@inv_id from merchant_auto_invoice_number where merchant_id=@merchant_id and is_active=1 limit 1;
end if;

select count(*) into @gst_meta from customer_column_metadata where merchant_id=@merchant_id and is_active=1 and column_datatype='gst';
if(@gst_meta=0)then
	INSERT INTO `customer_column_metadata`(`merchant_id`,`column_datatype`,`position`,`column_name`,`column_type`,
	`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(@merchant_id,'gst','L','GST number','Custom',
	@merchant_id,CURRENT_TIMESTAMP(),@merchant_id,CURRENT_TIMESTAMP());
end if;

SET @templatename = 'App Invoice';
SET @template_type = 'isp';
SET @main_header_id = '9~10~11~12';
SET @main_header_default = 'Profile~Profile~Profile~Profile';
SET @customer_column = '1~2~3~4';
SET @custom_column = '';
SET @header = 'Invoice No.~Billing cycle name~Bill date~Due date';
SET @position = 'R~R~R~R';
SET @column_type = 'H~H~H~H';
SET @sort = 'MCompany name~MMerchant contact~MMerchant email~MMerchant address~CCustomer code~CCustomer name~CEmail ID~CMobile no~HInvoice No.~HBilling cycle name~HBill date~HDue date';
SET @column_position = '-1~4~5~6';
SET @function_id = '9~-1~5~7';
SET @function_param = 'system_generated~~~bill_date';
SET @function_val = concat(@inv_id,'~~~5');
SET @is_delete = '1~2~2~2';
SET @headerdatatype = 'text~text~date~date';
SET @headertablename = 'metadata~request~request~request';
SET @headermandatory = '2~1~1~1';
SET @particularname = 'Particular';
SET @pc = '{"sr_no":"#","item":"Description","qty":"Quantity","unit_type":"Unit type","rate":"Rate","gst":"GST","discount":"Discount","total_amount":"Absolute cost"}';
SET @pd = '';
SET @td = concat('["',@cgst_id,'","',@sgst_id,'"]');
SET @plugin = '';
SET @tnc = '';
SET @defaultValue = '';
SET @particular_total = 'Sub total';
SET @tax_total = 'Tax total';
SET @ext = '.';
SET @maxposition = '6';

call `template_save`(@templatename,@template_type,@merchant_id,@user_id,@main_header_id,@main_header_default,@customer_column,@custom_column,@header,@position,@column_type,@sort,@column_position,@function_id,@function_param,@function_val,@is_delete,@headerdatatype,@headertablename,@headermandatory,@tnc,@defaultValue,@particular_total,@tax_total,@ext,@maxposition,@pc,@pd,@td,@plugin,0,@user_id,@message,@template_id);


#Contact template
SET @templatename = 'Payment request';
SET @template_type = 'scan';
SET @main_header_id = '9~10~11~12';
SET @main_header_default = 'Profile~Profile~Profile~Profile';
SET @customer_column = '1~2~3~4';
SET @custom_column = '';
SET @header = 'Billing cycle name~Bill date~Due date~Payble amount';
SET @position = 'R~R~R~R';
SET @column_type = 'H~H~H~H';
SET @sort = 'MCompany name~MMerchant contact~MMerchant email~MMerchant address~CCustomer code~CCustomer name~CEmail ID~CMobile no~HBilling cycle name~HBill date~HDue date~HPayble amount';
SET @column_position = '4~5~6~10';
SET @function_id = '-1~5~5~13';
SET @function_param = '~~~';
SET @function_val = '~~~';
SET @is_delete = '2~2~2~1';
SET @headerdatatype = 'text~date~date~money';
SET @headertablename = 'request~request~request~metadata';
SET @headermandatory = '1~1~1~1';
SET @particularname = 'Particular';
SET @pc = '';
SET @pd = '';
SET @td = '';
SET @plugin = '';
SET @tnc = '';
SET @defaultValue = '';
SET @particular_total = 'Sub total';
SET @tax_total = 'Tax total';
SET @ext = '.';
SET @maxposition = '10';
select template_id into @invoicetemplate_id from invoice_template where merchant_id=@merchant_id order by template_id desc limit 1;
call `template_save`(@templatename,@template_type,@merchant_id,@user_id,@main_header_id,@main_header_default,@customer_column,@custom_column,@header,@position,@column_type,@sort,@column_position,@function_id,@function_param,@function_val,@is_delete,@headerdatatype,@headertablename,@headermandatory,@tnc,@defaultValue,@particular_total,@tax_total,@ext,@maxposition,@pc,@pd,@td,@plugin,0,@user_id,@message,@template_id);
select column_id into @column_id from invoice_column_metadata where template_id=@template_id and function_id=13 limit 1;
select column_id into @invoice_no_column_id from invoice_column_metadata where template_id=@invoicetemplate_id and function_id=9 limit 1;

SET @json=concat('{\"payment_request_format_id\":\"',@template_id,'\",\"payment_request_column_id\":\"',@column_id,'\",\"invoice_format_id\":\"',@invoicetemplate_id,'\",\"invoice_no_column_id\":\"',@invoice_no_column_id,'\",\"invoice_seq_id\":\"',@inv_id,'\"}');
INSERT INTO `merchant_config_data` (`merchant_id`, `user_id`, `key`, `value`,  `created_date`) VALUES (@merchant_id,@user_id, 'MOBILE_FORMAT_DETAILS', @json, now());
if(@freesms>0)then
SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
INSERT INTO `merchant_addon` (`package_id`, `merchant_id`, `package_transaction_id`, `license_bought`, `license_available`, `start_date`, `end_date`, `is_active`, `created_by`, `created_date`, `last_update_by`) 
VALUES ('7', @merchant_id, 'FREE', @freesms, @freesms, curdate(), @end_date, '1', 'System', now(), 'System');
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `merchant_register` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_register`(_email varchar(254),_first_name nvarchar(100),_last_name nvarchar(100),_mobile_code varchar(5),_mobile varchar(13),_pass varchar(60),_company_name nvarchar(100),_plan_id INT,_campaign_id INT,_registered_from INT,_service_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
            
		END; 
START TRANSACTION;

SELECT GENERATE_SEQUENCE('User_id') INTO @user_id;
SELECT GENERATE_SEQUENCE('group_id') INTO @group_id;


set @get_id=0;
SET @group_usertype=1;
SET @merchant_type=2;
SET @statuss=12;
SET @merchant_status=2;

SET @package_id=2;
SET @license_package_id=0;
SET @bulk_upload_limit=250;
SET @full_name=CONCAT(_first_name,' ',_last_name);


SELECT 
    individual_invoice,    bulk_invoice,    free_sms,    merchant_role,    duration,    pg_integration,    site_builder,
    brand_keyword,    total_invoices,    coupon,    supplier
INTO @individual_invoice , @bulk_invoice , @free_sms , @merchant_role , @duration , @pg_integration , @site_builder , @brand_keyword , @total_invoices , @coupon , @supplier FROM
    package
WHERE
    package_id = @package_id;
SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);

INSERT INTO `user`(`email_id`, `user_id`,`name`, `first_name`, `last_name`,`password`,`mob_country_code`,`mobile_no`, `user_status`, `group_id`
, `user_group_type`, `user_type`,`registered_from`, `created_by`, `created_date`, `last_updated_by`, `last_updated_date`)
VALUES (_email,@user_id,@full_name,_first_name,_last_name,_pass,_mobile_code,_mobile,@statuss,@group_id,@group_usertype,0,_registered_from,@user_id,CURRENT_TIMESTAMP(),@User_id,CURRENT_TIMESTAMP());

SET @auth_id=LAST_INSERT_ID();


SELECT GENERATE_SEQUENCE('Merchant_id') INTO @merchant_id;

INSERT INTO `merchant`(`merchant_id`, `user_id`,`merchant_plan`,`merchant_status`,`entity_type`,`merchant_type`, `group_id`, `company_name`
,`package_expiry_date`,registration_campaign_id,`service_id`,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,@user_id,@package_id,@merchant_status,@typee,@merchant_type, @group_id,_company_name,@end_date,_campaign_id,_service_id,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());


INSERT INTO `merchant_billing_profile` (`merchant_id`,`profile_name`,`company_name`,`business_email`,`business_contact`,`is_default`,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,'Default profile',_company_name,_email,_mobile,1,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());


INSERT INTO `preferences`(`user_id`, `created_date`, `last_update_date`) values(@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

if(_campaign_id=30)then
SET @bulk_upload_limit=2000;
end if;

INSERT INTO `merchant_setting`(`merchant_id`,`sms_gateway_type`,`sms_gateway`,`min_transaction`,`max_transaction`,
`invoice_bulk_upload_limit`,`customer_bulk_upload_limit`,`xway_enable`,`statement_enable`,`show_ad`,`promotion_id`,
`auto_approve`,`customer_auto_generate`,`prefix`,`created_by`,`last_update_by`,`last_update_date`,`created_date`)
VALUES(@merchant_id,2,1,20.00,100000.00,@bulk_upload_limit,@bulk_upload_limit,0,0,0,0,1,0,'C',@user_id,@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());


INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,
`bulk_invoice`,`free_sms`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`merchant_role`,`coupon`,`supplier`,`start_date`,`end_date`,`license_key_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@package_id,'',0,@individual_invoice,@bulk_invoice,@free_sms,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@merchant_role,@coupon,@supplier,NOW(),@end_date,0,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);

   
 
if(_campaign_id<>30)then     
	
	 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
	, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
	VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
	'Check out our video tutorials and learn at your own pace.',
	 'Check out our video tutorials and learn at your own pace.', 
	 'https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured', 'Videos', '0', '1', now(), now(), now());
	  
	 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
	, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
	VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
	'Chat with us and our representative will guide you to setup your account.',
	 'Chat with us and our representative will guide you to setup your account.', 
	 'https://www.swipez.in/merchant/chatnow', 'Chat with us', '0', '1', now(), now(), now());
     
     INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
	, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
	VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
	'Check out our new help center. Get the most out of your Swipez account!',
	 'Check out our new help center. Get the most out of your Swipez account!', 
	 'http://helpdesk.swipez.in/help', 'Check it out', '0', '1', now(), now(), now());
 end if;
 
 if(_service_id=8)then
  INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
'Watch how cable operators organize their payments collections using Swipez',
 'Watch how cable operators organize their payments collections using Swipez', 
 '#cable" data-toggle="modal" id="cablepopup', 'Videos', '0', '1', now(), now(), now());
 end if;
 

INSERT INTO `customer_sequence` (`merchant_id`, `val`) VALUES (@merchant_id, '0');
INSERT INTO `merchant_auto_invoice_number`(`merchant_id`,`prefix`,`val`,`type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,'EST/','0',2,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
INSERT INTO `notification_count`(`merchant_id`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@user_id,CURRENT_TIMESTAMP(),@user_id);

if(_service_id>0)then
	if(_service_id=15)then
			INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@merchant_id,@user_id,2,1,@user_id,CURRENT_TIMESTAMP(),@user_id);
	end if;
INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,_service_id,1,@user_id,CURRENT_TIMESTAMP(),@user_id);
else
INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,2,1,@user_id,CURRENT_TIMESTAMP(),@user_id);
end if;

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

if(_registered_from=0)then
call merchant_dummy_data(@merchant_id,@user_id);
end if;
SET @message='success';

SELECT @message AS 'Message',_email AS 'email_id', @merchant_id AS 'merchant_id', @user_id AS 'user_id' , @auth_id as 'id';

commit;




END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `merchant_transactions` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_transactions`(_merchant_id CHAR(10), _start int, _limit int, _search nvarchar(45),_group varchar(10))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
 		BEGIN
        show errors;
		END;
Drop TEMPORARY  TABLE  IF EXISTS temp_transaction;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_transaction (
    `payment_request_id` char(10) NOT NULL ,
    `customer_id` INT NOT NULL ,
    `customer_code` varchar(45) NULL ,
	`name` nvarchar(100) NULL ,
    `mobile` varchar(20) NULL,
    `bill_date` DATE NOT NULL ,
    `transaction_date` DATETIME NULL,
    `amount` DECIMAL(11,2) NULL ,
    `invoice_number` varchar(45) NULL,
    `payment_mode` varchar(45) NULL,
    `transaction_id` char(10) NULL,
    `payment_request_status` int NOT NULL,
	`is_settled` int NOT NULL default 0,
    `settled_on` datetime NULL,
    `settlement_id` int NOT NULL default 0,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
    IF(_search <> '' or _group<>'') THEN
    Drop TEMPORARY  TABLE  IF EXISTS temp_filter_customer;
	CREATE TEMPORARY TABLE IF NOT EXISTS temp_filter_customer (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    SET @group_id=0;
    if(_group <> '')then
	SET @group_id=_group;
    end if;
    
    if(_search <> '')then
    insert into temp_filter_customer (customer_id)
	select customer_id from customer where merchant_id=_merchant_id and `customer_group` LIKE concat('%{',@group_id,'}%') and (`first_name` LIKE concat('%',_search,'%') or `last_name` LIKE concat('%',_search,'%') or customer_code LIKE concat('%',_search,'%') or mobile LIKE concat('%',_search,'%') );
    else
     insert into temp_filter_customer (customer_id)
	select customer_id from customer where merchant_id=_merchant_id and `customer_group` LIKE concat('%{',@group_id,'}%');
 
    end if;
	SET @sql=concat('insert into temp_transaction (payment_request_id, customer_id, bill_date, payment_request_status, invoice_number)select payment_request_id, p.customer_id, bill_date, payment_request_status, invoice_number from payment_request p inner join temp_filter_customer c on c.customer_id=p.customer_id  where payment_request_status in (1, 2) and merchant_id="', _merchant_id , '"  order by last_update_date desc limit ',_start,',',_limit);
	else
	SET @sql=concat('insert into temp_transaction (payment_request_id, customer_id, bill_date, payment_request_status, invoice_number)select payment_request_id, customer_id, bill_date, payment_request_status, invoice_number from payment_request where payment_request_status in (1, 2) and merchant_id="', _merchant_id , '"  order by last_update_date desc limit ',_start,',',_limit);
    end if;
    
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
	update temp_transaction t , payment_transaction p set t.amount=p.amount, t.transaction_id=p.pay_transaction_id, t.payment_mode=p.payment_mode, t.transaction_date=p.created_date,t.is_settled=p.is_settled where t.payment_request_id=p.payment_request_id and t.payment_request_status=1;
	update temp_transaction t , offline_response o set t.amount=o.amount, t.transaction_id=o.offline_response_id, t.payment_mode=o.offline_response_type, t.transaction_date=o.created_date where t.payment_request_id=o.payment_request_id and t.payment_request_status=2;
    update temp_transaction t , customer c set t.customer_code=c.customer_code, t.mobile=c.mobile, t.name=concat(c.first_name, ' ',c.last_name) where t.customer_id=c.customer_id;
    update temp_transaction t , config c set t.payment_mode=c.config_value where t.payment_mode=c.config_key and c.config_type='offline_response_type' and t.payment_request_status=2;
	update temp_transaction t , payment_transaction_settlement s set t.settled_on=s.settlement_date,t.settlement_id=s.settlement_id where t.transaction_id=s.transaction_id and t.is_settled=1;
	select * from temp_transaction;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `offlinerespond` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `offlinerespond`(_amount decimal(11,2),_bank_name varchar(100),_payment_req_id varchar(11)
,_date DATETIME ,_bank_transaction_no varchar(20),_respond_type int,_cheque_no varchar(20),_cash_paidto nvarchar(100)
,_user_id varchar(10),_payment_request_status int,_coupon_id INT,_discount decimal(11,2),_deduct_amount decimal(11,2),_deduct_text varchar(100),_cheque_status varchar(10),_is_partial tinyint(1),_cod_status varchar(20))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        SET @message = 'failed';
        	ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @merchant_user_id='';
SET @transaction_status=1;

if(_respond_type=6)then
SET _payment_request_status=1;
end if;

if(@currency='')then
 SET @currency='INR';
end if;

SELECT merchant_id,user_id,customer_id,payment_request_type,franchise_id,vendor_id,paid_amount,grand_total,currency into @merchant_id,@merchant_user_id,@customer_id,@payment_request_type,@franchise_id,@vendor_id,@paid_amount,@grand_total,@currency
from payment_request where payment_request_id =_payment_req_id;

select generate_sequence('Offline_respond_id') into @offline_response_id;

if(_cheque_status='Bounced')then
SET _payment_request_status=4;
SET @transaction_status=0;
end if;

INSERT INTO `offline_response`(`offline_response_id`,`payment_request_id`,`payment_request_type`,`customer_id`,`merchant_user_id`,`merchant_id`,`offline_response_type`,
`settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`discount`,`coupon_id`,`cheque_no`,`cheque_status`,`transaction_status`,`cash_paid_to`,`deduct_amount`,`deduct_text`,`created_by`,`created_date`, `last_update_by`,
`last_update_date`,`franchise_id`,`vendor_id`,`is_partial_payment`,`cod_status`,`currency`)
VALUES (@offline_response_id,_payment_req_id,1,@customer_id,@merchant_user_id,@merchant_id,_respond_type,_date,_bank_transaction_no,_bank_name,
    _amount,_discount,_coupon_id,_cheque_no,_cheque_status,@transaction_status,_cash_paidto,_deduct_amount,_deduct_text,_user_id, CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP(),@franchise_id,@vendor_id,_is_partial,_cod_status,@currency);



update payment_request set payment_request_status=_payment_request_status where payment_request_id=_payment_req_id;

update customer set balance = balance - _amount where customer_id=@customer_id;
INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@customer_id,concat('Payment on ',DATE_FORMAT(NOW(),'%Y-%m-%d')),_amount,2,@offline_response_id,'CREDIT',@customer_id,CURRENT_TIMESTAMP(),@customer_id);


if(_is_partial=1)then
update payment_request set payment_request_status=7,paid_amount=@paid_amount+_amount where payment_request_id=_payment_req_id;
end if;

if(@merchant_user_id!=_user_id) then
select payment_request_type into @type from payment_request where payment_request_id=_payment_req_id;
if(@type=2)then
SET @link='/merchant/transaction/viewlist/event';

elseif(@type=3)then
SET @link='/merchant/transaction/viewlist/bulk';

elseif(@type=4)then
SET @link='/merchant/transaction/viewlist/subscription';

else
SET @link='/merchant/transaction/viewlist';

end if;
    if(_payment_request_status<>3)then
        SET @notification_type=1;
        SET @message='count payment request(s) have been settled by your patron';
        INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,
                `created_by`,`created_date`,`last_update_by`,`last_update_date`)
                VALUES(@merchant_user_id,@notification_type,@link,CURRENT_TIMESTAMP(),DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH),
                @message,'',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
    end if;
end if;

select customer_code,concat(first_name,' ',last_name),email,mobile into @user_code, @patron_name,@email_id,@mobile from customer where customer_id=@customer_id ;
commit;
        SET @message = 'success';
        select @message as 'message',@offline_response_id as 'offline_response_id',@user_code as 'user_code',@patron_name as 'patron_name',@email_id as 'email_id',@mobile as 'mobile';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `patron_register` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `patron_register`(_merchant_id varchar(10),_user_id varchar(10),_first_name varchar(100),_last_name varchar(100),_email varchar(254),_mobile_code varchar(5),_mobile varchar(15),_password varchar(100),_customer_id int,_payment_request_id varchar(10),_login_type INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
            
		END; 
START TRANSACTION;

SELECT GENERATE_SEQUENCE('group_id') INTO @group_id;

set @get_id=0;
SET @group_usertype=1;
SET @merchant_type=2;
SET @statuss=12;
SET @merchant_status=2;
SET @package_id=2;
SET @license_package_id=0;
SET @user_id=_user_id;
SET @merchant_id='';
SET @company_name='';

if(_payment_request_id<>'')then
SET @source_type=1;
SET @status=0;

select customer_id,address,city,state,zipcode into @customer_id,@address,@city,@state,@zipcode from payment_request where payment_request_id=_payment_request_id;

update customer set customer_status=1,last_update_by=@user_id where customer_id=@customer_id;

call `save_change_details`(@user_id,@source_type,@customer_id,@status,@customer_id,_first_name,_last_name,_email,_mobile,@address,@city,@state,@zipcode);

end if;


if(@user_id='')then
if(_customer_id>0)then
select first_name,last_name into @first_name,@last_name from customer where customer_id=_customer_id;
else
SET @first_name=_first_name;
SET @last_name=_last_name;
end if;

SELECT GENERATE_SEQUENCE('User_id') INTO @user_id;

Drop TEMPORARY  TABLE  IF EXISTS temp_customer_email;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_email (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;

insert into temp_customer_email(customer_id)
select customer_id from customer where email=_email and _email<>'';

update customer c,temp_customer_email t set customer_status=2 ,user_id=@user_id where c.customer_id=t.customer_id;

INSERT INTO `user`(`email_id`, `user_id`,`password`, `first_name`, `last_name`,`mob_country_code`,`mobile_no`, `user_status`, `group_id`
, `user_group_type`, `user_type`,`login_type`, `created_by`, `created_date`, `last_updated_by`
, `last_updated_date`)
VALUES (_email,@user_id,_password,@first_name,@last_name,_mobile_code,_mobile,@statuss,@group_id,@group_usertype,0,_login_type,@user_id,CURRENT_TIMESTAMP(),@User_id,CURRENT_TIMESTAMP());


INSERT INTO `preferences`(`user_id`, `created_date`, `last_update_date`) values(@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

          
else

update user set user_status=15 ,user_group_type=@group_usertype,group_id=@group_id where user_id=@user_id;
end if;

if(_login_type=1)then
SELECT GENERATE_SEQUENCE('Merchant_id') INTO @merchant_id;

SELECT 
    individual_invoice,    bulk_invoice,    free_sms,    merchant_role,    duration,    pg_integration,    site_builder,
    brand_keyword,    total_invoices,    coupon,    supplier
INTO @individual_invoice , @bulk_invoice , @free_sms , @merchant_role , @duration , @pg_integration , @site_builder , @brand_keyword , @total_invoices , @coupon , @supplier FROM
    package
WHERE
    package_id = @package_id;
SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);

INSERT INTO `merchant`(`merchant_id`, `user_id`,`merchant_plan`,`type`,`merchant_status`,`entity_type`,`merchant_type`, `group_id`, `company_name`
,`package_expiry_date`,registration_campaign_id,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,@user_id,@package_id,2,@merchant_status,@typee,@merchant_type, @group_id,'',@end_date,0,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

INSERT INTO `merchant_billing_profile` (`merchant_id`,`profile_name`,`company_name`,`business_email`,`business_contact`,`is_default`,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,'Default profile','',_email,_mobile,1,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());


INSERT INTO `merchant_setting`(`merchant_id`,`sms_gateway_type`,`sms_gateway`,`min_transaction`,`max_transaction`,
`invoice_bulk_upload_limit`,`customer_bulk_upload_limit`,`xway_enable`,`statement_enable`,`show_ad`,`promotion_id`,
`auto_approve`,`customer_auto_generate`,`prefix`,`created_by`,`last_update_by`,`last_update_date`,`created_date`)
VALUES(@merchant_id,2,1,20.00,2000.00,250,250,0,0,0,0,1,0,'C',@user_id,@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,
`bulk_invoice`,`free_sms`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`merchant_role`,`coupon`,`supplier`,`start_date`,`end_date`,`license_key_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@package_id,'',0,@individual_invoice,@bulk_invoice,@free_sms,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@merchant_role,@coupon,@supplier,NOW(),@end_date,0,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);



 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
'Check out our video tutorials and learn at your own pace.',
 'Check out our video tutorials and learn at your own pace.', 
 'https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured', 'Videos', '0', '1', '2017-12-13', '2017-12-13 12:21:52', '2017-12-13 12:21:52');
 
 
 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
'Chat with us and our representative will guide you to setup your account.',
 'Chat with us and our representative will guide you to setup your account.', 
 'https://tawk.to/chat/574eb7843c365f2e5bd7c68f/default/?$_tawk_popout=true', 'Chat with us', '0', '1', '2017-12-13', '2017-12-13 12:21:52', '2017-12-13 12:21:52');
 
    

INSERT INTO `customer_sequence` (`merchant_id`, `val`) VALUES (@merchant_id, '0');
INSERT INTO `merchant_auto_invoice_number`(`merchant_id`,`prefix`,`val`,`type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,'EST/','0',2,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
INSERT INTO `notification_count`(`merchant_id`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@user_id,CURRENT_TIMESTAMP(),@user_id);

end if;

if(_login_type=2)then

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`merge_menu`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,1,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

	if(_merchant_id<>'')then
		SET @has_cable=0;
		select count(id) into @has_cable from merchant_active_apps where merchant_id=_merchant_id and service_id=8 and status=1;
        if(@has_cable>0)then
			INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`merge_menu`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@merchant_id,@user_id,9,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);
        end if;
	end if;
else

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

end if;
select created_date into @created_date from user where user_id=@user_id;
select company_name into @company_name from merchant where merchant_id=_merchant_id;


SET @message='success';

SELECT @message AS 'Message',_email AS 'email_id', @merchant_id AS 'customer_merchant_id',@company_name as 'company_name', @user_id AS 'user_id',@merchant_id as 'merchant_id', @created_date as 'created_date';

commit;




END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `patron_register_delete` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `patron_register_delete`(_merchant_id varchar(10),_user_id varchar(10),_first_name varchar(100),_last_name varchar(100),_email varchar(254),_mobile_code varchar(5),_mobile varchar(15),_password varchar(100),_customer_id int,_payment_request_id varchar(10),_login_type INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
            
		END; 
START TRANSACTION;

SELECT GENERATE_SEQUENCE('group_id') INTO @group_id;

set @get_id=0;
SET @group_usertype=1;
SET @merchant_type=2;
SET @statuss=11;
SET @merchant_status=2;
SET @package_id=2;
SET @license_package_id=0;
SET @user_id=_user_id;
SET @merchant_id='';
SET @company_name='';

if(_payment_request_id<>'')then
SET @source_type=1;
SET @status=0;

select customer_id,address,city,state,zipcode into @customer_id,@address,@city,@state,@zipcode from payment_request where payment_request_id=_payment_request_id;

update customer set customer_status=1,last_update_by=@user_id where customer_id=@customer_id;

call `save_change_details`(@user_id,@source_type,@customer_id,@status,@customer_id,_first_name,_last_name,_email,_mobile,@address,@city,@state,@zipcode);

end if;


if(@user_id='')then
if(_customer_id>0)then
select first_name,last_name into @first_name,@last_name from customer where customer_id=_customer_id;
else
SET @first_name=_first_name;
SET @last_name=_last_name;
end if;

SELECT GENERATE_SEQUENCE('User_id') INTO @user_id;

Drop TEMPORARY  TABLE  IF EXISTS temp_customer_email;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_email (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;

insert into temp_customer_email(customer_id)
select customer_id from customer where email=_email and _email<>'';

update customer c,temp_customer_email t set customer_status=2 ,user_id=@user_id where c.customer_id=t.customer_id;

INSERT INTO `user`(`email_id`, `user_id`,`password`, `first_name`, `last_name`,`mob_country_code`,`mobile_no`, `user_status`, `group_id`
, `user_group_type`, `user_type`,`login_type`, `created_by`, `created_date`, `last_updated_by`
, `last_updated_date`)
VALUES (_email,@user_id,_password,@first_name,@last_name,_mobile_code,_mobile,@statuss,@group_id,@group_usertype,0,_login_type,@user_id,CURRENT_TIMESTAMP(),@User_id,CURRENT_TIMESTAMP());


INSERT INTO `preferences`(`user_id`, `created_date`, `last_update_date`) values(@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

          
else

update user set user_status=15 ,user_group_type=@group_usertype,group_id=@group_id where user_id=@user_id;
end if;

if(_login_type=1)then
SELECT GENERATE_SEQUENCE('Merchant_id') INTO @merchant_id;

SELECT 
    individual_invoice,    bulk_invoice,    free_sms,    merchant_role,    duration,    pg_integration,    site_builder,
    brand_keyword,    total_invoices,    coupon,    supplier
INTO @individual_invoice , @bulk_invoice , @free_sms , @merchant_role , @duration , @pg_integration , @site_builder , @brand_keyword , @total_invoices , @coupon , @supplier FROM
    package
WHERE
    package_id = @package_id;
SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);

INSERT INTO `merchant`(`merchant_id`, `user_id`,`merchant_plan`,`type`,`merchant_status`,`entity_type`,`merchant_type`, `group_id`, `company_name`
,`package_expiry_date`,registration_campaign_id,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,@user_id,@package_id,2,@merchant_status,@typee,@merchant_type, @group_id,'',@end_date,0,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

INSERT INTO `merchant_billing_profile` (`merchant_id`,`profile_name`,`company_name`,`business_email`,`business_contact`,`is_default`,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,'Default profile','',_email,_mobile,1,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());


INSERT INTO `merchant_setting`(`merchant_id`,`sms_gateway_type`,`sms_gateway`,`min_transaction`,`max_transaction`,
`invoice_bulk_upload_limit`,`customer_bulk_upload_limit`,`xway_enable`,`statement_enable`,`show_ad`,`promotion_id`,
`auto_approve`,`customer_auto_generate`,`prefix`,`created_by`,`last_update_by`,`last_update_date`,`created_date`)
VALUES(@merchant_id,2,1,20.00,2000.00,250,250,0,0,0,0,1,0,'C',@user_id,@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,
`bulk_invoice`,`free_sms`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`merchant_role`,`coupon`,`supplier`,`start_date`,`end_date`,`license_key_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@package_id,'',0,@individual_invoice,@bulk_invoice,@free_sms,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@merchant_role,@coupon,@supplier,NOW(),@end_date,0,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);



 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
'Check out our video tutorials and learn at your own pace.',
 'Check out our video tutorials and learn at your own pace.', 
 'https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured', 'Videos', '0', '1', '2017-12-13', '2017-12-13 12:21:52', '2017-12-13 12:21:52');
 
 
 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
'Chat with us and our representative will guide you to setup your account.',
 'Chat with us and our representative will guide you to setup your account.', 
 'https://tawk.to/chat/574eb7843c365f2e5bd7c68f/default/?$_tawk_popout=true', 'Chat with us', '0', '1', '2017-12-13', '2017-12-13 12:21:52', '2017-12-13 12:21:52');
 
    

INSERT INTO `customer_sequence` (`merchant_id`, `val`) VALUES (@merchant_id, '0');
INSERT INTO `merchant_auto_invoice_number`(`merchant_id`,`prefix`,`val`,`type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,'EST/','0',2,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
INSERT INTO `notification_count`(`merchant_id`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@user_id,CURRENT_TIMESTAMP(),@user_id);

end if;

if(_login_type=2)then

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`merge_menu`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,1,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`merge_menu`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,9,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

else

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

end if;
select created_date into @created_date from user where user_id=@user_id;
select company_name into @company_name from merchant where merchant_id=_merchant_id;


SET @message='success';

SELECT @message AS 'Message',_email AS 'email_id', @merchant_id AS 'customer_merchant_id',@company_name as 'company_name', @user_id AS 'user_id',@merchant_id as 'merchant_id', @created_date as 'created_date';

commit;




END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_aging_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_aging_detail`(_user_id varchar(10),_from_date date , _to_date date,_customer_id varchar(10),_aging_by varchar(50))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
        show errors;
			ROLLBACK;
		END;
START TRANSACTION;
SET @aging = _aging_by;
SET @user_id = _user_id;
SET @customer_id = _customer_id;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(NOW(),'%Y-%m-%d');
set max_heap_table_size = 33554432;
Drop TEMPORARY  TABLE  IF EXISTS temp_aging_detail;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_aging_detail (
    `aging_id` INT NOT NULL AUTO_INCREMENT,
    `customer_id` INT  NULL ,
    `customer_code` varchar(45)  NULL,
    `email` varchar(250)  NULL,
    `mobile` varchar(20)  NULL,
	`invoice_number` varchar(45)  NULL default '',
	`display_invoice_no` INT null default 0,
    `customer_name` varchar(100) NULL,
    `date` datetime not null,
    `payment_request_id` char(10)  NULL,
    `status` varchar(20) NOT NULL,
    `age` varchar(20) not NULL,
    `amount` DECIMAL(11,2) NULL default 0,
    `balance_due` DECIMAL(11,2) NULL default 0,
	`currency` char(3) null,
	`currency_icon` nvarchar(5) null,
	`company_name` varchar (250) null,
    PRIMARY KEY (`aging_id`)) ENGINE=MEMORY;

    if(@customer_id<>0)then
    SET @sql=concat("insert into temp_aging_detail(payment_request_id,customer_id,date,status,amount,balance_due,age,invoice_number,currency) select payment_request_id,customer_id,",@aging,",payment_request_status,grand_total,grand_total-paid_amount,concat(DATEDIFF(NOW(),",@aging,"),' days'),invoice_number,currency from payment_request where payment_request_status in(0,4,5) and payment_request_type<>4 and user_id='",@user_id,"' and customer_id='",@customer_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and is_active=1;");
    else
    SET @sql=concat("insert into temp_aging_detail(payment_request_id,customer_id,date,status,amount,balance_due,age,invoice_number,currency) select payment_request_id,customer_id,",@aging,",payment_request_status,grand_total,grand_total-paid_amount,concat(DATEDIFF(NOW(),",@aging,"),' days'),invoice_number,currency from payment_request where payment_request_status in(0,4,5) and payment_request_type<>4 and user_id='",@user_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and is_active=1;");
    end if;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    update temp_aging_detail t,currency c set t.currency_icon=c.icon where c.code=t.currency;

    update temp_aging_detail b , customer u  set b.customer_name = concat(u.first_name,' ',u.last_name),b.customer_code=u.customer_code,b.email=u.email,b.mobile=u.mobile,b.company_name = u.company_name  where b.customer_id=u.customer_id ;
    update temp_aging_detail b , config c  set b.status = c.config_value where b.status=c.config_key and c.config_type='payment_request_status';
	select count(invoice_number) into @inv_count from temp_aging_detail where invoice_number<>'';
    if(@inv_count>0)then
		update temp_aging_detail set display_invoice_no = 1;
    end if;

    select * from temp_aging_detail where customer_id is not null ;

    Drop TEMPORARY  TABLE  IF EXISTS temp_aging_detail;
commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_aging_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_aging_summary`(_user_id varchar(10),_from_date date , _to_date date,_interval INT,_interval_of INT,_aging_by varchar(50))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
show errors;
BEGIN
ROLLBACK;
END;
START TRANSACTION;
SET @aging = _aging_by;
SET @user_id = _user_id;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');


Drop TEMPORARY  TABLE  IF EXISTS temp_load_data;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_load_data (
`load_id` INT NOT NULL AUTO_INCREMENT,
`customer_id` INT  NULL ,
`date` datetime NULL,
`last_update_date` datetime NULL,
`grand_total` DECIMAL(11,2) NULL default 0,
PRIMARY KEY (`load_id`)) ENGINE=MEMORY;




SET @sql=concat("insert into temp_load_data(customer_id,date,last_update_date,grand_total) select customer_id,due_date,last_update_date,grand_total from payment_request where payment_request_status in(0,4,5) and payment_request_type<>4 and user_id='",@user_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and is_active=1;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

Drop TEMPORARY  TABLE  IF EXISTS temp_aging_summary;

SET @aging = 'date';


CREATE TEMPORARY TABLE IF NOT EXISTS temp_aging_summary (
`aging_id` INT NOT NULL AUTO_INCREMENT,
`customer_id` INT  NULL ,
`customer_code` varchar(45)  NULL ,
`customer_name` varchar(250) NULL,
`current` DECIMAL(11,2) NULL default 0,
`total` DECIMAL(11,2) NULL default 0,
`company_name` varchar (250) null,
PRIMARY KEY (`aging_id`)) ENGINE=MEMORY;

SET  @grand_total= 0;
SET @sql=concat("select customer_id,sum(grand_total) into @customer_id,@grand_total from temp_load_data where DATE_FORMAT(",@aging,",'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') 
group by customer_id; ");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
if(@grand_total>0) then
insert into temp_aging_summary(customer_id,current)values(@customer_id,@grand_total);
end if;

SET  @interval_= _interval;
SET  @max= 0;
SET  @int_= 1;
SET  @interval_of= _interval_of;
SET  @start= 1;
SET @last='current';
SET @finalsql="select customer_name,customer_code,sum(current) as current,company_name,";
SET @totalsql="update temp_aging_summary set total=`current` +";

WHILE @int_ < @interval_ DO
SET @start_interval  = @start + @max;
SET @max=@max+@interval_of;
SET @col_name=concat(@start_interval,'_to_',@max);

SET @sql=concat('ALTER TABLE temp_aging_summary ADD  ', @col_name , ' DECIMAL(11,2) NULL default 0 AFTER ',@last);
SET @last= @col_name;
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql=concat("insert into temp_aging_summary(customer_id,", @col_name ,") select customer_id,sum(grand_total) from temp_load_data where DATE_FORMAT(",@aging,",'%Y-%m-%d') >= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL ", @max ," DAY),'%Y-%m-%d') and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL ", @start_interval ," DAY),'%Y-%m-%d')group by customer_id;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @finalsql= concat(@finalsql,"sum(`",@col_name,"`) as ",@col_name,",");
SET @totalsql= concat(@totalsql,"+",@col_name);

SET @int_ = @int_ + 1;
END WHILE;

SET @col_name=concat('above_',@max);
SET @sql=concat('ALTER TABLE temp_aging_summary ADD  ', @col_name , ' DECIMAL(11,2) NULL default 0 AFTER ',@last);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql=concat("insert into temp_aging_summary(customer_id,", @col_name ,") select customer_id,sum(grand_total) from temp_load_data where DATE_FORMAT(",@aging,",'%Y-%m-%d') < DATE_FORMAT(DATE_SUB(NOW(),INTERVAL ", @max ," DAY),'%Y-%m-%d') group by customer_id;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @finalsql= concat(@finalsql,"sum(`",@col_name,"`) as ",@col_name,",sum(`total`) as `total` from temp_aging_summary where customer_id is not null group by customer_id");
SET @totalsql= concat(@totalsql,"+",@col_name);

PREPARE stmt FROM @totalsql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
update temp_aging_summary b , customer u  set b.customer_name = concat(u.first_name,' ',u.last_name),b.customer_code=u.customer_code,b.company_name=u.company_name where b.customer_id=u.customer_id ;

PREPARE stmt FROM @finalsql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

Drop TEMPORARY  TABLE  IF EXISTS temp_load_data;
Drop TEMPORARY  TABLE  IF EXISTS temp_aging_summary;

commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_collections` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_collections`(_merchant_id varchar(10),_from_date date , _to_date date,_base_where longtext,_where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
        show errors;
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');



Drop TEMPORARY  TABLE  IF EXISTS temp_collections;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_collections (
    `pay_transaction_id` char(10) NOT NULL ,
    `customer_id` INT NULL,
    `date` DATETIME not null,
    `customer_name` varchar(100)  NULL,
    `mode` varchar(50)  NULL default '',
    `amount` DECIMAL(11,2) not null ,
    `source` varchar(20)  null,
    `offline_response_type` INT not null default 0,
    `source_type` INT not null default 0,
    `xway_source_type` INT not null default 0,
    `currency` char(3) null,
	`currency_icon` nvarchar(5) null,
	`company_name` varchar (250) null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    
   insert into temp_collections(pay_transaction_id,customer_id,date,
    mode,amount,`source`,source_type,currency) select pay_transaction_id,customer_id,created_date,'Online payment',amount,'Invoice',payment_request_type,currency from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id and 
	DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_collections(pay_transaction_id,customer_id,date,
    mode,offline_response_type,amount,`source`,source_type,currency) 
    select offline_response_id,customer_id,created_date,'Offline payment',offline_response_type,amount,'Invoice',payment_request_type,currency from offline_response where merchant_id=_merchant_id and is_active=1 and offline_response_type<>6  and transaction_status=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    insert into temp_collections(pay_transaction_id,customer_name,date,
    mode,amount,xway_source_type,currency) select xway_transaction_id,`name`,created_date,'Online payment',amount,type,currency from xway_transaction where xway_transaction_status=1 and merchant_id=_merchant_id 
    and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date ;


      update temp_collections t,currency c set t.currency_icon=c.icon where c.code=t.currency;

UPDATE temp_collections t,
    customer u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.company_name = u.company_name
WHERE
    t.customer_id = u.customer_id;
    
    UPDATE temp_collections 
SET 
    `source` = 'Event'
WHERE
		 source_type = 2;
         
   UPDATE temp_collections 
SET 
    `source` = 'Booking'
WHERE
		 source_type = 5;

UPDATE temp_collections t,
    config c 
SET 
    t.`source` = c.config_value
WHERE
		t.xway_source_type = c.config_key
        AND c.config_type = 'xway_type'
        AND xway_source_type >0;
        
        UPDATE temp_collections t,
    config c 
SET 
    t.mode = c.config_value
WHERE
		t.offline_response_type = c.config_key
        AND c.config_type = 'offline_response_type'
        AND offline_response_type <>0;
        

    SET @where=REPLACE(_where,'~',"'");
	SET @where=REPLACE(@where,'WHERE  where',"where");
   	SET @where=REPLACE(@where,'WHERE  where',"where");
    SET @basewhere=REPLACE(_base_where,'~',"'");
    SET @basewhere=REPLACE(@basewhere,'WHERE  where',"where");


    SET @count=0;    
    SET @sql=concat('select count(pay_transaction_id),sum(amount) into @count,@totalSum from temp_collections ',@basewhere);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;    
    SET @sql=concat('select count(pay_transaction_id) ,sum(amount) into @fcount,@totalSum from temp_collections ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_collections ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_collections;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_customer_balance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_customer_balance`(_user_id varchar(10),_from_date date , _to_date date,_customer_id varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
		END;
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_customer_balance;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_balance (
    `payment_request_id` varchar(10) NOT NULL ,
    `customer_id` INT NOT NULL ,
    `payment_request_type` INT NOT NULL,
    `customer_name` varchar(250) NULL,
    `invoice_balance` DECIMAL(11,2) not null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    if(_customer_id<>'')then
    insert into temp_customer_balance(payment_request_id,customer_id,invoice_balance,payment_request_type) select payment_request_id,customer_id,grand_total ,payment_request_type from payment_request where  payment_request_status in(0,4,5) and DATE_FORMAT(due_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(due_date,'%Y-%m-%d') <= @to_date  and payment_request_type <> 4 and user_id=_user_id and customer_id=_customer_id;
    else
    insert into temp_customer_balance(payment_request_id,customer_id,invoice_balance,payment_request_type) select payment_request_id,customer_id,grand_total,payment_request_type from payment_request where payment_request_status in(0,4,5) and DATE_FORMAT(due_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(due_date,'%Y-%m-%d') <= @to_date and payment_request_type <> 4  and user_id=_user_id;
    end if;

    update temp_customer_balance b , customer u  set b.customer_name = concat(u.first_name,' ',u.last_name)  where b.customer_id=u.customer_id ;
    select * from temp_customer_balance;

    Drop TEMPORARY  TABLE  IF EXISTS temp_customer_balance;

commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_dispute_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_dispute_details`(_merchant_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;

		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_dispute;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_dispute (
    `dispute_id` INT NOT NULL ,
    `transaction_id` varchar(10) NOT NULL ,
    `created_date` datetime null,
    `transaction_date` datetime null,
    `customer_id` INT  NULL,
    `customer_code` varchar(45)  NULL,
    `customer_name` varchar(100)  NULL,
    `transaction_amount` decimal(11,2)  NULL,
    `dispute_status` INT NULL,
    `dispute_date` datetime null,
    `reason` varchar(250)  NULL,
    `company_name` varchar (250) null,
    PRIMARY KEY (`dispute_id`)) ENGINE=MEMORY;
    
    
    insert into temp_payment_dispute(dispute_id,transaction_id,created_date,transaction_amount,dispute_status,dispute_date,reason)
	select id,transaction_id,created_date,transaction_amount,dispute_status,dispute_date,reason from dispute_request where merchant_id=_merchant_id 
    and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date;
    
    UPDATE temp_payment_dispute t,
    payment_transaction u 
SET 
    t.customer_id = u.customer_id,
    t.transaction_date=u.created_date
WHERE
    t.transaction_id = u.pay_transaction_id;
    
UPDATE temp_payment_dispute t,
    customer u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.customer_code,
    t.company_name = u.company_name
WHERE
    t.customer_id = u.customer_id;
    
    UPDATE temp_payment_dispute t,
    xway_transaction u
SET 
    t.customer_name = u.name,
    t.customer_code = u.udf1,
    t.transaction_date=u.created_date
WHERE
    t.transaction_id = u.xway_transaction_id;
    

    select * from temp_payment_dispute;
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_dispute;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_expected_payment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_expected_payment`(_user_id varchar(10),_from_date date , _to_date date,_interval INT,_interval_of INT,_aging_by varchar(50))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
show errors;
BEGIN
ROLLBACK;
END;
START TRANSACTION;
SET @aging = _aging_by;
SET @user_id = _user_id;


SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');


Drop TEMPORARY  TABLE  IF EXISTS temp_load_data;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_load_data (
`load_id` INT NOT NULL AUTO_INCREMENT,
`customer_id` INT NULL ,
`date` datetime NULL,
`last_update_date` datetime NULL,
`grand_total` DECIMAL(11,2) NULL default 0,
PRIMARY KEY (`load_id`)) ENGINE=MEMORY;



SET @sql=concat("insert into temp_load_data(customer_id,date,last_update_date,grand_total) select customer_id,due_date,last_update_date,grand_total from payment_request where payment_request_status in(0,4,5) and payment_request_type <> 4 and merchant_id='",@user_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and is_active=1;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

Drop TEMPORARY  TABLE  IF EXISTS temp_expected_payment;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_expected_payment (
`aging_id` INT NOT NULL AUTO_INCREMENT,
`customer_id` INT  NULL ,
`customer_code` varchar(45)  NULL ,
`customer_name` varchar(250) NULL,
`current` DECIMAL(11,2) NULL default 0,
`total` DECIMAL(11,2) NULL default 0,
`company_name` varchar (250) null,
PRIMARY KEY (`aging_id`)) ENGINE=MEMORY;

SET @aging = 'date';

SET  @grand_total= 0;
SET @sql=concat("insert into temp_expected_payment(customer_id,current) select tc.customer_id,sum(grand_total) from temp_load_data tc where DATE_FORMAT(",@aging,",'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') group by tc.customer_id;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



SET  @interval_= _interval;
SET  @max= 0;
SET  @int_= 1;
SET  @interval_of= _interval_of;
SET  @start= 1;
SET @last='current';
SET @finalsql="select customer_name,customer_code,sum(current) as current,company_name,";
SET @totalsql="update temp_expected_payment set total=`current` +";

WHILE @int_ < @interval_ DO
SET @start_interval  = @start + @max;
SET @max=@max+@interval_of;
SET @col_name=concat(@start_interval,'_to_',@max);

SET @sql=concat('ALTER TABLE temp_expected_payment ADD  ', @col_name , ' DECIMAL(11,2) NULL default 0 AFTER ',@last);
SET @last= @col_name;
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql=concat("insert into temp_expected_payment(customer_id,", @col_name ,") select customer_id,sum(grand_total) from temp_load_data where 
DATE_FORMAT(",@aging,",'%Y-%m-%d') <= DATE_FORMAT(DATE_ADD(NOW(),INTERVAL ", @max ," DAY),'%Y-%m-%d') and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= DATE_FORMAT(DATE_ADD(NOW(),INTERVAL ", @start_interval ," DAY),'%Y-%m-%d') group by customer_id;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @finalsql= concat(@finalsql,"sum(`",@col_name,"`) as ",@col_name,",");
SET @totalsql= concat(@totalsql,"+",@col_name);

SET @int_ = @int_ + 1;
END WHILE;
SET @col_name=concat('above_',@max);
SET @sql=concat('ALTER TABLE temp_expected_payment ADD  ', @col_name , ' DECIMAL(11,2) NULL default 0 AFTER ',@last);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql=concat("insert into temp_expected_payment(customer_id,", @col_name ,") select customer_id,sum(grand_total) from temp_load_data where DATE_FORMAT(",@aging,",'%Y-%m-%d') > DATE_FORMAT(DATE_ADD(NOW(),INTERVAL ", @max ," DAY),'%Y-%m-%d') group by customer_id;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @finalsql= concat(@finalsql,"sum(`",@col_name,"`) as ",@col_name,",sum(`total`) as `total` from temp_expected_payment where customer_id is not null group by customer_id");
SET @totalsql= concat(@totalsql,"+",@col_name);

PREPARE stmt FROM @totalsql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
update temp_expected_payment b , customer u  set b.customer_name = concat(u.first_name,' ',u.last_name),b.customer_code=u.customer_code,b.company_name=u.company_name where b.customer_id=u.customer_id ;

PREPARE stmt FROM @finalsql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

Drop TEMPORARY  TABLE  IF EXISTS temp_load_data;
Drop TEMPORARY  TABLE  IF EXISTS temp_expected_payment;
commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_invoice_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_invoice_details`(_merchant_id varchar(10),_from_date date , _to_date date,_invoice_type INT,
_billing_cycle_id varchar(11),_customer_id int(11), _status varchar(50),_aging_by varchar(50),_column_name longtext,_is_setteled INT,_franchise_id INT,_vendor_id INT,_where longtext,_order longtext,_limit longtext)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @merchant_id=_merchant_id;
SET @billing_cycle_id=_billing_cycle_id;
SET @customer_id=_customer_id;
SET @aging=_aging_by;
SET @status=_status;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

if(_invoice_type=2)then
SET @number_type='estimate_number';
else
SET @number_type='invoice_number';
end if;
set max_heap_table_size = 33554432;
Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_details (
	`invoice_id` varchar(10) NOT NULL ,
	`customer_id` INT NOT NULL,
	`customer_code` varchar(45)  NULL,
	`invoice_number` varchar(45)  NULL default '',
    `customer_group` varchar(45)  NULL default '',
  	`payment_request_type` INT  NULL default 0,
   	`__Type` varchar(20)  NULL default '',
	`display_invoice_no` INT null default 0,
	`invoice_amount` DECIMAL(11,2) not null ,
    `paid_amount` DECIMAL(11,2) not null ,
	`status` varchar(50) not null,
	`billing_cycle_id` varchar(10) NOT NULL,
	`cycle_name` varchar(250)  null,
	`sent_date` datetime not null,
	`bill_date` datetime not null,
	`due_date` DATETIME not null,
	`customer_name` varchar (100) null,
    `franchise_id` INT NULL default 0,
    `vendor_id` INT NULL default 0,
    `billing_profile_id` INT NULL default 0,
	`currency` char(3) null,
	`currency_icon` nvarchar(5) null,
    `merchant_gst_number` char (15) null,
    `user_id` varchar(10) NULL,
    `offline_response_type` INT null,
	`__Email` varchar (250) null,
	`__Mobile` varchar (20) null,
    `__Address` varchar (250) null,
    `__City` varchar (45) null,
    `__State` varchar (45) null,
    `__Zipcode` varchar (20) null,
    `created_by` varchar (10) null,
    `__Settled_by` varchar (100) null,
    `__Created_by` varchar (100) null,
    `__Franchise_name` varchar (100) null,
    `__Vendor_name` varchar (100) null,
    `company_name` varchar (250) null,
	PRIMARY KEY (`invoice_id`),INDEX `customer_idx` (`customer_id` ASC),INDEX `billing_cycle_idx` (`billing_cycle_id` ASC)) ENGINE=MEMORY;

SET @franchise_search='';
if(_franchise_id>0)then
SET @franchise_search=concat(' and franchise_id=',_franchise_id);
end if;

if(_vendor_id>0)then
SET @franchise_search=concat(@franchise_search,' and vendor_id=',_vendor_id);
end if;

    if(_billing_cycle_id <>'') then

        if(_status<>'') then
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where is_active=1 and merchant_id='",@merchant_id,"' and invoice_type=",_invoice_type," and payment_request_type <> 4 and billing_cycle_id='",@billing_cycle_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,")  ",@franchise_search," and is_active=1;");
        else
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and billing_cycle_id='",@billing_cycle_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1;");
        end if;

    else
        if(_status<>'') then
               SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
                select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,") ",@franchise_search," and is_active=1;");
                else
               SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
                select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1; ");
        end if;
    end if;


    if(_customer_id<>0) then
        if(_status<>'') then
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and customer_id='",@customer_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,") ",@franchise_search," and is_active=1;");
        else
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and customer_id='",@customer_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1;");
        end if;
    end if;

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

SET @last='customer_name';
WHILE _column_name != '' > 0 DO

        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        if(@column_name<>'Settled by') then
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_invoice_details ADD  ', @col_name , ' varchar(250) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
        SET @sql=concat('update temp_invoice_details t , invoice_column_values i ,invoice_column_metadata m  set t.',@col_name,' =i.value  where t.invoice_id=i.payment_request_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
		
        SET @sql=concat('update temp_invoice_details t , customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and t.',@col_name,' is null and m.column_name=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    end if;
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;

	update temp_invoice_details r,offline_response c set r.offline_response_type=c.offline_response_type where  r.status=2 and r.invoice_id=c.payment_request_id and c.merchant_id=_merchant_id;

	#if(_is_setteled=1)then
		update temp_invoice_details r,user u set  __Settled_by=concat(u.first_name,' ',u.last_name) where u.user_id=r.created_by and r.status=2;
	#end if;
UPDATE temp_invoice_details r,  customer u 
SET 
    r.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_group = u.customer_group,
    r.customer_code = u.customer_code,
    r.__Address = u.address,
    r.__City = u.city,
    r.__State = u.state,
    r.__Zipcode = u.zipcode,
	r.__Mobile = u.mobile,
    r.__Email = u.email,
    r.company_name = u.company_name
WHERE
    r.customer_id = u.customer_id;

UPDATE temp_invoice_details set `status`=0 where `status`=5;

UPDATE temp_invoice_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.status = c.config_key
        AND c.config_type = 'payment_request_status';
    
UPDATE temp_invoice_details r,
    config c 
SET 
    r.__Type = c.config_value
WHERE
    r.payment_request_type = c.config_key
        AND c.config_type = 'payment_request_type';

UPDATE temp_invoice_details t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.offline_response_type = c.config_key
        AND c.config_type = 'offline_response_type'
        AND offline_response_type  is not null;
        
UPDATE temp_invoice_details r,  billing_cycle_detail b SET r.cycle_name = b.cycle_name WHERE r.billing_cycle_id = b.billing_cycle_id;
update temp_invoice_details t,currency c set t.currency_icon=c.icon where c.code=t.currency;

	SELECT COUNT(invoice_number) INTO @inv_count FROM  temp_invoice_details WHERE  invoice_number <> '';
    if(@inv_count>0)then
		update temp_invoice_details set display_invoice_no = 1;
    end if;

	UPDATE temp_invoice_details t, user u SET  t.__Created_by = CONCAT(u.first_name, ' ', u.last_name) WHERE  t.created_by = u.user_id;
    
    UPDATE temp_invoice_details t,  franchise u SET t.__Franchise_name = u.franchise_name WHERE  t.franchise_id = u.franchise_id;
    
  UPDATE temp_invoice_details t, vendor u SET t.__Vendor_name = u.vendor_name WHERE  t.vendor_id = u.vendor_id;
    
SET @where=REPLACE(_where,'~',"'");

	SET @count=0;    
    SET @sql=concat('select count(invoice_id) into @count from temp_invoice_details ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;   
    SET @sql=concat('select count(invoice_id),sum(invoice_amount) into @fcount,@totalSum from temp_invoice_details ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_invoice_details ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_details;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_invoice_details2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_invoice_details2`(_merchant_id varchar(10),_from_date date , _to_date date,_invoice_type INT,
_billing_cycle_id varchar(11),_customer_id int(11), _status varchar(50),_aging_by varchar(50),_column_name longtext,_is_setteled INT,_franchise_id INT,_vendor_id INT,_where longtext,_order longtext,_limit longtext)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @merchant_id=_merchant_id;
SET @billing_cycle_id=_billing_cycle_id;
SET @customer_id=_customer_id;
SET @aging=_aging_by;
SET @status=_status;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

if(_invoice_type=2)then
SET @number_type='estimate_number';
else
SET @number_type='invoice_number';
end if;
set max_heap_table_size = 33554432;
Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_invoice_details (
	`invoice_id` varchar(10) NOT NULL ,
	`customer_id` INT NOT NULL,
	`customer_code` varchar(45)  NULL,
	`invoice_number` varchar(45)  NULL default '',
    `customer_group` varchar(45)  NULL default '',
  	`payment_request_type` INT  NULL default 0,
   	`__Type` varchar(20)  NULL default '',
	`display_invoice_no` INT null default 0,
	`invoice_amount` DECIMAL(11,2) not null ,
    `paid_amount` DECIMAL(11,2) not null ,
	`status` varchar(50) not null,
	`billing_cycle_id` varchar(10) NOT NULL,
	`cycle_name` varchar(250)  null,
	`sent_date` datetime not null,
	`bill_date` datetime not null,
	`due_date` DATETIME not null,
	`customer_name` varchar (100) null,
    `franchise_id` INT NULL default 0,
    `vendor_id` INT NULL default 0,
    `billing_profile_id` INT NULL default 0,
	`currency` char(3) null,
	`currency_icon` nvarchar(5) null,
    `merchant_gst_number` char (15) null,
    `user_id` varchar(10) NULL,
    `offline_response_type` INT null,
	`__Email` varchar (250) null,
	`__Mobile` varchar (20) null,
    `__Address` varchar (250) null,
    `__City` varchar (45) null,
    `__State` varchar (45) null,
    `__Zipcode` varchar (20) null,
    `created_by` varchar (10) null,
    `__Settled_by` varchar (100) null,
    `__Created_by` varchar (100) null,
    `__Franchise_name` varchar (100) null,
    `__Vendor_name` varchar (100) null,
    `company_name` varchar (250) null,
	PRIMARY KEY (`invoice_id`),INDEX `customer_idx` (`customer_id` ASC),INDEX `billing_cycle_idx` (`billing_cycle_id` ASC)) ENGINE=MEMORY;

SET @franchise_search='';
if(_franchise_id>0)then
SET @franchise_search=concat(' and franchise_id=',_franchise_id);
end if;

if(_vendor_id>0)then
SET @franchise_search=concat(@franchise_search,' and vendor_id=',_vendor_id);
end if;

    if(_billing_cycle_id <>'') then

        if(_status<>'') then
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where is_active=1 and merchant_id='",@merchant_id,"' and invoice_type=",_invoice_type," and payment_request_type <> 4 and billing_cycle_id='",@billing_cycle_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,")  ",@franchise_search," and is_active=1;");
        else
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and billing_cycle_id='",@billing_cycle_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1;");
        end if;

    else
        if(_status<>'') then
               SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
                select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,") ",@franchise_search," and is_active=1;");
                else
               SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
                select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1; ");
        end if;
    end if;


    if(_customer_id<>0) then
        if(_status<>'') then
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and customer_id='",@customer_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and payment_request_status in (",@status,") ",@franchise_search," and is_active=1;");
        else
            SET @sql=concat("insert into temp_invoice_details(invoice_id,payment_request_type,billing_cycle_id,customer_id,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,franchise_id,vendor_id,billing_profile_id,merchant_gst_number,paid_amount,currency) 
            select payment_request_id,payment_request_type,billing_cycle_id,customer_id,grand_total,payment_request_status,created_date,bill_date,due_date,",@number_type,", created_by,franchise_id,vendor_id,billing_profile_id,gst_number,paid_amount,currency from payment_request where merchant_id='",@merchant_id,"' and is_active=1 and invoice_type=",_invoice_type," and payment_request_type <> 4 and customer_id='",@customer_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"'  and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' ",@franchise_search," and is_active=1;");
        end if;
    end if;

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

SET @last='customer_name';
WHILE _column_name != '' > 0 DO

        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        if(@column_name<>'Settled by') then
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_invoice_details ADD  ', @col_name , ' varchar(250) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
        SET @sql=concat('update temp_invoice_details t , invoice_column_values i ,invoice_column_metadata m  set t.',@col_name,' =i.value  where t.invoice_id=i.payment_request_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
		
        SET @sql=concat('update temp_invoice_details t , customer_column_values i ,customer_column_metadata m  set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=m.column_id and t.',@col_name,' is null and m.column_name=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    end if;
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;

	update temp_invoice_details r,offline_response c set r.offline_response_type=c.offline_response_type where  r.status=2 and r.invoice_id=c.payment_request_id and c.merchant_id=_merchant_id;

	#if(_is_setteled=1)then
		update temp_invoice_details r,user u set  __Settled_by=concat(u.first_name,' ',u.last_name) where u.user_id=r.created_by ;
	#end if;
UPDATE temp_invoice_details r,  customer u 
SET 
    r.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_group = u.customer_group,
    r.customer_code = u.customer_code,
    r.__Address = u.address,
    r.__City = u.city,
    r.__State = u.state,
    r.__Zipcode = u.zipcode,
	r.__Mobile = u.mobile,
    r.__Email = u.email,
    r.company_name = u.company_name
WHERE
    r.customer_id = u.customer_id;

UPDATE temp_invoice_details set `status`=0 where `status`=5;

UPDATE temp_invoice_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.status = c.config_key
        AND c.config_type = 'payment_request_status';
    
UPDATE temp_invoice_details r,
    config c 
SET 
    r.__Type = c.config_value
WHERE
    r.payment_request_type = c.config_key
        AND c.config_type = 'payment_request_type';

UPDATE temp_invoice_details t,
    config c 
SET 
    t.status = c.config_value
WHERE
    t.offline_response_type = c.config_key
        AND c.config_type = 'offline_response_type'
        AND offline_response_type  is not null;
        
UPDATE temp_invoice_details r,  billing_cycle_detail b SET r.cycle_name = b.cycle_name WHERE r.billing_cycle_id = b.billing_cycle_id;
update temp_invoice_details t,currency c set t.currency_icon=c.icon where c.code=t.currency;

	SELECT COUNT(invoice_number) INTO @inv_count FROM  temp_invoice_details WHERE  invoice_number <> '';
    if(@inv_count>0)then
		update temp_invoice_details set display_invoice_no = 1;
    end if;

	UPDATE temp_invoice_details t, user u SET  t.__Created_by = CONCAT(u.first_name, ' ', u.last_name) WHERE  t.created_by = u.user_id;
    
    UPDATE temp_invoice_details t,  franchise u SET t.__Franchise_name = u.franchise_name WHERE  t.franchise_id = u.franchise_id;
    
  UPDATE temp_invoice_details t, vendor u SET t.__Vendor_name = u.vendor_name WHERE  t.vendor_id = u.vendor_id;
    
SET @where=REPLACE(_where,'~',"'");

	SET @count=0;    
    SET @sql=concat('select count(invoice_id) into @count from temp_invoice_details ');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;   
    SET @sql=concat('select count(invoice_id),sum(invoice_amount) into @fcount,@totalSum from temp_invoice_details ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_invoice_details ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    Drop TEMPORARY  TABLE  IF EXISTS temp_invoice_details;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_ledger` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_ledger`(_merchant_id varchar(10),_from_date DATE ,_to_date DATE,_customer_id INT,_franchise_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 
        
Drop TEMPORARY  TABLE  IF EXISTS tmpsumledger;

CREATE TEMPORARY TABLE IF NOT EXISTS tmpsumledger ( 
`id` INT NOT NULL auto_increment , 
`cr_dr` varchar(10) NOT NULL, 
`debit` DECIMAL(11,2),
`credit` DECIMAL(11,2),
`tdr` DECIMAL(11,2),
`st` DECIMAL(11,2),
PRIMARY KEY (`id`)) ENGINE=MEMORY;



SELECT 
    user_id
INTO @user_id FROM
    merchant
WHERE
    merchant_id = _merchant_id;

if(_customer_id=0)then

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
select 'dr',sum(absolute_cost),0,0,0 from payment_request where user_id=@user_id and payment_request_type <> 4 
and DATE_FORMAT(bill_date,'%Y-%m-%d') < _from_date and is_active=1 and payment_request_status<>3;


insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT'dr',sum(absolute_cost),0,0,0 FROM `xway_transaction`
where merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') < _from_date  and xway_transaction_status=1;


insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`captured`),substring(sum(`tdr`),2),substring(sum(`service_tax`),2) FROM `payment_transaction_settlement`
where merchant_id=@user_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') < _from_date;

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`amount`),0,0 FROM `offline_response`
where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m-%d') < _from_date and is_active=1;

else

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
select 'dr',sum(absolute_cost),0,0,0 from payment_request where user_id=@user_id and payment_request_type <> 4 
and DATE_FORMAT(bill_date,'%Y-%m-%d') < _from_date and is_active=1 and payment_request_status<>3 and customer_id=_customer_id;

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`captured`),substring(sum(`tdr`),2),substring(sum(`service_tax`),2) FROM `payment_transaction_settlement` s 
inner join payment_transaction t on s.transaction_id=t.pay_transaction_id
where s.merchant_id=@user_id and DATE_FORMAT(s.transaction_date,'%Y-%m-%d') < _from_date and t.customer_id=_customer_id; 

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`amount`),0,0 FROM `offline_response`
where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m-%d') < _from_date and is_active=1 and customer_id=_customer_id;

end if;
SELECT 
    SUM(debit) - SUM(credit)
INTO @opening_balance FROM
    tmpsumledger;




Drop TEMPORARY  TABLE  IF EXISTS tmpledger;

CREATE TEMPORARY TABLE IF NOT EXISTS tmpledger ( 
`id` INT NOT NULL auto_increment , 
`customer_id` INT NULL,  
`franchise_id` INT NULL default 0,  
`payment_request_id` varchar(10) NULL, 
`customer_detail` varchar(250) NULL,  
`customer_code` varchar(45) NULL,  
`cr_dr` varchar(10) NULL, 
`invoice_number` varchar(50) NULL default '', 
`type` varchar(20) null,
`invoice_type` varchar(50) null,
`debit` DECIMAL(11,2) NULL,
`credit` DECIMAL(11,2) NULL,
`tdr` DECIMAL(11,2) NULL,
`st` DECIMAL(11,2) NULL,
`date` DATE null,
PRIMARY KEY (`id`)) ENGINE=MEMORY;

insert into tmpledger (debit,`date`) values(@opening_balance,_from_date);

if(_customer_id=0)then

insert into tmpledger (customer_id,payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
select customer_id,payment_request_id,'dr',invoice_number,payment_request_type,absolute_cost,0,0,0,bill_date from payment_request where user_id=@user_id and payment_request_type <> 4 
and DATE_FORMAT(bill_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= _to_date and is_active=1 and payment_request_status<>3;


insert into tmpledger (customer_detail,customer_code,cr_dr,invoice_number,`type`,debit,credit,tdr,st,`date`,franchise_id)
SELECT `name`,udf1,'dr',reference_no,'Website',absolute_cost,0,0,0,`created_date`,franchise_id FROM `xway_transaction`
where merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date and xway_transaction_status=1;


insert into tmpledger (payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
SELECT `payment_request_id`,'cr',transaction_id,'',0,captured,substring(`tdr`,2),substring(`service_tax`,2),`transaction_date` FROM `payment_transaction_settlement`
where merchant_id=@user_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= _to_date ;

insert into tmpledger (payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
SELECT `payment_request_id`,'cr',offline_response_id,'',0,`amount`,0,0,`settlement_date` FROM `offline_response`
where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date and is_active=1;

else

insert into tmpledger (customer_id,payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
select customer_id,payment_request_id,'dr',invoice_number,payment_request_type,absolute_cost,0,0,0,bill_date from payment_request where user_id=@user_id and payment_request_type <> 4
and DATE_FORMAT(bill_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= _to_date and is_active=1 and payment_request_status<>3 and customer_id=_customer_id; 


insert into tmpledger (payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
SELECT s.payment_request_id,'cr',transaction_id,'',0,captured,substring(`tdr`,2),substring(`service_tax`,2),`transaction_date` FROM `payment_transaction_settlement` s 
inner join payment_transaction t on s.transaction_id=t.pay_transaction_id
where s.merchant_id=@user_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= _to_date and t.customer_id=_customer_id; 

insert into tmpledger (payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
SELECT `payment_request_id`,'cr',offline_response_id,'',0,`amount`,0,0,`settlement_date` FROM `offline_response`
where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date and is_active=1 and customer_id=_customer_id; 

end if;

UPDATE tmpledger s,
    xway_transaction c 
SET 
    s.customer_detail = c.name,
    s.customer_code = c.udf1,
    s.type = 'Website',
    s.invoice_number = c.reference_no
WHERE
    s.invoice_number = c.xway_transaction_id;

UPDATE tmpledger s,
    payment_request c 
SET 
    s.invoice_type = c.payment_request_type,
    s.customer_id = c.customer_id,
    s.franchise_id = c.franchise_id,
    s.invoice_number = c.invoice_number
WHERE
    s.payment_request_id = c.payment_request_id;
    
    
UPDATE tmpledger s,
    event_request c 
SET 
    s.invoice_type = 2,
    s.franchise_id = c.franchise_id
WHERE
    s.payment_request_id = c.event_request_id;
    
    
UPDATE tmpledger s,
    customer c 
SET 
    s.customer_detail = CONCAT(c.first_name,
            ' ',
            c.last_name,
            ' ',
            ' (',
            c.customer_code,
            ')'),
            s.customer_code = c.customer_code
WHERE
    s.customer_id = c.customer_id;
UPDATE tmpledger s 
SET 
    s.invoice_number = payment_request_id
WHERE
    s.invoice_number = '';

UPDATE tmpledger s,
    config c 
SET 
    s.type = c.config_value
WHERE
    s.invoice_type = c.config_key
        AND c.config_type = 'payment_request_type';
if(_franchise_id>0)then
SELECT 
    date,
    customer_detail,
    customer_code,
    cr_dr,
    invoice_number,
    type,
    debit,
    credit,
    tdr,
    st
FROM
    tmpledger where franchise_id=_franchise_id
ORDER BY `date` , id;
else
SELECT 
    date,
    customer_detail,
    customer_code,
    cr_dr,
    invoice_number,
    type,
    debit,
    credit,
    tdr,
    st
FROM
    tmpledger
ORDER BY `date` , id;
         end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_payment_received` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_payment_received`(_merchant_id varchar(10),_from_date date , _to_date date,_customer_id INT,_column_name longtext,_base_where longtext,_where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
        show errors;
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');


SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_received (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NOT NULL ,
    `patron_id` varchar(10) NULL,
    `customer_id` INT NULL,
    `customer_group` varchar(45)  NULL default '',
    `customer_code` varchar(45)  NULL,
    `invoice_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 0,
    `amount` DECIMAL(11,2) not null ,
    `late_payment` varchar(5) not null DEFAULT '0',
    `received_cost` DECIMAL(11,2) not null DEFAULT '0.00',
    `deduct_amount` DECIMAL(11,2) not null DEFAULT '0.00',
    `status` varchar(50) not null,
    `billing_cycle_id` varchar(10) NULL,
	`cycle_name` varchar(250)  null,
    `date` DATETIME not null,
    `customer_name` varchar (100) null,
    `offline_response_type` INT not null default 0,
    `ref_no` varchar (45) null,
     `currency` char(3) null,
	`currency_icon` nvarchar(5) null,
	`franchise_id` INT  NULL default 0,
    `vendor_id` INT  NULL default 0,
    `__Email` varchar (250) null,
	`__Mobile` varchar (20) null,
	`__Narrative` varchar (500) null,
    `__Franchise_name` varchar (100) null,
    `__Vendor_name` varchar (100) null,
    `company_name` varchar (250) null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    
    if(_customer_id<>0) then
   insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,late_payment,status,date,offline_response_type,ref_no,__Narrative,currency) select pay_transaction_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,late_payment,
    payment_mode,created_date,0,pg_ref_no,narrative,currency from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id and 
customer_id=_customer_id  and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,status,date,offline_response_type,__Narrative,currency) 
    select offline_response_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,offline_response_type,created_date,offline_response_type,narrative,currency from offline_response where merchant_id=_merchant_id and customer_id=_customer_id and is_active=1 and offline_response_type<>6  and transaction_status=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    else
    
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,late_payment,status,date,offline_response_type,ref_no,__Narrative,currency) select pay_transaction_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,late_payment,
    payment_mode,created_date,0,pg_ref_no,narrative,currency from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,status,date,offline_response_type,__Narrative,currency) 
    select offline_response_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,offline_response_type,created_date,offline_response_type,narrative,currency from offline_response where merchant_id=_merchant_id and is_active=1 and offline_response_type<>6  and transaction_status=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    end if;
SET @last='ref_no';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_payment_received ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_payment_received t , invoice_column_values i ,invoice_column_metadata m  set t.',@col_name,' =i.value  where t.payment_request_id=i.payment_request_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
	
      
UPDATE temp_payment_received t,
    customer u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_group = u.customer_group,
    t.customer_code = u.customer_code,
    t.__Mobile = u.mobile,
    t.__Email = u.email,
    t.company_name = u.company_name
WHERE
    t.customer_id = u.customer_id;
UPDATE temp_payment_received t,
    user u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.user_id,
    t.__Email = u.email_id
WHERE
    t.patron_id = u.user_id
        AND customer_id = 0;
    
    UPDATE temp_payment_received 
SET 
    late_payment = 'Yes'
WHERE
         late_payment = '1';
         
         UPDATE temp_payment_received 
SET 
    late_payment = 'No'
WHERE
         late_payment = '0';
    
UPDATE temp_payment_received t,
    payment_request p 
SET 
    received_cost = p.grand_total - p.convenience_fee
WHERE
    t.payment_request_id = p.payment_request_id
        AND t.offline_response_type = 0;
UPDATE temp_payment_received t,
    payment_request p 
SET 
    t.billing_cycle_id = p.billing_cycle_id,
    t.invoice_number = p.invoice_number,
    t.franchise_id = p.franchise_id,
    t.vendor_id=p.vendor_id,
    t.__Narrative=p.narrative
WHERE
    t.payment_request_id = p.payment_request_id;
    
    
UPDATE temp_payment_received t,
    event_request p 
SET 
    t.cycle_name = p.event_name,t.franchise_id=p.franchise_id,t.vendor_id=p.vendor_id
WHERE
    t.payment_request_id = p.event_request_id;
UPDATE temp_payment_received 
SET 
    received_cost = amount
WHERE
    offline_response_type <> 0;
    
	UPDATE temp_payment_received t,
    offline_response c 
SET 
    t.ref_no = c.bank_transaction_no
WHERE
    t.offline_response_type > 0
        AND t.pay_transaction_id = c.offline_response_id;

UPDATE temp_payment_received t,
    offline_response c 
SET 
    t.ref_no = c.cheque_no
WHERE
    t.offline_response_type =2
        AND t.pay_transaction_id = c.offline_response_id;
        
      update temp_payment_received t,currency c set t.currency_icon=c.icon where c.code=t.currency;


UPDATE temp_payment_received t,
    config c 
SET 
    t.status = c.config_value
WHERE
		t.status = c.config_key
        AND c.config_type = 'offline_response_type'
        AND offline_response_type <>0
        AND offline_response_type is not null;
        
UPDATE temp_payment_received r,
    billing_cycle_detail b 
SET 
    r.cycle_name = b.cycle_name
WHERE
    r.billing_cycle_id = b.billing_cycle_id;
    
    
UPDATE temp_payment_received t,
    franchise u 
SET 
    t.__Franchise_name = u.franchise_name
WHERE
    t.franchise_id = u.franchise_id;
    
UPDATE temp_payment_received t,
    vendor u 
SET 
    t.__Vendor_name = u.vendor_name
WHERE
    t.vendor_id = u.vendor_id;

	SELECT 
    COUNT(invoice_number)
INTO @inv_count FROM
    temp_payment_received
WHERE
    invoice_number <> '';
    if(@inv_count>0)then
		update temp_payment_received set display_invoice_no = 1;
    end if;

    SET @where=REPLACE(_where,'~',"'");
	SET @where=REPLACE(@where,'WHERE  where',"where");
   	SET @where=REPLACE(@where,'WHERE  where',"where");
    SET @basewhere=REPLACE(_base_where,'~',"'");
    SET @basewhere=REPLACE(@basewhere,'WHERE  where',"where");


    SET @count=0;    
    SET @sql=concat('select count(pay_transaction_id),sum(amount) into @count,@totalSum from temp_payment_received ',@basewhere);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;    
    SET @sql=concat('select count(pay_transaction_id) ,sum(amount) into @fcount,@totalSum from temp_payment_received ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_payment_received ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_payment_received2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_payment_received2`(_merchant_id varchar(10),_from_date date , _to_date date,_customer_id INT,_column_name longtext,_base_where longtext,_where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
        show errors;
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_received (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NOT NULL ,
    `patron_id` varchar(10) NULL,
    `customer_id` INT NULL,
    `customer_group` varchar(45)  NULL default '',
    `customer_code` varchar(45)  NULL,
    `invoice_number` varchar(45)  NULL default '',
    `display_invoice_no` INT null default 0,
    `amount` DECIMAL(11,2) not null ,
    `late_payment` varchar(5) not null DEFAULT '0',
    `received_cost` DECIMAL(11,2) not null DEFAULT '0.00',
    `deduct_amount` DECIMAL(11,2) not null DEFAULT '0.00',
    `status` varchar(50) not null,
    `billing_cycle_id` varchar(10) NULL,
	`cycle_name` varchar(250)  null,
    `date` DATETIME not null,
    `customer_name` varchar (100) null,
    `offline_response_type` INT not null default 0,
    `ref_no` varchar (45) null,
     `currency` char(3) null,
	`currency_icon` nvarchar(5) null,
	`franchise_id` INT  NULL default 0,
    `vendor_id` INT  NULL default 0,
    `__Email` varchar (250) null,
	`__Mobile` varchar (20) null,
	`__Narrative` varchar (500) null,
    `__Franchise_name` varchar (100) null,
    `__Vendor_name` varchar (100) null,
    `company_name` varchar (250) null,
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    
    if(_customer_id<>0) then
   insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,late_payment,status,date,offline_response_type,ref_no,__Narrative,currency) select pay_transaction_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,late_payment,
    payment_mode,created_date,0,pg_ref_no,narrative,currency from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id and 
customer_id=_customer_id  and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,status,date,offline_response_type,__Narrative,currency) 
    select offline_response_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,offline_response_type,created_date,offline_response_type,narrative,currency from offline_response where merchant_id=_merchant_id and customer_id=_customer_id and is_active=1 and offline_response_type<>6  and transaction_status=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    else
    
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,late_payment,status,date,offline_response_type,ref_no,__Narrative,currency) select pay_transaction_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,late_payment,
    payment_mode,created_date,0,pg_ref_no,narrative,currency from payment_transaction where payment_transaction_status=1 and merchant_id=_merchant_id  and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date ;
     
    insert into temp_payment_received(pay_transaction_id,payment_request_id,customer_id,patron_id,
    amount,deduct_amount,status,date,offline_response_type,__Narrative,currency) 
    select offline_response_id,payment_request_id,customer_id,patron_user_id,amount,deduct_amount,offline_response_type,created_date,offline_response_type,narrative,currency from offline_response where merchant_id=_merchant_id and is_active=1 and offline_response_type<>6  and transaction_status=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date ;
    
    end if;
SET @last='ref_no';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_payment_received ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_payment_received t , invoice_column_values i ,invoice_column_metadata m  set t.',@col_name,' =i.value  where t.payment_request_id=i.payment_request_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
	
      
UPDATE temp_payment_received t,
    customer u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_group = u.customer_group,
    t.customer_code = u.customer_code,
    t.__Mobile = u.mobile,
    t.__Email = u.email,
    t.company_name = u.company_name
WHERE
    t.customer_id = u.customer_id;
UPDATE temp_payment_received t,
    user u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.user_id,
    t.__Email = u.email_id
WHERE
    t.patron_id = u.user_id
        AND customer_id = 0;
    
    UPDATE temp_payment_received 
SET 
    late_payment = 'Yes'
WHERE
         late_payment = '1';
         
         UPDATE temp_payment_received 
SET 
    late_payment = 'No'
WHERE
         late_payment = '0';
    
UPDATE temp_payment_received t,
    payment_request p 
SET 
    received_cost = p.grand_total - p.convenience_fee
WHERE
    t.payment_request_id = p.payment_request_id
        AND t.offline_response_type = 0;
UPDATE temp_payment_received t,
    payment_request p 
SET 
    t.billing_cycle_id = p.billing_cycle_id,
    t.invoice_number = p.invoice_number,
    t.franchise_id = p.franchise_id,
    t.vendor_id=p.vendor_id,
    t.__Narrative=p.narrative
WHERE
    t.payment_request_id = p.payment_request_id;
    
    
UPDATE temp_payment_received t,
    event_request p 
SET 
    t.cycle_name = p.event_name,t.franchise_id=p.franchise_id,t.vendor_id=p.vendor_id
WHERE
    t.payment_request_id = p.event_request_id;
UPDATE temp_payment_received 
SET 
    received_cost = amount
WHERE
    offline_response_type <> 0;
    
	UPDATE temp_payment_received t,
    offline_response c 
SET 
    t.ref_no = c.bank_transaction_no
WHERE
    t.offline_response_type > 0
        AND t.pay_transaction_id = c.offline_response_id;

UPDATE temp_payment_received t,
    offline_response c 
SET 
    t.ref_no = c.cheque_no
WHERE
    t.offline_response_type =2
        AND t.pay_transaction_id = c.offline_response_id;
        
      update temp_payment_received t,currency c set t.currency_icon=c.icon where c.code=t.currency;


UPDATE temp_payment_received t,
    config c 
SET 
    t.status = c.config_value
WHERE
		t.status = c.config_key
        AND c.config_type = 'offline_response_type'
        AND offline_response_type <>0
        AND offline_response_type is not null;
        
UPDATE temp_payment_received r,
    billing_cycle_detail b 
SET 
    r.cycle_name = b.cycle_name
WHERE
    r.billing_cycle_id = b.billing_cycle_id;
    
    
UPDATE temp_payment_received t,
    franchise u 
SET 
    t.__Franchise_name = u.franchise_name
WHERE
    t.franchise_id = u.franchise_id;
    
UPDATE temp_payment_received t,
    vendor u 
SET 
    t.__Vendor_name = u.vendor_name
WHERE
    t.vendor_id = u.vendor_id;

	SELECT 
    COUNT(invoice_number)
INTO @inv_count FROM
    temp_payment_received
WHERE
    invoice_number <> '';
    if(@inv_count>0)then
		update temp_payment_received set display_invoice_no = 1;
    end if;

    SET @where=REPLACE(_where,'~',"'");
	SET @where=REPLACE(@where,'WHERE  where',"where");
   	SET @where=REPLACE(@where,'WHERE  where',"where");
    SET @basewhere=REPLACE(_base_where,'~',"'");
    SET @basewhere=REPLACE(@basewhere,'WHERE  where',"where");


    SET @count=0;    
    SET @sql=concat('select count(pay_transaction_id),sum(amount) into @count,@totalSum from temp_payment_received ',@basewhere);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @fcount=0;    
    SET @sql=concat('select count(pay_transaction_id) ,sum(amount) into @fcount,@totalSum from temp_payment_received ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_payment_received ',@where,' ' ,_order,' ',_limit);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_received;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_payment_settlement_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_payment_settlement_detail`(_userid varchar(10),_from_date date , _to_date date,_franchise_id INT,_settlement_id INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_settlement_detail;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_settlement_detail (
  `id` int(11) NOT NULL,
  `settlement_id` varchar(20) DEFAULT NULL,
  `payment_id` varchar(45) DEFAULT NULL,
  `transaction_id` varchar(10) DEFAULT NULL,
  `payment_request_id` varchar(10) DEFAULT NULL,
  `merchant_id` varchar(10) DEFAULT NULL,
  `patron_id` varchar(10) DEFAULT NULL,
  `captured` decimal(11,2) DEFAULT NULL,
  `tdr` decimal(11,2) DEFAULT NULL,
  `service_tax` decimal(11,2) DEFAULT NULL,
  `bank_reff` varchar(20) DEFAULT NULL,
  `franchise_name` varchar(50) DEFAULT NULL,
  `franchise_id` INT DEFAULT 0,
  `transaction_date` datetime DEFAULT NULL,
  `settlement_date` datetime DEFAULT NULL,
  `created_date` timestamp DEFAULT '2014-01-01 00:00:00',
  `customer_id` int(11) DEFAULT NULL,
  `customer_code` varchar(45) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `company_name` varchar (250) null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;



	if(_settlement_id>0)then
    insert into temp_payment_settlement_detail(`id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date`) select `id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date` from payment_transaction_settlement 
    where settlement_id=_settlement_id ;
	
	else
    
    insert into temp_payment_settlement_detail(`id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date`) select `id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date` from payment_transaction_settlement 
    where DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date and merchant_id=_userid;
    end if;
    
   
UPDATE temp_payment_settlement_detail t,
    payment_request p 
SET 
    t.franchise_id = p.franchise_id
WHERE
    t.payment_request_id = p.payment_request_id;
    
    UPDATE temp_payment_settlement_detail t,
    payment_transaction p 
SET 
    t.customer_id = p.customer_id
WHERE
    t.transaction_id = p.pay_transaction_id;
    
    
    
UPDATE temp_payment_settlement_detail t,
    customer c 
SET 
    t.customer_name = CONCAT(c.first_name, ' ', c.last_name),
    t.customer_code = c.customer_code,
    t.company_name = c.company_name
WHERE
    (t.customer_id = c.customer_id);
    
	
    
UPDATE temp_payment_settlement_detail t,
    xway_transaction c 
SET 
    t.customer_name = c.name,
    t.customer_code=c.udf1,
    t.franchise_id = c.franchise_id
WHERE
    (t.transaction_id = c.xway_transaction_id);
    
    UPDATE temp_payment_settlement_detail t,
    franchise p 
SET 
    t.franchise_name = p.franchise_name
WHERE
    t.franchise_id = p.franchise_id;

if(_franchise_id>0 )then
    select * from temp_payment_settlement_detail where franchise_id=_franchise_id order by created_date;
else
    select * from temp_payment_settlement_detail order by created_date;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_payment_transaction_tdr` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_payment_transaction_tdr`(_userid varchar(10),_from_date date , _to_date date,_column_name longtext,_franchise_id INT,_where longtext,_order longtext,_limit longtext)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;

		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_transaction_tdr;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_transaction_tdr (
    `tdr_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NULL ,
    `franchise_id` int NOT NULL default 0,
    `transaction_id` varchar(10) NOT NULL ,
    `patron_id` varchar(10)  NULL,
    `patron_name` varchar(100)  NULL,
    `payment_id` varchar(50)  NULL,
    `payment_method` varchar(45)  NULL,
    `bank_reff` varchar(45)  NULL,
    `auth_date` datetime,
    `cap_date` datetime,
    `captured` DECIMAL(11,2)  null ,
    `refunded` DECIMAL(11,2)  null ,
    `chargeback` DECIMAL(11,2)  null ,
    `tdr` DECIMAL(11,2)  null ,
    `service_tax` DECIMAL(11,2)  null ,
    `surcharge` DECIMAL(11,2)  null ,
    `surcharge_service_tax` DECIMAL(11,2)  null ,
    `emitdr` DECIMAL(11,2)  null ,
    `emi_service_tax` DECIMAL(11,2)  null ,
    `net_amount` DECIMAL(11,2)  null ,
    PRIMARY KEY (`tdr_id`)) ENGINE=MEMORY;
    
    

    
      insert into temp_payment_transaction_tdr(tdr_id,payment_request_id,transaction_id,patron_id,patron_name,payment_id,payment_method,bank_reff,auth_date,
	cap_date,captured,refunded,chargeback,tdr,service_tax,surcharge,surcharge_service_tax,emitdr,emi_service_tax,net_amount) select tdr_id,payment_request_id,transaction_id,patron_id,patron_name,payment_id,payment_method,bank_reff,auth_date,
	cap_date,captured,refunded,chargeback,tdr,service_tax,surcharge,surcharge_service_tax,emitdr,emi_service_tax,net_amount from payment_transaction_tdr where DATE_FORMAT(cap_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(cap_date,'%Y-%m-%d')<= @to_date and merchant_id=_userid;

	
SET @last='net_amount';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('__',REPLACE(@column_name, ' ', '_'));
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @sql=concat('ALTER TABLE temp_payment_transaction_tdr ADD  ', @col_name , ' varchar(500) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql=concat('update temp_payment_transaction_tdr t , invoice_column_values i ,invoice_column_metadata m  set t.',@col_name,' =i.value  where t.payment_request_id=i.payment_request_id and i.column_id=m.column_id and m.column_name=''',@column_name,'''');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
	
    
   

    if(_franchise_id>0)then
    update temp_payment_transaction_tdr t,payment_request r set t.franchise_id=r.franchise_id where t.payment_request_id=r.payment_request_id;
UPDATE temp_payment_transaction_tdr t,
    event_request r 
SET 
    t.franchise_id = r.franchise_id
WHERE
    t.payment_request_id = r.event_request_id;
UPDATE temp_payment_transaction_tdr t,
    xway_transaction r 
SET 
    t.franchise_id = r.franchise_id
WHERE
    t.transaction_id = r.xway_transaction_id;
    end if;
    
    SET @where=REPLACE(_where,'~',"'");

    SET @fcount=0;    
    SET @sql=concat('select count(tdr_id),count(tdr_id),sum(captured) into @fcount,@count,@totalSum from temp_payment_transaction_tdr ',@where);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
   
    SET @sql=concat('select *,@count,@fcount,@totalSum from temp_payment_transaction_tdr ',@where,' ' ,_order,' ',_limit);
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_transaction_tdr;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_product_wise_sales` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_product_wise_sales`(_merchant_id varchar(10),_from_date date,_to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_product_wise_sales_report;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_product_wise_sales_report (
    `id` INT NOT NULL ,
    `payment_request_id` varchar(10) NOT NULL ,
    `sale_date` datetime null,
    `product_id` INT NULL,
    `product_name` varchar(100)  NULL,
    `price` decimal(11,2)  NULL,
    `quantity` INT NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    
    insert into temp_product_wise_sales_report(id,payment_request_id,sale_date,product_id,product_name,price,quantity)
	select ip.id,ip.payment_request_id,pr.bill_date,ip.product_id,p.product_name,ip.total_amount,ip.qty 
    from invoice_particular ip
    inner join payment_request pr on ip.payment_request_id=pr.payment_request_id
	inner join merchant_product p on ip.product_id=p.product_id
    where 
		pr.merchant_id=_merchant_id and 
        (DATE_FORMAT(ip.created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(ip.created_date,'%Y-%m-%d')<= @to_date) and 
        (pr.payment_request_status = 1 or pr.payment_request_status = 2);
    

    select * from temp_product_wise_sales_report;
    Drop TEMPORARY  TABLE  IF EXISTS temp_product_wise_sales_report;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_refund_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_refund_details`(_merchant_id varchar(10),_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;

		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_refund;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_refund (
    `refund_id` INT NOT NULL ,
    `transaction_id` varchar(10) NOT NULL ,
    `created_date` datetime null,
    `transaction_date` datetime null,
    `customer_id` INT  NULL,
    `customer_code` varchar(45)  NULL,
    `customer_name` varchar(100)  NULL,
    `transaction_amount` decimal(11,2)  NULL,
    `refund_amount` decimal(11,2) NULL,
    `refund_status` INT NULL,
    `refund_date` datetime null,
    `reason` varchar(250)  NULL,
    `company_name` varchar (250) null,
    PRIMARY KEY (`refund_id`)) ENGINE=MEMORY;
    
    
    insert into temp_payment_refund(refund_id,transaction_id,created_date,transaction_amount,refund_amount,refund_status,refund_date,reason)
	select id,transaction_id,created_date,transaction_amount,refund_amount,refund_status,refund_date,reason from refund_request where merchant_id=_merchant_id 
    and DATE_FORMAT(created_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<= @to_date;
    
    UPDATE temp_payment_refund t,
    payment_transaction u 
SET 
    t.customer_id = u.customer_id,
    t.transaction_date=u.created_date
WHERE
    t.transaction_id = u.pay_transaction_id;
    
UPDATE temp_payment_refund t,
    customer u 
SET 
    t.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    t.customer_code = u.customer_code,
    t.company_name = u.company_name
WHERE
    t.customer_id = u.customer_id;
    
    UPDATE temp_payment_refund t,
    xway_transaction u
SET 
    t.customer_name = u.name,
    t.customer_code = u.udf1,
    t.transaction_date=u.created_date
WHERE
    t.transaction_id = u.xway_transaction_id;
    

    select * from temp_payment_refund;
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_refund;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_swipez_ledger` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_swipez_ledger`(_merchant_id varchar(10),_from_date DATE ,_to_date DATE,_customer_id INT
,_franchise_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 
        
Drop TEMPORARY  TABLE  IF EXISTS tmpsumledger;

CREATE TEMPORARY TABLE IF NOT EXISTS tmpsumledger ( 
`id` INT NOT NULL auto_increment , 
`cr_dr` varchar(10) NOT NULL, 
`debit` DECIMAL(11,2),
`credit` DECIMAL(11,2),
`tdr` DECIMAL(11,2),
`st` DECIMAL(11,2),
PRIMARY KEY (`id`)) ENGINE=MEMORY;



SELECT 
    user_id
INTO @user_id FROM
    merchant
WHERE
    merchant_id = _merchant_id;

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
select 'dr',sum(amount),0,0,0 from payment_transaction where merchant_id=_merchant_id and payment_transaction_status =1 
and DATE_FORMAT(created_date,'%Y-%m-%d') < _from_date and DATE_FORMAT(created_date,'%Y-%m-%d')>'2016-01-01';


insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'dr',sum(absolute_cost),0,0,0 FROM `xway_transaction`
where merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') < _from_date and DATE_FORMAT(created_date,'%Y-%m-%d')>'2016-01-01' and xway_transaction_status=1;

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`captured`),0,0 FROM `payment_transaction_settlement`
where merchant_id=@user_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') < _from_date;

SELECT 
    SUM(debit) - SUM(credit)
INTO @opening_balance FROM
    tmpsumledger;




Drop TEMPORARY  TABLE  IF EXISTS tmpledger;

CREATE TEMPORARY TABLE IF NOT EXISTS tmpledger ( 
`id` INT NOT NULL auto_increment , 
`customer_id` INT NULL,  
`franchise_id` INT NULL default 0,  
`payment_request_id` varchar(10) NULL, 
`transaction_id` varchar(10) NULL, 
`customer_detail` varchar(250) NULL,  
`__Customer_code` varchar(45) NULL, 
`__Narrative` varchar(500) NULL, 
`invoice_number` varchar(50) NULL default '', 
`type` varchar(20) null,
`invoice_type` varchar(50) null,
`debit` DECIMAL(11,2) not NULL default 0,
`credit` DECIMAL(11,2) not NULL default 0,
`tdr` DECIMAL(11,2) not NULL default 0,
`st` DECIMAL(11,2) not NULL default 0,
`date` DATETIME null,
`settlement_date` DATETIME null,
`settlement_id` INT not null default 0,
`bank_ref` varchar(50) null,
`__Settlement_value` decimal(11,2) not null default 0,
`__Bill_date` DATE null,
PRIMARY KEY (`id`)) ENGINE=MEMORY;




insert into tmpledger (customer_id,payment_request_id,transaction_id,debit,credit,tdr,st,`date`)
select customer_id,payment_request_id,pay_transaction_id,amount,0,0,0,created_date from payment_transaction where merchant_id=_merchant_id and payment_transaction_status =1 
and DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date ;


insert into tmpledger (customer_detail,__Customer_code,__Narrative,transaction_id,invoice_number,`type`,debit,credit,tdr,st,`date`,franchise_id)
SELECT `name`,udf1,description,xway_transaction_id,reference_no,'Website',absolute_cost,0,0,0,`created_date`,franchise_id FROM `xway_transaction`
where merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date and xway_transaction_status=1;


UPDATE tmpledger l,
    payment_transaction_settlement s 
SET 
    credit = s.captured,
    l.tdr = REPLACE(s.tdr, '-', ''),
    st = REPLACE(s.service_tax, '-', ''),
    l.settlement_date=s.settlement_date,
    l.bank_ref=s.bank_reff,
    l.settlement_id=s.settlement_id
WHERE
    l.transaction_id = s.transaction_id;
    
    
    UPDATE tmpledger s,
    settlement_detail c 
SET 
    s.__Settlement_value = c.requested_settlement_amount
WHERE
    s.settlement_id = c.id;
    

UPDATE tmpledger s,
    payment_request c 
SET 
    s.invoice_type = c.payment_request_type,
    s.customer_id = c.customer_id,
    s.franchise_id = c.franchise_id,
    s.invoice_number = c.invoice_number,
    s.__Narrative=c.narrative,
    s.__Bill_date=c.bill_date
WHERE
    s.payment_request_id = c.payment_request_id;
    
    
    UPDATE tmpledger s,
    event_request c 
SET 
    s.invoice_type = 2,
    s.franchise_id = c.franchise_id
WHERE
    s.payment_request_id = c.event_request_id;
    
UPDATE tmpledger s,
    customer c 
SET 
    s.customer_detail = CONCAT(c.first_name,
            ' ',
            c.last_name)
            ,
            s.__Customer_code=c.customer_code
WHERE
    s.customer_id = c.customer_id;

UPDATE tmpledger s,
    config c 
SET 
    s.type = c.config_value
WHERE
    s.invoice_type = c.config_key
        AND c.config_type = 'payment_request_type';


if(_franchise_id>0)then
    SELECT 
    date,
    customer_detail,
    transaction_id ,
    invoice_number,
    type,
    debit,
    credit,
    tdr,
    st,
    settlement_date,
	settlement_id,
	bank_ref,
	__Settlement_value,
	__Bill_date
    
FROM
    tmpledger where franchise_id=_franchise_id
ORDER BY `date` , id;
else
    SELECT 
    date,
    customer_detail,
    __Customer_code,
    transaction_id ,
    invoice_number,
    type,
    __Narrative,
    debit,
    credit,
    tdr,
    st,
    settlement_date,
	settlement_id,
	bank_ref,
	__Settlement_value,
	__Bill_date
FROM
    tmpledger
ORDER BY `date` , id;
end if;



         

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_taxdetails_metadata` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_taxdetails_metadata`(_merchant_id varchar(10),_template_id varchar(10),_from_date date , _to_date date,
_status varchar(50))
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @status=_status;
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');
Drop TEMPORARY  TABLE  IF EXISTS temp_col_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_col_details (
	`column_id` INT NOT NULL ,
	PRIMARY KEY (`column_id`)) ENGINE=MEMORY;

        if(_status<>'') then
			insert into temp_col_details(column_id) 
			select distinct column_id from invoice_column_values v inner join payment_request p on v.payment_request_id=p.payment_request_id where  p.merchant_id=_merchant_id and p.is_active=1 and p.payment_request_type <> 4 and DATE_FORMAT(p.created_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(p.created_date,'%Y-%m-%d') <= @to_date and p.payment_request_status = _status and p.template_id=_template_id;
		else
			insert into temp_col_details(column_id) 
			select distinct column_id from invoice_column_values v inner join payment_request p on v.payment_request_id=p.payment_request_id where  p.merchant_id=_merchant_id and p.is_active=1 and p.payment_request_type <> 4 and DATE_FORMAT(p.created_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(p.created_date,'%Y-%m-%d') <= @to_date  and p.template_id=_template_id;
        end if;
SELECT sort_order,column_id,default_column_value,position,is_mandatory,column_group_id,is_active,is_delete_allow,save_table_name,column_datatype,column_position,column_name,column_type,column_group_id,customer_column_id,template_id,function_id 
from invoice_column_metadata  where template_id=_template_id and is_active=1 and column_id in (select column_id from temp_col_details) order by sort_order,column_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_tax_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_tax_details`(_merchant_id varchar(10),_template_id varchar(10),_from_date date , _to_date date,
_status varchar(50),_column_name longtext,_customer_column_name longtext,_billing_profile_id INT)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @status=_status;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_tax_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_tax_details (
	`invoice_id` varchar(10) NOT NULL ,
	`customer_id` INT NOT NULL,
	`customer_code` varchar(45)  NULL,
	`invoice_number` varchar(45)  NULL default '',
   	`basic_amount` DECIMAL(11,2) not null ,
	`invoice_amount` DECIMAL(11,2) not null ,
    `offline_response_type` INT default 0 not null,
    `merchant_gst_number` char (15) null,
    `billing_profile_id` INT default 0 not null,
	`status` varchar(50) not null,
	`sent_date` datetime not null,
	`bill_date` date not null,
	`due_date` DATE not null,
	`customer_name` varchar (100) null,
     `__Transaction_id` varchar(10)  null,
    `__Transaction_ref_no` varchar(50)  null,
	`__Email` varchar (250) null,
    `__Gst_Number` varchar (20) null,
	`__Mobile` varchar (20) null,
    `__Address` varchar (250) null,
    `__City` varchar (45) null,
    `__State` varchar (45) null,
    `__Zipcode` varchar (20) null,
    `created_by` varchar (10) null,
    `__Settlement_ref_no` varchar(50)  null,
    `__Settlement_date` datetime null,
    `__Transaction_amount` decimal(11,2) default 0 not null,
    `__TDR` decimal(5,2) default 0 not null,
    `__TDR_GST` decimal(5,2) default 0 not null,
    `__Settled_Amount` decimal(11,2) default 0 not null,
    `__Advance_Received` decimal(11,2) default 0 null,
    `company_name` varchar (250) null,
    PRIMARY KEY (`invoice_id`),INDEX `customer_idx` (`customer_id` ASC),
    INDEX `transaction_idx` (`__Transaction_id` ASC)
    ) ENGINE=MEMORY;

if(_billing_profile_id>0)then

if(_status<>'') then
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received+paid_amount from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date and payment_request_status = _status and template_id=_template_id and billing_profile_id=_billing_profile_id;
		else
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received+paid_amount from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date  and template_id=_template_id and billing_profile_id=_billing_profile_id and payment_request_status<>3;
        end if;
else
if(_status<>'') then
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received+paid_amount from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date and payment_request_status = _status and template_id=_template_id;
		else
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received+paid_amount from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date  and template_id=_template_id and payment_request_status<>3;
        end if;
end if;
        
SET @last='customer_name';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('META__',@column_name);	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_tax_details ADD  ', @col_name , ' varchar(100) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET @sql=concat('update temp_tax_details t , invoice_column_values i   set t.',@col_name,' =i.value  where t.invoice_id=i.payment_request_id and i.column_id=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;

SET @last='customer_name';
WHILE _customer_column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_customer_column_name, @separator, 1);
        SET @col_name=concat('CUST__',@column_name);	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_tax_details ADD  ', @col_name , ' varchar(250) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET @sql=concat('update temp_tax_details t , customer_column_values i   set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
	
	SET @last=@col_name;
    SET _customer_column_name = SUBSTRING(_customer_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;


UPDATE temp_tax_details r,
    customer u 
SET 
    r.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_code = u.customer_code,
    r.__Address = u.address,
    r.__City = u.city,
    r.__State = u.state,
    r.__Zipcode = u.zipcode,
	r.__Mobile = u.mobile,
    r.__Email = u.email,
    r.__Gst_Number = u.gst_number,
    r.company_name = u.company_name
WHERE
    r.customer_id = u.customer_id;
    

UPDATE temp_tax_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.status = c.config_key
        AND c.config_type = 'payment_request_status';

        
UPDATE temp_tax_details r,
    payment_transaction b 
SET 
    r.__Transaction_ref_no = b.pg_ref_no,r.__Transaction_id=b.pay_transaction_id
    ,r.__Transaction_amount=b.amount
WHERE
    r.invoice_id = b.payment_request_id;   

UPDATE temp_tax_details r,
    offline_response b 
SET 
    r.__Transaction_ref_no = b.bank_transaction_no,r.__Transaction_id=b.offline_response_id,r.offline_response_type=b.offline_response_type
    ,r.__Transaction_amount=b.amount
WHERE
    r.invoice_id = b.payment_request_id;   
UPDATE temp_tax_details r,
    payment_transaction_settlement b 
SET 
    r.__Settlement_ref_no = b.bank_reff,r.__Settlement_date=b.settlement_date,
    r.__TDR=replace(b.tdr,'-',''),r.__TDR_GST=replace(b.service_tax,'-',''),r.__Settled_Amount=b.captured+b.tdr+b.service_tax
WHERE
    r.__Transaction_id = b.transaction_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= @to_date;  
UPDATE temp_tax_details r,
    payment_transaction_settlement b 
SET 
    r.__Settlement_ref_no = b.bank_reff,r.__Settlement_date=b.settlement_date,
    r.__TDR=replace(b.tdr,'-',''),r.__TDR_GST=replace(b.service_tax,'-',''),r.__Settled_Amount=b.captured+b.tdr+b.service_tax
WHERE
    r.__Transaction_ref_no = b.transaction_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= @to_date;  


UPDATE temp_tax_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.offline_response_type = c.config_key AND c.config_type = 'offline_response_type' AND r.offline_response_type>0;
select * from temp_tax_details;
Drop TEMPORARY  TABLE  IF EXISTS temp_tax_details;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_tax_details2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_tax_details2`(_merchant_id varchar(10),_template_id varchar(10),_from_date date , _to_date date,
_status varchar(50),_column_name longtext,_customer_column_name longtext,_billing_profile_id INT)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @status=_status;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_tax_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_tax_details (
	`invoice_id` varchar(10) NOT NULL ,
	`customer_id` INT NOT NULL,
	`customer_code` varchar(45)  NULL,
	`invoice_number` varchar(45)  NULL default '',
   	`basic_amount` DECIMAL(11,2) not null ,
	`invoice_amount` DECIMAL(11,2) not null ,
    `offline_response_type` INT default 0 not null,
    `merchant_gst_number` char (15) null,
    `billing_profile_id` INT default 0 not null,
	`status` varchar(50) not null,
	`sent_date` datetime not null,
	`bill_date` date not null,
	`due_date` DATE not null,
	`customer_name` varchar (100) null,
     `__Transaction_id` varchar(10)  null,
    `__Transaction_ref_no` varchar(50)  null,
	`__Email` varchar (250) null,
    `__Gst_Number` varchar (20) null,
	`__Mobile` varchar (20) null,
    `__Address` varchar (250) null,
    `__City` varchar (45) null,
    `__State` varchar (45) null,
    `__Zipcode` varchar (20) null,
    `created_by` varchar (10) null,
    `__Settlement_ref_no` varchar(50)  null,
    `__Settlement_date` datetime null,
    `__Transaction_amount` decimal(11,2) default 0 not null,
    `__TDR` decimal(5,2) default 0 not null,
    `__TDR_GST` decimal(5,2) default 0 not null,
    `__Settled_Amount` decimal(11,2) default 0 not null,
    `__Advance_Received` decimal(11,2) default 0 null,
    `company_name` varchar (250) null,
    PRIMARY KEY (`invoice_id`),INDEX `customer_idx` (`customer_id` ASC),
    INDEX `transaction_idx` (`__Transaction_id` ASC)
    ) ENGINE=MEMORY;

if(_billing_profile_id>0)then

if(_status<>'') then
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received+paid_amount from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date and payment_request_status = _status and template_id=_template_id and billing_profile_id=_billing_profile_id;
		else
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received+paid_amount from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date  and template_id=_template_id and billing_profile_id=_billing_profile_id and payment_request_status<>3;
        end if;
else
if(_status<>'') then
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received+paid_amount from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date and payment_request_status = _status and template_id=_template_id;
		else
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received+paid_amount from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date  and template_id=_template_id and payment_request_status<>3;
        end if;
end if;
        
SET @last='customer_name';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('META__',@column_name);	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_tax_details ADD  ', @col_name , ' varchar(100) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET @sql=concat('update temp_tax_details t , invoice_column_values i   set t.',@col_name,' =i.value  where t.invoice_id=i.payment_request_id and i.column_id=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;

SET @last='customer_name';
WHILE _customer_column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_customer_column_name, @separator, 1);
        SET @col_name=concat('CUST__',@column_name);	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_tax_details ADD  ', @col_name , ' varchar(250) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET @sql=concat('update temp_tax_details t , customer_column_values i   set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
	
	SET @last=@col_name;
    SET _customer_column_name = SUBSTRING(_customer_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;


UPDATE temp_tax_details r,
    customer u 
SET 
    r.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_code = u.customer_code,
    r.__Address = u.address,
    r.__City = u.city,
    r.__State = u.state,
    r.__Zipcode = u.zipcode,
	r.__Mobile = u.mobile,
    r.__Email = u.email,
    r.__Gst_Number = u.gst_number,
    r.company_name = u.company_name
WHERE
    r.customer_id = u.customer_id;
    

UPDATE temp_tax_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.status = c.config_key
        AND c.config_type = 'payment_request_status';

        
UPDATE temp_tax_details r,
    payment_transaction b 
SET 
    r.__Transaction_ref_no = b.pg_ref_no,r.__Transaction_id=b.pay_transaction_id
    ,r.__Transaction_amount=b.amount
WHERE
    r.invoice_id = b.payment_request_id;   

UPDATE temp_tax_details r,
    offline_response b 
SET 
    r.__Transaction_ref_no = b.bank_transaction_no,r.__Transaction_id=b.offline_response_id,r.offline_response_type=b.offline_response_type
    ,r.__Transaction_amount=b.amount
WHERE
    r.invoice_id = b.payment_request_id;   
UPDATE temp_tax_details r,
    payment_transaction_settlement b 
SET 
    r.__Settlement_ref_no = b.bank_reff,r.__Settlement_date=b.settlement_date,
    r.__TDR=replace(b.tdr,'-',''),r.__TDR_GST=replace(b.service_tax,'-',''),r.__Settled_Amount=b.captured+b.tdr+b.service_tax
WHERE
    r.__Transaction_id = b.transaction_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= @to_date;  
UPDATE temp_tax_details r,
    payment_transaction_settlement b 
SET 
    r.__Settlement_ref_no = b.bank_reff,r.__Settlement_date=b.settlement_date,
    r.__TDR=replace(b.tdr,'-',''),r.__TDR_GST=replace(b.service_tax,'-',''),r.__Settled_Amount=b.captured+b.tdr+b.service_tax
WHERE
    r.__Transaction_ref_no = b.transaction_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= @to_date;  


UPDATE temp_tax_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.offline_response_type = c.config_key AND c.config_type = 'offline_response_type' AND r.offline_response_type>0;
select * from temp_tax_details;
Drop TEMPORARY  TABLE  IF EXISTS temp_tax_details;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_tax_detailsv1` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_tax_detailsv1`(_merchant_id varchar(10),_template_id varchar(10),_from_date date , _to_date date,
_status varchar(50),_column_name longtext,_customer_column_name longtext,_billing_profile_id INT)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
BEGIN
END; 
SET @status=_status;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_tax_details;

	CREATE TEMPORARY TABLE IF NOT EXISTS temp_tax_details (
	`invoice_id` varchar(10) NOT NULL ,
	`customer_id` varchar(10) NOT NULL,
	`customer_code` varchar(45)  NULL,
	`invoice_number` varchar(45)  NULL default '',
   	`basic_amount` DECIMAL(11,2) not null ,
	`invoice_amount` DECIMAL(11,2) not null ,
    `offline_response_type` INT default 0 not null,
    `merchant_gst_number` char (15) null,
    `billing_profile_id` INT default 0 not null,
	`status` varchar(50) not null,
	`sent_date` datetime not null,
	`bill_date` date not null,
	`due_date` DATE not null,
	`customer_name` varchar (100) null,
     `__Transaction_id` varchar(10)  null,
    `__Transaction_ref_no` varchar(50)  null,
	`__Email` varchar (250) null,
    `__Gst_Number` varchar (20) null,
	`__Mobile` varchar (20) null,
    `__Address` varchar (250) null,
    `__City` varchar (45) null,
    `__State` varchar (45) null,
    `__Zipcode` varchar (20) null,
    `created_by` varchar (10) null,
    `__Settlement_ref_no` varchar(50)  null,
    `__Settlement_date` datetime null,
    `__Transaction_amount` decimal(11,2) default 0 not null,
    `__TDR` decimal(5,2) default 0 not null,
    `__TDR_GST` decimal(5,2) default 0 not null,
    `__Settled_Amount` decimal(11,2) default 0 not null,
    `__Advance_Received` decimal(11,2) default 0 null,
	PRIMARY KEY (`invoice_id`),INDEX `customer_idx` (`customer_id` ASC),
    INDEX `transaction_idx` (`__Transaction_id` ASC)
    ) ENGINE=MEMORY;

if(_billing_profile_id>0)then

if(_status<>'') then
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date and payment_request_status = _status and template_id=_template_id and billing_profile_id=_billing_profile_id;
		else
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date  and template_id=_template_id and billing_profile_id=_billing_profile_id and payment_request_status<>3;
        end if;
else
if(_status<>'') then
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date and payment_request_status = _status and template_id=_template_id;
		else
		   insert into temp_tax_details(invoice_id,customer_id,basic_amount,invoice_amount,status,sent_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,merchant_gst_number,`__Advance_Received`) 
			select payment_request_id,customer_id,basic_amount,grand_total,payment_request_status,created_date,bill_date,due_date,invoice_number, created_by,billing_profile_id,gst_number,advance_received from payment_request where merchant_id=_merchant_id and is_active=1 and payment_request_type <> 4 and DATE_FORMAT(bill_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= @to_date  and template_id=_template_id and payment_request_status<>3;
        end if;
end if;
        
SET @last='customer_name';
WHILE _column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_column_name, @separator, 1);
        SET @col_name=concat('META__',@column_name);	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_tax_details ADD  ', @col_name , ' varchar(100) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET @sql=concat('update temp_tax_details t , invoice_column_values i   set t.',@col_name,' =i.value  where t.invoice_id=i.payment_request_id and i.column_id=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
	
	SET @last=@col_name;
    SET _column_name = SUBSTRING(_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;

SET @last='customer_name';
WHILE _customer_column_name != '' > 0 DO
        SET @column_name  = SUBSTRING_INDEX(_customer_column_name, @separator, 1);
        SET @col_name=concat('CUST__',@column_name);	
        SET @col_name=REPLACE(@col_name, '.', '');	
        SET @col_name=REPLACE(@col_name, ' ', '_');	
        SET @sql=concat('ALTER TABLE temp_tax_details ADD  ', @col_name , ' varchar(250) NULL AFTER ',@last);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET @sql=concat('update temp_tax_details t , customer_column_values i   set t.',@col_name,' =i.value  where t.customer_id=i.customer_id and i.column_id=''',@column_name,'''');
       PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
	
	SET @last=@col_name;
    SET _customer_column_name = SUBSTRING(_customer_column_name, CHAR_LENGTH(@column_name) + @separatorLength + 1);
END WHILE;
 do sleep(10);

UPDATE temp_tax_details r,
    customer u 
SET 
    r.customer_name = CONCAT(u.first_name, ' ', u.last_name),
    r.customer_code = u.customer_code,
    r.__Address = u.address,
    r.__City = u.city,
    r.__State = u.state,
    r.__Zipcode = u.zipcode,
	r.__Mobile = u.mobile,
    r.__Email = u.email,
    r.__Gst_Number = u.gst_number
WHERE
    r.customer_id = u.customer_id;


UPDATE temp_tax_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.status = c.config_key
        AND c.config_type = 'payment_request_status';

        
UPDATE temp_tax_details r,
    payment_transaction b 
SET 
    r.__Transaction_ref_no = b.pg_ref_no,r.__Transaction_id=b.pay_transaction_id
    ,r.__Transaction_amount=b.amount
WHERE
    r.invoice_id = b.payment_request_id;   

UPDATE temp_tax_details r,
    offline_response b 
SET 
    r.__Transaction_ref_no = b.bank_transaction_no,r.__Transaction_id=b.offline_response_id,r.offline_response_type=b.offline_response_type
    ,r.__Transaction_amount=b.amount
WHERE
    r.invoice_id = b.payment_request_id;   
UPDATE temp_tax_details r,
    payment_transaction_settlement b 
SET 
    r.__Settlement_ref_no = b.bank_reff,r.__Settlement_date=b.settlement_date,
    r.__TDR=replace(b.tdr,'-',''),r.__TDR_GST=replace(b.service_tax,'-',''),r.__Settled_Amount=b.captured+b.tdr+b.service_tax
WHERE
    r.__Transaction_id = b.transaction_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= @to_date;  
UPDATE temp_tax_details r,
    payment_transaction_settlement b 
SET 
    r.__Settlement_ref_no = b.bank_reff,r.__Settlement_date=b.settlement_date,
    r.__TDR=replace(b.tdr,'-',''),r.__TDR_GST=replace(b.service_tax,'-',''),r.__Settled_Amount=b.captured+b.tdr+b.service_tax
WHERE
    r.__Transaction_ref_no = b.transaction_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= @from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= @to_date;  


UPDATE temp_tax_details r,
    config c 
SET 
    r.status = c.config_value
WHERE
    r.offline_response_type = c.config_key AND c.config_type = 'offline_response_type' AND r.offline_response_type>0;

select * from temp_tax_details;
Drop TEMPORARY  TABLE  IF EXISTS temp_tax_details;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `report_tax_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `report_tax_summary`(_user_id varchar(10),_from_date datetime , _to_date datetime)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
                    show errors;
			ROLLBACK;
		END;
START TRANSACTION;

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_tax_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_tax_summary (
    `tax_id` INT NOT NULL AUTO_INCREMENT,
    `column_id` INT NOT NULL,
    `percent_id` INT NOT NULL,
    `applicable_id` INT NOT NULL,
    `amount_id` INT NOT NULL,
    `tax_name` varchar(250)  NULL ,
    `tax_percentage` varchar(250) NULL,
    `applicable` decimal(11,2) NULL default 0,
    `amount` decimal(11,2) NULL default 0,
    PRIMARY KEY (`tax_id`)) ENGINE=MEMORY;

    insert into temp_tax_summary(tax_name,column_id,percent_id,applicable_id,amount_id)select column_name,m.column_id,v.invoice_id + 1,v.invoice_id + 2,v.invoice_id + 3 from invoice_column_metadata m  inner join invoice_column_values v on m.column_id=v.column_id where m.column_type='TF' and v.created_by=_user_id and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') >=  @from_date  and DATE_FORMAT(v.last_update_date,'%Y-%m-%d') <=  @to_date; 
    update temp_tax_summary t , invoice_column_values v  set t.tax_percentage = v.value   where t.percent_id=v.invoice_id ;
    update temp_tax_summary t , invoice_column_values v  set t.applicable = v.value   where t.applicable_id=v.invoice_id and v.value>0;
    update temp_tax_summary t , invoice_column_values v  set t.amount = v.value   where t.amount_id=v.invoice_id and v.value>0;

    select tax_name,tax_percentage,sum(applicable) as applicable,sum(amount) as amount from temp_tax_summary where tax_name <> '' and tax_percentage<>'' group by tax_name,tax_percentage ;
commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `savebulkUpload_invoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `savebulkUpload_invoice`(_bulk_upload_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @inv_count=0;


    
SELECT 
    COUNT(bulk_upload_id)
INTO @count FROM
    bulk_upload
WHERE
    bulk_upload_id = _bulk_upload_id;
if(@count>0) then

/*for saving staging_subscriptions into subscription table */
SELECT `invoice_number`,`merchant_id`,`template_id`,`invoice_type`,`payment_request_type` into @invoice_numstring,@merchant_id,@template_id,@invoice_type,@payment_request_type FROM staging_payment_request where bulk_id=_bulk_upload_id limit 1;

if(@payment_request_type=4) then
	INSERT INTO `subscription`(`payment_request_id`,`merchant_id`,`mode`,`repeat_every`,`repeat_on`,`start_date`,`due_date`,`due_diff`,
	`carry_due`,`last_sent_date`,`next_bill_date`,`end_mode`,`occurrences`,`end_date`,`display_text`,`billing_period_start_date`,
	`billing_period_duration`,`billing_period_type`,`service_id`,`is_active`,`created_by`,`created_date`,`last_updated_by`,`last_updated_date`)
	SELECT `staging_subscription`.`payment_request_id`,`merchant_id`,`mode`,`repeat_every`,`repeat_on`,`start_date`,`due_date`,`due_diff`,
	`carry_due`,`last_sent_date`,`next_bill_date`,`end_mode`,`occurrences`,`end_date`,`display_text`,`billing_period_start_date`,
	`billing_period_duration`,`billing_period_type`,`service_id`,`is_active`,`created_by`
	,CURRENT_TIMESTAMP(),`last_updated_by`,CURRENT_TIMESTAMP() FROM `staging_subscription` where bulk_id=_bulk_upload_id and is_active=1;
end if;

SET @numstring=SUBSTRING(@invoice_numstring,1,16);
if(@numstring='System generated' and @invoice_type=1)then

	SET @autoval=SUBSTRING(@invoice_numstring,17);

	INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`,
`created_date`,`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`)
SELECT `staging_payment_request`.`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,`invoice_type`,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
generate_invoice_number(@autoval),`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`
FROM `staging_payment_request` where bulk_id=_bulk_upload_id and is_active=1 and invoice_type=1;

	UPDATE staging_invoice_values s,
    payment_request p 
SET 
    s.value = p.invoice_number
WHERE
    s.payment_request_id = p.payment_request_id
        AND s.value = @invoice_numstring;

else
	SET @fun_count=0;
		SELECT 
    COUNT(column_id)
INTO @fun_count FROM
    invoice_column_metadata
WHERE
    template_id = @template_id
        AND function_id = 9;
	if(@fun_count>0)then
		select count(s.payment_request_id) into @inv_count from payment_request p inner join staging_payment_request s on  s.invoice_number=p.invoice_number and s.merchant_id=p.merchant_id
		where  p.invoice_number<>'' and s.bulk_id=_bulk_upload_id and s.invoice_number is not null;    
	end if;
 
 if(@inv_count=0)then
	
if(@invoice_type=2)then
INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`,
`created_date`,`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`)
SELECT `staging_payment_request`.`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,`invoice_type`,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
`invoice_number`,generate_estimate_number(merchant_id),`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`
FROM `staging_payment_request` where bulk_id=_bulk_upload_id and is_active=1;
else
INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`,
`created_date`,`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`)
SELECT `staging_payment_request`.`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,`invoice_type`,`template_id`,`billing_cycle_id`,
`absolute_cost`,`basic_amount`,`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,`grand_total`,`previous_due`,`advance_received`,`paid_amount`,
`invoice_number`,`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,1,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`,`currency`,`einvoice_type`, `product_taxation_type`
FROM `staging_payment_request` where bulk_id=_bulk_upload_id and is_active=1;
end if;
	end if;
end if;


INSERT INTO `invoice_particular`
(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`unit_type`,`qty`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT `payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`unit_type`,`qty`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`gst`,
`tax_amount`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `staging_invoice_particular` where bulk_id=_bulk_upload_id and is_active=1;

INSERT INTO `invoice_travel_particular`
(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT `payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `staging_invoice_travel_particular` where bulk_id=_bulk_upload_id and is_active=1;


INSERT INTO `invoice_tax`
(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT `payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `staging_invoice_tax` where bulk_id=_bulk_upload_id and is_active=1;






if(@inv_count=0)then
	 INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
	 SELECT s.`payment_request_id`,s.`column_id`,s.`value`,s.`is_active`,s.`created_by`,CURRENT_TIMESTAMP(),s.`last_update_by`,CURRENT_TIMESTAMP()
	FROM `staging_invoice_values` s inner join staging_payment_request on s.payment_request_id=staging_payment_request.payment_request_id inner join bulk_upload b on b.bulk_upload_id= staging_payment_request.bulk_id
	where b.bulk_upload_id=_bulk_upload_id;


	UPDATE bulk_upload 
SET 
    status = 5
WHERE
    bulk_upload_id = _bulk_upload_id;
    
	
    
    
update customer c,payment_request p set c.balance = balance + p.grand_total where c.customer_id=p.customer_id and p.bulk_id=_bulk_upload_id and p.is_active=1;

INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
select customer_id,concat('Invoice for bill date ',bill_date),grand_total,1,payment_request_id,'DEBIT',created_by,CURRENT_TIMESTAMP(),created_by from payment_request where bulk_id=_bulk_upload_id and is_active=1;

    SET @duecolumn_id=0;

select column_id into @duecolumn_id from invoice_column_metadata where template_id=@template_id and function_id=4 and is_active=1; 
if(@duecolumn_id>0)then
SET @due_mode='';
select param into @due_mode from column_function_mapping where column_id=@duecolumn_id and function_id=4 and is_active=1;
end if;
	commit;
else
	ROLLBACK;
    SET @message ='Invoice number already exist';
SELECT @message AS 'message';
end if;
SELECT 
    payment_request_id,payment_request_type,invoice_type,notify_patron,due_date,has_custom_reminder,template_id,customer_id,merchant_id,user_id,plugin_value,@due_mode as 'due_mode'
FROM
    payment_request where bulk_id=_bulk_upload_id;
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `saveSubscription_invoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `saveSubscription_invoice`(_payment_request_id LONGTEXT, _due_date LONGTEXT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 
Drop TEMPORARY  TABLE  IF EXISTS sendingInvoiceEmail;

CREATE TEMPORARY TABLE IF NOT EXISTS sendingInvoiceEmail (
    `request_id` varchar(10) NOT NULL ,
    `parent_request_id` varchar(10) NOT NULL ,
    `due_date` date NULL,
     `customer_id` INT  NOT NULL,
	`user_id` varchar(10)  NOT NULL,
     `merchant_id` varchar(10)  NULL,
	`template_id` varchar(10)  NULL,
    `merchant_type` int  NULL,
    `company_name` varchar(250)  NULL,
    `display_name` varchar(250)  default '',
    `sms_gateway_type` int Null default 1,
    `sms_gateway` int Null default 1,
    `profile_id` INT  NOT NULL default 0,
    `image` varchar(200)  NULL,
    `plugin_value` varchar(1000)  NULL,
    `merchant_domain` INT  NULL,
    `merchant_domain_name` varchar(45)  NULL,
    `grand_total` decimal(11,2)  NULL,
    `email` varchar(250)  NULL,
	`franchise_id` INT  NULL,
    `has_custom_reminder` INT NULL,
    `mobile` varchar(13)  NULL,
    `sms_name` varchar(50) default '',
    PRIMARY KEY (`request_id`)) ENGINE=MEMORY;
    
    
    SET @separator = '~';
    SET @separatorLength = CHAR_LENGTH(@separator);
    SET @estimate_number='';

    SET @payment_request_id  = _payment_request_id;
	SET @due_date  = _due_date;
    
SELECT 
    `invoice_number`,
    `user_id`,
    `merchant_id`,
    `invoice_type`,
    `customer_id`,
    `template_id`,
    `billing_cycle_id`,
    absolute_cost - previous_due,
    `basic_amount`,
    `advance_received`,
    `tax_amount`,
    invoice_total - previous_due,
    swipez_total - previous_due,
    previous_due,
    `convenience_fee`,
    grand_total - previous_due,
    `late_payment_fee`,
    `payment_request_status`,
    `narrative`,
    `notify_patron`,
    `franchise_id`,
    `vendor_id`,
	has_custom_reminder,
    billing_profile_id

INTO @invoice_numstring , @_user_id,@merchant_id,@invoice_type , @_customer_id ,   @_template_id , @_billing_cycle_id , @_absolute_cost ,
 @_basic_amount ,@_advance, @_tax_amount , @_invoice_total , @_swipez_total , @previous_due , @_convenience_fee , @_grand_total ,
 @_late_payment_fee , @_payment_request_status , @_narrative , @_notify_patron , @franchise_id ,@vendor_id,@has_custom_reminder,@billing_profile_id
     FROM
    payment_request
WHERE
    payment_request_id = @payment_request_id;
    
SET @exist_payment_request_id=0;  
select 1 into @exist_payment_request_id from payment_request
where merchant_id =@merchant_id and  payment_request_type=5 and parent_request_id=@payment_request_id and due_date=@due_date;

if(@exist_payment_request_id=0)then
SELECT GENERATE_SEQUENCE('Pay_Req_Id') INTO @req_id;

SELECT 
	`mode`,
    `repeat_every`,
    `end_date`,
    `end_mode`,
    carry_due,
    billing_period_start_date,
    billing_period_duration,
    billing_period_type
INTO @smode,@repeat_every,@end_date,@end_mode, @carry_due , @period_start_date , @period , @period_type FROM
    subscription
WHERE
    payment_request_id = @payment_request_id;

if(@invoice_type=1)then
   
	if(@previous_due=0)then
		if(@carry_due=1)then
        update payment_request p , payment_transaction t set p.payment_request_status=1 where p.payment_request_id=t.payment_request_id and p.parent_request_id=@payment_request_id and t.payment_transaction_status=1;
		select sum(invoice_total) into @previous_due from payment_request where parent_request_id=@payment_request_id and payment_request_status in (0,5,4) and (expiry_date is null or expiry_date > CURDATE());
UPDATE payment_request 
SET 
    expiry_date = CURDATE()
WHERE
    parent_request_id = @payment_request_id
        AND payment_request_status IN (0 , 5, 4)
        AND (expiry_date IS NULL
        OR expiry_date > CURDATE());
		end if;
        else
			update payment_request set absolute_cost=absolute_cost-previous_due,invoice_total=invoice_total-previous_due,grand_total=grand_total-previous_due,swipez_total=swipez_total-previous_due,previous_due=0 where payment_request_id=@payment_request_id;
	  end if;
end if;


 if(@previous_due>0)then
    SET @previous_due=@previous_due;
    else
    SET @previous_due=0;
    end if;

SET @_absolute_cost=@_absolute_cost + @previous_due;
SELECT GET_SURCHARGE_AMOUNT(@merchant_id, @_absolute_cost, 0) INTO @_convenience_fee;

SET @autoval=0;

if(@billing_profile_id>0)then
	select invoice_seq_id into @autoval from merchant_billing_profile where id=@billing_profile_id;
end if;

if(@invoice_type=2)then
SELECT generate_estimate_number(@merchant_id) INTO @estimate_number;
SET @invoice_number='';
else
SET @numstring=SUBSTRING(@invoice_numstring,1,16);
if(@numstring='System generated')then
	if(@autoval=0)then
		SET @autoval=SUBSTRING(@invoice_numstring,17);
    end if;
SELECT GENERATE_INVOICE_NUMBER(@autoval) INTO @invoice_number;
else
    SET @invoice_number=@invoice_numstring;
end if;
    
    end if;
    
    INSERT INTO `payment_request`(`payment_request_id`,`user_id`,`merchant_id`,`customer_id`,`payment_request_type`,
`invoice_type`,`template_id`,`billing_cycle_id`,`absolute_cost`,`basic_amount`,
`tax_amount`,`invoice_total`,`swipez_total`,`convenience_fee`,`late_payment_fee`,
`grand_total`,`previous_due`,`advance_received`,`paid_amount`,`invoice_number`,
`estimate_number`,`payment_request_status`,`bill_date`,`due_date`,
`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,
`unit_available`,`converted_request_id`,`parent_request_id`,`notify_patron`,`notification_sent`,
`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`document_url`,`created_by`,
`created_date`,`last_update_by`,`currency`,`einvoice_type`)
SELECT @req_id,`user_id`,`merchant_id`,`customer_id`,5,`invoice_type`,`template_id`,`billing_cycle_id`,
@_absolute_cost,@_basic_amount,@_tax_amount,@_invoice_total + @previous_due,@_swipez_total+@previous_due,@_convenience_fee,
`late_payment_fee`,@_grand_total+@previous_due,@previous_due,`advance_received`,`paid_amount`,
@invoice_number,`estimate_number`,`payment_request_status`,CURDATE(),@due_date,`expiry_date`,`narrative`,`franchise_id`,`vendor_id`,`is_active`,`bulk_id`,`unit_available`,`converted_request_id`,@payment_request_id,`notify_patron`,`notification_sent`,`webhook_id`,`short_url`,`has_custom_reminder`,`autocollect_plan_id`,`plugin_value`,`gst_number`,`billing_profile_id`,`generate_estimate_invoice`,`document_url`,`created_by`
,CURRENT_TIMESTAMP(),`last_update_by`,`currency`,`einvoice_type`
FROM `payment_request` where payment_request_id = @payment_request_id;
    
 INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
 SELECT @req_id,s.`column_id`,s.`value`,s.`is_active`,s.`created_by`,CURRENT_TIMESTAMP(),s.`last_update_by`,CURRENT_TIMESTAMP()
FROM `invoice_column_values` s  where s.payment_request_id = @payment_request_id;

INSERT INTO `invoice_particular`
(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`gst`,
`tax_amount`,`discount_perc`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,`qty`,`unit_type`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`gst`,
`tax_amount`,`discount_perc`,`discount`,`total_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_particular` where payment_request_id = @payment_request_id;

INSERT INTO `invoice_tax`
(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`)
SELECT @req_id,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,
`created_by`,`created_date`,`last_update_by`,`last_update_date`
FROM `invoice_tax` where payment_request_id = @payment_request_id;

if(@vendor_id>0)then
INSERT INTO `invoice_vendor_commission`(`merchant_id`,`payment_request_id`,`vendor_id`,`amount`,`type`,`commission_value`,`is_active`,`created_by`,`created_date`,`last_update_by`)
select `merchant_id`,@req_id,`vendor_id`,`amount`,`type`,`commission_value`,`is_active`,`created_by`,`created_date`,`last_update_by` from invoice_vendor_commission where payment_request_id = @payment_request_id;
end if;


INSERT INTO sendingInvoiceEmail(`request_id`,`parent_request_id`,`due_date`,`template_id`,`customer_id`,`user_id`,`grand_total`,`franchise_id`,`has_custom_reminder`,plugin_value,profile_id)
SELECT `payment_request_id`,@payment_request_id,@due_date,`template_id`,`customer_id`,`user_id`,@_absolute_cost+@_convenience_fee,franchise_id,@has_custom_reminder,plugin_value,billing_profile_id FROM `payment_request`  
where payment_request_id = @req_id and notify_patron=1;

if(@smode=1)then
SET @nextbill_date= DATE_ADD(CURDATE(), INTERVAL @repeat_every DAY);
elseif(@smode=2)then
SET @nextbill_date= DATE_ADD(CURDATE(), INTERVAL @repeat_every*7 DAY);
elseif(@smode=3)then
SET @nextbill_date= DATE_ADD(CURDATE(), INTERVAL @repeat_every MONTH);
else
SET @nextbill_date= DATE_ADD(CURDATE(), INTERVAL @repeat_every YEAR);
end if;

if(@end_mode<>1 and @end_date<@nextbill_date)then
SET @nextbill_date=CURDATE();
end if;

UPDATE subscription 
SET 
    last_sent_date = CURDATE(),
    next_bill_date=@nextbill_date,
    last_updated_date = CURRENT_TIMESTAMP()
WHERE
    payment_request_id = @payment_request_id;
    
if(@period_start_date is not NULL)then
SET  @total_req_sent=0;
SELECT 
    COUNT(payment_request_id) - 1
INTO @total_req_sent FROM
    payment_request
WHERE merchant_id =@merchant_id and 
    parent_request_id = @payment_request_id;
    
SET @from_int=@total_req_sent*@period;
if(@period_type='month')then
SET @period_from_date= DATE_ADD(@period_start_date,INTERVAL @from_int MONTH);
SET @period_to_date= DATE_ADD(@period_from_date,INTERVAL @period MONTH);
SET @period_to_date= DATE_ADD(@period_to_date,INTERVAL -1 DAY);
else
SET @period_from_date= DATE_ADD(@period_start_date,INTERVAL @from_int DAY);
SET @period_to_date= DATE_ADD(@period_from_date,INTERVAL @period DAY);
end if;
SET @period_from_date=DATE_FORMAT(@period_from_date,'%d %b %Y');
SET @period_to_date=DATE_FORMAT(@period_to_date,'%d %b %Y');
UPDATE invoice_column_values v,
    invoice_column_metadata m 
SET 
    `value` = CONCAT(@period_from_date,
            ' To ',
            @period_to_date)
WHERE
    v.payment_request_id = @req_id
        AND m.function_id = 10
        AND v.column_id = m.column_id;
end if;
UPDATE invoice_column_values v,
    invoice_column_metadata m 
SET 
    `value` = @previous_due
WHERE
    v.payment_request_id = @req_id
        AND m.function_id = 4
        AND v.column_id = m.column_id;

UPDATE invoice_column_values 
SET 
    `value` = @invoice_number
WHERE
    payment_request_id = @req_id
        AND `value` = @invoice_numstring;
SET _payment_request_id = SUBSTRING(_payment_request_id, CHAR_LENGTH(@payment_request_id) + @separatorLength + 1);
    SET _due_date = SUBSTRING(_due_date, CHAR_LENGTH(@due_date) + @separatorLength + 1);
    

update customer set balance = balance + @_grand_total where customer_id=@_customer_id;

INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@_customer_id,concat('Invoice for bill date ',curdate()),@_grand_total,1,@req_id,'DEBIT',@_user_id,CURRENT_TIMESTAMP(),@_user_id);

if(@_payment_request_status!=11)then
call `stock_management`(@merchant_id,@req_id,3,1);
end if;

end if;
	
UPDATE sendingInvoiceEmail r,
    merchant m 
SET 
    r.merchant_id = m.merchant_id,
    r.merchant_domain = m.merchant_domain,
    r.merchant_type = m.merchant_type
WHERE
    r.user_id = m.user_id;
    

UPDATE sendingInvoiceEmail r,
    config c 
SET 
    r.merchant_domain_name = c.config_value
WHERE
    r.merchant_domain = c.config_key
        AND c.config_type = 'merchant_domain';
        


    

    
SELECT 
    *
FROM
    sendingInvoiceEmail;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_autocollect_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_autocollect_transaction`(_subscription_id INT,_payment_id varchar(20),_referenceId varchar(20),_amount decimal(11,2),_date datetime,_status varchar(20))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        SET @message='failed';
        show errors;
        	ROLLBACK;
		END; 
START TRANSACTION;
SET @payment_transaction_id=null;
SET @payment_request_id=null;
select payment_transaction_id,transaction_id into @payment_transaction_id,@transaction_id from autocollect_transaction where subscription_id=subscription_id and pg_ref=_payment_id;

select plan_id,merchant_id,customer_id,invoice_subscription_id into @plan_id,@merchant_id,@customer_id,@invoice_subscription_id 
from autocollect_subscriptions where subscription_id =_subscription_id;

select payment_request_id into @parent_request_id from subscription where subscription_id=@invoice_subscription_id;
select payment_request_id into @payment_request_id from payment_request where merchant_id=@merchant_id and parent_request_id=@parent_request_id and
payment_request_status in (0,5,4) and grand_total=_amount limit 1;

if(@payment_transaction_id is null and @payment_request_id is not null)then
select generate_sequence('Pay_Trans') into @payment_transaction_id;
select user_id into @merchant_user_id from merchant where merchant_id=@merchant_id;
select fee_detail_id,pg_id into @fee_id,@pg_id from merchant_fee_detail where merchant_id=@merchant_id and is_active=1 limit 1;
if(_status='SUCCESS')then
SET @status=1;
else
SET @status=4;
end if;
SET @countp=0;
select count(payment_request_id) into @countp from payment_transaction where payment_request_id=@payment_request_id and payment_transaction_status=1;
if(@countp=0)then
INSERT INTO `payment_transaction` (`pay_transaction_id`, `payment_request_id`, `customer_id`, `patron_user_id`, `merchant_id`, `merchant_user_id`,
 `amount`,  `payment_request_type`,
 `payment_transaction_status`,  `pg_id`, `fee_id`, `pg_ref_no`, `pg_ref_1`, `payment_mode`, `narrative`, `paid_on`, `created_by`,
 `created_date`, `last_update_by`) 
 VALUES (@payment_transaction_id, @payment_request_id, @customer_id, @customer_id, @merchant_id, @merchant_user_id, _amount,
'1',@status,@pg_id, @fee_id, _payment_id, _referenceId, 'Auto collect', 'Auto collect payment', _date, @customer_id, _date,@customer_id);

end if;

if(@transaction_id>0)then
update autocollect_transaction set payment_transaction_id=@payment_transaction_id where transaction_id=@transaction_id;
else
INSERT INTO `autocollect_transaction` (`merchant_id`, `subscription_id`, `plan_id`, `payment_transaction_id`, `amount`,
 `description`, `status`, `pg_ref`, `pg_ref1`,  `created_by`, `created_date`, `last_update_by`) 
VALUES (@merchant_id, _subscription_id, @plan_id, @payment_transaction_id, _amount, 'Subscription payment', _status, _payment_id, _referenceId, @customer_id, _date,@customer_id);
end if;
end if;

commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_bulk_expense` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_bulk_expense`(_expense_id int,_expense_no varchar(45))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @gst_number='';

select merchant_id into @merchant_id from staging_expense where expense_id=_expense_id;
select gst_number into @gst_number from merchant_billing_profile where merchant_id=@merchant_id and is_default=1;


INSERT INTO `expense`(`type`,`merchant_id`,`vendor_id`,`category_id`,`department_id`,`expense_no`,`invoice_no`,`bill_date`,
`due_date`,`tds`,`discount`,`adjustment`,`payment_status`,`payment_mode`,`base_amount`,`cgst_amount`,
`sgst_amount`,`igst_amount`,`total_amount`,`notify`,`narrative`,`file_path`,`bulk_id`,`gst_number`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
select `type`,`merchant_id`,`vendor_id`,`category_id`,`department_id`,_expense_no,`invoice_no`,`bill_date`,
`due_date`,`tds`,`discount`,`adjustment`,`payment_status`,`payment_mode`,`base_amount`,`cgst_amount`,
`sgst_amount`,`igst_amount`,`total_amount`,`notify`,`narrative`,`file_path`,`bulk_id`,@gst_number,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
from staging_expense where expense_id=_expense_id;

SET @expense_id=LAST_INSERT_ID();

INSERT INTO `expense_detail`
(`expense_id`,`particular_name`,`product_id`,`sac_code`,`qty`,`rate`,`sale_price`,`amount`,`tax`,`cgst_amount`,`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
select @expense_id,`particular_name`,`product_id`,`sac_code`,`qty`,`rate`,`sale_price`,`amount`,`tax`,`cgst_amount`,`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
from staging_expense_detail where expense_id=_expense_id;
commit;
SET @status='success';
select @status as 'status';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_change_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_change_details`(_user_id varchar(10),_source INT,_source_id varchar(10),_status INT,_customer_id INT,_fname varchar(100),_lname varchar(100),_email varchar(250),_mobile varchar(20),_address varchar(250),_city varchar(45),_state varchar(45),_zip varchar(20))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
show errors;
ROLLBACK;
END; 
SET @customer_id=_customer_id;
SET @source_type=_source;
SET @source_id=_source_id;
SET @user_id =_user_id;
SET @status=_status;
SET @change_det_id=0;

SET @fname=TRIM(LOWER(_fname));
SET @lname=TRIM(LOWER(_lname));
SET @email=TRIM(LOWER(_email));
SET @mobile=TRIM(LOWER(_mobile));
SET @address=TRIM(LOWER(_address));
SET @city=TRIM(LOWER(_city));
SET @state=TRIM(LOWER(_state));
SET @zip=TRIM(LOWER(_zip));

select merchant_id,first_name,last_name,address,city,state,zipcode,email,mobile into @merchant_id,@ex_first_name,@ex_last_name,@ex_address,@ex_city,@ex_state,@ex_zipcode,@ex_email,@ex_mobile from customer
 where customer_id=@customer_id;
 

SET @exfirst_name=TRIM(LOWER(@ex_first_name));
SET @exlast_name=TRIM(LOWER(@ex_last_name));
SET @exemail=TRIM(LOWER(@ex_email));
SET @exmobile=TRIM(LOWER(@ex_mobile));
SET @exaddress=TRIM(LOWER(@ex_address));
SET @excity=TRIM(LOWER(@ex_city));
SET @exstate=TRIM(LOWER(@ex_state));
SET @exzipcode=TRIM(LOWER(@ex_zipcode));


if(@exfirst_name<>@fname OR @exlast_name<>@lname OR @exemail<>@email OR @exmobile<>@mobile OR @exaddress<>@address 
OR @excity<>@city OR @exstate<>@state OR @exzipcode<>@zip) then

INSERT INTO `customer_data_change`(`customer_id`,`merchant_id`,`source_id`,
`source_type`,`status`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@customer_id,@merchant_id,@user_id,@source_type,@status,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

select LAST_INSERT_ID() into @change_id;

if(@exfirst_name<>@fname)then
select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=1 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_first_name,`changed_value`=_fname where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,1,@customer_id,@customer_id,@ex_first_name,_fname,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exlast_name<>@lname)then

select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=2 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_last_name,`changed_value`=_lname where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,2,@customer_id,@customer_id,@ex_last_name,_lname,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exemail<>@email)then

select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=3 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_email,`changed_value`=_email where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,3,@customer_id,@customer_id,@ex_email,_email,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exmobile<>@mobile)then

select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=4 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_mobile,`changed_value`=_mobile where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,4,@customer_id,@customer_id,@ex_mobile,_mobile,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exaddress<>@address and @address<>'')then
	select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=5 and `status`=0 and `customer_id`=@customer_id;
	if(@change_det_id>0)then
		update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_address,`changed_value`=_address where `change_detail_id`=@change_det_id;
	else
		INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
		`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
		VALUES(@change_id,5,@customer_id,@customer_id,@ex_address,_address,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
	end if;
end if;

SET @change_det_id=0;

if(@excity<>@city and @city<>'')then
select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=6 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_city,`changed_value`=_city where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,6,@customer_id,@customer_id,@ex_city,_city,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exstate<>@state and @state<>'')then
select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=7 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_state,`changed_value`=_state where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,7,@customer_id,@customer_id,@ex_state,_state,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exzipcode<>@zip and @zip<>'')then
select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=8 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_zipcode,`changed_value`=_zip where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,8,@customer_id,@customer_id,@ex_zipcode,_zip,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;




INSERT INTO `pending_change`(`change_id`,`source_id`,`source_type`,`status`,`created_by`,
`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,@source_id,@source_type,@status,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

end if;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_event_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_event_transaction`(_payment_request_id varchar(10),_customer_id INT, _patron_id varchar(10),_user_id varchar(10),
 _amount decimal(11,2),_tax_amount decimal(11,2),_discount_amount decimal(11,2), _pg_id INT,_fee_id INT, _seat INT,_occurence_id longtext, _package_id longtext,_attendee_customer_id longtext,
 _coupon_id INT,_narrative varchar(500),_custom_column_id longtext,_custom_column_value longtext,_franchise_id INT,_vendor_id INT,_currency char(3))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SELECT 
    merchant_id
INTO @merchat_id FROM
    merchant
WHERE
    user_id = _user_id;

SELECT GENERATE_SEQUENCE('Pay_Trans') INTO @transaction_id;

SET @grand_total=_amount - _tax_amount + _discount_amount;

INSERT INTO `payment_transaction`(`pay_transaction_id`,`payment_request_id`,`customer_id`,`patron_user_id`,`paid_on`,
`merchant_user_id`,`merchant_id`,`amount`,`tax`,`discount`,`grand_total`,`unit_price`,`quantity`,`payment_request_type`,`payment_transaction_status`,
`bank_status`,`pg_id`,`fee_id`,`franchise_id`,`vendor_id`,`pg_ref_no`,`pg_ref_1`,`pg_ref_2`,`narrative`,`currency`,`created_by`,`created_date`,`last_update_by`,
`last_update_date`)VALUES(@transaction_id,_payment_request_id,_customer_id,_patron_id,CURDATE(),_user_id,@merchat_id,_amount,_tax_amount,_discount_amount,@grand_total,@grand_total/_seat,_seat,2,0,0,_pg_id,_fee_id,_franchise_id,_vendor_id,'',
'','',_narrative,_currency,_patron_id,CURRENT_TIMESTAMP(),_patron_id,CURRENT_TIMESTAMP());

SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @coupon_id=0;

SELECT 
    coupon_code
INTO @coupon_id FROM
    event_request
WHERE
    coupon_code = _coupon_id
        AND event_request_id = _payment_request_id;

WHILE _package_id != '' > 0 DO
    SET @attendee_customer_id  = SUBSTRING_INDEX(_attendee_customer_id, @separator, 1);
    SET @package_id  = SUBSTRING_INDEX(_package_id, @separator, 1);
    SET @occurence_id  = SUBSTRING_INDEX(_occurence_id, @separator, 1);
    SET @coupon_code=0;
    
SELECT 
    price
INTO @price FROM
    event_package
WHERE
    package_id = @package_id;

SELECT 
    coupon_code
INTO @coupon_id FROM
    event_package
WHERE
    coupon_code = _coupon_id
        AND package_id = @package_id;

if(@coupon_id>0) then 
SET @coupon_code=@coupon_id;
else
SET @coupon_code=0;
end if;

INSERT INTO `event_transaction_detail`(`transaction_id`,`event_request_id`,`package_id`,`occurence_id`,`customer_id`,`amount`,`coupon_code`,`created_by`,
`created_date`,`last_update_by`,`last_update_date`)VALUES(@transaction_id,_payment_request_id,@package_id,@occurence_id,@attendee_customer_id,
@price,@coupon_code,_patron_id,CURRENT_TIMESTAMP(),_patron_id,CURRENT_TIMESTAMP());

    SET _attendee_customer_id = SUBSTRING(_attendee_customer_id, CHAR_LENGTH(@attendee_customer_id) + @separatorLength + 1);
    SET _package_id = SUBSTRING(_package_id, CHAR_LENGTH(@package_id) + @separatorLength + 1);
    SET _occurence_id = SUBSTRING(_occurence_id, CHAR_LENGTH(@occurence_id) + @separatorLength + 1);
END WHILE;



WHILE _custom_column_id != '' > 0 DO
    SET @custom_column_id  = SUBSTRING_INDEX(_custom_column_id, @separator, 1);
    SET @custom_column_value  = SUBSTRING_INDEX(_custom_column_value, @separator, 1);
   
   INSERT INTO `event_capture_values`(`transaction_id`,`column_id`,`value`,`created_by`,`created_date`,
`last_update_by`,`last_update_date`)VALUES(@transaction_id,@custom_column_id,@custom_column_value,_patron_id,CURRENT_TIMESTAMP(),_patron_id,CURRENT_TIMESTAMP());

    SET _custom_column_id = SUBSTRING(_custom_column_id, CHAR_LENGTH(@custom_column_id) + @separatorLength + 1);
    SET _custom_column_value = SUBSTRING(_custom_column_value, CHAR_LENGTH(@custom_column_value) + @separatorLength + 1);
END WHILE;


SELECT @transaction_id AS 'transaction_id';

commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_invoice_particular` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_invoice_particular`(_payment_request_id char(10),_payment_request_status INT,_particular_id longtext,_item longtext
,_annual_recurring_charges longtext,_sac_code longtext,_description longtext,_qty longtext,_unit_type longtext,_rate longtext,_mrp longtext,_product_expiry_date longtext,_product_number longtext,_gst longtext
,_tax_amount longtext,_discount_perc longtext,_discount longtext,_total_amount longtext,_narrative longtext,_user_id char(10),_merchant_id char(10),_staging tinyint(1),_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @particular_id = '';
SET @item = '';
SET @annual_recurring_charges = '';
SET @sac_code = '';
SET @description = '';
SET @unit_type=null;
SET @qty = 0;
SET @rate = 0;
SET @mrp = 0;
SET @product_expiry_date = '';
SET @product_number = '';
SET @gst =0;
SET @tax_amount = 0;
SET @discount_perc = 0;
SET @discount = 0;
SET @total_amount = 0;
SET @narrative = '';
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
if(_staging=1)then
select bulk_id into _bulk_id from staging_payment_request where payment_request_id=_payment_request_id;
update staging_invoice_particular set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
else
update invoice_particular set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
end if;
WHILE _item != '' > 0 DO
SET @total_amount = 0;
	SET @item  = SUBSTRING_INDEX(_item, @separator, 1);
	SET _item = SUBSTRING(_item, CHAR_LENGTH(@item) + @separatorLength + 1);
    
    SET @annual_recurring_charges=null;
    SET @sac_code=null;
    SET @unit_type=null;
    SET @description=null;
    
	if(_annual_recurring_charges!='')then
	SET @annual_recurring_charges  = SUBSTRING_INDEX(_annual_recurring_charges, @separator, 1);
	SET _annual_recurring_charges = SUBSTRING(_annual_recurring_charges, CHAR_LENGTH(@annual_recurring_charges) + @separatorLength + 1);
	end if;
    
	if(_sac_code!='')then
	SET @sac_code  = SUBSTRING_INDEX(_sac_code, @separator, 1);
	SET _sac_code = SUBSTRING(_sac_code, CHAR_LENGTH(@sac_code) + @separatorLength + 1);
	end if;
    
    if(_unit_type!='')then
	SET @unit_type  = SUBSTRING_INDEX(_unit_type, @separator, 1);
	SET _unit_type = SUBSTRING(_unit_type, CHAR_LENGTH(@unit_type) + @separatorLength + 1);
	end if;
    
	if(_description!='')then
        SET @description  = SUBSTRING_INDEX(_description, @separator, 1);
	SET _description = SUBSTRING(_description, CHAR_LENGTH(@description) + @separatorLength + 1);
	end if;
    
	if(_qty!='')then
	SET @qty  = SUBSTRING_INDEX(_qty, @separator, 1);
	SET _qty = SUBSTRING(_qty, CHAR_LENGTH(@qty) + @separatorLength + 1);
	end if;
    
    if(@qty='')then
    SET @qty=0;
    end if;
        
	if(_rate<>'')then
	SET @rate  = SUBSTRING_INDEX(_rate, @separator, 1);
	SET _rate = SUBSTRING(_rate, CHAR_LENGTH(@rate) + @separatorLength + 1);
	end if;
    
    if(@rate='')then
    SET @rate=0;
    end if;
    SET @gst=0;
	if(_gst<>'')then
        SET @gst  = SUBSTRING_INDEX(_gst, @separator, 1);
		SET _gst = SUBSTRING(_gst, CHAR_LENGTH(@gst) + @separatorLength + 1);
	end if;
    
    if(@gst='')then
    SET @gst=0;
    end if;
    
	if(_tax_amount<>'')then
        SET @tax_amount  = SUBSTRING_INDEX(_tax_amount, @separator, 1);
	SET _tax_amount = SUBSTRING(_tax_amount, CHAR_LENGTH(@tax_amount) + @separatorLength + 1);
	end if;
    
    if(@tax_amount='')then
    SET @tax_amount=0;
    end if;
    
	if(_discount_perc<>'')then
        SET @discount_perc  = SUBSTRING_INDEX(_discount_perc, @separator, 1);
	SET _discount_perc = SUBSTRING(_discount_perc, CHAR_LENGTH(@discount_perc) + @separatorLength + 1);
	end if;
    if(@discount_perc='')then
    SET @discount_perc=0;
    end if;
	
	if(_mrp<>'')then
        SET @mrp  = SUBSTRING_INDEX(_mrp, @separator, 1);
	SET _mrp = SUBSTRING(_mrp, CHAR_LENGTH(@mrp) + @separatorLength + 1);
	end if;
    if(@mrp='')then
    SET @mrp=0;
    end if;
	
	if(_product_expiry_date<>'')then
        SET @product_expiry_date  = SUBSTRING_INDEX(_product_expiry_date, @separator, 1);
	SET _product_expiry_date = SUBSTRING(_product_expiry_date, CHAR_LENGTH(@product_expiry_date) + @separatorLength + 1);
	end if;
    if(@product_expiry_date='')then
    SET @product_expiry_date=0;
    end if;
	
	if(_product_number<>'')then
        SET @product_number  = SUBSTRING_INDEX(_product_number, @separator, 1);
	SET _product_number = SUBSTRING(_discount_perc, CHAR_LENGTH(@product_number) + @separatorLength + 1);
	end if;
    if(@product_number='')then
    SET @product_number=0;
    end if;
	
	if(_discount<>'')then
        SET @discount  = SUBSTRING_INDEX(_discount, @separator, 1);
	SET _discount = SUBSTRING(_discount, CHAR_LENGTH(@discount) + @separatorLength + 1);
	end if;
    
    if(@discount='')then
    SET @discount=0;
    end if;
    
	if(_total_amount!='')then
        SET @total_amount  = SUBSTRING_INDEX(_total_amount, @separator, 1);
	SET _total_amount = SUBSTRING(_total_amount, CHAR_LENGTH(@total_amount) + @separatorLength + 1);
	end if;
    
    
    if(@total_amount='')then
    SET @total_amount=0;
    end if;
    
    if(@total_amount>0 and @qty=0)then
    SET @qty=1;
    end if;
    
    if(@total_amount>0 and @rate=0)then
    SET @rate=@total_amount;
    end if;
    
	if(_narrative!='')then
	SET @narrative  = SUBSTRING_INDEX(_narrative, @separator, 1);
	SET _narrative = SUBSTRING(_narrative, CHAR_LENGTH(@narrative) + @separatorLength + 1);
	end if;
    
	SET @particular_id  = SUBSTRING_INDEX(_particular_id, @separator, 1);
	SET _particular_id = SUBSTRING(_particular_id, CHAR_LENGTH(@particular_id) + @separatorLength + 1);
    
    SET @product_id=null;
    select product_id into @product_id from merchant_product where merchant_id=_merchant_id and product_name=@item limit 1;
    
    if(@product_id>0)then
    SET @product_id=@product_id;
    else
		if(@rate=0)then
        SET @product_rate=@total_amount;
        else
        SET @product_rate=@rate;
        end if;
        SET @unit_type_id=0;
        if(@unit_type<>'')then
        select id into @unit_type_id from merchant_unit_type where `name`=@unit_type and merchant_id in ('system',_merchant_id) limit 1;
        if(@unit_type_id>0)then
        SET @unit_type_id=@unit_type_id;
        else
        INSERT INTO `merchant_unit_type`(`name`,`merchant_id`,`created_by`,`created_date`,`last_update_by`)
		VALUES(@unit_type,_merchant_id,_user_id,CURRENT_TIMESTAMP(),_user_id);
        SET @unit_type_id=LAST_INSERT_ID();
        end if;
        end if;
		INSERT INTO `merchant_product`(`merchant_id`,`sac_code`,`product_name`,`description`,`gst_percent`,`price`,`unit_type_id`,
        `unit_type`,`product_number`,`mrp`,`product_expiry_date`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_merchant_id,@sac_code,@item,@description,@gst,@product_rate,@unit_type_id,@unit_type,@product_number,@mrp,@product_expiry_date,_user_id,CURRENT_TIMESTAMP(),_user_id);
        SET @product_id=LAST_INSERT_ID();
    end if;

	if(@particular_id>0)then
		if(_staging=1)then
			UPDATE `staging_invoice_particular` SET `product_id` = @product_id,`item` = @item,`annual_recurring_charges` = @annual_recurring_charges,`sac_code` = @sac_code,`description` = @description,`qty` = @qty,`unit_type`=@unit_type,`rate` = @rate,`mrp` = @mrp,`product_expiry_date` = @product_expiry_date,`product_number` = @product_number,`gst` = @gst,`tax_amount` = @tax_amount,`discount_perc` = @discount_perc,`discount` = @discount,`total_amount` = @total_amount,`narrative` = @narrative,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @particular_id;
        else
			UPDATE `invoice_particular` SET `product_id` = @product_id,`item` = @item,`annual_recurring_charges` = @annual_recurring_charges,`sac_code` = @sac_code,`description` = @description,`qty` = @qty,`unit_type`=@unit_type,`rate` = @rate,`mrp` = @mrp,`product_expiry_date` = @product_expiry_date,`product_number` = @product_number,`gst` = @gst,`tax_amount` = @tax_amount,`discount_perc` = @discount_perc,`discount` = @discount,`total_amount` = @total_amount,`narrative` = @narrative,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @particular_id;
		end if;
	else
    if(@item<>'' or @total_amount<>0)then
		if(_staging=1)then
        INSERT INTO `staging_invoice_particular`(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,
			`qty`,`unit_type`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`gst`,`tax_amount`,`discount_perc`,`discount`,`total_amount`,`narrative`,`bulk_id`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_payment_request_id,@product_id,@item,@annual_recurring_charges,@sac_code,@description,@qty,@unit_type,@rate,@mrp,@product_expiry_date,@product_number,@gst,@tax_amount,@discount_perc,@discount,@total_amount,@narrative,_bulk_id,_user_id,CURRENT_TIMESTAMP(),_user_id);
		else
				INSERT INTO `invoice_particular`(`payment_request_id`,`product_id`,`item`,`annual_recurring_charges`,`sac_code`,`description`,
			`qty`,`unit_type`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`gst`,`tax_amount`,`discount_perc`,`discount`,`total_amount`,`narrative`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_payment_request_id,@product_id,@item,@annual_recurring_charges,@sac_code,@description,@qty,@unit_type,@rate,@mrp,@product_expiry_date,@product_number,@gst,@tax_amount,@discount_perc,@discount,@total_amount,@narrative,_user_id,CURRENT_TIMESTAMP(),_user_id);
		end if;
	end if;
    end if;

END WHILE;

if(_staging<>1 and _payment_request_status!=11)then
call `stock_management`(_merchant_id,_payment_request_id,3,1);
end if;

SET @message='success';
commit;

select @message as 'message';


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_invoice_reminder` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
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


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_invoice_tax` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_invoice_tax`(_payment_request_id char(10),_tax_id longtext,_tax_percent longtext,_tax_applicable longtext,_tax_amt longtext,_tax_detail_id longtext,_user_id varchar(10),_staging tinyint(1),_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;


SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

if(_staging=1)then
select bulk_id into _bulk_id from staging_payment_request where payment_request_id=_payment_request_id;
update staging_invoice_tax set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
else
update invoice_tax set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
end if;

WHILE _tax_id != '' > 0 DO

	SET @tax_id  = SUBSTRING_INDEX(_tax_id, @separator, 1);
	SET _tax_id = SUBSTRING(_tax_id, CHAR_LENGTH(@tax_id) + @separatorLength + 1);
    
	SET @tax_percent  = SUBSTRING_INDEX(_tax_percent, @separator, 1);
	SET _tax_percent = SUBSTRING(_tax_percent, CHAR_LENGTH(@tax_percent) + @separatorLength + 1);
    
    SET @tax_applicable  = SUBSTRING_INDEX(_tax_applicable, @separator, 1);
	SET _tax_applicable = SUBSTRING(_tax_applicable, CHAR_LENGTH(@tax_applicable) + @separatorLength + 1);
    
    if(@tax_applicable>0)then
    SET @tax_applicable=@tax_applicable;
    else
    SET @tax_applicable=0;
    end if;
    
    
    SET @tax_amt  = SUBSTRING_INDEX(_tax_amt, @separator, 1);
	SET _tax_amt = SUBSTRING(_tax_amt, CHAR_LENGTH(@tax_amt) + @separatorLength + 1);
    
    if(@tax_amt>0)then
    SET @tax_amt=@tax_amt;
    else
    SET @tax_amt=0;
    end if;
    
	SET @tax_detail_id  = SUBSTRING_INDEX(_tax_detail_id, @separator, 1);
	SET _tax_detail_id = SUBSTRING(_tax_detail_id, CHAR_LENGTH(@tax_detail_id) + @separatorLength + 1);

if(@tax_id>0)then
	if(@tax_detail_id>0)then
		if(_staging=1)then
			UPDATE `staging_invoice_tax` SET `tax_id` = @tax_id,`tax_percent` = @tax_percent,`applicable` = @tax_applicable,`tax_amount` = @tax_amt,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @tax_detail_id;
		else
			UPDATE `invoice_tax` SET `tax_id` = @tax_id,`tax_percent` = @tax_percent,`applicable` = @tax_applicable,`tax_amount` = @tax_amt,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @tax_detail_id;
		end if;
	else
    if(_staging=1)then
			INSERT INTO `staging_invoice_tax`(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`bulk_id`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_payment_request_id,@tax_id,@tax_percent,@tax_applicable,@tax_amt,_bulk_id,_user_id,CURRENT_TIMESTAMP(),_user_id);
		else
			INSERT INTO `invoice_tax`(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_payment_request_id,@tax_id,@tax_percent,@tax_applicable,@tax_amt,_user_id,CURRENT_TIMESTAMP(),_user_id);
		end if;
	
	end if;
end if;
END WHILE;

SET @message='success';
SET @has_expense=0;
commit;

if(_staging=0)then
	select customer_merchant_id into @customer_merchant_id from payment_request p inner join customer c on p.customer_id=c.customer_id  where p.payment_request_id=_payment_request_id;
    if(@customer_merchant_id is not null)then
		call convert_invoice_to_expense(_payment_request_id,@customer_merchant_id);
        SET @has_expense=1;
    end if;
end if;

select @message as 'message',@has_expense as 'has_expense';


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_merchant_package` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_merchant_package`(_merchant_id char(10),_plan INT,_amount decimal(11,2),_ref_no varchar(20),_sms INT)
BEGIN

SET @merchant_plan=_plan;
SET @merchant_id=_merchant_id;
SET @base_amount=_amount*100/118;
SET @tax_amount=_amount-@base_amount;
SET @ref_no=_ref_no;
SET @sms_bought=_sms;

select package_name into @package_name from package where package_id=@merchant_plan;
select user_id into @user_id from merchant where merchant_id=@merchant_id;
select email_id,concat(first_name,' ',last_name),mobile_no  into @email,@name,@mobile from user where user_id=@user_id;
select address,city,state,zipcode into @address,@city,@state,@zipcode from merchant_billing_profile where merchant_id=@merchant_id;
select generate_sequence('Fee_transaction_id') into @trans_id;
INSERT INTO `package_transaction` (`package_transaction_id`, `user_id`, `merchant_id`, `payment_transaction_status`, `package_id`, `base_amount`,tax_amount,tax_text, `amount`, `narrative`, `pg_type`, `pg_ref_no`, `pg_ref_1`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) 
VALUES (@trans_id, @user_id, @merchant_id, '1', @merchant_plan, @base_amount,@tax_amount,'["SGST@9%","CGST@9%"]', @base_amount+@tax_amount, @package_name, '3', @ref_no, @ref_no, 'ADMIN', current_timestamp(), 'ADMIN', current_timestamp());
INSERT INTO `package_transaction_details`(`package_transaction_id`,`name`,`email`,`mobile`,`address`,`city`,`state`,`zipcode`,`created_date`)
VALUES(@trans_id,@name,@email,@mobile,@address,@city,@state,@zipcode,current_timestamp());
update account set is_active=0 where merchant_id=@merchant_id;
SET @end_date=DATE_ADD(now(), INTERVAL 12 MONTH);
update merchant set merchant_plan=@merchant_plan,package_expiry_date=@end_date where merchant_id=@merchant_id;
INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,total_invoices,`bulk_invoice`,`free_sms`,`merchant_role`,coupon,supplier,
`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
select @merchant_id,package_id,@trans_id,package_cost,individual_invoice,total_invoices,bulk_invoice,free_sms,merchant_role,coupon,supplier,now(),DATE_ADD(now(), INTERVAL duration MONTH),1,@merchant_id,now(),@merchant_id from package where package_id=@merchant_plan;
INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
`last_update_by`)VALUES(7,@merchant_id,@trans_id,@sms_bought,@sms_bought,now(),@end_date,1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');

if(@merchant_plan=3)then
INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
`last_update_by`)VALUES(6,@merchant_id,@trans_id,1,1,now(),DATE_ADD(now(), INTERVAL 12 MONTH),1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');

INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
`last_update_by`)VALUES(8,@merchant_id,@trans_id,1,1,now(),DATE_ADD(now(), INTERVAL 12 MONTH),1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_merchant_PG_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_merchant_PG_details`(_merchant_id varchar(10))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

UPDATE `merchant` 
SET 
    `is_legal_complete` = '1',
    `merchant_type` = '2'
WHERE
    `merchant_id` = _merchant_id;

    
    SELECT 
    account_holder_name
INTO @account_holder_name FROM
    merchant_bank_detail
WHERE `merchant_id` = _merchant_id limit 1;

SET @countpg=0;
select count(*) into @countpg from  merchant_fee_detail where merchant_id=_merchant_id;
if(@countpg=0)then
	INSERT INTO `merchant_fee_detail`(`merchant_id`,`pg_id`,`swipez_fee_type`,`swipez_fee_val`,`pg_fee_type`,`pg_fee_val`,
	`pg_tax_type`,`pg_tax_val`,`surcharge_enabled`,`is_active`,`created_date`,`last_update_date`) VALUES
	(_merchant_id, '15', 'F', '0.00', 'F', '0', 'ST', '0', '0', '1', NOW(), NOW());

	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '1',  @account_holder_name, '1', '1.87', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '2',  @account_holder_name, '1', '0.75', '0.75', '2000.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '2',  @account_holder_name, '1', '1.00', '1.00', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '3',  @account_holder_name, '1', '1.87', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '4',  @account_holder_name, '1', '1.87', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '5',  @account_holder_name, '1', '1.2', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '6',  @account_holder_name, '1', '1.90', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
end if;
commit;
set @message  = 'success';
SELECT @message AS 'message';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_new_structure` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_new_structure`(_type tinyint(1),_template_id char(10),_request_id char(10),_template_type varchar(30),_merchant_id char(10))
BEGIN 
DECLARE bDone INT;
DECLARE col_id INT;
DECLARE mid CHAR(10);
DECLARE muid CHAR(10);
DECLARE curs CURSOR FOR SELECT v.column_id FROM  invoice_column_metadata m inner join invoice_column_values v on m.column_id=v.column_id  where m.template_id=_template_id and v.payment_request_id=_request_id and m.column_type in ('PF','TF') and v.is_active=1;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
END;

Drop TEMPORARY  TABLE  IF EXISTS temp_req_list;

if(_type=2)then
	update invoice_tax set is_active=0 where payment_request_id=_request_id;
	update invoice_particular set is_active=0 where payment_request_id=_request_id;
end if;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_req_list (
    `id` INT NOT NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    OPEN curs;
    SET bDone = 0;
    REPEAT
		FETCH curs INTO col_id;
      
	select count(id) into @count from temp_req_list where id=col_id;
    if(@count<1)then
		insert into temp_req_list(id) values(col_id);
        select column_type into @column_type from invoice_column_metadata where column_id=col_id;
 if(@column_type='PF') then      
	CASE _template_type
      WHEN 'society' THEN 
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        select value into @val5 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+4;
        INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`description`,`narrative`,`qty`,`rate`,`total_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@val1,'',@val5,@val3,@val2,@val4,@user_id,CURRENT_TIMESTAMP(),@user_id);

	WHEN 'school' THEN 
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`description`,`narrative`,`total_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@val1,@val2,@val4,@val3,@user_id,CURRENT_TIMESTAMP(),@user_id);
    WHEN 'hotel' THEN 
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`qty`,`rate`,`total_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@val1,@val3,@val2,@val4,@user_id,CURRENT_TIMESTAMP(),@user_id);
	WHEN 'isp' THEN 
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`description`,`annual_recurring_charges`,`total_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@val1,@val3,@val2,@val4,@user_id,CURRENT_TIMESTAMP(),@user_id);        

	END CASE;
        
        else
        select value,created_by into @val1,@user_id from invoice_column_values where payment_request_id=_request_id and column_id=col_id;
        select value into @val2 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+1;
        select value into @val3 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+2;
        select value into @val4 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+3;
        select value into @val5 from invoice_column_values where payment_request_id=_request_id and column_id=col_id+4;

		SET @tax_id=0;  
        SELECT `tax_id` INTO @tax_id FROM `merchant_tax` WHERE `merchant_id` = _merchant_id  AND `tax_name` = @val1 and percentage=@val2 LIMIT 1;
        if(@tax_id=0) then
			INSERT INTO `merchant_tax`(`merchant_id`,`type`,`tax_name`,`percentage`,`description`,`created_by`,`created_date`,`last_update_by`)
			VALUES(_merchant_id,1,@val1,@val2,@val5,@user_id,CURRENT_TIMESTAMP(),@user_id);
			SET @tax_id=LAST_INSERT_ID();
		end if;
        INSERT INTO `invoice_tax`(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`created_by`,`created_date`,`last_update_by`)
        VALUES(_request_id,@tax_id,@val2,@val3,@val4,@user_id,CURRENT_TIMESTAMP(),@user_id);
        end if;
	 end if;
      
    UNTIL bDone END REPEAT;
    CLOSE curs;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_offline_event_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_offline_event_transaction`(_payment_request_id varchar(10), _customer_id INT,_merchant_user_id varchar(10),
_amount decimal(11,2),_tax_amount decimal(11,2),_discount_amount decimal(11,2),_price longtext, _bank_id varchar(200), _date datetime,_bank_ref_no varchar(20),_cheque_no INT,_cash_paid_to varchar(100),_response_type INT,_seat INT,_occurence_id longtext,
_package_id longtext,_attendee_name longtext, _mobile longtext,_age longtext,_coupon_id INT,_cheque_status varchar(20),_currency char(3)	)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @coupon_id=0;

select generate_sequence('Offline_respond_id') into @transaction_id;

select coupon_code,franchise_id,vendor_id,merchant_id into @coupon_id,@franchise_id,@vendor_id,@merchant_id from event_request where coupon_code=_coupon_id and  event_request_id =_payment_request_id;

SET @grand_total=_amount - _tax_amount + _discount_amount;

INSERT INTO `offline_response`(`offline_response_id`,`payment_request_id`,`payment_request_type`,`customer_id`,`merchant_id`,`merchant_user_id`,`offline_response_type`,
`settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`tax`,`discount`,`grand_total`,`quantity`,`cheque_no`,`cash_paid_to`,`cheque_status`,`created_by`,`created_date`, `last_update_by`,
`last_update_date`,franchise_id,vendor_id,currency)
	VALUES (@transaction_id,_payment_request_id,2,_customer_id,@merchant_id,_merchant_user_id,_response_type,_date,_bank_ref_no,_bank_id,_amount,_tax_amount,_discount_amount,@grand_total,_seat,_cheque_no,_cash_paid_to,_cheque_status,_merchant_user_id, CURRENT_TIMESTAMP(),_merchant_user_id,CURRENT_TIMESTAMP(),@franchise_id,@vendor_id,_currency);

SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);




WHILE _package_id != '' > 0 DO
    SET @attendee_name  = SUBSTRING_INDEX(_attendee_name, @separator, 1);
    SET @package_id  = SUBSTRING_INDEX(_package_id, @separator, 1);
    SET @occurence_id  = SUBSTRING_INDEX(_occurence_id, @separator, 1);
    SET @mobile  = SUBSTRING_INDEX(_mobile, @separator, 1);
    SET @age  = SUBSTRING_INDEX(_age, @separator, 1);
    SET @price  = SUBSTRING_INDEX(_price, @separator, 1);
	SET @coupon_code=0;
    if(@age>0)then
    SET @age=@age;
    else
    SET @age=0;
    end if;

	
select price into @price from event_package where  package_id=@package_id;

select coupon_code into @coupon_id from event_package where coupon_code=_coupon_id and  package_id=@package_id;

if(@coupon_id>0) then 
SET @coupon_code=@coupon_id;
else
SET @coupon_code=0;
end if;

INSERT INTO `event_transaction_detail`(`transaction_id`,`event_request_id`,`occurence_id`,`package_id`,`customer_id`,`mobile`,`is_paid`,`age`,`amount`,`coupon_code`,`created_by`,
`created_date`,`last_update_by`,`last_update_date`)VALUES(@transaction_id,_payment_request_id,@occurence_id,@package_id,@attendee_name,
@mobile,1,@age,@price,@coupon_code,_merchant_user_id,CURRENT_TIMESTAMP(),_merchant_user_id,CURRENT_TIMESTAMP());

    SET _attendee_name = SUBSTRING(_attendee_name, CHAR_LENGTH(@attendee_name) + @separatorLength + 1);
    SET _package_id = SUBSTRING(_package_id, CHAR_LENGTH(@package_id) + @separatorLength + 1);
    SET _occurence_id = SUBSTRING(_occurence_id, CHAR_LENGTH(@occurence_id) + @separatorLength + 1);
    SET _mobile = SUBSTRING(_mobile, CHAR_LENGTH(@mobile) + @separatorLength + 1);
    SET _age = SUBSTRING(_age, CHAR_LENGTH(@age) + @separatorLength + 1);
    SET _price = SUBSTRING(_price, CHAR_LENGTH(@price) + @separatorLength + 1);
END WHILE;


Drop TEMPORARY  TABLE  IF EXISTS temp_coupon_ids;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_coupon_ids (
     `id` int(11) NOT NULL AUTO_INCREMENT,
    `coupon_id` INT  NULL ,
    `count` INT NOT NULL ,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

insert temp_coupon_ids (coupon_id,count) select coupon_code ,count(coupon_code) from event_transaction_detail where transaction_id=@transaction_id and coupon_code<>0;
update coupon r , temp_coupon_ids m  set r.available = r.available - m.count  where r.coupon_id=m.coupon_id and r.`limit` <> 0 ;

Drop TEMPORARY  TABLE  IF EXISTS temp_coupon_ids;

commit;

select @transaction_id as 'transaction_id';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_outgoing_sms_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
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


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_slot_offline_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_slot_offline_transaction`(_type INT,_customer_id INT,_merchant_id varchar(10),_merchant_user_id varchar(10),
_amount decimal(11,2),_price longtext, _bank_id varchar(200), _date datetime,_bank_ref_no varchar(20),_cheque_no INT,_cash_paid_to varchar(100),_response_type INT,_seat INT,_narrative varchar(500))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;


select generate_sequence('Offline_respond_id') into @transaction_id;

SET @grand_total=_amount;

INSERT INTO `offline_response`(`offline_response_id`,`payment_request_id`,`payment_request_type`,`customer_id`,`merchant_id`,`merchant_user_id`,`offline_response_type`,
`settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`tax`,`discount`,`grand_total`,`quantity`,`cheque_no`,`cash_paid_to`,`narrative`,`created_by`,`created_date`, `last_update_by`,
`last_update_date`)
	VALUES (@transaction_id,0,_type,_customer_id,_merchant_id,_merchant_user_id,_response_type,_date,_bank_ref_no,_bank_id,_amount,0,0,@grand_total,_seat,_cheque_no,_cash_paid_to,_narrative,_merchant_id, CURRENT_TIMESTAMP(),_merchant_id,CURRENT_TIMESTAMP());

SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

commit;

select @transaction_id as 'transaction_id';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_sub_merchant` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_sub_merchant`(_user_id varchar(10),_email varchar(254),_fname varchar(50),_lname varchar(50),_mob_country_code varchar(6),_mobile bigint(13),_pass varchar(100),_role INT,_franchise_id INT,_group varchar(100))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
		ROLLBACK;
		END; 
START TRANSACTION;

select generate_sequence('User_id') into @user_id;

select group_id into @group_id from user where user_id=_user_id;

INSERT INTO `user`(`email_id`, `user_id`,`password`,`name`, `first_name`, `last_name`, `user_status`, `group_id`
, `user_group_type`, `user_type`,`franchise_id`,`customer_group`,mob_country_code,`mobile_no`, `created_by`, `created_date`, `last_updated_by`, `last_updated_date`)
VALUES (_email,@user_id,_pass,concat(_fname,' ',_lname),_fname,_lname,19,@group_id,2,0,_franchise_id,_group,_mob_country_code,_mobile,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

INSERT INTO `user_privileges`(`user_id`,`role_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES
(@user_id,_role,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

INSERT INTO `preferences`(`user_id`, `created_date`, `last_update_date`) values(@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

select  CONCAT(user_id, created_date) into @username from user where user_id=@user_id;

commit;
SET @message = 'success';
select @message as 'message' , @user_id as 'user_id',@username as 'usertimestamp';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_transaction_settlement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_transaction_settlement`(_payment_id varchar(45),_transaction_id varchar(10),_transaction_date datetime,_settlement_date datetime,_bank_reff varchar(20),_settlement_id varchar(20),
_captured decimal(9,2),_tdr decimal(9,2),_service_tax decimal(9,2))
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
	show errors;
ROLLBACK;
END; 

SET @id=0;

SET @merchant_user_id='';
SET @currency='INR';
	select id into @id from payment_transaction_settlement where payment_id=_payment_id;

if(@id>0)then
	SET @message='success';
	select @message as 'message';
else

	select payment_request_id,merchant_user_id,patron_user_id,currency into @payment_request_id,@merchant_user_id,@patron_user_id,@currency from payment_transaction where pay_transaction_id=_transaction_id;

	if(@merchant_user_id='')then
	select merchant_id,currency into @merchant_id,@currency from xway_transaction where xway_transaction_id=_transaction_id;
	select user_id into @merchant_user_id from merchant where merchant_id=@merchant_id;
	end if;
    
	if(@merchant_user_id='')then
	SET @merchant_id = 'M000000151';
	SET @merchant_user_id ='U000002349';
	end if;

if(@merchant_user_id='')then
	SET @message='failed';
else
if(SUBSTRING(_transaction_id, 1, 5)='cfpay')then
SET _transaction_id='NA';
end if;

if(LENGTH(_transaction_id)=10)then
	INSERT INTO `payment_transaction_settlement`
	(`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,`merchant_id`,`patron_id`,`captured`,`tdr`,
	`service_tax`,`bank_reff`,`currency`,`transaction_date`,`settlement_date`,`created_date`)VALUES(_settlement_id,_payment_id,_transaction_id,@payment_request_id,@merchant_user_id,@patron_user_id,_captured,_tdr,_service_tax,
	_bank_reff,@currency,_transaction_date,_settlement_date,CURRENT_TIMESTAMP());

	SET @id=LAST_INSERT_ID();
end if;

if(@id>0) then
	SET @message='success';
end if;
	select @message as 'message';
	end if;
end if;




END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_transaction_tdr` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_transaction_tdr`(_payment_id varchar(45),_auth_date datetime,_cap_date datetime,_payment_method varchar(100),
_bank_reff varchar(250),_transaction_id varchar(10),_patron_name varchar(250),_captured decimal(9,2),_refunded decimal(9,2),_charge_back decimal(9,2),
_tdr decimal(9,2),_service_tax decimal(9,2),_surcharge decimal(9,2),_surcharge_service_tax decimal(9,2),_emitdr decimal(9,2),_emi_service_tax decimal(9,2),_net_amount decimal(9,2))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 

SET @id=0;

SET @merchant_user_id='';

select tdr_id into @id from payment_transaction_tdr where transaction_id=_transaction_id;

if(@id>0)then
SET @message='success';
    select @message as 'message';
else
    
    select payment_request_id,merchant_user_id,patron_user_id into @payment_request_id,@merchant_user_id,@patron_user_id from payment_transaction where pay_transaction_id=_transaction_id;

if(@merchant_user_id='')Then
    select merchant_id into @merchant_id from xway_transaction where xway_transaction_id=_transaction_id;
    select user_id into @merchant_user_id from merchant where merchant_id=@merchant_id;
    SET @payment_request_id='';
    SET @patron_user_id='';
end if;

if(@merchant_user_id='')Then
    SET @merchant_id = 'M000000151';
	SET @merchant_user_id ='U000002349';
    SET @payment_request_id='';
    SET @patron_user_id='';
end if;

    INSERT INTO `payment_transaction_tdr`(`transaction_id`,`payment_request_id`,`merchant_id`,`patron_id`,`patron_name`,
    `payment_id`,`auth_date`,`cap_date`,`payment_method`,`bank_reff`,`captured`,`refunded`,`chargeback`,`tdr`,`service_tax`,`surcharge`,`surcharge_service_tax`,
    `emitdr`,`emi_service_tax`,`net_amount`,`created_date`,`last_update_date`)VALUES(_transaction_id,@payment_request_id,@merchant_user_id,@patron_user_id,
    _patron_name,_payment_id,_auth_date,_cap_date,_payment_method,_bank_reff,_captured,_refunded,_charge_back,_tdr,_service_tax,_surcharge,_surcharge_service_tax,
    _emitdr,_emi_service_tax,_net_amount,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

        SET @id=LAST_INSERT_ID();
        if(@id>0) then
        SET @message='success';
        select @message as 'message';
    end if;
end if;




END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_travel_particular` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_travel_particular`(_payment_request_id varchar(10),_texistid LONGTEXT,_btype LONGTEXT,_booking_date LONGTEXT,_journey_date LONGTEXT,_b_name LONGTEXT,_b_type LONGTEXT,_unit_type LONGTEXT,_sac_code LONGTEXT,_b_from LONGTEXT,_b_to LONGTEXT,_b_amt LONGTEXT,_b_charge LONGTEXT,_b_unit LONGTEXT,_b_rate LONGTEXT,_b_mrp LONGTEXT,_b_product_expiry_date LONGTEXT,_b_product_number LONGTEXT,_b_discount_perc LONGTEXT,_b_discount LONGTEXT,_b_gst LONGTEXT,_b_total LONGTEXT,_b_description LONGTEXT,_b_information LONGTEXT,_user_id varchar(10),_staging tinyint(1),_bulk_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;


SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

if(_staging=1)then
select bulk_id into _bulk_id from staging_payment_request where payment_request_id=_payment_request_id;
update staging_invoice_travel_particular set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
else
update invoice_travel_particular set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;
end if;


WHILE _btype != '' > 0 DO
    SET @btype  = SUBSTRING_INDEX(_btype, @separator, 1);
    SET @texistid  = SUBSTRING_INDEX(_texistid, @separator, 1);
	SET @booking_date  = SUBSTRING_INDEX(_booking_date, @separator, 1);
	SET @journey_date  = SUBSTRING_INDEX(_journey_date, @separator, 1);
	SET @b_name  = SUBSTRING_INDEX(_b_name, @separator, 1);
	SET @b_type  = SUBSTRING_INDEX(_b_type, @separator, 1);
    SET @sac_code  = SUBSTRING_INDEX(_sac_code, @separator, 1);
    SET @unit_type  = SUBSTRING_INDEX(_unit_type, @separator, 1);
	SET @b_from  = SUBSTRING_INDEX(_b_from, @separator, 1);
	SET @b_to  = SUBSTRING_INDEX(_b_to, @separator, 1);
	SET @b_amt  = SUBSTRING_INDEX(_b_amt, @separator, 1);
	SET @b_charge  = SUBSTRING_INDEX(_b_charge, @separator, 1);
    SET @b_unit  = SUBSTRING_INDEX(_b_unit, @separator, 1);
    SET @b_rate  = SUBSTRING_INDEX(_b_rate, @separator, 1);
    SET @b_mrp  = SUBSTRING_INDEX(_b_mrp, @separator, 1);
    SET @b_product_expiry_date  = SUBSTRING_INDEX(_b_product_expiry_date, @separator, 1);
    SET @b_product_number  = SUBSTRING_INDEX(_b_product_number, @separator, 1);
    SET @b_discount_perc  = SUBSTRING_INDEX(_b_discount_perc, @separator, 1);
    SET @b_discount  = SUBSTRING_INDEX(_b_discount, @separator, 1);
    SET @b_gst  = SUBSTRING_INDEX(_b_gst, @separator, 1);
	SET @b_total  = SUBSTRING_INDEX(_b_total, @separator, 1);
    SET @b_description  = SUBSTRING_INDEX(_b_description, @separator, 1);
	SET @b_information  = SUBSTRING_INDEX(_b_information, @separator, 1);
    if(@b_mrp>0)then
    SET @b_mrp=@b_mrp;
    else
    SET @b_mrp=0;
    end if;

	if(@btype='b')then
	SET @type=1;
	elseif(@btype='c')then
	SET @type=2;
    elseif(@btype='hb')then
    SET @type=3;
    elseif(@btype='fs')then
    SET @type=4;
	end if;
    if(@b_total>0)then
	if(@texistid>0)then
		if(_staging=1)then
			UPDATE `staging_invoice_travel_particular` SET `booking_date` = @booking_date,`journey_date` = @journey_date,`name` = @b_name,`vehicle_type` = @b_type,`unit_type`=@unit_type,`sac_code`=@sac_code,`from_station` = @b_from,
			`to_station` = @b_to,`amount` = @b_amt ,`charge` = @b_charge,`total` = @b_total,`units`=@b_unit,`rate`=@b_rate,`mrp`=@b_mrp,`product_expiry_date`=@b_product_expiry_date,`product_number`=@b_product_number,`discount_perc`=@b_discount_perc,`discount`=@b_discount,`gst`=@b_gst,`description` = @b_description,`information` = @b_information,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @texistid;
		else
			UPDATE `invoice_travel_particular` SET `booking_date` = @booking_date,`journey_date` = @journey_date,`name` = @b_name,`vehicle_type` = @b_type,`unit_type`=@unit_type,`sac_code`=@sac_code,`from_station` = @b_from,
			`to_station` = @b_to,`amount` = @b_amt ,`charge` = @b_charge,`total` = @b_total,`units`=@b_unit,`rate`=@b_rate,`mrp`=@b_mrp,`product_expiry_date`=@b_product_expiry_date,`product_number`=@b_product_number,`discount_perc`=@b_discount_perc,`discount`=@b_discount,`gst`=@b_gst,`description` = @b_description,`information` = @b_information,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @texistid;
		end if;
	else
		if(_staging=1)then
		INSERT INTO `staging_invoice_travel_particular`(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,
	`created_by`,`created_date`,`last_update_by`,`bulk_id`)VALUES(_payment_request_id,@booking_date,@journey_date,@b_name,@b_type,@unit_type,@sac_code,@b_from,@b_to,@b_amt,@b_charge,@b_unit,@b_rate,@b_mrp,@b_product_expiry_date,@b_product_number,@b_discount_perc,@b_discount,@b_gst,@b_total,@type,@b_description,@b_information,_user_id,CURRENT_TIMESTAMP(),_user_id,_bulk_id);
		else
        INSERT INTO `invoice_travel_particular`(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`unit_type`,`sac_code`,`from_station`,`to_station`,`amount`,`charge`,`units`,`rate`,`mrp`,`product_expiry_date`,`product_number`,`discount_perc`,`discount`,`gst`,`total`,`type`,`description`,`information`,
	`created_by`,`created_date`,`last_update_by`)VALUES(_payment_request_id,@booking_date,@journey_date,@b_name,@b_type,@unit_type,@sac_code,@b_from,@b_to,@b_amt,@b_charge,@b_unit,@b_rate,@b_mrp,@b_product_expiry_date,@b_product_number,@b_discount_perc,@b_discount,@b_gst,@b_total,@type,@b_description,@b_information,_user_id,CURRENT_TIMESTAMP(),_user_id);
		end if;
	end if;
    end if;

    SET _btype = SUBSTRING(_btype, CHAR_LENGTH(@btype) + @separatorLength + 1);
    SET _texistid = SUBSTRING(_texistid, CHAR_LENGTH(@texistid) + @separatorLength + 1);
	SET _booking_date = SUBSTRING(_booking_date, CHAR_LENGTH(@booking_date) + @separatorLength + 1);
	SET _journey_date = SUBSTRING(_journey_date, CHAR_LENGTH(@journey_date) + @separatorLength + 1);
	SET _b_name = SUBSTRING(_b_name, CHAR_LENGTH(@b_name) + @separatorLength + 1);
	SET _b_type = SUBSTRING(_b_type, CHAR_LENGTH(@b_type) + @separatorLength + 1);
    SET _unit_type = SUBSTRING(_unit_type, CHAR_LENGTH(@unit_type) + @separatorLength + 1);
    SET _sac_code = SUBSTRING(_sac_code, CHAR_LENGTH(@sac_code) + @separatorLength + 1);
	SET _b_from = SUBSTRING(_b_from, CHAR_LENGTH(@b_from) + @separatorLength + 1);
	SET _b_to = SUBSTRING(_b_to, CHAR_LENGTH(@b_to) + @separatorLength + 1);
	SET _b_amt = SUBSTRING(_b_amt, CHAR_LENGTH(@b_amt) + @separatorLength + 1);
	SET _b_charge = SUBSTRING(_b_charge, CHAR_LENGTH(@b_charge) + @separatorLength + 1);
    
    SET _b_unit = SUBSTRING(_b_unit, CHAR_LENGTH(@b_unit) + @separatorLength + 1);
    SET _b_rate = SUBSTRING(_b_rate, CHAR_LENGTH(@b_rate) + @separatorLength + 1);
    SET _b_mrp = SUBSTRING(_b_rate, CHAR_LENGTH(@b_mrp) + @separatorLength + 1);
    SET _b_product_expiry_date = SUBSTRING(_b_rate, CHAR_LENGTH(@b_product_expiry_date) + @separatorLength + 1);
    SET _b_product_number = SUBSTRING(_b_rate, CHAR_LENGTH(@b_product_number) + @separatorLength + 1);
    SET _b_discount_perc = SUBSTRING(_b_discount_perc, CHAR_LENGTH(@b_discount_perc) + @separatorLength + 1);
    SET _b_discount = SUBSTRING(_b_discount, CHAR_LENGTH(@b_discount) + @separatorLength + 1);
    SET _b_gst = SUBSTRING(_b_gst, CHAR_LENGTH(@b_gst) + @separatorLength + 1);
    
	SET _b_total = SUBSTRING(_b_total, CHAR_LENGTH(@b_total) + @separatorLength + 1);
    SET _b_description = SUBSTRING(_b_description, CHAR_LENGTH(@b_description) + @separatorLength + 1);
    SET _b_information = SUBSTRING(_b_information, CHAR_LENGTH(@b_information) + @separatorLength + 1);

END WHILE;

###############################################
SET @message='success';
commit;

select @message as 'message';


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_travel_ticket_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_travel_ticket_detail`(_payment_request_id varchar(10),_texistid LONGTEXT,_btype LONGTEXT,_booking_date LONGTEXT,_journey_date LONGTEXT,_b_name LONGTEXT,_b_type LONGTEXT,_b_from LONGTEXT,_b_to LONGTEXT,_b_amt LONGTEXT,_b_charge LONGTEXT,_b_total LONGTEXT,_user_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		show errors;
			ROLLBACK;
		END; 
START TRANSACTION;


SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);



update invoice_travel_ticket_detail set `is_active` = 0,`last_update_by` = _user_id where payment_request_id=_payment_request_id;

WHILE _btype != '' > 0 DO
    SET @btype  = SUBSTRING_INDEX(_btype, @separator, 1);
    SET @texistid  = SUBSTRING_INDEX(_texistid, @separator, 1);
	SET @booking_date  = SUBSTRING_INDEX(_booking_date, @separator, 1);
	SET @journey_date  = SUBSTRING_INDEX(_journey_date, @separator, 1);
	SET @b_name  = SUBSTRING_INDEX(_b_name, @separator, 1);
	SET @b_type  = SUBSTRING_INDEX(_b_type, @separator, 1);
	SET @b_from  = SUBSTRING_INDEX(_b_from, @separator, 1);
	SET @b_to  = SUBSTRING_INDEX(_b_to, @separator, 1);
	SET @b_amt  = SUBSTRING_INDEX(_b_amt, @separator, 1);
	SET @b_charge  = SUBSTRING_INDEX(_b_charge, @separator, 1);
	SET @b_total  = SUBSTRING_INDEX(_b_total, @separator, 1);

	if(@btype='b')then
	SET @type=1;
	else
	SET @type=2;
	end if;
    
	if(@texistid>0)then
	
	UPDATE `invoice_travel_ticket_detail` SET `booking_date` = @booking_date,`journey_date` = @journey_date,`name` = @b_name,`vehicle_type` = @b_type,`from_station` = @b_from,
	`to_station` = @b_to,`amount` = @b_amt ,`charge` = @b_charge,`total` = @b_total,`is_active` = 1,`last_update_by` = _user_id WHERE `id` = @texistid;
	
	else
	
	INSERT INTO `invoice_travel_ticket_detail`(`payment_request_id`,`booking_date`,`journey_date`,`name`,`vehicle_type`,`from_station`,`to_station`,`amount`,`charge`,`total`,`type`,
`created_by`,`created_date`,`last_update_by`)VALUES(_payment_request_id,@booking_date,@journey_date,@b_name,@b_type,@b_from,@b_to,@b_amt,@b_charge,@b_total,@type,_user_id,CURRENT_TIMESTAMP(),_user_id);
	
	end if;

    SET _btype = SUBSTRING(_btype, CHAR_LENGTH(@btype) + @separatorLength + 1);
    SET _texistid = SUBSTRING(_texistid, CHAR_LENGTH(@texistid) + @separatorLength + 1);
	SET _booking_date = SUBSTRING(_booking_date, CHAR_LENGTH(@booking_date) + @separatorLength + 1);
	SET _journey_date = SUBSTRING(_journey_date, CHAR_LENGTH(@journey_date) + @separatorLength + 1);
	SET _b_name = SUBSTRING(_b_name, CHAR_LENGTH(@b_name) + @separatorLength + 1);
	SET _b_type = SUBSTRING(_b_type, CHAR_LENGTH(@b_type) + @separatorLength + 1);
	SET _b_from = SUBSTRING(_b_from, CHAR_LENGTH(@b_from) + @separatorLength + 1);
	SET _b_to = SUBSTRING(_b_to, CHAR_LENGTH(@b_to) + @separatorLength + 1);
	SET _b_amt = SUBSTRING(_b_amt, CHAR_LENGTH(@b_amt) + @separatorLength + 1);
	SET _b_charge = SUBSTRING(_b_charge, CHAR_LENGTH(@b_charge) + @separatorLength + 1);
	SET _b_total = SUBSTRING(_b_total, CHAR_LENGTH(@b_total) + @separatorLength + 1);

END WHILE;


SET @message='success';
commit;

select @message as 'message';


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_xwaytransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_xwaytransaction`(_pg_id int,_merchant_id varchar(10), _referrer_url varchar(255),_account_id varchar(100), _secure_hash varchar(255), _return_url varchar(255), _reference_no varchar(45), _amount decimal(11,2)
,_surcharge decimal(11,2),_absolute_cost decimal(11,2),_description varchar(255),_name varchar(108),_address varchar(255),_city varchar(32),_state varchar(32),_postal_code varchar(10)
,_phone varchar(20),_email varchar(108),_udf1 varchar(250),_udf2 varchar(250),_udf3 varchar(250),_udf4 varchar(250),_udf5 varchar(250),_mdd varchar(250),_customer_code varchar(45),_franchise_id INT,_vendor_id INT,_currency varchar(10),_is_random_id INT,_type INT,_webhook_id INT,_discount decimal(11,2),_create_invoice_api INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @xtransaction_id='';
 SET @description =_description;
 SET @name = _name;
 SET @address=_address;
 SET @city =_city;
 SET @state =_state;
 SET @postal_code =_postal_code;
 SET @phone =_phone;
 SET @email=_email;
 
 if(_webhook_id=0)then
 select webhook_id into @webhook_id from merchant_webhook where merchant_id=_merchant_id and is_active=1 limit 1;
 if(@webhook_id>0)then
 SET _webhook_id=@webhook_id;
 end if;
 end if;

select logging_status into @logging_status from xway_merchant_detail where merchant_id=_merchant_id limit 1;

if(@logging_status=2)then
 SET @name = '';
 SET @address='';
 SET @city ='';
 SET @state ='';
 SET @postal_code ='';
 SET @country ='';
 SET @phone ='';
 SET @email='';
elseif(@logging_status=3)then
 SET @description ='';
 SET @name = '';
 SET @address='';
 SET @city ='';
 SET @state ='';
 SET @postal_code ='';
 SET @phone ='';
 SET @email='';
end if;

if(_currency is null)then
SET _currency='INR';
end if;

if(_currency='')then
SET _currency='INR';
end if;

if(_is_random_id=1)then
select generate_random_id('xway') into @xtransaction_id;
else
select generate_sequence('Xway_transaction_id') into @xtransaction_id;
end if;

INSERT INTO `xway_transaction`(`xway_transaction_id`,`merchant_id`,`referrer_url`,`account_id`,`customer_code`,`secure_hash`,`return_url`,`reference_no`,`amount`,`surcharge_amount`,`absolute_cost`,`description`,`name`,`address`,`city`,`state`,`postal_code`,
    `phone`,`email`,`udf1`,`udf2`,`udf3`,`udf4`,`udf5`,`create_invoice_api`,`mdd`,`pg_id`,`franchise_id`,`vendor_id`,`currency`,`type`,`webhook_id`,`discount`,`created_date`,`last_update_date`)
    values(@xtransaction_id,_merchant_id , _referrer_url ,_account_id ,_customer_code, _secure_hash , _return_url , _reference_no , _amount,_surcharge,_absolute_cost,@description ,@name ,@address ,@city ,@state ,@postal_code ,@phone ,@email
    ,_udf1 ,_udf2,_udf3 ,_udf4,_udf5 ,_create_invoice_api,_mdd,_pg_id,_franchise_id,_vendor_id,_currency,_type,_webhook_id,_discount ,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

SET @franchise_name='';
if(_franchise_id>0)then
select franchise_name into @franchise_name from franchise where franchise_id=_franchise_id;
end if;

select company_name ,merchant_id into @company_name,@company_merchant_id from merchant where merchant_id=_merchant_id;
select @xtransaction_id as 'xtransaction_id',@franchise_name as 'franchise_name',@company_name,@company_merchant_id;

commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `save_xway_payment_response` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `save_xway_payment_response`(_transaction_id varchar(10),_payment_id varchar(40),_pg_transaction_id varchar(40),_amount decimal(11,2),_mode varchar(50),_message varchar(250),_status varchar(20),_payment_mode varchar(45))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
					ROLLBACK;
                    show errors;
		END; 
START TRANSACTION;

SET @image='';
SET @pay_transaction_id=_transaction_id;
SET @payment_id=_payment_id;
SET @transaction_id=_pg_transaction_id;
SET @message=_message;
SET @franchise_id=0;
SELECT 
    `name`,
    `amount`,
    `email`,
    `phone`,
    `address`,
    `city`,
    `state`,
    `postal_code`,
    `udf1`,
    `udf2`,
    `udf3`,
    `udf4`,
    `udf5`,
    `franchise_id`,
    `vendor_id`,
    `return_url`,
    `reference_no`,
    `pg_id`,
    `type`,
    merchant_id
INTO @name,@request_amount , @email , @phone , @address , @city , @state , @postal_code , @udf1 , @udf2 , @udf3 , @udf4 , @udf5 ,@franchise_id,@vendor_id, @return_url , @reference_no,@pg_id,@xway_type , @merchant_id FROM
    xway_transaction
WHERE
    xway_transaction_id = @pay_transaction_id;

 
 if(_status='success') then
	if(@vendor_id>0 or @franchise_id>0)then
	call split_transaction(@pay_transaction_id,_amount,@vendor_id,@franchise_id,@merchant_id);
	end if;
    set @status=1;
 else
    set @status=4;
end if;	

UPDATE xway_transaction 
SET 
    xway_transaction_status = @status,
    pg_ref_no1 = _payment_id,
    payment_mode=_payment_mode,
    narrative=_message,
    pg_ref_no2 = _pg_transaction_id
WHERE
    xway_transaction_id = @pay_transaction_id;
    
SET @pg_surcharge_enabled=0;
    
    if(@xway_type=1)then
		select pg_surcharge_enabled into @pg_surcharge_enabled from xway_merchant_detail where merchant_id=@merchant_id and pg_id=@pg_id limit 1;
    else
		select pg_surcharge_enabled into @pg_surcharge_enabled from merchant_fee_detail where merchant_id=@merchant_id and pg_id=@pg_id limit 1;
    end if;

	if(@pg_surcharge_enabled=1)then
		update xway_transaction set surcharge_amount=_amount-absolute_cost,`absolute_cost`=_amount where  xway_transaction_id = @pay_transaction_id;
    end if;

select 
    company_name, user_id,merchant_domain
INTO @company_name , @merchant_user_id,@merchant_domain FROM
    merchant
WHERE
    merchant_id = @merchant_id;
    
    SELECT 
    email_id,mobile_no
INTO @merchant_email_id,@merchant_mobile_no FROM
    user
WHERE
    user_id = @merchant_user_id;
    
if(@franchise_id>0)then
select franchise_name into @franchise_name from franchise where franchise_id=@franchise_id;
SET @company_name = concat(@company_name,' (',@franchise_name,')');
end if;    


SELECT 
    @merchant_id AS 'checksum',
	@merchant_domain as 'merchant_domain',
    @pay_transaction_id AS 'transaction_id',
    _pg_transaction_id AS 'bank_ref_no',
    @reference_no AS 'reference_no',
    _mode AS 'mode',
    _status AS 'status',
    _amount AS 'amount',
    CURRENT_TIMESTAMP() AS 'date',
    _message AS 'message',
    @return_url AS 'return_url',
    @merchant_email_id AS 'merchant_email',
    @merchant_mobile_no AS 'mobile_no',
    @company_name AS 'company_name',
    @name AS 'billing_name',
    @name AS 'BillingName',
    @email AS 'billing_email',
    @phone AS 'billing_mobile',
    @address AS 'billing_address',
    @city AS 'billing_city',
    @state AS 'billing_state',
    @postal_code 'billing_postal_code',
    @franchise_id as 'franchise_id',
    @udf1 AS 'udf1',
    @udf2 AS 'udf2',
    @udf3 AS 'udf3',
    @udf4 AS 'udf4',
    @udf5 AS 'udf5',
    @request_amount as 'request_amount';



commit;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `set_partialypaid_amount` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `set_partialypaid_amount`(_payment_request_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 
SET @paid_amt=0;        
select plugin_value,payment_request_status,grand_total into @plugin_value,@payment_request_status,@grand_total from    payment_request where payment_request_id=_payment_request_id;      
if(@plugin_value like '%"has_partial":"1"%')then
select sum(amount) into @offline_amount from offline_response where payment_request_id=_payment_request_id and is_active=1 and transaction_status=1;
if(@offline_amount>0)then
SET @paid_amt=@paid_amt+@offline_amount;
end if;
select sum(amount)-sum(convenience_fee) into @online_amount from payment_transaction where payment_request_id=_payment_request_id and payment_transaction_status=1;
if(@online_amount>0)then
SET @paid_amt=@paid_amt+@online_amount;
end if;
if(@paid_amt=0 and @payment_request_status=7)then
SET @payment_request_status=0;
end if;
if(@grand_total<=@paid_amt and @payment_request_status=7)then
SET @payment_request_status=2;
end if;
update payment_request set paid_amount =@paid_amt,payment_request_status=@payment_request_status where payment_request_id=_payment_request_id;
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `split_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `split_transaction`(_transaction_id char(10),_amount decimal(11,2),_vendor_id INT,_franchise_id INT,_user_id char(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
	show errors;
    ROLLBACK;
		END; 
START TRANSACTION;

if(_vendor_id>0)then
select online_pg_settlement,commision_type,settlement_type,commission_percentage,commision_amount,merchant_id
into @online_pg_settlement,@commision_type,@settlement_type,@commission_percentage,@commision_amount,@merchant_id
from vendor where vendor_id=_vendor_id;
else
select online_pg_settlement,commision_type,settlement_type,commission_percentage,commision_amount,merchant_id
into @online_pg_settlement,@commision_type,@settlement_type,@commission_percentage,@commision_amount,@merchant_id
from franchise where franchise_id=_franchise_id;
end if;

if(@online_pg_settlement=1 and @settlement_type=2)then
	if(@commision_type=1)then
	SET @split_value=@commission_percentage;
	else
	SET @split_value=@commision_amount;
	end if;

	INSERT INTO `split_transaction`(`transaction_id`,`type`,`merchant_id`,
	`vendor_id`,`franchise_id`,`beneficiary_id`,`amount`,`split_type`,`split_value`,
	`created_by`,`created_date`,`last_update_by`)
	VALUES(_transaction_id,1,@merchant_id,_vendor_id,_franchise_id,0,_amount,@commision_type,@split_value,_user_id,CURRENT_TIMESTAMP(),_user_id);
end if; 

commit;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `stock_management` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `stock_management`(_merchant_id char(10),_id varchar(20),_ref_type INT,_type INT)
BEGIN
SET @app_id=0;

/* _type= 1 -> invoice , 2 -> expense
	_ref_type = 1 -> inventory stock added , 2- > expenses stock purchases, 3 -> invoice stock sold
*/
select account_id into @app_id from `account` where merchant_id=_merchant_id and inventory=1 and is_active=1;

if(@app_id>0)then
	SET @stock=1;
	if(_ref_type=3)then
    select payment_request_type,invoice_type into @payment_request_type,@invoice_type from payment_request where payment_request_id=_id;
		if(@payment_request_type=4 or @invoice_type=2)then
        SET @stock=0;
		end if;
	elseif(_ref_type=2)then
    select `type` into @type from expense where expense_id=_id;
    if(@type<>1)then
        SET @stock=0;
		end if;
    end if;
    if(@stock=1)then
	update stock_ledger set is_active=0 where reference_type=_ref_type and reference_id=_id;
    if(_type=1)then
		if(_ref_type=2)then
		INSERT INTO `stock_ledger`(`product_id`,`quantity`,`amount`,`reference_id`,`reference_type`,`narrative`,`created_by`,`created_date`,`last_update_by`)
		select d.product_id,d.qty,d.rate,_id,2,'Stock purchased',d.created_by,now(),d.created_by from expense_detail d inner join merchant_product p on p.product_id=d.product_id
		and p.has_stock_keeping=1 where expense_id=_id and d.is_active=1;
			
		elseif(_ref_type=3)then
		INSERT INTO `stock_ledger`(`product_id`,`quantity`,`amount`,`reference_id`,`reference_type`,`narrative`,`created_by`,`created_date`,`last_update_by`)
		select d.product_id,concat('-',d.qty),d.total_amount/d.qty,_id,3,'Stock sold',d.created_by,now(),d.created_by from invoice_particular d inner join merchant_product p on p.product_id=d.product_id
		and p.has_stock_keeping=1 where payment_request_id=_id and d.is_active=1;
		end if;
    end if;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_product;
	CREATE TEMPORARY TABLE IF NOT EXISTS temp_product (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `product_id` INT NOT NULL ,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    insert into temp_product(product_id)
    select product_id from stock_ledger where reference_id=_id and reference_type=_ref_type;
    
     update merchant_product f
	inner join ( select product_id, 
    sum(quantity) as avqty
	from stock_ledger  where product_id in (select product_id from temp_product) and is_active=1
    group by product_id 
  ) t 
  on (t.product_id = f.product_id)set f.available_stock = t.avqty where f.has_stock_keeping=1;
    
end if;
end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `supplier_save` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `supplier_save`(_user_id varchar(10),_email1 varchar(254),_email2 varchar(254),
_mob_country_code1 varchar(6),_mobile1 VARCHAR(13),_mob_country_code2 varchar(6),_mobile2 VARCHAR(13),
_industrytype int(4),_supplier_company_name varchar(100),_contact_person_name varchar(100) ,_contact_person_name2 varchar(100) , _company_website varchar(70))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        set @message = 'failed';
			ROLLBACK;
		END; 
START TRANSACTION;


select merchant_id into @merchant_id from merchant where user_id=_user_id;

INSERT into `supplier` (`user_id`,`merchant_id`,`email_id1`,`email_id2`,`mob_country_code1`,`mobile1`,
`mob_country_code2`,`mobile2`,`industry_type`,`supplier_company_name`,`contact_person_name`,`contact_person_name2`,`company_website`,`created_by`, 
`created_date`, `last_updated_by`, `last_updated_date`) VALUES (_user_id,@merchant_id,_email1,_email2,_mob_country_code1,_mobile1,_mob_country_code2, _mobile2,_industrytype,
_supplier_company_name,_contact_person_name,_contact_person_name2,_company_website,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

commit;
set @message  = 'success';
select @message as 'message';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `supplier_update` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `supplier_update`(_supplier_id int(11),_user_id varchar(10),_email1 varchar(254),
_email2 varchar(254),
_mob_country_code1 varchar(6),_mobile1 VARCHAR(13),_mob_country_code2 varchar(6),_mobile2 VARCHAR(13),
_industrytype int(4),_supplier_company_name varchar(100),_contact_person_name varchar(100) ,_contact_person_name2 varchar(100) , _company_website varchar(70))
BEGIN
BEGIN
        set @message = 'failed';
			ROLLBACK;
		END; 
START TRANSACTION;

set @supplier_id=_supplier_id;
set @user_id=_user_id;

if(@supplier_id !='' && @user_id !='') then

update `supplier` 
set `email_id1` = _email1 ,`email_id2` = _email2, `mob_country_code1` = _mob_country_code1,
    `mobile1` = _mobile1 , `mob_country_code1` = _mob_country_code2 , `mobile2` = _mobile2 , 
    `industry_type` = _industrytype ,    `supplier_company_name` =  _supplier_company_name ,
    `contact_person_name` =  _contact_person_name , `contact_person_name2` =  _contact_person_name2 ,
    `company_website` = _company_website ,`is_active` = 1 , `last_updated_by` = @user_id ,
    `last_updated_date` = CURRENT_TIMESTAMP()
where `supplier_id` = @supplier_id and `user_id` = @user_id;  

end if;



commit;
set @message  = 'success';
select @message as 'message';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `swipez_report_month_wise` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `swipez_report_month_wise`(_date DATE)
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
show errors;
ROLLBACK;
END; 

#Get atom transaction count and sum 

Drop TEMPORARY  TABLE  IF EXISTS temp_rep_monthly;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_rep_monthly (
`user_id` varchar(10) NOT NULL ,
`merchant_id` varchar(10)  NULL ,
`payment_request` INT NULL,
`payment_request_amount` DECIMAL(11,2)  null ,
`payment_transaction` INT NULL,
`payment_transaction_amount` DECIMAL(11,2)  null ,
`xway_request` INT NULL,
`xway_request_amount` DECIMAL(11,2)  null ,
`company_name` varchar(100)  NULL ,
`contact` varchar(20)  NULL ,
PRIMARY KEY (`user_id`)) ENGINE=MEMORY;

Drop TEMPORARY  TABLE  IF EXISTS temp_rep_monthly_req;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_rep_monthly_req (
`user_id` varchar(10) NOT NULL ,
`payment_request` INT NULL,
`payment_request_amount` DECIMAL(11,2)  null ,
PRIMARY KEY (`user_id`)) ENGINE=MEMORY;

insert into temp_rep_monthly (user_id,merchant_id,company_name) select user_id,merchant_id,company_name from merchant;

insert into temp_rep_monthly_req (user_id,payment_request,payment_request_amount) 
select user_id,count(payment_request_id),sum(absolute_cost) 
from payment_request where DATE_FORMAT(bill_date,'%Y-%m') = DATE_FORMAT(_date,'%Y-%m') group by user_id;

update temp_rep_monthly r,`user` u set r.contact=u.mobile_no where r.user_id=u.user_id;

update temp_rep_monthly r , temp_rep_monthly_req c  set r.payment_request = c.payment_request,r.payment_request_amount = c.payment_request_amount where r.user_id=c.user_id;

select * from temp_rep_monthly;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `swipe_settlement_save` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `swipe_settlement_save`(_settlement_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
            show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_swipe_settlement;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_swipe_settlement (
    `id` INT auto_increment ,
    `payu_settlement_id` varchar(40) NULL, 
    `transaction_id` varchar(10) NOT NULL,
    `payment_request_id` varchar(10) NULL,
    `merchant_id` varchar(10) NULL,
    `patron_id` varchar(10) NULL,
    `patron_name` varchar(100) NULL,
    `payment_id` varchar(40) NULL,
    `auth_date` datetime NULL,
    `cap_date` datetime NULL,
    `settlement_date` datetime NULL,
    `payment_method` varchar(20),
    `pg_bank_reff` varchar(100),
    `bank_reff` varchar(100) NULL,
    `captured` decimal(11,2) NULL,
	`tdr` decimal(11,2) NULL,
    `service_tax` decimal(11,2) NULL,
    `net_amount` decimal(11,2) NULL,
    `settlement_id` INT NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

insert into temp_swipe_settlement(payu_settlement_id,transaction_id,payment_method,captured,tdr,service_tax,net_amount,settlement_id) 
    select payu_settlement_id,transaction_id,payment_mode,amount,pg_tdr_amount+swipez_revenue,pg_service_tax+swipez_service_tax,swipez_amount,_settlement_id from revenue_detail where swipez_settlement_id=_settlement_id;
    
    update temp_swipe_settlement t, payu_settlement p set t.auth_date=p.added_on_date,t.cap_date=p.succeeded_on_date,t.patron_name=p.customer_name,t.payment_id=p.payment_id where t.payu_settlement_id=p.id;
    update temp_swipe_settlement t, payment_transaction p set t.merchant_id=p.merchant_user_id,t.patron_id=p.patron_user_id,t.payment_request_id=p.payment_request_id where t.transaction_id=p.pay_transaction_id;
    update temp_swipe_settlement t, xway_transaction p,merchant m set t.merchant_id=m.user_id where t.transaction_id=p.xway_transaction_id and p.merchant_id=m.merchant_id;
    update temp_swipe_settlement t, settlement_detail p set t.settlement_date=p.settlement_date,t.bank_reff=p.bank_reference where t.settlement_id=p.id;
	update temp_swipe_settlement t, pg_ret_bank4 p set t.pg_bank_reff=p.bank_ref_num where t.transaction_id=p.txnid;

	INSERT INTO `payment_transaction_tdr`(`transaction_id`,`payment_request_id`,`merchant_id`,`patron_id`,`patron_name`,`payment_id`,`auth_date`,`cap_date`,`payment_method`,`bank_reff`,
	`captured`,`tdr`,`service_tax`,`net_amount`,`created_date`,`last_update_date`)
	select transaction_id,payment_request_id,merchant_id,patron_id,patron_name,payment_id,auth_date,cap_date,payment_method,pg_bank_reff,captured,tdr,service_tax,net_amount
	,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP() from temp_swipe_settlement;
    
    
    INSERT INTO `payment_transaction_settlement`(`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,`merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,
    `transaction_date`,`settlement_date`,`created_date`)
	select settlement_id,payment_id,transaction_id,payment_request_id,merchant_id,patron_id,captured,concat('-',tdr),concat('-',service_tax),bank_reff,auth_date,settlement_date,CURRENT_TIMESTAMP()
    from temp_swipe_settlement;
SET @message='success';
select @message as 'message';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `template_edit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
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

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `template_save` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
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

INSERT INTO `invoice_template` (`template_id`,`merchant_id`, `user_id`, `template_name`,`template_type`,`image_path`,`plugin`,`profile_id`,`particular_total`,`tax_total`,`particular_column`,`default_particular`,`default_tax`,`tnc`,`design_name`,`design_color`,`created_by`, `created_date`, `last_update_by`, `last_update_date`) 
VALUES (@template_id,_merchant_id,user_id,template_name,_template_type,@image_name,_plugin,_profile_id,_particular_total,_tax_total,_pc,_pd,_td,_tnc,'design-one','#0f9dae',_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());



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



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `temp_gazonDynamic` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `temp_gazonDynamic`(req_id varchar(10))
BEGIN

DECLARE EXIT HANDLER FOR SQLEXCEPTION
show errors;
		BEGIN
END;






    select template_id into @template_id from payment_request where payment_request_id=req_id;
    select repeat_every,mode into @repeat_every,@mode from subscription where payment_request_id=req_id;
    if(@mode =4)then
    update subscription set billing_period_start_date=DATE_FORMAT(due_date,'%Y-%m-01') ,billing_period_duration=12 , billing_period_type='month' where payment_request_id=req_id;
    else
    update subscription set billing_period_start_date=DATE_FORMAT(due_date,'%Y-%m-01') ,billing_period_duration=repeat_every , billing_period_type='month' where payment_request_id=req_id;
     end if;
      select column_id,created_by into @column_id,@created_by from invoice_column_metadata where template_id=@template_id and function_id=10 and is_active=1 limit 1;
      SET @check_column_id=0;
      
      SELECT column_id into @check_column_id from invoice_column_values where column_id=@column_id and payment_request_id=req_id limit 1;
      select column_id into @time_column_id from invoice_column_metadata where template_id=@template_id and column_type='PS' and `column_name`='Time period' limit 1;
      if(@mode =4)then
		update invoice_column_values set value='Yearly' where column_id=@time_column_id and payment_request_id=req_id;
        else
        update invoice_column_values set value=concat(@repeat_every,' month') where column_id=@time_column_id and payment_request_id=req_id;
        end if;
      if(@check_column_id=0)then
      INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`)
		VALUES(req_id,@column_id,'',1,@created_by,CURRENT_TIMESTAMP(),created_by);
		end if;
        
  


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `temp_shengli_cc` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
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

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `temp_subscription_invoice_number` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `temp_subscription_invoice_number`(req_id varchar(10))
BEGIN

DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
END;



    
    select template_id into @template_id from payment_request where payment_request_id=req_id;
    select @template_id;
   SET @col_id=0;
    select column_id into @col_id from invoice_column_metadata where function_id=9 and is_active=1 and template_id=@template_id;
    select @col_id;
    if(@col_id>0)then
    set @invstring='';
    select concat(param,value) into @invstring from column_function_mapping where column_id=@col_id and is_active=1 limit 1;
    
     if(@col_id>0)then
		update payment_request set invoice_number=@invstring where payment_request_id=req_id;
     END IF;
     
     end if;
    
      
      



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `temp_update_inv` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `temp_update_inv`(_req_id varchar(10), _old_inv varchar(50),_new_inv varchar(50))
BEGIN
BEGIN
        set @message = 'failed';
			ROLLBACK;
		END; 
START TRANSACTION;

UPDATE `payment_request` SET `invoice_number`=_new_inv,`last_update_by`='Paresh' WHERE payment_request_id=_req_id;
UPDATE `invoice_column_values` SET `value`=_new_inv,`last_update_by`='Paresh' WHERE `value`=_old_inv and payment_request_id=_req_id;



commit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `test_report_payment_settlement_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `test_report_payment_settlement_detail`(_userid varchar(10),_from_date date , _to_date date,_franchise_id INT,_settlement_id INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_settlement_detail;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_settlement_detail (
  `id` int(11) NOT NULL,
  `settlement_id` varchar(20) DEFAULT NULL,
  `payment_id` varchar(45) DEFAULT NULL,
  `transaction_id` varchar(10) DEFAULT NULL,
  `payment_request_id` varchar(10) DEFAULT NULL,
  `merchant_id` varchar(10) DEFAULT NULL,
  `patron_id` varchar(10) DEFAULT NULL,
  `captured` decimal(11,2) DEFAULT NULL,
  `tdr` decimal(11,2) DEFAULT NULL,
  `service_tax` decimal(11,2) DEFAULT NULL,
  `bank_reff` varchar(20) DEFAULT NULL,
  `franchise_name` varchar(50) DEFAULT NULL,
  `franchise_id` INT DEFAULT 0,
  `transaction_date` datetime DEFAULT NULL,
  `settlement_date` datetime DEFAULT NULL,
  `created_date` timestamp DEFAULT '2014-01-01 00:00:00',
  `customer_id` int(11) DEFAULT NULL,
  `customer_code` varchar(45) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  
    PRIMARY KEY (`id`)) ENGINE=MEMORY;


	if(_settlement_id>0)then
    insert into temp_payment_settlement_detail(`id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date`) select `id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date` from payment_transaction_settlement 
    where settlement_id=_settlement_id ;
	
	else
    
    insert into temp_payment_settlement_detail(`id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date`) select `id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date` from payment_transaction_settlement 
    where DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date and merchant_id=_userid;
    end if;
    
   
UPDATE temp_payment_settlement_detail t,
    payment_request p 
SET 
    t.franchise_id = p.franchise_id
WHERE
    t.payment_request_id = p.payment_request_id;
    
    UPDATE temp_payment_settlement_detail t,
    payment_transaction p 
SET 
    t.customer_id = p.customer_id
WHERE
    t.transaction_id = p.pay_transaction_id;
    
    
UPDATE temp_payment_settlement_detail t,
    customer c 
SET 
    t.customer_name = CONCAT(c.first_name, ' ', c.last_name),
    t.customer_code = c.customer_code
WHERE
    (t.customer_id = c.customer_id);
    
	
    
UPDATE temp_payment_settlement_detail t,
    xway_transaction c 
SET 
    t.customer_name = c.name,
    t.customer_code=c.udf1,
    t.franchise_id = c.franchise_id
WHERE
    (t.transaction_id = c.xway_transaction_id);
    
    UPDATE temp_payment_settlement_detail t,
    franchise p 
SET 
    t.franchise_name = p.franchise_name
WHERE
    t.franchise_id = p.franchise_id;

if(_franchise_id>0 )then
    select * from temp_payment_settlement_detail where franchise_id=_franchise_id order by created_date;
else
    select * from temp_payment_settlement_detail order by created_date;
end if;
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_settlement_detail;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_api_invoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_api_invoice`(_invoice_id varchar(10),_invoice_values LONGTEXT,_column_id LONGTEXT,_user_id varchar(10),_customer_id INT,
_bill_date datetime,_due_date datetime,_bill_cycle_name varchar(40),_narrative varchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),particular_total varchar(45),tax_total varchar(45),
_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),_supplier varchar(250),_late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_expiry_date date,_notify_patron INT,_coupon_id INT,_franchise_id INT,_webhook_id INT,_vendor_id INT,_type INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
        SET @message='failed';
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @p_type=2;
SET @position=0;
Set @zero=0;
Set @one=1;
SET @bc_id='';
SET @patron_id='';
SET @ptColumn_id='';
SET @ttColumn_id='';
SET @account_id='';
SET @separator = '~';
SET @req_id='';
set @pay_req_status=0;
SET @separatorLength = CHAR_LENGTH(@separator);
SET @swipez_fee=0;
SET @EBS_fee=0;
SET @pg_tax_val=0;
SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
SET @swipez_total=@invoice_total;
SET @convenience_fee=0;
SET @grand_total=@invoice_total;
SET @parent_request_id='';

SELECT 
    CONCAT(first_name, ' ', last_name), user_id, customer_code
INTO @patron_name , @customer_user_id , @customer_code FROM
    customer
WHERE
    customer_id = _customer_id;

if(@customer_user_id <>'')then
SET @p_type=1;
end if;

SELECT 
    merchant_id, merchant_domain, company_name
INTO @merchat_id , @merchant_domain , @company FROM
    merchant
WHERE
    user_id = _user_id;
SELECT 
    swipez_fee_type,
    swipez_fee_val,
    pg_fee_type,
    pg_fee_val,
    pg_tax_val,
    surcharge_enabled
INTO @swipez_fee_type , @swipez_fee_val , @pg_fee_type , @pg_fee_val , @pg_tax , @surcharge FROM
    merchant_fee_detail
WHERE
    merchant_id = @merchat_id
        AND is_active = 1
ORDER BY pg_fee_val DESC
LIMIT 1;

SELECT 
    template_id,short_url
INTO @template_id,@short_url FROM
    payment_request
WHERE
    payment_request_id = _invoice_id;

SELECT 
    image_path, is_roundoff
INTO @image , @is_roundoff FROM
    invoice_template
WHERE
    template_id = @template_id;
if(@is_roundoff=1)then
SET @grand_total=ROUND(@grand_total);
end if;

SET @grand_total=@grand_total-_advance;

UPDATE invoice_column_values 
SET 
    `is_active` = 0
WHERE
    `payment_request_id` = _invoice_id;


if(@swipez_fee_type='F') then 
set @swipez_fee=@swipez_fee_val;
end if;

if(@swipez_fee_type='P') then 
set @swipez_fee=@invoice_total * @swipez_fee_val/100;
end if;

if(@surcharge=1) then
SET @swipez_total= @invoice_total + @swipez_fee;
end if;

if(@pg_fee_type='F') then 
set @EBS_fee= @pg_fee_val;
end if;

if(@pg_fee_type='P') then 
set @EBS_fee=@swipez_total * @pg_fee_val/100;
end if;

if(@pg_tax>0) then 
set @pg_tax_val= @EBS_fee * @pg_tax/100;
end if;

SET @convenience_fee=@swipez_fee + @EBS_fee + @pg_tax_val;




SELECT DISTINCTROW
    `billing_cycle_id`
INTO @bc_id FROM
    `billing_cycle_detail`
WHERE
    `_user_id` = _user_id
        AND `cycle_name` = _bill_cycle_name
LIMIT 1;


if(@bc_id='') then
select generate_sequence('Billing_cycle_id') into @bc_id;

INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`, `is_active`, `created_by`, `created_date`, 
`last_update_by`, `last_update_date`)
			 VALUES (@bc_id,_user_id,_bill_cycle_name,@one,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
end if;



INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,`created_by`,`created_date`,`last_update_by`,
`last_update_date`)
VALUES(@patron_id,1,'paymentrequest/viewlist',CURRENT_TIMESTAMP(),DATE_ADD(NOW(), INTERVAL 31 DAY),'You have received count payment request','',
_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
 


UPDATE payment_request 
SET 
    `customer_id` = _customer_id,
    `absolute_cost` = @grand_total,
    `basic_amount` = _amount,
    `tax_amount` = _tax,
    `invoice_total` = @invoice_total,
    `swipez_total` = @swipez_total,
    `convenience_fee` = @convenience_fee,
    `grand_total` = @grand_total,
    `late_payment_fee` = _late_fee,
    `bill_date` = _bill_date,
    `due_date` = _due_date,
    `narrative` = _narrative,
    `billing_cycle_id` = @bc_id,
    `notification_sent` = @zero,
    `expiry_date` = _expiry_date,
    `notify_patron` = _notify_patron,
    `coupon_id` = _coupon_id,
	`franchise_id`=_franchise_id,
    `vendor_id`=_vendor_id,
    `webhook_id`=_webhook_id,
    `advance_received`=_advance
WHERE
    payment_request_id = _invoice_id;


SET @max_particular=0;
SET @max_tax=0;
SELECT 
    MAX(column_position)
INTO @max_particular FROM
    invoice_column_metadata
WHERE
    template_id = @template_id
        AND column_type = 'PF';
SELECT 
    MAX(column_position)
INTO @max_tax FROM
    invoice_column_metadata
WHERE
    template_id = @template_id
        AND column_type = 'TF';

if(@max_particular>0)then
SET @max_particular=@max_particular;
else
SET @max_particular=0;
end if;

if(@max_tax>0)then
SET @max_tax=@max_tax;
else
SET @max_tax=0;
end if;


WHILE _column_id != '' > 0 DO
    SET @existid  = SUBSTRING_INDEX(_column_id, @separator, 1);
    SET @existvalue  = SUBSTRING_INDEX(_invoice_values, @separator, 1);

SET @is_new=0;
SET @invoice_values=@existvalue;
CASE @existid
      WHEN 'P1' THEN 
 select generate_sequence('Column_group_id') into @column_group_id;  
 SET @max_particular= @max_particular + 1;
	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_particular,@invoice_values,'PF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
SET @is_new=1;
      WHEN 'P2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_particular,'Unit Price','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'P3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'integer',@max_particular,'No of units','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'P4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_particular,'Absolute cost','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'P5' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_particular,'Narrative','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'T1' THEN 
 select generate_sequence('Column_group_id') into @column_group_id;  
 SET @max_tax=@max_tax + 1;
	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,@invoice_values,'TF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
SET @is_new=1;
      WHEN 'T2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'percentage',@max_tax,'Percentage','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'T3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Applicable on (RS)','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'T4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Absolute cost','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
  WHEN 'T5' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,'Narrative','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 WHEN 'CC' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',0,@invoice_values,'CC',0,'',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 WHEN 'D1' THEN 
	select generate_sequence('Column_group_id') into @column_group_id;  
     SET @max_tax=@max_tax + 1;
       	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,@invoice_values,'DF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 
 WHEN 'D2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'percentage',@max_tax,'Percentage','DS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 WHEN 'D3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Applicable on (RS)','DS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 SET @is_new=1;
 WHEN 'D4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Absolute cost','DS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
      SET @is_new=1;
      
    ELSE
   SET @is_new=0;
    END CASE;

if(@is_new=1)then
INSERT INTO `invoice_column_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (_invoice_id,@column_id,@invoice_values,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
else
    update invoice_column_values set `value`= @existvalue,`last_update_date`=CURRENT_TIMESTAMP(),`is_active`=1 where `column_id` = @existid and `payment_request_id`=_invoice_id;
  end if;
  
    SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@existid) + @separatorLength + 1);
    SET _invoice_values = SUBSTRING(_invoice_values, CHAR_LENGTH(@existvalue) + @separatorLength + 1);
END WHILE;
 

SELECT 
    `value`
INTO @patron_email FROM
    customer_comm_detail
WHERE
    customer_id = _customer_id AND type = 1
        AND is_preferred = 1
LIMIT 1;
SELECT 
    `value`
INTO @patron_mobile FROM
    customer_comm_detail
WHERE
    customer_id = _customer_id AND type = 2
        AND is_preferred = 1
LIMIT 1;
 
commit;
SET @message = 'success';
SELECT 
    @customer_code AS 'code',
    @patron_name AS 'patron_name',
    @patron_email AS 'patron_email',
    @patron_mobile AS 'patron_mobile',
    _invoice_id AS 'invoice_id',
    @short_url as 'short_url',
    @message AS 'message';


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_booking_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_booking_status`(_pay_transaction_id varchar(10))
BEGIN

	update booking_slots s,booking_transaction_detail d
	set s.slot_available=0
	where s.is_multiple=0
	and s.total_seat > 0
	and d.transaction_id=_pay_transaction_id
	and s.slot_id=d.slot_id;
	
	Drop TEMPORARY  TABLE  IF EXISTS temp_slots_ids;
	CREATE TEMPORARY TABLE IF NOT EXISTS temp_slots_ids (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`slot_id` INT  NULL ,
	`count` INT NOT NULL ,
	PRIMARY KEY (`id`)) ENGINE=MEMORY;
	
	insert temp_slots_ids (slot_id,count) select slot_id ,count(slot_id) from booking_transaction_detail where transaction_id=_pay_transaction_id and is_paid=1 group by slot_id;
	
    update booking_slots s,temp_slots_ids d
	set s.available_seat=available_seat-d.count
	where s.slot_id=d.slot_id
	and s.total_seat>0;
	
	update booking_slots s,booking_transaction_detail d
	set s.slot_available=0
	where s.is_multiple=1
	and d.transaction_id=_pay_transaction_id
	and s.slot_id=d.slot_id
	and s.total_seat>0
	and s.available_seat<1;
    
 
set @package_id = 0;   
    
SELECT a.package_id, a.merchant_id, a.slot_date, a.slot_time_from, a.slot_time_to
into @package_id, @merchant_id, @slot_date, @slot_time_from, @slot_time_to
from booking_slots a
join booking_transaction_detail b on a.slot_id=b.slot_id 
and b.transaction_id= _pay_transaction_id 
and a.is_primary= 1 and a.slot_available = 0 limit 1;

if( @package_id > 0) then
	update booking_slots 
	set slot_available=0
	where package_id  = @package_id 
	and is_primary = 0
	and merchant_id  = @merchant_id
    and slot_date = @slot_date
    and slot_time_from  = @slot_time_from
    and slot_time_to  = @slot_time_to;
end if;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_invoicevalues` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_invoicevalues`(_payment_request_id char(10),_user_id char(10),_customer_id INT,_invoice_number varchar(45),invoice_values LONGTEXT,
_column_id LONGTEXT,_bill_date date,_due_date date,_bill_cycle_name varchar(100),_narrative nvarchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),
_late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_notify_patron INT,_payment_request_status INT,_franchise_id INT,_vendor_id INT
,_expiry_date date,_created_by varchar(10),_invoice_type INT,_auto_collect_plan_id INT,_plugin_value longtext,_staging tinyint(1),_billing_profile INT,_generate_estimate_invoice tinyint(1),
_currency char(3),_einvoice_type varchar(20), _product_taxation_type INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        SET @message='failed';
        show errors;
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @bc_id='';
SET @separator = '~';
SET @req_id=_payment_request_id;
set @pay_req_status=_payment_request_status;
SET @separatorLength = CHAR_LENGTH(@separator);
SET @convenience_fee=0;
SET @parent_request_id='';
SET @estimate_number='';
SET @autoval=0;
SET @tax=0;

if(_product_taxation_type = 2)then
SET @invoice_total=_amount +_previous_dues -_last_payment - _adjustment;
else
SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
SET @tax = _tax;
end if;


SET @grand_total=@invoice_total;
SET @swipez_total=@invoice_total;

if(_payment_request_status!=11)then
	SET @numstring=SUBSTRING(_invoice_number,1,16);
	if(@numstring='System generated')then
        if(_invoice_type<>2)then
			if(@autoval=0)then
            SET @autoval=SUBSTRING(_invoice_number,17);
            end if;
            SELECT GENERATE_INVOICE_NUMBER(@autoval) INTO @invoice_number;
            SET invoice_values=REPLACE(invoice_values, _invoice_number, @invoice_number);
            SET _invoice_number= @invoice_number;
        else
            SET @invoice_number='';
            SET invoice_values=REPLACE(invoice_values, _invoice_number, @invoice_number);
            SET _invoice_number= @invoice_number;
        end if;
	end if;
end if;

select get_surcharge_amount(@merchant_id ,@invoice_total,0) into @convenience_fee;

SET @grand_total=@grand_total-_advance;

SELECT `billing_cycle_id` INTO @bc_id FROM `billing_cycle_detail` WHERE `_user_id` = _user_id AND `cycle_name` = _bill_cycle_name LIMIT 1;

if(@bc_id='') then
select generate_sequence('Billing_cycle_id') into @bc_id;
INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`,  `created_by`, `created_date`, 
`last_update_by`) VALUES (@bc_id,_user_id,_bill_cycle_name,_created_by,CURRENT_TIMESTAMP(),_created_by);
end if;

if(_staging=1)then
update staging_payment_request set customer_id=_customer_id,`invoice_number`=_invoice_number,`previous_due`=_previous_dues,`absolute_cost`=@grand_total
,basic_amount=_amount,tax_amount=@tax,invoice_total=@invoice_total,swipez_total=@swipez_total,
convenience_fee=@convenience_fee,grand_total=@grand_total,late_payment_fee=_late_fee,advance_received=_advance,
bill_date=_bill_date,due_date=_due_date,narrative=_narrative,billing_cycle_id=@bc_id,notify_patron=_notify_patron,payment_request_status=@pay_req_status,
franchise_id=_franchise_id,vendor_id=_vendor_id,expiry_date=_expiry_date,plugin_value=_plugin_value,generate_estimate_invoice=_generate_estimate_invoice,
currency=_currency,einvoice_type=_einvoice_type,last_update_by=_created_by,product_taxation_type=_product_taxation_type
where payment_request_id=_payment_request_id;
else
update payment_request set customer_id=_customer_id,`invoice_number`=_invoice_number,`previous_due`=_previous_dues,`absolute_cost`=@grand_total
,basic_amount=_amount,tax_amount=@tax,invoice_total=@invoice_total,swipez_total=@swipez_total,
convenience_fee=@convenience_fee,grand_total=@grand_total,late_payment_fee=_late_fee,advance_received=_advance,
bill_date=_bill_date,due_date=_due_date,narrative=_narrative,billing_cycle_id=@bc_id,notify_patron=_notify_patron,payment_request_status=@pay_req_status,
franchise_id=_franchise_id,vendor_id=_vendor_id,expiry_date=_expiry_date,plugin_value=_plugin_value,generate_estimate_invoice=_generate_estimate_invoice,
currency=_currency,einvoice_type=_einvoice_type,last_update_by=_created_by,product_taxation_type=_product_taxation_type
where payment_request_id=_payment_request_id;
end if;


if(_billing_profile>0)then
	SET @autoval=0;
	select gst_number,invoice_seq_id into @gst_number,@autoval from merchant_billing_profile where id=_billing_profile;
    if(_staging=1)then
		update staging_payment_request set gst_number=@gst_number,billing_profile_id=_billing_profile where payment_request_id=_payment_request_id;
    else
		select billing_profile_id into @exist_gst_id from payment_request where payment_request_id=_payment_request_id;
        if(@exist_gst_id<>_billing_profile and @autoval>0)then
			SELECT GENERATE_INVOICE_NUMBER(@autoval) INTO @invoice_number;
			update payment_request set invoice_number=@invoice_number where payment_request_id=_payment_request_id;
            update invoice_column_values v , invoice_column_metadata m set `value`=@invoice_number where v.column_id=m.column_id and m.function_id=9 and v.payment_request_id=_payment_request_id;
        end if;
		update payment_request set gst_number=@gst_number,billing_profile_id=_billing_profile where payment_request_id=_payment_request_id;
    end if;
end if;



WHILE _column_id != '' > 0 DO
    SET @column_id  = SUBSTRING_INDEX(_column_id, @separator, 1);
    SET @invoice_values  = SUBSTRING_INDEX(invoice_values, @separator, 1);
 if(_staging=1)then
 update staging_invoice_values set value=@invoice_values , last_update_by=_created_by where invoice_id=@column_id;
else
 update invoice_column_values set value=@invoice_values , last_update_by=_created_by where invoice_id=@column_id;
end if;

    SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id) + @separatorLength + 1);
    SET invoice_values = SUBSTRING(invoice_values, CHAR_LENGTH(@invoice_values) + @separatorLength + 1);
END WHILE;

select id,amount into @ledger_id,@ledger_amount from contact_ledger where customer_id=_customer_id and reference_no=_payment_request_id;
if(@ledger_id>0)then
update customer set balance = balance - @ledger_amount + @grand_total where customer_id=_customer_id;
update contact_ledger set amount= @grand_total where id=@ledger_id;
else 
update customer set balance = balance + @grand_total where customer_id=_customer_id;
INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
`ledger_type`,`created_by`,`created_date`,`last_update_by`)
VALUES(_customer_id,concat('Invoice for bill date ',_bill_date),@grand_total,1,@req_id,'DEBIT',_created_by,CURRENT_TIMESTAMP(),_created_by);
end if;

commit;
SET @message = 'success';

SELECT 
    _notify_patron AS 'notify_patron',
    _payment_request_id AS 'request_id',
    @message AS 'message';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_staging_invoicevalues` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_staging_invoicevalues`(_payment_request_id varchar(10),_existid LONGTEXT,_existvalue LONGTEXT,invoice_values LONGTEXT,_column_id LONGTEXT,
_user_id varchar(10),_customer_id INT,_bill_date datetime,_due_date datetime,_bill_cycle_name varchar(40),_narrative varchar(500),_amount DECIMAL(11,2),_tax DECIMAL(11,2),
_previous_dues DECIMAL(11,2),_last_payment DECIMAL(11,2),_adjustment DECIMAL(11,2),_supplier_id INT ,_supplier varchar(100),_late_fee DECIMAL(11,2),_advance DECIMAL(11,2),_expiry_date date,_notify_patron INT,_coupon_id INT,_franchise_id INT,_franchise_name_invoice INT,_vendor_id INT,_enable_partial_payment int ,_partial_min_amount decimal(11,2))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
        	ROLLBACK;
		END; 
START TRANSACTION;

SET @patron_type=2;
SET @position=0;
Set @zero=0;
Set @one=1;
SET @bc_id='';
SET @patron_id='';
SET @ptColumn_id='';
SET @ttColumn_id='';
SET @account_id='';
SET @separator = '~';
SET @req_id=_payment_request_id;
set @pay_req_status=0;
SET @separatorLength = CHAR_LENGTH(@separator);
SET @swipez_fee=0;
SET @EBS_fee=0;
SET @pg_tax_val=0;
SET @invoice_total=_amount + _tax +_previous_dues -_last_payment - _adjustment;
SET @swipez_total=@invoice_total;
SET @convenience_fee=0;
SET @grand_total=@invoice_total;


select concat(first_name,' ',last_name),user_id,email,mobile into @patron_name,@customer_user_id,@customer_email,@customer_mobile from customer where customer_id=_customer_id;

if(@customer_user_id <>'')then
SET @patron_type=1;
end if;

update staging_invoice_values set `is_active`=0 where `payment_request_id`=_payment_request_id;
select template_id into @template_id from staging_payment_request where `payment_request_id`=_payment_request_id;



select merchant_id,merchant_domain,company_name,display_name into @merchat_id,@merchant_domain,@company,@display_name from merchant where user_id=_user_id;
select swipez_fee_type , swipez_fee_val,pg_fee_type,pg_fee_val,pg_tax_val,surcharge_enabled into @swipez_fee_type,@swipez_fee_val,@pg_fee_type,@pg_fee_val,@pg_tax,@surcharge from merchant_fee_detail where merchant_id=@merchat_id and is_active=1 order by pg_fee_val desc limit 1;

 if(@display_name<>'')then
 SET @company=@display_name;
 end if;

if(@swipez_fee_type='F') then 
set @swipez_fee=@swipez_fee_val;
end if;

if(@swipez_fee_type='P') then 
set @swipez_fee=@invoice_total * @swipez_fee_val/100;
end if;

if(@surcharge=1) then
SET @swipez_total= @invoice_total + @swipez_fee;
end if;

if(@pg_fee_type='F') then 
set @EBS_fee= @pg_fee_val;
end if;

if(@pg_fee_type='P') then 
set @EBS_fee=@swipez_total * @pg_fee_val/100;
end if;

if(@pg_tax>0) then 
set @pg_tax_val= @EBS_fee * @pg_tax/100;
end if;

SET @convenience_fee=@swipez_fee + @EBS_fee + @pg_tax_val;



select image_path,is_roundoff into @image,@is_roundoff from invoice_template where template_id=@template_id;
if(@is_roundoff=1)then
SET @grand_total=ROUND(@grand_total);
end if;

SET @grand_total=@grand_total-_advance;

SELECT DISTINCTROW `billing_cycle_id` into @bc_id FROM `billing_cycle_detail` where `_user_id`=_user_id and `cycle_name`=_bill_cycle_name limit 1;


if(@bc_id='') then
select generate_sequence('Billing_cycle_id') into @bc_id;

INSERT INTO `billing_cycle_detail` (`billing_cycle_id`, `user_id`, `cycle_name`, `is_active`, `created_by`, `created_date`, 
`last_update_by`, `last_update_date`)
			 VALUES (@bc_id,_user_id,_bill_cycle_name,@one,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
end if;

update staging_payment_request set `customer_id`=_customer_id ,`account_id`=@account_id,`absolute_cost`=@grand_total,`basic_amount`=_amount,`tax_amount`=_tax,
`invoice_total`=@invoice_total,`swipez_total`=@swipez_total,`convenience_fee`=@convenience_fee,`grand_total`=@grand_total,`bill_date`=_bill_date,`due_date`=_due_date,
`narrative`=_narrative,`billing_cycle_id`=@bc_id,`late_payment_fee`=_late_fee,`expiry_date`=_expiry_date,`notify_patron`=_notify_patron,`franchise_id`=_franchise_id,`franchise_name_invoice`=_franchise_name_invoice,`vendor_id`=_vendor_id,`advance_received`=_advance
,enable_partial_payment=_enable_partial_payment,partial_min_amount=_partial_min_amount
,`last_update_date`=CURRENT_TIMESTAMP() where `payment_request_id`=_payment_request_id;


WHILE _existid != '' > 0 DO
    SET @existid  = SUBSTRING_INDEX(_existid, @separator, 1);
    SET @existvalue  = SUBSTRING_INDEX(_existvalue, @separator, 1);

update staging_invoice_values set `value`= @existvalue,`last_update_date`=CURRENT_TIMESTAMP(),`is_active`=1 where `invoice_id` = @existid;
  
    SET _existid = SUBSTRING(_existid, CHAR_LENGTH(@existid) + @separatorLength + 1);
    SET _existvalue = SUBSTRING(_existvalue, CHAR_LENGTH(@existvalue) + @separatorLength + 1);
END WHILE;


SET @max_particular=0;
SET @max_tax=0;
select max(column_position) into @max_particular from invoice_column_metadata where template_id=@template_id and column_type='PF';
select max(column_position) into @max_tax from invoice_column_metadata where template_id=@template_id and column_type='TF';

if(@max_particular>0)then
SET @max_particular=@max_particular;
else
SET @max_particular=0;
end if;

if(@max_tax>0)then
SET @max_tax=@max_tax;
else
SET @max_tax=0;
end if;


SET @column_group_id='';
WHILE _column_id != '' > 0 DO
    SET @column_id_value  = SUBSTRING_INDEX(_column_id, @separator, 1);
	SET @invoice_values  = SUBSTRING_INDEX(invoice_values, @separator, 1);
		
 CASE @column_id_value
      WHEN 'P1' THEN 
 select generate_sequence('Column_group_id') into @column_group_id;  
 SET @max_particular= @max_particular + 1;
	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_particular,@invoice_values,'PF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();

      WHEN 'P2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_particular,'Unit Price','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'P3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'integer',@max_particular,'No of units','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'P4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_particular,'Absolute cost','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'P5' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_particular,'Narrative','PS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'T1' THEN 
 select generate_sequence('Column_group_id') into @column_group_id;  
 SET @max_tax=@max_tax + 1;
	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,@invoice_values,'TF',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();

      WHEN 'T2' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'percentage',@max_tax,'Percentage','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'T3' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Applicable on (RS)','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
  WHEN 'T4' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'money',@max_tax,'Absolute cost','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
  
  WHEN 'T5' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',@max_tax,'Narrative','TS',0,@column_group_id,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
 
 WHEN 'CC' THEN 
      
      	INSERT INTO `invoice_column_metadata` (`template_id`, `column_datatype`, `column_position`, `column_name`, 
`column_type`,`is_template_column`,`column_group_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) values (@template_id
,'text',0,@invoice_values,'CC',0,'',_created_by,CURRENT_TIMESTAMP(),_created_by,CURRENT_TIMESTAMP());

 SET @column_id=LAST_INSERT_ID();
      
      ELSE
   SET @column_id=@column_id_value;
   if(@template_id='') then
   select template_id into @template_id from invoice_column_metadata where column_id=@column_id;
   end if;
    END CASE;

INSERT INTO `staging_invoice_values` (payment_request_id, `column_id`, `value`, `created_by`,`created_date`,`last_update_by`,`last_update_date`) 
values (@req_id,@column_id,@invoice_values,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());
 

	 SET _column_id = SUBSTRING(_column_id, CHAR_LENGTH(@column_id_value) + @separatorLength + 1);
    SET invoice_values = SUBSTRING(invoice_values, CHAR_LENGTH(@invoice_values) + @separatorLength + 1);
END WHILE;



 select config_value into @domain from config where config_type='merchant_domain' and config_key=@merchant_domain;
  
  
commit;
SET @message = 'success';

select _notify_patron as 'notify_patron', @customer_name as 'customer_name',@customer_email as 'customer_email',@customer_mobile as 'customer_mobile',@company as 'company',@req_id as 'request_id',@message as 'message',@domain as 'merchant_domain',@image as 'image';



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_status`(_payment_request_id varchar(10),_fee_transaction_id varchar(10),_amount decimal(11,2),_status int,_bankstatus int,_pg_ref_no varchar(40),
_pg_ref_1 varchar(40),_payment_mode varchar(45),_pg_ref_2 varchar(40), _message VARCHAR(100))
BEGIN 
   DECLARE patron_id VARCHAR(10) DEFAULT '';
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
		END; 

set @payment_request='';
set @payment_request=_payment_request_id;
SET @late_payment=0;
SET @user_id='';

SELECT due_date, customer_id, user_id,invoice_type,generate_estimate_invoice INTO @due_date , @customer_id , @user_id,@invoice_type,@generate_estimate_invoice FROM payment_request WHERE payment_request_id = @payment_request;

 if(DATE_FORMAT(NOW(),'%Y-%m-%d') > DATE_FORMAT(@due_date,'%Y-%m-%d')) then
	SET @late_payment=1;
 end if;
 
 if(@invoice_type=2 and _status=1 and @generate_estimate_invoice=1)then
 
	call convert_estimate_to_invoice(@payment_request,@new_request_id);
    
    UPDATE `payment_request` SET `payment_request_status` = _status WHERE
    payment_request_id = @new_request_id ;
    
    set @payment_request_id=@new_request_id;
    
    UPDATE `payment_transaction` SET payment_request_id=@new_request_id,estimate_request_id=@payment_request,payment_mode=_payment_mode,narrative=_message where pay_transaction_id = _fee_transaction_id;
end if;

UPDATE `payment_transaction` 
SET 
    `payment_transaction_status` = _status,
    `late_payment` = @late_payment,
    `amount` = _amount,
    bank_status = _bankstatus,
    pg_ref_no = _pg_ref_no,
    pg_ref_1 = _pg_ref_1,
    pg_ref_2 = _pg_ref_2,
    payment_mode=_payment_mode,
    narrative=_message,
    last_update_by = `patron_user_id`,
    last_update_date = CURRENT_TIMESTAMP()
WHERE
    pay_transaction_id = _fee_transaction_id;

	UPDATE `payment_request` SET `payment_request_status` = _status WHERE
    payment_request_id = @payment_request and payment_request_status not in (1,2);
	
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `validate_xwayrequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `validate_xwayrequest`(_mode varchar(4),_merchant_id varchar(10), _amount decimal(11,2),_reference_no varchar(50))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 
SET @Error_message='';
SET @merchant_id='';

select `merchant_id`,`min_transaction`,`max_transaction`,`xway_enable` into @merchant_id, @min_transaction,@max_transaction,@xway_enable from merchant_setting where merchant_id=_merchant_id and xway_enable=1;

SET @absolute_cost=_amount;
SET @xway_transaction_id=0;


if(_merchant_id!=@merchant_id) then
if(@Error_message!='')then
SET @Error_message = concat(@Error_message,',');
end if;
SET @Error_message = concat(@Error_message,'Invalid Merchant id');
else
select count(`xway_transaction_id`) into @xway_transaction_id from xway_transaction where merchant_id = _merchant_id and reference_no=_reference_no;
end if;

if(@min_transaction > @absolute_cost and @max_transaction < @absolute_cost) then
if(@Error_message!='')then
SET @Error_message = concat(@Error_message,',');
end if;
SET @Error_message = concat(@Error_message,'Invalid Amount');
end if;

if(@xway_transaction_id>0) then
if(@Error_message!='')then
SET @Error_message = concat(@Error_message,',');
end if;
SET @Error_message = concat(@Error_message,'Duplicate reference no');
end if;


if(@Error_message!='')then
SET @message='failed';
else
SET @message='success';
end if;

select @Error_message as 'error',@absolute_cost as 'absolute_cost',@message as 'message';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-10-13 12:21:40
