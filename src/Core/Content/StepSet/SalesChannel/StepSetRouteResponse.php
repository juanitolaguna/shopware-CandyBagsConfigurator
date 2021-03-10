<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet\SalesChannel;


use EventCandyCandyBags\Core\Content\StepSet\StepSetCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\System\SalesChannel\StoreApiResponse;

class StepSetRouteResponse extends StoreApiResponse
{
    /**
     * @var EntitySearchResult
     */
    protected $object;

    public function __construct(EntitySearchResult $object)
    {
        parent::__construct($object);
    }

    public function getStepSets(): StepSetCollection
    {
        /** @var StepSetCollection $collection */
        $collection = $this->object->getEntities();

        return $collection;
    }
}