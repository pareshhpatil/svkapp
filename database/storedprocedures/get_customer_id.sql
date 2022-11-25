CREATE DEFINER=`root`@`localhost` FUNCTION `get_customer_id`(_customer_code varchar(45),_merchant_id varchar(10)) RETURNS char(45) CHARSET latin1
begin

select customer_id into @customer_id from customer where customer_code=_customer_code and merchant_id=_merchant_id;
    
    RETURN @customer_id;
end
