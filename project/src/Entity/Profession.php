<?php

namespace App\Entity;

use App\Repository\ProfessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: ProfessionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['profession:read']],
    denormalizationContext: ['groups' => ['profession:write']],
)]
class Profession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['profession:read', 'character:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['profession:read', 'character:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, CharacterProfession>
     */
    #[ORM\OneToMany(mappedBy: 'profession', targetEntity: CharacterProfession::class, cascade: ['persist', 'remove'])]
    private Collection $characterProfessions;

    public function __construct()
    {
        $this->characterProfessions = new ArrayCollection();
    }

    // Getters et Setters...

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
     * @return Collection<int, CharacterProfession>
     */
    public function getCharacterProfessions(): Collection
    {
        return $this->characterProfessions;
    }

    public function addCharacterProfession(CharacterProfession $characterProfession): static
    {
        if (!$this->characterProfessions->contains($characterProfession)) {
            $this->characterProfessions->add($characterProfession);
            $characterProfession->setProfession($this);
        }

        return $this;
    }

    public function removeCharacterProfession(CharacterProfession $characterProfession): static
    {
        if ($this->characterProfessions->removeElement($characterProfession)) {
            // set the owning side to null (unless already changed)
            if ($characterProfession->getProfession() === $this) {
                $characterProfession->setProfession(null);
            }
        }

        return $this;
    }
}
