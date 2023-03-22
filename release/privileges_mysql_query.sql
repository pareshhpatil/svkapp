-- create briq roles table
CREATE TABLE `briq_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_updated_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `merchant_cost_types_idx` (`merchant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4984 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- create briq privileges table
CREATE TABLE `briq_privileges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merchant_id` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rule_engine_query` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_index` (`type`),
  KEY `type_id_index` (`type_id`),
  KEY `user_id_index` (`user_id`),
  KEY `merchant_id_index` (`merchant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- create briq user roles table
CREATE TABLE `briq_user_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id_index` (`role_id`),
  KEY `user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5080 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- create laravel notifications table
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- add send_push in prefrence table for dashboard notification
ALTER TABLE preferences ADD COLUMN send_push TINYINT(1) DEFAULT 1 AFTER send_app;

-- add fcm_token in user table for firebase
ALTER TABLE user ADD COLUMN fcm_token varchar(255) AFTER registered_from;

-- Add payment_request_status 14 saved
INSERT INTO `config` (`config_type`, `config_key`, `config_value`, `description`, `created_by`, `created_date`, `last_update_by`, `last_update_date`)
VALUES
	('payment_request_status', 14, 'Saved', 'Payment request saved', 'E14010102', '2023-02-16 11:57:35', 'E14010102', '2023-02-16 16:29:12');