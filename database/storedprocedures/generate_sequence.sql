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
end
