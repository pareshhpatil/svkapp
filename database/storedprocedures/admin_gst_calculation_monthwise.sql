CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_gst_calculation_monthwise`(_from_date date , _to_date date,_merchant_id char(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 

SET @from_date=DATE_FORMAT(_from_date,'%Y-%m-%d');
SET @to_date=DATE_FORMAT(_to_date,'%Y-%m-%d');

Drop TEMPORARY  TABLE  IF EXISTS temp_gst_month_set;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_month_set (
    `settelement_id` INT NOT NULL ,
PRIMARY KEY (`settelement_id`)) ENGINE=MEMORY;

if(_merchant_id<>'')then
insert into temp_gst_month_set
select id from settlement_detail where  DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date and merchant_id=_merchant_id;
else
insert into temp_gst_month_set
select id from settlement_detail where  DATE_FORMAT(settlement_date,'%Y-%m-%d') >= @from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<= @to_date;
end if;

Drop TEMPORARY  TABLE  IF EXISTS temp_gst_calculation_month;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_gst_calculation_month (
    `id` INT NOT NULL ,
    `settelement_id` INT NOT NULL ,
    `date` date  NULL,
    `merchant_id` varchar(20)  NULL,
    `pg_tdr_amount` decimal(11,2) NULL,
    `pg_service_tax` decimal(11,2) NULL,
    `swipez_revenue` DECIMAL(11,2)  null ,
    `swipez_service_tax` DECIMAL(11,2)  null ,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    

	insert into temp_gst_calculation_month(id,date,settelement_id,merchant_id,pg_tdr_amount,pg_service_tax,swipez_revenue,swipez_service_tax) 
	 select id,DATE_FORMAT(created_date,'%Y-%m-1'),swipez_settlement_id,merchant_id,pg_tdr_amount,pg_service_tax,swipez_revenue,swipez_service_tax
	 from revenue_detail where swipez_settlement_id in(select settelement_id from temp_gst_month_set);
       
       update temp_gst_calculation_month t, settlement_detail d set t.date = DATE_FORMAT(d.settlement_date,'%Y-%m-1') where t.settelement_id=d.id;
       
 Drop TEMPORARY  TABLE  IF EXISTS temp_caltdr;

   CREATE TEMPORARY TABLE IF NOT EXISTS temp_caltdr (
    `id` INT NOT NULL auto_increment ,
    `merchant_id` varchar(20)  NULL,
    `date` date  NULL,
    `case1_pgrev` decimal(11,2) NOT NULL default 0,
    `case1_pgtx` decimal(11,2) NOT NULL default 0,
    `case1_swrev` decimal(11,2) NOT NULL default 0,
    `case1_swtx` decimal(11,2) NOT NULL default 0,
    
    `case2_pgrev` decimal(11,2) NOT NULL default 0,
    `case2_pgtx` decimal(11,2) NOT NULL default 0,
    `case2_swrev` decimal(11,2) NOT NULL default 0,
    `case2_swtx` decimal(11,2) NOT NULL default 0,
    
    `case3_pgrev` decimal(11,2) NOT NULL default 0,
    `case3_pgtx` decimal(11,2) NOT NULL default 0,
    `case3_swrev` decimal(11,2) NOT NULL default 0,
    `case3_swtx` decimal(11,2) NOT NULL default 0,
    
    `case4_pgrev` decimal(11,2) NOT NULL default 0,
    `case4_pgtx` decimal(11,2) NOT NULL default 0,
    `case4_swrev` decimal(11,2) NOT NULL default 0,
    `case4_swtx` decimal(11,2) NOT NULL default 0,
    
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
       
         insert into temp_caltdr(merchant_id,`date`)
         select distinct merchant_id,`date` from temp_gst_calculation_month;


UPDATE temp_caltdr o 
INNER JOIN
(
   SELECT merchant_id,date,pg_service_tax,swipez_service_tax,
   SUM(pg_tdr_amount) 'pgrev',SUM(pg_service_tax) 'pgtax', SUM(swipez_revenue) 'swrev',SUM(swipez_service_tax) 'swtax'
   FROM temp_gst_calculation_month 
   where pg_service_tax>0 and swipez_service_tax>0
   GROUP BY merchant_id,date
) i ON o.merchant_id = i.merchant_id and  o.date = i.date
SET o.case1_pgrev = i.pgrev,o.case1_pgtx = i.pgtax,o.case1_swrev = i.swrev,o.case1_swtx = i.swtax
WHERE o.merchant_id = i.merchant_id and  o.date = i.date;


UPDATE temp_caltdr o 
INNER JOIN
(
   SELECT merchant_id,date,pg_service_tax,swipez_service_tax,
   SUM(pg_tdr_amount) 'pgrev',SUM(pg_service_tax) 'pgtax', SUM(swipez_revenue) 'swrev',SUM(swipez_service_tax) 'swtax'
   FROM temp_gst_calculation_month 
   where pg_service_tax>0 and swipez_service_tax=0
   GROUP BY merchant_id,date
) i ON o.merchant_id = i.merchant_id and  o.date = i.date
SET o.case2_pgrev = i.pgrev,o.case2_pgtx = i.pgtax,o.case2_swrev = i.swrev,o.case2_swtx = i.swtax
WHERE o.merchant_id = i.merchant_id and  o.date = i.date;


UPDATE temp_caltdr o 
INNER JOIN
(
   SELECT merchant_id,date,pg_service_tax,swipez_service_tax,
   SUM(pg_tdr_amount) 'pgrev',SUM(pg_service_tax) 'pgtax', SUM(swipez_revenue) 'swrev',SUM(swipez_service_tax) 'swtax'
   FROM temp_gst_calculation_month 
   where pg_service_tax=0 and swipez_service_tax>0
   GROUP BY merchant_id,date
) i ON o.merchant_id = i.merchant_id and  o.date = i.date
SET o.case3_pgrev = i.pgrev,o.case3_pgtx = i.pgtax,o.case3_swrev = i.swrev,o.case3_swtx = i.swtax
WHERE o.merchant_id = i.merchant_id and  o.date = i.date;


UPDATE temp_caltdr o 
INNER JOIN
(
   SELECT merchant_id,date,pg_service_tax,swipez_service_tax,
   SUM(pg_tdr_amount) 'pgrev',SUM(pg_service_tax) 'pgtax', SUM(swipez_revenue) 'swrev',SUM(swipez_service_tax) 'swtax'
   FROM temp_gst_calculation_month 
   where pg_service_tax=0 and swipez_service_tax=0
   GROUP BY merchant_id,date
) i ON o.merchant_id = i.merchant_id and  o.date = i.date
SET o.case4_pgrev = i.pgrev,o.case4_pgtx = i.pgtax,o.case4_swrev = i.swrev,o.case4_swtx = i.swtax
WHERE o.merchant_id = i.merchant_id and  o.date = i.date;


Drop TEMPORARY  TABLE  IF EXISTS temp_caltdr_output;

   CREATE TEMPORARY TABLE IF NOT EXISTS temp_caltdr_output (
    `id` INT NOT NULL auto_increment ,
    `merchant_id` varchar(20)  NULL,
    `merchant_name` varchar(100)  NULL,
    `customer_code` varchar(100)  NULL,
    `city_state` varchar(100)  NULL,
    `date` date  NULL,
    `tdr_with_tax` decimal(11,2) NOT NULL default 0,
    `tax` decimal(11,2) NOT NULL default 0,
    `tdr_without_tax` decimal(11,2) NOT NULL default 0,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

insert temp_caltdr_output (merchant_id,date,tdr_with_tax,tax,tdr_without_tax)
select merchant_id,date,case1_pgrev+case2_pgrev+case1_swrev+case3_swrev,
case1_pgtx+case1_swtx+case2_pgtx+case3_swtx,case3_pgrev+case4_pgrev from temp_caltdr;

update temp_caltdr_output o,merchant m set merchant_name=company_name,customer_code=m.admin_customer_code where o.merchant_id=m.merchant_id;

update temp_caltdr_output o,merchant_billing_profile m set city_state=m.state where o.merchant_id=m.merchant_id and is_default=1;

select merchant_id,customer_code,merchant_name,city_state,DATE_FORMAT(date,'%b-%Y') as `Month`,tdr_with_tax,tax,tdr_without_tax from temp_caltdr_output;

END
