CREATE DEFINER=`root`@`localhost` PROCEDURE `save_xwaytransaction`(_pg_id int,_merchant_id varchar(10), _referrer_url varchar(255),_account_id varchar(100), _secure_hash varchar(255), _return_url varchar(255), _reference_no varchar(45), _amount decimal(11,2)
,_surcharge decimal(11,2),_absolute_cost decimal(11,2),_description varchar(255),_name varchar(108),_address varchar(255),_city varchar(32),_state varchar(32),_postal_code varchar(10)
,_phone varchar(20),_email varchar(108),_udf1 varchar(250),_udf2 varchar(250),_udf3 varchar(250),_udf4 varchar(250),_udf5 varchar(250),_mdd varchar(250),_customer_code varchar(45),_franchise_id INT,_vendor_id INT,_currency varchar(10),_is_random_id INT,_type INT,_webhook_id INT,_discount decimal(11,2))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @xtransaction_id='';
 SET @description =_description;
 SET @name = _name;
 SET @address=_address;
 SET @city =_city;
 SET @state =_state;
 SET @postal_code =_postal_code;
 SET @phone =_phone;
 SET @email=_email;

select logging_status into @logging_status from xway_merchant_detail where merchant_id=_merchant_id limit 1;

if(@logging_status=2)then
 SET @name = '';
 SET @address='';
 SET @city ='';
 SET @state ='';
 SET @postal_code ='';
 SET @country ='';
 SET @phone ='';
 SET @email='';
elseif(@logging_status=3)then
 SET @description ='';
 SET @name = '';
 SET @address='';
 SET @city ='';
 SET @state ='';
 SET @postal_code ='';
 SET @phone ='';
 SET @email='';
end if;

if(_is_random_id=1)then
select generate_random_id('xway') into @xtransaction_id;
else
select generate_sequence('Xway_transaction_id') into @xtransaction_id;
end if;

INSERT INTO `xway_transaction`(`xway_transaction_id`,`merchant_id`,`referrer_url`,`account_id`,`customer_code`,`secure_hash`,`return_url`,`reference_no`,`amount`,`surcharge_amount`,`absolute_cost`,`description`,`name`,`address`,`city`,`state`,`postal_code`,
    `phone`,`email`,`udf1`,`udf2`,`udf3`,`udf4`,`udf5`,`mdd`,`pg_id`,`franchise_id`,`vendor_id`,`currency`,`type`,`webhook_id`,`discount`,`created_date`,`last_update_date`)
    values(@xtransaction_id,_merchant_id , _referrer_url ,_account_id ,_customer_code, _secure_hash , _return_url , _reference_no , _amount,_surcharge,_absolute_cost,@description ,@name ,@address ,@city ,@state ,@postal_code ,@phone ,@email
    ,_udf1 ,_udf2,_udf3 ,_udf4,_udf5 ,_mdd,_pg_id,_franchise_id,_vendor_id,_currency,_type,_webhook_id,_discount ,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

SET @franchise_name='';
if(_franchise_id>0)then
select franchise_name into @franchise_name from franchise where franchise_id=_franchise_id;
end if;

select company_name ,merchant_id into @company_name,@company_merchant_id from merchant where merchant_id=_merchant_id;
select @xtransaction_id as 'xtransaction_id',@franchise_name as 'franchise_name',@company_name,@company_merchant_id;

commit;
END
