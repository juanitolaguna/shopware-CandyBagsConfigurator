<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Card\Aggregate\CardTranslation;

use EventCandyCandyBags\Core\Content\Card\CardEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class CardTranslationEntity extends TranslationEntity
{

    /**
     * @var string
     */
    protected $cardId;

    /**
     * @var CardEntity
     */
    protected $card;

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
    public function getCardId(): string
    {
        return $this->cardId;
    }

    /**
     * @param string $cardId
     */
    public function setCardId(string $cardId): void
    {
        $this->cardId = $cardId;
    }

    /**
     * @return CardEntity
     */
    public function getCard(): CardEntity
    {
        return $this->card;
    }

    /**
     * @param CardEntity $card
     */
    public function setCard(CardEntity $card): void
    {
        $this->card = $card;
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
