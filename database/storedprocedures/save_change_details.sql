CREATE DEFINER=`root`@`localhost` PROCEDURE `save_change_details`(_user_id varchar(10),_source INT,_source_id varchar(10),_status INT,_customer_id INT,_fname varchar(100),_lname varchar(100),_email varchar(250),_mobile varchar(20),_address varchar(250),_city varchar(45),_state varchar(45),_zip varchar(20))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
BEGIN
show errors;
ROLLBACK;
END; 
SET @customer_id=_customer_id;
SET @source_type=_source;
SET @source_id=_source_id;
SET @user_id =_user_id;
SET @status=_status;
SET @change_det_id=0;

SET @fname=TRIM(LOWER(_fname));
SET @lname=TRIM(LOWER(_lname));
SET @email=TRIM(LOWER(_email));
SET @mobile=TRIM(LOWER(_mobile));
SET @address=TRIM(LOWER(_address));
SET @city=TRIM(LOWER(_city));
SET @state=TRIM(LOWER(_state));
SET @zip=TRIM(LOWER(_zip));

select merchant_id,first_name,last_name,address,city,state,zipcode,email,mobile into @merchant_id,@ex_first_name,@ex_last_name,@ex_address,@ex_city,@ex_state,@ex_zipcode,@ex_email,@ex_mobile from customer
 where customer_id=@customer_id;
 

SET @exfirst_name=TRIM(LOWER(@ex_first_name));
SET @exlast_name=TRIM(LOWER(@ex_last_name));
SET @exemail=TRIM(LOWER(@ex_email));
SET @exmobile=TRIM(LOWER(@ex_mobile));
SET @exaddress=TRIM(LOWER(@ex_address));
SET @excity=TRIM(LOWER(@ex_city));
SET @exstate=TRIM(LOWER(@ex_state));
SET @exzipcode=TRIM(LOWER(@ex_zipcode));


if(@exfirst_name<>@fname OR @exlast_name<>@lname OR @exemail<>@email OR @exmobile<>@mobile OR @exaddress<>@address 
OR @excity<>@city OR @exstate<>@state OR @exzipcode<>@zip) then

INSERT INTO `customer_data_change`(`customer_id`,`merchant_id`,`source_id`,
`source_type`,`status`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@customer_id,@merchant_id,@user_id,@source_type,@status,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

select LAST_INSERT_ID() into @change_id;

if(@exfirst_name<>@fname)then
select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=1 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_first_name,`changed_value`=_fname where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,1,@customer_id,@customer_id,@ex_first_name,_fname,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exlast_name<>@lname)then

select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=2 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_last_name,`changed_value`=_lname where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,2,@customer_id,@customer_id,@ex_last_name,_lname,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exemail<>@email)then

select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=3 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_email,`changed_value`=_email where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,3,@customer_id,@customer_id,@ex_email,_email,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exmobile<>@mobile)then

select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=4 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_mobile,`changed_value`=_mobile where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,4,@customer_id,@customer_id,@ex_mobile,_mobile,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exaddress<>@address and @address<>'')then
	select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=5 and `status`=0 and `customer_id`=@customer_id;
	if(@change_det_id>0)then
		update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_address,`changed_value`=_address where `change_detail_id`=@change_det_id;
	else
		INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
		`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
		VALUES(@change_id,5,@customer_id,@customer_id,@ex_address,_address,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
	end if;
end if;

SET @change_det_id=0;

if(@excity<>@city and @city<>'')then
select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=6 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_city,`changed_value`=_city where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,6,@customer_id,@customer_id,@ex_city,_city,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exstate<>@state and @state<>'')then
select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=7 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_state,`changed_value`=_state where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,7,@customer_id,@customer_id,@ex_state,_state,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;

SET @change_det_id=0;

if(@exzipcode<>@zip and @zip<>'')then
select change_detail_id into @change_det_id from customer_data_change_detail where `column_type`=8 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `change_id`=@change_id,`current_value`=@ex_zipcode,`changed_value`=_zip where `change_detail_id`=@change_det_id;
else
INSERT INTO `customer_data_change_detail`(`change_id`,`column_type`,`column_value_id`,`customer_id`,`current_value`,
`changed_value`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,8,@customer_id,@customer_id,@ex_zipcode,_zip,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());
end if;

end if;




INSERT INTO `pending_change`(`change_id`,`source_id`,`source_type`,`status`,`created_by`,
`created_date`,`last_update_by`,`last_update_date`)
VALUES(@change_id,@source_id,@source_type,@status,@user_id,CURRENT_TIMESTAMP(),@user_id,CURRENT_TIMESTAMP());

end if;


END
