
CREATE TABLE `payment_gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT '1' COMMENT '1- Active , 0- InActive',
  `is_test` tinyint DEFAULT '1' COMMENT '1- Yes , 0- No',
  `value` text COLLATE utf8mb4_unicode_ci,
  `live_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES  (42, '2021_12_25_102810_create_payment_gateways_table', 6);
--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `nickname` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `url_params` text COLLATE utf8mb4_unicode_ci,
  `icon` text COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint DEFAULT NULL,
  `menu_order` bigint DEFAULT NULL,
  `permission` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--
INSERT INTO `menus` (`id`, `title`, `nickname`, `url`, `url_params`, `icon`, `parent_id`, `menu_order`, `permission`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'dashboard', 'home', NULL, '<i class=\"ri-dashboard-line\"></i>', 0, 1, NULL, 1, '2022-02-03 06:08:15', NULL),
(2, 'Category', 'category', NULL, NULL, '<i class=\"ri-shopping-basket-2-line\"></i>', 0, 2, 'category list', 1, '2022-02-03 06:08:15', NULL),
(3, 'Category List', 'categorylist', 'category.index', NULL, '<i class=\"ri-list-unordered\"></i>', 2, NULL, 'category list', 1, '2022-02-03 06:08:15', NULL),
(4, 'Category Add', 'categoryadd', 'category.create', NULL, '<i class=\"ri-add-box-line\"></i>', 2, NULL, 'category add', 1, '2022-02-03 06:08:15', NULL),
(5, 'Service', 'service', NULL, NULL, '<i class=\"ri-service-line\"></i>', 0, 3, 'service list', 1, '2022-02-03 06:08:15', NULL),
(6, 'Service List', 'servicelist', 'service.index', NULL, '<i class=\"ri-list-unordered\"></i>', 5, NULL, 'service list', 1, '2022-02-03 06:08:15', NULL),
(7, 'Service Add', 'serviceadd', 'service.create', NULL, '<i class=\"ri-add-box-line\"></i>', 5, NULL, 'service add', 1, '2022-02-03 06:08:15', NULL),
(8, 'Provider', 'provider', NULL, NULL, '<i class=\"la la-users\"></i>', 0, 4, 'provider list', 1, '2022-02-03 06:08:15', NULL),
(9, 'Provider List', 'providerlist', 'provider.index', NULL, '<i class=\"ri-list-unordered\"></i>', 8, NULL, 'service list', 1, '2022-02-03 06:08:15', NULL),
(10, 'Pending Provider', 'providerpending', 'provider.pending', 'pending', '<i class=\"ri-list-unordered\"></i>', 8, NULL, 'provider pending', 1, '2022-02-03 06:08:15', NULL),
(11, 'Payout History', 'providerhistory', 'providerpayout.index', NULL, '<i class=\"fas fa-exchange-alt\"></i>', 8, NULL, 'admin,provider', 1, '2022-02-03 06:08:15', NULL),
(12, 'Document', 'document', NULL, NULL, '<i class=\"ri-shopping-basket-2-line\"></i>', 0, 5, 'document list', 1, '2022-02-03 06:08:15', NULL),
(13, 'Document List', 'documentlist', 'document.index', NULL, '<i class=\"ri-list-unordered\"></i>', 12, NULL, 'document list', 1, '2022-02-03 06:08:15', NULL),
(14, 'Document Add', 'documentadd', 'document.create', NULL, '<i class=\"ri-add-box-line\"></i>', 12, NULL, 'document add', 1, '2022-02-03 06:08:15', NULL),
(15, 'Booking', 'booking', 'booking.index', NULL, '<i class=\"ri-list-unordered\"></i>', 0, 6, 'booking list', 1, '2022-02-03 06:08:15', NULL),
(16, 'Tax', 'tax', 'tax.index', NULL, '<i class=\"fas fa-percent\"></i>', 0, 7, 'admin', 1, '2022-02-03 06:08:15', NULL),
(17, 'Earning', 'earning', 'earning', NULL, '<i class=\"fas fa-money-bill-alt\"></i>', 0, 8, 'admin', 1, '2022-02-03 06:08:15', NULL),
(18, 'Handyman', 'handyman', NULL, NULL, '<i class=\"las la-user-friends\"></i>', 0, 9, 'handyman list', 1, '2022-02-03 06:08:15', NULL),
(19, 'Handyman List', 'handymanlist', 'handyman.index', NULL, '<i class=\"ri-list-unordered\"></i>', 18, NULL, 'handyman list', 1, '2022-02-03 06:08:15', NULL),
(20, 'Handyman Pending List', 'handymanpending', 'handyman.pending', 'pending', '<i class=\"ri-list-unordered\"></i>', 18, NULL, 'handyman pending', 1, '2022-02-03 06:08:15', NULL),
(21, 'Users', 'users', 'user.index', NULL, '<i class=\"ri-list-unordered\"></i>', 0, 10, 'user list', 1, '2022-02-03 06:08:15', NULL),
(22, 'Coupon', 'coupon', NULL, NULL, '<i class=\"ri-coupon-fill\"></i>', 0, 11, 'coupon list', 1, '2022-02-03 06:08:15', NULL),
(23, 'Coupon List', 'couponlist', 'coupon.index', NULL, '<i class=\"ri-list-unordered\"></i>', 22, NULL, 'coupon list', 1, '2022-02-03 06:08:15', NULL),
(24, 'Coupon Add', 'couponadd', 'coupon.create', NULL, '<i class=\"ri-add-box-line\"></i>', 22, NULL, 'coupon add', 1, '2022-02-03 06:08:15', NULL),
(25, 'Payment', 'payment', 'payment.index', NULL, '<i class=\"ri-secure-payment-line\"></i>', 0, 12, 'payment list', 1, '2022-02-03 06:08:15', NULL),
(26, 'Slider', 'slider', NULL, NULL, '<i class=\"ri-slideshow-line\"></i>', 0, 13, 'slider list', 1, '2022-02-03 06:08:15', NULL),
(27, 'Slider List', 'sliderlist', 'slider.index', NULL, '<i class=\"ri-list-unordered\"></i>', 26, NULL, 'slider list', 1, '2022-02-03 06:08:15', NULL),
(28, 'Slider Add', 'slideradd', 'slider.index', NULL, '<i class=\"ri-add-box-line\"></i>', 26, NULL, 'slider add', 1, '2022-02-03 06:08:15', NULL),
(29, 'Account Setting', 'account_setting', NULL, NULL, '<i class=\"ri-list-settings-line\"></i>', 0, 14, 'role list,permission list', 1, '2022-02-03 06:08:15', NULL),
(30, 'Role List', 'rolelist', 'role.index', NULL, '<i class=\"ri-list-unordered\"></i>', 29, NULL, 'role list', 1, '2022-02-03 06:08:15', NULL),
(31, 'Permission List', 'permissionlist', 'permission.index', NULL, '<i class=\"ri-add-box-line\"></i>', 29, NULL, 'permission list', 1, '2022-02-03 06:08:15', NULL),
(32, 'Pages', 'pages', NULL, NULL, '<i class=\"ri-pages-line\"></i>', 0, 15, 'pages', 1, '2022-02-03 06:08:15', NULL),
(33, 'Terms Condition', 'termscondition', 'term-condition', NULL, '<i class=\"fas fa-file-contract\"></i>', 32, NULL, 'terms condition', 1, '2022-02-03 06:08:15', NULL),
(34, 'Privacy Policy', 'privacypolicy', 'privacy-policy', NULL, '<i class=\"ri-file-shield-2-line\"></i>', 32, NULL, 'privacy policy', 1, '2022-02-03 06:08:15', NULL),
(35, 'Setting', 'setting', 'setting.index', NULL, '<i class=\"ri-settings-2-line\"></i>', 0, 16, 'system setting', 1, '2022-02-03 06:08:15', NULL),
(36, 'Provider Type List', 'providertype list', 'providertype.index', NULL, '<i class=\"ri-file-list-3-line\"></i>', 8, NULL, 'providertype list', 1, '2022-02-03 06:08:15', NULL),
(37, 'Address List', 'address List', 'provideraddress.index', NULL, '<i class=\"ri-file-list-3-line\"></i>', 8, NULL, 'address list', 1, '2022-02-03 06:08:15', NULL),
(38, 'Provider Document List', 'document List', 'providerdocument.index', NULL, '<i class=\"ri-file-list-3-line\"></i>', 8, NULL, 'providerdocument list', 1, '2022-02-03 06:08:15', NULL),
(39, 'Handyman Earning', 'handyman earning', 'handymanEarning', NULL, '<i class=\"fas fa-money-bill-alt\"></i>', 18, NULL, 'provider', 1, '2022-02-03 06:08:15', NULL),
(40, 'Handyman Type List', 'handymantype list', 'handymantype.index', NULL, '<i class=\"ri-file-list-3-line\"></i>', 18, NULL, 'provider', 1, '2022-02-03 06:08:15', NULL),
(41, 'Handyman Payout List', 'handymanpayout list', 'handymanpayout.index', NULL, '<i class=\"fas fa-exchange-alt\"></i>', 18, NULL, 'handyman,provider', 1, '2022-02-03 06:08:15', NULL);

-- --------------------------------------------------------
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES  (43, '2022_02_02_073806_create_menus_table', 6);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- I
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `migrations`
--
-- --------------------------------------------------------

--
-- Table structure for table `handyman_payouts`
--

CREATE TABLE `handyman_payouts` (
  `id` bigint UNSIGNED NOT NULL,
  `handyman_id` bigint UNSIGNED DEFAULT NULL,
  `payment_method` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `amount` double DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------


INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES  (44, '2022_02_12_064817_create_handyman_payouts_table', 6);
--
-- Indexes for table `handyman_payouts`
--
ALTER TABLE `handyman_payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `handyman_payouts_handyman_id_foreign` (`handyman_id`);

  --
-- AUTO_INCREMENT for table `handyman_payouts`
--
ALTER TABLE `handyman_payouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `handyman_payouts`
--
ALTER TABLE `handyman_payouts`
  ADD CONSTRAINT `handyman_payouts_handyman_id_foreign` FOREIGN KEY (`handyman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

  -- --------------------------------------------------------

--
-- Table structure for table `handyman_types`
--

CREATE TABLE `handyman_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission` double DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `handyman_types` (`id`, `name`, `commission`, `type`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Company', 20, 'percent', 1, NULL, '2021-02-14 06:13:51', NULL),
(2, 'Freelance', 5, 'fixed', 1, NULL, '2021-02-14 06:28:53', NULL);

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45, '2022_02_14_051023_create_handyman_types_table', 6);
--
-- Indexes for table `handyman_types`
--
ALTER TABLE `handyman_types`
  ADD PRIMARY KEY (`id`);

  --
-- AUTO_INCREMENT for table `handyman_types`
--
ALTER TABLE `handyman_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `users`  ADD `handymantype_id` BIGINT UNSIGNED NULL;    

