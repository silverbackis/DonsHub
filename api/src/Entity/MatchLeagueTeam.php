<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ApiResource(
 *     mercure="true",
 *     itemOperations={ "GET" },
 *     collectionOperations={ "GET" },
 *     attributes={"pagination_items_per_page"=100, "order"={ "overallLeaguePosition": "ASC" }}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MatchLeagueTeamRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MatchLeagueTeam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"match_league:read"})
     */
    private $teamName;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match_league:read"})
     */
    private $teamId;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match_league:read"})
     */
    private $overallLeaguePosition;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match_league:read"})
     */
    private $overallGamesPlayed;

    /**
     * @ORM\Column(type="integer")
     * @SerializedName("overall_league_GF")
     */
    private $overallGoalsFor;

    /**
     * @ORM\Column(type="integer")
     * @SerializedName("overall_league_GA")
     */
    private $overallGoalsAgainst;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match_league:read"})
     */
    private $overallGoalDifference;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"match_league:read"})
     */
    private $overallPoints;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MatchLeague", inversedBy="matchLeagueTeams")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $matchLeague;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(string $teamName): self
    {
        $this->teamName = $teamName;

        return $this;
    }

    public function getTeamId(): int
    {
        return $this->teamId;
    }

    public function setTeamId(int $teamId): void
    {
        $this->teamId = $teamId;
    }

    public function getOverallLeaguePosition(): int
    {
        return $this->overallLeaguePosition;
    }

    public function setOverallLeaguePosition(int $overallLeaguePosition): self
    {
        $this->overallLeaguePosition = $overallLeaguePosition;

        return $this;
    }

    public function getOverallGamesPlayed(): int
    {
        return $this->overallGamesPlayed;
    }

    public function setOverallGamesPlayed(int $overallGamesPlayed): self
    {
        $this->overallGamesPlayed = $overallGamesPlayed;

        return $this;
    }

    public function getOverallGoalsFor(): int
    {
        return $this->overallGoalsFor;
    }

    public function setOverallGoalsFor(int $overallGoalsFor): self
    {
        $this->overallGoalsFor = $overallGoalsFor;

        return $this;
    }

    public function getOverallGoalsAgainst(): int
    {
        return $this->overallGoalsAgainst;
    }

    public function setOverallGoalsAgainst(int $overallGoalsAgainst): self
    {
        $this->overallGoalsAgainst = $overallGoalsAgainst;

        return $this;
    }

    public function getOverallPoints(): int
    {
        return $this->overallPoints;
    }

    public function setOverallPoints(int $overallPoints): self
    {
        $this->overallPoints = $overallPoints;

        return $this;
    }

    public function getMatchLeague(): ?MatchLeague
    {
        return $this->matchLeague;
    }

    public function setMatchLeague(?MatchLeague $matchLeague): self
    {
        $this->matchLeague = $matchLeague;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setOverallGoalDifference(): void
    {
        $this->overallGoalDifference = $this->getOverallGoalsFor() - $this->getOverallGoalsAgainst();
    }

    /**
     * @Groups({"match_league:read"})
     */
    public function getOverallGoalDifference(): int
    {
        return $this->getOverallGoalsFor() - $this->getOverallGoalsAgainst();
    }
}
