CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_viewevent`(_userid varchar(10),_from_date datetime , _to_date datetime)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 

Drop TEMPORARY  TABLE  IF EXISTS merchant_view_event;

CREATE TEMPORARY TABLE IF NOT EXISTS merchant_view_event (
    `payment_request_id` varchar(10) NOT NULL ,
    `template_id` varchar(10) NULL,
    `event_name` varchar(250) NULL,
    `user_id` varchar(10) NOT NULL,
    `absolute_cost` DECIMAL(11,2) not null ,
    `payment_request_status` int not null,
    `send_date` datetime not null,
    `due_date` DATETIME not null,
    `status` varchar(250) null,
     `count` int null,
    PRIMARY KEY (`payment_request_id`)) ENGINE=MEMORY;
    
      
       insert into merchant_view_event(payment_request_id,template_id,user_id,
    absolute_cost,payment_request_status,send_date,due_date) 
    select payment_request_id,template_id,user_id,absolute_cost,
    payment_request_status,bill_date,due_date from payment_request where user_id=_userid and payment_request_status=0 and payment_request_type=2 and DATE_FORMAT(last_update_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(last_update_date,'%Y-%m-%d') <= _to_date;
        
   
    update merchant_view_event r , invoice_template u  set r.event_name = u.template_name where r.template_id=u.template_id ;
    
    select count(payment_request_id) into @count from merchant_view_event;
    update merchant_view_event r , invoice_template u  set r.count = @count;

    select *  from merchant_view_event order by payment_request_id desc;
     
END
