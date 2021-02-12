<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard;

use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardTranslation\ItemCardTranslationCollection;
use EventCandyCandyBags\Core\Content\Item\ItemCollection;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ItemCardEntity extends Entity {
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $internalName;

    /**
     * @var MediaEntity
     */
    protected $media;


    /**
     * OneToMany
     * @var ItemCollection|null
     */
    protected $items;

    /**
     * @var ItemCardTranslationCollection
     */
    protected $translations;

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
     * @return MediaEntity
     */
    public function getMedia(): MediaEntity
    {
        return $this->media;
    }

    /**
     * @param MediaEntity $media
     */
    public function setMedia(MediaEntity $media): void
    {
        $this->media = $media;
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
     * @return ItemCardTranslationCollection
     */
    public function getTranslations(): ItemCardTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param ItemCardTranslationCollection $translations
     */
    public function setTranslations(ItemCardTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }



}

