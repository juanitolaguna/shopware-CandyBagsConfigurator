<?php declare(strict_types=1);
namespace EventCandyCandyBags\Core\Content\StepSet;

use InvalidArgumentException;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlMapping;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteConfig;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class StepSetSeoUrlRoute implements SeoUrlRouteInterface
{
    public const ROUTE_NAME = 'eccb.candybags';

    /**
     * @var StepSetDefinition
     */
    private $definition;

    public function __construct(StepSetDefinition $definition)
    {
        $this->definition = $definition;
    }

    public function getConfig(): SeoUrlRouteConfig
    {
        return new SeoUrlRouteConfig(
            $this->definition,
            self::ROUTE_NAME,
            'candy-bags/{{ entry.title }}'
        );
    }

    public function prepareCriteria(Criteria $criteria): void
    {
    }

    public function getMapping(Entity $entry, ?SalesChannelEntity $salesChannel): SeoUrlMapping
    {
        if (!$entry instanceof StepSetEntity) {
            throw new InvalidArgumentException('Expected BlogEntriesEntity');
        }

        return new SeoUrlMapping(
            $entry,
            ['stepSetId' => $entry->getId()],
            [
                'entry' => $entry,
            ]
        );
    }
}
