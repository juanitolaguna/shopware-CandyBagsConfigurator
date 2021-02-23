<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ItemSet;

use EventCandyCandyBags\Core\Content\Item\ItemDefinition;
use EventCandyCandyBags\Core\Content\ItemSet\Aggregate\ItemSetTranslation\ItemSetTranslationDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeItemSet\TreeNodeItemSetDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ItemSetDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_item_set';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ItemSetCollection::class;
    }

    public function getEntityClass(): string
    {
        return ItemSetEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))
                ->addFlags(new Required(), new PrimaryKey()),

            (new StringField('internal_name', 'internalName'))->addFlags(new Required()),
            (new OneToManyAssociationField('items', ItemDefinition::class, 'item_set_id', 'id'))
                ->addFlags(new CascadeDelete()),

            new ManyToManyAssociationField(
                'treeNodes',
                TreeNodeDefinition::class,
                TreeNodeItemSetDefinition::class,
                'item_set_id',
                'tree_node_id'
            )

        ]);
    }
}