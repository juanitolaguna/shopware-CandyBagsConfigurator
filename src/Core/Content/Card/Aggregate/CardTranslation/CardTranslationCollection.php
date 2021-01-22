<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Card\Aggregate\CardTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(CardTranslationCollection $entity)
 * @method void              set(string $key, CardTranslationCollection $entity)
 * @method CardTranslationCollection[]    getIterator()
 * @method CardTranslationCollection[]    getElements()
 * @method CardTranslationCollection|null get(string $key)
 * @method CardTranslationCollection|null first()
 * @method CardTranslationCollection|null last()
 */
class CardTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return CardTranslationEntity::class;
    }
}