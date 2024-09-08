<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['character:read', 'raid:read', 'classe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['character:read', 'raid:read', 'classe:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'classe')]
    private Collection $characters;

    /**
     * @var Collection<int, Specialization>
     */
    #[ORM\OneToMany(targetEntity: Specialization::class, mappedBy: 'classe')]
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
            $character->setClasse($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getClasse() === $this) {
                $character->setClasse(null);
            }
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
            $specialization->setClasse($this);
        }

        return $this;
    }

    public function removeSpecialization(Specialization $specialization): static
    {
        if ($this->specializations->removeElement($specialization)) {
            // set the owning side to null (unless already changed)
            if ($specialization->getClasse() === $this) {
                $specialization->setClasse(null);
            }
        }

        return $this;
    }
}
