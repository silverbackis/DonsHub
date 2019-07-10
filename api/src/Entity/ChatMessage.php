<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     mercure="true",
 *     normalizationContext={ "groups"={"chat_message:read"} },
 *     denormalizationContext={ "groups"={"chat_message:write"} },
 *     attributes={ "pagination_items_per_page"=10, "order"={"created": "DESC"}, "access_control"="is_granted('ROLE_USER')" },
 *     itemOperations={
 *         "GET",
 *         "DELETE"={ "access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.chatUser == user)" }
 *     },
 *     collectionOperations={
 *         "GET",
 *         "POST"={ "access_control"="is_granted('ROLE_USER')" }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"match": "exact"})
 * @ApiFilter(DateFilter::class, properties={"created"})
 * @ORM\Entity(repositoryClass="App\Repository\ChatMessageRepository")
 * @ORM\Table(indexes={ @ORM\Index(name="match_chat_index", columns={"match_id"}), @ORM\Index(name="created_index", columns={"created"}) })
 */
class ChatMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ChatUser", inversedBy="chatMessages")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"chat_message:read"})
     */
    private $chatUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Match", inversedBy="chatMessages")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"chat_message:read"})
     */
    private $match;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"chat_message:read"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=500)
     * @Groups({"chat_message:write", "chat_message:read"})
     * @Assert\NotBlank(message="Please enter a message")
     * @Assert\Length(max="500", maxMessage="Your message cannot be more than 500 characters")
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChatUser(): ?ChatUser
    {
        return $this->chatUser;
    }

    public function setChatUser(?ChatUser $chatUser): self
    {
        $this->chatUser = $chatUser;

        return $this;
    }

    public function getMatch(): ?Match
    {
        return $this->match;
    }

    public function setMatch(?Match $match): self
    {
        $this->match = $match;

        return $this;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): DateTimeInterface
    {
        return $this->created;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
