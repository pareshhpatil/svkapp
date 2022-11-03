CREATE DEFINER=`root`@`localhost` PROCEDURE `auto_aprove_customer_details`(_customer_id INT,_transaction_id varchar(10))
BEGIN

SET @customer_id=_customer_id;
set @change_id=0;
select change_id,pending_change_id into @change_id,@pending_change_id from pending_change where source_id=_transaction_id and status=0 order by pending_change_id desc limit 1;

if(@change_id>0)then

select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=1 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set first_name=@changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;
select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=2 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set last_name= @changed_value where customer_id=_customer_id;
end if;

SET @change_det_id=0;


select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=3 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set `email`= @changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;


select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=4 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set `mobile`= @changed_value where customer_id=_customer_id;
end if;

SET @change_det_id=0;

select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=5 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update `customer_data_change_detail` set `status`=1 where `change_detail_id`=@change_det_id;
update customer set address= @changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;

select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=6 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set city= @changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;

select  change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=7 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set state= @changed_value where customer_id=_customer_id;
end if;


SET @change_det_id=0;

select change_detail_id,changed_value into @change_det_id,@changed_value from customer_data_change_detail where `column_type`=8 and `status`=0 and `customer_id`=@customer_id;
if(@change_det_id>0)then
update customer set zipcode= @changed_value where customer_id=_customer_id;
end if;

update customer_data_change_detail set `status`= 1 where change_id=@change_id;
update pending_change set status=1 where pending_change_id=@pending_change_id;
update customer_data_change set status=1 where change_id=@change_id;

end if;

END
