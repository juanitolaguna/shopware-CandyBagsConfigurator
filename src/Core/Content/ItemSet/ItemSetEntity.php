<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ItemSet;

use EventCandyCandyBags\Core\Content\Item\ItemCollection;
use EventCandyCandyBags\Core\Content\ItemSet\Aggregate\ItemSetTranslation\ItemSetTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ItemSetEntity extends Entity {

    use EntityIdTrait;

    /**
     * @var ItemCollection|null
     */
    protected $items;

    /**
     * @var ItemSetTranslationCollection
     */
    protected $translations;

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
     * @return ItemSetTranslationCollection
     */
    public function getTranslations(): ItemSetTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param ItemSetTranslationCollection $translations
     */
    public function setTranslations(ItemSetTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

}