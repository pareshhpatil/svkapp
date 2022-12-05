CREATE DEFINER=`root`@`localhost` PROCEDURE `save_merchant_PG_details`(_merchant_id varchar(10))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

UPDATE `merchant` 
SET 
    `is_legal_complete` = '1',
    `merchant_type` = '2'
WHERE
    `merchant_id` = _merchant_id;

    
    SELECT 
    account_holder_name
INTO @account_holder_name FROM
    merchant_bank_detail
WHERE `merchant_id` = _merchant_id limit 1;

SET @countpg=0;
select count(*) into @countpg from  merchant_fee_detail where merchant_id=_merchant_id;
if(@countpg=0)then
	INSERT INTO `merchant_fee_detail`(`merchant_id`,`pg_id`,`swipez_fee_type`,`swipez_fee_val`,`pg_fee_type`,`pg_fee_val`,
	`pg_tax_type`,`pg_tax_val`,`surcharge_enabled`,`is_active`,`created_date`,`last_update_date`) VALUES
	(_merchant_id, '15', 'F', '0.00', 'F', '0', 'ST', '0', '0', '1', NOW(), NOW());

	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '1',  @account_holder_name, '1', '1.87', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '2',  @account_holder_name, '1', '0.75', '0.75', '2000.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '2',  @account_holder_name, '1', '1.00', '1.00', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '3',  @account_holder_name, '1', '1.87', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '4',  @account_holder_name, '1', '1.87', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '5',  @account_holder_name, '1', '1.2', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
	INSERT INTO `merchant_tdr` (`merchant_id`, `mode`, `merchant_name`, `is_active`, `pg_rate`, `swipez_rate`, `threshold_amount`, `valid_from`, `valid_till`, `created_date`, `updated_date`) 
	VALUES (_merchant_id, '6',  @account_holder_name, '1', '1.90', '2.10', '0.00', '2014-01-01', '2050-12-31', now(), now());
end if;
commit;
set @message  = 'success';
SELECT @message AS 'message';
END
