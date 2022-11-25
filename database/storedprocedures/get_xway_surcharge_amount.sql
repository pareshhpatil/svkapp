CREATE DEFINER=`root`@`localhost` FUNCTION `get_xway_surcharge_amount`(_amount decimal(11,2),_fee_detail_id INT) RETURNS decimal(11,2)
BEGIN

SET @invoice_total=_amount;
SET @convenience_fee=0;

select surcharge_enable,pg_surcharge_enabled  into @surcharge,@pg_surcharge_enabled from xway_merchant_detail where xway_merchant_detail_id=_fee_detail_id;

if(@surcharge=1 and @pg_surcharge_enabled=0)then

select swipez_fee_type , swipez_fee_val,pg_fee_type,pg_fee_val,pg_tax_val,merchant_id
into @swipez_fee_type,@swipez_fee_val,@pg_fee_type,@pg_fee_val,@pg_tax,@merchant_id
from xway_fee_detail where xway_id=_fee_detail_id;

set @pg_tax_val=0;
set @swipez_fee=0;
set @EBS_fee=0;
set @pg_rate=0;
SET @convenience_fee=0;

if(@swipez_fee_type='F') then 
set @swipez_fee=@swipez_fee_val;
end if;

if(@swipez_fee_type='P') then 
set @swipez_fee=@invoice_total * @swipez_fee_val/100;
end if;

SET @swipez_total= @invoice_total + @swipez_fee;

if(@pg_fee_type='F') then 
set @EBS_fee= @pg_fee_val;
end if;

if(@pg_fee_type='P') then 
set @EBS_fee=@swipez_total * @pg_fee_val/100;
end if;

if(@pg_tax>0) then 
set @pg_tax_val= @EBS_fee * @pg_tax/100;
end if;


SET @convenience_fee=@swipez_fee + @EBS_fee + @pg_tax_val;
end if;

RETURN @convenience_fee;
END
