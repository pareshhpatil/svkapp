-- MySQL Workbench Synchronization
-- Generated: 2023-05-09 13:07
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Shuhaid

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


ALTER TABLE `swipez`.`briq_privileges` 
DROP INDEX `type_index` ,
ADD INDEX `type_index` (`type` ASC) ,
DROP INDEX `type_id_index` ,
ADD INDEX `type_id_index` (`type_id` ASC) ;

ALTER TABLE `swipez`.`invoice_construction_particular` 
CHANGE COLUMN `group` `group` VARCHAR(45) NULL DEFAULT NULL  ;

ALTER TABLE `swipez`.`notifications` 
DROP INDEX `notifications_notifiable_type_notifiable_id_index` ,
ADD INDEX `notifications_notifiable_type_notifiable_id_index` (`notifiable_type` ASC, `notifiable_id` ASC) ;


CREATE TABLE IF NOT EXISTS `swipez`.`internal_reminders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `contract_id` INT(11) NOT NULL,
  `user_id` VARCHAR(45) NULL DEFAULT NULL,
  `subject` VARCHAR(500) NULL DEFAULT NULL,
  `reminder_date_type` TINYINT(1) NULL DEFAULT NULL COMMENT '0=last day of month,1=1st day of month,2=last weekday of every month,3=30 days from last invoice bill date,4=custom',
  `reminder_date` VARCHAR(45) NULL DEFAULT NULL,
  `end_date_type` TINYINT(1) NULL DEFAULT NULL COMMENT '0=number of occurences,1=end date',
  `end_date` VARCHAR(20) NULL DEFAULT NULL,
  `repeat_every` INT(11) NULL DEFAULT NULL,
  `repeat_type` VARCHAR(7) NULL DEFAULT NULL COMMENT 'day,week,month,year',
  `repeat_on` VARCHAR(45) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NULL DEFAULT 1,
  `created_by` VARCHAR(10) NOT NULL,
  `last_updated_by` VARCHAR(10) NOT NULL,
  `last_updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  `created_date` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `swipez`.`lambda` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `contract_name` LONGTEXT NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT '2014-01-01 00:00:00',
  `last_update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


ALTER TABLE `swipez`.`merchant_config_data` 
CHANGE COLUMN `value` `value` LONGTEXT NOT NULL ,
CHANGE COLUMN `last_update_date` `last_update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP() ;

CREATE TABLE IF NOT EXISTS `swipez`.`sub_contract` (
  `sub_contract_id` INT(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` CHAR(10) NULL DEFAULT NULL,
  `vendor_id` INT(11) NOT NULL,
  `project_id` INT(11) NOT NULL,
  `sub_contract_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `title` VARCHAR(100) NULL DEFAULT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `default_retainage` VARCHAR(100) NULL DEFAULT NULL,
  `sub_contract_code` VARCHAR(100) NULL DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `sign` VARCHAR(100) NULL DEFAULT NULL,
  `description` VARCHAR(500) NULL DEFAULT NULL,
  `attachments` LONGTEXT NULL DEFAULT NULL,
  `particulars` LONGTEXT NOT NULL,
  `is_active` TINYINT(1) NULL DEFAULT 1,
  `created_by` VARCHAR(10) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT '2013-12-31 18:30:00',
  `last_update_by` VARCHAR(10) NOT NULL,
  `last_update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`sub_contract_id`),
  INDEX `IDX_PAYMENT_REQUEST_USER_ID` (`merchant_id` ASC) ,
  INDEX `IDX_PAYMENT_REQUEST_VENDOR_ID` (`vendor_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `swipez`.`subcontract_change_order` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_no` VARCHAR(45) NULL DEFAULT NULL,
  `order_desc` VARCHAR(120) NULL DEFAULT NULL,
  `merchant_id` CHAR(10) NULL DEFAULT NULL,
  `contract_id` INT(11) NOT NULL,
  `total_original_contract_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `total_change_order_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `order_date` DATE NOT NULL,
  `approved_date` DATE NULL DEFAULT NULL,
  `unapprove_message` VARCHAR(200) NULL DEFAULT NULL,
  `particulars` LONGTEXT NOT NULL,
  `status` VARCHAR(25) NOT NULL DEFAULT '1',
  `invoice_status` VARCHAR(45) NOT NULL DEFAULT '0',
  `bulk_id` INT(11) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_by` VARCHAR(10) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT '2013-12-31 18:30:00',
  `last_update_by` VARCHAR(10) NOT NULL,
  `last_update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`order_id`),
  INDEX `IDX_PAYMENT_REQUEST_USER_ID` (`merchant_id` ASC) ,
  INDEX `IDX_PAYMENT_REQUEST_CONTRACT_ID` (`contract_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `swipez`.`subcontract_payment_request` (
  `request_id` INT(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` CHAR(10) NULL DEFAULT NULL,
  `vendor_id` INT(11) NOT NULL,
  `absolute_cost` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `basic_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `invoice_total` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `grand_total` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `previous_due` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `advance_received` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `paid_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `retainage_amount` DECIMAL(11,2) NULL DEFAULT NULL,
  `retainage_store_material` DECIMAL(11,2) NULL DEFAULT NULL,
  `request_number` VARCHAR(45) NULL DEFAULT NULL,
  `payment_request_status` TINYINT(4) NOT NULL,
  `bill_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `narrative` VARCHAR(500) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `bulk_id` INT(11) NOT NULL DEFAULT 0,
  `notify_patron` TINYINT(1) NOT NULL DEFAULT 0,
  `notification_sent` TINYINT(1) NOT NULL DEFAULT 0,
  `short_url` VARCHAR(45) NOT NULL DEFAULT '',
  `document_url` VARCHAR(200) NULL DEFAULT NULL,
  `change_order_id` VARCHAR(100) NULL DEFAULT NULL,
  `currency` CHAR(3) NOT NULL DEFAULT 'INR',
  `sub_contract_id` INT(11) NOT NULL DEFAULT 0,
  `revision_no` VARCHAR(20) NULL DEFAULT NULL,
  `created_by` VARCHAR(10) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT '2013-12-31 18:30:00',
  `last_update_by` VARCHAR(10) NOT NULL,
  `last_update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`request_id`),
  INDEX `IDX_PAYMENT_REQUEST_VENDOR_ID` (`vendor_id` ASC) ,
  INDEX `IDX_PAYMENT_REQUEST_USER_ID` (`merchant_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `swipez`.`subcontract_request_payment_particular` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `subcontract_request_id` INT(11) NOT NULL,
  `pint` INT(11) NULL DEFAULT NULL,
  `sort_order` INT(11) NULL DEFAULT NULL,
  `bill_code` VARCHAR(100) NULL DEFAULT NULL,
  `description` VARCHAR(500) NULL DEFAULT NULL,
  `bill_type` VARCHAR(100) NULL DEFAULT NULL,
  `original_contract_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `approved_change_order_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `current_contract_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `previously_billed_percent` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `previously_billed_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `current_billed_percent` DOUBLE NOT NULL DEFAULT 0,
  `current_billed_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `total_billed` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `retainage_percent` DOUBLE NOT NULL DEFAULT 0,
  `retainage_amount_previously_withheld` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `retainage_amount_for_this_draw` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `retainage_percent_stored_materials` DOUBLE NOT NULL DEFAULT 0,
  `retainage_amount_previously_stored_materials` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `retainage_stored_materials_release_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `retainage_amount_stored_materials` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `net_billed_amount` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `retainage_release_amount` DOUBLE NOT NULL DEFAULT 0,
  `total_outstanding_retainage` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `stored_materials` DOUBLE NOT NULL DEFAULT 0,
  `previously_stored_materials` DOUBLE NOT NULL DEFAULT 0,
  `current_stored_materials` DOUBLE NOT NULL DEFAULT 0,
  `project` VARCHAR(100) NULL DEFAULT NULL,
  `cost_code` VARCHAR(100) NULL DEFAULT NULL,
  `cost_type` VARCHAR(100) NULL DEFAULT NULL,
  `group_code1` VARCHAR(100) NULL DEFAULT NULL,
  `group_code2` VARCHAR(100) NULL DEFAULT NULL,
  `group_code3` VARCHAR(100) NULL DEFAULT NULL,
  `group_code4` VARCHAR(100) NULL DEFAULT NULL,
  `group_code5` VARCHAR(100) NULL DEFAULT NULL,
  `calculated_perc` VARCHAR(45) NULL DEFAULT NULL,
  `calculated_row` VARCHAR(250) NULL DEFAULT NULL,
  `task_number` VARCHAR(45) NULL DEFAULT NULL,
  `group` VARCHAR(45) NULL DEFAULT NULL,
  `sub_group` VARCHAR(45) NULL DEFAULT NULL,
  `bill_code_detail` VARCHAR(3) NULL DEFAULT NULL,
  `attachments` LONGTEXT NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_by` VARCHAR(10) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT '2013-12-31 18:30:00',
  `last_update_by` VARCHAR(10) NOT NULL,
  `last_update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  INDEX `subcontract_request_idx` (`subcontract_request_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `swipez`.`project` 
ADD COLUMN `users` LONGTEXT NOT NULL AFTER `sequence_number`,
CHANGE COLUMN `last_update_date` `last_update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP() ;

ALTER TABLE `swipez`.`user` 
CHANGE COLUMN `custom_menu` `custom_menu` TINYINT(4) NOT NULL DEFAULT '0' ,
CHANGE COLUMN `last_updated_date` `last_updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP() ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;











truncate table menu;


INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('1', 'Dashboard', '0', '1', 'fa fa-home', '/merchant/dashboard', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-19 23:58:57');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('2', 'Contacts', '0', '2', 'fa fa-address-book-o', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-19 23:58:57');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('3', 'Contract management', '0', '3', 'fa fa-rocket', '/merchant/collect-payments', '1', '1', '2013-12-30 09:30:00', '1', '2023-04-11 06:56:26');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('4', 'Split payouts', '131', '101', 'fa fa-inr', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('5', 'Invoicing', '0', '5', 'fa fa-envelope', '1', '1', '2013-12-30 09:30:00', '1', '2023-04-11 06:56:26');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('6', 'Transactions', '0', '6', 'fa fa-money', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-19 23:58:57');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('7', 'Site Builder', '0', '7', 'fa fa-html5', '/merchant/website/start', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('8', 'Events', '0', '8', 'fa fa-bullhorn', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('9', 'Booking Calendar', '0', '10', 'fa fa-calendar', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('10', 'Promotions', '0', '11', 'fa fa-envelope', '0', '1', '2013-12-30 09:30:00', '1', '2022-03-28 06:51:24');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('11', 'Loyalty', '0', '12', 'fa fa-inr', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('12', 'GST', '0', '13', 'fa fa-percent', '0', '1', '2013-12-30 09:30:00', '1', '2022-11-03 14:03:35');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('13', 'Reports', '0', '14', 'fa fa-bar-chart', '/merchant/report', '1', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:40');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('14', 'Settings', '0', '15', 'fa fa-cogs', '/merchant/profile/settings', '1', '1', '2013-12-30 09:30:00', '1', '2020-10-14 20:07:27');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('15', 'Customer', '2', '100', 'fa fa-user', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('16', 'Vendors', '2', '100', 'fa fa-truck', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('17', 'Franchise', '2', '100', 'fa fa-line-chart', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('18', 'Invoice Formats', '3', '90', 'fa fa-file-text-o', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 18:57:54');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('19', 'Create Invoice', '3', '91', '', '/merchant/invoice/create', '0', '1', '2013-12-30 09:30:00', '1', '2022-03-30 01:36:10');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('20', 'Bulk Upload', '3', '93', '', '/merchant/bulkupload/newupload', '0', '1', '2013-12-30 09:30:00', '1', '2022-03-30 01:36:10');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('21', 'Create Subscription', '3', '94', '', '/merchant/subscription/create', '0', '1', '2013-12-30 09:30:00', '1', '2022-03-30 01:36:10');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('22', 'Payment links', '3', '95', '', '/merchant/directpaylink', '0', '1', '2013-12-30 09:30:00', '1', '2022-03-30 01:36:10');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('23', 'Initiate Transfer', '4', '100', '', '/merchant/vendor/transfer', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-13 17:31:58');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('24', 'Transfer Transactions', '4', '100', '', '/merchant/vendor/transferlist', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('25', 'Bulk Upload', '4', '101', '', '/merchant/vendor/bulkupload/transfer', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-13 17:33:12');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('26', 'Pending Settlements', '4', '100', '', '/merchant/franchise/pendingsettlement', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('27', 'Nodal Ledger Statements', '4', '100', '', '/merchant/franchise/nodalledger', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('28', 'Invoices', '5', '92', '', '/merchant/paymentrequest/viewlist', '1', '1', '2013-12-30 09:30:00', '1', '2023-05-03 10:26:26');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('29', 'Bulk Invoices', '5', '101', '', '/merchant/bulkupload/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2023-05-02 06:12:22');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('30', 'Bulk Upload Transactions', '5', '108', '', '/merchant/transaction/bulkupload', '0', '1', '2013-12-30 09:30:00', '1', '2023-05-03 10:26:26');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('31', 'Invoice', '6', '90', '', '/merchant/transaction/viewlist', '1', '1', '2013-12-30 09:30:00', '1', '2019-07-30 04:46:18');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('32', 'Website', '6', '92', '', '/merchant/transaction/xway', '1', '1', '2013-12-30 09:30:00', '1', '2019-07-30 04:46:18');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('33', 'Form Builder', '6', '93', '', '/merchant/transaction/xway/form', '1', '1', '2013-12-30 09:30:00', '1', '2019-07-30 04:46:18');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('34', 'Plans', '6', '94', '', '/merchant/transaction/xway/plan', '1', '1', '2013-12-30 09:30:00', '1', '2019-07-30 04:46:18');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('35', 'Create Events', '8', '100', '', '/merchant/event/create', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('36', 'Events list', '8', '100', '', '/merchant/event/viewlist', '1', '1', '2013-12-30 09:30:00', '1', '2020-10-26 15:00:35');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('37', 'Event Transactions', '8', '100', '', '/merchant/transaction/viewlist/event', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('38', 'Calendars', '9', '90', '', '/merchant/bookings/calendars', '1', '1', '2013-12-30 09:30:00', '1', '2020-04-16 19:13:41');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('39', 'Book Now', '9', '91', '', '', '1', '1', '2013-12-30 09:30:00', '1', '2020-04-16 19:13:41');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('40', 'Bookings', '9', '92', '', '', '1', '1', '2013-12-30 09:30:00', '1', '2020-04-16 19:13:41');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('41', 'Transactions', '9', '93', '', '/merchant/transaction/viewlist/booking', '1', '1', '2013-12-30 09:30:00', '1', '2020-04-16 19:13:41');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('42', 'Configuration', '9', '100', '', '', '1', '1', '2013-12-30 09:30:00', '1', '2020-10-15 14:49:25');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('43', 'Create Promotions', '10', '100', '', '/merchant/promotions/create', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('44', 'List Promotions', '10', '100', '', '/merchant/promotions/viewlist', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('45', 'Scan QR', '11', '100', '', '/merchant/loyalty/scanqrcode', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('46', 'Earned Points', '11', '100', '', '/merchant/loyalty/points/earn', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('47', 'Redeem Points', '11', '100', '', '/merchant/loyalty/points/redeem', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('48', 'Settings', '11', '100', '', '/merchant/loyalty/setting', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('49', 'GST Connection', '12', '100', '', '/merchant/gst/gstconnection', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('50', 'Invoices', '12', '100', '', '/merchant/gst/listinvoices', '1', '1', '2013-12-30 09:30:00', '1', '2020-11-26 16:19:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('51', 'GSTR 3B', '12', '100', '', '', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('52', 'GSTR 1', '12', '100', '', '/merchant/gst/gstr1upload', '1', '1', '2013-12-30 09:30:00', '1', '2020-11-26 18:40:40');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('53', 'GSTR 2', '12', '100', '', '/merchant/gst/viewlist/gstr2', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('54', 'Collections', '13', '100', 'fa fa-rupee', '', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:44:46');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('55', 'Invoicing', '13', '100', 'fa fa-file-text-o', '', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:44:46');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('56', 'Settlements', '13', '100', 'fa fa-bank', '', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:44:46');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('57', 'Form Builder', '13', '100', 'fa fa-files-o', '', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:44:46');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('58', 'General Settings', '14', '100', '', '/merchant/profile/setting', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('59', 'Suppliers', '14', '100', '', '/merchant/supplier/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('60', 'Products', '14', '100', '', '/merchant/product/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('61', 'Tax', '14', '100', '', '/merchant/tax/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('62', 'Plans', '14', '100', '', '/merchant/plan/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('63', 'Coupons', '14', '100', '', '/merchant/coupon/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('64', 'Roles', '14', '100', '', '/merchant/subuser/roles', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('65', 'Sub Merchants', '14', '100', '', '/merchant/subuser/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('66', 'Covering Notes', '14', '100', '', '/merchant/coveringnote/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('67', 'Structure', '15', '1', '', '/merchant/customer/structure', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-14 20:11:55');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('68', 'Create Customer', '15', '2', '', '/merchant/customer/create', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-24 22:46:29');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('69', 'Customer list', '15', '3', '', '/merchant/customer/viewlist', '1', '1', '2013-12-30 09:30:00', '1', '2021-09-07 00:42:19');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('70', 'Bulk Upload', '15', '4', '', '/merchant/customer/bulkupload', '1', '1', '2013-12-30 09:30:00', '1', '2019-11-26 11:31:28');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('71', 'Manage Group', '15', '5', '', '/merchant/customer/managegroup', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-24 22:46:29');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('72', 'Customer Logins', '15', '6', '', '/merchant/customer/register', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('73', 'Pending Approvals', '15', '7', '', '/merchant/approve/pending', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-24 22:46:29');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('74', 'Create Vendor', '16', '100', '', '/merchant/vendor/create', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('75', 'List Vendor', '16', '100', '', '/merchant/vendor/viewlist', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('76', 'Bulk Upload', '16', '100', '', '/merchant/vendor/bulkupload/vendor', '1', '1', '2013-12-30 09:30:00', '1', '2019-11-26 11:31:28');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('77', 'Create Franchise', '17', '100', '', '/merchant/franchise/create', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('78', 'View Franchise', '17', '100', '', '/merchant/franchise/viewlist', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('79', 'Bulk Upload', '17', '100', '', '/merchant/franchise/bulkupload', '1', '1', '2013-12-30 09:30:00', '1', '2019-11-26 11:31:28');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('80', 'Create Format', '18', '100', '', '/merchant/template/newtemplate', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 18:58:07');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('81', 'List Format', '18', '100', '', '/merchant/template/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 18:58:07');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('82', 'Slots', '39', '100', '', '/merchant/bookings/selectslot', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('83', 'Membership', '39', '100', '', '/merchant/bookings/bookmembership', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('84', 'Slots', '40', '100', '', '/merchant/bookings/reservations', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('85', 'Membership', '40', '100', '', '/merchant/bookings/membershipdetails', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('86', 'Categories', '42', '100', '', '/merchant/bookings/categories', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('87', 'Membership', '42', '100', '', '/merchant/bookings/membership', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('88', 'Prepare Summary', '51', '100', '', '/merchant/gst/gst3b', '1', '1', '2013-12-30 09:30:00', '1', '2020-11-26 15:46:36');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('89', 'Save Summary', '51', '100', '', '/merchant/gst/gst3bupload', '1', '1', '2013-12-30 09:30:00', '1', '2020-11-26 15:44:11');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('90', 'Prepare GSTR 1', '52', '100', '', '/merchant/gst/gstr1upload', '0', '1', '2013-12-30 09:30:00', '1', '2020-11-26 18:39:58');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('91', 'Save GSTR1', '52', '100', '', '/merchant/gst/gstdraft', '0', '1', '2013-12-30 09:30:00', '1', '2020-11-26 18:39:58');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('92', 'Submit GSTR1', '52', '100', '', '/merchant/gst/gstsubmit', '0', '1', '2013-12-30 09:30:00', '1', '2020-11-26 18:39:58');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('93', 'Payment Received', '54', '95', '', '/merchant/report/paymentsreceived', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('94', 'Website Payment Received', '54', '96', '', '/merchant/report/websitepaymentsreceived', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('95', 'Plan Payment Received', '54', '97', '', '/merchant/report/websitepaymentsreceived/plan', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('96', 'Ledger Reports', '54', '100', '', '/merchant/report/ledger', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('97', 'TDRs', '54', '100', '', '/merchant/report/payment_tdr', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('98', 'Coupon Analytics', '54', '100', '', '/merchant/report/couponanalytics', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('99', 'Invoice Details', '55', '100', '', '/merchant/report/invoicedetails', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('100', 'Estimate Details', '55', '100', '', '/merchant/report/invoicedetails/estimate', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('101', 'Aging Summary', '55', '100', '', '/merchant/report/agingsummary', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('102', 'Aging Details', '55', '100', '', '/merchant/report/agingdetails', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('103', 'Payment Expected', '55', '100', '', '/merchant/report/paymentexpected', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('104', 'Tax Summary', '55', '100', '', '/merchant/report/taxsummary', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('105', 'Tax Details', '55', '100', '', '/merchant/report/taxdetails', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('106', 'Settlement Summary', '56', '100', '', '/merchant/report/payment_settlement_summary', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('107', 'Settlement Details', '56', '100', '', '/merchant/report/payment_settlement_details', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('108', 'Refund Details', '56', '100', '', '/merchant/report/refunddetails', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('109', 'Form Builder Data', '57', '100', '', '/merchant/report/formbuilder', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('110', 'Cable', '0', '9', 'fa fa-television', '', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('111', 'Set Top Box', '110', '100', '', '/merchant/cable/settopboxlist', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('112', 'Customer Packages', '110', '100', '', '/merchant/cable/packagelist', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('113', 'Subscription', '110', '100', '', '/merchant/cable/subscription', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('114', 'Settings', '110', '100', '', '/merchant/cable/setting', '1', '1', '2013-12-30 09:30:00', '1', '2019-06-20 04:07:04');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('115', 'Payments Due', '0', '2', 'icon-drawer', '/patron/paymentrequest/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('116', 'Receipts', '0', '3', 'icon-credit-card', '/patron/transaction/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('117', 'Access Keys', '14', '100', '', '/merchant/profile/accesskey', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('118', 'Subscriptions', '5', '100', '', '/merchant/subscription/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2023-05-02 06:12:06');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('119', 'Search My Bills', '0', '18', 'icon-anchor', '/mybills', '0', '1', '2013-12-30 15:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('120', 'Suggest a Merchant', '0', '17', 'icon-user', '/patron/profile/suggest', '0', '1', '2013-12-30 15:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('121', 'Cable Packages', '0', '12', 'fa fa-television', '/cable/settopbox', '0', '1', '2013-12-30 15:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('122', 'Create Estimate', '3', '92', '/merchant/invoice/create/estimate', '0', '1', '2013-12-30 15:00:00', '1', '2022-03-30 01:36:10');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('123', 'Payment links', '6', '91', '/merchant/transaction/xway/directpay', '1', '1', '2013-12-30 15:00:00', '1', '2020-11-18 15:03:09');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('124', 'Move Invoices', '50', '100', '/merchant/gst/moveinvoices', '0', '1', '2013-12-30 15:00:00', '1', '2020-11-26 16:18:19');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('125', 'Upload invoices', '50', '101', '/merchant/gst/bulkupload', '0', '1', '2013-12-30 15:00:00', '1', '2020-11-26 16:18:19');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('126', 'List GST Invoices', '50', '102', '/merchant/gst/listinvoices', '0', '1', '2013-12-30 15:00:00', '1', '2020-11-26 16:18:19');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('127', 'Beneficiaries', '2', '100', 'fa fa-users', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('128', 'Create Beneficiary', '127', '100', '', '/merchant/beneficiary/create', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('129', 'List beneficiary', '127', '100', '', '/merchant/beneficiary/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('130', 'Bulk Upload', '127', '100', '', '/merchant/vendor/bulkupload/beneficiary', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('131', 'Payout', '0', '4', 'fa fa-inr', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('132', 'Initiate transfer', '131', '100', '/merchant/payout/transfer', '0', '1', '2013-12-30 20:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('133', 'Transactions', '131', '100', '/merchant/payout/transaction', '0', '1', '2013-12-30 20:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('134', 'Bulk transfer\n', '131', '100', '/merchant/vendor/bulkupload/payout', '0', '1', '2013-12-30 20:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('135', 'Nodal Account', '131', '100', '/merchant/payout/nodal', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('136', 'Auto collect', '0', '100', 'icon-credit-card', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('137', 'Plans', '136', '100', '/merchant/autocollect/plans', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('138', 'Subscriptions', '136', '100', '/merchant/autocollect/subscriptions', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('139', 'Transactions', '136', '100', '/merchant/autocollect/transactions', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('140', 'Landing Page', '42', '100', '/merchant/bookings/setting', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('141', 'Direct Payment Received', '54', '98', '', '/merchant/report/websitepaymentsreceived/directpay', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('142', 'Cashgram', '131', '100', '', '/merchant/cashgram/list', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('143', 'Purchases', '0', '13', 'fa fa-inr', '0', '1', '2013-12-31 02:00:00', '1', '2022-11-03 14:03:36');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('144', 'Expense', '143', '97', '', '0', '1', '2020-04-01 18:02:15', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('145', 'Purchase order', '143', '98', '', '0', '1', '2020-04-01 18:02:15', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('146', 'Category', '143', '100', '/merchant/expense/category', '0', '1', '2020-04-01 18:02:15', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('147', 'Department', '143', '100', '/merchant/expense/department', '0', '1', '2020-04-01 18:02:15', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('148', 'Create', '144', '97', '/merchant/expense/create', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('149', 'Create', '145', '100', '/merchant/expense/po/create', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('150', 'List', '144', '98', '/merchant/expense/viewlist/expense', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('151', 'List', '145', '100', '/merchant/expense/viewlist/po', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('152', 'Expense', '13', '100', 'fa fa-rupee', '', '0', '1', '2013-12-30 09:30:00', '1', '2020-10-19 12:44:46');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('153', 'Expense', '152', '100', '/merchant/report/expense', '0', '1', '2013-12-31 02:00:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('154', 'Bulk upload', '144', '100', '/merchant/expense/bulkupload', '0', '1', '2020-04-01 18:02:15', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('155', 'Purchase order', '152', '100', '/merchant/report/expense/po', '0', '1', '2013-12-31 02:00:00', '1', '2020-10-19 12:43:00');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('156', 'QR code scanner', '8', '101', '/merchant/directpaylink/qrcode/event', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('157', 'QR code scanner', '9', '99', '/merchant/directpaylink/qrcode/booking', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('158', 'Lookup', '0', '100', 'fa fa-user', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('159', 'Registered Users', '158', '100', '', '/merchant/lookup/registration', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('160', 'Credit Note', '5', '101', '', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('161', 'Create', '176', '100', '/merchant/debitnote/create', '0', '1', '2020-04-01 18:02:15', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('162', 'List', '176', '100', '/merchant/debitnote/viewlist', '0', '1', '2020-04-01 18:02:15', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('163', 'Create', '160', '100', '/merchant/creditnote/create', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('164', 'Debit note', '161', '100', '/merchant/debitnote/create', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('165', 'List', '160', '100', '/merchant/creditnote/viewlist', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('166', 'Debit note', '162', '100', '/merchant/debitnote/viewlist', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('167', 'Move Notes', '50', '100', '/merchant/gst/movenotes', '0', '1', '2013-12-30 15:00:00', '1', '2020-11-26 16:18:19');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('168', 'Digital Signature', '14', '100', '', '/merchant/profile/digitalsignature', '0', '1', '2013-12-30 09:30:00', '1', '2020-09-28 19:43:38');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('169', 'Inbox', '143', '99', '/merchant/expense/pending', '0', '1', '2013-12-31 02:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('170', 'Pay my bills', '0', '13', 'fa fa-thumbs-up', '/merchant/pay-my-bills', '0', '1', '2021-02-28 07:30:00', '1', '2022-11-03 14:04:32');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('171', 'Inventory', '0', '13', 'fa fa-shopping-cart', '/merchant/product/dashboard', '0', '1', '2013-12-31 07:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `hindi_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('172', 'GSTR 2B Reconciliation', '', '12', '100', '', '/merchant/gst/reconciliation', '0', '', '2013-12-31 07:30:00', '', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('173', 'Benefits', '0', '13', 'fa fa-thumbs-up', '/merchant/benefits', '0', '1', '2021-02-28 07:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('174', 'Messaging', '15', '100', '', '/merchant/promotions/viewlist', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('175', 'E-Invoices', '5', '102', '', '/merchant/einvoice/list', '0', '1', '2013-12-30 09:30:00', '1', '2023-05-02 06:11:15');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('176', 'Debit Note', '143', '98', '', '0', '1', '2013-12-31 13:00:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('177', 'Cancellations', '9', '100', '', '/merchant/transaction/booking/cancellations', '0', '1', '2013-12-30 09:30:00', '1', '2023-03-13 12:18:20');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('179', 'Contracts', '5', '90', '', '/merchant/contract/list', '1', '1', '2013-12-30 09:30:00', '1', '2023-05-03 10:26:26');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('180', 'Change Orders', '5', '91', '', '/merchant/order/list', '1', '1', '2013-12-30 09:30:00', '1', '2023-05-03 10:26:26');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('181', 'Request for Payment', '5', '95', '/merchant/subcontract/requestpayment/list', '1', '1', '2013-12-31 18:30:00', '1', '2023-05-03 10:26:27');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `icon`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('182', 'Sub Contract', '5', '93', '', '/merchant/sub-contracts', '1', '1', '2013-12-30 09:30:00', '1', '2023-05-03 10:26:27');
INSERT INTO `swipez`.`menu` (`id`, `eng_title`, `parent_id`, `seq`, `link`, `is_active`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ('183', 'Sub Contract Change Order', '5', '94', '/merchant/order/list/subcontract', '1', '1', '2013-12-31 18:30:00', '1', '2023-05-03 10:26:27');


UPDATE `swipez`.`menu` SET `eng_title` = 'Subcontract' WHERE (`id` = '182');
UPDATE `swipez`.`menu` SET `eng_title` = 'Subcontract Change Order' WHERE (`id` = '183');
UPDATE `swipez`.`menu` SET `eng_title` = 'Subcontract COs' WHERE (`id` = '183');

