CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_paid_merchant_transaction`(_start_date DATE, _end_date DATE)
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
    select p.merchant_id,count(p.pay_transaction_id) from payment_transaction p
    inner join merchant m on m.merchant_id=p.merchant_id
    where m.merchant_plan != 2 and m.merchant_plan != 1 and DATE_FORMAT(p.created_date,'%Y-%m-%d')>=_start_date and DATE_FORMAT(p.created_date,'%Y-%m-%d')<=_end_date group by p.merchant_id;
	insert into temp_tmerchant (merchant_id,xcount)
    select x.merchant_id,count(x.xway_transaction_id) from xway_transaction x
    inner join merchant m on m.merchant_id=x.merchant_id
    where m.merchant_plan != 2 and m.merchant_plan != 1 and DATE_FORMAT(x.created_date,'%Y-%m-%d')>=_start_date and DATE_FORMAT(x.created_date,'%Y-%m-%d')<=_end_date group by x.merchant_id;
select t.merchant_id,company_name,sum(tcount) transaction_count,sum(xcount) xway_count from temp_tmerchant t inner join merchant m on m.merchant_id=t.merchant_id
group by t.merchant_id;
END
