<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Checkout\Cart\Extension;

use EventCandy\Sets\Core\Content\DynamicProduct\Cart\DynamicProduct;
use EventCandy\Sets\Core\Content\DynamicProduct\Cart\DynamicProductService;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Framework\Uuid\Uuid;

class CandyBagsDynamicProductService extends DynamicProductService
{
    /**
     * @param array $lineItems
     * @param string $token
     * @return array
     */
    public function createDynamicProductCollection(array $lineItems, string $token): array
    {
        $collection = [];
        foreach ($lineItems as $lineItem) {
            $products = $this->getProductIdsForLineItem($lineItem);
            foreach ($products as $productId) {
                $id = Uuid::randomHex();
                $collection[] = new DynamicProduct(
                    $id,
                    $token,
                    $productId,
                    $lineItem->getId()
                );
            }
        }
        return $collection;
    }

    /**
     * @param LineItem $lineItem
     * @return array
     */
    private function getProductIdsForLineItem(LineItem $lineItem): array
    {
        $filteredProductIds = [];
        /** @var array $item */
        foreach ($lineItem->getPayload()['selected'] as $item) {
            if ($item['cardType'] == 'treeNodeCard') {
                $itemCard = $item['item']['itemCard'];
                $id = $itemCard['productId'];

                if ($id !== null) {
                    $filteredProductIds[] = $id;
                }
            } else {
                $itemCard = $item['itemCard'];
                $id = $itemCard['productId'];

                if ($id !== null) {
                    $filteredProductIds[] = $id;
                }
            }
        }
        return $filteredProductIds;
    }

}