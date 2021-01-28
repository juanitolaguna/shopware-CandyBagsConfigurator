<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item;


use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemTranslation\ItemTranslationDefinition;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ItemDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_item';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ItemCollection::class;
    }

    public function getEntityClass(): string
    {
        return ItemEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([

            (new IdField('id', 'id'))
                ->addFlags(new Required(), new PrimaryKey()),

            new IntField('position', 'position'),
            new BoolField('active', 'active'),
            new BoolField('terminal', 'terminal'),
            new BoolField('purchasable', 'purchasable'),

            new StringField('internal_name', 'internalName'),
            (new StringField('type', 'type'))->addFlags(new Required()),

            new FkField('item_set_id', 'itemSet', ItemSetDefinition::class),
            new ManyToOneAssociationField(
                'itemSet',
                'item_set_id',
                ItemSetDefinition::class
            ),


            new FkField('media_id', 'mediaId', MediaDefinition::class),
            new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class
            ),

            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Inherited()),
            new FkField('product_id', 'productId', MediaDefinition::class),
            new ManyToOneAssociationField(
                'product',
                'product_id',
                ProductDefinition::class
            ),

            // An Item may be associated to many nodes
            // treeNodes are mutually exclusive with the itemSet property
            (new OneToManyAssociationField('treeNodes', TreeNodeDefinition::class, 'tree_node_id', 'id')),

            new TranslatedField('name'),
            new TranslatedField('description'),
            new TranslatedField('additionalItemData'),
            new TranslationsAssociationField(ItemTranslationDefinition::class, 'eccb_item_id'),
        ]);
    }

}