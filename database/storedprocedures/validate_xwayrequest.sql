CREATE DEFINER=`root`@`localhost` PROCEDURE `validate_xwayrequest`(_mode varchar(4),_merchant_id varchar(10), _amount decimal(11,2),_reference_no varchar(50))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
		END; 
SET @Error_message='';
SET @merchant_id='';

select `merchant_id`,`min_transaction`,`max_transaction`,`xway_enable` into @merchant_id, @min_transaction,@max_transaction,@xway_enable from merchant_setting where merchant_id=_merchant_id and xway_enable=1;

SET @absolute_cost=_amount;
SET @xway_transaction_id=0;


if(_merchant_id!=@merchant_id) then
if(@Error_message!='')then
SET @Error_message = concat(@Error_message,',');
end if;
SET @Error_message = concat(@Error_message,'Invalid Merchant id');
else
select count(`xway_transaction_id`) into @xway_transaction_id from xway_transaction where merchant_id = _merchant_id and reference_no=_reference_no;
end if;

if(@min_transaction > @absolute_cost and @max_transaction < @absolute_cost) then
if(@Error_message!='')then
SET @Error_message = concat(@Error_message,',');
end if;
SET @Error_message = concat(@Error_message,'Invalid Amount');
end if;

if(@xway_transaction_id>0) then
if(@Error_message!='')then
SET @Error_message = concat(@Error_message,',');
end if;
SET @Error_message = concat(@Error_message,'Duplicate reference no');
end if;


if(@Error_message!='')then
SET @message='failed';
else
SET @message='success';
end if;

select @Error_message as 'error',@absolute_cost as 'absolute_cost',@message as 'message';

END
