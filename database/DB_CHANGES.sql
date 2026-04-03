CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL COMMENT 'Path under storage/app/public, e.g. site/xxx.png',
  `favicon_path` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `whatsapp` varchar(64) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `website_url` varchar(191) DEFAULT NULL,
  `facebook_url` varchar(191) DEFAULT NULL,
  `instagram_url` varchar(191) DEFAULT NULL,
  `youtube_url` varchar(191) DEFAULT NULL,
  `linkedin_url` varchar(191) DEFAULT NULL,
  `twitter_url` varchar(191) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default row only if empty
INSERT INTO `site_settings` (
  `site_name`, `tagline`, `email`, `phone`, `whatsapp`, `address`, `website_url`, `created_at`, `updated_at`
)
SELECT
  'PropUpdate',
  'Update your property search',
  'info@propupdate.com',
  '7204362646',
  '917204362646',
  'North Bangalore, Karnataka, India',
  'https://www.propupdate.com',
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` LIMIT 1);

