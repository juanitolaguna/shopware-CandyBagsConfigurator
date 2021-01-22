<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Card;

use EventCandyCandyBags\Core\Content\Card\Aggregate\CardTranslation\CardTranslationDefinition;
use EventCandyCandyBags\Core\Content\Step\StepDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CardDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_card';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CardCollection::class;
    }

    public function getEntityClass(): string
    {
        return CardEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([

            (new IdField('id', 'id'))
                ->addFlags(new Required(), new PrimaryKey()),

            (new StringField('name', 'name'))
                ->addFlags(new Required()),

            (new StringField('description', 'description')),

            (new BoolField('active', 'active')),

            (new IntField('position', 'position')),

            new FkField('media_id', 'mediaId', MediaDefinition::class),

            new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class
            ),

            new ManyToOneAssociationField(
                'step',
                'step_id',
                StepDefinition::class
            ),

            new TranslationsAssociationField(CardTranslationDefinition::class, 'eccb_card_id'),
        ]);
    }
}