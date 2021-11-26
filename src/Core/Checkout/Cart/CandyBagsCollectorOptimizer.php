<?php

declare(strict_types=1);

namespace EventCandyCandyBags\Core\Checkout\Cart;

use EventCandy\Sets\Core\Checkout\Cart\CollectorOptimizer\CollectorOptimizerInterface;
use EventCandyCandyBags\Core\Checkout\Cart\Extension\CandyBagsDynamicProductService;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CandyBagsCollectorOptimizer implements CollectorOptimizerInterface
{
    /**
     * @var CandyBagsDynamicProductService
     */
    private $dynamicProductService;

    /**
     * @param CandyBagsDynamicProductService $dynamicProductService
     */
    public function __construct(CandyBagsDynamicProductService $dynamicProductService)
    {
        $this->dynamicProductService = $dynamicProductService;
    }

    public function saveDynamicProductsBeforeCollect(
        CartDataCollection $data,
        Cart $original,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void {
        $lineItems = $original
            ->getLineItems()
            ->filterFlatByType(CandyBagsCartCollector::TYPE);

        $dynamicProducts = $this->dynamicProductService->createDynamicProductCollection(
            $lineItems,
            $context->getToken()
        );

        $this->dynamicProductService->saveDynamicProductsToDb($dynamicProducts);
    }
}