<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\CandyBag\Aggregate\CandyBagTranslation;

use EventCandyCandyBags\Core\Content\CandyBag\CandyBagDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CandyBagTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'eccb_candy_bag_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CandyBagTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return CandyBagTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return CandyBagDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('description', 'description'))->addFlags(new AllowHtml())
        ]);
    }

}