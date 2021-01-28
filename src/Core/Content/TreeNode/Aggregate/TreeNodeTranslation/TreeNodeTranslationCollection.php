<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(TreeNodeTranslationEntity $entity)
 * @method void              set(string $key, TreeNodeTranslationEntity $entity)
 * @method TreeNodeTranslationEntity[]    getIterator()
 * @method TreeNodeTranslationEntity[]    getElements()
 * @method TreeNodeTranslationEntity|null get(string $key)
 * @method TreeNodeTranslationEntity|null first()
 * @method TreeNodeTranslationEntity|null last()
 */
class TreeNodeTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return TreeNodeTranslationEntity::class;
    }
}