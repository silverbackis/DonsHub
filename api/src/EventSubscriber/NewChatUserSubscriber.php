<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\ChatUser;
use App\Repository\ChatUserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class NewChatUserSubscriber implements EventSubscriberInterface
{
    private $chatUserRepository;

    public function __construct(
        ChatUserRepository $chatUserRepository
    )
    {
        $this->chatUserRepository = $chatUserRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['attachOwnerAndMatch', EventPriorities::PRE_WRITE],
        ];
    }

    private function getPlacement(int $row, int $seat)
    {
        return sprintf('%d-%d', $row, $seat);
    }

    public function attachOwnerAndMatch(ViewEvent $event): void
    {
        $message = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$message instanceof ChatUser || Request::METHOD_POST !== $method) {
            return;
        }

        // Find a seat
//        $seats = [];
//        for($row=1; $row<=9; $row++) {
//            for($seat=1; $seat<=15; $seat++) {
//                $seats[$this->getPlacement($row, $seat)] = [
//                    'row' => $row,
//                    'seat' => $seat
//                ];
//            }
//        }
//
//        $seatPlacements = array_keys($seats);
//
//        $users = $this->chatUserRepository->findAll();
//        foreach ($users as $user) {
//            $userPlacement = $this->getPlacement($user->getTerraceRow(), $user->getTerraceSeat());
//            if ($index = array_search($userPlacement, $seats, true)) {
//                array_splice($seats, $index, 1);
//            }
//        }
//
//        $seats[array_rand($seats)];
    }
}
