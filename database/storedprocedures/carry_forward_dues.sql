CREATE DEFINER=`root`@`localhost` FUNCTION `carry_forward_dues`(_merchant_id char(10),_customer_id INT,_req_id char(10)) RETURNS int(11)
BEGIN

update payment_request p,contact_ledger l set p.payment_request_status=8, p.expiry_date=SUBDATE(CURDATE(),1),l.is_active=0 
where p.merchant_id=_merchant_id and p.customer_id=_customer_id and  p.payment_request_status in(0,5,4) 
and p.payment_request_id<>_req_id and p.payment_request_id=l.reference_no and p.customer_id=l.customer_id and l.type=1;

select sum(amount) into @debit from contact_ledger where customer_id=_customer_id and ledger_type='DEBIT' and is_active=1;
select sum(amount) into @credit from contact_ledger where customer_id=_customer_id and ledger_type='CREDIT' and is_active=1;
if(@credit>0)then
update customer set balance=@debit-@credit where customer_id=_customer_id;
else
update customer set balance=@debit where customer_id=_customer_id;
end if;

RETURN 1;
END
