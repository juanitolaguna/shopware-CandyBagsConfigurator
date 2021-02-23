<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeItemSet;

use EventCandyCandyBags\Core\Content\ItemSet\ItemSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class TreeNodeItemSetEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var TreeNodeEntity
     */
    protected $treeNode;

    /**
     * @var ItemSetEntity
     */
    protected $itemSet;

    /**
     * @var TreeNodeEntity
     */
    protected $childNode;

    /**
     * @return TreeNodeEntity
     */
    public function getTreeNode(): TreeNodeEntity
    {
        return $this->treeNode;
    }

    /**
     * @param TreeNodeEntity $treeNode
     */
    public function setTreeNode(TreeNodeEntity $treeNode): void
    {
        $this->treeNode = $treeNode;
    }

    /**
     * @return ItemSetEntity
     */
    public function getItemSet(): ItemSetEntity
    {
        return $this->itemSet;
    }

    /**
     * @param ItemSetEntity $itemSet
     */
    public function setItemSet(ItemSetEntity $itemSet): void
    {
        $this->itemSet = $itemSet;
    }

    /**
     * @return TreeNodeEntity
     */
    public function getChildNode(): TreeNodeEntity
    {
        return $this->childNode;
    }

    /**
     * @param TreeNodeEntity $childNode
     */
    public function setChildNode(TreeNodeEntity $childNode): void
    {
        $this->childNode = $childNode;
    }
}