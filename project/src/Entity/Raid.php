<?php

namespace App\Entity;

use App\Repository\RaidRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RaidRepository::class)]
class Raid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?int $capacity = null;

    /**
     * @var Collection<int, RaidRegister>
     */
    #[ORM\OneToMany(targetEntity: RaidRegister::class, mappedBy: 'raid')]
    private Collection $raidRegisters;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['raid:read'])] // Inclure cet ID dans le groupe "raid:read"
    private ?string $mode = null;


    #[ORM\ManyToOne(inversedBy: 'raids')]
    private ?RaidTier $raidtier = null;

    /**
     * @var Collection<int, Boss>
     */
    #[ORM\ManyToMany(targetEntity: Boss::class, inversedBy: 'raids')]
    private Collection $downBosses;

    #[ORM\Column(nullable: true)]
    private ?bool $isArchived = null;


    public function __construct()
    {
        $this->raidRegisters = new ArrayCollection();
        $this->downBosses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): static
    {
        $this->capacity = $capacity;

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
            $raidRegister->setRaid($this);
        }

        return $this;
    }

    public function removeRaidRegister(RaidRegister $raidRegister): static
    {
        if ($this->raidRegisters->removeElement($raidRegister)) {
            // set the owning side to null (unless already changed)
            if ($raidRegister->getRaid() === $this) {
                $raidRegister->setRaid(null);
            }
        }

        return $this;
    }

    // Ajoutez cette méthode pour formater la date
    #[Groups(['raid:read'])]
    public function getFormattedDate(): ?string
    {
        return $this->date ? $this->date->format('l d à H\h') : null;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }


    public function getRaidtier(): ?RaidTier
    {
        return $this->raidtier;
    }

    public function setRaidtier(?RaidTier $raidtier): static
    {
        $this->raidtier = $raidtier;

        return $this;
    }

    /**
     * @return Collection<int, Boss>
     */
    public function getDownBosses(): Collection
    {
        return $this->downBosses;
    }

    public function addDownBoss(Boss $downBoss): static
    {
        if (!$this->downBosses->contains($downBoss)) {
            $this->downBosses->add($downBoss);
        }

        return $this;
    }

    public function removeDownBoss(Boss $downBoss): static
    {
        $this->downBosses->removeElement($downBoss);
        return $this;
    }
    
    
    public function isArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setArchived(?bool $isArchived): static
    {
        $this->isArchived = $isArchived;


        return $this;
    }
}
