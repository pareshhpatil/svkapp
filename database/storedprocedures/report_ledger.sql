CREATE DEFINER=`root`@`localhost` PROCEDURE `report_ledger`(_merchant_id varchar(10),_from_date DATE ,_to_date DATE,_customer_id INT,_franchise_id INT)
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

if(_customer_id=0)then

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
select 'dr',sum(absolute_cost),0,0,0 from payment_request where user_id=@user_id and payment_request_type <> 4 
and DATE_FORMAT(bill_date,'%Y-%m-%d') < _from_date and is_active=1 and payment_request_status<>3;


insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT'dr',sum(absolute_cost),0,0,0 FROM `xway_transaction`
where merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') < _from_date  and xway_transaction_status=1;


insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`captured`),substring(sum(`tdr`),2),substring(sum(`service_tax`),2) FROM `payment_transaction_settlement`
where merchant_id=@user_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') < _from_date;

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`amount`),0,0 FROM `offline_response`
where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m-%d') < _from_date and is_active=1;

else

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
select 'dr',sum(absolute_cost),0,0,0 from payment_request where user_id=@user_id and payment_request_type <> 4 
and DATE_FORMAT(bill_date,'%Y-%m-%d') < _from_date and is_active=1 and payment_request_status<>3 and customer_id=_customer_id;

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`captured`),substring(sum(`tdr`),2),substring(sum(`service_tax`),2) FROM `payment_transaction_settlement` s 
inner join payment_transaction t on s.transaction_id=t.pay_transaction_id
where s.merchant_id=@user_id and DATE_FORMAT(s.transaction_date,'%Y-%m-%d') < _from_date and t.customer_id=_customer_id; 

insert into tmpsumledger (cr_dr,debit,credit,tdr,st)
SELECT 'cr',0,sum(`amount`),0,0 FROM `offline_response`
where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m-%d') < _from_date and is_active=1 and customer_id=_customer_id;

end if;
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
`customer_detail` varchar(250) NULL,  
`customer_code` varchar(45) NULL,  
`cr_dr` varchar(10) NULL, 
`invoice_number` varchar(50) NULL default '', 
`type` varchar(20) null,
`invoice_type` varchar(50) null,
`debit` DECIMAL(11,2) NULL,
`credit` DECIMAL(11,2) NULL,
`tdr` DECIMAL(11,2) NULL,
`st` DECIMAL(11,2) NULL,
`date` DATE null,
PRIMARY KEY (`id`)) ENGINE=MEMORY;

insert into tmpledger (debit,`date`) values(@opening_balance,_from_date);

if(_customer_id=0)then

insert into tmpledger (customer_id,payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
select customer_id,payment_request_id,'dr',invoice_number,payment_request_type,absolute_cost,0,0,0,bill_date from payment_request where user_id=@user_id and payment_request_type <> 4 
and DATE_FORMAT(bill_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= _to_date and is_active=1 and payment_request_status<>3;


insert into tmpledger (customer_detail,customer_code,cr_dr,invoice_number,`type`,debit,credit,tdr,st,`date`,franchise_id)
SELECT `name`,udf1,'dr',reference_no,'Website',absolute_cost,0,0,0,`created_date`,franchise_id FROM `xway_transaction`
where merchant_id=_merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(created_date,'%Y-%m-%d') <= _to_date and xway_transaction_status=1;


insert into tmpledger (payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
SELECT `payment_request_id`,'cr',transaction_id,'',0,captured,substring(`tdr`,2),substring(`service_tax`,2),`transaction_date` FROM `payment_transaction_settlement`
where merchant_id=@user_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= _to_date ;

insert into tmpledger (payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
SELECT `payment_request_id`,'cr',offline_response_id,'',0,`amount`,0,0,`settlement_date` FROM `offline_response`
where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date and is_active=1;

else

insert into tmpledger (customer_id,payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
select customer_id,payment_request_id,'dr',invoice_number,payment_request_type,absolute_cost,0,0,0,bill_date from payment_request where user_id=@user_id and payment_request_type <> 4
and DATE_FORMAT(bill_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(bill_date,'%Y-%m-%d') <= _to_date and is_active=1 and payment_request_status<>3 and customer_id=_customer_id; 


insert into tmpledger (payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
SELECT s.payment_request_id,'cr',transaction_id,'',0,captured,substring(`tdr`,2),substring(`service_tax`,2),`transaction_date` FROM `payment_transaction_settlement` s 
inner join payment_transaction t on s.transaction_id=t.pay_transaction_id
where s.merchant_id=@user_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(transaction_date,'%Y-%m-%d') <= _to_date and t.customer_id=_customer_id; 

insert into tmpledger (payment_request_id,cr_dr,invoice_number,`invoice_type`,debit,credit,tdr,st,`date`)
SELECT `payment_request_id`,'cr',offline_response_id,'',0,`amount`,0,0,`settlement_date` FROM `offline_response`
where merchant_id=_merchant_id and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date  and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date and is_active=1 and customer_id=_customer_id; 

end if;

UPDATE tmpledger s,
    xway_transaction c 
SET 
    s.customer_detail = c.name,
    s.customer_code = c.udf1,
    s.type = 'Website',
    s.invoice_number = c.reference_no
WHERE
    s.invoice_number = c.xway_transaction_id;

UPDATE tmpledger s,
    payment_request c 
SET 
    s.invoice_type = c.payment_request_type,
    s.customer_id = c.customer_id,
    s.franchise_id = c.franchise_id,
    s.invoice_number = c.invoice_number
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
            c.last_name,
            ' ',
            ' (',
            c.customer_code,
            ')'),
            s.customer_code = c.customer_code
WHERE
    s.customer_id = c.customer_id;
UPDATE tmpledger s 
SET 
    s.invoice_number = payment_request_id
WHERE
    s.invoice_number = '';

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
    customer_code,
    cr_dr,
    invoice_number,
    type,
    debit,
    credit,
    tdr,
    st
FROM
    tmpledger where franchise_id=_franchise_id
ORDER BY `date` , id;
else
SELECT 
    date,
    customer_detail,
    customer_code,
    cr_dr,
    invoice_number,
    type,
    debit,
    credit,
    tdr,
    st
FROM
    tmpledger
ORDER BY `date` , id;
         end if;

END
