CREATE DEFINER=`root`@`localhost` PROCEDURE `save_offline_event_transaction`(_payment_request_id varchar(10), _customer_id INT,_merchant_user_id varchar(10),
_amount decimal(11,2),_tax_amount decimal(11,2),_discount_amount decimal(11,2),_price longtext, _bank_id varchar(200), _date datetime,_bank_ref_no varchar(20),_cheque_no INT,_cash_paid_to varchar(100),_response_type INT,_seat INT,_occurence_id longtext,
_package_id longtext,_attendee_name longtext, _mobile longtext,_age longtext,_coupon_id INT,_cheque_status varchar(20)	)
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
			ROLLBACK;
            show errors;
		END; 
START TRANSACTION;

SET @coupon_id=0;

select generate_sequence('Offline_respond_id') into @transaction_id;

select coupon_code,franchise_id,vendor_id,merchant_id into @coupon_id,@franchise_id,@vendor_id,@merchant_id from event_request where coupon_code=_coupon_id and  event_request_id =_payment_request_id;

SET @grand_total=_amount - _tax_amount + _discount_amount;

INSERT INTO `offline_response`(`offline_response_id`,`payment_request_id`,`payment_request_type`,`customer_id`,`merchant_id`,`merchant_user_id`,`offline_response_type`,
`settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`tax`,`discount`,`grand_total`,`quantity`,`cheque_no`,`cash_paid_to`,`cheque_status`,`created_by`,`created_date`, `last_update_by`,
`last_update_date`,franchise_id,vendor_id)
	VALUES (@transaction_id,_payment_request_id,2,_customer_id,@merchant_id,_merchant_user_id,_response_type,_date,_bank_ref_no,_bank_id,_amount,_tax_amount,_discount_amount,@grand_total,_seat,_cheque_no,_cash_paid_to,_cheque_status,_merchant_user_id, CURRENT_TIMESTAMP(),_merchant_user_id,CURRENT_TIMESTAMP(),@franchise_id,@vendor_id);

SET @position=0;
SET @separator = '~';
SET @separatorLength = CHAR_LENGTH(@separator);




WHILE _package_id != '' > 0 DO
    SET @attendee_name  = SUBSTRING_INDEX(_attendee_name, @separator, 1);
    SET @package_id  = SUBSTRING_INDEX(_package_id, @separator, 1);
    SET @occurence_id  = SUBSTRING_INDEX(_occurence_id, @separator, 1);
    SET @mobile  = SUBSTRING_INDEX(_mobile, @separator, 1);
    SET @age  = SUBSTRING_INDEX(_age, @separator, 1);
    SET @price  = SUBSTRING_INDEX(_price, @separator, 1);
	SET @coupon_code=0;
    if(@age>0)then
    SET @age=@age;
    else
    SET @age=0;
    end if;

	
select price into @price from event_package where  package_id=@package_id;

select coupon_code into @coupon_id from event_package where coupon_code=_coupon_id and  package_id=@package_id;

if(@coupon_id>0) then 
SET @coupon_code=@coupon_id;
else
SET @coupon_code=0;
end if;

INSERT INTO `event_transaction_detail`(`transaction_id`,`event_request_id`,`occurence_id`,`package_id`,`name`,`mobile`,`is_paid`,`age`,`amount`,`coupon_code`,`created_by`,
`created_date`,`last_update_by`,`last_update_date`)VALUES(@transaction_id,_payment_request_id,@occurence_id,@package_id,@attendee_name,
@mobile,1,@age,@price,@coupon_code,_merchant_user_id,CURRENT_TIMESTAMP(),_merchant_user_id,CURRENT_TIMESTAMP());

    SET _attendee_name = SUBSTRING(_attendee_name, CHAR_LENGTH(@attendee_name) + @separatorLength + 1);
    SET _package_id = SUBSTRING(_package_id, CHAR_LENGTH(@package_id) + @separatorLength + 1);
    SET _occurence_id = SUBSTRING(_occurence_id, CHAR_LENGTH(@occurence_id) + @separatorLength + 1);
    SET _mobile = SUBSTRING(_mobile, CHAR_LENGTH(@mobile) + @separatorLength + 1);
    SET _age = SUBSTRING(_age, CHAR_LENGTH(@age) + @separatorLength + 1);
    SET _price = SUBSTRING(_price, CHAR_LENGTH(@price) + @separatorLength + 1);
END WHILE;


Drop TEMPORARY  TABLE  IF EXISTS temp_coupon_ids;
CREATE TEMPORARY TABLE IF NOT EXISTS temp_coupon_ids (
     `id` int(11) NOT NULL AUTO_INCREMENT,
    `coupon_id` INT  NULL ,
    `count` INT NOT NULL ,
    PRIMARY KEY (`id`)) ENGINE=MEMORY;

insert temp_coupon_ids (coupon_id,count) select coupon_code ,count(coupon_code) from event_transaction_detail where transaction_id=@transaction_id and coupon_code<>0;
update coupon r , temp_coupon_ids m  set r.available = r.available - m.count  where r.coupon_id=m.coupon_id and r.`limit` <> 0 ;

Drop TEMPORARY  TABLE  IF EXISTS temp_coupon_ids;

commit;

select @transaction_id as 'transaction_id';

END