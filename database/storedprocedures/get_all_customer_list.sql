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

     

END
