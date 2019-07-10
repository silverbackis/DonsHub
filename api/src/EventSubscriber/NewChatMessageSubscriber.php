<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\ChatMessage;
use App\Entity\ChatUser;
use App\Repository\MatchRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class NewChatMessageSubscriber implements EventSubscriberInterface
{
    private $security;
    private $matchRepository;

    public function __construct(
        Security $security,
        MatchRepository $matchRepository
    )
    {
        $this->security = $security;
        $this->matchRepository = $matchRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['attachOwnerAndMatch', EventPriorities::PRE_WRITE],
        ];
    }

    public function attachOwnerAndMatch(ViewEvent $event): void
    {
        $message = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$message instanceof ChatMessage || Request::METHOD_POST !== $method) {
            return;
        }
        $user = $this->security->getUser();
        if (!$user instanceof ChatUser) {
            return;
        }
        $message->setCreated(new \DateTime('now'));
        $message->setChatUser($user);
        $message->setMatch($this->matchRepository->findCurrent());
    }
}
