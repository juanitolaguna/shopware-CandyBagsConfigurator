<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeItemSet;

use EventCandyCandyBags\Core\Content\ItemSet\ItemSetDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class TreeNodeItemSetDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_tree_node_item_set';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return TreeNodeItemSetCollection::class;
    }

    public function getEntityClass(): string
    {
        return TreeNodeItemSetEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))
                ->addFlags(new Required(), new PrimaryKey(), new ApiAware()),

            (new FkField('tree_node_id', 'treeNodeId', TreeNodeDefinition::class))
                ->addFlags(new Required(), new ApiAware()),

            (new FkField('item_set_id', 'itemSetId', ItemSetDefinition::class))
                ->addFlags(new Required(), new ApiAware()),

            (new ManyToOneAssociationField('treeNode', 'tree_node_id', TreeNodeDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('itemSet', 'item_set_id', ItemSetDefinition::class))->addFlags(new ApiAware()),

            (new OneToOneAssociationField('childNode', 'id', 'tree_node_item_set_id', TreeNodeDefinition::class, false))->addFlags(new ApiAware())
        ]);
    }
}