CREATE DEFINER=`root`@`localhost` PROCEDURE `GrouploginCheck`(_email varchar(254),_password varchar(60),_group_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @user_id='';
SET @status=0;
SET @name='';
SET @firstName='';
SET @email_id='';
SET @loginattempt=0;
SET @merchant_type=0;
SET @patron_has_payreq=0;
SET @merchant_status=0;
SET @merchant_id='';
SET @bulk_upload_limit='';
SET @display_url='';
SET @merchant_domain_name='';
SET @company_name='';
SET @view_roles='';
SET @update_roles='';
SET @sub_user_id='';
SET @directpay_enable=0;
SET @franchise_id=0;
SET @ticket_enable=0;

SELECT 
    u.user_id,
    u.user_status,
    CONCAT(first_name, ' ', last_name) AS name,
    first_name,
    email_id,
    franchise_id,
    group_id
INTO @user_id , @status , @name , @firstName , @email_id ,@franchise_id, @group_id FROM
    `user` u
        INNER JOIN
    user_cred c ON u.user_id = c.user_id
WHERE
    u.email_id = _email
        AND `user_status` IN (2 , 12, 13, 14, 15, 16, 20, 19)
        AND c.password = _password
        AND u.group_id = _group_id;
            
if(@status=2 or (@status>11 and @status<17) or @status=20)then
	update user_cred set login_attempt=0, last_login=CURRENT_TIMESTAMP() where user_id=@user_id;
	SET @isValid=1;
	if(@status=2) then
		select patron_id into @patron_id from payment_request where patron_id=@user_id limit 1;
			if(@patron_id!='') then
				SET @patron_has_payreq=1;
			end if;
	else
		if(@status=20)then
        SET @sub_user_id=@user_id;
SELECT 
    view_controllers,update_controllers
INTO @view_roles,@update_roles FROM
    roles
        INNER JOIN
    user_privileges ON user_privileges.role_id = roles.role_id
WHERE
    user_privileges.user_id = @user_id
LIMIT 1;
			SELECT 
    user_id
INTO @user_id FROM
    user
WHERE
    group_id = @group_id
        AND user_group_type = 1;
		end if;
		SELECT 
    merchant_type,
    company_name,
    merchant_domain,
    display_url,
    merchant_status,
    merchant_id
INTO @merchant_type , @company_name , @merchant_domain , @display_url , @merchant_status , @merchant_id FROM
    merchant
WHERE
    user_id = @user_id
LIMIT 1;
SELECT 
    xway_enable, directpay_enable,ticket_enable
INTO @xway_enable , @directpay_enable,@ticket_enable FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
		SELECT 
    config_value
INTO @merchant_domain_name FROM
    config
WHERE
    config_key = @merchant_domain
        AND config_type = 'merchant_domain';
	end if;
	else
		select login_attempt,user.user_id into @attempt,@userId from user_cred inner join user on user.user_id=user_cred.user_id where user.email_id=_email and group_id=_group_id;
		UPDATE user_cred 
SET 
    login_attempt = login_attempt + 1
WHERE
    user_id = @userId;
		SET @loginattempt=@attempt+1;
		if(@loginattempt>9) then
			update user set prev_status=`user_status`,`user_status`=18 where user_id=@userId and `user_status`<>18;
		SET @status=18;
		end if;
		SET @isValid=0;
end if;

if(@directpay_enable=1)then
SET @xway_enable=1;
end if;

SELECT 
    @merchant_domain_name AS 'merchant_domain',
    @company_name AS 'company_name',
    @display_url AS 'display_url',
    @user_id AS 'user_id',
    @status AS 'status',
    @name AS 'name',
    @firstName AS 'firstName',
    @email_id AS 'email_id',
    @loginattempt AS 'loginattempt',
    @isValid AS 'isValid',
    @merchant_id AS 'merchant_id',
    @merchant_type AS 'merchant_type',
    @merchant_status AS 'merchant_status',
    @patron_has_payreq AS 'patron_has_payreq',
	@view_roles AS 'view_roles',
    @update_roles AS 'update_roles',
    @sub_user_id AS 'sub_user_id',
    @xway_enable AS 'xway_enable',
    @franchise_id as 'franchise_id',
	@ticket_enable as 'ticket_enable';

commit;

END
