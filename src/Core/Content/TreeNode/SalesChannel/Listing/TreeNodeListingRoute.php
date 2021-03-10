<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\SalesChannel\Listing;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Routing\Annotation\Entity;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"store-api"})
 */
class TreeNodeListingRoute
{
    /**
     * @var EntityRepositoryInterface
     */
    private $treeNodeRepositoy;

    /**
     * TreeNodeListingRoute constructor.
     * @param EntityRepositoryInterface $treeNodeRepositoy
     */
    public function __construct(EntityRepositoryInterface $treeNodeRepositoy)
    {
        $this->treeNodeRepositoy = $treeNodeRepositoy;
    }


    /**
     * @Entity("eccb_tree_node")
     * @Route("/store-api/v{version}/tree-node-listing/{stepSetId}", name="store-api.tree-node.listing", methods={"POST"})
     */
    public function load(string $stepSetId, SalesChannelContext $context, Criteria $criteria): TreeNodeListingRouteResponse
    {

        $criteria->addFilter(new EqualsFilter('stepSetId', $stepSetId))
            ->addFilter(new EqualsFilter('parentId', null))
            ->addFilter(new EqualsFilter('treeNodeItemSetId', null))
            ->addSorting(new FieldSorting('item.position', FieldSorting::DESCENDING));

        return new TreeNodeListingRouteResponse($this->treeNodeRepositoy->search($criteria, $context->getContext()));

    }

}