<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ItemSet;

use EventCandyCandyBags\Core\Content\Item\ItemCollection;
use EventCandyCandyBags\Core\Content\ItemSet\Aggregate\ItemSetTranslation\ItemSetTranslationCollection;
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

}