<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item;

use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardEntity;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ItemEntity extends Entity
{

    use EntityIdTrait;

    /**
     * ItemSet
     * @var int
     */
    protected $position;

    /**
     * ItemSet
     * @var bool
     */
    protected $active;

    /**
     * ItemSet
     * @var bool
     */
    protected $terminal;

    /**
     * ItemSet
     * @var bool
     */
    protected $purchasable;


    /**
     * ItemSet|TreeNode
     * @var string
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $internalName;


    /**
     * @var string|null
     */
    protected $itemSetId;

    /**
     * ItemSet
     * @var ItemSetEntity|null
     */
    protected $itemSet;

    /**
     * @var string|null
     */
    protected $treeNodeId;

    /**
     * TreeNode
     * @var TreeNodeEntity|null
     */
    protected $treeNode;



    /** Concrete Item Types */

    /**
     * @var string|null
     */
    protected $itemCardId;


    /**
     * @var ItemCardEntity|null
     */
    protected $itemCard;

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getInternalName(): ?string
    {
        return $this->internalName;
    }

    /**
     * @param string|null $internalName
     */
    public function setInternalName(?string $internalName): void
    {
        $this->internalName = $internalName;
    }

    /**
     * @return string|null
     */
    public function getItemSetId(): ?string
    {
        return $this->itemSetId;
    }

    /**
     * @param string|null $itemSetId
     */
    public function setItemSetId(?string $itemSetId): void
    {
        $this->itemSetId = $itemSetId;
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
     * @return string|null
     */
    public function getTreeNodeId(): ?string
    {
        return $this->treeNodeId;
    }

    /**
     * @param string|null $treeNodeId
     */
    public function setTreeNodeId(?string $treeNodeId): void
    {
        $this->treeNodeId = $treeNodeId;
    }

    /**
     * @return TreeNodeEntity|null
     */
    public function getTreeNode(): ?TreeNodeEntity
    {
        return $this->treeNode;
    }

    /**
     * @param TreeNodeEntity|null $treeNode
     */
    public function setTreeNode(?TreeNodeEntity $treeNode): void
    {
        $this->treeNode = $treeNode;
    }

    /**
     * @return string|null
     */
    public function getItemCardId(): ?string
    {
        return $this->itemCardId;
    }

    /**
     * @param string|null $itemCardId
     */
    public function setItemCardId(?string $itemCardId): void
    {
        $this->itemCardId = $itemCardId;
    }

    /**
     * @return ItemCardEntity|null
     */
    public function getItemCard(): ?ItemCardEntity
    {
        return $this->itemCard;
    }

    /**
     * @param ItemCardEntity|null $itemCard
     */
    public function setItemCard(?ItemCardEntity $itemCard): void
    {
        $this->itemCard = $itemCard;
    }
}