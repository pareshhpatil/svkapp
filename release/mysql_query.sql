CREATE TABLE `invoice_draft` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_request_id` char(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` varchar(10) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT '2013-12-31 18:30:00',
  `last_update_by` varchar(10) NOT NULL,
  `last_update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



CREATE TABLE `staging_invoice_construction_particular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `draft_id` int(11) NOT NULL,
  `payment_request_id` char(10) NOT NULL,
  `pint` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `bill_code` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `bill_type` varchar(100) DEFAULT NULL,
  `original_contract_amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `approved_change_order_amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `current_contract_amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `previously_billed_percent` decimal(11,2) NOT NULL DEFAULT 0.00,
  `previously_billed_amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `current_billed_percent` double NOT NULL DEFAULT 0,
  `current_billed_amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `total_billed` decimal(11,2) NOT NULL DEFAULT 0.00,
  `retainage_percent` double NOT NULL DEFAULT 0,
  `retainage_amount_previously_withheld` decimal(11,2) NOT NULL DEFAULT 0.00,
  `retainage_amount_for_this_draw` decimal(11,2) NOT NULL DEFAULT 0.00,
  `retainage_percent_stored_materials` double NOT NULL DEFAULT 0,
  `retainage_amount_previously_stored_materials` decimal(11,2) NOT NULL DEFAULT 0.00,
  `retainage_stored_materials_release_amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `retainage_amount_stored_materials` decimal(11,2) NOT NULL DEFAULT 0.00,
  `net_billed_amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `retainage_release_amount` double NOT NULL DEFAULT 0,
  `total_outstanding_retainage` decimal(11,2) NOT NULL DEFAULT 0.00,
  `stored_materials` double NOT NULL DEFAULT 0,
  `previously_stored_materials` double NOT NULL DEFAULT 0,
  `current_stored_materials` double NOT NULL DEFAULT 0,
  `project` varchar(100) DEFAULT NULL,
  `cost_code` varchar(100) DEFAULT NULL,
  `cost_type` varchar(100) DEFAULT NULL,
  `group_code1` varchar(100) DEFAULT NULL,
  `group_code2` varchar(100) DEFAULT NULL,
  `group_code3` varchar(100) DEFAULT NULL,
  `group_code4` varchar(100) DEFAULT NULL,
  `group_code5` varchar(100) DEFAULT NULL,
  `calculated_perc` varchar(45) DEFAULT NULL,
  `calculated_row` varchar(250) DEFAULT NULL,
  `billed_transaction_ids` varchar(100) DEFAULT NULL,
  `group` varchar(45) DEFAULT NULL,
  `sub_group` varchar(45) DEFAULT NULL,
  `bill_code_detail` varchar(3) DEFAULT NULL,
  `attachments` longtext DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` varchar(10) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT '2013-12-31 18:30:00',
  `last_update_by` varchar(10) NOT NULL,
  `last_update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `payment_request_id_idx` (`payment_request_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
