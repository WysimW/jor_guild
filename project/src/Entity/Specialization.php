<?php

namespace App\Entity;

use App\Repository\SpecializationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SpecializationRepository::class)]
class Specialization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['character:read', 'raid:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'specializations')]
    private ?Classe $classe = null;

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
}
