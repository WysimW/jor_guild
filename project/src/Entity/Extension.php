<?php

namespace App\Entity;

use App\Repository\ExtensionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExtensionRepository::class)]
class Extension
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    /**
     * @var Collection<int, RaidTier>
     */
    #[ORM\OneToMany(targetEntity: RaidTier::class, mappedBy: 'extension')]
    private Collection $raidTiers;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->raidTiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, RaidTier>
     */
    public function getRaidTiers(): Collection
    {
        return $this->raidTiers;
    }

    public function addRaidTier(RaidTier $raidTier): static
    {
        if (!$this->raidTiers->contains($raidTier)) {
            $this->raidTiers->add($raidTier);
            $raidTier->setExtension($this);
        }

        return $this;
    }

    public function removeRaidTier(RaidTier $raidTier): static
    {
        if ($this->raidTiers->removeElement($raidTier)) {
            // set the owning side to null (unless already changed)
            if ($raidTier->getExtension() === $this) {
                $raidTier->setExtension(null);
            }
        }

        return $this;
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
}
