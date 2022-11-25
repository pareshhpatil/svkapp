CREATE DEFINER=`root`@`localhost` PROCEDURE `report_expected_payment`(_user_id varchar(10),_from_date date , _to_date date,_interval INT,_interval_of INT,_aging_by varchar(50))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
show errors;
BEGIN
ROLLBACK;
END;
START TRANSACTION;
SET @aging = _aging_by;
SET @user_id = _user_id;


SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');


Drop TEMPORARY  TABLE  IF EXISTS temp_load_data;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_load_data (
`load_id` INT NOT NULL AUTO_INCREMENT,
`customer_id` INT NULL ,
`date` datetime NULL,
`last_update_date` datetime NULL,
`grand_total` DECIMAL(11,2) NULL default 0,
PRIMARY KEY (`load_id`)) ENGINE=MEMORY;



SET @sql=concat("insert into temp_load_data(customer_id,date,last_update_date,grand_total) select customer_id,due_date,last_update_date,grand_total from payment_request where payment_request_status in(0,4,5) and payment_request_type <> 4 and merchant_id='",@user_id,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= '",@from_date,"' and DATE_FORMAT(",@aging,",'%Y-%m-%d') <= '",@to_date,"' and is_active=1;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

Drop TEMPORARY  TABLE  IF EXISTS temp_expected_payment;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_expected_payment (
`aging_id` INT NOT NULL AUTO_INCREMENT,
`customer_id` INT  NULL ,
`customer_code` varchar(45)  NULL ,
`customer_name` varchar(250) NULL,
`current` DECIMAL(11,2) NULL default 0,
`total` DECIMAL(11,2) NULL default 0,
PRIMARY KEY (`aging_id`)) ENGINE=MEMORY;

SET @aging = 'date';

SET  @grand_total= 0;
SET @sql=concat("insert into temp_expected_payment(customer_id,current) select tc.customer_id,sum(grand_total) from temp_load_data tc where DATE_FORMAT(",@aging,",'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') group by tc.customer_id;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



SET  @interval_= _interval;
SET  @max= 0;
SET  @int_= 1;
SET  @interval_of= _interval_of;
SET  @start= 1;
SET @last='current';
SET @finalsql="select customer_name,customer_code,sum(current) as current,";
SET @totalsql="update temp_expected_payment set total=`current` +";

WHILE @int_ < @interval_ DO
SET @start_interval  = @start + @max;
SET @max=@max+@interval_of;
SET @col_name=concat(@start_interval,'_to_',@max);

SET @sql=concat('ALTER TABLE temp_expected_payment ADD  ', @col_name , ' DECIMAL(11,2) NULL default 0 AFTER ',@last);
SET @last= @col_name;
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql=concat("insert into temp_expected_payment(customer_id,", @col_name ,") select customer_id,sum(grand_total) from temp_load_data where 
DATE_FORMAT(",@aging,",'%Y-%m-%d') <= DATE_FORMAT(DATE_ADD(NOW(),INTERVAL ", @max ," DAY),'%Y-%m-%d') and DATE_FORMAT(",@aging,",'%Y-%m-%d') >= DATE_FORMAT(DATE_ADD(NOW(),INTERVAL ", @start_interval ," DAY),'%Y-%m-%d') group by customer_id;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @finalsql= concat(@finalsql,"sum(`",@col_name,"`) as ",@col_name,",");
SET @totalsql= concat(@totalsql,"+",@col_name);

SET @int_ = @int_ + 1;
END WHILE;
SET @col_name=concat('above_',@max);
SET @sql=concat('ALTER TABLE temp_expected_payment ADD  ', @col_name , ' DECIMAL(11,2) NULL default 0 AFTER ',@last);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql=concat("insert into temp_expected_payment(customer_id,", @col_name ,") select customer_id,sum(grand_total) from temp_load_data where DATE_FORMAT(",@aging,",'%Y-%m-%d') > DATE_FORMAT(DATE_ADD(NOW(),INTERVAL ", @max ," DAY),'%Y-%m-%d') group by customer_id;");
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @finalsql= concat(@finalsql,"sum(`",@col_name,"`) as ",@col_name,",sum(`total`) as `total` from temp_expected_payment where customer_id is not null group by customer_id");
SET @totalsql= concat(@totalsql,"+",@col_name);

PREPARE stmt FROM @totalsql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
update temp_expected_payment b , customer u  set b.customer_name = concat(u.first_name,' ',u.last_name),b.customer_code=u.customer_code  where b.customer_id=u.customer_id ;

PREPARE stmt FROM @finalsql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

Drop TEMPORARY  TABLE  IF EXISTS temp_load_data;
Drop TEMPORARY  TABLE  IF EXISTS temp_expected_payment;
commit;
END
