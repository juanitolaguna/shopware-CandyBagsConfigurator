<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\ConfiguratorStep;

use EventCandyCandyBags\Core\Content\ConfiguratorStep\Aggregate\ConfiguratorStepTranslation\ConfiguratorStepTranslationDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductMedia\ProductMediaDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ChildCountField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ChildrenAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ParentAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ParentFkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ConfiguratorStepDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_configurator_step';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ConfiguratorStepCollection::class;
    }

    public function getEntityClass(): string
    {
        return ConfiguratorStepEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([

            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new TranslatedField('name'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            new TranslatedField('description'),
            new TranslatedField('stepDescription'),

            (new IntField('position', 'position')),
            (new BoolField('active', 'active')),
            (new BoolField('purchasable', 'purchasable')),

            new FkField('media_id', 'mediaId', MediaDefinition::class),

            new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class
            ),

            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Inherited()),
            new FkField('product_id', 'productId', MediaDefinition::class),

            new ManyToOneAssociationField(
                'product',
                'product_id',
                ProductDefinition::class
            ),


            new ChildCountField(),
            new ParentFkField(self::class),

            (new ParentAssociationField(self::class, 'id'))->addFlags(new CascadeDelete()),
            new ChildrenAssociationField(self::class),

            (new TranslationsAssociationField(ConfiguratorStepTranslationDefinition::class, 'eccb_configurator_step_id'))
                ->addFlags(new Required())

        ]);
    }
}