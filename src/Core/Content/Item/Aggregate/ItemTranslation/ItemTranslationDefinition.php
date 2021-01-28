<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemTranslation;


use EventCandyCandyBags\Core\Content\Item\ItemDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ItemTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'eccb_item_translation';
    }

    public function getCollectionClass(): string
    {
        return ItemTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return ItemTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return ItemDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            new StringField('description', 'description'),
            (new StringField('additional_item_data', 'additionalItemData'))
                ->addFlags(new AllowHtml())
        ]);
    }
}