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
                `internal_name` VARCHAR(255) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;          
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
