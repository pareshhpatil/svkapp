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
end
