CREATE DEFINER=`root`@`localhost` PROCEDURE `stock_management`(_merchant_id char(10),_id varchar(20),_ref_type INT,_type INT)
BEGIN
SET @app_id=0;


select account_id into @app_id from `account` where merchant_id=_merchant_id and inventory=1 and is_active=1;

if(@app_id>0)then
	SET @stock=1;
	if(_ref_type=3)then
    select payment_request_type,invoice_type into @payment_request_type,@invoice_type from payment_request where payment_request_id=_id;
		if(@payment_request_type=4 or @invoice_type=2)then
        SET @stock=0;
		end if;
	elseif(_ref_type=2)then
    select `type` into @type from expense where expense_id=_id;
    if(@type<>1)then
        SET @stock=0;
		end if;
    end if;
    if(@stock=1)then
	update stock_ledger set is_active=0 where reference_type=_ref_type and reference_id=_id;
    if(_type=1)then
		if(_ref_type=2)then
		INSERT INTO `stock_ledger`(`product_id`,`quantity`,`amount`,`reference_id`,`reference_type`,`narrative`,`created_by`,`created_date`,`last_update_by`)
		select d.product_id,d.qty,d.rate,_id,2,'Stock purchased',d.created_by,now(),d.created_by from expense_detail d inner join merchant_product p on p.product_id=d.product_id
		and p.has_stock_keeping=1 where expense_id=_id and d.is_active=1;
			
		elseif(_ref_type=3)then
		INSERT INTO `stock_ledger`(`product_id`,`quantity`,`amount`,`reference_id`,`reference_type`,`narrative`,`created_by`,`created_date`,`last_update_by`)
		select d.product_id,concat('-',d.qty),d.total_amount/d.qty,_id,3,'Stock sold',d.created_by,now(),d.created_by from invoice_particular d inner join merchant_product p on p.product_id=d.product_id
		and p.has_stock_keeping=1 where payment_request_id=_id and d.is_active=1;
		end if;
    end if;
    
    Drop TEMPORARY  TABLE  IF EXISTS temp_product;
	CREATE TEMPORARY TABLE IF NOT EXISTS temp_product (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `product_id` INT NOT NULL ,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;
    
    insert into temp_product(product_id)
    select product_id from stock_ledger where reference_id=_id and reference_type=_ref_type;
    
     update merchant_product f
	inner join ( select product_id, 
    sum(quantity) as avqty
	from stock_ledger  where product_id in (select product_id from temp_product) and is_active=1
    group by product_id 
  ) t 
  on (t.product_id = f.product_id)set f.available_stock = t.avqty where f.has_stock_keeping=1;
    
end if;
end if;
END
