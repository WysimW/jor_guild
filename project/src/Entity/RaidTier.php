<?php

namespace App\Entity;

use App\Repository\RaidTierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaidTierRepository::class)]
class RaidTier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'raidTiers')]
    private ?Extension $extension = null;

    /**
     * @var Collection<int, Boss>
     */
    #[ORM\OneToMany(targetEntity: Boss::class, mappedBy: 'raidTier')]
    private Collection $bosses;

    /**
     * @var Collection<int, Raid>
     */
    #[ORM\OneToMany(targetEntity: Raid::class, mappedBy: 'raidtier')]
    private Collection $raids;

    public function __construct()
    {
        $this->bosses = new ArrayCollection();
        $this->raids = new ArrayCollection();
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

    public function getExtension(): ?Extension
    {
        return $this->extension;
    }

    public function setExtension(?Extension $extension): static
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return Collection<int, Boss>
     */
    public function getBosses(): Collection
    {
        return $this->bosses;
    }

    public function addBoss(Boss $boss): static
    {
        if (!$this->bosses->contains($boss)) {
            $this->bosses->add($boss);
            $boss->setRaidTier($this);
        }

        return $this;
    }

    public function removeBoss(Boss $boss): static
    {
        if ($this->bosses->removeElement($boss)) {
            // set the owning side to null (unless already changed)
            if ($boss->getRaidTier() === $this) {
                $boss->setRaidTier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Raid>
     */
    public function getRaids(): Collection
    {
        return $this->raids;
    }

    public function addRaid(Raid $raid): static
    {
        if (!$this->raids->contains($raid)) {
            $this->raids->add($raid);
            $raid->setRaidtier($this);
        }

        return $this;
    }

    public function removeRaid(Raid $raid): static
    {
        if ($this->raids->removeElement($raid)) {
            // set the owning side to null (unless already changed)
            if ($raid->getRaidtier() === $this) {
                $raid->setRaidtier(null);
            }
        }

        return $this;
    }
}
