<?php

namespace App\Entity;

use App\Repository\GuildBossProgressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuildBossProgressRepository::class)]
class GuildBossProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'guildBossProgress')]
    private ?Boss $boss = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $difficulty = null;

    #[ORM\Column]
    private ?bool $defeated = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $firstKillDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoss(): ?Boss
    {
        return $this->boss;
    }

    public function setBoss(?Boss $boss): static
    {
        $this->boss = $boss;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function isDefeated(): ?bool
    {
        return $this->defeated;
    }

    public function setDefeated(bool $defeated): static
    {
        $this->defeated = $defeated;

        return $this;
    }

    public function getFirstKillDate(): ?\DateTimeInterface
    {
        return $this->firstKillDate;
    }

    public function setFirstKillDate(?\DateTimeInterface $firstKillDate): static
    {
        $this->firstKillDate = $firstKillDate;

        return $this;
    }
}
