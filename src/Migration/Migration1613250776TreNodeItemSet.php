<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1613250776TreNodeItemSet extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1613250776;
    }

    public function update(Connection $connection): void
    {

        $connection->executeUpdate("
            CREATE TABLE `eccb_tree_node_item_set` (
                `id` BINARY(16) NOT NULL,
                `tree_node_id` BINARY(16) NOT NULL,
                `item_set_id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                KEY `fk.eccb_tree_node_item_set.tree_node_id` (`tree_node_id`),
                KEY `fk.eccb_tree_node_item_set.item_set_id` (`item_set_id`),
                CONSTRAINT `fk.eccb_tree_node_item_set.tree_node_id` FOREIGN KEY (`tree_node_id`) REFERENCES `eccb_tree_node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.eccb_tree_node_item_set.item_set_id` FOREIGN KEY (`item_set_id`) REFERENCES `eccb_item_set` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $connection->executeUpdate("
            ALTER TABLE `eccb_tree_node`
            ADD COLUMN `tree_node_item_set_id` BINARY(16) NULL AFTER `parent_id`,
            ADD CONSTRAINT `fk.eccb_tree_node.tree_node_item_set_id` FOREIGN KEY (`tree_node_item_set_id`) 
            REFERENCES `eccb_tree_node_item_set` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
        ");


    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
