<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemTranslation;

use EventCandyCandyBags\Core\Content\Item\ItemEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class ItemTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $itemId;

    /**
     * @var ItemEntity
     */
    protected $item;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $additionalItemData;

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }

    /**
     * @param string $itemId
     */
    public function setItemId(string $itemId): void
    {
        $this->itemId = $itemId;
    }

    /**
     * @return ItemEntity
     */
    public function getItem(): ItemEntity
    {
        return $this->item;
    }

    /**
     * @param ItemEntity $item
     */
    public function setItem(ItemEntity $item): void
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getAdditionalItemData(): ?string
    {
        return $this->additionalItemData;
    }

    /**
     * @param string|null $additionalItemData
     */
    public function setAdditionalItemData(?string $additionalItemData): void
    {
        $this->additionalItemData = $additionalItemData;
    }

}