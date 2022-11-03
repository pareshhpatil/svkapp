CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customer_details`(_customer_id INT,_merchant_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


select customer_id,customer_code,concat(first_name,' ',last_name) as name,first_name,last_name,email,mobile,address2,address,city,state,zipcode,customer_group,created_date 
    from customer where customer_id=_customer_id and merchant_id=_merchant_id;
     
     

END
