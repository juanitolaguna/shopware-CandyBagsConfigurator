<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet\Aggregate\StepSetTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void            add(StepSetTranslationEntity $entity)
 * @method void            set(string $key, StepSetTranslationEntity $entity)
 * @method StepSetTranslationEntity[]    getIterator()
 * @method StepSetTranslationEntity[]    getElements()
 * @method StepSetTranslationEntity|null get(string $key)
 * @method StepSetTranslationEntity|null first()
 * @method StepSetTranslationEntity|null last()
 */
class StepSetTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return StepSetTranslationEntity::class;
    }
}