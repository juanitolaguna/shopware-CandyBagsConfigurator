<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ItemSet\Aggregate\ItemSetTranslation;

use EventCandyCandyBags\Core\Content\ItemSet\ItemSetDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ItemSetTranslationDefinition extends EntityTranslationDefinition {
    public function getEntityName(): string
    {
        return 'eccb_item_set_translation';
    }

    public function getCollectionClass(): string
    {
        return ItemSetTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return ItemSetTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return ItemSetDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
        ]);
    }
}