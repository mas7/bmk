-- -------------------------------------------------------------
-- TablePlus 3.11.0(352)
--
-- https://tableplus.com/
--
-- Database: bmk
-- Generation Time: 2023-11-22 23:22:22.4540
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


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


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;