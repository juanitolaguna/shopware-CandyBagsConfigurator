<?php declare(strict_types=1);

namespace EventCandyCandyBags;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class EventCandyCandyBags extends Plugin
{
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_card');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_card_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_step_product');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_step');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_step_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_candy_bag');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_candy_bag_translation');
    }

}