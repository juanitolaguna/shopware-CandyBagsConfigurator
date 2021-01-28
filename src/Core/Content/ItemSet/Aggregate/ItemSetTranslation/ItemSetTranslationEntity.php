<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ItemSet\Aggregate\ItemSetTranslation;

use EventCandyCandyBags\Core\Content\ItemSet\ItemSetEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class ItemSetTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $itemSetId;

    /**
     * @var ItemSetEntity
     */
    protected $itemSet;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getItemSetId(): string
    {
        return $this->itemSetId;
    }

    /**
     * @param string $itemSetId
     */
    public function setItemSetId(string $itemSetId): void
    {
        $this->itemSetId = $itemSetId;
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




}