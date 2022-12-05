CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_tdr_revenue_calc`(_pg_type TINYINT, _from_date DATETIME, _to_date DATETIME)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		show errors;
	END;

SET sql_safe_updates=0;
SET @swipez_merchant_id = 'M000000151';
DROP TEMPORARY  TABLE  IF EXISTS temp_tdr_diff_calculation;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_tdr_diff_calculation (
	`payment_id` VARCHAR(45) NOT NULL,
    `transaction_id` VARCHAR(10) NOT NULL,
    `merchant_user_id` VARCHAR(10) NULL,
    `merchant_id` VARCHAR(10) NULL,
    `company_name` VARCHAR(50) NULL,
    `pg_id` TINYINT NULL,
    `pg_type` TINYINT NULL,
    `payment_mode` varchar(55) NOT NULL,
    `amount` decimal(9,2),
    `swipez_tdr_share` decimal(9,2),
    `gst` decimal(9,2),
    `cap_date` timestamp DEFAULT '2014-01-01',
PRIMARY KEY (`transaction_id`)) ENGINE=MEMORY;

INSERT INTO temp_tdr_diff_calculation (payment_id, transaction_id, merchant_user_id, payment_mode, amount,cap_date)  
	SELECT payment_id, transaction_id, merchant_id, payment_method, captured, cap_date FROM payment_transaction_tdr
    WHERE cap_date >= _from_date
    AND cap_date <= _to_date;


UPDATE temp_tdr_diff_calculation t, merchant m SET
t.merchant_id = m.merchant_id,
t.company_name = m.company_name
WHERE t.merchant_user_id=m.user_id;


UPDATE temp_tdr_diff_calculation t, payment_transaction p SET
t.pg_id = p.pg_id
WHERE t.transaction_id = p.pay_transaction_id;


UPDATE temp_tdr_diff_calculation t, xway_transaction x SET
t.pg_id = x.pg_id
WHERE t.transaction_id = x.xway_transaction_id;


UPDATE temp_tdr_diff_calculation t, payment_gateway p SET
t.pg_type = p.pg_type
WHERE t.pg_id = p.pg_id;


DELETE FROM temp_tdr_diff_calculation WHERE pg_type <> _pg_type;


DELETE FROM temp_tdr_diff_calculation WHERE merchant_id = @swipez_merchant_id;


UPDATE temp_tdr_diff_calculation SET payment_mode = 10 WHERE payment_mode='UPI';


UPDATE temp_tdr_diff_calculation t, config c SET
t.payment_mode = c.config_key
WHERE config_type='payment_mode'
AND t.payment_mode = c.config_value;


DELETE FROM temp_tdr_diff_calculation WHERE payment_mode = "9";


UPDATE temp_tdr_diff_calculation t, merchant_tdr m 
SET t.swipez_tdr_share = t.amount * ((m.swipez_rate-m.pg_rate)/100)
WHERE t.merchant_id = m.merchant_id
AND t.payment_mode = m.mode;

SELECT t.payment_id, t.transaction_id, t.company_name, c.config_value as 'payment_mode', t.amount, t.swipez_tdr_share, t.cap_date
FROM temp_tdr_diff_calculation t, config c
WHERE c.config_type='payment_mode'
AND c.config_key = t.payment_mode;

DROP TABLE temp_tdr_diff_calculation;

END
