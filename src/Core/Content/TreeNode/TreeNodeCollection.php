<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                add(TreeNodeEntity $entity)
 * @method void                set(string $key, TreeNodeEntity $entity)
 * @method TreeNodeEntity[]    getIterator()
 * @method TreeNodeEntity[]    getElements()
 * @method TreeNodeEntity|null get(string $key)
 * @method TreeNodeEntity|null first()
 * @method TreeNodeEntity|null last()
 */
class TreeNodeCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'eccb_tree_node';
    }

    protected function getExpectedClass(): string
    {
        return TreeNodeEntity::class;
    }
}