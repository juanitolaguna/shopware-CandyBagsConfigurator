<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Step\Aggregate\StepTranslation;

use EventCandyCandyBags\Core\Content\Step\StepDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class StepTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'eccb_step_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return StepTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return StepTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return StepDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('description', 'description'))
        ]);
    }
}