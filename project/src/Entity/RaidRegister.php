<?php

namespace App\Entity;

use App\Repository\RaidRegisterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RaidRegisterRepository::class)]
class RaidRegister
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'raidRegisters')]
    private ?Raid $raid = null;

    #[ORM\ManyToOne(inversedBy: 'raidRegisters')]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?Character $registredCharacter = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?\DateTimeInterface $registeredDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaid(): ?Raid
    {
        return $this->raid;
    }

    public function setRaid(?Raid $raid): static
    {
        $this->raid = $raid;

        return $this;
    }

    public function getRegistredCharacter(): ?Character
    {
        return $this->registredCharacter;
    }

    public function setRegistredCharacter(?Character $registredCharacter): static
    {
        $this->registredCharacter = $registredCharacter;

        return $this;
    }

    public function getRegisteredDate(): ?\DateTimeInterface
    {
        return $this->registeredDate;
    }

    public function setRegisteredDate(\DateTimeInterface $registeredDate): static
    {
        $this->registeredDate = $registeredDate;

        return $this;
    }
}
