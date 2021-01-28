<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;


/**
 * @method void                add(StepSetEntity $entity)
 * @method void                set(string $key, StepSetEntity $entity)
 * @method StepSetEntity[]    getIterator()
 * @method StepSetEntity[]    getElements()
 * @method StepSetEntity|null get(string $key)
 * @method StepSetEntity|null first()
 * @method StepSetEntity|null last()
 */
class StepSetCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'eccb_step_set';
    }

    protected function getExpectedClass(): string
    {
        return StepSetEntity::class;
    }

}