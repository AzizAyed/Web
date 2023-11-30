<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StationRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Nom ne doit pas etre vide")]
    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[Assert\NotBlank(message: "Adresse ne doit pas etre vide")]
    #[ORM\Column(length: 150)]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La latitude ne doit pas être vide.')]
    #[Assert\Regex(
        pattern: "/^[^a-zA-Z]+$/",
        message: 'La latitude ne doit pas contenir de caractères alphabétiques.'
    )]
    #[Assert\Regex(
        pattern: "/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/",
        message: 'La latitude doit être un nombre comprit entre -90 et 90.'
    )]
    private ?float $lat = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La longitude ne doit pas être vide.')]
    #[Assert\Regex(
        pattern: "/^[^a-zA-Z]+$/",
        message: 'La longitude ne doit pas contenir de caractères alphabétiques.'
    )]
    #[Assert\Regex(
        pattern: "/^[-+]?((1[0-7]\d(\.\d+)?)|([1-9]?\d(\.\d+)?|180(\.0+)?))$/",
        message: 'La longitude doit être un nombre comprit entre -180 et 180.'
    )]
    private ?float $longi = null;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLongi(): ?string
    {
        return $this->longi;
    }

    public function setLongi(string $longi): static
    {
        $this->longi = $longi;

        return $this;
    }
}
