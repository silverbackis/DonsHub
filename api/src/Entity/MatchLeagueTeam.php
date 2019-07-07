<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\MatchLeagueTeamRepository")
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
     */
    private $teamName;

    /**
     * @ORM\Column(type="integer")
     */
    private $overallLeaguePosition;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MatchLeague", inversedBy="matchLeagueTeams")
     * @ORM\JoinColumn(nullable=false)
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

    public function getOverallLeaguePosition(): ?int
    {
        return $this->overallLeaguePosition;
    }

    public function setOverallLeaguePosition(int $overallLeaguePosition): self
    {
        $this->overallLeaguePosition = $overallLeaguePosition;

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
}
