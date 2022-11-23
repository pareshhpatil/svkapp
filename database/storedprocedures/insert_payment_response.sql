CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_payment_response`(_type varchar(20),_transaction_id varchar(10),_payment_id varchar(40),_pg_transaction_id varchar(40),_amount decimal(11,2),_payment_method INT,_payment_mode varchar(45),_message varchar(250),_status varchar(20),_user_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
	show errors;
    ROLLBACK;
		END; 
START TRANSACTION;

SET @plugin_value='';
SET @image='';
SET @patron_mobile='';
SET @pay_transaction_id=_transaction_id;
SET @payment_id=_payment_id;
SET @transaction_id=_pg_transaction_id;
SET @message=_message;
SET @payment_method=_payment_method;
SET @template_id='';
SET @payment_status=3;
SET @invoice_type=1;
SET @sms_gateway=1;
SET @from_email='';
SET @sms_gateway_type=1;
SET @sms_name=NULL;
SET @short_url='';
SET @franchise_id=0;
SET @main_company_name='';
SET @webhook_id=0;
SET @event_name='';
SET @unit_type='';
SET @type='';
SET @quantity =0;
SET @profile_id=0;
SET @request_type=1;
SET @sms_available=0;
if(_status='success') then
set @status=1;
SET @response_code=0;
else
set @status=4;
SET @response_code=1;
end if;	

if(_type='package') then
	update package_transaction set payment_transaction_status =@status,pg_ref_no=@payment_id,pg_ref_1=@transaction_id,payment_mode=_payment_mode,payment_info=_message where package_transaction_id=_transaction_id;

	SELECT user_id,merchant_id, package_id, custom_package_id INTO @merchant_user_id,@merchant_id,@package_id,@custom_package_id FROM
	package_transaction WHERE package_transaction_id = _transaction_id;
    
    select concat(first_name,' ',last_name),email_id,mobile_no into @patron_name,@patron_email,@patron_mobile from user where user_id=@merchant_user_id;

	if(_status='success') then
		if(@custom_package_id>0)then
			select 1,package_cost,12,0,0,0,free_sms,invoice,invoice,invoice,event_booking,0,0,0
			into @package_type,@package_cost,@duration,@pg_integration,@site_builder,@brand_keyword,@free_sms,@individual_invoice,
			@bulk_invoice,@total_invoices,@event_booking,@merchant_role,@coupon,@supplier from custom_package where package_id=@custom_package_id;
			UPDATE custom_package SET status = 1 WHERE package_id = @custom_package_id;
			SET @package_id=12;
		else
			select `type`,package_cost,duration,pg_integration,site_builder,brand_keyword,free_sms,individual_invoice,bulk_invoice,total_invoices,merchant_role,coupon,supplier,0
			into @package_type,@package_cost,@duration,@pg_integration,@site_builder,@brand_keyword,@free_sms,@individual_invoice,@bulk_invoice,@total_invoices,@merchant_role,@coupon,@supplier,@event_booking from package where package_id=@package_id;
		end if;

		if(@package_type=1)then
			SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);

			UPDATE account SET is_active = 0 WHERE merchant_id = @merchant_id AND is_active = 1;

			INSERT INTO `account`(`merchant_id`,`package_id`,`custom_package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,`bulk_invoice`,`event_booking`,`free_sms`,
			`merchant_role`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`coupon`,`supplier`,`start_date`,`end_date`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@merchant_id,@package_id,@custom_package_id,_transaction_id,_amount,@individual_invoice,@bulk_invoice,@event_booking,@free_sms,@merchant_role,@pg_integration,@site_builder,@brand_keyword,@total_invoices,@coupon,@supplier
			,NOW(),@end_date,_user_id,CURRENT_TIMESTAMP(),_user_id);


			UPDATE merchant SET merchant_plan = @package_id,package_expiry_date=@end_date WHERE merchant_id = @merchant_id;

			if(@pg_integration=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 0 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,5,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;

			if(@site_builder=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,6,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
				UPDATE merchant_setting 
				SET 
				site_builder = 1
				WHERE
				merchant_id = @merchant_id;
			end if;

			if(@brand_keyword=1)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 0 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,8,_transaction_id,1,1,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;

			if(@free_sms>0)then
				SET @end_date=DATE_ADD(NOW(), INTERVAL 12 MONTH);
				INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
				`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)
				VALUES(@merchant_id,7,_transaction_id,@free_sms,@free_sms,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
				UPDATE merchant SET merchant_type = 2 WHERE	merchant_id = @merchant_id;
			end if;
		end if;

		if(@package_type=2)then
			SET @end_date=DATE_ADD(NOW(), INTERVAL @duration MONTH);
			if(@package_id=7)then
			select base_amount into @base_amount from package_transaction where package_transaction_id=_transaction_id;
			SET @licence_bought=@base_amount/@package_cost;
			UPDATE merchant 
			SET 
			merchant_type = 2
			WHERE
			merchant_id = @merchant_id;
			else
			SET @licence_bought=1;
			end if;
			if(@package_id=6)then
			update merchant_setting set site_builder=1 where merchant_id=@merchant_id;
			end if;
			INSERT INTO `merchant_addon`(`merchant_id`,`package_id`,`package_transaction_id`,`license_bought`,`license_available`,`start_date`,
			`end_date`,`is_active`,`created_by`,`created_date`,`last_update_by`)VALUES(@merchant_id,@package_id,_transaction_id,@licence_bought,@licence_bought,NOW(), @end_date,1,_user_id,CURRENT_TIMESTAMP(),_user_id);
			end if;
		end if;
else
		SELECT payment_request_id,customer_id,merchant_user_id,merchant_id,payment_request_type,discount,coupon_id,quantity,narrative,deduct_amount,deduct_text,fee_id,is_partial_payment,amount-convenience_fee 
		into @payment_request_id,@customer_id,@merchant_user_id,@merchant_id,@type,@discount,@coupon_id,@quantity,
		@narrative,@deduct_amount,@deduct_text,@fee_detail_id,@is_partial_payment,@inv_transaction_amount from payment_transaction where pay_transaction_id=_transaction_id;
		
		SET @pg_surcharge_enabled=0;

		SELECT pg_surcharge_enabled INTO @pg_surcharge_enabled FROM merchant_fee_detail WHERE fee_detail_id = @fee_detail_id;

		if(@pg_surcharge_enabled=1)then
			update payment_transaction set convenience_fee=_amount-amount,`amount`=_amount where pay_transaction_id=_transaction_id;
		end if;

		if(@type=2)then
			SELECT short_url,franchise_id,event_name,unit_type,template_id into @short_url,@franchise_id,@event_name,@unit_type,@template_id from event_request 
			where event_request_id=@payment_request_id;
			else
			SELECT invoice_number,@invoice_type, short_url, franchise_id, webhook_id,template_id,paid_amount,grand_total,payment_request_status,plugin_value,billing_profile_id,request_type
            INTO @invoice_number ,@invoice_type, @short_url , @franchise_id , @webhook_id,@template_id,@paid_amount,@invgrand_total,@payment_request_status,@plugin_value,@profile_id,@request_type FROM
			payment_request WHERE payment_request_id = @payment_request_id;
		end if;

		if(_status='success') then
			SET @payment_status=2;
            
            if(@coupon_id>0) then
					SET @coupon_availed=1;
                    if(@type=2)then
						select count(coupon_code) into @coupon_availed from event_transaction_detail where transaction_id=@pay_transaction_id and coupon_code=@coupon_id;
                    end if;
					
					UPDATE coupon r SET r.available = r.available - @coupon_availed WHERE r.coupon_id = @coupon_id
					AND r.`limit` <> 0;
              end if;
            
			if(@type=2)then
				SET @link='/merchant/transaction/viewlist/event';

				UPDATE event_transaction_detail SET is_paid = 1 WHERE transaction_id = @pay_transaction_id;
				elseif(@type=3)then
				SET @link='/merchant/transaction/viewlist/bulk';
				elseif(@type=4)then
				SET @link='/merchant/transaction/viewlist/subscription';
				elseif(@type=5)then
				update booking_transaction_detail set is_paid=1 where transaction_id=@pay_transaction_id;
				call update_booking_status(@pay_transaction_id);
				SET @link='/merchant/transaction/viewlist/booking';
				elseif(@type=6)then
				update customer_membership set status=1 where transaction_id=@pay_transaction_id;
				SET @link='/merchant/transaction/viewlist/booking';
				else
				SET @link='/merchant/transaction/viewlist';
				end if;

				
				if(@template_id<>'')then
				select image_path into @image from invoice_template where template_id=@template_id;
				end if;

				INSERT INTO `notification`(`user_id`,`notification_type`,`link`,`from_date`,`to_date`,`message1`,`message2`,
				`created_by`,`created_date`,`last_update_by`,`last_update_date`)
				VALUES(@merchant_user_id,1,@link,CURRENT_TIMESTAMP(),DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH),
				'Payment request(s) have been settled by your patron','',_user_id,CURRENT_TIMESTAMP(),_user_id,CURRENT_TIMESTAMP());

				UPDATE customer SET payment_status = @payment_status WHERE customer_id = @customer_id AND payment_status <> 2;

			else
				update customer set payment_status=@payment_status where customer_id=@customer_id and payment_status in(0,1);
			end if;

			SET @suppliers='';
            UPDATE `payment_request` SET `payment_request_status` = @status WHERE  payment_request_id = @payment_request_id and payment_request_status not in (1,2);
            call `update_status`(@payment_request_id,@pay_transaction_id,_amount,@status,@response_code,@payment_id,@transaction_id,_payment_mode,0,@message);
		
			if(_status='success') then
			update customer set balance = balance - _amount where customer_id=@customer_id;
			INSERT INTO `contact_ledger`(`customer_id`,`description`,`amount`,`type`,`reference_no`,
			`ledger_type`,`created_by`,`created_date`,`last_update_by`)
			VALUES(@customer_id,concat('Payment on ',DATE_FORMAT(NOW(),'%Y-%m-%d')),_amount,2,@transaction_id,'CREDIT',@customer_id,CURRENT_TIMESTAMP(),@customer_id);
			end if;
        
			if(@is_partial_payment=1 and _status='success')then
				SET @paid_amount=@paid_amount+@inv_transaction_amount;

				if(@invgrand_total>@paid_amount)then
				SET @inv_status=7;
				else
				SET @inv_status=1;
				end if;

				update payment_request set payment_request_status=@inv_status,paid_amount=@paid_amount where payment_request_id= @payment_request_id;
			end if;
            
            if(@payment_request_status=7 and _status<>'success')then
				update payment_request set payment_request_status=7 where payment_request_id= @payment_request_id;
            end if;


		SELECT sms_gateway,from_email,sms_gateway_type,sms_name,auto_approve
		INTO @sms_gateway , @from_email , @sms_gateway_type , @sms_name , @auto_approve FROM
		merchant_setting WHERE	merchant_id = @merchant_id;

		SET @pending_change_id='';
		SELECT pending_change_id, change_id INTO @pending_change_id , @change_id FROM pending_change 
		WHERE source_id = @pay_transaction_id AND source_type = 2 AND status = - 1; 

		UPDATE pending_change SET status = 0 WHERE pending_change_id = @pending_change_id;
		UPDATE customer_data_change SET status = 0 WHERE change_id = @change_id;
		UPDATE customer_data_change_detail SET status = 0 WHERE	change_id = @change_id;
		if(@pending_change_id<>'') then
			if(@auto_approve>0) then
				call `auto_aprove_customer_details`(@customer_id,@pay_transaction_id);
			end if;
		end if;

			SELECT customer_code, CONCAT(first_name, ' ', last_name),email,mobile INTO @customer_code , @patron_name,@patron_email,@patron_mobile FROM customer
			WHERE customer_id = @customer_id; 
			SELECT  `logo` INTO @merchant_logo FROM merchant_landing  WHERE merchant_id = @merchant_id;
end if;

	SELECT merchant_domain INTO @merchant_domain FROM merchant WHERE merchant_id = @merchant_id;	
    
 if(@profile_id>0)then
 select company_name,company_name,address,business_email,business_contact into @display_name,@company_name,@merchant_address,@merchant_email_id,@business_contact
 from merchant_billing_profile where id = @billing_profile_id;
 else
  select company_name,company_name,address,business_email,business_contact into @display_name,@company_name,@merchant_address,@merchant_email_id,@business_contact
 from merchant_billing_profile where merchant_id = @merchant_id and is_default=1;
 end if;

	SET @sms_name=@company_name;

	SELECT mobile_no INTO @merchant_mobile_no FROM  `user` WHERE user_id = @merchant_user_id;
	SELECT config_value INTO @payment_Mode FROM config WHERE config_key = @payment_method AND config_type = 'transaction mode'; 

SET @franchise_email_id='';
SET @franchise_mobile_no='';

if(@request_type=2)then
	if(@sms_gateway_type=1)then
		SET @sms_available=1;
	else
		select count(id) into @sms_count from merchant_addon where merchant_id=@merchant_id and package_id=7 and license_available >0 and end_date>curdate();
		if(@sms_count>0)then
			SET @sms_available=1;
		end if;
	end if;
end if;

if(@franchise_id>0)then
	SET @main_company_name=@company_name;
	SET @merchant_email_id_=@merchant_email_id;
	SELECT franchise_name, email_id, mobile
	INTO @company_name , @franchise_email_id , @franchise_mobile_no FROM
	franchise WHERE franchise_id = @franchise_id;
end if;
SET @message='success';
SELECT 
@message AS 'message',
@merchant_domain as 'merchant_domain',
@payment_request_id AS 'payment_request_id',
@invoice_number AS 'invoice_number',
@merchant_user_id AS 'merchant_user_id',
@merchant_id AS 'merchant_id',
@image AS 'image',
@merchant_logo AS 'merchant_logo',
@sms_gateway AS 'sms_gateway',
@company_name AS 'company_name',
@merchant_email_id AS 'merchant_email',
@merchant_mobile_no AS 'mobile_no',
@payment_Mode AS 'payment_mode',
@suppliers AS 'suppliers',
@discount AS 'discount',
@deduct_amount AS 'deduct_amount',
@deduct_text AS 'deduct_text',
@quantity AS 'quantity',
_message AS 'narrative',
@customer_code AS 'customer_code',
@patron_name AS 'patron_name',
@patron_name AS 'BillingName',
@patron_email AS 'patron_email',
@patron_mobile as 'patron_mobile',
@patron_mobile as 'billing_mobile',
@from_email AS 'from_email',
@sms_gateway_type AS 'sms_gateway_type',
@sms_name AS 'sms_name',
@short_url AS 'short_url',
@webhook_id AS 'webhook_id',
@main_company_name AS 'main_company_name',
@franchise_email_id AS 'franchise_email',
@franchise_mobile_no AS 'franchise_mobile',
@plugin_value as 'plugin_value',
@event_name AS 'event_name',
@unit_type AS 'unit_type',
@type AS 'type',
@request_type as 'request_type',
@sms_available as 'sms_available',
@invoice_type as 'invoice_type';

commit;


END