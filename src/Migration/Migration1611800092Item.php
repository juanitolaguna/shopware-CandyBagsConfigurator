<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1611800092Item extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1611800092;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate("
            CREATE TABLE `eccb_item` (
                `id` BINARY(16) NOT NULL,
                `position` INT NOT NULL DEFAULT 0,
                `active` TINYINT(1) NULL DEFAULT 0,
                `terminal` TINYINT(1) NULL DEFAULT 0,
                `purchasable` TINYINT(1) NULL DEFAULT 0,
                `type` VARCHAR(255) NOT NULL,
                `internal_name` VARCHAR(255) NULL,
                `tree_node_id` BINARY(16) NULL,
                `item_set_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                KEY `fk.eccb_item.item_set_id` (`item_set_id`),
                KEY `fk.eccb_item.tree_node_id` (`tree_node_id`),
                CONSTRAINT `fk.eccb_item.item_set_id` FOREIGN KEY (`item_set_id`) REFERENCES `eccb_item_set` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_item.item_card_id` FOREIGN KEY (`tree_node_id`) REFERENCES `eccb_tree_node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;      
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
