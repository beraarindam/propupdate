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

-- CMS pages (Home, About, Contact, Privacy, Terms) — mirror of migration 2026_04_03_140000
CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `banner_title` varchar(255) DEFAULT NULL,
  `banner_lead` text DEFAULT NULL,
  `banner_image_url` text DEFAULT NULL,
  `body_html` longtext DEFAULT NULL,
  `extras` json DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pages` (
  `slug`, `name`, `meta_title`, `meta_description`, `meta_keywords`,
  `banner_title`, `banner_lead`, `banner_image_url`, `body_html`, `extras`, `is_published`, `created_at`, `updated_at`
)
SELECT * FROM (
  SELECT 'home' AS slug, 'Home' AS name, 'PropUpdate Realty — Bangalore resale & new launches' AS meta_title,
    'PropUpdate Realty helps you buy the right property in Bangalore with transparent guidance on resale, new launches, and investments.' AS meta_description,
    'PropUpdate, Bangalore real estate, resale, new launch, property' AS meta_keywords,
    NULL AS banner_title, NULL AS banner_lead, NULL AS banner_image_url, NULL AS body_html,
    JSON_OBJECT(
      'hero', JSON_OBJECT(
        'line1', 'Update your property search with',
        'line2', 'PropUpdate Realty',
        'subtitle', 'where decisions are informed, not influenced',
        'bg_url', 'https://images.unsplash.com/photo-1486325212027-8081e485255e?auto=format&fit=crop&w=1920&q=80',
        'search_placeholder', 'Location | Project | Builder'
      )
    ) AS extras,
    1 AS is_published, NOW() AS created_at, NOW() AS updated_at
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `pages` WHERE `slug` = 'home' LIMIT 1);

INSERT INTO `pages` (
  `slug`, `name`, `meta_title`, `meta_description`, `meta_keywords`,
  `banner_title`, `banner_lead`, `banner_image_url`, `body_html`, `extras`, `is_published`, `created_at`, `updated_at`
)
SELECT * FROM (
  SELECT 'about-us', 'About us', 'About PropUpdate Realty',
    'Learn how PropUpdate Realty serves buyers and investors in Bangalore with transparent resale and launch guidance.',
    'About PropUpdate, Bangalore realtor, property advisory',
    'About PropUpdate',
    'Where every property decision is <strong>informed</strong>, not influenced — serving serious buyers and investors across Bangalore.',
    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80',
    NULL, NULL, 1, NOW(), NOW()
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `pages` WHERE `slug` = 'about-us' LIMIT 1);

INSERT INTO `pages` (
  `slug`, `name`, `meta_title`, `meta_description`, `meta_keywords`,
  `banner_title`, `banner_lead`, `banner_image_url`, `body_html`, `extras`, `is_published`, `created_at`, `updated_at`
)
SELECT * FROM (
  SELECT 'contact', 'Contact us', 'Contact PropUpdate Realty',
    'Contact PropUpdate for resale, new launches, and investment property enquiries in Bangalore.',
    'Contact PropUpdate, Bangalore property enquiry',
    'Contact us',
    'Questions about <strong>resale</strong>, <strong>launches</strong>, or <strong>investments</strong>? We reply within one business day.',
    'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?auto=format&fit=crop&w=1920&q=80',
    '<p>Share your brief — budget, locality, timeline — and we’ll route you to the right specialist.</p>', NULL, 1, NOW(), NOW()
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `pages` WHERE `slug` = 'contact' LIMIT 1);

INSERT INTO `pages` (
  `slug`, `name`, `meta_title`, `meta_description`, `meta_keywords`,
  `banner_title`, `banner_lead`, `banner_image_url`, `body_html`, `extras`, `is_published`, `created_at`, `updated_at`
)
SELECT * FROM (
  SELECT 'privacy-policy', 'Privacy policy', 'Privacy policy — PropUpdate Realty',
    'How PropUpdate Realty collects, uses, and protects your personal information.',
    'privacy policy, PropUpdate, data protection',
    'Privacy policy',
    'How PropUpdate Realty collects, uses, and protects your <strong>personal information</strong> on this website.',
    'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1920&q=80',
    NULL, NULL, 1, NOW(), NOW()
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `pages` WHERE `slug` = 'privacy-policy' LIMIT 1);

INSERT INTO `pages` (
  `slug`, `name`, `meta_title`, `meta_description`, `meta_keywords`,
  `banner_title`, `banner_lead`, `banner_image_url`, `body_html`, `extras`, `is_published`, `created_at`, `updated_at`
)
SELECT * FROM (
  SELECT 'terms-and-conditions', 'Terms & conditions', 'Terms & conditions — PropUpdate Realty',
    'Rules for using the PropUpdate Realty website and information services.',
    'terms, conditions, PropUpdate',
    'Terms & conditions',
    'Rules for using this website and our <strong>information services</strong>. Please read before you submit enquiries or rely on published content.',
    'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1920&q=80',
    NULL, NULL, 1, NOW(), NOW()
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `pages` WHERE `slug` = 'terms-and-conditions' LIMIT 1);

-- FAQs (homepage accordion)
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `faqs` (`question`, `answer`, `sort_order`, `is_published`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 'What areas in Bangalore do you cover?' AS question,
    'We focus on North Bangalore and key growth corridors, and support clients across the wider city for resale, new launches, and investment inventory.' AS answer,
    10 AS sort_order, 1 AS is_published, NOW(), NOW()
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `faqs` LIMIT 1);

INSERT INTO `faqs` (`question`, `answer`, `sort_order`, `is_published`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 'How quickly will you respond to my enquiry?',
    'We aim to reply within one business day. For urgent briefs, mention your timeline in the message.',
    20, 1, NOW(), NOW()
) AS t
WHERE (SELECT COUNT(*) FROM `faqs`) = 1;

INSERT INTO `faqs` (`question`, `answer`, `sort_order`, `is_published`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 'Do you only work with pre-launch projects?',
    'No — we help with resale, ready-to-move, and launch-stage inventory. Pre-launch is one of several lanes we cover.',
    30, 1, NOW(), NOW()
) AS t
WHERE (SELECT COUNT(*) FROM `faqs`) = 2;

-- Enquiries (contact form + pre-register)
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `source` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiries_created_at_index` (`created_at`),
  KEY `enquiries_read_at_index` (`read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services (homepage cards)
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `description` text DEFAULT NULL,
  `icon_class` varchar(120) DEFAULT NULL COMMENT 'e.g. fa-solid fa-building',
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` (`name`, `summary`, `description`, `icon_class`, `sort_order`, `is_published`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 'Resale advisory' AS name,
    'End-to-end support for buying and selling resale homes in Bangalore — pricing, diligence, and closure.' AS summary,
    NULL AS description,
    'fa-solid fa-house-chimney' AS icon_class,
    10 AS sort_order, 1 AS is_published, NOW(), NOW()
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `services` LIMIT 1);

INSERT INTO `services` (`name`, `summary`, `description`, `icon_class`, `sort_order`, `is_published`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 'New launch access',
    'Early pricing, floor plans, and inventory before public launch — with clear milestone tracking.',
    NULL, 'fa-solid fa-rocket', 20, 1, NOW(), NOW()
) AS t
WHERE (SELECT COUNT(*) FROM `services`) = 1;

INSERT INTO `services` (`name`, `summary`, `description`, `icon_class`, `sort_order`, `is_published`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 'Investment consulting',
    'Portfolio-aligned picks across micro-markets, with stress on approvals and realistic yield.',
    NULL, 'fa-solid fa-chart-line', 30, 1, NOW(), NOW()
) AS t
WHERE (SELECT COUNT(*) FROM `services`) = 2;

INSERT INTO `services` (`name`, `summary`, `description`, `icon_class`, `sort_order`, `is_published`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 'Documentation & legal',
    'Title review, agreement checks, and coordination with counsel so you transact with confidence.',
    NULL, 'fa-solid fa-file-contract', 40, 1, NOW(), NOW()
) AS t
WHERE (SELECT COUNT(*) FROM `services`) = 3;

-- Blog posts (slug URLs, SEO fields)
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `body` longtext NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `featured_image_url` text DEFAULT NULL,
  `featured_image_path` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If `blog_posts` already exists without `featured_image_path` (older DB), run:
-- ALTER TABLE `blog_posts` ADD COLUMN `featured_image_path` varchar(255) DEFAULT NULL AFTER `featured_image_url`;

-- Property management (categories, types, listings)
CREATE TABLE IF NOT EXISTS `property_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `property_categories_slug_unique` (`slug`),
  KEY `property_categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `property_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `property_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Legacy `property_categories` without hierarchy / image: add columns then foreign key (adjust if columns already exist):
-- ALTER TABLE `property_categories` ADD COLUMN `parent_id` bigint UNSIGNED DEFAULT NULL AFTER `id`;
-- ALTER TABLE `property_categories` ADD COLUMN `image_path` varchar(255) DEFAULT NULL AFTER `meta_keywords`;
-- ALTER TABLE `property_categories` ADD COLUMN `image_url` text DEFAULT NULL AFTER `image_path`;
-- ALTER TABLE `property_categories` ADD KEY `property_categories_parent_id_foreign` (`parent_id`);
-- ALTER TABLE `property_categories` ADD CONSTRAINT `property_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `property_categories` (`id`) ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS `property_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `property_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `properties` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `property_category_id` bigint UNSIGNED DEFAULT NULL,
  `property_type_id` bigint UNSIGNED DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `listing_type` varchar(20) NOT NULL DEFAULT 'sale',
  `price` decimal(18,2) DEFAULT NULL,
  `price_currency` varchar(10) NOT NULL DEFAULT 'INR',
  `price_on_request` tinyint(1) NOT NULL DEFAULT 0,
  `maintenance_charges` varchar(120) DEFAULT NULL,
  `bedrooms` decimal(5,1) DEFAULT NULL,
  `bathrooms` decimal(5,1) DEFAULT NULL,
  `balconies` smallint UNSIGNED DEFAULT NULL,
  `parking_covered` smallint UNSIGNED DEFAULT NULL,
  `built_up_area_sqft` decimal(12,2) DEFAULT NULL,
  `carpet_area_sqft` decimal(12,2) DEFAULT NULL,
  `plot_area_sqft` decimal(12,2) DEFAULT NULL,
  `floor_number` smallint DEFAULT NULL,
  `total_floors` smallint UNSIGNED DEFAULT NULL,
  `facing` varchar(60) DEFAULT NULL,
  `furnishing` varchar(60) DEFAULT NULL,
  `age_of_property_years` smallint UNSIGNED DEFAULT NULL,
  `possession_status` varchar(120) DEFAULT NULL,
  `developer_name` varchar(255) DEFAULT NULL,
  `rera_number` varchar(120) DEFAULT NULL,
  `developer_description` text DEFAULT NULL,
  `project_land_area` varchar(120) DEFAULT NULL,
  `total_units` int UNSIGNED DEFAULT NULL,
  `towers_blocks_summary` varchar(500) DEFAULT NULL,
  `unit_variants_summary` varchar(120) DEFAULT NULL,
  `maps_link_url` varchar(2000) DEFAULT NULL,
  `price_disclaimer` text DEFAULT NULL,
  `configuration_rows` json DEFAULT NULL,
  `unit_mix` json DEFAULT NULL,
  `specifications` json DEFAULT NULL,
  `expert_pros` json DEFAULT NULL,
  `expert_cons` json DEFAULT NULL,
  `project_faqs` json DEFAULT NULL,
  `master_plan_path` varchar(255) DEFAULT NULL,
  `floor_plan_paths` json DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `locality` varchar(120) DEFAULT NULL,
  `city` varchar(120) DEFAULT NULL,
  `state` varchar(120) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(120) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `amenities` json DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `featured_image_path` varchar(255) DEFAULT NULL,
  `featured_image_url` text DEFAULT NULL,
  `gallery_paths` json DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `properties_slug_unique` (`slug`),
  KEY `properties_property_category_id_foreign` (`property_category_id`),
  KEY `properties_property_type_id_foreign` (`property_type_id`),
  KEY `properties_published_idx` (`is_published`, `published_at`),
  KEY `properties_featured_idx` (`is_featured`, `is_published`),
  CONSTRAINT `properties_property_category_id_foreign` FOREIGN KEY (`property_category_id`) REFERENCES `property_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `properties_property_type_id_foreign` FOREIGN KEY (`property_type_id`) REFERENCES `property_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If `properties` predates project micro-site fields, run Laravel migration
-- `2026_04_05_120000_add_project_sections_to_properties_table.php` or add columns manually.
