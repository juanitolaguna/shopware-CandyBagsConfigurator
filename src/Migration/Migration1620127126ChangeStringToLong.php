<?php declare(strict_types=1);

namespace EventCandyCandyBags\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1620127126ChangeStringToLong extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1620127126;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate("
            ALTER TABLE `eccb_item_card_translation`
            MODIFY COLUMN `description`  MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL;
        ");

        $connection->executeUpdate("
            ALTER TABLE `eccb_item_card_translation`
            MODIFY COLUMN `additional_data`  MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL;
        ");


        $connection->executeUpdate("
            ALTER TABLE `eccb_step_set_translation`
            MODIFY COLUMN `additional_data`  MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
