<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item;


use EventCandyCandyBags\Core\Content\Item\Aggregate\ItemCard\ItemCardDefinition;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
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
                ->addFlags(new Required(), new PrimaryKey(), new ApiAware()),

            (new IntField('position', 'position'))->addFlags(new ApiAware()),
            (new BoolField('active', 'active'))->addFlags(new ApiAware()),
            (new BoolField('terminal', 'terminal'))->addFlags(new ApiAware()),
            (new BoolField('purchasable', 'purchasable'))->addFlags(new ApiAware()),

            (new StringField('type', 'type'))->addFlags(new Required(), new ApiAware()),
            (new StringField('internal_name', 'internalName'))->addFlags(new ApiAware()),

            (new FkField('tree_node_id', 'treeNodeId', TreeNodeDefinition::class))->addFlags(new ApiAware()),
            (new OneToOneAssociationField('treeNode', 'tree_node_id', 'id', TreeNodeDefinition::class, false))->addFlags(new ApiAware()),


            // Nur für navigationszwecke
            (new FkField('item_set_id', 'itemSetId', ItemSetDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField(
                'itemSet',
                'item_set_id',
                ItemSetDefinition::class
            ))->addFlags(new ApiAware()),

            /** Concrete Item Types */

            (new FkField('item_card_id', 'itemCardId', ItemCardDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField(
                'itemCard',
                'item_card_id',
                ItemCardDefinition::class
            ))->addFlags(new ApiAware()),

        ]);
    }

}

/** ToDo: Add ManyToOne Field - ProductItem */