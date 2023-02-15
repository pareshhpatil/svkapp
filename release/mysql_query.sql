UPDATE `swipez`.`merchant_auto_invoice_number` SET `prefix` = '' WHERE prefix is null;

ALTER TABLE `swipez`.`merchant_auto_invoice_number` 
CHANGE COLUMN `prefix` `prefix` VARCHAR(20) NOT NULL DEFAULT '' ;