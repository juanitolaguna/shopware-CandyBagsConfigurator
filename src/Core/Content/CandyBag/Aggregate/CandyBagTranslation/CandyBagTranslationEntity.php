<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\CandyBag\Aggregate\CandyBagTranslation;

use EventCandyCandyBags\Core\Content\CandyBag\CandyBagEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class CandyBagTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $candyBagId;

    /**
     * @var CandyBagEntity
     */
    protected $candyBag;

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
    public function getCandyBagId(): string
    {
        return $this->candyBagId;
    }

    /**
     * @param string $candyBagId
     */
    public function setCandyBagId(string $candyBagId): void
    {
        $this->candyBagId = $candyBagId;
    }

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

}