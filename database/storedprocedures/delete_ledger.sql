CREATE DEFINER=`root`@`localhost` FUNCTION `delete_ledger`(_ref varchar(10),_type tinyint(1)) RETURNS tinyint(1)
BEGIN

SELECT id, customer_id, amount,ledger_type INTO @id , @customer_id , @amount,@ledger_type FROM contact_ledger WHERE reference_no = _ref and `type`=_type and is_active=1 limit 1;

if(@ledger_type='CREDIT')then
update customer set balance = balance + @amount where customer_id=@customer_id;
else
update customer set balance = balance - @amount where customer_id=@customer_id;
end if;

update contact_ledger set is_active = 0 where id=@id;

RETURN 1;
END
