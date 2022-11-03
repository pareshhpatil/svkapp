CREATE DEFINER=`root`@`localhost` PROCEDURE `save_event_transaction`(_payment_request_id varchar(10),_customer_id INT, _patron_id varchar(10),_user_id varchar(10),
 _amount decimal(11,2),_tax_amount decimal(11,2),_discount_amount decimal(11,2), _pg_id INT,_fee_id INT, _seat INT,_occurence_id longtext, _package_id longtext,_attendee_customer_id longtext,
 _coupon_id INT,_narrative varchar(500),_custom_column_id longtext,_custom_column_value longtext,_franchise_id INT,_vendor_id INT)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SELECT 
    merchant_id
INTO @merchat_id FROM
    merchant
WHERE
    user_id = _user_id;

SELECT GENERATE_SEQUENCE('Pay_Trans') INTO @transaction_id;

SET @grand_total=_amount - _tax_amount + _discount_amount;

INSERT INTO `payment_transaction`(`pay_transaction_id`,`payment_request_id`,`customer_id`,`patron_user_id`,`paid_on`,
`merchant_user_id`,`merchant_id`,`amount`,`tax`,`discount`,`grand_total`,`unit_price`,`quantity`,`payment_request_type`,`payment_transaction_status`,
`bank_status`,`pg_id`,`fee_id`,`franchise_id`,`vendor_id`,`pg_ref_no`,`pg_ref_1`,`pg_ref_2`,`narrative`,`created_by`,`created_date`,`last_update_by`,
`last_update_date`)VALUES(@transaction_id,_payment_request_id,_customer_id,_patron_id,CURDATE(),_user_id,@merchat_id,_amount,_tax_amount,_discount_amount,@grand_total,@grand_total/_seat,_seat,2,0,0,_pg_id,_fee_id,_franchise_id,_vendor_id,'',
'','',_narrative,_patron_id,CURRENT_TIMESTAMP(),_patron_id,CURRENT_TIMESTAMP());

SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);
SET @coupon_id=0;

SELECT 
    coupon_code
INTO @coupon_id FROM
    event_request
WHERE
    coupon_code = _coupon_id
        AND event_request_id = _payment_request_id;

WHILE _package_id != '' > 0 DO
    SET @attendee_customer_id  = SUBSTRING_INDEX(_attendee_customer_id, @separator, 1);
    SET @package_id  = SUBSTRING_INDEX(_package_id, @separator, 1);
    SET @occurence_id  = SUBSTRING_INDEX(_occurence_id, @separator, 1);
    SET @coupon_code=0;
    
SELECT 
    price
INTO @price FROM
    event_package
WHERE
    package_id = @package_id;

SELECT 
    coupon_code
INTO @coupon_id FROM
    event_package
WHERE
    coupon_code = _coupon_id
        AND package_id = @package_id;

if(@coupon_id>0) then 
SET @coupon_code=@coupon_id;
else
SET @coupon_code=0;
end if;

INSERT INTO `event_transaction_detail`(`transaction_id`,`event_request_id`,`package_id`,`occurence_id`,`customer_id`,`amount`,`coupon_code`,`created_by`,
`created_date`,`last_update_by`,`last_update_date`)VALUES(@transaction_id,_payment_request_id,@package_id,@occurence_id,@attendee_customer_id,
@price,@coupon_code,_patron_id,CURRENT_TIMESTAMP(),_patron_id,CURRENT_TIMESTAMP());

    SET _attendee_customer_id = SUBSTRING(_attendee_customer_id, CHAR_LENGTH(@attendee_customer_id) + @separatorLength + 1);
    SET _package_id = SUBSTRING(_package_id, CHAR_LENGTH(@package_id) + @separatorLength + 1);
    SET _occurence_id = SUBSTRING(_occurence_id, CHAR_LENGTH(@occurence_id) + @separatorLength + 1);
END WHILE;



WHILE _custom_column_id != '' > 0 DO
    SET @custom_column_id  = SUBSTRING_INDEX(_custom_column_id, @separator, 1);
    SET @custom_column_value  = SUBSTRING_INDEX(_custom_column_value, @separator, 1);
   
   INSERT INTO `event_capture_values`(`transaction_id`,`column_id`,`value`,`created_by`,`created_date`,
`last_update_by`,`last_update_date`)VALUES(@transaction_id,@custom_column_id,@custom_column_value,_patron_id,CURRENT_TIMESTAMP(),_patron_id,CURRENT_TIMESTAMP());

    SET _custom_column_id = SUBSTRING(_custom_column_id, CHAR_LENGTH(@custom_column_id) + @separatorLength + 1);
    SET _custom_column_value = SUBSTRING(_custom_column_value, CHAR_LENGTH(@custom_column_value) + @separatorLength + 1);
END WHILE;


SELECT @transaction_id AS 'transaction_id';

commit;
END
