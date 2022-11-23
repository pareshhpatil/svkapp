CREATE DEFINER=`root`@`localhost` PROCEDURE `get_merchant_cycledetail`(_userid varchar(250),_type varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

Drop TEMPORARY  TABLE  IF EXISTS merchant_cycle_detail;

CREATE TEMPORARY TABLE IF NOT EXISTS merchant_cycle_detail (
    `merchant_id` varchar(10) NOT NULL,
    `name` varchar (100) null,
    PRIMARY KEY (`merchant_id`)) ENGINE=MEMORY;
    
    
    if(_type='Request')then
     insert into merchant_cycle_detail(merchant_id)  select distinct merchant_id
    from customer where email=_userid ;
    
    else
			insert into merchant_cycle_detail(merchant_id)  select distinct merchant_id
			from customer where email=_userid  ;
    end if;
   
    update merchant_cycle_detail t , merchant u  set t.name = u.company_name where t.merchant_id=u.merchant_id ;

 
    select distinct name,merchant_id as id from merchant_cycle_detail;
   
    

END
