<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet;

use EventCandyCandyBags\Core\Content\StepSet\Aggregate\StepSetTranslation\StepSetTranslationDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\Api\Context\SalesChannelApiSource;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ReadProtected;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\PriceField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Tax\TaxDefinition;

class StepSetDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'eccb_step_set';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return StepSetCollection::class;
    }

    public function getEntityClass(): string
    {
        return StepSetEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([

            (new IdField('id', 'id'))
                ->addFlags(new Required(), new PrimaryKey(), new ApiAware()),

            (new IntField('position', 'position'))->addFlags(new ApiAware()),
            (new BoolField('active', 'active'))->addFlags(new ApiAware()),

            (new PriceField('price', 'price'))->addFlags(new ApiAware()),

            (new FkField('tax_id', 'taxId', TaxDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('tax', 'tax_id', TaxDefinition::class, 'id', true))->addFlags(new ApiAware()),

            (new OneToManyAssociationField('steps', TreeNodeDefinition::class, 'step_set_id'))->addFlags(new CascadeDelete(), new ApiAware()),


            (new FkField('media_id', 'mediaId', MediaDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class
            ))->addFlags(new ApiAware()),

            (new FkField('selection_base_image_id', 'selectionBaseImageId', MediaDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField(
                'selectionBaseImage',
                'selection_base_image_id',
                MediaDefinition::class
            ))->addFlags(new ApiAware()),

            (new TranslatedField('name'))->addFlags(new ApiAware()),
            (new TranslatedField('description'))->addFlags(new ApiAware()),
            (new TranslatedField('additionalData'))->addFlags(new ApiAware()),
            (new TranslatedField('keywords'))->addFlags(new ApiAware()),
            (new TranslatedField('revisitAfter'))->addFlags(new ApiAware()),

            new TranslationsAssociationField(StepSetTranslationDefinition::class, 'eccb_step_set_id'),
        ]);
    }
}