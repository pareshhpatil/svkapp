CREATE DEFINER=`root`@`localhost` PROCEDURE `temp_gazonDynamic`(req_id varchar(10))
BEGIN

DECLARE EXIT HANDLER FOR SQLEXCEPTION
show errors;
		BEGIN
END;






    select template_id into @template_id from payment_request where payment_request_id=req_id;
    select repeat_every,mode into @repeat_every,@mode from subscription where payment_request_id=req_id;
    if(@mode =4)then
    update subscription set billing_period_start_date=DATE_FORMAT(due_date,'%Y-%m-01') ,billing_period_duration=12 , billing_period_type='month' where payment_request_id=req_id;
    else
    update subscription set billing_period_start_date=DATE_FORMAT(due_date,'%Y-%m-01') ,billing_period_duration=repeat_every , billing_period_type='month' where payment_request_id=req_id;
     end if;
      select column_id,created_by into @column_id,@created_by from invoice_column_metadata where template_id=@template_id and function_id=10 and is_active=1 limit 1;
      SET @check_column_id=0;
      
      SELECT column_id into @check_column_id from invoice_column_values where column_id=@column_id and payment_request_id=req_id limit 1;
      select column_id into @time_column_id from invoice_column_metadata where template_id=@template_id and column_type='PS' and `column_name`='Time period' limit 1;
      if(@mode =4)then
		update invoice_column_values set value='Yearly' where column_id=@time_column_id and payment_request_id=req_id;
        else
        update invoice_column_values set value=concat(@repeat_every,' month') where column_id=@time_column_id and payment_request_id=req_id;
        end if;
      if(@check_column_id=0)then
      INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`)
		VALUES(req_id,@column_id,'',1,@created_by,CURRENT_TIMESTAMP(),created_by);
		end if;
        
  


END
