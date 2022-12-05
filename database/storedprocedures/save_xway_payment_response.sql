CREATE DEFINER=`root`@`localhost` PROCEDURE `save_xway_payment_response`(_transaction_id varchar(10),_payment_id varchar(40),_pg_transaction_id varchar(40),_amount decimal(11,2),_mode varchar(50),_message varchar(250),_status varchar(20),_payment_mode varchar(45))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
					ROLLBACK;
                    show errors;
		END; 
START TRANSACTION;

SET @image='';
SET @pay_transaction_id=_transaction_id;
SET @payment_id=_payment_id;
SET @transaction_id=_pg_transaction_id;
SET @message=_message;
SET @franchise_id=0;
SELECT 
    `name`,
    `amount`,
    `email`,
    `phone`,
    `address`,
    `city`,
    `state`,
    `postal_code`,
    `udf1`,
    `udf2`,
    `udf3`,
    `udf4`,
    `udf5`,
    `franchise_id`,
    `return_url`,
    `reference_no`,
    `pg_id`,
    `type`,
    merchant_id
INTO @name,@request_amount , @email , @phone , @address , @city , @state , @postal_code , @udf1 , @udf2 , @udf3 , @udf4 , @udf5 ,@franchise_id, @return_url , @reference_no,@pg_id,@xway_type , @merchant_id FROM
    xway_transaction
WHERE
    xway_transaction_id = @pay_transaction_id;

 
 if(_status='success') then
    set @status=1;
 else
    set @status=4;
end if;	

UPDATE xway_transaction 
SET 
    xway_transaction_status = @status,
    pg_ref_no1 = _payment_id,
    payment_mode=_payment_mode,
    narrative=_message,
    pg_ref_no2 = _pg_transaction_id
WHERE
    xway_transaction_id = @pay_transaction_id;
    
SET @pg_surcharge_enabled=0;
    
    if(@xway_type=1)then
		select pg_surcharge_enabled into @pg_surcharge_enabled from xway_merchant_detail where merchant_id=@merchant_id and pg_id=@pg_id limit 1;
    else
		select pg_surcharge_enabled into @pg_surcharge_enabled from merchant_fee_detail where merchant_id=@merchant_id and pg_id=@pg_id limit 1;
    end if;

	if(@pg_surcharge_enabled=1)then
		update xway_transaction set surcharge_amount=_amount-absolute_cost,`absolute_cost`=_amount where  xway_transaction_id = @pay_transaction_id;
    end if;

select 
    company_name, user_id,merchant_domain
INTO @company_name , @merchant_user_id,@merchant_domain FROM
    merchant
WHERE
    merchant_id = @merchant_id;
    
    SELECT 
    email_id,mobile_no
INTO @merchant_email_id,@merchant_mobile_no FROM
    user
WHERE
    user_id = @merchant_user_id;
    
if(@franchise_id>0)then
select franchise_name into @franchise_name from franchise where franchise_id=@franchise_id;
SET @company_name = concat(@company_name,' (',@franchise_name,')');
end if;    


SELECT 
    @merchant_id AS 'checksum',
	@merchant_domain as 'merchant_domain',
    @pay_transaction_id AS 'transaction_id',
    _pg_transaction_id AS 'bank_ref_no',
    @reference_no AS 'reference_no',
    _mode AS 'mode',
    _status AS 'status',
    _amount AS 'amount',
    CURRENT_TIMESTAMP() AS 'date',
    _message AS 'message',
    @return_url AS 'return_url',
    @merchant_email_id AS 'merchant_email',
    @merchant_mobile_no AS 'mobile_no',
    @company_name AS 'company_name',
    @name AS 'billing_name',
    @name AS 'BillingName',
    @email AS 'billing_email',
    @phone AS 'billing_mobile',
    @address AS 'billing_address',
    @city AS 'billing_city',
    @state AS 'billing_state',
    @postal_code 'billing_postal_code',
    @franchise_id as 'franchise_id',
    @udf1 AS 'udf1',
    @udf2 AS 'udf2',
    @udf3 AS 'udf3',
    @udf4 AS 'udf4',
    @udf5 AS 'udf5',
    @request_amount as 'request_amount';



commit;


END
