CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_monthly_payment_trends`()
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


Drop TEMPORARY  TABLE  IF EXISTS temp_inv;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_inv (
    `merchant_id` varchar(10) NOT NULL ,
    `company_name` varchar(100) NULL ,
    `march` INT NOT NULL default 0,
    `march_sum` decimal(11,2) NOT NULL default 0,
    `april` INT NOT NULL default 0,
    `april_sum` decimal(11,2) NOT NULL default 0,
    `may` INT NOT NULL default 0,
    `may_sum` decimal(11,2) NOT NULL default 0,
    `june` INT NOT  NULL default 0,
    `june_sum` decimal(11,2) NOT  NULL default 0,
    `july` INT NOT  NULL default 0,
    `july_sum` decimal(11,2) NOT  NULL default 0,
    `aug` INT NOT  NULL default 0,
    `aug_sum` decimal(11,2) NOT  NULL default 0,
    `sep` INT NOT  NULL default 0,
	`sep_sum`decimal(11,2) NOT  NULL default 0,
	`oct` INT NOT  NULL default 0,
    `oct_sum` decimal(11,2) NOT  NULL default 0,
    PRIMARY KEY (`merchant_id`)) ENGINE=MEMORY;
    

insert into temp_inv(merchant_id)    
select distinct merchant_id FROM payment_request where created_date>'2020-03-01' and created_date<'2020-12-01' ;

update temp_inv t, merchant m set t.company_name=m.company_name where t.merchant_id=m.merchant_id;


UPDATE temp_inv o 
INNER JOIN
(
   SELECT merchant_id, count(payment_request_id) 'cnt',sum(grand_total) 'sumu'
   FROM payment_request where 
DATE_FORMAT(created_date, "%Y-%m")='2020-03'
   GROUP BY merchant_id
) i ON o.merchant_id = i.merchant_id
SET o.march = i.cnt, o.march_sum = i.sumu;




Drop TEMPORARY  TABLE  IF EXISTS temp_inv_val;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_inv_val (
	`id` int NOT NULL auto_increment,
    `ttype` varchar(10) NOT NULL ,
    `merchant_id` varchar(10) NOT NULL ,
    `company_name` varchar(100) NULL ,
     `march` INT NOT NULL default 0,
    `april` INT NOT NULL default 0,
    `may` INT NOT NULL default 0,
    `june` INT NOT  NULL default 0,
    `july` INT NOT  NULL default 0,
    `aug` INT NOT  NULL default 0,
    `sep` INT NOT  NULL default 0,
	`oct` INT NOT  NULL default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

	insert into temp_inv_val(merchant_id,ttype,company_name,march,april,may,june,july,aug,sep,oct)  
     select merchant_id,'Count',company_name,march,april,may,june,july,aug,sep,oct from temp_inv order by merchant_id;
     
	 insert into temp_inv_val(merchant_id,ttype,company_name,march,april,may,june,july,aug,sep,oct)  
     select merchant_id,'Sum',company_name,march_sum,april_sum,may_sum,june_sum,july_sum,aug_sum,sep_sum,oct_sum from temp_inv order by merchant_id;

     select merchant_id,company_name,march,april,may,june,july,aug,sep,oct from temp_inv_val where ttype='Count' order by merchant_id;
     select merchant_id,company_name,march,april,may,june,july,aug,sep,oct from temp_inv_val where ttype='Sum' order by merchant_id;
     select * from temp_inv_val order by merchant_id;

END
