<?php


namespace EventCandyCandyBags\DataProvider;


use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Media\Aggregate\MediaFolder\MediaFolderEntity;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Content\Media\File\MediaFile;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class BaseMediaProvider extends DemoDataProvider
{

    /**
     * @var FileSaver
     */
    protected $fileSaver;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var EntityRepositoryInterface
     */
    private $mediaFolderRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $mediaFolderConfigurationRepository;

    /**
     * @var string
     */
    private $folderId;


    public function __construct(
        Connection $connection,
        FileSaver $fileSaver,
        EntityRepositoryInterface $mediaFolderRepository,
        EntityRepositoryInterface $mediaFolderConfigurationRepository)
    {
        parent::__construct($connection, $fileSaver);
        $this->mediaFolderRepository = $mediaFolderRepository;
        $this->mediaFolderConfigurationRepository = $mediaFolderConfigurationRepository;
    }

    public function prepare(Context $context): void
    {
        $folderName = 'Candy Bags';

        $thumbnails = $this->connection->fetchAll('
                select LOWER(HEX(media_thumbnail_size.id)) AS id from media_thumbnail_size
                where media_thumbnail_size.width in (400, 800, 1920);');


        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $folderName));
        /** @var MediaFolderEntity $result */
        $result = $this->mediaFolderRepository->search($criteria, $context)->first();

        if ($result != null) {
            $this->folderId = $result->getId();
        } else {
            $this->folderId = Uuid::randomHex();
        }

        $this->mediaFolderRepository->upsert([
            [
                'id' => $this->folderId,
                'name' => $folderName,
                'useParentConfiguration' => false,
                'configuration' => [
                    'id' => $this->folderId,
                    'createThumbnails' => true,
                    'keepAspectRation' => true,
                    'thumbnailQuality' => 80,
                    'mediaThumbnailSizes' => [
                        ['id' => $thumbnails[0]['id']],
                        ['id' => $thumbnails[1]['id']],
                        ['id' => $thumbnails[2]['id']]
                    ]
                ]
            ]
        ], $context);

        parent::prepare($context);
    }


    public function getAction(): string
    {
        return 'upsert';
    }

    public function getEntity(): string
    {
        return 'media';
    }

    public function getPayload(): array
    {

        return [
            [
                'id' => '5b09e55ed08a4047aacf27f47f30ef51',
                'mediaFolderId' => $this->folderId,
            ]
        ];
    }


    public function finalize(Context $context): void
    {

        foreach (glob(__DIR__ . '/../Resources/media/base/*/*.png') as $file) {
            $this->fileSaver->persistFileToMedia(
                new MediaFile(
                    $file,
                    mime_content_type($file),
                    pathinfo($file, PATHINFO_EXTENSION),
                    filesize($file)
                ),
                pathinfo($file, PATHINFO_FILENAME),
                basename(dirname($file)),
                $context
            );
        }

        parent::finalize($context);
    }


}
