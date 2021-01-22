<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Card\Aggregate\CardTranslation;

use EventCandyCandyBags\Core\Content\Card\CardDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CardTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'eccb_card_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CardTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return CardTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return CardDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('description', 'description'))
        ]);
    }
}