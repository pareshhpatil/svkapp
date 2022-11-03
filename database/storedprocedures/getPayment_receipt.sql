CREATE DEFINER=`root`@`localhost` PROCEDURE `getPayment_receipt`(_payment_transaction_id varchar(10),_type varchar(20))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
		END; 
SET @patron_email='';
SET @transaction_id='';
SET @settlement_date='';
SET @patron_user_id='';
SET @patron_name='';
SET @pay_mode='';
SET @success='';
SET @cheque_status='';
SET @franchise_id=0;
SET @customer_id=0;
SET @event_name='';
SET @unit_type='';
SET @type='';
SET @quantity =0;
SET @xway_type =0;
SET @franchise_name_invoice=1;
SET @estimate_req_id='';
SET @customer_code='';
SET @invoice_number='';
SET @profile_id =0;

if(_type='Online' or _type='Offline') then
		if(_type='Offline') then
			SELECT 'success',payment_request_id,customer_id,settlement_date,last_update_date,bank_transaction_no,amount,cheque_no,cheque_status,cash_paid_to,narrative,merchant_id,offline_response_type,bank_name,payment_request_type,tax,discount,is_availed,quantity,narrative,deduct_amount,deduct_text,created_date,quantity
			into @success,@payment_request_id,@customer_id,@settlement_date,@update_on,@bank_transaction_no,@amount,@cheque_no,@cheque_status,@cash_paid_to,@narrative,@merchant_id,@offline_response_type,@bank_name,@payment_request_type,@tax,@discount,@is_availed,@quantity,@narrative,@deduct_amount,@deduct_text,@transaction_date,@quantity
			FROM offline_response where offline_response_id=_payment_transaction_id and is_active=1;
		
			SELECT config_value INTO @payment_mode FROM config WHERE config_key = @offline_response_type AND config_type = 'offline_response_type';
		else
			select 'success',pay_transaction_id,estimate_request_id,payment_request_id,customer_id,patron_user_id,customer_id,merchant_id,pg_ref_no,amount,payment_request_type,tax,discount,is_availed,quantity,narrative,deduct_amount,deduct_text,created_date,quantity,payment_mode into 
			@success,@transaction_id,@estimate_req_id,@payment_request_id,@customer_id,@patron_user_id,@customer_id,@merchant_id,@pg_ref_no,@amount,@payment_request_type,@tax,@discount,@is_availed,@quantity,@narrative,@deduct_amount,@deduct_text,@transaction_date,@quantity,@pay_mode from
			 payment_transaction where pay_transaction_id=_payment_transaction_id;
             
		end if;
        
		select concat(first_name,' ',last_name),email,mobile,customer_code into @patron_name,@patron_email,@patron_mobile,@customer_code from customer where customer_id=@customer_id ;
        
        if(@payment_request_type=2)then
		select template_id,franchise_id,event_name,unit_type,'event' into @template_id,@franchise_id,@event_name,@unit_type,@type from event_request where event_request_id=@payment_request_id;
		else
		select template_id,franchise_id,billing_profile_id,invoice_number into @template_id,@franchise_id,@profile_id,@invoice_number from payment_request where payment_request_id=@payment_request_id;
		end if;
        
        SELECT image_path INTO @image FROM invoice_template WHERE    template_id = @template_id;
        
else
		select 'success',name,email,phone,merchant_id,amount,description,customer_code,created_date,payment_mode,`type` into @success,@patron_name,@patron_email,@patron_mobile,@merchant_id,@amount,@narrative,@customer_code,
            @transaction_date,@pay_mode,@xway_type from xway_transaction where xway_transaction_id= _payment_transaction_id;
end if;

 SET @settlement_date=@transaction_date;
	
SELECT 
    user_id
INTO @merchant_user_id FROM
    merchant
WHERE
    merchant_id = @merchant_id;
    
if(@profile_id>0)then
	select business_email,company_name,company_name into 
   @merchant_email, @display_name , @company_name from merchant_billing_profile where id=@profile_id;
else
	select business_email,company_name,company_name into 
   @merchant_email, @display_name , @company_name from merchant_billing_profile where merchant_id = @merchant_id and is_default=1;
end if;
    
SELECT 
    `logo`
INTO @merchant_logo FROM
    merchant_landing
WHERE
    merchant_id = @merchant_id;

if(@patron_name='')then
SET @patron_name='Guest patron';
end if;



SET @main_company_name='';

  if(@franchise_id>0)then
SET @merchant_email_id=@merchant_email;
SET @main_company_name=@company_name;
SELECT 
    franchise_name, email_id, mobile
INTO @company_name , @merchant_email , @merchant_mobile_no FROM
    franchise
WHERE
    franchise_id = @franchise_id;
 SET @display_name=@company_name;
 
   if(@merchant_email='')then
 SET @merchant_email=@merchant_email_id;
 end if;
 end if;

SELECT 
    @success AS 'success',
    @image AS 'image',
    @invoice_number AS 'invoice_number',
    @merchant_logo AS 'merchant_logo',
    @company_name AS 'company_name',
    @main_company_name AS 'main_company_name',
    @display_name AS 'display_name',
    _payment_transaction_id AS 'transaction_id',
    @patron_email AS 'patron_email',
    @patron_mobile AS 'patron_mobile',
    @patron_name AS 'patron_name',
    @transaction_date AS 'date',
    @update_on AS 'update_on',
    @transaction_date AS 'create_date',
    @bank_transaction_no AS 'transaction_no',
    @amount AS 'amount',
    @cheque_no AS 'cheque_no',
    @cheque_status AS 'cheque_status',
    @cash_paid_to AS 'cash_paid_to',
    @offline_response_type AS 'type',
    @bank_name AS 'bank_name',
    @payment_mode AS 'payment_mode',
    @ref_no AS 'ref_no',
    @payment_request_type AS 'payment_request_type',
    @tax AS 'tax',
    @discount AS 'discount',
    @merchant_user_id AS 'merchant_user_id',
    @deduct_amount AS 'deduct_amount',
    @deduct_text AS 'deduct_text',
    @is_availed AS 'is_availed',
    @quantity AS 'quantity',
    @narrative AS 'narrative',
    @customer_code AS 'customer_code',
    @customer_id AS 'customer_id',
    @merchant_email AS 'merchant_email',
    @payment_request_id AS 'payment_request_id',
    @estimate_req_id as 'estimate_req_id',
    @event_name AS 'event_name',
    @unit_type AS 'unit_type',
    @type AS 'type',
    @customer_id as 'customer_id',
    @xway_type as 'xway_type',
    @quantity AS 'quantity';

END
