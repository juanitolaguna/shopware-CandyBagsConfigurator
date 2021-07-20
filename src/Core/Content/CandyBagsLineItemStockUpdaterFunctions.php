<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content;

use EventCandy\Sets\Core\Content\Product\DataAbstractionLayer\LineItemStockUpdaterFunctionsInterface;
use EventCandyCandyBags\Core\Checkout\Cart\CandyBagsCartProcessor;
use EventCandyCandyBags\Utils;
use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Shopware\Core\Checkout\Order\Aggregate\OrderLineItem\OrderLineItemEntity;
use Shopware\Core\Framework\Uuid\Uuid;

class CandyBagsLineItemStockUpdaterFunctions implements LineItemStockUpdaterFunctionsInterface
{

    public function getLineItemType(): string
    {
        return CandyBagsCartProcessor::TYPE;
    }

    public function createOrderLineItemProducts(OrderLineItemEntity $lineItem, CheckoutOrderPlacedEvent $event): array
    {
        $order = $event->getOrder();
        $lineItemQuantity = $lineItem->getQuantity();
        $subProducts = $lineItem->getPayload()['order_line_item_products'];

        Utils::log(print_r($subProducts, true));

        $orderLineItems = [];
        foreach ($subProducts as $product) {

            $main = $product['mainProduct'];
            $mainOrderLineItemId = Uuid::randomHex();
            $orderLineItems[] = [
                'id' => $mainOrderLineItemId,
                'productId' => $main['product_id'],
                'orderId' => $order->getId(),
                'orderLineItemId' => $lineItem->getId(),
                'quantity' => $lineItemQuantity
            ];

            foreach ($product['subProducts'] as $sub) {
                $orderLineItems[] = [
                    'id' => Uuid::randomHex(),
                    'parentId' => $mainOrderLineItemId,
                    'productId' => $sub['product_id'],
                    'orderId' => $order->getId(),
                    'orderLineItemId' => $lineItem->getId(),
                    'quantity' => $sub['quantity'] * $lineItemQuantity
                ];
            }
        }
        return $orderLineItems;
    }
}