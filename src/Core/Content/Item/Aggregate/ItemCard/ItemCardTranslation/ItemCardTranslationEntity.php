<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardTranslation;

use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class ItemCardTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $itemCardId;

    /**
     * @var ItemCardEntity
     */
    protected $item;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $additionalData;

    /**
     * @return string
     */
    public function getItemCardId(): string
    {
        return $this->itemCardId;
    }

    /**
     * @param string $itemCardId
     */
    public function setItemCardId(string $itemCardId): void
    {
        $this->itemCardId = $itemCardId;
    }

    /**
     * @return ItemCardEntity
     */
    public function getItem(): ItemCardEntity
    {
        return $this->item;
    }

    /**
     * @param ItemCardEntity $item
     */
    public function setItem(ItemCardEntity $item): void
    {
        $this->item = $item;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAdditionalData(): string
    {
        return $this->additionalData;
    }

    /**
     * @param string $additionalData
     */
    public function setAdditionalData(string $additionalData): void
    {
        $this->additionalData = $additionalData;
    }
}