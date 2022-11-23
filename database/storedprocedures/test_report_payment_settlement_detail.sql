CREATE DEFINER=`root`@`localhost` PROCEDURE `test_report_payment_settlement_detail`(_userid varchar(10),_from_date date , _to_date date,_franchise_id INT,_settlement_id INT)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);

Drop TEMPORARY  TABLE  IF EXISTS temp_payment_settlement_detail;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_payment_settlement_detail (
  `id` int(11) NOT NULL,
  `settlement_id` varchar(20) DEFAULT NULL,
  `payment_id` varchar(45) DEFAULT NULL,
  `transaction_id` varchar(10) DEFAULT NULL,
  `payment_request_id` varchar(10) DEFAULT NULL,
  `merchant_id` varchar(10) DEFAULT NULL,
  `patron_id` varchar(10) DEFAULT NULL,
  `captured` decimal(11,2) DEFAULT NULL,
  `tdr` decimal(11,2) DEFAULT NULL,
  `service_tax` decimal(11,2) DEFAULT NULL,
  `bank_reff` varchar(20) DEFAULT NULL,
  `franchise_name` varchar(50) DEFAULT NULL,
  `franchise_id` INT DEFAULT 0,
  `transaction_date` datetime DEFAULT NULL,
  `settlement_date` datetime DEFAULT NULL,
  `created_date` timestamp DEFAULT '2014-01-01 00:00:00',
  `customer_id` int(11) DEFAULT NULL,
  `customer_code` varchar(45) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  
    PRIMARY KEY (`id`)) ENGINE=MEMORY;


	if(_settlement_id>0)then
    insert into temp_payment_settlement_detail(`id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date`) select `id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date` from payment_transaction_settlement 
    where settlement_id=_settlement_id ;
	
	else
    
    insert into temp_payment_settlement_detail(`id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date`) select `id`,`settlement_id`,`payment_id`,`transaction_id`,`payment_request_id`,
    `merchant_id`,`patron_id`,`captured`,`tdr`,`service_tax`,`bank_reff`,`transaction_date`,
    `settlement_date`,`created_date` from payment_transaction_settlement 
    where DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date and merchant_id=_userid;
    end if;
    
   
UPDATE temp_payment_settlement_detail t,
    payment_request p 
SET 
    t.franchise_id = p.franchise_id
WHERE
    t.payment_request_id = p.payment_request_id;
    
    UPDATE temp_payment_settlement_detail t,
    payment_transaction p 
SET 
    t.customer_id = p.customer_id
WHERE
    t.transaction_id = p.pay_transaction_id;
    
    
UPDATE temp_payment_settlement_detail t,
    customer c 
SET 
    t.customer_name = CONCAT(c.first_name, ' ', c.last_name),
    t.customer_code = c.customer_code
WHERE
    (t.customer_id = c.customer_id);
    
	
    
UPDATE temp_payment_settlement_detail t,
    xway_transaction c 
SET 
    t.customer_name = c.name,
    t.customer_code=c.udf1,
    t.franchise_id = c.franchise_id
WHERE
    (t.transaction_id = c.xway_transaction_id);
    
    UPDATE temp_payment_settlement_detail t,
    franchise p 
SET 
    t.franchise_name = p.franchise_name
WHERE
    t.franchise_id = p.franchise_id;

if(_franchise_id>0 )then
    select * from temp_payment_settlement_detail where franchise_id=_franchise_id order by created_date;
else
    select * from temp_payment_settlement_detail order by created_date;
end if;
    Drop TEMPORARY  TABLE  IF EXISTS temp_payment_settlement_detail;

END
