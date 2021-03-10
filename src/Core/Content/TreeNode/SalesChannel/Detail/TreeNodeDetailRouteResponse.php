<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\SalesChannel\Detail;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\System\SalesChannel\StoreApiResponse;

class TreeNodeDetailRouteResponse extends StoreApiResponse
{
    /**
     * @var Entity
     */
    protected $object;

    public function __construct(Entity $object)
    {
        parent::__construct($object);
    }
}