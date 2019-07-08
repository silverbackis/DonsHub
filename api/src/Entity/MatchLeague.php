<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     mercure="true",
 *     itemOperations={ "GET" },
 *     collectionOperations={ "GET" }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MatchLeagueRepository")
 */
class MatchLeague
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Match", inversedBy="matchLeague", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $match;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MatchLeagueTeam", mappedBy="matchLeague", orphanRemoval=true)
     */
    private $matchLeagueTeams;

    public function __construct()
    {
        $this->matchLeagueTeams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatch(): ?Match
    {
        return $this->match;
    }

    public function setMatch(Match $match): self
    {
        $this->match = $match;

        return $this;
    }

    /**
     * @return Collection|MatchLeagueTeam[]
     */
    public function getMatchLeagueTeams(): Collection
    {
        return $this->matchLeagueTeams;
    }

    public function addMatchLeagueTeam(MatchLeagueTeam $matchLeagueTeam): self
    {
        if (!$this->matchLeagueTeams->contains($matchLeagueTeam)) {
            $this->matchLeagueTeams[] = $matchLeagueTeam;
            $matchLeagueTeam->setMatchLeague($this);
        }

        return $this;
    }

    public function removeMatchLeagueTeam(MatchLeagueTeam $matchLeagueTeam): self
    {
        if ($this->matchLeagueTeams->contains($matchLeagueTeam)) {
            $this->matchLeagueTeams->removeElement($matchLeagueTeam);
            // set the owning side to null (unless already changed)
            if ($matchLeagueTeam->getMatchLeague() === $this) {
                $matchLeagueTeam->setMatchLeague(null);
            }
        }

        return $this;
    }
}
