<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeItemSet;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;


/**
 * @method void              add( TreeNodeItemSetEntity $entity )
 * @method void              set( string $key, TreeNodeItemSetEntity $entity )
 * @method TreeNodeItemSetEntity[]    getIterator()
 * @method TreeNodeItemSetEntity[]    getElements()
 * @method TreeNodeItemSetEntity|null get( string $key )
 * @method TreeNodeItemSetEntity|null first()
 * @method TreeNodeItemSetEntity|null last()
 */
class TreeNodeItemSetCollection extends EntityCollection
{

    public function getApiAlias(): string
    {
        return 'eccb_tree_node_item_set';
    }

    protected function getExpectedClass(): string
    {
        return TreeNodeItemSetEntity::class;
    }

}
