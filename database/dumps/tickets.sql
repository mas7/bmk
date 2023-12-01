-- -------------------------------------------------------------
-- TablePlus 3.11.0(352)
--
-- https://tableplus.com/
--
-- Database: bmk
-- Generation Time: 2023-12-01 19:23:11.7840
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ticket_services`;
CREATE TABLE `ticket_services`
(
    `id`         bigint unsigned NOT NULL AUTO_INCREMENT,
    `ticket_id`  bigint unsigned NOT NULL,
    `service_id` bigint unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY          `ticket_services_ticket_id_foreign` (`ticket_id`),
    KEY          `ticket_services_service_id_foreign` (`service_id`),
    CONSTRAINT `ticket_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
    CONSTRAINT `ticket_services_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets`
(
    `id`                     bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id`                bigint unsigned NOT NULL,
    `property_id`            bigint unsigned NOT NULL,
    `contractor_id`          bigint unsigned DEFAULT NULL,
    `description`            text COLLATE utf8mb4_unicode_ci,
    `contractor_description` text COLLATE utf8mb4_unicode_ci,
    `status`                 tinyint NOT NULL,
    `expected_visit_at`      datetime DEFAULT NULL,
    `resolution_at`          datetime DEFAULT NULL,
    `created_at`             timestamp NULL DEFAULT NULL,
    `updated_at`             timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY                      `tickets_user_id_foreign` (`user_id`),
    KEY                      `tickets_property_id_foreign` (`property_id`),
    KEY                      `tickets_contractor_id_foreign` (`contractor_id`),
    CONSTRAINT `tickets_contractor_id_foreign` FOREIGN KEY (`contractor_id`) REFERENCES `users` (`id`),
    CONSTRAINT `tickets_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`),
    CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ticket_images` (`id`, `ticket_id`, `path`, `type`, `created_at`, `updated_at`)
VALUES ('1', '2', 'images/2/01HGK6CVVQEX66ESXM7M8A5J83.jpg', '1', '2023-12-01 17:21:36', '2023-12-01 17:21:36'),
       ('2', '2', 'signatures/2/signature.png', '2', '2023-12-01 17:21:36', '2023-12-01 17:21:36');

INSERT INTO `ticket_services` (`id`, `ticket_id`, `service_id`, `created_at`, `updated_at`)
VALUES ('1', '1', '1', '2023-12-01 17:15:49', '2023-12-01 17:15:49'),
       ('2', '1', '2', '2023-12-01 17:15:49', '2023-12-01 17:15:49'),
       ('3', '2', '3', '2023-12-01 17:16:59', '2023-12-01 17:16:59');

INSERT INTO `tickets` (`id`, `user_id`, `property_id`, `contractor_id`, `description`, `contractor_description`,
                       `status`, `expected_visit_at`, `resolution_at`, `created_at`, `updated_at`)
VALUES ('1', '2', '1', '5', 'Cleaning my house', NULL, '1', '2023-12-09 21:00:00', NULL, '2023-12-01 17:15:49',
        '2023-12-01 17:15:49'),
       ('2', '2', '1', '4', 'Fixing my house electricity', 'House electricity fixed ', '5', '2023-11-23 21:00:00',
        '2023-12-01 17:21:36', '2023-12-01 17:16:59', '2023-12-01 17:21:36');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;