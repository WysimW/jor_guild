<?php

namespace App\Entity;

use App\Repository\RaidRegisterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Specialization>
     */
    #[ORM\ManyToMany(targetEntity: Specialization::class, inversedBy: 'raidRegisters')]
    private Collection $registredSpecialization;

    public function __construct()
    {
        $this->registredSpecialization = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Specialization>
     */
    public function getRegistredSpecialization(): Collection
    {
        return $this->registredSpecialization;
    }

    public function addRegistredSpecialization(Specialization $registredSpecialization): static
    {
        if (!$this->registredSpecialization->contains($registredSpecialization)) {
            $this->registredSpecialization->add($registredSpecialization);
        }

        return $this;
    }

    public function removeRegistredSpecialization(Specialization $registredSpecialization): static
    {
        $this->registredSpecialization->removeElement($registredSpecialization);

        return $this;
    }
}
