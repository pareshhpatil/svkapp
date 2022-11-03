CREATE DEFINER=`root`@`localhost` PROCEDURE `save_sub_merchant`(_user_id varchar(10),_email varchar(254),_fname varchar(50),_lname varchar(50),_mob_country_code varchar(6),_mobile bigint(13),_pass varchar(100),_role INT,_franchise_id INT,_group varchar(100))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
		ROLLBACK;
		END; 
START TRANSACTION;

select generate_sequence('User_id') into @user_id;

select group_id into @group_id from user where user_id=_user_id;

INSERT INTO `user`(`email_id`, `user_id`,`password`,`name`, `first_name`, `last_name`, `user_status`, `group_id`
, `user_group_type`, `user_type`,`franchise_id`,`customer_group`,mob_country_code,`mobile_no`, `created_by`, `created_date`, `last_updated_by`, `last_updated_date`)
VALUES (_email,@user_id,_pass,concat(_fname,' ',_lname),_fname,_lname,19,@group_id,2,0,_franchise_id,_group,_mob_country_code,_mobile,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

INSERT INTO `user_privileges`(`user_id`,`role_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES
(@user_id,_role,_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

INSERT INTO `preferences`(`user_id`, `created_date`, `last_update_date`) values(@user_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());

select  CONCAT(user_id, created_date) into @username from user where user_id=@user_id;

commit;
SET @message = 'success';
select @message as 'message' , @user_id as 'user_id',@username as 'usertimestamp';

END
