<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ChatUserPostAction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     mercure="true",
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 *     itemOperations={
 *         "GET",
 *         "PUT"={ "controller"=ChatUserPostAction::class, "access_control"="is_granted('ROLE_USER') and object == user" }
 *     },
 *     collectionOperations={
 *          "GET",
 *          "POST"={ "controller"=ChatUserPostAction::class }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ChatUserRepository")
 * @UniqueEntity("username", message="That nickname is already in use at the moment.")
 */
class ChatUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Please enter a nickname")
     * @Assert\Length(max="180", maxMessage="Your nickname cannot be more than 180 characters", min="3", minMessage="Your nickname must be at least 3 characters")
     * @Groups({"user:write", "user:read", "chat_message:read"})
     */
    private $username;

    /**
     * @var null|string
     * @Groups({"user:write"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string")
     * @var null|string
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please select an avatar")
     * @Groups({"user:write", "user:read", "chat_message:read"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChatMessage", mappedBy="chatUser", orphanRemoval=true)
     */
    private $chatMessages;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     * @Assert\Range(min="1", max="9", minMessage="You must be at least on the first terrace row", maxMessage="There are only 9 rows on the terrace")
     * @var null|integer
     */
    private $terraceRow;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     * @Assert\GreaterThan(value="0", message="The terrace seat must be at least 1")
     * @var null|integer
     */
    private $terraceSeat;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->chatMessages = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection|ChatMessage[]
     */
    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessage $chatMessage): self
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages[] = $chatMessage;
            $chatMessage->setChatUser($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): self
    {
        if ($this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->removeElement($chatMessage);
            // set the owning side to null (unless already changed)
            if ($chatMessage->getChatUser() === $this) {
                $chatMessage->setChatUser(null);
            }
        }

        return $this;
    }

    public function getTerraceRow(): ?int
    {
        return $this->terraceRow;
    }

    public function setTerraceRow(?int $terraceRow): self
    {
        $this->terraceRow = $terraceRow;

        return $this;
    }

    public function getTerraceSeat(): ?int
    {
        return $this->terraceSeat;
    }

    public function setTerraceSeat(?int $terraceSeat): self
    {
        $this->terraceSeat = $terraceSeat;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
