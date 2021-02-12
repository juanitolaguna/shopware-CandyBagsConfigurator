<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard;

use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardTranslation\ItemCardTranslationDefinition;
use EventCandyCandyBags\Core\Content\Item\ItemDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ItemCardDefinition extends EntityDefinition
{

    public const ENTITY_NAME = 'eccb_item_card';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ItemCardCollection::class;
    }

    public function getEntityClass(): string
    {
        return ItemCardEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))
                ->addFlags(new Required(), new PrimaryKey()),

            new StringField('internal_name', 'internalName'),

            new FkField('media_id', 'mediaId', MediaDefinition::class),
            new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class
            ),

            //Welchen Items bin ich zugewiesen
            (new OneToManyAssociationField('items', ItemDefinition::class, 'item_set_id', 'id')),

            new TranslatedField('name'),
            new TranslatedField('description'),
            new TranslatedField('additionalData'),
            new TranslationsAssociationField(ItemCardTranslationDefinition::class, 'eccb_item_card_id'),
        ]);

    }

}