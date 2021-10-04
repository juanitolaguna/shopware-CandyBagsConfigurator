<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Checkout\Cart;

use Doctrine\DBAL\Connection;
use EventCandy\LabelMe\Core\Checkout\Cart\EclmCartProcessor;
use EventCandy\Sets\Core\Checkout\Cart\SetProductCartProcessor;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryInformation;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryTime;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\QuantityInformation;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Content\Product\Cart\ProductGatewayInterface;
use Shopware\Core\Content\Product\Cart\ProductOutOfStockError;
use Shopware\Core\Content\Product\Cart\ProductStockReachedError;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\Price;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\DeliveryTime\DeliveryTimeEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use function count;

class CandyBagsCartProcessor implements CartProcessorInterface, CartDataCollectorInterface
{

    /**
     * @var ProductGatewayInterface
     */
    private $productGateway;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityRepositoryInterface
     */
    private $mediaRepository;

    /**
     * @var QuantityPriceCalculator
     */
    private $calculator;

    public const TYPE = 'event-candy-candy-bags';
    public const DATA_KEY = 'eccb-';
    public const MIN_PURCHASE = 1;
    public const MAX_PURCHASE = 20;
    public const PURCHASE_STEPS = 1;
    public const SHIPPING_FREE = false;


    /**
     * CandyBagsCartProcessor constructor.
     * @param ProductGatewayInterface $productGateway
     * @param Connection $connection
     * @param EntityRepositoryInterface $mediaRepository
     * @param QuantityPriceCalculator $calculator
     */
    public function __construct(ProductGatewayInterface $productGateway, Connection $connection, EntityRepositoryInterface $mediaRepository, QuantityPriceCalculator $calculator)
    {
        $this->productGateway = $productGateway;
        $this->connection = $connection;
        $this->mediaRepository = $mediaRepository;
        $this->calculator = $calculator;
    }


    /**
     * ToDo: Warum wird collect so ofr aufgerufen?
     * @param CartDataCollection $data
     * @param Cart $original
     * @param SalesChannelContext $context
     * @param CartBehavior $behavior
     */
    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $lineItems = $original
            ->getLineItems()
            ->filterType(self::TYPE);

        if (count($lineItems) === 0) {
            return;
        }


        // fetch lineItem products and subproducts
        // add them to CartDataCollection
        foreach ($lineItems as $lineItem) {

            $key = self::DATA_KEY . $lineItem->getReferencedId();
            if (!$data->has($key)) {

                // fetch product Ids for all LineItems
                // get all LineItems
                $products = $this->fetchProductsForLineItem($lineItem, $context);

                /** @link fetchProductsForLineItem -> adding extension for reducer */
                $context->removeExtension('lineItem');
                $productsAry = $this->getSubProducts($products, $context);
                $data->set($key, $productsAry);


                $productStockInfo = [];
                foreach ($productsAry as $product) {
                    /** @var SalesChannelProductEntity $mainProduct */
                    $mainProduct = $product['product'];
                    $productStockInfo[] = [
                        'mainProduct' => [
                            'product_number' => $mainProduct->getProductNumber(),
                            'name' => $mainProduct->getTranslation('name'),
                            'product_id' => $mainProduct->getId(),
                            'product_version_id' => $mainProduct->getVersionId(),
                            'quantity' => 1,
                        ],
                        'subProducts' => $product['sub_product_payload']
                    ];
                }

                $formattedInfo = $this->createLabelAndFlinkData($productsAry, $lineItem);
                $lineItem->setPayload(['line_item_sub_products' => $formattedInfo['flinkAggregate']]);
                $lineItem->setPayload(['cart_info' => $formattedInfo['cartInfo']]);
                $lineItem->setPayload(['order_line_item_products' => $productStockInfo]);
            }

        }

        foreach ($lineItems as $lineItem) {
            if ($this->isComplete($lineItem) && !$lineItem->isModified()) {
                return;
            }

            $stepSet = $lineItem->getPayloadValue('stepSet');
            $lineItem->setLabel($stepSet['name']);

            $lineItem->setDescription('test description');

            $id = $stepSet['selectionBaseImage']['id'] ?? null;
            if ($id) {
                $image = $this->getLineItemImage($id, $context);
                $lineItem->setCover($image);
            }

            $deliveryTime = $this->getDeliveryTimeFromProducts($lineItem, $data);
            $availableStock = self::getAvailableStock($lineItem, $data);
            $weight = $this->calculateWeight($lineItem, $data);
            $restockTime = $this->calculateRestockTime($lineItem, $data);


            $lineItem->setDeliveryInformation(
                new DeliveryInformation(
                    $availableStock,
                    $weight,
                    self::SHIPPING_FREE,
                    $restockTime,
                    $deliveryTime ? $deliveryTime : null
                )
            );

            $quantityInformation = new QuantityInformation();
            $quantityInformation
                ->setMaxPurchase($availableStock)
                ->setMinPurchase(self::MIN_PURCHASE)
                ->setPurchaseSteps(self::PURCHASE_STEPS);

            $lineItem->setQuantityInformation($quantityInformation);
        }
    }

    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $lineItems = $original->getLineItems()->filterType(self::TYPE);

        if (count($lineItems) === 0) {
            return;
        }

        foreach ($lineItems as $lineItem) {

            /*
             * ToDo: ...comment
             * This code snippet behaves strange
             * removes lineItem from Cart but
             * does not trigger out of Stock error...
             *
             *
             */
//            if ($lineItem->getPayloadValue('outOfStock')) {
//                Utils::log('outOfStock');
//                $toCalculate->addErrors(
//                    new ProductOutOfStockError($lineItem->getId(), $lineItem->getLabel())
//                );
//            }



            if ($lineItem->getQuantityInformation()->getMaxPurchase() <= 0) {
                $original->remove($lineItem->getId());

                $toCalculate->addErrors(
                    new ProductOutOfStockError($lineItem->getId(), $lineItem->getLabel())
                );
                continue;
            }


            if ($lineItem->getQuantityInformation()->getMaxPurchase() < $lineItem->getQuantity()) {
                $maxQuantity = $lineItem->getQuantityInformation()->getMaxPurchase();
                $lineItem->setQuantity($maxQuantity);

                $toCalculate->addErrors(
                    new ProductStockReachedError($lineItem->getId(), $lineItem->getLabel(), $maxQuantity)
                );
            }


            $payload = $lineItem->getPayload();
            $taxId = $payload['stepSet']['taxId'] ?? $this->getProductWithHighestTaxRate($lineItem, $data)->getTaxId();
            $taxRules = $context->buildTaxRules($taxId);
            $currencyPrice = $this->getProductCurrencyPrice($lineItem, $data, $context);

            $quantityPriceDefinition = new QuantityPriceDefinition(
                $currencyPrice,
                $taxRules,
                $lineItem->getQuantity()
            );

            $calculatedPrice = $this->calculator->calculate($quantityPriceDefinition, $context);
            $lineItem->setPrice($calculatedPrice);
            $toCalculate->add($lineItem);
        }

    }

    private function isComplete(LineItem $lineItem): bool
    {
        return $lineItem->getPriceDefinition() !== null
            && $lineItem->getLabel() !== null
            && $lineItem->getCover() !== null
            && $lineItem->getDescription() !== null
            && $lineItem->getDeliveryInformation() !== null
            && $lineItem->getQuantityInformation() !== null;
    }

    /**
     * Fetches the products with the SalesChannelRepository
     * which triggers the salesChannelProductLoaded event.
     * The SetProduct Plugin subscribes to that event and therefore
     * updates the available Stock correctly.
     *
     * @param LineItem $lineItem
     * @param SalesChannelContext $context
     * @return ProductCollection
     */
    private function fetchProductsForLineItem(LineItem $lineItem, SalesChannelContext $context): ProductCollection
    {
        $ids = self::getProductIdsForLineItem($lineItem);

        /**
         * Add lineItem to prevent self calculations inside the reducer
         * @link collect -> remove extension, after stock calculation
         */
        $context->addExtension("lineItem", $lineItem);
        return $this->productGateway->get($ids, $context);
    }


    /**
     * Method used in CandyBagsSubProductCartReducer
     * @param LineItem $lineItem
     * @return array
     */
    public static function getProductIdsForLineItem(LineItem $lineItem): array
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

    /**
     * Contains duplicated code
     * #dup - @param ProductCollection $products
     * @param SalesChannelContext $context
     * @param LineItem $lineItem
     * @param CartDataCollection $data
     * @return array
     * @return DeliveryTime|null
     * @link SetProductCartProcessor
     * #dup
     */
    private function getSubProducts(ProductCollection $products, SalesChannelContext $context): array
    {
        $productsFetched = [];
        foreach ($products as $product) {

            $relatedProducts = $this->getRelatedProducts($product, $context) ?? [];
            //Mix them together with main product
            $relatedProducts['product'] = $product;
            $productsFetched[] = $relatedProducts;
        }
        return $productsFetched;
    }


    private function getRelatedProducts(ProductEntity $product, SalesChannelContext $context): array
    {
        $sqlSetProducts = 'select
      pp.product_version_id,
      pp.product_id,
      pp.quantity,
      pt.name,
      p.product_number
      from
      ec_product_product as pp
      left join product_translation pt on pp.product_id = pt.product_id
      left join product p on pp.product_id = p.id
      where
      pp.set_product_id = :id
      and pt.language_id = :languageId';

        $rows = $this->connection->fetchAll(
            $sqlSetProducts,
            [
                'id' => Uuid::fromHexToBytes($product->getId()),
                'languageId' => Uuid::fromHexToBytes($context->getContext()->getLanguageId())
            ]
        );

        $setProducts = [];
        $subProductsString = $product->getTranslation('name') . "\n";

        foreach ($rows as $row) {
            $setProducts[] = [
                'product_number' => $row['product_number'],
                'name' => $row['name'],
                'product_id' => Uuid::fromBytesToHex($row['product_id']),
                'product_version_id' => Uuid::fromBytesToHex($row['product_version_id']),
                'quantity' => $row['quantity']
            ];

            // Sub Products line für fljnk
            $subProductsString .= "\t- {$row['product_number']} - {$row['name']} - {$row['quantity']}x \n";
        }

        return [
            'sub_product_payload' => $setProducts,
            'sub_product_flink_formatted' => $subProductsString
        ];
    }

    private function getMinDelivery(ProductEntity $p)
    {
        return $p->getDeliveryTime() && $p->getDeliveryTime()->getMin() ? $p->getDeliveryTime()->getMin() : null;
    }

    private function getMaxDelivery(ProductEntity $p)
    {
        return $p->getDeliveryTime() && $p->getDeliveryTime()->getMax() ? $p->getDeliveryTime()->getMax() : null;
    }

    /**
     * @link EclmCartProcessor
     *
     * change ProductCollection to Array, add SubProduct Data & SubProduct String for Fljnk App
     *
     * [
     *      ['product' (1) => ProductEntity
     *      'sub_product_payload' =>  array
     *      'sub_product_flink_formatted' => array
     *      ],
     *
     *      ['product' (1) => ProductEntity
     *      'sub_product_payload' =>  array
     *      'sub_product_flink_formatted' => array
     *      ]
     * ]
     *
     */
    private function getDeliveryTimeFromProducts(LineItem $lineItem, CartDataCollection $data): ?DeliveryTime
    {
        $key = self::DATA_KEY . $lineItem->getReferencedId();
        /** @var SalesChannelProductEntity[] $products */
        $products = array_column($data->get($key), 'product');

        /** @var ProductEntity $minDeliveryTime */
        $minDeliveryTime = array_reduce($products, function (SalesChannelProductEntity $p1, SalesChannelProductEntity $p2) {
            $min1 = $this->getMinDelivery($p1);
            $min2 = $this->getMinDelivery($p2);
            return $min1 > $min2 ? $p1 : $p2;
        }, $products[0]);

        /** @var ProductEntity $maxDeliveryTime */
        $maxDeliveryTime = array_reduce($products, function (SalesChannelProductEntity $p1, SalesChannelProductEntity $p2) {
            $max1 = $this->getMaxDelivery($p1);
            $max2 = $this->getMaxDelivery($p2);
            return $max1 > $max2 ? $p1 : $p2;
        }, $products[0]);


        $deliveryInfoIsComplete = $this->getMinDelivery($minDeliveryTime) && $this->getMaxDelivery($minDeliveryTime);
        if ($deliveryInfoIsComplete) {
            $deliveryTime = new DeliveryTime();
            $deliveryTime->setMin($this->getMinDelivery($minDeliveryTime));
            $deliveryTime->setMax($this->getMaxDelivery($maxDeliveryTime));
            $deliveryTime->setUnit(DeliveryTimeEntity::DELIVERY_TIME_DAY);

            return $deliveryTime;
        }

        return null;
    }

    /**
     * Available Stock of each product in Collection is already corrected by
     * the ProductListingSubscriber
     *
     * @param LineItem $lineItem
     * @param CartDataCollection $data
     * @return int
     */
    public static function getAvailableStock(LineItem $lineItem, CartDataCollection $data): int
    {
        $key = self::DATA_KEY . $lineItem->getReferencedId();
        /** @var SalesChannelProductEntity[] $products */
        $products = array_column($data->get($key), 'product');

        /** @var SalesChannelProductEntity $availableStock */
        $availableStock = array_reduce($products, function (SalesChannelProductEntity $p1, SalesChannelProductEntity $p2) {
            $stock1 = $p1->getAvailableStock();
            $stock2 = $p2->getAvailableStock();
            return $stock1 < $stock2 ? $p1 : $p2;
        }, $products[0]);

        return $availableStock->getAvailableStock();
    }

    private function calculateWeight(LineItem $lineItem, CartDataCollection $data): float
    {
        $key = self::DATA_KEY . $lineItem->getReferencedId();
        /** @var SalesChannelProductEntity[] $products */
        $products = array_column($data->get($key), 'product');

        return array_reduce($products, function (float $weight, SalesChannelProductEntity $p) {
            $weight += $p->getWeight() ?? 0;
            return $weight;
        }, 0);
    }

    /**
     * Get Maximum value
     * @param LineItem $lineItem
     * @param CartDataCollection $data
     * @return int
     */
    private function calculateRestockTime(LineItem $lineItem, CartDataCollection $data): int
    {
        $key = self::DATA_KEY . $lineItem->getReferencedId();
        /** @var SalesChannelProductEntity[] $products */
        $products = array_column($data->get($key), 'product');

        /** @var ProductEntity $calculatedRestockTime */
        $calculatedRestockTime = array_reduce($products, function (SalesChannelProductEntity $p1, SalesChannelProductEntity $p2) {
            $restockTime1 = $p1->getRestockTime();
            $restockTime2 = $p2->getRestockTime();
            return $restockTime1 > $restockTime2 ? $p1 : $p2;
        }, $products[0]);

        return $calculatedRestockTime->getRestockTime() ?? 0;
    }

    /**
     * Get Maximum value
     * @param LineItem $lineItem
     * @param CartDataCollection $data
     * @return int
     */
    private function getProductWithHighestTaxRate(LineItem $lineItem, CartDataCollection $data): ProductEntity
    {
        $key = self::DATA_KEY . $lineItem->getReferencedId();
        /** @var SalesChannelProductEntity[] $products */
        $products = array_column($data->get($key), 'product');

        /** @var ProductEntity $productWithHighestTax */
        $productWithHighestTax = array_reduce($products, function (SalesChannelProductEntity $p1, SalesChannelProductEntity $p2) {
            $taxRate1 = $p1->getTax()->getTaxRate();
            $taxRate2 = $p2->getTax()->getTaxRate();
            return $taxRate1 > $taxRate2 ? $p1 : $p2;
        }, $products[0]);

        return $productWithHighestTax;
    }


    private function getProductCurrencyPrice(LineItem $lineItem, CartDataCollection $data, SalesChannelContext $context): float
    {
        $key = self::DATA_KEY . $lineItem->getReferencedId();
        /** @var SalesChannelProductEntity[] $products */
        $products = array_column($data->get($key), 'product');
        $currencyId = $context->getCurrency()->getId();

        /** @var float $calculatedPrice */
        $calculatedPrice = array_reduce($products, function (float $price, SalesChannelProductEntity $product) use ($currencyId, $context) {
            $priceClass = $product->getPrice()->getCurrencyPrice($currencyId);
            $priceNetOrGross = $this->netOrGross($priceClass, $context);
            $recalculated = $this->recalculateCurrencyIfNeeded($priceNetOrGross, $priceClass, $context);
            $price += $recalculated;
            return $price;
        }, 0.0);


        // Add BasePrice if exists
        $stepSet = $lineItem->getPayloadValue('stepSet');
        $basePrice = $stepSet['price'][0] ?? null;

        if ($basePrice) {
            if ($context->getTaxState() === CartPrice::TAX_STATE_GROSS) {
                $calculatedPrice += $basePrice['gross'];
            } else {
                $calculatedPrice += $basePrice['net'];
            }
            if ($basePrice['currencyId'] !== $context->getCurrency()->getId()) {
                $calculatedPrice *= $context->getContext()->getCurrencyFactor();
            }
        }

        return $calculatedPrice;
    }

    private function netOrGross(Price $price, SalesChannelContext $context): float
    {
        if ($context->getTaxState() === CartPrice::TAX_STATE_GROSS) {
            return $price->getGross() ?? 0.0;
        } else {
            return $price->getNet() ?? 0.0;
        }
    }

    private function recalculateCurrencyIfNeeded(float $value, Price $price, SalesChannelContext $context): float
    {
        if ($price->getCurrencyId() !== $context->getCurrency()->getId()) {
            $value *= $context->getContext()->getCurrencyFactor();
        }
        return $value;
    }

    private function getLineItemImage(string $id, SalesChannelContext $context): MediaEntity
    {
        $criteria = new Criteria([$id]);
        /** @var MediaEntity $media */
        $media = $this->mediaRepository->search($criteria, $context->getContext())->first();

        return $media;
    }

    /**
     * @param array $productsAry
     * @param LineItem $lineItem
     * @return string[]
     *
     * [ 'flinkAggregate' => $flinkAggregate,
     * 'cartInfo' => $cartInfo ]
     *
     */
    private function createLabelAndFlinkData(array $productsAry, LineItem $lineItem): array
    {
        $flinkAggregate = "";
        $cartInfo = "";
        foreach ($lineItem->getPayload()['selected'] as $item) {

            if ($item['cardType'] == 'treeNodeCard') {
                $itemCard = $item['item']['itemCard'];
                $id = $itemCard['productId'];
                if ($id !== null) {
                    $productInfo = $this->getProductById($productsAry, $id);
                    $flinkAggregate .= $productInfo['sub_product_flink_formatted'] . "\n";
                    /** @var ProductEntity $productEntity */
                    $productEntity = $productInfo['product'];
                    $cartInfo .= "- " . $productEntity->getTranslation('name') . "\n";
                } else {
                    $flinkAggregate .= "- " . $itemCard['name'] . "\n\n";
                    $cartInfo .= "- " . $itemCard['name'] . "\n";
                }
            } else {

                $itemCard = $item['itemCard'];
                $id = $itemCard['productId'];
                if ($id !== null) {
                    $productInfo = $this->getProductById($productsAry, $id);
                    $flinkAggregate .= $productInfo['sub_product_flink_formatted'] . "\n";
                    /** @var ProductEntity $productEntity */
                    $productEntity = $productInfo['product'];
                    $cartInfo .= "- " . $productEntity->getTranslation('name') . "\n";
                } else {
                    $flinkAggregate .= "- " . $itemCard['name'] . "\n\n";
                    $cartInfo .= "- " . $itemCard['name'] . "\n";
                }
            }

        }

        return [
            'flinkAggregate' => $flinkAggregate,
            'cartInfo' => $cartInfo
        ];
    }

    private function getProductById(array $productsAry, $id)
    {
        $filtered = array_filter($productsAry, function ($product) use ($id) {
            return $product['product']->getId() == $id;
        });

        return array_values($filtered)[0];
    }
}