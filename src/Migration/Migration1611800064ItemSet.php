<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1611800064ItemSet extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1611800064;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate("
            CREATE TABLE `eccb_item_set` (
                `id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.eccb_item_set.translated` CHECK (JSON_VALID(`translated`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;            
        ");

        $connection->executeUpdate("
            CREATE TABLE `eccb_item_set_translation` (
                `name` VARCHAR(255) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `eccb_item_set_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`eccb_item_set_id`,`language_id`),
                KEY `fk.eccb_item_set_translation.eccb_item_set_id` (`eccb_item_set_id`),
                KEY `fk.eccb_item_set_translation.language_id` (`language_id`),
                CONSTRAINT `fk.eccb_item_set_translation.eccb_item_set_id` FOREIGN KEY (`eccb_item_set_id`) REFERENCES `eccb_item_set` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_item_set_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
