<?php declare(strict_types=1);

namespace EventCandyCandyBags;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
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

        $sql = "select count(*) from eccb_step_set";
        $result = $connection->fetchArray($sql);
        // data should exist, quit demo data generation
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

        $this->deleteSeoUrls($uninstallContext->getContext());
        $this->deleteSeoUrlTemplate($uninstallContext->getContext());

        $this->container->get(Connection::class)->exec('
            SET FOREIGN_KEY_CHECKS = 0;
            ALTER TABLE eccb_tree_node DROP FOREIGN KEY `fk.eccb_tree_node.tree_node_item_set_id`;
            ALTER TABLE eccb_tree_node DROP COLUMN tree_node_item_set_id;
            SET FOREIGN_KEY_CHECKS = 1;');

        $this->container->get(Connection::class)->exec('DROP TRIGGER IF EXISTS eccb_before_item_set_delete');
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

    private function deleteSeoUrlTemplate(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('entityName', 'eccb_step_set')
        );

        /** @var EntityRepositoryInterface $seoUrlTemplateRepository */
        $seoUrlTemplateRepository = $this->container->get('seo_url_template.repository');

        $seoUrlTemplateRepository->search($criteria, $context);

        $seoUrlTemplateIds = $seoUrlTemplateRepository->searchIds($criteria, $context)->getIds();

        if (!empty($seoUrlTemplateIds)) {
            $ids = array_map(static function ($id) {
                return ['id' => $id];
            }, $seoUrlTemplateIds);
            $seoUrlTemplateRepository->delete($ids, $context);
        }
    }

    private function deleteSeoUrls(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('routeName', 'eccb.candybags')
        );

        /** @var EntityRepositoryInterface $seoUrlRepository */
        $seoUrlRepository = $this->container->get('seo_url.repository');

        $seoUrlRepository->search($criteria, $context);

        $seoUrlIds = $seoUrlRepository->searchIds($criteria, $context)->getIds();

        if (!empty($seoUrlIds)) {
            $ids = array_map(static function ($id) {
                return ['id' => $id];
            }, $seoUrlIds);
            $seoUrlRepository->delete($ids, $context);
        }
    }

}