CREATE DEFINER=`root`@`localhost` PROCEDURE `mailer_monthly_payment_summary`(_from_date date , _to_date date)
BEGIN
DECLARE bDone INT;
DECLARE uid CHAR(10);
DECLARE mid CHAR(10);
DECLARE muid CHAR(10);
DECLARE curs CURSOR FOR select user_id, merchant_id, merchant_user_id from tmp_mail_mnthly_pymt_summary;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
END;

Drop TEMPORARY  TABLE  IF EXISTS tmp_mail_mnthly_pymt_summary;

CREATE TEMPORARY TABLE IF NOT EXISTS tmp_mail_mnthly_pymt_summary (
    `user_id` varchar(10) NOT NULL,
    `merchant_id` varchar(10) NOT NULL ,
    `merchant_user_id` varchar(10) NULL ,
    `company_name` varchar(100) NULL ,
    `merchant_fname` varchar(10) NULL,
    `merchant_email` varchar(254) NULL,
    `invoices_sent` INT NULL,
    `invoices_sent_amount` DECIMAL(11,2) DEFAULT 0,
    `pymt_online_number` INT NULL,
    `pymt_online_amount` DECIMAL(11,2) DEFAULT 0,
    `pymt_offline_number` INT NULL,
    `pymt_offline_amount` DECIMAL(11,2) DEFAULT 0,
    `invoices_pending_amount` DECIMAL(11,2) DEFAULT 0,
    PRIMARY KEY (`user_id`)) ENGINE=MEMORY;

    insert into tmp_mail_mnthly_pymt_summary (user_id, merchant_id, merchant_user_id)
      select user_id, merchant_id, merchant_user_id from merchant_notification_preferences
      where weekly_summary=1;

    UPDATE tmp_mail_mnthly_pymt_summary t, user u
    SET merchant_fname = u.first_name,
    merchant_email = u.email_id
    WHERE t.user_id = u.user_id;

    UPDATE tmp_mail_mnthly_pymt_summary t, merchant m
    SET t.company_name = m.company_name
    WHERE t.user_id = m.user_id;

    OPEN curs;
    SET bDone = 0;
    REPEAT
		FETCH curs INTO uid,mid,muid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @invoice_count, @invoices_sent_amount FROM payment_request
      WHERE user_id = muid
      AND payment_request_type<>4 and created_date >= _from_date
      AND created_date <= _to_date;

      IF @invoices_sent_amount IS NULL
      THEN
        SET @invoices_sent_amount = 0;
      END IF;

      UPDATE tmp_mail_mnthly_pymt_summary SET
      invoices_sent = @invoice_count,
      invoices_sent_amount = @invoices_sent_amount
      WHERE user_id = uid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @pymt_online_number, @pymt_online_amount FROM payment_request
      WHERE user_id = muid
      AND created_date >= _from_date
      AND payment_request_type<>4 and created_date <= _to_date
      AND payment_request_status = 1;

      IF @pymt_online_amount IS NULL
      THEN
        SET @pymt_online_amount = 0;
      END IF;

      UPDATE tmp_mail_mnthly_pymt_summary SET
      pymt_online_number = @pymt_online_number,
      pymt_online_amount = @pymt_online_amount
      WHERE user_id = uid;

      
      SELECT count(payment_request_id), sum(absolute_cost) into @pymt_offline_number, @pymt_offline_amount FROM payment_request
      WHERE user_id = muid
      AND created_date >= _from_date
      AND payment_request_type<>4 and created_date <= _to_date
      AND payment_request_status = 2;

      IF @pymt_offline_amount IS NULL
      THEN
        SET @pymt_offline_amount = 0;
      END IF;

      UPDATE tmp_mail_mnthly_pymt_summary SET
      pymt_offline_number = @pymt_offline_number,
      pymt_offline_amount = @pymt_offline_amount
      WHERE user_id = uid;
    UNTIL bDone END REPEAT;
    CLOSE curs;

    UPDATE tmp_mail_mnthly_pymt_summary SET
    invoices_pending_amount = invoices_sent_amount - (pymt_online_amount + pymt_offline_amount);
    
    select * from tmp_mail_mnthly_pymt_summary order by invoices_sent desc;
    Drop TEMPORARY  TABLE  IF EXISTS tmp_mail_mnthly_pymt_summary;

END
