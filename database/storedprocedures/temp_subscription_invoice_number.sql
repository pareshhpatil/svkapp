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
    
      
      



END
