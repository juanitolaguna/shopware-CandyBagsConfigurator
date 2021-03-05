<?php declare(strict_types=1);
namespace EventCandyCandyBags\Core\Content\StepSet;

use Shopware\Core\Content\Seo\SeoUrlUpdater;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StepSetSeoUrlListener implements EventSubscriberInterface
{
    /**
     * @var SeoUrlUpdater
     */
    private $seoUrlUpdater;

    public function __construct(SeoUrlUpdater $seoUrlUpdater)
    {
        $this->seoUrlUpdater = $seoUrlUpdater;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'eccb_step_set.written' => 'onStepSetUpdated',
        ];
    }

    public function onStepSetUpdated(EntityWrittenEvent $event): void
    {
        $this->seoUrlUpdater->update(StepSetSeoUrlRoute::ROUTE_NAME, $event->getIds());
    }
}
