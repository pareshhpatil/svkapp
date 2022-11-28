CREATE DEFINER=`root`@`localhost` PROCEDURE `update_booking_status`(_pay_transaction_id varchar(10))
BEGIN
	update booking_slots s,booking_transaction_detail d set s.slot_available=0 where s.is_multiple=0 and d.transaction_id=_pay_transaction_id and s.slot_id=d.slot_id;
	Drop TEMPORARY  TABLE  IF EXISTS temp_slots_ids;
	CREATE TEMPORARY TABLE IF NOT EXISTS temp_slots_ids (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`slot_id` INT  NULL ,
	`count` INT NOT NULL ,
	PRIMARY KEY (`id`)) ENGINE=MEMORY;
	insert temp_slots_ids (slot_id,count) select slot_id ,count(slot_id) from booking_transaction_detail where transaction_id=_pay_transaction_id group by slot_id;
	update booking_slots s,temp_slots_ids d set s.available_seat=available_seat-d.count where s.slot_id=d.slot_id and s.total_seat>0;
	update booking_slots s,booking_transaction_detail d set s.slot_available=0 where s.is_multiple=1 and d.transaction_id=_pay_transaction_id and s.slot_id=d.slot_id and s.total_seat>0 and s.available_seat<1;
END
