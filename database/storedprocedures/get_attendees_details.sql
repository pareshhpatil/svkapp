CREATE DEFINER=`root`@`localhost` PROCEDURE `get_attendees_details`(_event_id varchar(10),_from_date date,_to_date date,_package_id INT,_occurence_id INT)
BEGIN
DECLARE bDone INT;
DECLARE col_id INT;
DECLARE curs CURSOR FOR select `column_id` FROM event_capture_metadata where event_id=_event_id limit 3;
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_attendees_table;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_attendees_table (
    `id` varchar(10) NOT NULL ,
    `package_id` INT NOT NULL,
    `occurence_id` INT NOT NULL,
    `transaction_id` varchar(10) NOT NULL,
    `transaction_date` datetime null,
    `attendee_customer_id` INT null,
    `mobile` varchar(15) null,
    `amount` DECIMAL(11,2) not null ,
    `customer_id` INT NULL,
    `user_id` varchar(10) NULL,
    `package_name` varchar(250) NULL,
    `paid_by` varchar(250) NULL,
    `customer_code` varchar(50) NULL,
    `email` varchar(250) NULL,
    `event_name` varchar(250) NULL,
    `event_date` DATE null,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

	if(_from_date is null)then
		
        if(_occurence_id>0)then 
			insert into temp_attendees_table(id,package_id,occurence_id,transaction_id,attendee_customer_id,amount,transaction_date)
    select event_transaction_detail_id,package_id,occurence_id,transaction_id,customer_id,amount,created_date 
    from event_transaction_detail where event_request_id =_event_id and is_paid=1 and occurence_id=_occurence_id;
        else
			insert into temp_attendees_table(id,package_id,occurence_id,transaction_id,attendee_customer_id,amount,transaction_date)
    select event_transaction_detail_id,package_id,occurence_id,transaction_id,customer_id,amount,created_date 
    from event_transaction_detail where event_request_id =_event_id and is_paid=1;
        end if;
            
    else
    
    if(_occurence_id>0)then 
			insert into temp_attendees_table(id,package_id,occurence_id,transaction_id,attendee_customer_id,amount,transaction_date)
    select event_transaction_detail_id,package_id,occurence_id,transaction_id,customer_id,amount,created_date
    from event_transaction_detail where event_request_id =_event_id and is_paid=1 and 
    DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date and occurence_id=_occurence_id;
        else
			insert into temp_attendees_table(id,package_id,occurence_id,transaction_id,attendee_customer_id,amount,transaction_date)
    select event_transaction_detail_id,package_id,occurence_id,transaction_id,customer_id,amount,created_date
    from event_transaction_detail where event_request_id =_event_id and is_paid=1 and 
    DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date;
        end if;
		
    end if;

UPDATE temp_attendees_table r,
    event_request c 
SET 
    r.event_name = c.event_name
WHERE
    c.event_request_id = _event_id;
UPDATE temp_attendees_table r,
    event_occurence m 
SET 
    r.event_date = m.start_date
WHERE
    r.occurence_id = m.occurence_id;
UPDATE temp_attendees_table r,
    event_package c 
SET 
    r.package_name = c.package_name
WHERE
    r.package_id = c.package_id;
UPDATE temp_attendees_table r,
    payment_transaction c 
SET 
    r.customer_id = c.customer_id
WHERE
    r.transaction_id = c.pay_transaction_id;
UPDATE temp_attendees_table r,
    offline_response c 
SET 
    r.customer_id = c.customer_id
WHERE
    r.transaction_id = c.offline_response_id;

	UPDATE temp_attendees_table r,
    customer b 
SET 
    r.paid_by = CONCAT(b.first_name, ' ', b.last_name),
    r.customer_code = b.customer_code,
    r.email=b.email,
    r.mobile=b.mobile

WHERE
    r.customer_id = b.customer_id;
	

	if(_package_id>0)then
    	select id,transaction_id,transaction_date,event_name,package_name,amount,event_date,paid_by,customer_code,email,attendee_customer_id from temp_attendees_table where package_id=_package_id;

    else
			select id,transaction_id,transaction_date,event_name,package_name,amount,event_date,paid_by,customer_code,email,attendee_customer_id from temp_attendees_table;

    end if;

END
