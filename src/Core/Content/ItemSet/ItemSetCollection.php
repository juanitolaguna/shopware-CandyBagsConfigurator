<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ItemSet;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                add(ItemSetEntity $entity)
 * @method void                set(string $key, ItemSetEntity $entity)
 * @method ItemSetEntity[]    getIterator()
 * @method ItemSetEntity[]    getElements()
 * @method ItemSetEntity|null get(string $key)
 * @method ItemSetEntity|null first()
 * @method ItemSetEntity|null last()
 */
class ItemSetCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'eccb_item_set';
    }

    protected function getExpectedClass(): string
    {
        return ItemSetEntity::class;
    }
}