<?php declare(strict_types=1);

namespace EventCandyCandyBags\Core\Content\StepSet;

use EventCandyCandyBags\Core\Content\StepSet\Aggregate\StepSetTranslation\StepSetTranslationDefinition;
use EventCandyCandyBags\Core\Content\TreeNode\TreeNodeDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

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
                ->addFlags(new Required(), new PrimaryKey()),

            (new OneToManyAssociationField('steps', TreeNodeDefinition::class, 'step_id', 'id'))->addFlags(new CascadeDelete()),

            new FkField('media_id', 'mediaId', MediaDefinition::class),
            new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class
            ),

            new TranslatedField('name'),
            new TranslatedField('description'),
            new TranslatedField('additionalData'),
            new TranslationsAssociationField(StepSetTranslationDefinition::class, 'eccb_step_set_id'),
        ]);
    }
}