CREATE DEFINER=`root`@`localhost` PROCEDURE `supplier_save`(_user_id varchar(10),_email1 varchar(254),_email2 varchar(254),
_mob_country_code1 varchar(6),_mobile1 VARCHAR(13),_mob_country_code2 varchar(6),_mobile2 VARCHAR(13),
_industrytype int(4),_supplier_company_name varchar(100),_contact_person_name varchar(100) ,_contact_person_name2 varchar(100) , _company_website varchar(70))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        set @message = 'failed';
			ROLLBACK;
		END; 
START TRANSACTION;


select merchant_id into @merchant_id from merchant where user_id=_user_id;

INSERT into `supplier` (`user_id`,`merchant_id`,`email_id1`,`email_id2`,`mob_country_code1`,`mobile1`,
`mob_country_code2`,`mobile2`,`industry_type`,`supplier_company_name`,`contact_person_name`,`contact_person_name2`,`company_website`,`created_by`, 
`created_date`, `last_updated_by`, `last_updated_date`) VALUES (_user_id,@merchant_id,_email1,_email2,_mob_country_code1,_mobile1,_mob_country_code2, _mobile2,_industrytype,
_supplier_company_name,_contact_person_name,_contact_person_name2,_company_website,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

commit;
set @message  = 'success';
select @message as 'message';
END
