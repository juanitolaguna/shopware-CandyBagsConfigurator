<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode;

use EventCandyCandyBags\Core\Content\Item\ItemDefinition;
use EventCandyCandyBags\Core\Content\ItemSet\ItemSetDefinition;
use EventCandyCandyBags\Core\Content\StepSet\StepSetDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeTranslation\TreeNodeTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ChildrenAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
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

            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            new BoolField('set_node', 'setNode'),


            (new OneToOneAssociationField('item', 'id', 'tree_node_id', ItemDefinition::class)),

            new FkField('item_set_id', 'itemSetId', ItemSetDefinition::class),
            new ManyToOneAssociationField(
                'itemSet',
                'item_set_id',
                ItemSetDefinition::class
            ),

            new FkField('step_set_id', 'stepSetId', StepSetDefinition::class),
            new ManyToOneAssociationField(
                'stepSet',
                'step_set_id',
                StepSetDefinition::class
            ),

            new ParentFkField(self::class),
            new ParentAssociationField(self::class, 'id'),

            new ChildrenAssociationField(self::class),

            new TranslatedField('stepDescription'),
            (new TranslationsAssociationField(TreeNodeTranslationDefinition::class, 'eccb_tree_node_id'))
                ->addFlags(new Required())
        ]);
    }
}