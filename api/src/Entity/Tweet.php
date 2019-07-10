<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TweetRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="source", type="string")
 * @ORM\DiscriminatorMap({ "club"="ClubTweet", "fan"="FanTweet" })
 * @ORM\Table(indexes={ @ORM\Index(name="created_at", columns={"created_at"}), @ORM\Index(name="tweet_source", columns={"source"}), @ORM\Index(name="tweet_source_created_at", columns={"source", "created_at"}) })
 * @ApiFilter(DateFilter::class, properties={"createdAt"})
 */
abstract class Tweet
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $idStr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $text;

    /**
     * @ORM\Column(type="json")
     */
    protected $entities = [];

    /**
     * @ORM\Column(type="json")
     */
    protected $metadata = [];

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $inReplyToStatusId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $inReplyToStatusIdStr;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $inReplyToUserId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $inReplyToUserIdStr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $inReplyToScreenName;

    /**
     * @ORM\Column(type="json")
     * @SerializedName("user")
     */
    protected $twitterUser = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $geo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $coordinates;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $place = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $contributors;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $retweetedStatus = [];

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isQuoteStatus;

    /**
     * @ORM\Column(type="integer")
     */
    protected $retweetCount;

    /**
     * @ORM\Column(type="integer")
     */
    protected $favoriteCount;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $favorited;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $retweeted;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $possiblySensitive;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $lang;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIdStr(): ?string
    {
        return $this->idStr;
    }

    public function setIdStr(string $idStr): self
    {
        $this->idStr = $idStr;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getEntities(): ?array
    {
        return $this->entities;
    }

    public function setEntities(array $entities): self
    {
        $this->entities = $entities;

        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getInReplyToStatusId(): ?int
    {
        return $this->inReplyToStatusId;
    }

    public function setInReplyToStatusId(?int $inReplyToStatusId): self
    {
        $this->inReplyToStatusId = $inReplyToStatusId;

        return $this;
    }

    public function getInReplyToStatusIdStr(): ?string
    {
        return $this->inReplyToStatusIdStr;
    }

    public function setInReplyToStatusIdStr(?string $inReplyToStatusIdStr): self
    {
        $this->inReplyToStatusIdStr = $inReplyToStatusIdStr;

        return $this;
    }

    public function getInReplyToUserId(): ?int
    {
        return $this->inReplyToUserId;
    }

    public function setInReplyToUserId(?int $inReplyToUserId): self
    {
        $this->inReplyToUserId = $inReplyToUserId;

        return $this;
    }

    public function getInReplyToUserIdStr(): ?string
    {
        return $this->inReplyToUserIdStr;
    }

    public function setInReplyToUserIdStr(?string $inReplyToUserIdStr): self
    {
        $this->inReplyToUserIdStr = $inReplyToUserIdStr;

        return $this;
    }

    public function getInReplyToScreenName(): ?string
    {
        return $this->inReplyToScreenName;
    }

    public function setInReplyToScreenName(?string $inReplyToScreenName): self
    {
        $this->inReplyToScreenName = $inReplyToScreenName;

        return $this;
    }

    public function getTwitterUser(): ?array
    {
        return $this->twitterUser;
    }

    public function setTwitterUser(array $twitterUser): self
    {
        $this->twitterUser = $twitterUser;

        return $this;
    }

    public function getGeo(): ?string
    {
        return $this->geo;
    }

    public function setGeo(?string $geo): self
    {
        $this->geo = $geo;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getPlace(): ?array
    {
        return $this->place;
    }

    public function setPlace(?array $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getContributors(): ?string
    {
        return $this->contributors;
    }

    public function setContributors(?string $contributors): self
    {
        $this->contributors = $contributors;

        return $this;
    }

    public function getRetweetedStatus(): ?array
    {
        return $this->retweetedStatus;
    }

    public function setRetweetedStatus(?array $retweetedStatus): self
    {
        $this->retweetedStatus = $retweetedStatus;

        return $this;
    }

    public function getIsQuoteStatus(): ?bool
    {
        return $this->isQuoteStatus;
    }

    public function setIsQuoteStatus(bool $isQuoteStatus): self
    {
        $this->isQuoteStatus = $isQuoteStatus;

        return $this;
    }

    public function getRetweetCount(): ?int
    {
        return $this->retweetCount;
    }

    public function setRetweetCount(int $retweetCount): self
    {
        $this->retweetCount = $retweetCount;

        return $this;
    }

    public function getFavoriteCount(): ?int
    {
        return $this->favoriteCount;
    }

    public function setFavoriteCount(int $favoriteCount): self
    {
        $this->favoriteCount = $favoriteCount;

        return $this;
    }

    public function getFavorited(): ?bool
    {
        return $this->favorited;
    }

    public function setFavorited(bool $favorited): self
    {
        $this->favorited = $favorited;

        return $this;
    }

    public function getRetweeted(): ?bool
    {
        return $this->retweeted;
    }

    public function setRetweeted(bool $retweeted): self
    {
        $this->retweeted = $retweeted;

        return $this;
    }

    public function getPossiblySensitive(): ?bool
    {
        return $this->possiblySensitive;
    }

    public function setPossiblySensitive(?bool $possiblySensitive): self
    {
        $this->possiblySensitive = $possiblySensitive;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }
}
