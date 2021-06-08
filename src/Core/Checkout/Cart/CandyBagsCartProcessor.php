<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Checkout\Cart;

use Doctrine\DBAL\Connection;
use EventCandyCandyBags\Utils;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryInformation;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryTime;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\QuantityInformation;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTax;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRule;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\Content\Product\Cart\ProductGatewayInterface;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\DeliveryTime\DeliveryTimeEntity;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepositoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CandyBagsCartProcessor implements CartProcessorInterface, CartDataCollectorInterface
{

    /**
     * @var SalesChannelRepositoryInterface
     */
    private $productRepository;


    /**
     * @var ProductGatewayInterface
     */
    private $productGateway;


    /**
     * @var Connection
     */
    private $connection;


    public const TYPE = 'event-candy-candy-bags';
    public const DATA_KEY = 'eccb-';
    public const MIN_PURCHASE = 1;
    public const MAX_PURCHASE = 20;
    public const PURCHASE_STEPS = 1;
    public const SHIPPING_FREE = false;

    /**
     * CandyBagsCartProcessor constructor.
     * @param SalesChannelRepositoryInterface $productRepository
     * @param ProductGatewayInterface $productGateway
     * @param Connection $connection
     */
    public function __construct(SalesChannelRepositoryInterface $productRepository, ProductGatewayInterface $productGateway, Connection $connection)
    {
        $this->productRepository = $productRepository;
        $this->productGateway = $productGateway;
        $this->connection = $connection;
    }


    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $lineItems = $original
            ->getLineItems()
            ->filterType(self::TYPE);

        if (\count($lineItems) === 0) {
            return;
        }

        // fetch lineItem products and subproducts
        // add them to CartDataCollection
        foreach ($lineItems as $lineItem) {
            $key = self::DATA_KEY . $lineItem->getReferencedId();
            if (!$data->has($key)) {
                $products = $this->fetchProductsForLineItem($lineItem, $context);
                $productsAry = $this->getSubProducts($products, $context);
                $data->set($key, $productsAry);
            }
        }


        foreach ($lineItems as $lineItem) {
            if ($this->isComplete($lineItem) && !$lineItem->isModified()) {
                return;
            }
            // ToDo: hard coded values
            $lineItem->setLabel('Candy Bags Product');
            $lineItem->setDescription('product description');

            $deliveryTime = $this->getDeliveryTimeFromProducts($lineItem, $data);
            $availableStock = $this->getAvailableStock($lineItem, $data);
            $weight = $this->calculateWeight($lineItem, $data);
            $restockTime = $this->calculateRestockTime($lineItem, $data);

            Utils::log($restockTime);


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

        if (\count($lineItems) === 0) {
            return;
        }

        foreach ($lineItems as $lineItem) {

            $key = self::DATA_KEY . $lineItem->getReferencedId();


            $taxCollection = new CalculatedTaxCollection([
                new CalculatedTax(
                    0.19,
                    10,
                    10
                )
            ]);

            $taxRules = new TaxRuleCollection([
                new TaxRule(0.19)
            ]);

            $lineItem->setPrice(new CalculatedPrice(
                99,
                99,
                $taxCollection,
                $taxRules
            ));


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
        $ids = $this->getProductIdsForLineItem($lineItem);
        return $this->productGateway->get($ids, $context);
    }


    private function getProductIdsForLineItem(LineItem $lineItem): array
    {
        $filteredProductIds = [];
        /** @var array $item */
        foreach ($lineItem->getPayload()['selected'] as $item) {
            if ($item['cardType'] == 'treeNodeCard') {
                $id = $item['item']['itemCard']['productId'];

                if ($id !== null) {
                    $filteredProductIds[] = $item['item']['itemCard']['productId'];
                }
            } else {
                $id = $item['itemCard']['productId'];
                if ($id !== null) {
                    $filteredProductIds[] = $item['itemCard']['productId'];
                }
            }

        }
        return $filteredProductIds;
    }

    /**
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
     * @param ProductCollection $products
     * @param SalesChannelContext $context
     * @return array
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
        $subProductsString = "\n" . $product->getTranslation('name') . "\n";

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
     * @param LineItem $lineItem
     * @param CartDataCollection $data
     * @return DeliveryTime|null
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
     * Available Stock of each product in Collection is allready corrected by
     * the ProductListingSubscriber
     *
     * @param LineItem $lineItem
     * @param CartDataCollection $data
     * @return int
     */
    private function getAvailableStock(LineItem $lineItem, CartDataCollection $data): int
    {
        $key = self::DATA_KEY . $lineItem->getReferencedId();
        /** @var SalesChannelProductEntity[] $products */
        $products = array_column($data->get($key), 'product');

        /** @var ProductEntity $availableStock */
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

        return $calculatedRestockTime->getRestockTime();
    }
}

