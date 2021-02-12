<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void            add(ItemCardTranslationEntity $entity)
 * @method void            set(string $key, ItemCardTranslationEntity $entity)
 * @method ItemCardTranslationEntity[]    getIterator()
 * @method ItemCardTranslationEntity[]    getElements()
 * @method ItemCardTranslationEntity|null get(string $key)
 * @method ItemCardTranslationEntity|null first()
 * @method ItemCardTranslationEntity|null last()
 */
class ItemCardTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ItemCardTranslationEntity::class;
    }
}