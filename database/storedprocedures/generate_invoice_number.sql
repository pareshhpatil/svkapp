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
end
