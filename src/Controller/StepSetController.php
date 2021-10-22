<?php declare(strict_types=1);

namespace EventCandyCandyBags\Controller;

use EventCandyCandyBags\Core\Content\StepSet\StepSetEntity;
use Shopware\Core\Content\Cms\Exception\PageNotFoundException;
use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Page\GenericPageLoader;
use Shopware\Storefront\Page\MetaInformation;
use Shopware\Storefront\Page\Navigation\NavigationPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class StepSetController extends StorefrontController
{

    /**
     * @var GenericPageLoader
     */
    private $genericPageLoader;

    /**
     * StepSetController constructor.
     * @param GenericPageLoader $genericPageLoader
     */
    public function __construct(GenericPageLoader $genericPageLoader)
    {
        $this->genericPageLoader = $genericPageLoader;
    }


    /**
     * @Route("/candy-bags/{stepSetId}", name="eccb.candybags", options={"seo"="true"}, methods={"GET"})
     */
    public function getCandyBags(string $stepSetId, Request $request, SalesChannelContext $context): Response
    {
        $page = $this->genericPageLoader->load($request, $context);
        $page = NavigationPage::createFrom($page);

        /** @var EntityRepositoryInterface $stepSetRepository */
        $stepSetRepository = $this->container->get('eccb_step_set.repository');
        $criteria = new Criteria([$stepSetId]);
        $criteria->addAssociations(['media']);

        $results = $stepSetRepository->search($criteria, $context->getContext())->getEntities();

        $stepSetNavigationCriteria = new Criteria();
        $stepSetNavigationCriteria->addFilter(
            new NotFilter(
                NotFilter::CONNECTION_AND,
                [new EqualsFilter('id', $stepSetId)]
            ));
        $stepSetNavigation = $stepSetRepository->search($stepSetNavigationCriteria, $context->getContext())->getEntities();


        /** @var StepSetEntity $entry */
        $entry = $results->first();

        if (!$entry) {
            throw new PageNotFoundException($stepSetId);
        }
        /** @var MetaInformation $metaInformation */
        $metaInformation = $page->getMetaInformation();
        $metaInformation->setMetaTitle($entry->getTranslated()['name']);
        $metaInformation->setMetaDescription($entry->getTranslated()['description']);
        $metaInformation->setMetaKeywords($entry->getTranslation('keywords') ?? '');
        $revisitAfter = $entry->getTranslation('revisitAfter');

        if ($revisitAfter == 0 || $revisitAfter == null) {
            $revisitAfter = '15 days';
        } else {
            $revisitAfter = (string)$revisitAfter . ' days';
        }
        $metaInformation->setRevisit($revisitAfter);

        $page->setMetaInformation($metaInformation);

        return $this->renderStorefront('@EventCandyCandyBags/storefront/page/eccb-detail.html.twig', [
            'page' => $page,
            'entry' => $entry,
            'stepSetNavigation' => $stepSetNavigation
        ]);

    }
}