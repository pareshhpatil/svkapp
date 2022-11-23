CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_save_payment_gateway`(_merchant_id char(10),_type INT,_pg_type INT(10),_secret_key varchar(255),_access_key varchar(255),_razorpay_account_id varchar(255),_whitelist_url varchar(255),_created_by varchar(10),_last_update_by varchar(10))
BEGIN
SET @merchant_id=_merchant_id;
SET @p_type=_type;
SET @pg_type=_pg_type;
SET @pg_val1=_access_key;
SET @pg_val2=_secret_key;
SET @pg_val3="Live";
SET @pg_val7=_razorpay_account_id;
SET @whitelist_url=_whitelist_url;
SET @pg_val8 = null;
SET @pg_val9 = null;
SET @created_by=_created_by;

SET @last_update_by = _last_update_by;
select company_name into @company_name from merchant where merchant_id=@merchant_id;
IF(@company_name IS NULL OR @company_name = '') then
   SET @pg_id = null;
else
	/*pg_type = 10 Razorpay , 7 = Cashfree */
	if(@pg_type=10)then
		SET @pg_val4 = "Swipez";
		SET @pg_val5 = @company_name;
		SET @pg_val6 = @merchant_id;
		SET @req_url = null;
		SET @ret_tname = "pg_ret_bank9";
		SET @pg_name = CONCAT('RP-',@company_name);
	else
		SET @pg_val4 = null;
		SET @pg_val5 = "https://www.swipez.in/secure/invoke/cashfree";
		SET @pg_val6 = null;
		SET @req_url = "https://www.cashfree.com/checkout/post/submit";
		SET @ret_tname = "pg_ret_bank7";
		SET @pg_name = CONCAT('CF-',@company_name);
	end if;
	if(@p_type=1) then
		if(@pg_type=7) then
			SET @status_url = "https://www.swipez.in/secure/cashfreeresponse";
		else
			SET @status_url= "https://www.swipez.in/secure/razorpayresponse";
		end if;
	else
		if(@pg_type=7) then
			SET @status_url = "https://www.swipez.in/xway/cashfreeresponse";
		else
			SET @status_url = "https://www.swipez.in/xway/razorpayresponse";
		end if;
	end if;
	INSERT INTO `payment_gateway` (`pg_name`, `pg_type`, `is_active`, `pg_val1`, `pg_val2`, `pg_val3`, `pg_val4`, `pg_val5`, `pg_val6`, `pg_val7`, `pg_val8`, `pg_val9`, `req_url` , `status_url`, `nodal_settlement`, `type`, `ret_tname`, `created_by`, `created_date`, `last_update_by`, `last_update_date`)
	VALUES (@pg_name, @pg_type, '1', @pg_val1, @pg_val2, @pg_val3, @pg_val4, @pg_val5, @pg_val6, @pg_val7, @pg_val8, @pg_val9, @req_url, @status_url, '0', '1', @ret_tname, @created_by, current_timestamp(), @last_update_by, current_timestamp());
	/*select last_insert_id() into InsertId ;*/
	SET @pg_id = LAST_INSERT_ID();
	if(@p_type=1)then
		update merchant_fee_detail set is_active=0 where merchant_id=@merchant_id;
		INSERT INTO `merchant_fee_detail` (`merchant_id`, `franchise_id`, `vendor_id`, `pg_id`, `swipez_fee_type`, `swipez_fee_val`, `pg_fee_type` , `pg_fee_val`, `pg_tax_type`, `pg_tax_val`, `surcharge_enabled`, `pg_surcharge_enabled`, `enable_tnc`, `is_active`, `created_date`, `last_update_date`)
		VALUES (@merchant_id, '0', '0', @pg_id, 'F', 0, 'F', 0, 'GST', 18.00, '0', '0', '0', '1', current_timestamp(), current_timestamp());
	else
		SET @xway_security_key = md5(concat(@merchant_id, @company_name));
		INSERT INTO `xway_merchant_detail` (`merchant_id`, `franchise_id`, `vendor_id`, `xway_security_key`, `pg_id`, `logging_status`, `referrer_url` , `return_url`, `notify_merchant`, `notify_patron`, `surcharge_enable`, `pg_surcharge_enabled`, `ga_tag`, `created_date`, `last_update_date`)
		VALUES (@merchant_id, '0', '0', @xway_security_key, @pg_id, '1', @whitelist_url, @whitelist_url, '1', '1', '0', '0', null, current_timestamp(), current_timestamp());
		
		update merchant_setting set xway_enable=1 where merchant_id=@merchant_id;
	end if;
    update merchant set is_legal_complete=1 where merchant_id=@merchant_id;
	select @pg_id as 'pg_id';
end if;
END
