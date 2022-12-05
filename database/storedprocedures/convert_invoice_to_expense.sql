CREATE DEFINER=`root`@`localhost` PROCEDURE `convert_invoice_to_expense`(_payment_request_id char(10),_customer_merchant_id char(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
            
		END; 
START TRANSACTION;

if(_customer_merchant_id!='')then
	select merchant_id,basic_amount,grand_total,tax_amount,bill_date,due_date,invoice_number,narrative 
	into @merchant_id,@basic_amount,@grand_total,@tax_amount,@bill_date,@due_date,@invoice_number,@narrative 
	from payment_request where payment_request_id=_payment_request_id;

	select vendor_id,category_id,sub_category_id into @vendor_id,@v_category_id,@v_sub_category_id from vendor where merchant_id=_customer_merchant_id and vendor_merchant_id=@merchant_id;

	select expense_auto_generate into @expense_auto_generate from merchant_setting where merchant_id=_customer_merchant_id;
    
    select `name` into @category_name from vendor_category where id=@v_category_id and merchant_id=_customer_merchant_id;
    select `name` into @sub_category_name from vendor_category where id=@v_sub_category_id and merchant_id=_customer_merchant_id;

	if(@vendor_id>0)then
	SET @vendor_id=@vendor_id;
	else
	SET @vendor_id=0;
	end if;
	SET @expense_no='';

	if(@expense_auto_generate=1)then
	SET @expense_no='Auto generate';
	end if;

	SET @igst_amount=0;
	SET @cgst_amount=0;
	SET @cgst_percent=0;
	SET @igst_percent=0;
	SET @expense_id=0;
    SET @category_id=0;
    SET @sub_category_id=0;
    
    select `id` into @category_id from expense_category where `name`=@category_name and merchant_id=_customer_merchant_id;
    select `id` into @sub_category_id from expense_department where `name`=@sub_category_name and merchant_id=_customer_merchant_id;
    

	SET @ccount=0;
	select count(id) into @ccount from invoice_tax t inner join merchant_tax m on t.tax_id=m.tax_id where payment_request_id=_payment_request_id and m.tax_type=1 and t.is_active=1;
	if(@ccount>0)then	
		select sum(tax_amount),tax_percent into @cgst_amount,@cgst_percent from invoice_tax t inner join merchant_tax m on t.tax_id=m.tax_id where payment_request_id=_payment_request_id and m.tax_type=1 and t.is_active=1;
	end if;

	SET @icount=0;
	select count(id) into @icount from invoice_tax t inner join merchant_tax m on t.tax_id=m.tax_id where payment_request_id=_payment_request_id and m.tax_type=3 and t.is_active=1;
	if(@icount>0)then	
		select sum(tax_amount),tax_percent into @igst_amount,@igst_percent from invoice_tax t inner join merchant_tax m on t.tax_id=m.tax_id where payment_request_id=_payment_request_id and m.tax_type=3 and t.is_active=1;
	end if;



	select expense_id into @expense_id from staging_expense where payment_request_id=_payment_request_id limit 1;

	if(@expense_id>0)then
		update staging_expense set invoice_no=@invoice_number,bill_date=@bill_date,due_date=@due_date,base_amount=@basic_amount,cgst_amount=@cgst_amount,sgst_amount=@cgst_amount
		,igst_amount=@igst_amount,total_amount=@grand_total,narrative=@narrative,`type`=3 where expense_id=@expense_id;
		update staging_expense_detail set is_active=0 where expense_id=@expense_id; 
	else
		INSERT INTO `staging_expense`
		(`type`,`merchant_id`,`vendor_id`,`category_id`,`department_id`,`expense_no`,`invoice_no`,`bill_date`,`due_date`,`tds`,`discount`,`adjustment`,`payment_status`,
		`payment_mode`,`base_amount`,`cgst_amount`,`sgst_amount`,`igst_amount`,`total_amount`,`notify`,`narrative`,`bulk_id`,`payment_request_id`,`created_by`,`created_date`,
		`last_update_by`)
		VALUES (3,_customer_merchant_id,@vendor_id,@category_id,@sub_category_id,@expense_no,@invoice_number,@bill_date,@due_date,0,0,0,0,0,@basic_amount,
		@cgst_amount,@cgst_amount,@igst_amount,@grand_total,0,@narrative,0,_payment_request_id,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
		select LAST_INSERT_ID() into @expense_id;
	end if;

	if(@cgst_amount>0)then
	SET @gst_type=1;
	else
	SET @gst_type=2;
	end if;

	select count(id) into @p_count from invoice_particular where payment_request_id=_payment_request_id and is_active=1; 

if(@p_count>0)then	
	if(@gst_type=1)then
		INSERT INTO `staging_expense_detail`
		(`expense_id`,`particular_name`,`sac_code`,`qty`,`rate`,`amount`,`tax`,`cgst_amount`,
		`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`created_by`,`created_date`,
		`last_update_by`)
		select @expense_id,item,sac_code,qty,rate,total_amount,c.config_key,(total_amount*gst/100)/2,(total_amount*gst/100)/2,0,gst,total_amount+(total_amount*gst/100),@merchant_id,CURRENT_TIMESTAMP(),@merchant_id
		from invoice_particular p inner join config c on p.gst=c.config_value and c.config_type='expense_tax_type'
		where payment_request_id=_payment_request_id and p.is_active=1;
	else
		INSERT INTO `staging_expense_detail`
		(`expense_id`,`particular_name`,`sac_code`,`qty`,`rate`,`amount`,`tax`,`cgst_amount`,
		`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`created_by`,`created_date`,
		`last_update_by`)
		select @expense_id,item,sac_code,qty,rate,total_amount,c.config_key,0,0,(total_amount*gst/100),gst,total_amount+(total_amount*gst/100),@merchant_id,CURRENT_TIMESTAMP(),@merchant_id
		from invoice_particular p inner join config c on p.gst=c.config_value and c.config_type='expense_tax_type'
		where payment_request_id=_payment_request_id and p.is_active=1;
	end if;
	 update staging_expense_detail set qty=1 where qty=0  and  expense_id=@expense_id; 
	 update staging_expense_detail set rate=`amount` where qty=1  and  expense_id=@expense_id; 
else
	INSERT INTO `staging_expense_detail`
		(`expense_id`,`particular_name`,`sac_code`,`qty`,`rate`,`amount`,`tax`,`cgst_amount`,
		`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`created_by`,`created_date`,
		`last_update_by`)values(@expense_id,'Particular','',1,@basic_amount,@basic_amount,0,0,0,0,0,@basic_amount,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
end if;

end if;
commit;




END
