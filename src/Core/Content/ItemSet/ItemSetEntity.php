<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ItemSet;

use EventCandyCandyBags\Core\Content\Item\ItemCollection;
use EventCandyCandyBags\Core\Content\ItemSet\Aggregate\ItemSetTranslation\ItemSetTranslationCollection;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ItemSetEntity extends Entity {

    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $internalName;

    /**
     * @var ItemCollection|null
     */
    protected $items;


    /**
     * @var TreeNodeCollection|null
     */
    protected $treeNodes;

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
     * @return ItemCollection|null
     */
    public function getItems(): ?ItemCollection
    {
        return $this->items;
    }

    /**
     * @param ItemCollection|null $items
     */
    public function setItems(?ItemCollection $items): void
    {
        $this->items = $items;
    }

    /**
     * @return TreeNodeCollection|null
     */
    public function getTreeNodes(): ?TreeNodeCollection
    {
        return $this->treeNodes;
    }

    /**
     * @param TreeNodeCollection|null $treeNodes
     */
    public function setTreeNodes(?TreeNodeCollection $treeNodes): void
    {
        $this->treeNodes = $treeNodes;
    }

}