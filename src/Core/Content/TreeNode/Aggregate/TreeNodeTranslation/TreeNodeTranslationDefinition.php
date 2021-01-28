<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeTranslation;

use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class TreeNodeTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'eccb_tree_node_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return TreeNodeTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return TreeNodeTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return TreeNodeDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            new StringField('step_description', 'stepDescription')
        ]);
    }
}