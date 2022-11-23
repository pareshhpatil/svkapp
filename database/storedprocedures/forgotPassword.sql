CREATE DEFINER=`root`@`localhost` PROCEDURE `forgotPassword`(_email varchar(254),_group_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;
if(_group_id<>'') then
select count(user_id),user_id into @user_count,@user_id from user where email_id=_email and group_id=_group_id;
else 
select count(user_id),user_id into @user_count,@user_id from user where (email_id = _email or concat(mob_country_code,mobile_no)=_email);
end if;
if(@user_count>0) then
select count(email_id),id into @count,@id from forgot_password where user_id=@user_id and is_active=1;
if(@count>0) then
update forgot_password set last_update_date = CURRENT_TIMESTAMP() where id=@id;
else
INSERT INTO `forgot_password`(`email_id`,`user_id`, `create_date`, `last_update_date`)
VALUES (_email,@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());
SET @id=LAST_INSERT_ID();
end if;	
select concat(last_update_date,'',email_id) into @string from forgot_password where id=@id;
commit;
else
set @string='error';
end if;
select @string,@id;
END
