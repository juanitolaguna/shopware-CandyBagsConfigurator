<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1611800092TreeNode extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1611800092;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate("
            CREATE TABLE `eccb_tree_node` (
                `id` BINARY(16) NOT NULL,
                `position` INT(11) NULL,
                `active` TINYINT(1) NULL DEFAULT 0,
                `terminal` TINYINT(1) NULL DEFAULT 0,
                `purchasable` TINYINT(1) NULL DEFAULT 0,
                `set_node` TINYINT(1) NULL DEFAULT 0,
                `item_id` BINARY(16) NULL,
                `item_set_id` BINARY(16) NULL,
                `step_set_id` BINARY(16) NULL,
                `parent_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.eccb_tree_node.translated` CHECK (JSON_VALID(`translated`)),
                KEY `fk.eccb_tree_node.item_id` (`item_id`),
                KEY `fk.eccb_tree_node.item_set_id` (`item_set_id`),
                KEY `fk.eccb_tree_node.step_set_id` (`step_set_id`),
                KEY `fk.eccb_tree_node.parent_id` (`parent_id`),
                CONSTRAINT `fk.eccb_tree_node.item_id` FOREIGN KEY (`item_id`) REFERENCES `eccb_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_tree_node.item_set_id` FOREIGN KEY (`item_set_id`) REFERENCES `eccb_item_set` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_tree_node.step_set_id` FOREIGN KEY (`step_set_id`) REFERENCES `eccb_step_set` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_tree_node.parent_id` FOREIGN KEY (`parent_id`) REFERENCES `eccb_tree_node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;                
        ");

        $connection->executeUpdate("
            CREATE TABLE `eccb_tree_node_translation` (
                `step_description` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `eccb_tree_node_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`eccb_tree_node_id`,`language_id`),
                KEY `fk.eccb_tree_node_translation.eccb_tree_node_id` (`eccb_tree_node_id`),
                KEY `fk.eccb_tree_node_translation.language_id` (`language_id`),
                CONSTRAINT `fk.eccb_tree_node_translation.eccb_tree_node_id` FOREIGN KEY (`eccb_tree_node_id`) REFERENCES `eccb_tree_node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_tree_node_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;        
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
