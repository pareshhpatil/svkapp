CREATE DEFINER=`root`@`localhost` PROCEDURE `get_patron_viewtransaction`(_userid varchar(250),_from_date date , _to_date date,_user_name varchar(10) ,_status int)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 show errors;
		BEGIN
		END; 

Drop TEMPORARY  TABLE  IF EXISTS patron_view_transaction;

CREATE TEMPORARY TABLE IF NOT EXISTS patron_view_transaction (
    `pay_transaction_id` varchar(10) NOT NULL ,
    `payment_request_id` varchar(10) NOT NULL ,
    `payment_request_type` INT NOT NULL ,
    `user_id` varchar(10) NOT NULL,
    `customer_id` INT NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_transaction_status` int not null,
    `is_reject` int not null default 0,
    `narrative` varchar(500) null,
    `date` DATETIME not null,
    `last_update_on` DATETIME not null,
    `due_date` DATETIME  null,
    `name` varchar (100) null,
    `mode` varchar (10) null,
    `status` varchar(250) null,
    `count` int null,
    
    PRIMARY KEY (`pay_transaction_id`)) ENGINE=MEMORY;
    
    
    Drop TEMPORARY  TABLE  IF EXISTS tempt_customer_ids;
CREATE TEMPORARY TABLE IF NOT EXISTS tempt_customer_ids (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
insert into tempt_customer_ids(customer_id)
select customer_id from customer where email=_userid;
    
    if(_user_name<>'' and _status <> -1)then
        
        if(_status=4) then
        insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and merchant_id=_user_name and payment_transaction_status in (4,0) and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    else        
          insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and merchant_id=_user_name and payment_transaction_status= _status and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    
    end if;
    
    if(_status=2) then
    
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and is_active=1 and offline_response_type <> 4 and  merchant_id=_user_name and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
    end if;
    
     if(_status=3) then
   insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and is_active=1 and offline_response_type = 4 and  merchant_id=_user_name and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
        end if;
    
    elseif(_user_name='' and _status <> -1)then
    
     if(_status=4) then
     
      insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and payment_transaction_status in (4,0) and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    
    else
    insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and payment_transaction_status=_status and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    end if;
    
     if(_status=2) then
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and offline_response_type <> 4 and is_active=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
   
   elseif(_status=3) then
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    3,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and offline_response_type=4 and is_active=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
  
    end if;
    
    elseif(_user_name<>'' and _status = -1) then
    
    insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and merchant_id=_user_name and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and is_active=1 and merchant_id=_user_name and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
    
    else
    
    insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,narrative,date,last_update_on,mode) 
    select pay_transaction_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    payment_transaction_status,narrative,last_update_date,last_update_date,'online' from payment_transaction where payment_transaction.customer_id in(select customer_id from tempt_customer_ids) and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date ;
    
     insert into patron_view_transaction(pay_transaction_id,payment_request_id,payment_request_type,user_id,customer_id,
    absolute_cost,payment_transaction_status,is_reject,narrative,date,last_update_on,mode) 
    select offline_response_id,payment_request_id,payment_request_type,merchant_user_id,customer_id,amount,
    2,offline_response_type,narrative,settlement_date,last_update_date,'offline' from offline_response where customer_id in(select customer_id from customer where user_id=_userid) and is_active=1 and DATE_FORMAT(settlement_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d') <= _to_date ;
    
    end if;
    
    update patron_view_transaction t , payment_request r  set t.due_date = r.due_date where t.payment_request_id=r.payment_request_id ;
    
    update patron_view_transaction t , event_request r  set t.due_date = r.event_from_date where t.payment_request_id=r.event_request_id ;

   
    update patron_view_transaction t , merchant m  set t.name = m.company_name where t.user_id=m.user_id ;
    
   
    update patron_view_transaction t , config c  set t.status = c.config_value where t.payment_transaction_status=c.config_key and c.config_type='payment_transaction_status';
    select * from patron_view_transaction order by last_update_on desc;
          
    
    

END
