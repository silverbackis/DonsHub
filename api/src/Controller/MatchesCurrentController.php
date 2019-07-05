<?php

namespace App\Controller;

use App\Entity\Match;
use App\Repository\MatchRepository;
use Symfony\Component\HttpFoundation\Request;

class MatchesCurrentController
{
    private $matchRepository;

    public function __construct(
        MatchRepository $matchRepository
    ) {
        $this->matchRepository = $matchRepository;
    }

    /**
     * @param Request $request
     * @return Match|null
     */
    public function __invoke(Request $request): ?Match
    {
        return $this->matchRepository->findCurrent();
    }
}
