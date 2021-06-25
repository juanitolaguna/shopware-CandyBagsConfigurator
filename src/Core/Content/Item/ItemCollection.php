<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Item;

use EventCandy\Sets\Storefront\Page\Product\Subscriber\ProductListingSubscriber;
use Shopware\Core\Checkout\Order\Aggregate\OrderAddress\OrderAddressEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Util\AfterSort;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

/**
 * @method void                add(ItemEntity $entity)
 * @method void                set(string $key, ItemEntity $entity)
 * @method ItemEntity[]    getIterator()
 * @method ItemEntity[]    getElements()
 * @method ItemEntity|null get(string $key)
 * @method ItemEntity|null first()
 * @method ItemEntity|null last()
 */
class ItemCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'eccb_item';
    }

    protected function getExpectedClass(): string
    {
        return ItemEntity::class;
    }

    public function getPrices(string $currencyId): self
    {
        /** @var ItemEntity[] $elements */
        $elements = $this->elements;

        foreach ($elements as $item) {
            $product = $item->getItemCard()->getProduct();
            $price = $product ? $product->getCurrencyPrice($currencyId) : null;
            $item->setCurrencyPrice($price);
        }

        return $this;
    }

    public function correctAvailableStock(ProductListingSubscriber $productListingSubscriber, SalesChannelContext $context): self
    {
        /** @var ItemEntity[] $elements */
        $elements = $this->elements;

        foreach ($elements as $item) {
            $product = $item->getItemCard()->getProduct();
            if ($product) {
                $keyIsTrue = array_key_exists('ec_is_set', $product->getCustomFields())
                    && $product->getCustomFields()['ec_is_set'];
                if ($keyIsTrue) {
                    $availableStock = $productListingSubscriber->getAvailableStock($product->getId(), $context);
                    $product->setAvailableStock($availableStock);
                }
            }
        }

        return $this;
    }

    public function sortByPosition(): self
    {
        $elements = $this->elements;
        // pre-sort elements to pull elements without an after id parent to the front
        uasort($elements, function (ItemEntity $a, ItemEntity $b) {
            if ($a->getPosition() < $b->getPosition()) {
                return 1;
            }

            if ($a->getPosition() > $b->getPosition()) {
                return -1;
            }

            return 0;
        });
        $this->elements = $elements;
        return $this;
    }

    public function filterByActive(): self
    {
        $filtered = $this->filter(function (ItemEntity $item) {
            return $item->isActive() === true;
        });

        $this->elements = $filtered->getElements();
        return $this;
    }
}