<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode;

use EventCandyCandyBags\Core\Content\Item\ItemDefinition;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetDefinition;
use EventCandyCandyBags\Core\Content\StepSet\StepSetDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeItemSet\TreeNodeItemSetDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeTranslation\TreeNodeTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ChildrenAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ParentAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ParentFkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class TreeNodeDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_tree_node';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return TreeNodeCollection::class;
    }

    public function getEntityClass(): string
    {
        return TreeNodeEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([

            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required(), new ApiAware()),

            (new OneToOneAssociationField('item', 'id', 'tree_node_id', ItemDefinition::class))->addFlags(new ApiAware()),


            (new FkField('tree_node_item_set_id', 'treeNodeItemSetId', TreeNodeItemSetDefinition::class))->addFlags(new ApiAware()),
            (new OneToOneAssociationField('treeNodeItemSet', 'tree_node_item_set_id', 'id', TreeNodeItemSetDefinition::class))->addFlags(new ApiAware()),


            (new FkField('step_set_id', 'stepSetId', StepSetDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField(
                'stepSet',
                'step_set_id',
                StepSetDefinition::class
            ))->addFlags(new ApiAware()),

            (new ParentFkField(self::class))->addFlags(new ApiAware()),
            (new ParentAssociationField(self::class, 'id'))->addFlags(new ApiAware()),

            (new ChildrenAssociationField(self::class))->addFlags(new ApiAware()),

            (new TranslatedField('stepDescription'))->addFlags(new ApiAware()),
            (new TranslationsAssociationField(TreeNodeTranslationDefinition::class, 'eccb_tree_node_id'))
                ->addFlags(new Required()),

            (new ManyToManyAssociationField(
                'itemSets',
                ItemSetDefinition::class,
                TreeNodeItemSetDefinition::class,
                'tree_node_id',
                'item_set_id'
            ))->addFlags(new ApiAware())
        ]);
    }
}