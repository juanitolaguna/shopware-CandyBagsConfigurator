<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode;

use EventCandyCandyBags\Core\Content\Item\ItemEntity;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetCollection;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetEntity;
use EventCandyCandyBags\Core\Content\StepSet\StepSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeTranslation\TreeNodeTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class TreeNodeEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var bool
     */
    protected $terminal;

    /**
     * @var bool
     */
    protected $purchasable;

    /**
     * @var bool
     */
    protected $setNode;

    /**
     * @var ItemEntity|null
     */
    protected $item;

    /**
     * @var ItemSetEntity|null
     */
    protected $itemSet;

    /**
     * @var StepSetEntity
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
     * @var TreeNodeCollection|null
     */
    protected $children;

    /**
     * @var TreeNodeTranslationCollection|null
     */
    protected $translations;

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isTerminal(): bool
    {
        return $this->terminal;
    }

    /**
     * @param bool $terminal
     */
    public function setTerminal(bool $terminal): void
    {
        $this->terminal = $terminal;
    }

    /**
     * @return bool
     */
    public function isPurchasable(): bool
    {
        return $this->purchasable;
    }

    /**
     * @param bool $purchasable
     */
    public function setPurchasable(bool $purchasable): void
    {
        $this->purchasable = $purchasable;
    }

    /**
     * @return bool
     */
    public function isSetNode(): bool
    {
        return $this->setNode;
    }

    /**
     * @param bool $setNode
     */
    public function setSetNode(bool $setNode): void
    {
        $this->setNode = $setNode;
    }

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
     * @return ItemSetEntity|null
     */
    public function getItemSet(): ?ItemSetEntity
    {
        return $this->itemSet;
    }

    /**
     * @param ItemSetEntity|null $itemSet
     */
    public function setItemSet(?ItemSetEntity $itemSet): void
    {
        $this->itemSet = $itemSet;
    }

    /**
     * @return StepSetEntity
     */
    public function getStepSet(): StepSetEntity
    {
        return $this->stepSet;
    }

    /**
     * @param StepSetEntity $stepSet
     */
    public function setStepSet(StepSetEntity $stepSet): void
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

}