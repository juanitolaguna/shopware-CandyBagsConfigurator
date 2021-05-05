<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardTranslation;

use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ItemCardTranslationDefinition extends EntityTranslationDefinition {
    public function getEntityName(): string
    {
        return 'eccb_item_card_translation';
    }

    public function getCollectionClass(): string
    {
        return ItemCardTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return ItemCardTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return ItemCardDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new LongTextField('description', 'description'))->addFlags(new AllowHtml()),
            (new LongTextField('additional_data', 'additionalData'))->addFlags(new AllowHtml())
        ]);
    }
}