<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ItemSet\Aggregate\ItemSetTranslation;


use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void            add(ItemSetTranslationEntity $entity)
 * @method void            set(string $key, ItemSetTranslationEntity $entity)
 * @method ItemSetTranslationEntity[]    getIterator()
 * @method ItemSetTranslationEntity[]    getElements()
 * @method ItemSetTranslationEntity|null get(string $key)
 * @method ItemSetTranslationEntity|null first()
 * @method ItemSetTranslationEntity|null last()
 */
class ItemSetTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ItemSetTranslationEntity::class;
    }
}