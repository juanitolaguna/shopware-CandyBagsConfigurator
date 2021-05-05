<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet\SalesChannel\Detail;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Routing\Annotation\Entity;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepositoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"store-api"})
 */
class StepSetDetailRoute
{
    /**
     * @var EntityRepositoryInterface
     */
    private $stepSetRepository;

    /**
     * StepSetRoute constructor.
     * @param EntityRepositoryInterface $stepSetRepository
     */
    public function __construct(EntityRepositoryInterface $stepSetRepository)
    {
        $this->stepSetRepository = $stepSetRepository;
    }

    /**
     * @Entity("eccb_step_set")
     * @Route("/store-api/v{version}/step-set/{stepSetId}", name="store-api.step-set.detail", methods={"POST"})
     */
    public function load(string $stepSetId, SalesChannelContext $context, Criteria $criteria): StepSetDetailRouteResponse
    {

        $criteria
            ->setIds([$stepSetId])
            ->addAssociation('media')
            ->addAssociation('selectionBaseImage')
            ->addAssociation('steps');

        return new StepSetDetailRouteResponse($this->stepSetRepository->search($criteria, $context->getContext()));

    }

}