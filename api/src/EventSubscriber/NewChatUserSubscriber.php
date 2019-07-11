<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\ChatUser;
use App\Entity\TerraceSeatPosition;
use App\Repository\ChatUserRepository;
use function count;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class NewChatUserSubscriber implements EventSubscriberInterface
{
    private $chatUserRepository;
    private $normalizer;

    public function __construct(
        ChatUserRepository $chatUserRepository
    )
    {
        $this->chatUserRepository = $chatUserRepository;
        $this->normalizer = new ObjectNormalizer();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['attachOwnerAndMatch', EventPriorities::PRE_WRITE],
        ];
    }

    private function getRowSeatReference(int $row, int $seat)
    {
        return sprintf('%d-%d', $row, $seat);
    }

    /**
     * @param ChatUser[] $allUsers
     * @return ArrayCollection|TerraceSeatPosition[]
     * @throws ExceptionInterface
     */
    private function getAllSeatPositions(array $allUsers): ArrayCollection
    {
        $rows = 9;
        $minSeatNumber = 1;
        $maxSeatNumber = 14;
        $percentCapacityExtend = 0.92;
        $totalUsers = count($allUsers);
        while($totalUsers > ($rows * ($maxSeatNumber - $minSeatNumber + 1))*$percentCapacityExtend) {
            $maxSeatNumber+=5;
            $minSeatNumber-=5;
        }

        $seatPositionCollection = new ArrayCollection();
        for($currentRow=1; $currentRow<=9; $currentRow++) {
            for($currentSeat=$minSeatNumber; $currentSeat<=$maxSeatNumber; $currentSeat++) {
                $seatPositionCollection->add(
                    $this->normalizer->normalize(new TerraceSeatPosition($currentRow, $currentSeat))
                );
            }
        }
        return $seatPositionCollection;
    }

    /**
     * @param ViewEvent $event
     * @throws ExceptionInterface
     */
    public function attachOwnerAndMatch(ViewEvent $event): void
    {
        $message = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$message instanceof ChatUser || Request::METHOD_POST !== $method) {
            return;
        }
        $allUsers = $this->chatUserRepository->findAll();
        $seatPositions = $this->getAllSeatPositions($allUsers);
        foreach ($allUsers as $currentUser) {
            $existingPosition = $this->normalizer->normalize(new TerraceSeatPosition($currentUser->getTerraceRow(), $currentUser->getTerraceSeat()));
            $seatPositions->removeElement($existingPosition);
        }
        $randomSeatReference = array_rand($seatPositions->toArray());
        /** @var TerraceSeatPosition $seatPosition */
        $seatPosition = $this->normalizer->denormalize($seatPositions->get($randomSeatReference), TerraceSeatPosition::class);
        $message->setTerraceRow($seatPosition->getRow());
        $message->setTerraceSeat($seatPosition->getSeat());
    }
}
