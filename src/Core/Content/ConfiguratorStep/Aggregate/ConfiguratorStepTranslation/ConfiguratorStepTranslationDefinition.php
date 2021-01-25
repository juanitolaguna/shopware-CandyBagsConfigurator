<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ConfiguratorStep\Aggregate\ConfiguratorStepTranslation;

use EventCandyCandyBags\Core\Content\Card\CardDefinition;
use EventCandyCandyBags\Core\Content\ConfiguratorStep\ConfiguratorStepDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ConfiguratorStepTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'eccb_configurator_step_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ConfiguratorStepTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return ConfiguratorStepTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return ConfiguratorStepDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('description', 'description')),
            (new StringField('step_description', 'stepDescription'))
        ]);
    }
}