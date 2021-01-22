<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Step\Aggregate\StepTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(StepTranslationCollection $entity)
 * @method void              set(string $key, StepTranslationCollection $entity)
 * @method StepTranslationCollection[]    getIterator()
 * @method StepTranslationCollection[]    getElements()
 * @method StepTranslationCollection|null get(string $key)
 * @method StepTranslationCollection|null first()
 * @method StepTranslationCollection|null last()
 */
class StepTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return StepTranslationEntity::class;
    }
}