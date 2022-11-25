CREATE DEFINER=`root`@`localhost` FUNCTION `generate_estimate_number`(_merchant_id varchar(10)) RETURNS char(45) CHARSET latin1
begin


    UPDATE merchant_auto_invoice_number SET val=last_insert_id(val+1) WHERE merchant_id=_merchant_id and type=2;

    SET @subscript = (SELECT concat(prefix,val) FROM merchant_auto_invoice_number WHERE merchant_id=_merchant_id and type=2);
    
    
    RETURN @subscript;
end
