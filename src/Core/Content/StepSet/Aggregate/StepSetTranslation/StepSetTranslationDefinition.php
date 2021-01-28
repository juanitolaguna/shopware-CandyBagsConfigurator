<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet\Aggregate\StepSetTranslation;

use EventCandyCandyBags\Core\Content\StepSet\StepSetDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class StepSetTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'eccb_step_set_translation';
    }

    public function getCollectionClass(): string
    {
        return StepSetTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return StepSetTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return StepSetDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('description', 'description'))->addFlags(new AllowHtml()),
            (new StringField('additional_data', 'additionalData'))->addFlags(new AllowHtml())
        ]);
    }
}