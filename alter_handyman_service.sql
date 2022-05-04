--
-- Table structure for table `service_faqs`
--

CREATE TABLE `service_faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `service_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint DEFAULT '1' COMMENT '1- Active , 0- InActive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Indexes for table `service_faqs`
--
ALTER TABLE `service_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_faqs_service_id_foreign` (`service_id`);

--
-- AUTO_INCREMENT for table `service_faqs`
--
ALTER TABLE `service_faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for table `service_faqs`
--
ALTER TABLE `service_faqs`
  ADD CONSTRAINT `service_faqs_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47, '2022_03_03_091112_create_service_faqs_table', 7);

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trial_period` bigint DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

INSERT INTO `plans` (`id`, `title`, `identifier`, `type`, `trial_period`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Free plan', 'free', 'weekly', 7, 0, 1, '2022-03-10 05:56:15', '2022-03-10 05:56:15'),
(2, 'Basic plan', 'basic', 'monthly', NULL, 10, 1, '2022-03-10 05:56:15', '2022-03-10 05:56:15'),
(3, 'Premium plan', 'premium', 'yearly', NULL, 100, 1, '2022-03-10 05:56:15', '2022-03-10 05:56:15');


INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48, '2022_03_10_055017_create_plans_table', 7);


CREATE TABLE `provider_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_taxes`
--
--
-- Indexes for table `provider_subscriptions`
--
ALTER TABLE `provider_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_subscriptions_plan_id_foreign` (`plan_id`);

--
-- AUTO_INCREMENT for table `provider_subscriptions`
--
ALTER TABLE `provider_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;


--
-- Constraints for table `provider_subscriptions`
--
ALTER TABLE `provider_subscriptions`
  ADD CONSTRAINT `provider_subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49, '2022_03_10_064650_create_provider_subscriptions_table', 7);


--
-- Table structure for table `subscription_transactions`
--

CREATE TABLE `subscription_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_plan_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `payment_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `txn_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pending, paid , failed',
  `other_transaction_detail` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

--
-- Indexes for table `subscription_transactions`
--
ALTER TABLE `subscription_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_transactions_subscription_plan_id_foreign` (`subscription_plan_id`);

--
-- AUTO_INCREMENT for table `subscription_transactions`
--
ALTER TABLE `subscription_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;


--
-- Constraints for table `subscription_transactions`
--
ALTER TABLE `subscription_transactions`
  ADD CONSTRAINT `subscription_transactions_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `provider_subscriptions` (`id`) ON DELETE CASCADE;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50, '2022_03_10_101132_create_subscription_transactions_table', 7);


ALTER TABLE `users`  ADD `is_subscribe` BIGINT UNSIGNED NULL DEFAULT 0;    
ALTER TABLE `app_settings`  ADD `time_zone` VARCHAR(50)  NULL ;    
ALTER TABLE `app_settings`  ADD `earning_type` VARCHAR(50)  NULL ;    
