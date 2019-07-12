<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\ChatUser;
use App\Factory\ChatUserFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

final class NewChatUserSubscriber implements EventSubscriberInterface
{
    private $chatUserFactory;

    public function __construct(
        ChatUserFactory $chatUserFactory
    )
    {
        $this->chatUserFactory = $chatUserFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['attachOwnerAndMatch', EventPriorities::PRE_WRITE],
        ];
    }


    /**
     * @param ViewEvent $event
     * @throws ExceptionInterface
     */
    public function attachOwnerAndMatch(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$entity instanceof ChatUser || Request::METHOD_POST !== $method) {
            return;
        }

        $this->chatUserFactory->setUserTerracePosition($entity);
    }
}
