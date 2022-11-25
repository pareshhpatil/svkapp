CREATE DEFINER=`root`@`localhost` PROCEDURE `get_transaction_status`(_merchant_id varchar(10),_user_id varchar(10),_transaction_id varchar(20),_type varchar(20))
BEGIN 
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
END; 

SET @settlement_date ='';
SET @name='';
SET @udf1='';
SET @udf2='';
SET @udf3='';
SET @udf4='';
SET @udf5='';
if(_type=1) then
    select xway_transaction_id,reference_no,xway_transaction_status,amount,name,email,phone,address,city,state,postal_code,udf1,udf2,udf3,udf4,udf5,created_date,payment_Mode into 
    @transaction_id,@reference_no,@status,@amount,@name,@email,@phone,@address,@city,@state,@postal_code,@udf1,@udf2,@udf3,@udf4,@udf5,@date,@payment_Mode from
     xway_transaction where reference_no=_transaction_id and merchant_id=_merchant_id;
elseif(_type=2) then
    SET @transaction_id =_transaction_id;
    select xway_transaction_id,reference_no,payment_mode,pg_ref_no1,xway_transaction_status,amount,name,email,phone,address,city,state,postal_code,udf1,udf2,udf3,udf4,udf5,created_date,payment_Mode into 
    @transaction_id,@reference_no,@payment_Mode,@ref_no,@status,@amount,@name,@email,@phone,@address,@city,@state,@postal_code,@udf1,@udf2,@udf3,@udf4,@udf5,@date,@payment_Mode from
     xway_transaction where xway_transaction_id=_transaction_id and merchant_id=_merchant_id;
else
    
    select payment_request_status,customer_id into @payment_request_status,@customer_id  from payment_request where payment_request_id=_transaction_id; 

    if(@payment_request_status=1)then
        select pay_transaction_id,pg_ref_no,payment_transaction_status,amount,created_date,payment_mode into 
	@transaction_id,@reference_no,@status,@amount,@date,@payment_Mode from
	 payment_transaction where pay_transaction_id=@transaction_id and merchant_id=_merchant_id;
    elseif(@payment_request_status=2)then
        select offline_response_id,bank_transaction_no,offline_response_type,bank_name,cheque_no,cash_paid_to,1,amount,customer_id,patron_user_id,created_date into 
	@transaction_id,@bank_transaction,@offline_type,@bank_name,@cheque_no,@cash_paid_to,@status,@amount,@customer_id,@patron_user_id,@date from
	 offline_response where offline_response_id=@transaction_id and merchant_id=_merchant_id;
         
        if(@offline_type=1)then
           SET @payment_mode='NEFT';
           SET @reference_no=@bank_transaction;
        elseif(@offline_type=2)then
           SET @payment_mode='Cheque';
           SET @reference_no=@cheque_no;
        elseif(@offline_type=3)then
           SET @payment_mode='Cash';
           SET @reference_no=@cash_paid_to;
        elseif(@offline_type=4)then
           SET @payment_mode='Online';
           SET @reference_no=@bank_transaction;
        end if;
    end if;
    
    select concat(first_name,' ',last_name),address,city,state,zipcode,email,mobile into @name,@address,@city,@state,@postal_code,@email,@phone from customer where customer_id=@customer_id and merchant_id=_merchant_id ;
    
end if;

if(@status=0)then
SET @status='initiated';
elseif(@status=1) then
SET @status='success';
elseif(@status=4) then
SET @status='failed';
end if;

select @transaction_id as 'transaction_id',@reference_no as 'reference_no',@status as 'status',@date as 'date',@ref_no as 'bank_ref_no',@payment_Mode as 'mode',@amount as 'amount',@name as 'billing_name',@email as 'billing_email',@phone as 'billing_mobile',@address as 'billing_address'
,@city as 'billing_city',@state as 'billing_state',@postal_code as 'billing_postal_code',@udf1 as 'udf1',@udf2 as 'udf2',@udf3 as 'udf3',@udf4 as 'udf4',@udf5 as 'udf5';

END
