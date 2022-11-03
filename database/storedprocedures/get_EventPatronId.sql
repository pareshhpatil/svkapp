CREATE DEFINER=`root`@`localhost` PROCEDURE `get_EventPatronId`(_first_name varchar(100),_last_name varchar(100),_email varchar(250),_mobile varchar(13),_city varchar(45),_address varchar(500),_state varchar(45),_zipcode varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 

		BEGIN
			ROLLBACK;
            
		END; 
START TRANSACTION;

SET @pid='';

SELECT  user.user_id into @pid FROM `user` where `email_id`= _email limit 1 ;

if(@pid='') then
select generate_sequence('Patron_id') into @pid;
INSERT INTO `non_registered_patron` (`patron_id`, `first_name`,`last_name`, `email_id`, `mobile_no`,`city`,`address1`,`state`,`zipcode`,`is_registered`, `created_by`, `created_date`, `last_update_by`, `last_update_date`)
 VALUES (@pid,_first_name,_last_name,_email,_mobile,_city,_address,_state,_zipcode,0,@pid,CURRENT_TIMESTAMP() ,@pid,CURRENT_TIMESTAMP());
end if;
commit;

select @pid as 'patron_id';
END
