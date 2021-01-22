<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Card;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(CardCollection $entity)
 * @method void              set(string $key, CardCollection $entity)
 * @method CardCollection[]    getIterator()
 * @method CardCollection[]    getElements()
 * @method CardCollection|null get(string $key)
 * @method CardCollection|null first()
 * @method CardCollection|null last()
 */
class CardCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return CardEntity::class;
    }
}