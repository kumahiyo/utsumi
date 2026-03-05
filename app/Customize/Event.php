<?php

namespace Customize;

use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Customize\Service\EntryIndexCompleteService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Event implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntryIndexCompleteService
     */
    protected $entryIndexCompleteService;

    public function __construct(
        ContainerInterface $container,
        EntryIndexCompleteService $entryIndexCompleteService
    ) {
        $this->container = $container;
        $this->entryIndexCompleteService = $entryIndexCompleteService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
          EccubeEvents::FRONT_ENTRY_INDEX_COMPLETE => ['onEntryIndexComplete', 10],
        ];
    }

    public function onEntryIndexComplete(EventArgs $event)
    {
        $this->entryIndexCompleteService->setPoint($event, $this->container);
    }
}
