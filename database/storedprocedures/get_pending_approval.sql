CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pending_approval`(_merchant_id varchar(10),_from_date datetime , _to_date datetime)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
		END; 


Drop TEMPORARY  TABLE  IF EXISTS temp_pending_approval;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_pending_approval (
    `change_detail_id` INT NOT NULL ,
    `change_id` INT NOT NULL,
    `column_type` INT NOT NULL,
    `column_value_id` int NOT NULL,
    `column_name` varchar(20) NULL,
    `customer_id` INT NOT NULL,
    `current_value` varchar(500)  NULL,
    `changed_value` varchar(500)  NULL,
    `date` DATETIME not null,
    `customer_code` varchar (100) null,
    `name` varchar (250) null,
	`email` varchar (100) null,
    `mobile` varchar (15) null,
    
    PRIMARY KEY (`change_detail_id`)) ENGINE=MEMORY;
    
      
	insert into temp_pending_approval(change_detail_id,change_id,column_type,column_value_id,customer_id,current_value,changed_value,
    date) 
    select change_detail_id,d.change_id,column_type,column_value_id,c.customer_id,current_value,changed_value,c.created_date
    from customer_data_change c inner join customer_data_change_detail d on c.change_id=d.change_id where c.merchant_id=_merchant_id and d.status=0
    and DATE_FORMAT(c.created_date,'%Y-%m-%d') >= _from_date and DATE_FORMAT(c.created_date,'%Y-%m-%d') <= _to_date ;
        
    
    
    update temp_pending_approval r , customer u  set r.customer_code = u.customer_code,r.name=concat(u.first_name,' ',u.last_name),r.email=u.email,r.mobile=u.mobile  where r.customer_id=u.customer_id;
    
    update temp_pending_approval r , config c  set r.column_name = c.config_value where r.column_type=c.config_key and c.config_type='change_column_type' ;

    
    select * from temp_pending_approval order by date desc ;
    
     
     

END