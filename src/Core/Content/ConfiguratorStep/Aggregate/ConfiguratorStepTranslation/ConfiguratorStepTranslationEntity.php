<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ConfiguratorStep\Aggregate\ConfiguratorStepTranslation;

use EventCandyCandyBags\Core\Content\ConfiguratorStep\ConfiguratorStepEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class ConfiguratorStepTranslationEntity extends TranslationEntity
{

    /**
     * @var string
     */
    protected $configuratorStepId;

    /**
     * @var ConfiguratorStepEntity|null
     */
    protected $configuratorStep;

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
    protected $stepDescription;

    /**
     * @return string
     */
    public function getConfiguratorStepId(): string
    {
        return $this->configuratorStepId;
    }

    /**
     * @param string $configuratorStepId
     */
    public function setConfiguratorStepId(string $configuratorStepId): void
    {
        $this->configuratorStepId = $configuratorStepId;
    }

    /**
     * @return ConfiguratorStepEntity|null
     */
    public function getConfiguratorStep(): ?ConfiguratorStepEntity
    {
        return $this->configuratorStep;
    }

    /**
     * @param ConfiguratorStepEntity|null $configuratorStep
     */
    public function setConfiguratorStep(?ConfiguratorStepEntity $configuratorStep): void
    {
        $this->configuratorStep = $configuratorStep;
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
    public function getStepDescription(): ?string
    {
        return $this->stepDescription;
    }

    /**
     * @param string|null $stepDescription
     */
    public function setStepDescription(?string $stepDescription): void
    {
        $this->stepDescription = $stepDescription;
    }


}
