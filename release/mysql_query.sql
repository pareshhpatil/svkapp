ALTER TABLE `swipez`.`merchant_auto_invoice_number` 
ADD COLUMN `seprator` VARCHAR(5) NULL DEFAULT NULL AFTER `type`;


-----------------------------------------------------------------------------------

USE `swipez`;
DROP function IF EXISTS `generate_invoice_number`;

USE `swipez`;
DROP function IF EXISTS `swipez`.`generate_invoice_number`;
;

DELIMITER $$
USE `swipez`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_invoice_number`(_auto_invoice_id varchar(10)) RETURNS char(45) CHARSET latin1
begin


    UPDATE merchant_auto_invoice_number SET val=last_insert_id(val+1) WHERE auto_invoice_id=_auto_invoice_id;

    SELECT prefix,seprator,val,length into @subscript,@seprator,@seqval,@length  FROM merchant_auto_invoice_number WHERE auto_invoice_id=_auto_invoice_id;
    
    SET @len = length(@seqval);
    
    IF @len < @length THEN 
        SET @returnval = CONCAT(@subscript,@seprator, LPAD(@seqval, @length, '0'));
    ELSE
        SET @returnval = CONCAT(@subscript,@seprator, @seqval);
    END IF;
    
    RETURN @returnval;
end$$

DELIMITER ;
;



