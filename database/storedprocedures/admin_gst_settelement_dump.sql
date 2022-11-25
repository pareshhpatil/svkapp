CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_gst_settelement_dump`(_from_date date , _to_date date)
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');


Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation_set;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation_set (
    `settelement_id` INT NOT NULL ,
PRIMARY KEY (`settelement_id`)) ENGINE=MEMORY;

insert into temp_gst_calculation_set
select id from settlement_detail where  DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date;

Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation (
    `transaction_id` varchar(10) NOT NULL ,
    `payment_id` varchar(45)  NULL ,
    `amount` DECIMAL(11,2)  null ,
    `transaction_date` datetime  NULL,
    `customer_name` varchar(70) NULL,
    `customer_email` varchar(100) NULL,
    `customer_phone` varchar(20)  NULL,
    `settlement_amount` DECIMAL(11,2)  null ,
    `settlement_date` datetime,
    `payu_tdr_charge`  DECIMAL(11,2) null ,
    `payu_service_tax`  DECIMAL(11,2) null ,
    `payment_mode` varchar(10)  NULL,
    `utr_no` varchar(20)  NULL,
    `merchant_id` varchar(20)  NULL,
    `merchant_name` varchar(45) NULL,
    `base_tdr` decimal(11,2) NULL,
    `merchant_tdr` decimal(11,2) NULL,
    `differential` decimal(11,2) NULL,
    `swipez_revenue` DECIMAL(11,2)  null ,
    `swipez_service_tax` DECIMAL(11,2)  null ,
    `swipez_settlement_amount` DECIMAL(11,2) null ,
    `total_swipez_settlement_amount` DECIMAL(11,2)  null ,
    `swipez_bank_reff`  varchar(20)  NULL,
    `swipez_settlement_date`datetime null ,
    `swipez_settlement_id` INT NULL,
    PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;
    
    
    
    
		 insert into temp_gst_calculation(transaction_id,settlement_amount,payu_tdr_charge,payu_service_tax,merchant_id,swipez_settlement_amount,swipez_settlement_id
         ,merchant_name,swipez_revenue,merchant_tdr,base_tdr,swipez_service_tax) 
		 select transaction_id,pg_settlement_amount,pg_tdr_amount,pg_service_tax,merchant_id,swipez_amount,swipez_settlement_id,merchant_name,swipez_revenue,swipez_tdr
         ,(pg_tdr_amount*100)/amount,swipez_service_tax
         from revenue_detail where swipez_settlement_id in(select settelement_id from temp_gst_calculation_set);
    
		update temp_gst_calculation t, payu_settlement r set t.payment_id=r.payment_id,
         t.amount=r.amount,t.customer_name=r.customer_name,t.transaction_date=r.succeeded_on_date,
         t.payment_mode=r.payment_mode,t.customer_email=r.customer_email,
         t.customer_phone=r.customer_phone,t.settlement_date=r.settlement_date,t.utr_no=r.utr_number
         where t.transaction_id=r.transaction_id;
         
        
    
    
        
         
         
         update temp_gst_calculation t, settlement_detail r set t.total_swipez_settlement_amount=r.settlement_amount,
         t.swipez_bank_reff=r.bank_reference,t.swipez_settlement_date=r.settlement_date
         where t.swipez_settlement_id=r.id;
         
         update temp_gst_calculation set differential=merchant_tdr-base_tdr;
         
         
         select * from temp_gst_calculation;


END
