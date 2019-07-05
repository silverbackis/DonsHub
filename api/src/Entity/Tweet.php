<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use DateTime;
use Symfony\Component\Serializer\Annotation\SerializedName;

abstract class Tweet
{
    /**
     * @var int
     * @ApiProperty(identifier=true)
     */
    protected $id;

    /**
     * @var string
     * @SerializedName("id_str")
     */
    protected $idStr;

    /**
     * @var DateTime
     * @SerializedName("created_at")
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $text;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdStr(): ?string
    {
        return $this->idStr;
    }

    public function setIdStr(string $idStr): void
    {
        $this->idStr = $idStr;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
