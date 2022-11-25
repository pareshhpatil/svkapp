CREATE DEFINER=`root`@`localhost` PROCEDURE `patron_register_delete`(_merchant_id varchar(10),_user_id varchar(10),_first_name varchar(100),_last_name varchar(100),_email varchar(254),_mobile_code varchar(5),_mobile varchar(15),_password varchar(100),_customer_id int,_payment_request_id varchar(10),_login_type INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
            
		END; 
START TRANSACTION;

SELECT GENERATE_SEQUENCE('group_id') INTO @group_id;

set @get_id=0;
SET @group_usertype=1;
SET @merchant_type=2;
SET @statuss=11;
SET @merchant_status=2;
SET @package_id=2;
SET @license_package_id=0;
SET @user_id=_user_id;
SET @merchant_id='';
SET @company_name='';

if(_payment_request_id<>'')then
SET @source_type=1;
SET @status=0;

select customer_id,address,city,state,zipcode into @customer_id,@address,@city,@state,@zipcode from payment_request where payment_request_id=_payment_request_id;

update customer set customer_status=1,last_update_by=@user_id where customer_id=@customer_id;

call `save_change_details`(@user_id,@source_type,@customer_id,@status,@customer_id,_first_name,_last_name,_email,_mobile,@address,@city,@state,@zipcode);

end if;


if(@user_id='')then
if(_customer_id>0)then
select first_name,last_name into @first_name,@last_name from customer where customer_id=_customer_id;
else
SET @first_name=_first_name;
SET @last_name=_last_name;
end if;

SELECT GENERATE_SEQUENCE('User_id') INTO @user_id;

Drop TEMPORARY  TABLE  IF EXISTS temp_customer_email;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_customer_email (
    `customer_id` INT NOT NULL ,
    PRIMARY KEY (`customer_id`)) ENGINE=MEMORY;

insert into temp_customer_email(customer_id)
select customer_id from customer where email=_email and _email<>'';

update customer c,temp_customer_email t set customer_status=2 ,user_id=@user_id where c.customer_id=t.customer_id;

INSERT INTO `user`(`email_id`, `user_id`,`password`, `first_name`, `last_name`,`mob_country_code`,`mobile_no`, `user_status`, `group_id`
, `user_group_type`, `user_type`,`login_type`, `created_by`, `created_date`, `last_updated_by`
, `last_updated_date`)
VALUES (_email,@user_id,_password,@first_name,@last_name,_mobile_code,_mobile,@statuss,@group_id,@group_usertype,0,_login_type,@user_id,CURRENT_TIMESTAMP(),@User_id,CURRENT_TIMESTAMP());


INSERT INTO `preferences`(`user_id`, `created_date`, `last_update_date`) values(@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

          
else

update user set user_status=15 ,user_group_type=@group_usertype,group_id=@group_id where user_id=@user_id;
end if;

if(_login_type=1)then
SELECT GENERATE_SEQUENCE('Merchant_id') INTO @merchant_id;

SELECT 
    individual_invoice,    bulk_invoice,    free_sms,    merchant_role,    duration,    pg_integration,    site_builder,
    brand_keyword,    total_invoices,    coupon,    supplier
INTO @individual_invoice , @bulk_invoice , @free_sms , @merchant_role , @duration , @pg_integration , @site_builder , @brand_keyword , @total_invoices , @coupon , @supplier FROM
    package
WHERE
    package_id = @package_id;
SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);

INSERT INTO `merchant`(`merchant_id`, `user_id`,`merchant_plan`,`type`,`merchant_status`,`entity_type`,`merchant_type`, `group_id`, `company_name`
,`package_expiry_date`,registration_campaign_id,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,@user_id,@package_id,2,@merchant_status,@typee,@merchant_type, @group_id,'',@end_date,0,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

INSERT INTO `merchant_billing_profile` (`merchant_id`,`profile_name`,`company_name`,`business_email`,`business_contact`,`is_default`,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,'Default profile','',_email,_mobile,1,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());


INSERT INTO `merchant_setting`(`merchant_id`,`sms_gateway_type`,`sms_gateway`,`min_transaction`,`max_transaction`,
`invoice_bulk_upload_limit`,`customer_bulk_upload_limit`,`xway_enable`,`statement_enable`,`show_ad`,`promotion_id`,
`auto_approve`,`customer_auto_generate`,`prefix`,`created_by`,`last_update_by`,`last_update_date`,`created_date`)
VALUES(@merchant_id,2,1,20.00,2000.00,250,250,0,0,0,0,1,0,'C',@user_id,@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,
`bulk_invoice`,`free_sms`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`merchant_role`,`coupon`,`supplier`,`start_date`,`end_date`,`license_key_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@package_id,'',0,@individual_invoice,@bulk_invoice,@free_sms,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@merchant_role,@coupon,@supplier,NOW(),@end_date,0,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);



 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
'Check out our video tutorials and learn at your own pace.',
 'Check out our video tutorials and learn at your own pace.', 
 'https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured', 'Videos', '0', '1', '2017-12-13', '2017-12-13 12:21:52', '2017-12-13 12:21:52');
 
 
 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
'Chat with us and our representative will guide you to setup your account.',
 'Chat with us and our representative will guide you to setup your account.', 
 'https://tawk.to/chat/574eb7843c365f2e5bd7c68f/default/?$_tawk_popout=true', 'Chat with us', '0', '1', '2017-12-13', '2017-12-13 12:21:52', '2017-12-13 12:21:52');
 
    

INSERT INTO `customer_sequence` (`merchant_id`, `val`) VALUES (@merchant_id, '0');
INSERT INTO `merchant_auto_invoice_number`(`merchant_id`,`prefix`,`val`,`type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,'EST/','0',2,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
INSERT INTO `notification_count`(`merchant_id`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@user_id,CURRENT_TIMESTAMP(),@user_id);

end if;

if(_login_type=2)then

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`merge_menu`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,1,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`merge_menu`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,9,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

else

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

end if;
select created_date into @created_date from user where user_id=@user_id;
select company_name into @company_name from merchant where merchant_id=_merchant_id;


SET @message='success';

SELECT @message AS 'Message',_email AS 'email_id', @merchant_id AS 'customer_merchant_id',@company_name as 'company_name', @user_id AS 'user_id',@merchant_id as 'merchant_id', @created_date as 'created_date';

commit;




END
