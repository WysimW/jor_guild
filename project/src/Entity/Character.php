<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['character:read', 'raid:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['character:read', 'raid:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    private ?User $user = null;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'characters')]
    #[Groups(['character:read', 'raid:read'])]
    private Collection $raidRoles;

    /**
     * @var Collection<int, RaidRegister>
     */
    #[ORM\OneToMany(targetEntity: RaidRegister::class, mappedBy: 'registredCharacter')]
    private Collection $raidRegisters;

    public function __construct()
    {
        $this->raidRoles = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRaidRoles(): Collection
    {
        return $this->raidRoles;
    }

    public function addRaidRole(Role $raidRole): static
    {
        if (!$this->raidRoles->contains($raidRole)) {
            $this->raidRoles->add($raidRole);
        }

        return $this;
    }

    public function removeRaidRole(Role $raidRole): static
    {
        $this->raidRoles->removeElement($raidRole);

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
            $raidRegister->setRegistredCharacter($this);
        }

        return $this;
    }

    public function removeRaidRegister(RaidRegister $raidRegister): static
    {
        if ($this->raidRegisters->removeElement($raidRegister)) {
            // set the owning side to null (unless already changed)
            if ($raidRegister->getRegistredCharacter() === $this) {
                $raidRegister->setRegistredCharacter(null);
            }
        }

        return $this;
    }
}
