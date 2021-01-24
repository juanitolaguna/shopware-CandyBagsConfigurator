<?php declare(strict_types=1);

namespace EventCandyCandyBags;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class EventCandyCandyBags extends Plugin
{

    /**
     * @var DemoDataService
     */
    private $demoDataService;

    public function activate(ActivateContext $context): void
    {
        $connection = $this->container->get(Connection::class);

        $sql = "select count(*) from eccb_candy_bag";
        $result = $connection->fetchArray($sql);
        // data should exist, quit migration
        if ($result[0] != 0) {
            return;
        }

        $this->demoDataService->generate($context->getContext());
    }


    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_card_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_card');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_step_product');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_step_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_step');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_candy_bag_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_candy_bag');
    }

    /**
     * @required
     */
    public function setDemoDataService(DemoDataService $demoDataService): void
    {
        $this->demoDataService = $demoDataService;
    }

}