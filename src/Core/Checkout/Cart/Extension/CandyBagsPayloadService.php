<?php

declare(strict_types=1);

namespace EventCandyCandyBags\Core\Checkout\Cart\Extension;


use EventCandy\Sets\Core\Checkout\Cart\Payload\PayloadLineItem;
use EventCandy\Sets\Core\Checkout\Cart\Payload\PayloadService;
use EventCandy\Sets\Core\Subscriber\LineItemAddToCartSubscriber;
use EventCandy\Sets\Utils;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Framework\Uuid\Uuid;

class CandyBagsPayloadService extends PayloadService
{

    /**
     * @param LineItem $lineItem
     * @param string $payloadKey
     * @return string[]
     */
    public function makeCartInfo(LineItem $lineItem, string $payloadKey): array
    {
        $cartInfo = "";
        foreach ($lineItem->getPayload()['selected'] as $item) {
            if ($item['cardType'] == 'treeNodeCard') {
                $itemCard = $item['item']['itemCard'];
                $cartInfo .= "- " . $itemCard['name'] . "\n";
            } else {
                $itemCard = $item['itemCard'];
                $cartInfo .= "- " . $itemCard['name'] . "\n";
            }
        }
        return [$payloadKey => $cartInfo];
    }

    /**
     * Creates Data that is not derived from product, but is needed for commissioning
     * @param LineItem $lineItem
     * @param string $key
     * @return array[]
     */
    public function makePacklistDataWithoutProducts(LineItem $lineItem, string $key): array
    {

        $data = [];
        foreach ($lineItem->getPayload()['selected'] as $item) {
            if ($item['cardType'] == 'treeNodeCard') {
                $itemCard = $item['item']['itemCard'];
                $name = $itemCard['name'];
                $productId = $itemCard['product'] ? $itemCard['product']['id'] : false;
                if(!$productId) {
                    $data[] = $name;
                }

            } else {
                $itemCard = $item['itemCard'];
                $name = $itemCard['name'];
                $productId = $itemCard['product'] ? $itemCard['product']['id'] :  false;
                if(!$productId) {
                    $data[] = $name;
                }
            }
        }
        return [$key => $data];
    }

    /**
     * @param LineItem $lineItem
     * @param string $key
     * @return array[]
     */
    public function makePacklistDataWithProducts(LineItem $lineItem, string $key): array
    {
        $data = [];
        foreach ($lineItem->getPayload()['selected'] as $item) {
            if ($item['cardType'] == 'treeNodeCard') {
                $itemCard = $item['item']['itemCard'];
            } else {
                $itemCard = $item['itemCard'];
            }
            $name = $itemCard['name'];
            $data[] = $name;
        }
        return [$key => $data];
    }
}