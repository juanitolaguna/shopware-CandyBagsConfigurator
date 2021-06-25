<?php

namespace EventCandyCandyBags\Core\Checkout\Cart;

use EventCandy\Sets\Core\Checkout\Cart\SetProductCartProcessor;
use EventCandy\Sets\Core\Checkout\Cart\SubProductQuantityInCartReducerInterface;
use EventCandyCandyBags\Utils;
use OpenApi\Util;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CandyBagsSubProductCartReducer implements SubProductQuantityInCartReducerInterface
{

    public function reduce(Cart $cart, string $relatedMainId, string $mainProductId, int $subProductQuantity, SalesChannelContext $context = null): int
    {

        $lineItems = $cart->getLineItems()->filterFlatByType(CandyBagsCartProcessor::TYPE);
        if (count($lineItems) == 0) {
            return 0;
        }

        $baseLineItemId = null;
        if ($context && $context->getExtension('lineItem')) {
            /** @var LineItem $baseLineItem */
            $baseLineItem = $context->getExtension('lineItem');
            $baseLineItemId = $baseLineItem ? $baseLineItem->getId() : null;
        }


        $counter = 0;
        foreach ($lineItems as $lineItem) {
            $productsIds = CandyBagsCartProcessor::getProductIdsForLineItem($lineItem);

            $skippedFirst = false;
            foreach ($productsIds as $productId) {

                if ($productId === $relatedMainId) {
                    // skip first matching element if check is coming from self.
                    if (($baseLineItemId === $lineItem->getId()) && !$skippedFirst) {
                        $skippedFirst = true;
                        continue;
                    }
                    $counter += $lineItem->getQuantity() * $subProductQuantity;
                }

            }
        }
        return $counter;
    }
}