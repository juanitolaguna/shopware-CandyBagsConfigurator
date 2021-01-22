<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Step\Aggregate\StepTranslation;

use EventCandyCandyBags\Core\Content\Step\StepEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class StepTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $stepId;

    /**
     * @var StepEntity
     */
    protected $step;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @return string
     */
    public function getStepId(): string
    {
        return $this->stepId;
    }

    /**
     * @param string $stepId
     */
    public function setStepId(string $stepId): void
    {
        $this->stepId = $stepId;
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
}

