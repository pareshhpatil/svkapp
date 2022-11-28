CREATE DEFINER=`root`@`localhost` PROCEDURE `save_bulk_expense`(_expense_id int,_expense_no varchar(45))
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION 
 		BEGIN
        show errors;
			ROLLBACK;
		END; 
START TRANSACTION;

SET @gst_number='';

select merchant_id into @merchant_id from staging_expense where expense_id=_expense_id;
select gst_number into @gst_number from merchant_billing_profile where merchant_id=@merchant_id and is_default=1;


INSERT INTO `expense`(`type`,`merchant_id`,`vendor_id`,`category_id`,`department_id`,`expense_no`,`invoice_no`,`bill_date`,
`due_date`,`tds`,`discount`,`adjustment`,`payment_status`,`payment_mode`,`base_amount`,`cgst_amount`,
`sgst_amount`,`igst_amount`,`total_amount`,`notify`,`narrative`,`file_path`,`bulk_id`,`gst_number`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
select `type`,`merchant_id`,`vendor_id`,`category_id`,`department_id`,_expense_no,`invoice_no`,`bill_date`,
`due_date`,`tds`,`discount`,`adjustment`,`payment_status`,`payment_mode`,`base_amount`,`cgst_amount`,
`sgst_amount`,`igst_amount`,`total_amount`,`notify`,`narrative`,`file_path`,`bulk_id`,@gst_number,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
from staging_expense where expense_id=_expense_id;

SET @expense_id=LAST_INSERT_ID();

INSERT INTO `expense_detail`
(`expense_id`,`particular_name`,`product_id`,`sac_code`,`qty`,`rate`,`sale_price`,`amount`,`tax`,`cgst_amount`,`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
select @expense_id,`particular_name`,`product_id`,`sac_code`,`qty`,`rate`,`sale_price`,`amount`,`tax`,`cgst_amount`,`sgst_amount`,`igst_amount`,`gst_percent`,`total_value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`
from staging_expense_detail where expense_id=_expense_id;
commit;
SET @status='success';
select @status as 'status';
END
