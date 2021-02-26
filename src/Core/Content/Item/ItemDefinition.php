<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item;


use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardDefinition;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class   ItemDefinition extends EntityDefinition
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

            (new StringField('type', 'type'))->addFlags(new Required()),
            (new StringField('internal_name', 'internalName')),

            new FkField('tree_node_id', 'treeNodeId', TreeNodeDefinition::class),
            (new OneToOneAssociationField('treeNode', 'tree_node_id', 'id', TreeNodeDefinition::class, false)),


            // Nur für navigationszwecke
            new FkField('item_set_id', 'itemSetId', ItemSetDefinition::class),
            new ManyToOneAssociationField(
                'itemSet',
                'item_set_id',
                ItemSetDefinition::class
            ),

            /** Concrete Item Types */

            new FkField('item_card_id', 'itemCardId', ItemCardDefinition::class),
            new ManyToOneAssociationField(
                'itemCard',
                'item_card_id',
                ItemCardDefinition::class
            ),

        ]);
    }

}

/** ToDo: Add ManyToOne Field - ProductItem */