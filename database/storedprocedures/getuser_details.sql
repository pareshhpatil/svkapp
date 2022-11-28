CREATE DEFINER=`root`@`localhost` PROCEDURE `getuser_details`(_customer_id varchar(10), _paymentrequest_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 show errors;
		BEGIN
		END; 
SET @payment_req=_paymentrequest_id;
set @user_id='';
set @company_merchant_id='';
SET @swipez_total=0;
SET @patron_email='';
SET @merchant_address2='';
SET @customer_id=_customer_id;

select user_id,merchant_id,payment_request_type,paid_amount,narrative,absolute_cost,late_payment_fee,due_date,swipez_total into @user_id,@company_merchant_id,@payment_request_type,@paid_amount,@narrative,@absolute_cost,@late_fee,@due_date,@swipez_total from payment_request where payment_request_id=@payment_req and customer_id=_customer_id 
and payment_request_status in (0,4,5,7);

if (@user_id <> '') then
	select  email_id,first_name,last_name,mobile_no into @merchant_email,@merchant_first_name,@merchant_last_name,@merchant_mobile_no from user where user_id=@user_id;
	select company_name,address,city,zipcode,`state` into @company_name,@merchant_address1,@merchant_city,@merchant_zipcode,
	@merchant_state from merchant_billing_profile where merchant_id=@company_merchant_id and is_default=1;

	SET @display_name=@company_name;

	select customer_code, first_name,last_name,address,address2,city,zipcode,state,email,mobile into @customer_code,@patron_first_name,@patron_last_name,
	@patron_address1,@patron_address2,@patron_city,@patron_zipcode,@patron_state,@patron_email,@patron_mobile_no from customer where customer_id=@customer_id;

	SET @absolute_cost= @absolute_cost - @paid_amount;

	 if(DATE_FORMAT(NOW(),'%Y-%m-%d') > @due_date) then
	 SET @absolute_cost= @absolute_cost + @late_fee;
	 end if;

	set @message='success';
	select @message,@merchant_email,@merchant_first_name,@merchant_last_name,@merchant_mobile_no,@merchant_address1,@merchant_address2,@merchant_city,@merchant_zipcode,
	@merchant_state,@patron_email,@patron_first_name,@patron_last_name,@patron_mobile_no,@patron_address1,@patron_address2,@patron_city,@patron_zipcode,
	@patron_state,@narrative,@absolute_cost,@swipez_total,@user_id,@company_name,@company_merchant_id,@message,@payment_request_type;
end if ;

END
