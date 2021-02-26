<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1612654126ItemCard extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1612654126;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate("
            CREATE TABLE `eccb_item_card` (
                `id` BINARY(16) NOT NULL,
                `internal_name` VARCHAR(255) NULL,
                `media_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.eccb_item_card.translated` CHECK (JSON_VALID(`translated`)),
                KEY `fk.eccb_item_card.media_id` (`media_id`),
                CONSTRAINT `fk.eccb_item_card.media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");


        $connection->executeUpdate("
            CREATE TABLE `eccb_item_card_translation` (
                `name` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NULL,
                `additional_data` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `eccb_item_card_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`eccb_item_card_id`,`language_id`),
                KEY `fk.eccb_item_card_translation.eccb_item_card_id` (`eccb_item_card_id`),
                KEY `fk.eccb_item_card_translation.language_id` (`language_id`),
                CONSTRAINT `fk.eccb_item_card_translation.eccb_item_card_id` FOREIGN KEY (`eccb_item_card_id`) REFERENCES `eccb_item_card` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_item_card_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $connection->executeUpdate("
            ALTER TABLE `eccb_item`
            ADD COLUMN `item_card_id` BINARY(16) NULL AFTER `item_set_id`,
            ADD CONSTRAINT `fk.language.item_card_id` FOREIGN KEY (`item_card_id`)
            REFERENCES `eccb_item_card` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
        ");

        $connection->executeUpdate("
            CREATE TRIGGER `eccb_before_item_set_delete`
            	BEFORE DELETE ON `eccb_item_set` FOR EACH ROW
            BEGIN
            	DELETE FROM `eccb_item_card`
            	WHERE id IN (
            		SELECT
            			`eccb_item`.`item_card_id` FROM `eccb_item`
            		WHERE
            			`item_set_id` = `old`.`id`);
            END;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
