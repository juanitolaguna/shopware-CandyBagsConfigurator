<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\CandyBag;

use EventCandyCandyBags\Core\Content\CandyBag\Aggregate\CandyBagTranslation\CandyBagTranslationDefinition;
use EventCandyCandyBags\Core\Content\Step\StepDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CandyBagDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_candy_bag';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CandyBagCollection::class;
    }

    public function getEntityClass(): string
    {
        return CandyBagEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([

            (new IdField('id', 'id'))
                ->addFlags(new Required(), new PrimaryKey()),

            (new StringField('name', 'name'))
                ->addFlags(new Required()),

            (new StringField('description', 'description')),

            (new IntField('min_steps', 'minSteps')),

            (new OneToManyAssociationField('steps', StepDefinition::class, 'candy_bag_id', 'id'))->addFlags(new CascadeDelete()),

            (new BoolField('active', 'active')),

            (new IntField('position', 'position')),

            new FkField('media_id', 'mediaId', MediaDefinition::class),

            new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class
            ),

            new TranslationsAssociationField(CandyBagTranslationDefinition::class, 'eccb_candy_bag_id'),
        ]);
    }
}