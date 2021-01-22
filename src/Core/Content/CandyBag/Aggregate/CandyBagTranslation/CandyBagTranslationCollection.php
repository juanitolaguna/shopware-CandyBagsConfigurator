<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\CandyBag\Aggregate\CandyBagTranslation;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(CandyBagTranslationCollection $entity)
 * @method void              set(string $key, CandyBagTranslationCollection $entity)
 * @method CandyBagTranslationCollection[]    getIterator()
 * @method CandyBagTranslationCollection[]    getElements()
 * @method CandyBagTranslationCollection|null get(string $key)
 * @method CandyBagTranslationCollection|null first()
 * @method CandyBagTranslationCollection|null last()
 */
class CandyBagTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return CandyBagTranslationEntity::class;
    }
}