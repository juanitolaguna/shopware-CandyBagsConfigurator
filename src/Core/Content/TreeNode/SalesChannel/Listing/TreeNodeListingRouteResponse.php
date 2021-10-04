<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\SalesChannel\Listing;

use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\System\SalesChannel\StoreApiResponse;

class TreeNodeListingRouteResponse extends StoreApiResponse
{
    /**
     * @var EntitySearchResult
     */
    protected $object;

    public function __construct(EntitySearchResult $object)
    {
        parent::__construct($object);
    }
}