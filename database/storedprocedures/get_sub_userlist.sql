CREATE DEFINER=`root`@`localhost` PROCEDURE `get_sub_userlist`(_userid varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
		END;

Drop TEMPORARY  TABLE  IF EXISTS temp_sub_userlist;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_sub_userlist (
    `user_id` varchar(10) NOT NULL ,
    `name` varchar(100) NOT NULL,
    `email` varchar(250) NOT NULL,
    `user_status` varchar(10) NOT NULL,
    `role_id` int not null default 0,
    `role` varchar(50) Null,
    `config_value` varchar(100) NULL,
    PRIMARY KEY (`user_id`)) ENGINE=MEMORY;


select group_id into @group_id from user where user_id=_userid and user_group_type=1;

insert temp_sub_userlist(`user_id`,`name`,`email`,`user_status`) select user_id,concat(first_name,' ',last_name),email_id,user_status from user where group_id=@group_id and user_group_type=2  and user_status in (19,20);

update temp_sub_userlist t , user_privileges p set t.role_id = p.role_id where t.user_id=p.user_id;

update temp_sub_userlist t , roles r set t.role = r.name where t.role_id=r.role_id;
update temp_sub_userlist t , config r set t.config_value = r.config_value where t.user_status=r.config_key and r.config_type='user_status';


select * from temp_sub_userlist;


END
