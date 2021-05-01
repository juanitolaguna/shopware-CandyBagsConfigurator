<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1619800084StepSetPriceAndTaxColumn extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1619800084;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate("
            ALTER TABLE `eccb_step_set`
            ADD COLUMN `price` JSON NULL AFTER `active`;
        ");

        $connection->executeUpdate("
            ALTER TABLE `eccb_step_set`
            ADD COLUMN `tax_id` BINARY(16) NULL AFTER `active`,
            ADD CONSTRAINT `fk.eccb_step_set.tax_id` FOREIGN KEY (`tax_id`) 
            REFERENCES `tax` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
