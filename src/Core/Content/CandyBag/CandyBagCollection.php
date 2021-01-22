<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\CandyBag;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(CandyBagCollection $entity)
 * @method void              set(string $key, CandyBagCollection $entity)
 * @method CandyBagCollection[]    getIterator()
 * @method CandyBagCollection[]    getElements()
 * @method CandyBagCollection|null get(string $key)
 * @method CandyBagCollection|null first()
 * @method CandyBagCollection|null last()
 */
class CandyBagCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return CandyBagEntity::class;
    }
}