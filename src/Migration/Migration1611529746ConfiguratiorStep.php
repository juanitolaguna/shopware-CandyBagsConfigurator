<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1611529746ConfiguratiorStep extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1611529746;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE `eccb_configurator_step` (
                `id` BINARY(16) NOT NULL,
                `position` INT(11) NULL DEFAULT 0,
                `active` TINYINT(1) NULL DEFAULT 0,
                `purchasable` TINYINT(1) NULL DEFAULT 0,
                `media_id` BINARY(16) NULL,
                `product_version_id` BINARY(16) NULL,
                `product_id` BINARY(16) NULL,
                `child_count` INT(11) NULL,
                `parent_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.eccb_configurator_step.translated` CHECK (JSON_VALID(`translated`)),
                KEY `fk.eccb_configurator_step.media_id` (`media_id`),
                KEY `fk.eccb_configurator_step.product_id` (`product_id`,`product_version_id`),
                KEY `fk.eccb_configurator_step.parent_id` (`parent_id`),
                CONSTRAINT `fk.eccb_configurator_step.media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_configurator_step.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_configurator_step.parent_id` FOREIGN KEY (`parent_id`) REFERENCES `eccb_configurator_step` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE `eccb_configurator_step_translation` (
                `name` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NULL,
                `step_description` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `eccb_configurator_step_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`eccb_configurator_step_id`,`language_id`),
                KEY `fk.eccb_configurator_step_translation.eccb_configurator_step_id` (`eccb_configurator_step_id`),
                KEY `fk.eccb_configurator_step_translation.language_id` (`language_id`),
                CONSTRAINT `fk.eccb_configurator_step_translation.eccb_configurator_step_id` FOREIGN KEY (`eccb_configurator_step_id`) REFERENCES `eccb_configurator_step` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_configurator_step_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
