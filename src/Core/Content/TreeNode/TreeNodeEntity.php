<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode;

use EventCandyCandyBags\Core\Content\Item\ItemEntity;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetCollection;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetEntity;
use EventCandyCandyBags\Core\Content\StepSet\StepSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeItemSet\TreeNodeItemSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeTranslation\TreeNodeTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class TreeNodeEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var ItemEntity|null
     */
    protected $item;

    /**
     * @var StepSetEntity|null
     */
    protected $stepSet;

    /**
     * @var string|null
     */
    protected $parentId;

    /**
     * @var TreeNodeEntity|null
     */
    protected $parent;

    /**
     * @var string|null
     */
    protected $treeNodeItemSetId;

    /**
     * @var treeNodeItemSetEntity|null
     */
    protected $treeNodeItemSet;

    /**
     * @var TreeNodeCollection|null
     */
    protected $children;

    /**
     * @var ItemSetCollection|null
     */
    protected $itemSets;

    /**
     * @var TreeNodeTranslationCollection|null
     */
    protected $translations;



    /**
     * @return ItemEntity|null
     */
    public function getItem(): ?ItemEntity
    {
        return $this->item;
    }

    /**
     * @param ItemEntity|null $item
     */
    public function setItem(?ItemEntity $item): void
    {
        $this->item = $item;
    }

    /**
     * @return StepSetEntity|null
     */
    public function getStepSet(): ?StepSetEntity
    {
        return $this->stepSet;
    }

    /**
     * @param StepSetEntity|null $stepSet
     */
    public function setStepSet(?StepSetEntity $stepSet): void
    {
        $this->stepSet = $stepSet;
    }

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param string|null $parentId
     */
    public function setParentId(?string $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return TreeNodeEntity|null
     */
    public function getParent(): ?TreeNodeEntity
    {
        return $this->parent;
    }

    /**
     * @param TreeNodeEntity|null $parent
     */
    public function setParent(?TreeNodeEntity $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return TreeNodeItemSetEntity|null
     */
    public function getTreeNodeItemSet(): ?TreeNodeItemSetEntity
    {
        return $this->treeNodeItemSet;
    }

    /**
     * @param TreeNodeItemSetEntity|null $treeNodeItemSet
     */
    public function setTreeNodeItemSet(?TreeNodeItemSetEntity $treeNodeItemSet): void
    {
        $this->treeNodeItemSet = $treeNodeItemSet;
    }

    /**
     * @return TreeNodeCollection|null
     */
    public function getChildren(): ?TreeNodeCollection
    {
        return $this->children;
    }

    /**
     * @param TreeNodeCollection|null $children
     */
    public function setChildren(?TreeNodeCollection $children): void
    {
        $this->children = $children;
    }

    /**
     * @return ItemSetCollection|null
     */
    public function getItemSets(): ?ItemSetCollection
    {
        return $this->itemSets;
    }

    /**
     * @param ItemSetCollection|null $itemSets
     */
    public function setItemSets(?ItemSetCollection $itemSets): void
    {
        $this->itemSets = $itemSets;
    }

    /**
     * @return TreeNodeTranslationCollection|null
     */
    public function getTranslations(): ?TreeNodeTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param TreeNodeTranslationCollection|null $translations
     */
    public function setTranslations(?TreeNodeTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    /**
     * @return string|null
     */
    public function getTreeNodeItemSetId(): ?string
    {
        return $this->treeNodeItemSetId;
    }

    /**
     * @param string|null $treeNodeItemSetId
     */
    public function setTreeNodeItemSetId(?string $treeNodeItemSetId): void
    {
        $this->treeNodeItemSetId = $treeNodeItemSetId;
    }

}