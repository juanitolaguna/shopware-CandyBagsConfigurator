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
     * @var MediaEntity
     */
    protected $media;

    /**
     * @var TreeNodeCollection
     */
    protected $steps;

    /**
     * @var StepSetTranslationCollection
     */
    protected $translations;

    /**
     * @return MediaEntity
     */
    public function getMedia(): MediaEntity
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
     * @return TreeNodeCollection
     */
    public function getSteps(): TreeNodeCollection
    {
        return $this->steps;
    }

    /**
     * @param TreeNodeCollection $steps
     */
    public function setSteps(TreeNodeCollection $steps): void
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