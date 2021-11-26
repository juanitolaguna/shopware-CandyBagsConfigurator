<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content;

use EventCandy\Sets\Core\Content\Product\DataAbstractionLayer\SetProductLineItemStockUpdaterFunctions;
use EventCandyCandyBags\Core\Checkout\Cart\CandyBagsCartCollector;

class CandyBagsLineItemStockUpdaterFunctions extends SetProductLineItemStockUpdaterFunctions
{
    public function getLineItemType(): string
    {
        return CandyBagsCartCollector::TYPE;
    }
}