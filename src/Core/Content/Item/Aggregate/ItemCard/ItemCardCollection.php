<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                add(ItemCardEntity $entity)
 * @method void                set(string $key, ItemCardEntity $entity)
 * @method ItemCardEntity[]    getIterator()
 * @method ItemCardEntity[]    getElements()
 * @method ItemCardEntity|null get(string $key)
 * @method ItemCardEntity|null first()
 * @method ItemCardEntity|null last()
 */
class ItemCardCollection extends EntityCollection {
    public function getApiAlias(): string
    {
        return 'eccb_item_card';
    }

    protected function getExpectedClass(): string
    {
        return ItemCardEntity::class;
    }
}