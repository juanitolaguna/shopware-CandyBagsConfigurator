<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Step;

use EventCandyCandyBags\Core\Content\CandyBag\CandyBagEntity;
use EventCandyCandyBags\Core\Content\Card\CardCollection;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class StepEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var ProductCollection|null
     */
    protected $products;

    /**
     * @var CardCollection|null
     */
    protected $cards;

    /**
     * @var CandyBagEntity
     */
    protected $candyBag;

    /**
     * @return CandyBagEntity
     */
    public function getCandyBag(): CandyBagEntity
    {
        return $this->candyBag;
    }

    /**
     * @param CandyBagEntity $candyBag
     */
    public function setCandyBag(CandyBagEntity $candyBag): void
    {
        $this->candyBag = $candyBag;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return ProductCollection|null
     */
    public function getProducts(): ?ProductCollection
    {
        return $this->products;
    }

    /**
     * @param ProductCollection|null $products
     */
    public function setProducts(?ProductCollection $products): void
    {
        $this->products = $products;
    }

    /**
     * @return CardCollection|null
     */
    public function getCards(): ?CardCollection
    {
        return $this->cards;
    }

    /**
     * @param CardCollection|null $cards
     */
    public function setCards(?CardCollection $cards): void
    {
        $this->cards = $cards;
    }


}