CREATE DEFINER=`root`@`localhost` PROCEDURE `merchantProfileUpdate`(_user_id char(10),_merchant_id char(10),_fname varchar(50),
_lname varchar(50),_mobile varchar(15),
_address varchar(250),_city varchar(45),_state varchar(45),_country varchar(45),
_zip varchar(11),_type int,_industry_type int,_company varchar(100),_company_registration_number varchar(20),_gst_number varchar(45),_cin_no varchar(45),_pan varchar(12),_tan varchar(45),_current_address varchar(250),
_current_city varchar(45),_current_state varchar(45),_current_country varchar(45),_current_zip varchar(11),
_business_email varchar(254),_country_code varchar(6),_business_contact varchar(45))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
		END; 
START TRANSACTION;

update user set first_name=_fname,last_name=_lname,mobile_no=_mobile where user_id=_user_id;

update merchant_billing_profile set reg_address=_address,reg_city=_city,reg_state=_state,reg_country=_country,reg_zipcode=_zip,
`address` = _current_address, `city` = _current_city,`zipcode` = _current_zip,
`state` = _current_state,`country` = _current_country,`business_email` = _business_email,
`business_contact` = _business_contact,`gst_number`=_gst_number,`cin_no` = _cin_no,`pan` = _pan,`tan` = _tan,
`last_update_by` = _user_id WHERE merchant_id=_merchant_id and is_default=1;

UPDATE `merchant` SET `entity_type` = _type,`industry_type` = _industry_type,`last_update_by` = _user_id WHERE merchant_id=_merchant_id;

commit;
SET @message='success';
select @message as 'message';


END
