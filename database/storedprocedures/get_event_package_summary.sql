CREATE DEFINER=`root`@`localhost` PROCEDURE `get_event_package_summary`(_event_id varchar(10))
BEGIN
DECLARE bDone INT;
DECLARE occ_id INT;
DECLARE mid CHAR(10);
DECLARE muid CHAR(10);
DECLARE curs CURSOR FOR select occurence_id FROM event_occurence where event_request_id=_event_id and is_active=1;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
 show errors;
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_package_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_package_summary (
    `id` INT auto_increment NOT NULL ,
    `package_id` INT NOT NULL,
    `occurence_id` INT NOT NULL,
    `package_type` INT NOT NULL,
    `date` date null,
    `start_time` time null,
    `end_time` time null,
    `package_name` varchar(250) null,
    `total_qty` INT not NULL default 0,
    `reserv_qty` INT not NULL default 0,
    `available_qty` INT  NULL,
    `price` decimal(11,2)  NULL,
    `available_amount` decimal(11,2)  not NULL default 0,
    `sold_amount` decimal(11,2) not NULL default 0,
    `unpaid_amount` decimal(11,2)  not NULL default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;


 OPEN curs;
    SET bDone = 0;
    REPEAT
		FETCH curs INTO occ_id;
	
    insert into temp_package_summary(package_id,package_type,package_name,total_qty,price,available_amount,occurence_id)
    select package_id,package_type,package_name,seats_available,price,seats_available*price,occ_id from event_package where event_request_id =_event_id ;
          
    UNTIL bDone END REPEAT;
    CLOSE curs;
    
    update temp_package_summary s,event_occurence o set s.date=o.start_date,s.start_time=o.start_time,s.end_time=o.end_time where s.occurence_id=o.occurence_id;
    
    
    UPDATE temp_package_summary o 
INNER JOIN
(
   SELECT occurence_id,package_id,
   count(event_transaction_detail_id) 'bookedqty',SUM(amount) 'bookedamt'
   FROM event_transaction_detail 
   where event_request_id=_event_id and is_paid=1
   GROUP BY occurence_id,package_id
) i ON o.occurence_id = i.occurence_id and  o.package_id = i.package_id
SET o.reserv_qty = i.bookedqty,o.sold_amount = i.bookedamt
WHERE o.occurence_id = i.occurence_id and  o.package_id = i.package_id;

update temp_package_summary  set available_qty=total_qty-reserv_qty,unpaid_amount=available_amount-sold_amount;


update  temp_package_summary s,event_package p set s.date=null ,s.package_name='' where p.package_type<>2 
AND occurence not like CONCAT('%', s.date , '%') and p.package_id=s.package_id;


select distinct package_name,date,start_time,end_time,total_qty,reserv_qty,available_qty,price,available_amount,sold_amount,unpaid_amount from temp_package_summary;





END
