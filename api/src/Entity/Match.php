<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use App\Controller\MatchesCurrentController;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     mercure="true",
 *     normalizationContext={"groups"={"match:read"}},
 *     itemOperations={ "GET" },
 *     collectionOperations={
 *         "GET",
 *         "current"={
 *             "method"="GET",
 *             "path"="/matches/current",
 *             "controller"=MatchesCurrentController::class,
 *             "pagination_enabled"=false,
 *             "http_cache"={ "max_age"=0, "shared_max_age"=0 },
 *             "defaults"={"_api_receive"=false}
 *          }
 *     },
 *     attributes={"pagination_items_per_page"=200, "order"={"matchHomeTeamName": "ASC"}}
 * )
 * @ApiFilter(DateFilter::class, properties={"matchDateTime"})
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(indexes={ @ORM\Index(name="match_date_time_index", columns={"match_date_time"}), @ORM\Index(name="home_team_name", columns={"match_home_team_name"}) })
 */
class Match
{
    private $gatesOpenTimeMinutesBefore = 60;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match:read"})
     */
    private $countryId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"match:read"})
     */
    private $countryName;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match:read"})
     */
    private $leagueId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"match:read"})
     */
    private $leagueName;

    /**
     * @ORM\Column(type="datetimetz")
     * @Groups({"match:read"})
     */
    private $matchDateTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"match:read"})
     */
    private $matchStatus;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match:read"})
     */
    private $matchHomeTeamId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"match:read"})
     */
    private $matchHomeTeamName;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match:read"})
     */
    private $matchAwayTeamId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"match:read"})
     */
    private $matchAwayTeamName;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match:read"})
     */
    private $matchHomeTeamScore;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match:read"})
     */
    private $matchAwayTeamScore;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match:read"})
     */
    private $matchId;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MatchLeague", mappedBy="match", cascade={"persist", "remove"})
     * @Groups({"match:read"})
     */
    private $matchLeague;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChatMessage", mappedBy="match", orphanRemoval=true)
     */
    private $chatMessages;

    public function __construct()
    {
        $this->chatMessages = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->created = new DateTime();
        $this->updated = new DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updated = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryId(): ?int
    {
        return $this->countryId;
    }

    public function setCountryId(int $countryId): self
    {
        $this->countryId = $countryId;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName($countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }

    public function getLeagueId(): ?int
    {
        return $this->leagueId;
    }

    public function setLeagueId(int $leagueId): self
    {
        $this->leagueId = $leagueId;

        return $this;
    }

    public function getLeagueName(): ?string
    {
        return $this->leagueName;
    }

    public function setLeagueName(string $leagueName): self
    {
        $this->leagueName = $leagueName;

        return $this;
    }

    public function getMatchDateTime(): DateTime
    {
        return $this->matchDateTime;
    }

    public function setMatchDateTime(DateTime $matchDateTime): self
    {
        $this->matchDateTime = $matchDateTime;

        return $this;
    }

    public function getMatchStatus(): string
    {
        return (string) $this->matchStatus;
    }

    public function setMatchStatus(?string $matchStatus): self
    {
        $this->matchStatus = $matchStatus;

        return $this;
    }

    public function getMatchHomeTeamId(): ?int
    {
        return $this->matchHomeTeamId;
    }

    public function setMatchHomeTeamId(int $matchHomeTeamId): self
    {
        $this->matchHomeTeamId = $matchHomeTeamId;

        return $this;
    }

    public function getMatchHomeTeamName(): ?string
    {
        return $this->matchHomeTeamName;
    }

    public function setMatchHomeTeamName(string $matchHomeTeamName): self
    {
        $this->matchHomeTeamName = $matchHomeTeamName;

        return $this;
    }

    public function getMatchAwayTeamId(): ?int
    {
        return $this->matchAwayTeamId;
    }

    public function setMatchAwayTeamId(int $matchAwayTeamId): self
    {
        $this->matchAwayTeamId = $matchAwayTeamId;

        return $this;
    }

    public function getMatchAwayTeamName(): ?string
    {
        return $this->matchAwayTeamName;
    }

    public function setMatchAwayTeamName(string $matchAwayTeamName): self
    {
        $this->matchAwayTeamName = $matchAwayTeamName;

        return $this;
    }

    public function getMatchHomeTeamScore(): ?int
    {
        return $this->matchHomeTeamScore;
    }

    public function setMatchHomeTeamScore($matchHomeTeamScore): self
    {
        $this->matchHomeTeamScore = (int) $matchHomeTeamScore;

        return $this;
    }

    public function getMatchAwayTeamScore(): ?int
    {
        return $this->matchAwayTeamScore;
    }

    public function setMatchAwayTeamScore($matchAwayTeamScore): self
    {
        $this->matchAwayTeamScore = (int) $matchAwayTeamScore;

        return $this;
    }

    public function getMatchId(): ?int
    {
        return $this->matchId;
    }

    public function setMatchId(int $matchId): self
    {
        $this->matchId = $matchId;

        return $this;
    }

    /**
     * @Groups({"match:read"})
     */
    public function getGatesOpen(): ?DateTime
    {
        if (!$gatesOpenTime = $this->getMatchDateTime()) {
            return null;
        }
        $gatesOpenTime->modify('-' . $this->gatesOpenTimeMinutesBefore . ' minutes');
        return $gatesOpenTime;
    }

    /**
     * @Groups({"match:read"})
     */
    public function getSecondsUntilGatesOpen(): int
    {
        if (!($gatesOpenTime = $this->getGatesOpen())) {
            return null;
        }
        $now = new DateTimeImmutable('now');
        return max(0, ceil(($gatesOpenTime->getTimestamp() - $now->getTimestamp()) / 1000));
    }

    /**
     * @Groups({"match:read"})
     */
    public function isGatesOpen(): bool
    {
        $secondsUntilOpen = $this->getSecondsUntilGatesOpen();
        if ($secondsUntilOpen === null) {
            return false;
        }
        return $secondsUntilOpen === 0;
    }

    public function getMatchLeague(): ?MatchLeague
    {
        return $this->matchLeague;
    }

    public function setMatchLeague(MatchLeague $matchLeague): self
    {
        $this->matchLeague = $matchLeague;

        // set the owning side of the relation if necessary
        if ($this !== $matchLeague->getMatch()) {
            $matchLeague->setMatch($this);
        }

        return $this;
    }

    public function isMatchSame(Match $match): bool
    {
        return (
            $this->getMatchId() === $match->getMatchId() &&
            $this->getCountryId() === $match->getCountryId() &&
            $this->getLeagueId() === $match->getLeagueId() &&
            $this->getMatchAwayTeamId() === $match->getMatchAwayTeamId() &&
            $this->getMatchHomeTeamId() === $match->getMatchHomeTeamId()
        );
    }

    public function updateFromMatch(Match $match): self
    {
        $this->setMatchHomeTeamScore($match->getMatchHomeTeamScore());
        $this->setMatchAwayTeamScore($match->getMatchAwayTeamScore());
        $this->setMatchStatus($match->getMatchStatus());
        $thisDT = $this->getMatchDateTime();
        $compareDT = $match->getMatchDateTime();
        if ($thisDT != $compareDT) {
            $this->setMatchDateTime($match->getMatchDateTime());
        }
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
            $chatMessage->setMatch($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): self
    {
        if ($this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->removeElement($chatMessage);
            // set the owning side to null (unless already changed)
            if ($chatMessage->getMatch() === $this) {
                $chatMessage->setMatch(null);
            }
        }

        return $this;
    }
}
