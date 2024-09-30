<?php

namespace App\Entity;

use App\Repository\CharacterProfessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: CharacterProfessionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['characterProfession:read']],
    denormalizationContext: ['groups' => ['characterProfession:write']],
)]
class CharacterProfession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['characterProfession:read', 'character:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Character::class, inversedBy: 'characterProfessions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['characterProfession:read', 'characterProfession:write'])]
    private ?Character $character = null;

    #[ORM\ManyToOne(targetEntity: Profession::class, inversedBy: 'characterProfessions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['characterProfession:read', 'characterProfession:write'])]
    private ?Profession $profession = null;

    #[ORM\Column]
    #[Groups(['characterProfession:read', 'character:read'])]
    private ?int $level = null;

    #[ORM\ManyToOne(targetEntity: ProfessionSpecialization::class, inversedBy: 'characterProfessions')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['characterProfession:read', 'characterProfession:write', 'character:read'])]
    private ?ProfessionSpecialization $specialization = null;

    /**
     * @var Collection<int, Patron>
     */
    #[ORM\ManyToMany(targetEntity: Patron::class, inversedBy: 'characterProfessions')]
    #[Groups(['characterProfession:read', 'characterProfession:write', 'character:read'])]
    private Collection $patrons;

    public function __construct()
    {
        $this->patrons = new ArrayCollection();
    }

    // Getters et Setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    public function setCharacter(?Character $character): static
    {
        $this->character = $character;
        return $this;
    }

    public function getProfession(): ?Profession
    {
        return $this->profession;
    }

    public function setProfession(?Profession $profession): static
    {
        $this->profession = $profession;
        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;
        return $this;
    }

    public function getSpecialization(): ?ProfessionSpecialization
    {
        return $this->specialization;
    }

    public function setSpecialization(?ProfessionSpecialization $specialization): static
    {
        $this->specialization = $specialization;
        return $this;
    }

    /**
     * @return Collection<int, Patron>
     */
    public function getPatrons(): Collection
    {
        return $this->patrons;
    }

    public function addPatron(Patron $patron): static
    {
        if (!$this->patrons->contains($patron)) {
            $this->patrons->add($patron);
        }

        return $this;
    }

    public function removePatron(Patron $patron): static
    {
        $this->patrons->removeElement($patron);
        return $this;
    }
}
