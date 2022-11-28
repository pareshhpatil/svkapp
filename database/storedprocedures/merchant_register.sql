CREATE DEFINER=`root`@`localhost` PROCEDURE `merchant_register`(_email varchar(254),_first_name varchar(100),_last_name varchar(100),_mobile_code varchar(5),_mobile varchar(13),_pass varchar(60),_company_name varchar(100),_plan_id INT,_campaign_id INT,_registered_from INT,_service_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
            
		END; 
START TRANSACTION;

SELECT GENERATE_SEQUENCE('User_id') INTO @user_id;
SELECT GENERATE_SEQUENCE('group_id') INTO @group_id;


set @get_id=0;
SET @group_usertype=1;
SET @merchant_type=2;
SET @statuss=12;
SET @merchant_status=2;

SET @package_id=2;
SET @license_package_id=0;
SET @bulk_upload_limit=250;
SET @full_name=CONCAT(_first_name,' ',_last_name);

SELECT 
    individual_invoice,    bulk_invoice,    free_sms,    merchant_role,    duration,    pg_integration,    site_builder,
    brand_keyword,    total_invoices,    coupon,    supplier
INTO @individual_invoice , @bulk_invoice , @free_sms , @merchant_role , @duration , @pg_integration , @site_builder , @brand_keyword , @total_invoices , @coupon , @supplier FROM
    package
WHERE
    package_id = @package_id;
SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);

INSERT INTO `user`(`email_id`, `user_id`,`name`, `first_name`, `last_name`,`password`,`mob_country_code`,`mobile_no`, `user_status`, `group_id`
, `user_group_type`, `user_type`,`registered_from`, `created_by`, `created_date`, `last_updated_by`, `last_updated_date`)
VALUES (_email,@user_id,@full_name,_first_name,_last_name,_pass,_mobile_code,_mobile,@statuss,@group_id,@group_usertype,0,_registered_from,@user_id,CURRENT_TIMESTAMP(),@User_id,CURRENT_TIMESTAMP());


SET @auth_id=LAST_INSERT_ID();


SELECT GENERATE_SEQUENCE('Merchant_id') INTO @merchant_id;

INSERT INTO `merchant`(`merchant_id`, `user_id`,`merchant_plan`,`merchant_status`,`entity_type`,`merchant_type`, `group_id`, `company_name`
,`package_expiry_date`,registration_campaign_id,`service_id`,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,@user_id,@package_id,@merchant_status,@typee,@merchant_type, @group_id,_company_name,@end_date,_campaign_id,_service_id,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());


INSERT INTO `merchant_billing_profile` (`merchant_id`,`profile_name`,`company_name`,`business_email`,`business_contact`,`is_default`,`created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES (@merchant_id,'Default profile',_company_name,_email,_mobile,1,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());


INSERT INTO `preferences`(`user_id`, `created_date`, `last_update_date`) values(@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

if(_campaign_id=30)then
SET @bulk_upload_limit=2000;
end if;

INSERT INTO `merchant_setting`(`merchant_id`,`sms_gateway_type`,`sms_gateway`,`min_transaction`,`max_transaction`,
`invoice_bulk_upload_limit`,`customer_bulk_upload_limit`,`xway_enable`,`statement_enable`,`show_ad`,`promotion_id`,
`auto_approve`,`customer_auto_generate`,`prefix`,`created_by`,`last_update_by`,`last_update_date`,`created_date`)
VALUES(@merchant_id,2,1,20.00,100000.00,@bulk_upload_limit,@bulk_upload_limit,0,0,0,0,1,0,'C',@user_id,@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());


INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,
`bulk_invoice`,`free_sms`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`merchant_role`,`coupon`,`supplier`,`start_date`,`end_date`,`license_key_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@package_id,'',0,@individual_invoice,@bulk_invoice,@free_sms,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@merchant_role,@coupon,@supplier,NOW(),@end_date,0,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);

   
 
if(_campaign_id<>30)then     
	
	 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
	, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
	VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
	'Check out our video tutorials and learn at your own pace.',
	 'Check out our video tutorials and learn at your own pace.', 
	 'https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured', 'Videos', '0', '1', now(), now(), now());
	  
	 INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
	, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
	VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
	'Chat with us and our representative will guide you to setup your account.',
	 'Chat with us and our representative will guide you to setup your account.', 
	 'https://tawk.to/chat/574eb7843c365f2e5bd7c68f/default/?$_tawk_popout=true', 'Chat with us', '0', '1', now(), now(), now());
     
     INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
	, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
	VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
	'Check out our new help center. Get the most out of your Swipez account!',
	 'Check out our new help center. Get the most out of your Swipez account!', 
	 'http://helpdesk.swipez.in/help', 'Check it out', '0', '1', now(), now(), now());
 end if;
 
 if(_service_id=8)then
  INSERT INTO `merchant_notification` ( `merchant_id`, `type`, `label_class`, `label_icon`, `message`, `description`, `link`, `link_text`, `is_shown`
, `is_active`, `notification_sent`, `created_date`, `last_update_date`) 
VALUES (@merchant_id, '0', 'label-success', 'fa-bullhorn', 
'Watch how cable operators organize their payments collections using Swipez',
 'Watch how cable operators organize their payments collections using Swipez', 
 '#cable" data-toggle="modal" id="cablepopup', 'Videos', '0', '1', now(), now(), now());
 end if;
 

INSERT INTO `customer_sequence` (`merchant_id`, `val`) VALUES (@merchant_id, '0');
INSERT INTO `merchant_auto_invoice_number`(`merchant_id`,`prefix`,`val`,`type`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,'EST/','0',2,@merchant_id,CURRENT_TIMESTAMP(),@merchant_id);
INSERT INTO `notification_count`(`merchant_id`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@user_id,CURRENT_TIMESTAMP(),@user_id);

if(_service_id>0)then
	if(_service_id=15)then
			INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@merchant_id,@user_id,2,1,@user_id,CURRENT_TIMESTAMP(),@user_id);
	end if;
INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,_service_id,1,@user_id,CURRENT_TIMESTAMP(),@user_id);
else
INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,2,1,@user_id,CURRENT_TIMESTAMP(),@user_id);
end if;

INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(@merchant_id,@user_id,1,1,@user_id,CURRENT_TIMESTAMP(),@user_id);

if(_registered_from=0)then
call merchant_dummy_data(@merchant_id,@user_id);
end if;
SET @message='success';

SELECT @message AS 'Message',_email AS 'email_id', @merchant_id AS 'merchant_id', @user_id AS 'user_id' , @auth_id as 'id';

commit;




END