<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\SalesChannel\Detail;

use EventCandyCandyBags\Core\Content\ItemSet\ItemSetCollection;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeItemSet\TreeNodeItemSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeCollection;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeEntity;
use PhpParser\Node\Expr\BinaryOp\Equal;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\Entity;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"store-api"})
 */
class TreeNodeDetailRoute
{
    /**
     * @var EntityRepositoryInterface
     */
    private $treeNodeRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $treeNodeItemSetRepository;

    /**
     * TreeNodeDetailRoute constructor.
     * @param EntityRepositoryInterface $treeNodeRepository
     * @param EntityRepositoryInterface $treeNodeItemSetRepository
     */
    public function __construct(EntityRepositoryInterface $treeNodeRepository, EntityRepositoryInterface $treeNodeItemSetRepository)
    {
        $this->treeNodeRepository = $treeNodeRepository;
        $this->treeNodeItemSetRepository = $treeNodeItemSetRepository;
    }


    /**
     * @Entity("eccb_tree_node")
     * @Route("/store-api/v{version}/tree-node/{treeNodeId}", name="store-api.tree-node.detail", methods={"POST"})
     */
    public function load(string $treeNodeId, SalesChannelContext $context, Criteria $criteria): TreeNodeDetailRouteResponse
    {

        $criteria->setIds([$treeNodeId])
            ->addAssociation('media');
        /** @var TreeNodeEntity $entry */
        $entry = $this->treeNodeRepository->search($criteria, $context->getContext())->first();


        // Set the Children.
        $childrenCriteria = new Criteria();
        $childrenCriteria
            ->addFilter(new EqualsFilter('parentId', $entry->getId()))
            ->addAssociation('item.itemCard.media')
            ->addAssociation('children');
        $children = $this->treeNodeRepository->search($childrenCriteria, $context->getContext());


        //ToDo: set item Sets
        $treeNodeItemSetCriteria = new Criteria();
        $treeNodeItemSetCriteria->addFilter(new EqualsFilter('treeNodeId', $entry->getId()))
            ->addAssociation('itemSet.items.itemCard.media')
            ->addAssociation('childNode.children')
            ->addAssociation('childNode.itemSets');

        /** @var EntitySearchResult $itemSetsSearchResult */
        $itemSetsSearchResult = $this->treeNodeItemSetRepository->search($treeNodeItemSetCriteria, $context->getContext());

        /** @var TreeNodeItemSetEntity[] $itemSets */
        $itemSets = [];

        /** @var TreeNodeItemSetEntity $entity */
        foreach ($itemSetsSearchResult->getEntities() as $entity) {
            $itemSet = $entity->getItemSet();

            if ($entity->getChildNode() !== null) {
                $itemSet->setChildNode($entity->getChildNode());
            }

            $itemSets[] = $itemSet;
        }

        $entry->setItemSets(new ItemSetCollection($itemSets));
        $entry->setChildren(new TreeNodeCollection($children->getEntities()));

        return new TreeNodeDetailRouteResponse($entry);
    }

}