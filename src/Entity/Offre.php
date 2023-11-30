<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\OffreRepository;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Le nom ne doit pas être vide.')]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le prix ne doit pas être vide.')]
    #[Assert\Regex(
        pattern: "/^[^a-zA-Z]+$/",
        message: 'Le prix ne doit pas contenir de caractères alphabétiques.'
    )]
    #[Assert\Regex(
        pattern: "/^\d+(\.\d{1,3})?$/",
        message: 'Le prix doit être un nombre avec au plus trois chiffres après la virgule.'
    )]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $duree = null;

    // Getter and setter methods...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }
}
