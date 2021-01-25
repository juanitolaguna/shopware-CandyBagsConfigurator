<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ConfiguratorStep\Aggregate\ConfiguratorStepTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(ConfiguratorStepTranslationCollection $entity)
 * @method void              set(string $key, ConfiguratorStepTranslationCollection $entity)
 * @method ConfiguratorStepTranslationCollection[]    getIterator()
 * @method ConfiguratorStepTranslationCollection[]    getElements()
 * @method ConfiguratorStepTranslationCollection|null get(string $key)
 * @method ConfiguratorStepTranslationCollection|null first()
 * @method ConfiguratorStepTranslationCollection|null last()
 */
class ConfiguratorStepTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ConfiguratorStepTranslationEntity::class;
    }
}