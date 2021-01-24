<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1611326169CreateBaseSchema extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1611326169;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `eccb_candy_bag` (
                `id` BINARY(16) NOT NULL,
                `min_steps` INT(11) NULL DEFAULT 2,
                `active` TINYINT(1) NULL DEFAULT 0,
                `position` INT(11) NULL DEFAULT 0,
                `media_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.eccb_candy_bag.translated` CHECK (JSON_VALID(`translated`)),
                KEY `fk.eccb_candy_bag.media_id` (`media_id`),
                CONSTRAINT `fk.eccb_candy_bag.media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `eccb_candy_bag_translation` (
                `name` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `eccb_candy_bag_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`eccb_candy_bag_id`,`language_id`),
                KEY `fk.eccb_candy_bag_translation.eccb_candy_bag_id` (`eccb_candy_bag_id`),
                KEY `fk.eccb_candy_bag_translation.language_id` (`language_id`),
                CONSTRAINT `fk.eccb_candy_bag_translation.eccb_candy_bag_id` FOREIGN KEY (`eccb_candy_bag_id`) REFERENCES `eccb_candy_bag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_candy_bag_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');


        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `eccb_step` (
                `id` BINARY(16) NOT NULL,
                `type` VARCHAR(255) NULL,
                `active` TINYINT(1) NULL DEFAULT 0,
                `position` INT(11) NULL,
                `candy_bag_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.eccb_step.translated` CHECK (JSON_VALID(`translated`)),
                KEY `fk.eccb_step.candy_bag_id` (`candy_bag_id`),
                CONSTRAINT `fk.eccb_step.candy_bag_id` FOREIGN KEY (`candy_bag_id`) REFERENCES `eccb_candy_bag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `eccb_step_translation` (
                `name` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `eccb_step_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`eccb_step_id`,`language_id`),
                KEY `fk.eccb_step_translation.eccb_step_id` (`eccb_step_id`),
                KEY `fk.eccb_step_translation.language_id` (`language_id`),
                CONSTRAINT `fk.eccb_step_translation.eccb_step_id` FOREIGN KEY (`eccb_step_id`) REFERENCES `eccb_step` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_step_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');


        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `eccb_step_product` (
                `step_id` BINARY(16) NOT NULL,
                `product_id` BINARY(16) NOT NULL,
                `product_version_id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                PRIMARY KEY (`step_id`,`product_id`,`product_version_id`),
                KEY `fk.eccb_step_product.step_id` (`step_id`),
                KEY `fk.eccb_step_product.product_id` (`product_id`,`product_version_id`),
                CONSTRAINT `fk.eccb_step_product.step_id` FOREIGN KEY (`step_id`) REFERENCES `eccb_step` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_step_product.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');


        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `eccb_card` (
                `id` BINARY(16) NOT NULL,
                `active` TINYINT(1) NULL DEFAULT 0,
                `position` INT(11) NULL,
                `media_id` BINARY(16) NULL,
                `step_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.eccb_card.translated` CHECK (JSON_VALID(`translated`)),
                KEY `fk.eccb_card.media_id` (`media_id`),
                KEY `fk.eccb_card.step_id` (`step_id`),
                CONSTRAINT `fk.eccb_card.media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_card.step_id` FOREIGN KEY (`step_id`) REFERENCES `eccb_step` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE `eccb_card_translation` (
                `name` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `eccb_card_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`eccb_card_id`,`language_id`),
                KEY `fk.eccb_card_translation.eccb_card_id` (`eccb_card_id`),
                KEY `fk.eccb_card_translation.language_id` (`language_id`),
                CONSTRAINT `fk.eccb_card_translation.eccb_card_id` FOREIGN KEY (`eccb_card_id`) REFERENCES `eccb_card` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_card_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
