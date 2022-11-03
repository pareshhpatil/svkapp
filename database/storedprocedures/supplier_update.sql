CREATE DEFINER=`root`@`localhost` PROCEDURE `supplier_update`(_supplier_id int(11),_user_id varchar(10),_email1 varchar(254),
_email2 varchar(254),
_mob_country_code1 varchar(6),_mobile1 VARCHAR(13),_mob_country_code2 varchar(6),_mobile2 VARCHAR(13),
_industrytype int(4),_supplier_company_name varchar(100),_contact_person_name varchar(100) ,_contact_person_name2 varchar(100) , _company_website varchar(70))
BEGIN
BEGIN
        set @message = 'failed';
			ROLLBACK;
		END; 
START TRANSACTION;

set @supplier_id=_supplier_id;
set @user_id=_user_id;

if(@supplier_id !='' && @user_id !='') then

update `supplier` 
set `email_id1` = _email1 ,`email_id2` = _email2, `mob_country_code1` = _mob_country_code1,
    `mobile1` = _mobile1 , `mob_country_code1` = _mob_country_code2 , `mobile2` = _mobile2 , 
    `industry_type` = _industrytype ,    `supplier_company_name` =  _supplier_company_name ,
    `contact_person_name` =  _contact_person_name , `contact_person_name2` =  _contact_person_name2 ,
    `company_website` = _company_website ,`is_active` = 1 , `last_updated_by` = @user_id ,
    `last_updated_date` = CURRENT_TIMESTAMP()
where `supplier_id` = @supplier_id and `user_id` = @user_id;  

end if;



commit;
set @message  = 'success';
select @message as 'message';
END
