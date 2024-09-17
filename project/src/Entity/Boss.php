<?php

namespace App\Entity;

use App\Repository\BossRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BossRepository::class)]
class Boss
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'bosses')]
    private ?RaidTier $raidTier = null;

    /**
     * @var Collection<int, Raid>
     */
    #[ORM\ManyToMany(targetEntity: Raid::class, mappedBy: 'downBosses')]
    private Collection $raids;

    /**
     * @var Collection<int, GuildBossProgress>
     */
    #[ORM\OneToMany(targetEntity: GuildBossProgress::class, mappedBy: 'boss')]
    private Collection $guildBossProgress;

    #[ORM\Column(nullable: true)]
    private ?int $orderInRaid = null;

    public function __construct()
    {
        $this->raids = new ArrayCollection();
        $this->guildBossProgress = new ArrayCollection();
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

    public function getRaidTier(): ?RaidTier
    {
        return $this->raidTier;
    }

    public function setRaidTier(?RaidTier $raidTier): static
    {
        $this->raidTier = $raidTier;

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
            $raid->addDownBoss($this);
        }

        return $this;
    }

    public function removeRaid(Raid $raid): static
    {
        if ($this->raids->removeElement($raid)) {
            $raid->removeDownBoss($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GuildBossProgress>
     */
    public function getGuildBossProgress(): Collection
    {
        return $this->guildBossProgress;
    }

    public function addGuildBossProgress(GuildBossProgress $guildBossProgress): static
    {
        if (!$this->guildBossProgress->contains($guildBossProgress)) {
            $this->guildBossProgress->add($guildBossProgress);
            $guildBossProgress->setBoss($this);
        }

        return $this;
    }

    public function removeGuildBossProgress(GuildBossProgress $guildBossProgress): static
    {
        if ($this->guildBossProgress->removeElement($guildBossProgress)) {
            // set the owning side to null (unless already changed)
            if ($guildBossProgress->getBoss() === $this) {
                $guildBossProgress->setBoss(null);
            }
        }

        return $this;
    }

    public function getOrderInRaid(): ?int
    {
        return $this->orderInRaid;
    }

    public function setOrderInRaid(?int $orderInRaid): static
    {
        $this->orderInRaid = $orderInRaid;

        return $this;
    }
}
