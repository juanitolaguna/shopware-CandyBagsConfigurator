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

//        $sql = "select count(*) from eccb_step_set";
//        $result = $connection->fetchArray($sql);
//        // data should exist, quit migration
//        if ($result[0] != 0) {
//            return;
//        }

        $this->demoDataService->generate($context->getContext());
    }


    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }


        $this->container->get(Connection::class)->exec("
            SET FOREIGN_KEY_CHECKS = 0;
            ALTER TABLE eccb_tree_node DROP COLUMN tree_node_item_set_id
            SET FOREIGN_KEY_CHECKS = 1;
        ");

        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_tree_node_item_set');

        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_item_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_item');

        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_item_card_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_item_card');

        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_tree_node_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_tree_node');


        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_item_set');

        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_step_set_translation');
        $this->container->get(Connection::class)->exec('DROP TABLE IF EXISTS eccb_step_set');
    }

    /**
     * @required
     */
    public function setDemoDataService(DemoDataService $demoDataService): void
    {
        $this->demoDataService = $demoDataService;
    }

}