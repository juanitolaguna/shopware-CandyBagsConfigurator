<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet;

use EventCandyCandyBags\Core\Content\StepSet\Aggregate\StepSetTranslation\StepSetTranslationCollection;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeCollection;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class StepSetEntity extends Entity
{
    use EntityIdTrait;

    /**
     * ItemSet
     * @var int
     */
    protected $position;

    /**
     * ItemSet
     * @var bool
     */
    protected $active;

    /**
     * @var MediaEntity
     */
    protected $media;

    /**
     * @var TreeNodeCollection|null
     */
    protected $steps;

    /**
     * @var StepSetTranslationCollection
     */
    protected $translations;

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
     * @return MediaEntity|null
     */
    public function getMedia(): ?MediaEntity
    {
        return $this->media;
    }

    /**
     * @param MediaEntity $media
     */
    public function setMedia(MediaEntity $media): void
    {
        $this->media = $media;
    }

    /**
     * @return TreeNodeCollection|null
     */
    public function getSteps(): ?TreeNodeCollection
    {
        return $this->steps;
    }

    /**
     * @param TreeNodeCollection|null $steps
     */
    public function setSteps(?TreeNodeCollection $steps): void
    {
        $this->steps = $steps;
    }

    /**
     * @return StepSetTranslationCollection
     */
    public function getTranslations(): StepSetTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param StepSetTranslationCollection $translations
     */
    public function setTranslations(StepSetTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }




}