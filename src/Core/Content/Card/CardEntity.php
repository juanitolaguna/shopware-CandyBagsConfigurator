<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Card;

use EventCandyCandyBags\Core\Content\Step\Aggregate\StepTranslation\StepTranslationCollection;
use EventCandyCandyBags\Core\Content\Step\StepEntity;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CardEntity extends Entity
{
    use EntityIdTrait;


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
     * @var StepEntity
     */
    protected $step;

    /**
     * @var StepTranslationCollection|null
     */
    protected $translations;

    /**
     * @return StepTranslationCollection|null
     */
    public function getTranslations(): ?StepTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param StepTranslationCollection|null $translations
     */
    public function setTranslations(?StepTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    /**
     * @return StepEntity
     */
    public function getStep(): StepEntity
    {
        return $this->step;
    }

    /**
     * @param StepEntity $step
     */
    public function setStep(StepEntity $step): void
    {
        $this->step = $step;
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