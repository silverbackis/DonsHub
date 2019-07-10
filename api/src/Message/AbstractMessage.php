<?php

namespace App\Message;

use DateTime;

abstract class AbstractMessage
{
    private $id;
    private $sleepSeconds;

    public function __construct(int $sleepSeconds = 0)
    {
        $this->id = sprintf('%s-%s', bin2hex(random_bytes(3)), (new DateTime('now'))->getTimestamp());
        $this->sleepSeconds = $sleepSeconds;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSleepSeconds(): int
    {
        return $this->sleepSeconds;
    }

    public function setSleepSeconds(int $sleepSeconds): self
    {
        $this->sleepSeconds = $sleepSeconds;
        return $this;
    }
}
