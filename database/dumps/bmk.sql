-- -------------------------------------------------------------
-- TablePlus 3.11.0(352)
--
-- https://tableplus.com/
--
-- Database: bmk
-- Generation Time: 2023-11-24 20:28:53.4610
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `contractors`;
CREATE TABLE `contractors`
(
    `id`                  bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id`             bigint unsigned NOT NULL,
    `service_category_id` bigint unsigned NOT NULL,
    `status`              tinyint unsigned NOT NULL,
    `created_at`          timestamp NULL DEFAULT NULL,
    `updated_at`          timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY                   `contractors_user_id_foreign` (`user_id`),
    KEY                   `contractors_service_category_id_foreign` (`service_category_id`),
    CONSTRAINT `contractors_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`),
    CONSTRAINT `contractors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`
(
    `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
    `uuid`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `connection` text COLLATE utf8mb4_unicode_ci         NOT NULL,
    `queue`      text COLLATE utf8mb4_unicode_ci         NOT NULL,
    `payload`    longtext COLLATE utf8mb4_unicode_ci     NOT NULL,
    `exception`  longtext COLLATE utf8mb4_unicode_ci     NOT NULL,
    `failed_at`  timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`
(
    `id`        int unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `batch`     int                                     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`
(
    `permission_id` bigint unsigned NOT NULL,
    `model_type`    varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `model_id`      bigint unsigned NOT NULL,
    PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
    KEY             `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
    CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`
(
    `role_id`    bigint unsigned NOT NULL,
    `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `model_id`   bigint unsigned NOT NULL,
    PRIMARY KEY (`role_id`, `model_id`, `model_type`),
    KEY          `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
    CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`
(
    `email`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `token`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments`
(
    `id`             bigint unsigned NOT NULL AUTO_INCREMENT,
    `client_id`      bigint unsigned NOT NULL,
    `rental_plan_id` bigint unsigned NOT NULL,
    `amount`         int      NOT NULL,
    `payment_date`   datetime NOT NULL,
    `status`         tinyint unsigned NOT NULL,
    `created_at`     timestamp NULL DEFAULT NULL,
    `updated_at`     timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY              `payments_client_id_foreign` (`client_id`),
    KEY              `payments_rental_plan_id_foreign` (`rental_plan_id`),
    CONSTRAINT `payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
    CONSTRAINT `payments_rental_plan_id_foreign` FOREIGN KEY (`rental_plan_id`) REFERENCES `rental_plans` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`
(
    `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`
(
    `id`             bigint unsigned NOT NULL AUTO_INCREMENT,
    `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `tokenable_id`   bigint unsigned NOT NULL,
    `name`           varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `token`          varchar(64) COLLATE utf8mb4_unicode_ci  NOT NULL,
    `abilities`      text COLLATE utf8mb4_unicode_ci,
    `last_used_at`   timestamp NULL DEFAULT NULL,
    `expires_at`     timestamp NULL DEFAULT NULL,
    `created_at`     timestamp NULL DEFAULT NULL,
    `updated_at`     timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
    KEY              `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `properties`;
CREATE TABLE `properties`
(
    `id`          bigint unsigned NOT NULL AUTO_INCREMENT,
    `name`        varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `location`    varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `rent_amount` int DEFAULT NULL,
    `client_id`   bigint unsigned DEFAULT NULL,
    `created_at`  timestamp NULL DEFAULT NULL,
    `updated_at`  timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY           `properties_client_id_foreign` (`client_id`),
    CONSTRAINT `properties_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `rental_plans`;
CREATE TABLE `rental_plans`
(
    `id`           bigint unsigned NOT NULL AUTO_INCREMENT,
    `property_id`  bigint unsigned NOT NULL,
    `client_id`    bigint unsigned NOT NULL,
    `start_date`   datetime NOT NULL,
    `end_date`     datetime NOT NULL,
    `monthly_rent` int      NOT NULL,
    `status`       tinyint unsigned NOT NULL,
    `created_at`   timestamp NULL DEFAULT NULL,
    `updated_at`   timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY            `rental_plans_client_id_foreign` (`client_id`),
    CONSTRAINT `rental_plans_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`
(
    `permission_id` bigint unsigned NOT NULL,
    `role_id`       bigint unsigned NOT NULL,
    PRIMARY KEY (`permission_id`, `role_id`),
    KEY             `role_has_permissions_role_id_foreign` (`role_id`),
    CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`
(
    `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `service_categories`;
CREATE TABLE `service_categories`
(
    `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ticket_images`;
CREATE TABLE `ticket_images`
(
    `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
    `ticket_id`  bigint unsigned NOT NULL,
    `path`       text COLLATE utf8mb4_unicode_ci NOT NULL,
    `type`       tinyint unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets`
(
    `id`                  bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id`             bigint unsigned NOT NULL,
    `service_category_id` bigint unsigned NOT NULL,
    `property_id`         bigint unsigned NOT NULL,
    `contractor_id`       bigint unsigned DEFAULT NULL,
    `description`         text COLLATE utf8mb4_unicode_ci,
    `status`              tinyint NOT NULL,
    `expected_visit_at`   datetime DEFAULT NULL,
    `resolution_at`       datetime DEFAULT NULL,
    `created_at`          timestamp NULL DEFAULT NULL,
    `updated_at`          timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY                   `tickets_user_id_foreign` (`user_id`),
    KEY                   `tickets_service_category_id_foreign` (`service_category_id`),
    KEY                   `tickets_property_id_foreign` (`property_id`),
    KEY                   `tickets_contractor_id_foreign` (`contractor_id`),
    CONSTRAINT `tickets_contractor_id_foreign` FOREIGN KEY (`contractor_id`) REFERENCES `users` (`id`),
    CONSTRAINT `tickets_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`),
    CONSTRAINT `tickets_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`),
    CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`                bigint unsigned NOT NULL AUTO_INCREMENT,
    `name`              varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email`             varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone_number`      varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password`          varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `remember_token`    varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at`        timestamp NULL DEFAULT NULL,
    `updated_at`        timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contractors` (`id`, `user_id`, `service_category_id`, `status`, `created_at`, `updated_at`)
VALUES ('1', '3', '1', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('2', '4', '2', '2', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('3', '5', '4', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57');

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES ('1', '2014_10_12_000000_create_users_table', '1'),
       ('2', '2014_10_12_100000_create_password_reset_tokens_table', '1'),
       ('3', '2019_08_19_000000_create_failed_jobs_table', '1'),
       ('4', '2019_12_14_000001_create_personal_access_tokens_table', '1'),
       ('5', '2023_10_30_003047_create_properties_table', '1'),
       ('6', '2023_11_08_220506_add_phone_number_to_users_table', '1'),
       ('7', '2023_11_08_225650_create_permission_tables', '1'),
       ('8', '2023_11_10_233930_create_service_categories_table', '1'),
       ('9', '2023_11_11_174614_create_contractors_table', '1'),
       ('10', '2023_11_11_210726_create_tickets_table', '1'),
       ('11', '2023_11_19_220152_create_rental_plans_table', '1'),
       ('12', '2023_11_19_233458_create_payments_table', '1'),
       ('13', '2023_11_20_214404_create_ticket_images_table', '1');

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
VALUES ('1', 'App\\Models\\User', '1'),
       ('2', 'App\\Models\\User', '2'),
       ('3', 'App\\Models\\User', '3'),
       ('3', 'App\\Models\\User', '4'),
       ('3', 'App\\Models\\User', '5');

INSERT INTO `payments` (`id`, `client_id`, `rental_plan_id`, `amount`, `payment_date`, `status`, `created_at`,
                        `updated_at`)
VALUES ('1', '2', '1', '12000', '2023-05-22 22:18:57', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('2', '2', '1', '12000', '2023-06-22 22:18:57', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('3', '2', '1', '12000', '2023-07-22 22:18:57', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('4', '2', '1', '12000', '2023-08-22 22:18:57', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('5', '2', '1', '12000', '2023-09-22 22:18:57', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('6', '2', '1', '12000', '2023-10-22 22:18:57', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('7', '2', '1', '12000', '2023-11-22 22:18:57', '1', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('8', '2', '1', '12000', '2023-12-22 22:18:57', '2', '2023-11-22 22:18:57', '2023-11-22 22:18:57');

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
VALUES ('1', 'view_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('2', 'view_any_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('3', 'create_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('4', 'update_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('5', 'restore_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('6', 'restore_any_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('7', 'replicate_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('8', 'reorder_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('9', 'delete_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('10', 'delete_any_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('11', 'force_delete_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('12', 'force_delete_any_client', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('13', 'view_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('14', 'view_any_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('15', 'create_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('16', 'update_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('17', 'restore_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('18', 'restore_any_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('19', 'replicate_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('20', 'reorder_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('21', 'delete_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('22', 'delete_any_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('23', 'force_delete_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('24', 'force_delete_any_property', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('25', 'view_role', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('26', 'view_any_role', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('27', 'create_role', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('28', 'update_role', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('29', 'delete_role', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('30', 'delete_any_role', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('31', 'widget_PropertyOverview', 'web', '2023-11-08 22:57:16', '2023-11-08 22:57:16'),
       ('32', 'view_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('33', 'view_any_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('34', 'create_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('35', 'update_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('36', 'restore_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('37', 'restore_any_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('38', 'replicate_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('39', 'reorder_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('40', 'delete_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('41', 'delete_any_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('42', 'force_delete_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('43', 'force_delete_any_user', 'web', '2023-11-08 23:19:46', '2023-11-08 23:19:46'),
       ('44', 'view_payment', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('45', 'view_any_payment', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('46', 'reorder_payment', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('47', 'view_rental::plan', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('48', 'view_any_rental::plan', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('49', 'reorder_rental::plan', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('50', 'view_ticket', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('51', 'view_any_ticket', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('52', 'create_ticket', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('53', 'update_ticket', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('54', 'reorder_ticket', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('55', 'delete_any_ticket', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09'),
       ('56', 'delete_ticket', 'web', '2023-11-22 00:38:09', '2023-11-22 00:38:09');

INSERT INTO `properties` (`id`, `name`, `location`, `rent_amount`, `client_id`, `created_at`, `updated_at`)
VALUES ('1', 'Villa-5-Thumama', 'Al Thumama Doha, Al Thumama', '12000', '2', '2023-11-22 22:18:57',
        '2023-11-22 22:18:57'),
       ('2', 'Apartment-1-Floresta-Gardins', 'Floresta Gardens Doha, The Pearl Island, Floresta Gardens', NULL, NULL,
        '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('3', 'Apartment-34-Musheireb', 'Regency Residence Musheireb Doha, Musheireb, Regency Residence Musheireb', NULL,
        NULL, '2023-11-22 22:18:57', '2023-11-22 22:18:57');

INSERT INTO `rental_plans` (`id`, `property_id`, `client_id`, `start_date`, `end_date`, `monthly_rent`, `status`,
                            `created_at`, `updated_at`)
VALUES ('1', '1', '2', '2023-05-22 22:18:57', '2024-05-22 22:18:57', '12000', '1', '2023-11-22 22:18:57',
        '2023-11-22 22:18:57');

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
VALUES ('13', '1'),
       ('13', '2'),
       ('14', '1'),
       ('14', '2'),
       ('15', '1'),
       ('16', '1'),
       ('17', '1'),
       ('18', '1'),
       ('19', '1'),
       ('20', '1'),
       ('20', '2'),
       ('21', '1'),
       ('22', '1'),
       ('23', '1'),
       ('24', '1'),
       ('25', '1'),
       ('26', '1'),
       ('27', '1'),
       ('28', '1'),
       ('29', '1'),
       ('30', '1'),
       ('31', '1'),
       ('32', '1'),
       ('33', '1'),
       ('34', '1'),
       ('35', '1'),
       ('36', '1'),
       ('37', '1'),
       ('38', '1'),
       ('39', '1'),
       ('40', '1'),
       ('41', '1'),
       ('42', '1'),
       ('43', '1'),
       ('44', '2'),
       ('45', '2'),
       ('46', '2'),
       ('47', '2'),
       ('48', '2'),
       ('49', '2'),
       ('50', '2'),
       ('51', '2'),
       ('52', '2'),
       ('53', '2'),
       ('54', '2'),
       ('55', '2'),
       ('56', '2');

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
VALUES ('1', 'super_admin', 'web', '2023-11-08 22:57:16', '2023-11-08 23:20:38'),
       ('2', 'client', 'web', '2023-11-08 23:33:47', '2023-11-08 23:51:23'),
       ('3', 'contractor', 'web', '2023-11-11 18:29:01', '2023-11-11 18:29:01');

INSERT INTO `service_categories` (`id`, `name`, `created_at`, `updated_at`)
VALUES ('1', 'Cleaning Services', NULL, NULL),
       ('2', 'Electrical Services', NULL, NULL),
       ('3', 'House Maintenance Services', NULL, NULL),
       ('4', 'AC Repair And Service', NULL, NULL),
       ('5', 'Carpentry Services', NULL, NULL),
       ('6', 'Painting Services', NULL, NULL);

INSERT INTO `ticket_images` (`id`, `ticket_id`, `path`, `type`, `created_at`, `updated_at`)
VALUES ('7', '1', 'images/1/01HG196D9AE2AER7WEA8HMDG7H.png', '1', '2023-11-24 18:24:11', '2023-11-24 18:24:11'),
       ('8', '1', 'signatures/1/signature.png', '2', '2023-11-24 18:24:11', '2023-11-24 18:24:11');

INSERT INTO `tickets` (`id`, `user_id`, `service_category_id`, `property_id`, `contractor_id`, `description`, `status`,
                       `expected_visit_at`, `resolution_at`, `created_at`, `updated_at`)
VALUES ('1', '2', '4', '1', '5',
        'The AC unit started making a loud humming noise last night and has stopped cooling effectively. The temperature in the house is significantly higher than the thermostat setting.',
        '5', '2023-11-26 00:00:00', '2023-11-24 18:24:59', '2023-11-22 22:23:15', '2023-11-24 18:24:59'),
       ('2', '2', '1', '1', NULL,
        'Kitchen: Clean appliances (fridge, oven, microwave), scrub sink, countertops, and cabinets.\nLiving Room: Dust and wipe all surfaces, vacuum and shampoo carpet, clean windows.\nBathrooms: Disinfect and scrub toilets, sinks, bathtubs, and showers; clean mirrors; mop floors.',
        '1', NULL, NULL, '2023-11-22 22:24:37', '2023-11-22 22:24:37');

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `password`, `remember_token`,
                     `created_at`, `updated_at`)
VALUES ('1', 'Admin', 'info@bmkfacilities.com', '30065065', '2023-11-22 22:18:57',
        '$2y$10$OYL7YpMeS7X7q2GrpkGCAegkqmeojPzfCyfBbvnP/oSjQnRnR4jY.',
        '5GGOnfs6ZZcCZseYiYVMogPM7MeNN1cCVbMlhcUzdd6nCvJRNz3noLjOR6C6', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('2', 'Maged Ali', 'maged@gmail.com', '30936209', '2023-11-22 22:18:57',
        '$2y$10$Hut7y7Al0gLYJi8xQtSUReeSlYuOSa6En0eaI1qDSzjdwsZjLcVwy',
        'j8MSyCvyUgHMxgDuag7vhqJXoIOCMSjSfm7sgHhfVHyWGM19omCkcPibwQee', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('3', 'Emerald Cleaning Services Qatar', 'info@emeraldqatar.com', '30001817', '2023-11-22 22:18:57',
        '$2y$10$dX25xzmijnE9amM31Ef2Ierts95MHCo.uOfWyJP5rfzOEY6p5WgFO',
        'xgnOOEXG3JCLCeFfrrq1IwlVFuKZB01uivyAlRWehbJb9Y8oQpEEIm6RlY8r', '2023-11-22 22:18:57', '2023-11-22 22:18:57'),
       ('4', 'Abu Zaid Contracting', 'info@abuzaidservices.com', '50283052', '2023-11-22 22:18:57',
        '$2y$10$V30ps23zLjKzITn6EV0/muR50Ivzasf1XEQrnIP5fIyD8Ty8PWHvi', 'EZ7UBHhqjl', '2023-11-22 22:18:57',
        '2023-11-22 22:18:57'),
       ('5', 'FIXIT', 'helpdesk@fixitqatar.com', '31334948', '2023-11-22 22:18:57',
        '$2y$10$DeUkustPGmftzl4dsGNGf.DaSWSBS8/tQlHGvKKX99PeA0Ju/fp1a', 'jZfMa0to9g', '2023-11-22 22:18:57',
        '2023-11-22 22:18:57');


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;