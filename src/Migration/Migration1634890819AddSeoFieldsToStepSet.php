<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1634890819AddSeoFieldsToStepSet extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1634890819;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement("
            ALTER TABLE `eccb_step_set_translation`
            ADD COLUMN `revisit_after` INT NULL AFTER `description`;
        ");

        $connection->executeStatement("
            ALTER TABLE `eccb_step_set_translation`
            ADD COLUMN `keywords` MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL AFTER `description`;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
