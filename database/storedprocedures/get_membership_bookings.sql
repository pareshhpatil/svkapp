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


END
