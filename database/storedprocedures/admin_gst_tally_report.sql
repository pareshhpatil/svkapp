CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_gst_tally_report`(_from_date date , _to_date date)
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
select id from settlement_detail where  settlement_date >= @from_date and settlement_date<= @to_date;

Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation (
	`id` INT auto_increment,
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
    `base_tdr` varchar(45) NULL,
    `merchant_tdr` decimal(11,2) NULL,
    `differential` decimal(11,2) NULL,
    `swipez_revenue` DECIMAL(11,2)  null ,
    `swipez_service_tax` DECIMAL(11,2)  null ,
    `swipez_settlement_amount` DECIMAL(11,2) null ,
    `total_swipez_settlement_amount` DECIMAL(11,2)  null ,
    `swipez_bank_reff`  varchar(20)  NULL,
    `swipez_settlement_date`datetime null ,
    `swipez_settlement_id` INT NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    
    
    
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
         
         
         
         
         
         
         
         
         
         
         Drop TEMPORARY  TABLE  IF EXISTS temp_gst_tally;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_tally (
    `id` INT NOT NULL auto_increment,
    `transaction_type` varchar(45)  NULL ,
    `date` datetime  null ,
    `ledger_name` varchar(250) NULL,
    `amount` DECIMAL(11,2)  null ,
	`merchant_cust_id` INT NULL,
	`merchant_cust_code` varchar(45) NULL,
	`merchant_name` varchar(100) NULL,
	`merchant_id` varchar(10) NULL,
    `cr_dr` varchar(10)  NULL,
    `narration` varchar(45)  NULL,
    `sort` varchar(45)  NULL,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
         
    insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Receipt',settlement_date,'HDFC Bank (50200002538452)',sum(settlement_amount),'Dr',utr_no,'A'
         from temp_gst_calculation group by utr_no; 
    
    insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Receipt',settlement_date,'PG Vendor',sum(amount)-sum(settlement_amount),'Dr',utr_no,'B'
         from temp_gst_calculation group by utr_no; 
         
    insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Receipt',settlement_date,'Nodal Account',sum(amount),'Cr',utr_no,'C'
         from temp_gst_calculation group by utr_no; 
         
         
         
         
      insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Payment',settlement_date,'Nodal Account',sum(total_capture),'Dr',bank_reference,'A'
         from settlement_detail where settlement_date >= @from_date and settlement_date<= @to_date group by bank_reference;   
         
         insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort) 
		 select 'Payment',settlement_date,'HDFC Bank (50200002538452)',sum(settlement_amount),'Cr',bank_reference,'B'
         from settlement_detail where settlement_date >= @from_date and settlement_date<= @to_date group by bank_reference;
         
         insert into temp_gst_tally(transaction_type,date,ledger_name,amount,cr_dr,narration,sort,merchant_id,merchant_name) 
		 select 'Payment',settlement_date,concat(merchant_name ,' (',merchant_id,')'),sum(total_capture)-sum(settlement_amount),'Cr',bank_reference,'C',merchant_id,merchant_name
         from settlement_detail where settlement_date >= @from_date and settlement_date<= @to_date group by bank_reference;
         
         update temp_gst_tally t,customer_column_values v set t.merchant_cust_id=v.customer_id where v.value=t.merchant_id;
         update temp_gst_tally t,customer v set t.merchant_cust_code=v.customer_code where t.merchant_cust_id=v.customer_id;
         
         update temp_gst_tally set ledger_name=concat(merchant_name,' (',merchant_cust_code,')') where sort='C' and transaction_type='Payment';

         
   select transaction_type,date,ledger_name,amount,cr_dr,narration from temp_gst_tally order by date,sort;


END
