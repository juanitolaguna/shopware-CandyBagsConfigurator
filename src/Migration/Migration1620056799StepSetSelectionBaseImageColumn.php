<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1620056799StepSetSelectionBaseImageColumn extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1620056799;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate("
            ALTER TABLE `eccb_step_set`
            ADD COLUMN `selection_base_image_id` BINARY(16) NULL AFTER `media_id`,
            ADD CONSTRAINT `fk.eccb_step_set.selection_base_image_id` FOREIGN KEY (`selection_base_image_id`) 
            REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
