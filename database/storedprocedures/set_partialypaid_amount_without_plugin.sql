CREATE DEFINER=`root`@`localhost` PROCEDURE `set_partialypaid_amount_without_plugin`(_payment_request_id varchar(10))
BEGIN
 DECLARE EXIT HANDLER FOR SQLEXCEPTION
BEGIN
END;
SET @paid_amt=0;
select plugin_value,payment_request_status,grand_total into @plugin_value,@payment_request_status,@grand_total from payment_request where payment_request_id=_payment_request_id;
select sum(amount) into @offline_amount from offline_response where payment_request_id=_payment_request_id and is_active=1 and transaction_status=1;
if(@offline_amount>0)then
SET @paid_amt=@paid_amt+@offline_amount;
end if;
select sum(amount) into @offline_amount from offline_response where payment_request_id=_payment_request_id and is_active=1 and transaction_status=1;
if(@offline_amount>0)then
SET @paid_amt=@paid_amt+@offline_amount;
end if;
select sum(amount)-sum(convenience_fee) into @online_amount from payment_transaction where payment_request_id=_payment_request_id and payment_transaction_status=1;
if(@online_amount>0)then
SET @paid_amt=@paid_amt+@online_amount;
end if;
if(@paid_amt=0 and @payment_request_status=7)then
SET @payment_request_status=0;
end if;
if(@grand_total<=@paid_amt and @payment_request_status=7)then
SET @payment_request_status=2;
end if;
if(@grand_total>@paid_amt and @payment_request_status=7)then
SET @payment_request_status=7;
end if;
if(@grand_total>@paid_amt and @payment_request_status=2)then
SET @payment_request_status=7;
end if;
if(@grand_total>@paid_amt and @payment_request_status=0)then
SET @payment_request_status=7;
end if;
if(@paid_amt=0 and @payment_request_status=7)then
SET @payment_request_status=0;
end if;
update payment_request set paid_amount =@paid_amt,payment_request_status=@payment_request_status where payment_request_id=_payment_request_id;
END