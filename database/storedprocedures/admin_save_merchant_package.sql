CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_save_merchant_package`(_merchant_id char(10),_plan INT,_amount decimal(11,2),_ref_no varchar(20),_sms INT)
BEGIN
SET @merchant_plan=_plan;
SET @merchant_id=_merchant_id;
SET @base_amount=_amount*100/118;
SET @tax_amount=_amount-@base_amount;
SET @ref_no=_ref_no;
SET @sms_bought=_sms;
select package_name into @package_name from package where package_id=@merchant_plan;
select user_id into @user_id from merchant where merchant_id=@merchant_id;
IF(@user_id IS NULL OR @user_id = '') then
  SET @trans_id = null;
else
	select email_id,concat(first_name,' ',last_name),mobile_no  into @email,@name,@mobile from user where user_id=@user_id;
	select address,city,state,zipcode into @address,@city,@state,@zipcode from merchant_billing_profile where merchant_id=@merchant_id;
	select generate_sequence('Fee_transaction_id') into @trans_id;
	INSERT INTO `package_transaction` (`package_transaction_id`, `user_id`, `merchant_id`, `payment_transaction_status`, `package_id`, `base_amount`,tax_amount,tax_text, `amount`, `narrative`, `pg_type`, `pg_ref_no`, `pg_ref_1`, `created_by`, `created_date`, `last_update_by`, `last_update_date`)
	VALUES (@trans_id, @user_id, @merchant_id, '1', @merchant_plan, @base_amount,@tax_amount,'["SGST@9%","CGST@9%"]', @base_amount+@tax_amount, @package_name, '3', @ref_no, @ref_no, 'ADMIN', current_timestamp(), 'ADMIN', current_timestamp());
	INSERT INTO `package_transaction_details`(`package_transaction_id`,`name`,`email`,`mobile`,`address`,`city`,`state`,`zipcode`,`created_date`)
	VALUES(@trans_id,@name,@email,@mobile,'','','','',current_timestamp());
	update account set is_active=0 where merchant_id=@merchant_id;
	SET @end_date=DATE_ADD(now(), INTERVAL 12 MONTH);
	update merchant set merchant_plan=@merchant_plan,package_expiry_date=@end_date where merchant_id=@merchant_id;
	INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,total_invoices,`bulk_invoice`,`free_sms`,`merchant_role`,coupon,supplier,
	`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
	select @merchant_id,package_id,@trans_id,_amount,individual_invoice,total_invoices,bulk_invoice,free_sms,merchant_role,coupon,supplier,now(),DATE_ADD(now(), INTERVAL duration MONTH),1,@merchant_id,now(),@merchant_id from package where package_id=@merchant_plan;
	INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
	`last_update_by`)VALUES(7,@merchant_id,@trans_id,@sms_bought,@sms_bought,now(),@end_date,1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');
	if(@merchant_plan=3)then
	INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
	`last_update_by`)VALUES(6,@merchant_id,@trans_id,1,1,now(),DATE_ADD(now(), INTERVAL 12 MONTH),1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');
	INSERT INTO `merchant_addon`(`package_id`,`merchant_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,`end_date`,`is_active`,`created_by`,`created_date`,
	`last_update_by`)VALUES(8,@merchant_id,@trans_id,1,1,now(),DATE_ADD(now(), INTERVAL 12 MONTH),1,'ADMIN',CURRENT_TIMESTAMP(), 'ADMIN');
	end if;
end if;
	select @trans_id as 'transaction_id';
END
