<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\TreeNode\Aggregate\TreeNodeTranslation;

use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class TreeNodeTranslationEntity extends TranslationEntity
{

    /**
     * @var string
     */
    protected $configuratorStepId;

    /**
     * @var TreeNodeEntity|null
     */
    protected $configuratorStep;

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
     * @return TreeNodeEntity|null
     */
    public function getConfiguratorStep(): ?TreeNodeEntity
    {
        return $this->configuratorStep;
    }

    /**
     * @param TreeNodeEntity|null $configuratorStep
     */
    public function setConfiguratorStep(?TreeNodeEntity $configuratorStep): void
    {
        $this->configuratorStep = $configuratorStep;
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
