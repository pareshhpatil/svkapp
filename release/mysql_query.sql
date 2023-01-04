ALTER TABLE `swipez`.`invoice_construction_particular` 
ADD COLUMN `retainage_percent_stored_materials` DOUBLE NOT NULL DEFAULT 0 AFTER `retainage_amount_for_this_draw`,
ADD COLUMN `retainage_amount_stored_materials` DECIMAL(11,2) NOT NULL DEFAULT 0 AFTER `retainage_percent_stored_materials`;
