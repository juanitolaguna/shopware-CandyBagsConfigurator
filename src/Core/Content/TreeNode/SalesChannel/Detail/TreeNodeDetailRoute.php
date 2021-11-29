<?php

declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\SalesChannel\Detail;

use EventCandy\Sets\Core\Event\BoolStruct;
use EventCandy\Sets\Core\Event\ProductLoadedEvent;
use EventCandy\Sets\Core\Subscriber\SalesChannelProductSubscriber;
use EventCandy\Sets\Utils;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetCollection;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeItemSet\TreeNodeItemSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeCollection;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeEntity;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Routing\Annotation\Entity;
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EntityRepositoryInterface $treeNodeRepository
     * @param EntityRepositoryInterface $treeNodeItemSetRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityRepositoryInterface $treeNodeRepository,
        EntityRepositoryInterface $treeNodeItemSetRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->treeNodeRepository = $treeNodeRepository;
        $this->treeNodeItemSetRepository = $treeNodeItemSetRepository;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @Entity("eccb_tree_node")
     * @Route("/store-api/v{version}/tree-node/{treeNodeId}", name="store-api.tree-node.detail", methods={"POST"})
     */
    public function load(
        string $treeNodeId,
        SalesChannelContext $context,
        Criteria $criteria
    ): TreeNodeDetailRouteResponse {
        $criteria->setIds([$treeNodeId])
            ->addAssociation('media');
        /** @var TreeNodeEntity $entry */
        $entry = $this->treeNodeRepository->search($criteria, $context->getContext())->first();

        // Set the Children.
        $childrenCriteria = new Criteria();
        $childrenCriteria
            ->addFilter(new EqualsFilter('parentId', $entry->getId()))
            ->addFilter(new EqualsFilter('item.active', true))
            ->addAssociation('item.itemCard.media')
            ->addAssociation('item.itemCard.product.prices')
            ->addAssociation('item.itemCard.product.name')
            ->addAssociation('children')
            ->addAssociation('itemSets')
            ->addSorting(new FieldSorting('item.position', FieldSorting::DESCENDING));

        $children = $this->treeNodeRepository->search($childrenCriteria, $context->getContext());


        /** @var TreeNodeEntity $entity */
        foreach ($children->getEntities() as $key => $entity) {
            $item = $entity->getItem();
            $product = $item->getItemCard()->getProduct();
            if ($product) {
                $price = $product->getCurrencyPrice($context->getContext()->getCurrencyId());
                $item->setCurrencyPrice($price);
                // Be aware to update this logic on change for ItemSets inside the ItemCollection Class
                $keyIsTrue = array_key_exists('ec_is_set', $product->getCustomFields())
                    && $product->getCustomFields()['ec_is_set'];
                if ($keyIsTrue) {
                    $product->addExtension(SalesChannelProductSubscriber::SKIP_UNIQUE_ID, new BoolStruct(true));
                    $this->eventDispatcher->dispatch(
                        new ProductLoadedEvent($context, new ProductCollection([$product]), true)
                    );
                }

                if (!$product->getAvailable()) {
                    $children->getEntities()->remove($key);
                }

            }
        }


        // Get the ItemSets
        // Sorting... wird in loop gemacht werden.
        $treeNodeItemSetCriteria = new Criteria();
        $treeNodeItemSetCriteria->addFilter(new EqualsFilter('treeNodeId', $entry->getId()))
            ->addAssociation('itemSet.items.itemCard.media')
            ->addAssociation('itemSet.items.itemCard.product.prices')
            ->addAssociation('itemSet.items.itemCard.product.unit')
            ->addAssociation('childNode.children')
            ->addAssociation('childNode.itemSets')
            ->addFilter(new EqualsFilter('childNode.item.active', true))
            ->addSorting(new FieldSorting('childNode.item.position', FieldSorting::DESCENDING));

        /** @var EntitySearchResult $itemSetsSearchResult */
        $itemSetsSearchResult = $this->treeNodeItemSetRepository->search(
            $treeNodeItemSetCriteria,
            $context->getContext()
        );


        /** @var TreeNodeItemSetEntity[] $itemSets */
        $itemSets = [];

        /** @var TreeNodeItemSetEntity $entity */
        foreach ($itemSetsSearchResult->getEntities() as $entity) {
            /** @var ItemSetEntity $itemSet */
            $itemSet = $entity->getItemSet();
            $itemSet->getItems()->filterByActive();
            $itemSet->getItems()->sortByPosition();
            // Be aware to update this logic on change for the TreeNode Class
            $itemSet->getItems()->correctAvailableStock($this->eventDispatcher, $context);

            // enrich with currency Price
            $itemSet->getItems()->getPrices($context->getContext()->getCurrencyId());

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