CREATE DEFINER=`root`@`localhost` PROCEDURE `temp_update_inv`(_req_id varchar(10), _old_inv varchar(50),_new_inv varchar(50))
BEGIN
BEGIN
        set @message = 'failed';
			ROLLBACK;
		END; 
START TRANSACTION;

UPDATE `payment_request` SET `invoice_number`=_new_inv,`last_update_by`='Paresh' WHERE payment_request_id=_req_id;
UPDATE `invoice_column_values` SET `value`=_new_inv,`last_update_by`='Paresh' WHERE `value`=_old_inv and payment_request_id=_req_id;



commit;
END
