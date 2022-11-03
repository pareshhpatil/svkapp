CREATE DEFINER=`root`@`localhost` PROCEDURE `get_calendar_reservation`(_merchant_id varchar(10),_calendar_id INT,_from_date date,_to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_reservation_table;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_reservation_table (
    `id` INT NOT NULL ,
    `slot_id` INT NOT NULL,
    `transaction_id` varchar(10) NOT NULL,
    `is_active` INT NOT NULL DEFAULT 1,
    `amount` DECIMAL(11,2) not null ,
    `customer_id` INT NULL,
    `category_name` varchar(250) NULL,
    `calendar_title` varchar(250) NULL,
    `paid_by` varchar(250) NULL,
    `customer_code` varchar(50) NULL,
    `email` varchar(250) NULL,
	`mobile` varchar(15) null,
    `slot` varchar(250) NULL,
    `calendar_date` DATE null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

	if(_calendar_id>0)then

        insert into temp_reservation_table(id,slot_id,calendar_date,transaction_id,slot,amount,category_name,calendar_title)
    select booking_transaction_detail_id,slot_id,calendar_date,transaction_id,slot,amount,category_name,calendar_title
    from booking_transaction_detail where calendar_id =_calendar_id and is_paid=1;
    else
		insert into temp_reservation_table(id,slot_id,calendar_date,transaction_id,slot,amount,category_name,calendar_title)
		select booking_transaction_detail_id,slot_id,calendar_date,transaction_id,slot,amount,category_name,d.calendar_title
		from booking_transaction_detail d inner join booking_calendars c on d.calendar_id=c.calendar_id  
        where c.merchant_id =_merchant_id and d.is_paid=1 and DATE_FORMAT(d.calendar_date,'%Y-%m-%d') >= DATE_FORMAT(_from_date,'%Y-%m-%d') and DATE_FORMAT(d.calendar_date,'%Y-%m-%d')<= DATE_FORMAT(_to_date,'%Y-%m-%d') ;
    end if;

    update temp_reservation_table r , payment_transaction c  set r.customer_id = c.customer_id where r.transaction_id=c.pay_transaction_id;
    update temp_reservation_table r , offline_response c  set r.customer_id = c.customer_id,r.is_active = c.is_active where r.transaction_id=c.offline_response_id;
	
	update temp_reservation_table r , customer b  set r.paid_by = concat(b.first_name,' ',b.last_name) , r.customer_code=b.customer_code,r.email=b.email,r.mobile=b.mobile where r.customer_id=b.customer_id ;


	select * from temp_reservation_table where is_active=1;


END
