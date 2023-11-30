<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $centenu = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Reclamation $reclamations = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCentenu(): ?string
    {
        return $this->centenu;
    }

    public function setCentenu(string $centenu): static
    {
        $this->centenu = $centenu;

        return $this;
    }

    public function getReclamations(): ?Reclamation
    {
        return $this->reclamations;
    }

    public function setReclamations(?Reclamation $reclamations): static
    {
        $this->reclamations = $reclamations;

        return $this;
    }
}
