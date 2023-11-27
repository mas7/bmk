-- -------------------------------------------------------------
-- TablePlus 3.11.0(352)
--
-- https://tableplus.com/
--
-- Database: bmk
-- Generation Time: 2023-11-24 20:27:57.9060
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
    `contractor_description` text COLLATE utf8mb4_unicode_ci,
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

INSERT INTO `ticket_images` (`id`, `ticket_id`, `path`, `type`, `created_at`, `updated_at`)
VALUES ('7', '1', 'images/1/01HG196D9AE2AER7WEA8HMDG7H.png', '1', '2023-11-24 18:24:11', '2023-11-24 18:24:11'),
       ('8', '1', 'signatures/1/signature.png', '2', '2023-11-24 18:24:11', '2023-11-24 18:24:11');

INSERT INTO `tickets` (`id`, `user_id`, `service_category_id`, `property_id`, `contractor_id`, `description`,
                       `contractor_description`, `status`,
                       `expected_visit_at`, `resolution_at`, `created_at`, `updated_at`)
VALUES ('1', '2', '4', '1', '5',
        'The AC unit started making a loud humming noise last night and has stopped cooling effectively. The temperature in the house is significantly higher than the thermostat setting.',
        NULL,
        '5', '2023-11-26 00:00:00', '2023-11-24 18:24:59', '2023-11-22 22:23:15', '2023-11-24 18:24:59'),
       ('2', '2', '1', '1', NULL,
        'Kitchen: Clean appliances (fridge, oven, microwave), scrub sink, countertops, and cabinets.\nLiving Room: Dust and wipe all surfaces, vacuum and shampoo carpet, clean windows.\nBathrooms: Disinfect and scrub toilets, sinks, bathtubs, and showers; clean mirrors; mop floors.',
        NULL, '1', NULL, NULL, '2023-11-22 22:24:37', '2023-11-22 22:24:37');


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;