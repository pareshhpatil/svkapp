CREATE DEFINER=`root`@`localhost` PROCEDURE `get_Eventuser_details`(_userid char(10), _paymentrequest_id char(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 
SET @payment_req=_paymentrequest_id;
set @merchant_id='';
SET @patron_id=_userid;
SET @patron_email='';
SET @merchant_address2='';
SET @patron_address1='';
SET @patron_address2='';
SET @patron_city='';
SET @patron_zipcode='';
SET @patron_state='';

select user_id,merchant_id into @merchant_id,@company_merchant_id  from event_request where event_request_id=@payment_req ;

if (@merchant_id <> '') then
	select  email_id,first_name,last_name,mobile_no into @merchant_email,@merchant_first_name,@merchant_last_name,@merchant_mobile_no from user where user_id=@merchant_id;
	select address,city,zipcode,`state`,company_name into @merchant_address1,@merchant_city,@merchant_zipcode,@merchant_state,@company_name from merchant_billing_profile where merchant_id=@company_merchant_id and is_default=1;
	
    SET @display_name=@company_name;
    
	select  email_id,first_name,last_name,mobile_no into @patron_email,@patron_first_name,@patron_last_name,@patron_mobile_no from user where user_id=@patron_id;

	set @message='success';
	select @merchant_email,@merchant_first_name,@merchant_last_name,@merchant_mobile_no,@merchant_address1,@merchant_address2,@merchant_city,@merchant_zipcode,
	@merchant_state,@patron_email,@patron_first_name,@patron_last_name,@patron_mobile_no,@patron_address1,@patron_address2,@patron_city,@patron_zipcode,
	@patron_state,@patron_id,@merchant_id,@company_name,@company_merchant_id,@display_name,@message;
end if ;

END
