<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ConfiguratorStep;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                add(ConfiguratorStepEntity $entity)
 * @method void                set(string $key, ConfiguratorStepEntity $entity)
 * @method ConfiguratorStepEntity[]    getIterator()
 * @method ConfiguratorStepEntity[]    getElements()
 * @method ConfiguratorStepEntity|null get(string $key)
 * @method ConfiguratorStepEntity|null first()
 * @method ConfiguratorStepEntity|null last()
 */
class ConfiguratorStepCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'eccb_configurator_step';
    }

    protected function getExpectedClass(): string
    {
        return ConfiguratorStepEntity::class;
    }
}