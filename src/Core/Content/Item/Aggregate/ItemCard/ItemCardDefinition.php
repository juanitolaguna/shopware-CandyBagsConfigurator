<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard;

use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardTranslation\ItemCardTranslationDefinition;
use EventCandyCandyBags\Core\Content\Item\ItemDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductManufacturer\ProductManufacturerDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
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
                ->addFlags(new Required(), new PrimaryKey(), new ApiAware()),

            (new BoolField('show_price', 'showPrice'))->addFlags(new ApiAware()),

            new StringField('internal_name', 'internalName'),

            (new FkField('media_id', 'mediaId', MediaDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class
            ))->addFlags(new ApiAware()),

            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField(
                'product',
                'product_id',
                ProductDefinition::class
            ))->addFlags(new ApiAware()),
            // ToDo, implement version id field.
            //(new ReferenceVersionField(ProductDefinition::class))->addFlags(new Required()),

            //Welchen Items bin ich zugewiesen
            (new OneToManyAssociationField('items', ItemDefinition::class, 'item_card_id', 'id'))->addFlags(new ApiAware()),

            (new TranslatedField('name'))->addFlags(new ApiAware()),
            (new TranslatedField('description'))->addFlags(new ApiAware()),
            (new TranslatedField('additionalData'))->addFlags(new ApiAware()),
            new TranslationsAssociationField(ItemCardTranslationDefinition::class, 'eccb_item_card_id')
        ]);

    }

}