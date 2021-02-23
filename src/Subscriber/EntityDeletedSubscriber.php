<?php declare(strict_types=1);

namespace EventCandyCandyBags\Subscriber;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EntityDeletedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EntityDeletedEvent::class => 'onTreeNodeDeleted',
        ];
    }

    public function onTreeNodeDeleted(EntityDeletedEvent $event) :void {
        $event = $event;
    }

}
