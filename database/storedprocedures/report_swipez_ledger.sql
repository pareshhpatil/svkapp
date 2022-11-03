CREATE DEFINER=`root`@`localhost` PROCEDURE `report_swipez_ledger`(_merchant_id varchar(10),_from_date DATE ,_to_date DATE,_customer_id INT
,_franchise_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 
        
Drop TEMPORARY  TABLE  IF EXISTS tmpsumledger;

CREATE TEMPORARY TABLE IF NOT EXISTS tmpsumledger ( 
`id` INT NOT NULL auto_increment , 
`cr_dr` varchar(10) NOT NULL, 
`debit` DECIMAL(11,2),
`credit` DECIMAL(11,2),
`tdr` DECIMAL(11,2),
`st` DECIMAL(11,2),
PRIMARY KEY (`id`)) ENGINE=MEMORY;



SELECT 
    user_id
INTO @user_id FROM
    merchant
WHERE
    merchant_id = _merchant_id;

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
select 'dr',sum(amount),0,0,0 from payment_transaction where merchant_id=_merchant_id and payment_transaction_status =1 
and DATE_FORMAT(created_date,'%Y-%m-%d') < _from_date and DATE_FORMAT(created_date,'%Y-%m-%d')>'2016-01-01';


insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'dr',sum(absolute_cost),0,0,0 FROM `xway_transaction`
where merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') < _from_date and DATE_FORMAT(created_date,'%Y-%m-%d')>'2016-01-01' and xway_transaction_status=1;

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`captured`),0,0 FROM `payment_transaction_settlement`
where merchant_id=@user_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') < _from_date;

SELECT 
    SUM(debit) - SUM(credit)
INTO @opening_balance FROM
    tmpsumledger;




Drop TEMPORARY  TABLE  IF EXISTS tmpledger;

CREATE TEMPORARY TABLE IF NOT EXISTS tmpledger ( 
`id` INT NOT NULL auto_increment , 
`customer_id` INT NULL,  
`franchise_id` INT NULL default 0,  
`payment_request_id` varchar(10) NULL, 
`transaction_id` varchar(10) NULL, 
`customer_detail` varchar(250) NULL,  
`__Customer_code` varchar(45) NULL, 
`__Narrative` varchar(500) NULL, 
`invoice_number` varchar(50) NULL default '', 
`type` varchar(20) null,
`invoice_type` varchar(50) null,
`debit` DECIMAL(11,2) not NULL default 0,
`credit` DECIMAL(11,2) not NULL default 0,
`tdr` DECIMAL(11,2) not NULL default 0,
`st` DECIMAL(11,2) not NULL default 0,
`date` DATETIME null,
`settlement_date` DATETIME null,
`settlement_id` INT not null default 0,
`bank_ref` varchar(50) null,
`__Settlement_value` decimal(11,2) not null default 0,
`__Bill_date` DATE null,
PRIMARY KEY (`id`)) ENGINE=MEMORY;




insert into tmpledger (customer_id,payment_request_id,transaction_id,debit,credit,tdr,st,`date`)
select customer_id,payment_request_id,pay_transaction_id,amount,0,0,0,created_date from payment_transaction where merchant_id=_merchant_id and payment_transaction_status =1 
and DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date ;


insert into tmpledger (customer_detail,__Customer_code,__Narrative,transaction_id,invoice_number,`type`,debit,credit,tdr,st,`date`,franchise_id)
SELECT `name`,udf1,description,xway_transaction_id,reference_no,'Website',absolute_cost,0,0,0,`created_date`,franchise_id FROM `xway_transaction`
where merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date and xway_transaction_status=1;


UPDATE tmpledger l,
    payment_transaction_settlement s 
SET 
    credit = s.captured,
    l.tdr = REPLACE(s.tdr, '-', ''),
    st = REPLACE(s.service_tax, '-', ''),
    l.settlement_date=s.settlement_date,
    l.bank_ref=s.bank_reff,
    l.settlement_id=s.settlement_id
WHERE
    l.transaction_id = s.transaction_id;
    
    
    UPDATE tmpledger s,
    settlement_detail c 
SET 
    s.__Settlement_value = c.requested_settlement_amount
WHERE
    s.settlement_id = c.id;
    

UPDATE tmpledger s,
    payment_request c 
SET 
    s.invoice_type = c.payment_request_type,
    s.customer_id = c.customer_id,
    s.franchise_id = c.franchise_id,
    s.invoice_number = c.invoice_number,
    s.__Narrative=c.narrative,
    s.__Bill_date=c.bill_date
WHERE
    s.payment_request_id = c.payment_request_id;
    
    
    UPDATE tmpledger s,
    event_request c 
SET 
    s.invoice_type = 2,
    s.franchise_id = c.franchise_id
WHERE
    s.payment_request_id = c.event_request_id;
    
UPDATE tmpledger s,
    customer c 
SET 
    s.customer_detail = CONCAT(c.first_name,
            ' ',
            c.last_name)
            ,
            s.__Customer_code=c.customer_code
WHERE
    s.customer_id = c.customer_id;

UPDATE tmpledger s,
    config c 
SET 
    s.type = c.config_value
WHERE
    s.invoice_type = c.config_key
        AND c.config_type = 'payment_request_type';


if(_franchise_id>0)then
    SELECT 
    date,
    customer_detail,
    transaction_id ,
    invoice_number,
    type,
    debit,
    credit,
    tdr,
    st,
    settlement_date,
	settlement_id,
	bank_ref,
	__Settlement_value,
	__Bill_date
    
FROM
    tmpledger where franchise_id=_franchise_id
ORDER BY `date` , id;
else
    SELECT 
    date,
    customer_detail,
    __Customer_code,
    transaction_id ,
    invoice_number,
    type,
    __Narrative,
    debit,
    credit,
    tdr,
    st,
    settlement_date,
	settlement_id,
	bank_ref,
	__Settlement_value,
	__Bill_date
FROM
    tmpledger
ORDER BY `date` , id;
end if;



         

END
