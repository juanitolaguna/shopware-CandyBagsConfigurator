<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet\Aggregate\StepSetTranslation;

use EventCandyCandyBags\Core\Content\StepSet\StepSetEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class StepSetTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $stepSetId;

    /**
     * @var StepSetEntity
     */
    protected $stepSet;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $additionalData;

    /**
     * @var string|null
     */
    protected $keywords;


    /**
     * @var int|null
     */
    protected $revisitAfter;


    /**
     * @return string
     */
    public function getStepSetId(): string
    {
        return $this->stepSetId;
    }

    /**
     * @param string $stepSetId
     */
    public function setStepSetId(string $stepSetId): void
    {
        $this->stepSetId = $stepSetId;
    }

    /**
     * @return StepSetEntity
     */
    public function getStepSet(): StepSetEntity
    {
        return $this->stepSet;
    }

    /**
     * @param StepSetEntity $stepSet
     */
    public function setStepSet(StepSetEntity $stepSet): void
    {
        $this->stepSet = $stepSet;
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
     * @return string|null
     */
    public function getAdditionalData(): ?string
    {
        return $this->additionalData;
    }

    /**
     * @param string|null $additionalData
     */
    public function setAdditionalData(?string $additionalData): void
    {
        $this->additionalData = $additionalData;
    }

    /**
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @param string|null $keywords
     */
    public function setKeywords(?string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @return int|null
     */
    public function getRevisitAfter(): ?int
    {
        return $this->revisitAfter;
    }

    /**
     * @param int|null $revisitAfter
     */
    public function setRevisitAfter(?int $revisitAfter): void
    {
        $this->revisitAfter = $revisitAfter;
    }
}