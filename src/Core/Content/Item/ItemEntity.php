<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item;

use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemTranslation\ItemTranslationCollection;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetEntity;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeCollection;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Content\Product\ProductEntity;
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
     * TreeNode
     * @var string|null
     */
    protected $internalName;

    /**
     * ItemSet|TreeNode
     * @var string
     */
    protected $type;

    /**
     * ItemSet
     * @var ItemSetEntity
     */
    protected $itemSet;

    /**
     * ItemSet|TreeNode
     * @var MediaEntity|null
     */
    protected $media;

    /**
     * ItemSet|TreeNode
     * @var ProductEntity|null
     */
    protected $product;

    /**
     * TreeNode
     * @var TreeNodeCollection
     */
    protected $treeNodes;

    /**
     * @var ItemTranslationCollection|null
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
     * @return MediaEntity|null
     */
    public function getMedia(): ?MediaEntity
    {
        return $this->media;
    }

    /**
     * @param MediaEntity|null $media
     */
    public function setMedia(?MediaEntity $media): void
    {
        $this->media = $media;
    }

    /**
     * @return ProductEntity|null
     */
    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    /**
     * @param ProductEntity|null $product
     */
    public function setProduct(?ProductEntity $product): void
    {
        $this->product = $product;
    }

    /**
     * @return TreeNodeCollection
     */
    public function getTreeNodes(): TreeNodeCollection
    {
        return $this->treeNodes;
    }

    /**
     * @param TreeNodeCollection $treeNodes
     */
    public function setTreeNodes(TreeNodeCollection $treeNodes): void
    {
        $this->treeNodes = $treeNodes;
    }

    /**
     * @return ItemTranslationCollection|null
     */
    public function getTranslations(): ?ItemTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param ItemTranslationCollection|null $translations
     */
    public function setTranslations(?ItemTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

}