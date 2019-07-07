<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\MatchesCurrentController;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     mercure="true",
 *     itemOperations={ "GET" },
 *     collectionOperations={
 *         "current"={
 *             "method"="GET",
 *             "path"="/matches/current",
 *             "controller"=MatchesCurrentController::class,
 *             "pagination_enabled"=false,
 *             "http_cache"={ "max_age"=0 },
 *             "defaults"={"_api_receive"=false}
 *          },
 *          "GET"
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
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
     */
    private $countryId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $countryName;

    /**
     * @ORM\Column(type="integer")
     */
    private $leagueId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $leagueName;

    /**
     * @ORM\Column(type="datetimetz")
     * @ApiProperty()
     */
    private $matchDateTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $matchStatus;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchHomeTeamId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matchHomeTeamName;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchAwayTeamId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matchAwayTeamName;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchHomeTeamScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchAwayTeamScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchId;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MatchLeague", mappedBy="match", cascade={"persist", "remove"})
     */
    private $matchLeague;

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

    public function getMatchDateTime(): ?DateTime
    {
        return $this->matchDateTime;
    }

    public function setMatchDateTime(DateTime $matchDateTime): self
    {
        $this->matchDateTime = $matchDateTime;

        return $this;
    }

    public function getMatchStatus(): ?string
    {
        return $this->matchStatus;
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
        $this->matchHomeTeamScore = (int)$matchHomeTeamScore;

        return $this;
    }

    public function getMatchAwayTeamScore(): ?int
    {
        return $this->matchAwayTeamScore;
    }

    public function setMatchAwayTeamScore($matchAwayTeamScore): self
    {
        $this->matchAwayTeamScore = (int)$matchAwayTeamScore;

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

    public function isGatesOpen(): bool
    {
        return true;
        if (!$gatesOpenTime = $this->getMatchDateTime()) {
            return false;
        }
        $gatesOpenTime->modify('-' . $this->gatesOpenTimeMinutesBefore . ' minutes');
        $now = new DateTimeImmutable('now');
        return $now->getTimestamp()-$gatesOpenTime->getTimestamp() > 0;
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
}
