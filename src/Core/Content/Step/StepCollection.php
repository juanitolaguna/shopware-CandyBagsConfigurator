<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Step;


use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(StepCollection $entity)
 * @method void              set(string $key, StepCollection $entity)
 * @method StepCollection[]    getIterator()
 * @method StepCollection[]    getElements()
 * @method StepCollection|null get(string $key)
 * @method StepCollection|null first()
 * @method StepCollection|null last()
 */
class StepCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return StepEntity::class;
    }
}