CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_merchant_transaction`()
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS temp_tmerchant;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_tmerchant (
	`id` INT NOT NULL auto_increment,
	`merchant_id` char(10) NOT NULL ,
	`tcount` INT default 0,
    `xcount` INT default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

	insert into temp_tmerchant (merchant_id,tcount)
    select merchant_id,count(pay_transaction_id) from payment_transaction where created_date>='2021-01-01' and created_date<='2021-04-27' group by merchant_id;


	insert into temp_tmerchant (merchant_id,xcount)
    select merchant_id,count(xway_transaction_id) from xway_transaction where created_date>='2021-01-01' and created_date<='2021-04-27' group by merchant_id;

select t.merchant_id,company_name,sum(tcount) transaction_count,sum(xcount) xway_count from temp_tmerchant t inner join merchant m on m.merchant_id=t.merchant_id
group by t.merchant_id;


END
