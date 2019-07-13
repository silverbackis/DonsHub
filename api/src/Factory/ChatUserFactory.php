<?php

namespace App\Factory;

use App\Entity\ChatUser;
use App\Entity\TerraceSeatPosition;
use App\Repository\ChatUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ChatUserFactory
{
    private $entityManager;
    private $chatUserRepository;
    private $normalizer;

    public function __construct(
        EntityManagerInterface $entityManager,
        ChatUserRepository $chatUserRepository)
    {
        $this->entityManager = $entityManager;
        $this->chatUserRepository = $chatUserRepository;
        $this->normalizer = new ObjectNormalizer();
    }

    /**
     * @param ChatUser[] $allUsers
     * @return ArrayCollection|TerraceSeatPosition[]
     * @throws ExceptionInterface
     */
    private function getAllSeatPositions(array $allUsers): ArrayCollection
    {
        $rows = 7;
        $minSeatNumber = 1;
        $maxSeatNumber = 14;
        $percentCapacityExtend = 0.85;
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
     * @param ChatUser $chatUser
     * @return ChatUser
     * @throws ExceptionInterface
     */
    public function setUserTerracePosition(ChatUser $chatUser): ChatUser
    {
        $allUsers = $this->chatUserRepository->findAll();
        $seatPositions = $this->getAllSeatPositions($allUsers);
        foreach ($allUsers as $currentUser) {
            $existingPosition = $this->normalizer->normalize(new TerraceSeatPosition($currentUser->getTerraceRow(), $currentUser->getTerraceSeat()));
            $seatPositions->removeElement($existingPosition);
        }
        $randomSeatReference = array_rand($seatPositions->toArray());
        /** @var TerraceSeatPosition $seatPosition */
        $seatPosition = $this->normalizer->denormalize($seatPositions->get($randomSeatReference), TerraceSeatPosition::class);
        $chatUser->setTerraceRow($seatPosition->getRow());
        $chatUser->setTerraceSeat($seatPosition->getSeat());
        return $chatUser;
    }

    /**
     * @param int $totalUsers
     * @return ArrayCollection
     * @throws ExceptionInterface
     */
    public function create(int $totalUsers = 1): ArrayCollection
    {
        $userCollection = new ArrayCollection();
        for($u = 0; $u < $totalUsers; $u++) {
            $randomAvatar = ChatUser::AVATARS[array_rand(ChatUser::AVATARS)];
            $newUser = new ChatUser();
            $newUser
                ->setAvatar($randomAvatar)
                ->setUsername(bin2hex(random_bytes(50)))
                ->setPassword(bin2hex(random_bytes(10)))
            ;
            $this->setUserTerracePosition($newUser);
            $userCollection->add($newUser);
            $this->entityManager->persist($newUser);
            // Must flush so that next user will be found by the findAll statement - better ways of doing this for sure
            // But it's only a dev command to create the users so has no real world impact
            $this->entityManager->flush();
        }
        return $userCollection;
    }
}
