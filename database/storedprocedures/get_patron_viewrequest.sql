CREATE DEFINER=`root`@`localhost` PROCEDURE `get_patron_viewrequest`(_userid varchar(250),_from_date date , _to_date date,_user_name varchar(10)
)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
 show errors;
		BEGIN
		END;

Drop TEMPORARY  TABLE  IF EXISTS patron_view_request;

CREATE TEMPORARY TABLE IF NOT EXISTS patron_view_request (
    `payment_request_id` varchar(10) NOT NULL ,
    `merchant_id` varchar(10) NOT NULL,
    `user_id` varchar(10) NOT NULL,
    `customer_id` INT NOT NULL,
    `billing_cycle_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `received_date` datetime not null,
    `due_date` DATETIME not null,
    `merchant_type` int  null,
    `merchant_domain` int Null,
    `merchant_domain_name` varchar(100) null,
    `name` varchar (100) null,
    `status` varchar(250) null,
    `billing_cycle_name` varchar(40) null,
    `count` int  null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;


Drop TEMPORARY  TABLE  IF EXISTS temp_customer_ids;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_ids (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;
    
insert into temp_customer_ids(customer_id)
select customer_id from customer where email=_userid;
    if(_user_name<>'')then
        insert into patron_view_request(payment_request_id,user_id,merchant_id,customer_id,billing_cycle_id,
    absolute_cost,payment_request_status,received_date,due_date)
    select payment_request_id,user_id,merchant_id,r.customer_id,billing_cycle_id,absolute_cost,
    payment_request_status,last_update_date,due_date from payment_request r inner join temp_customer_ids c  where r.customer_id =c.customer_id and 
    (payment_request_type<>4 OR parent_request_id<>'0') and payment_request_status in (0,4,5) and merchant_id = _user_name 
    and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date  ;

    else
        insert into patron_view_request(payment_request_id,user_id,merchant_id,customer_id,billing_cycle_id,
    absolute_cost,payment_request_status,received_date,due_date)
    select payment_request_id,user_id,merchant_id,r.customer_id,billing_cycle_id,absolute_cost,
    payment_request_status,last_update_date,due_date from payment_request r inner join temp_customer_ids c  where r.customer_id =c.customer_id and 
    (payment_request_type<>4 OR parent_request_id<>'0') and payment_request_status in(0,4,5) and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and 
    DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date;



    end if;
    update patron_view_request r , merchant m  set r.name = m.company_name , r.merchant_type=m.merchant_type,r.merchant_domain=m.merchant_domain where r.merchant_id=m.merchant_id ;
    update patron_view_request r , config c  set r.merchant_domain_name = c.config_value where r.merchant_domain=c.config_key and c.config_type='merchant_domain' ;


    update patron_view_request r , config c  set r.status = c.config_value where r.payment_request_status=c.config_key and c.config_type='payment_request_status';

     update patron_view_request r , billing_cycle_detail b  set r.billing_cycle_name = b.cycle_name where r.billing_cycle_id=b.billing_cycle_id ;

	select * from patron_view_request order by payment_request_id desc ;


END
