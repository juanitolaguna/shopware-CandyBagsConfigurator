<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1611800078Item extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1611800078;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate("
            CREATE TABLE `eccb_item` (
                `id` BINARY(16) NOT NULL,
                `item_set_id` BINARY(16) NULL,
                `position` INT(11) NULL DEFAULT 0,
                `active` TINYINT(1) NULL DEFAULT 1,
                `terminal` TINYINT(1) NULL DEFAULT 0,
                `purchasable` TINYINT(1) NULL DEFAULT 0,
                `internal_name` VARCHAR(255) NULL,
                `type` VARCHAR(255) NOT NULL DEFAULT 'card',
                `media_id` BINARY(16) NULL,
                `product_version_id` BINARY(16) NULL,
                `product_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.eccb_item.translated` CHECK (JSON_VALID(`translated`)),
                KEY `fk.eccb_item.item_set_id` (`item_set_id`),
                KEY `fk.eccb_item.media_id` (`media_id`),
                KEY `fk.eccb_item.product_id` (`product_id`,`product_version_id`),
                CONSTRAINT `fk.eccb_item.item_set_id` FOREIGN KEY (`item_set_id`) REFERENCES `eccb_item_set` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_item.media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_item.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;            
        ");

        $connection->executeUpdate("
            CREATE TABLE `eccb_item_translation` (
                `name` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NULL,
                `additional_item_data` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `eccb_item_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`eccb_item_id`,`language_id`),
                KEY `fk.eccb_item_translation.eccb_item_id` (`eccb_item_id`),
                KEY `fk.eccb_item_translation.language_id` (`language_id`),
                CONSTRAINT `fk.eccb_item_translation.eccb_item_id` FOREIGN KEY (`eccb_item_id`) REFERENCES `eccb_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_item_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;                        
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
