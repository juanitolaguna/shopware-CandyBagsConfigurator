<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Checkout\Cart\Extension;


use EventCandy\Sets\Core\Checkout\Cart\Payload\PayloadLineItem;
use EventCandy\Sets\Core\Checkout\Cart\Payload\PayloadService;

class CandyBagsPayloadService extends PayloadService
{

    /**
     * @param PayloadLineItem $payloadLineItem
     * @param string $payloadKey
     * @return string[]
     */
    public function makeCartInfo(PayloadLineItem $payloadLineItem,string $payloadKey ): array
    {
        $cartInfo = "";
        foreach ($payloadLineItem->getProducts() as $product) {
            $cartInfo .= "- " . $product->getName() . "\n";
        }
        return [$payloadKey => $cartInfo];
    }
}