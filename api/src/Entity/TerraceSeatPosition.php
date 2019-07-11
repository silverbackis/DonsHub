<?php

namespace App\Entity;

class TerraceSeatPosition
{
    /** @var int */
    private $row;
    /** @var int */
    private $seat;

    public function __construct(int $row = 0, int $seat = 0)
    {
        $this->row = $row;
        $this->seat = $seat;
    }

    public function setRow(int $row): void
    {
        $this->row = $row;
    }

    public function setSeat(int $seat): void
    {
        $this->seat = $seat;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getSeat(): int
    {
        return $this->seat;
    }
}
