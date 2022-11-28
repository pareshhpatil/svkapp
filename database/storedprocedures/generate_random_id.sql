CREATE DEFINER=`root`@`localhost` FUNCTION `generate_random_id`(_type char (20)) RETURNS char(10) CHARSET latin1
begin

if(_type='xway')then    
SET @chars1='ABCDEGIJKLMNOPQRSVWXYZ';
else
SET @chars1='ABCDEGIJKLMNOPQRSTVWYZ';
end if;

SET @chars2='ABCDEFGHIJKLMNOPQRSTVWXYZ';
SET @numbers='0123456789';

SET @exist_count=1;
while @exist_count>0 DO
select concat(substring(@chars1, rand()*23+1, 1),
              substring(@chars2, rand()*24+1, 1),
              substring(@chars2, rand()*24+1, 1),
              substring(@chars2, rand()*24+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1),
              substring(@numbers, rand()*9+1, 1)
             ) into @random_id;
 
	if(_type='xway')then  
		select count(xway_transaction_id) into @exist_count from xway_transaction where xway_transaction_id = @random_id;
	else
		select count(pay_transaction_id) into @exist_count from payment_transaction where pay_transaction_id = @random_id;
	end if;
    
    IF (length(@random_id) <> 10) THEN
		SET @exist_count=1;
    END IF;

 END WHILE;
    
    RETURN @random_id;
end
