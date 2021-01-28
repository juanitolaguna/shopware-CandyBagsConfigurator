<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                add(ItemEntity $entity)
 * @method void                set(string $key, ItemEntity $entity)
 * @method ItemEntity[]    getIterator()
 * @method ItemEntity[]    getElements()
 * @method ItemEntity|null get(string $key)
 * @method ItemEntity|null first()
 * @method ItemEntity|null last()
 */
class ItemCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'eccb_item';
    }

    protected function getExpectedClass(): string
    {
        return ItemEntity::class;
    }
}