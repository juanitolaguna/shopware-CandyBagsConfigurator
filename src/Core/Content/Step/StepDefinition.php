<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\Step;


use EventCandyCandyBags\Core\Content\CandyBag\CandyBagDefinition;
use EventCandyCandyBags\Core\Content\Card\CardDefinition;
use EventCandyCandyBags\Core\Content\Step\Aggregate\StepProduct\StepProductDefinition;
use EventCandyCandyBags\Core\Content\Step\Aggregate\StepTranslation\StepTranslationDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class StepDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_step';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return StepCollection::class;
    }

    public function getEntityClass(): string
    {
        return StepEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([

            (new IdField('id', 'id'))
                ->addFlags(new Required(), new PrimaryKey()),

            (new StringField('name', 'name'))
                ->addFlags(new Required()),

            (new StringField('description', 'description')),

            (new StringField('type', 'type')),

            (new BoolField('active', 'active')),

            (new IntField('position', 'position')),

            new ManyToManyAssociationField('products', ProductDefinition::class, StepProductDefinition::class, 'step_id', 'product_id'),

            (new OneToManyAssociationField('cards', CardDefinition::class, 'step_id', 'id'))->addFlags(new CascadeDelete()),

            new ManyToOneAssociationField(
                'candyBag',
                'candy_bag_id',
                CandyBagDefinition::class
            ),

            new TranslationsAssociationField(StepTranslationDefinition::class, 'eccb_step_id'),

        ]);
    }
}