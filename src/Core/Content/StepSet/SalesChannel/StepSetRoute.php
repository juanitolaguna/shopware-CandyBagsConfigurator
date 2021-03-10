<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet\SalesChannel;

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
class StepSetRoute
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
     * @Route("/store-api/v{version}/step-set", name="store-api.step-set.search", methods={"GET", "POST"})
     */
    public function load(SalesChannelContext $context, Criteria $criteria): StepSetRouteResponse
    {
        $criteria
            ->addAssociation('media')
            ->addAssociation('steps');

        return new StepSetRouteResponse($this->stepSetRepository->search($criteria, $context->getContext()));

    }

}