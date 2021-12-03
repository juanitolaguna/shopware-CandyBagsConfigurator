<?php

declare(strict_types=1);

namespace EventCandyCandyBags\Core\Checkout\Cart;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use ErrorException;
use EventCandy\Sets\Core\Checkout\Cart\CartProduct\CartProductService;
use EventCandy\Sets\Core\Checkout\Cart\LineItemPriceService;
use EventCandy\Sets\Core\Checkout\Cart\Payload\PayloadLineItem;
use EventCandy\Sets\Core\Checkout\Cart\Payload\PayloadService;
use EventCandy\Sets\Core\Content\DynamicProduct\Cart\DynamicProductGateway;
use EventCandy\Sets\Core\Content\DynamicProduct\Cart\DynamicProductService;
use EventCandy\Sets\Core\Content\DynamicProduct\DynamicProductEntity;
use EventCandy\Sets\Utils;
use EventCandyCandyBags\Core\Checkout\Cart\Extension\CandyBagsDynamicProductService;
use EventCandyCandyBags\Core\Checkout\Cart\Extension\CandyBagsPayloadService;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartPersisterInterface;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryInformation;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryTime;
use Shopware\Core\Checkout\Cart\Exception\CartTokenNotFoundException;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\QuantityInformation;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\DeliveryTime\DeliveryTimeEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CandyBagsCartCollector implements CartDataCollectorInterface
{
    public const TYPE = 'event-candy-candy-bags';
    public const CART_INFO_KEY = 'cart_info';
    public const PACKLIST_DATA_KEY = 'packlist_data';
    public const PACKLIST_DATA_COMPLETE_KEY = 'packlist_data_complete';
    public const SHIPPING_FREE = false;
    public const MIN_PURCHASE = 1;
    public const PURCHASE_STEPS = 1;

    /**
     * @var CartPersisterInterface
     */
    private $cartPersister;

    /**
     * @var CandyBagsDynamicProductService
     */
    private $dynamicProductService;

    /**
     * @var DynamicProductGateway
     */
    private $dynamicProductGateway;

    /**
     * @var CandyBagsPayloadService
     */
    private $payloadService;

    /**
     * @var CartProductService
     */
    private $cartProductService;

    /**
     * @var EntityRepositoryInterface
     */
    private $mediaRepository;

    /**
     * @var LineItemPriceService
     */
    private $lineItemPriceService;


    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param CartPersisterInterface $cartPersister
     * @param CandyBagsDynamicProductService $dynamicProductService
     * @param DynamicProductGateway $dynamicProductGateway
     * @param CandyBagsPayloadService $payloadService
     * @param CartProductService $cartProductService
     * @param EntityRepositoryInterface $mediaRepository
     * @param LineItemPriceService $lineItemPriceService
     * @param Connection $connection
     */
    public function __construct(
        CartPersisterInterface $cartPersister,
        CandyBagsDynamicProductService $dynamicProductService,
        DynamicProductGateway $dynamicProductGateway,
        CandyBagsPayloadService $payloadService,
        CartProductService $cartProductService,
        EntityRepositoryInterface $mediaRepository,
        LineItemPriceService $lineItemPriceService,
        Connection $connection
    ) {
        $this->cartPersister = $cartPersister;
        $this->dynamicProductService = $dynamicProductService;
        $this->dynamicProductGateway = $dynamicProductGateway;
        $this->payloadService = $payloadService;
        $this->cartProductService = $cartProductService;
        $this->mediaRepository = $mediaRepository;
        $this->lineItemPriceService = $lineItemPriceService;
        $this->connection = $connection;
    }


    public function collect(
        CartDataCollection $data,
        Cart $original,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void {
        $lineItemsChanged = $this->getNotCompleted(
            $data,
            $original->getLineItems()->getElements(),
            $original->isModified()
        );
        if (count($lineItemsChanged) === 0) {
            return;
        }

        //Utils::log('collectCB');

        $lineItems = $original->getLineItems()->filterFlatByType(self::TYPE);

        $this->createCartIfNotExists($context, $original);

        // delete cart stock data for this lineItem type, because new Product wrappers will be generated.
        foreach ($lineItems as $lineItem) {
            $this->dynamicProductService->removeDynamicProductsByLineItemId($lineItem->getId(), $context->getToken());
            //$this->cartProductService->removeCartProductsByLineItem($lineItem->getId(), $context->getToken());
        }
        $this->cartProductService->removeCartProductsByTokenAndType($context->getToken(), self::TYPE);
        $data->clear();


        $dynamicProducts = $this->dynamicProductService->createDynamicProductCollection(
            $lineItems,
            $original->getToken()
        );
        $dynamicProductIds = $this->dynamicProductService->getDynamicProductIdsFromCollection($dynamicProducts);
        $this->dynamicProductService->saveDynamicProductsToDb($dynamicProducts);

        $dynamicProductCollection = $this->dynamicProductGateway->get($dynamicProductIds, $context, false);
        $this->dynamicProductService->addDynamicProductsToCartDataByLineItemId($dynamicProductCollection, $data);

        /**
         * Optimization Loop - save CartProducts for correct stock calculation.
         * @var LineItem $lineItem
         */
        foreach ($lineItems as $lineItem) {
            $this->payloadService->loadPayloadDataForLineItem($lineItem, $data, $context);
            $cartProducts = $this->cartProductService->buildCartProductsFromPayload($lineItem, $data, self::TYPE);
            $this->cartProductService->saveCartProducts($cartProducts);
            $this->dynamicProductService->removeDynamicProductsFromCartDataByLineItemId($lineItem->getId(), $data);
        }

        // repeat it again but with correct stock
        $dynamicProductCollection = $this->dynamicProductGateway->get($dynamicProductIds, $context);
        $this->dynamicProductService->addDynamicProductsToCartDataByLineItemId($dynamicProductCollection, $data);

        foreach ($lineItems as $lineItem) {
            /** @link PayloadService::loadPayloadDataForLineItem() $payloadItem */
            $payloadItem = $this->payloadService->buildPayloadObject($lineItem, $data);
            $payloadAssociative = $this->payloadService->makePayloadDataAssociative($payloadItem, self::TYPE);
            $this->enrichLineItem($lineItem, $data, $context, $payloadItem);
            $lineItem->setPayload($payloadAssociative);
            $cartInfo = $this->payloadService->makeCartInfo($lineItem, self::CART_INFO_KEY);
            $packlistData = $this->payloadService->makePacklistDataWithoutProducts($lineItem, self::PACKLIST_DATA_KEY);
            $packlistDataComplete = $this->payloadService->makePacklistDataWithProducts($lineItem, self::PACKLIST_DATA_COMPLETE_KEY);
            $lineItem->setPayload($cartInfo);
            $lineItem->setPayload($packlistData);
            $lineItem->setPayload($packlistDataComplete);
        }
    }

    private function enrichLineItem(
        LineItem $lineItem,
        CartDataCollection $data,
        SalesChannelContext $context,
        PayloadLineItem $payloadLineItem
    ) {
        $stepSet = $lineItem->getPayloadValue('stepSet');
        $lineItem->setLabel($stepSet['name']);
        $lineItem->setCover($this->getLineItemImage($lineItem, $context));


        $availableStock = $this->calculateAvailableStock($lineItem, $context);
        $weight = $payloadLineItem->getTotalWeight(); // payloadLineItem considers subproducts for weight.
        $restockTime = $this->calculateRestockTime($lineItem, $data);
        $deliveryTime = $this->calculateDeliveryTime($lineItem, $data);

        $lineItem->setDeliveryInformation(
            new DeliveryInformation(
                $availableStock,
                $weight,
                self::SHIPPING_FREE,
                $restockTime,
                $deliveryTime
            )
        );

        if ($lineItem->getPriceDefinition() == null) {
            $qtyDefinition = $this->lineItemPriceService->buildQuantityPriceDefinition(
                $lineItem,
                $data,
                $context,
                null,
                $this->getBasePrice($lineItem, $context)
            );
            $lineItem->setPriceDefinition($qtyDefinition);
        }

        $quantityInformation = new QuantityInformation();
        $quantityInformation
            ->setMaxPurchase($availableStock)
            ->setMinPurchase(self::MIN_PURCHASE)
            ->setPurchaseSteps(self::PURCHASE_STEPS);

        $lineItem->setQuantityInformation($quantityInformation);
    }

    /**
     * @param SalesChannelContext $context
     * @param Cart $original
     */
    private function createCartIfNotExists(SalesChannelContext $context, Cart $original): void
    {
        try {
            $this->cartPersister->load($context->getToken(), $context);
        } catch (CartTokenNotFoundException $exception) {
            $this->cartPersister->save($original, $context);
        }
    }

    /**
     * @param LineItem $lineItem
     * @param SalesChannelContext $context
     * @return MediaEntity|null
     */
    private function getLineItemImage(LineItem $lineItem, SalesChannelContext $context): ?MediaEntity
    {
        $stepSet = $lineItem->getPayloadValue('stepSet');
        $id = $stepSet['selectionBaseImage']['id'] ?? null;
        if ($id) {
            $criteria = new Criteria([$id]);
            /** @var MediaEntity $media */
            $media = $this->mediaRepository->search($criteria, $context->getContext())->first();
            return $media;
        }
        return null;
    }

    private function calculateAvailableStock(LineItem $lineItem, SalesChannelContext $context)
    {
        $sql = "SELECT
                	floor(min(calculated)) AS calculated
                FROM (
                	SELECT
                		min(available_stock),
                		sum( if(countable != 'non-countable', 1, 0)),
                   		sub_product_quantity,
                		min(available_stock) / (sub_product_quantity / line_item_quantity) / sum( if(countable != 'non-countable', 1, 0)) AS calculated
                	FROM (  
                	SELECT
                		p.available_stock - sum(sub_product_quantity) AS available_stock,
                		'non-countable' AS countable,
                		cp.*
                	FROM
                		ec_cart_product cp
                	LEFT JOIN product p ON cp.sub_product_id = p.id
                WHERE
                	cp.sub_product_id IN(
                	SELECT
                		cpsub.sub_product_id FROM ec_cart_product cpsub
                	WHERE
                		cpsub.line_item_id = :lineItemId and cpsub.token = :token) 
                    AND cp.line_item_id != :lineItemId 
                    AND cp.token = :token
                GROUP BY
                	cp.sub_product_id
                UNION
                SELECT
                	p.available_stock,
                	cp.sub_product_id AS countable,
                	cp.*
                FROM
                	ec_cart_product cp
                	LEFT JOIN product p ON cp.sub_product_id = p.id
                WHERE
                	cp.line_item_id = :lineItemId and cp.token = :token) AS group1
                    GROUP BY
                	sub_product_id) AS group2;";


        try {
            $result = $this->connection->fetchAssociative($sql, [
                'lineItemId' => $lineItem->getId(),
                'token' => $context->getToken()
            ]);
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
        return (int)$result['calculated'];
    }


    private function calculateDeliveryTime(LineItem $lineItem, CartDataCollection $data): ?DeliveryTime
    {
        /** @var DynamicProductEntity[] $dynamicProducts */
        $products = $this->dynamicProductService->getFromCartDataByLineItemId($lineItem->getId(), $data);

        /** @var DynamicProductEntity $minDeliveryTimeProduct */
        $minDeliveryTimeProduct = array_reduce(
            $products,
            function (DynamicProductEntity $p1, DynamicProductEntity $p2) {
                $min1 = $this->getMinDelivery($p1);
                $min2 = $this->getMinDelivery($p2);
                return $min1 > $min2 ? $p1 : $p2;
            },
            $products[0]
        );

        /** @var DynamicProductEntity $maxDeliveryTimeProduct */
        $maxDeliveryTimeProduct = array_reduce(
            $products,
            function (DynamicProductEntity $p1, DynamicProductEntity $p2) {
                $max1 = $this->getMaxDelivery($p1);
                $max2 = $this->getMaxDelivery($p2);
                return $max1 > $max2 ? $p1 : $p2;
            },
            $products[0]
        );

        $deliveryInfoIsComplete = $this->getMinDelivery($minDeliveryTimeProduct)
            && $this->getMaxDelivery($minDeliveryTimeProduct);

        if ($deliveryInfoIsComplete) {
            $deliveryTime = new DeliveryTime();
            $deliveryTime->setMin($this->getMinDelivery($minDeliveryTimeProduct));
            $deliveryTime->setMax($this->getMaxDelivery($maxDeliveryTimeProduct));
            $deliveryTime->setUnit(DeliveryTimeEntity::DELIVERY_TIME_DAY);
            return $deliveryTime;
        }

        return null;
    }

    private function calculateRestockTime(LineItem $lineItem, CartDataCollection $data): int
    {
        /** @var DynamicProductEntity[] $dynamicProducts */
        $products = $this->dynamicProductService->getFromCartDataByLineItemId($lineItem->getId(), $data);

        /** @var DynamicProductEntity $maxRestockTimeProduct */
        $maxRestockTimeProduct = array_reduce(
            $products,
            function (DynamicProductEntity $p1, DynamicProductEntity $p2) {
                $restockTime1 = $p1->getProduct()->getRestockTime();
                $restockTime2 = $p2->getProduct()->getRestockTime();
                return $restockTime1 > $restockTime2 ? $p1 : $p2;
            },
            $products[0]
        );

        return $maxRestockTimeProduct->getProduct()->getRestockTime() ?? 0;
    }

    private function getMinDelivery(DynamicProductEntity $p): ?int
    {
        $p = $p->getProduct();
        return $p->getDeliveryTime() && $p->getDeliveryTime()->getMin() ? $p->getDeliveryTime()->getMin() : null;
    }

    private function getMaxDelivery(DynamicProductEntity $p): ?int
    {
        $p = $p->getProduct();
        return $p->getDeliveryTime() && $p->getDeliveryTime()->getMax() ? $p->getDeliveryTime()->getMax() : null;
    }

    private function getBasePrice(LineItem $lineItem, SalesChannelContext $context): float
    {
        $stepSet = $lineItem->getPayloadValue('stepSet');
        $basePrice = $stepSet['price'][0] ?? null;

        $calculatedPrice = 0.0;
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


    private function getNotCompleted(CartDataCollection $data, array $lineItems, bool $cartModified): array
    {
        $newLineItems = [];

        $areModified = array_filter($lineItems, function (LineItem $lineItem) {
            return $lineItem->isModified();
        });

        // If one Item is modified recalculate all.
        if (count($areModified) > 0) {
            return $lineItems;
        }

        // No items modified but one deleted
        if ($cartModified) {
            return $lineItems;
        }

        /** @var LineItem $lineItem */
        foreach ($lineItems as $lineItem) {
            $key = DynamicProductService::DYNAMIC_PRODUCT_LINE_ITEM_ID . $lineItem->getId();

            // check if some data is missing (label, price, cover)
            if (!$this->isComplete($lineItem)) {
                $newLineItems[] = $lineItem;
                continue;
            }

            // data already fetched?
            if ($data->has($key)) {
                continue;
            }
            $lineItems[] = $lineItem;
        }

        return $newLineItems;
    }

    private function isComplete(LineItem $lineItem): bool
    {
        return $lineItem->getPriceDefinition() !== null
            && $lineItem->getLabel() !== null
            && $lineItem->getDeliveryInformation() !== null
            && $lineItem->getQuantityInformation() !== null;
    }

}