<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['role:read', 'character:read', 'raid:read'])] // Fusion des groupes ici
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['role:read', 'character:read', 'raid:read'])] // Fusion des groupes ici
    private ?string $name = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\ManyToMany(targetEntity: Character::class, mappedBy: 'raidRoles')]
    private Collection $characters;

    /**
     * @var Collection<int, Specialization>
     */
    #[ORM\OneToMany(targetEntity: Specialization::class, mappedBy: 'speRole')]
    private Collection $specializations;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->specializations = new ArrayCollection();
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

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->addRaidRole($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        if ($this->characters->removeElement($character)) {
            $character->removeRaidRole($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Specialization>
     */
    public function getSpecializations(): Collection
    {
        return $this->specializations;
    }

    public function addSpecialization(Specialization $specialization): static
    {
        if (!$this->specializations->contains($specialization)) {
            $this->specializations->add($specialization);
            $specialization->setSpeRole($this);
        }

        return $this;
    }

    public function removeSpecialization(Specialization $specialization): static
    {
        if ($this->specializations->removeElement($specialization)) {
            // set the owning side to null (unless already changed)
            if ($specialization->getSpeRole() === $this) {
                $specialization->setSpeRole(null);
            }
        }

        return $this;
    }
}
