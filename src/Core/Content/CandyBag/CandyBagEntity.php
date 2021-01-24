<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\CandyBag;

use EventCandyCandyBags\Core\Content\CandyBag\Aggregate\CandyBagTranslation\CandyBagTranslationCollection;
use EventCandyCandyBags\Core\Content\Step\StepCollection;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CandyBagEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var int
     */
    protected $minSteps;

    /**
     * @var StepCollection|null
     */
    protected $steps;

    /**
     * @var CandyBagTranslationCollection|null
     */
    protected $translations;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var MediaEntity|null
     */
    protected $media;

    /**
     * @return CandyBagTranslationCollection|null
     */
    public function getTranslations(): ?CandyBagTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param CandyBagTranslationCollection|null $translations
     */
    public function setTranslations(?CandyBagTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }


    /**
     * @return int
     */
    public function getMinSteps(): int
    {
        return $this->minSteps;
    }

    /**
     * @param int $minSteps
     */
    public function setMinSteps(int $minSteps): void
    {
        $this->minSteps = $minSteps;
    }

    /**
     * @return StepCollection|null
     */
    public function getSteps(): ?StepCollection
    {
        return $this->steps;
    }

    /**
     * @param StepCollection|null $steps
     */
    public function setSteps(?StepCollection $steps): void
    {
        $this->steps = $steps;
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

}
