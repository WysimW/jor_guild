<?php

namespace App\Entity;

use App\Repository\SpecializationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SpecializationRepository::class)]
class Specialization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['specialization:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['character:read', 'raid:read', 'specialization:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'specializations')]
    private ?Classe $classe = null;

    /**
     * @var Collection<int, RaidRegister>
     */
    #[ORM\ManyToMany(targetEntity: RaidRegister::class, mappedBy: 'registredSpecialization')]
    private Collection $raidRegisters;

    #[ORM\ManyToOne(inversedBy: 'specializations')]
    private ?Role $speRole = null;

    public function __construct()
    {
        $this->raidRegisters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * @return Collection<int, RaidRegister>
     */
    public function getRaidRegisters(): Collection
    {
        return $this->raidRegisters;
    }

    public function addRaidRegister(RaidRegister $raidRegister): static
    {
        if (!$this->raidRegisters->contains($raidRegister)) {
            $this->raidRegisters->add($raidRegister);
            $raidRegister->addRegistredSpecialization($this);
        }

        return $this;
    }

    public function removeRaidRegister(RaidRegister $raidRegister): static
    {
        if ($this->raidRegisters->removeElement($raidRegister)) {
            $raidRegister->removeRegistredSpecialization($this);
        }

        return $this;
    }

    public function getSpeRole(): ?Role
    {
        return $this->speRole;
    }

    public function setSpeRole(?Role $speRole): static
    {
        $this->speRole = $speRole;

        return $this;
    }
}
