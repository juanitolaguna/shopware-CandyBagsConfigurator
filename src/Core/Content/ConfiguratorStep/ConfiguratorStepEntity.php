<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ConfiguratorStep;

use EventCandyCandyBags\Core\Content\ConfiguratorStep\Aggregate\ConfiguratorStepTranslation\ConfiguratorStepTranslationCollection;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ConfiguratorStepEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $parentId;

    /**
     * @var int|null
     */
    protected $childCount;

    /**
     * @var ConfiguratorStepEntity|null
     */
    protected $parent;

    /**
     * @var ConfiguratorStepCollection|null
     */
    protected $children;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var MediaEntity|null
     */
    protected $media;

    /**
     * @var ProductEntity|null
     */
    protected $product;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var bool
     */
    protected $active;


    /**
     * @var bool
     */
    protected $purchasable;

    /**
     * @var ConfiguratorStepTranslationCollection|null
     */
    protected $translations;

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param string|null $parentId
     */
    public function setParentId(?string $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return int|null
     */
    public function getChildCount(): ?int
    {
        return $this->childCount;
    }

    /**
     * @param int|null $childCount
     */
    public function setChildCount(?int $childCount): void
    {
        $this->childCount = $childCount;
    }

    /**
     * @return ConfiguratorStepEntity|null
     */
    public function getParent(): ?ConfiguratorStepEntity
    {
        return $this->parent;
    }

    /**
     * @param ConfiguratorStepEntity|null $parent
     */
    public function setParent(?ConfiguratorStepEntity $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return ConfiguratorStepCollection|null
     */
    public function getChildren(): ?ConfiguratorStepCollection
    {
        return $this->children;
    }

    /**
     * @param ConfiguratorStepCollection|null $children
     */
    public function setChildren(?ConfiguratorStepCollection $children): void
    {
        $this->children = $children;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return MediaEntity|null
     */
    public function getMedia(): ?MediaEntity
    {
        return $this->media;
    }

    /**
     * @param MediaEntity|null $media
     */
    public function setMedia(?MediaEntity $media): void
    {
        $this->media = $media;
    }

    /**
     * @return ProductEntity|null
     */
    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    /**
     * @param ProductEntity|null $product
     */
    public function setProduct(?ProductEntity $product): void
    {
        $this->product = $product;
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
     * @return bool
     */
    public function isPurchasable(): bool
    {
        return $this->purchasable;
    }

    /**
     * @param bool $purchasable
     */
    public function setPurchasable(bool $purchasable): void
    {
        $this->purchasable = $purchasable;
    }

    /**
     * @return ConfiguratorStepTranslationCollection|null
     */
    public function getTranslations(): ?ConfiguratorStepTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param ConfiguratorStepTranslationCollection|null $translations
     */
    public function setTranslations(?ConfiguratorStepTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

}