<?php

namespace App\Entity;

use App\Repository\ProgressionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgressionRepository::class)]
class Progression
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'progressions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur_id = null;

    #[ORM\ManyToOne(inversedBy: 'progressions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Defi $defi_id = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(nullable: true)]
    private ?int $progression = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_completion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurId(): ?User
    {
        return $this->utilisateur_id;
    }

    public function setUtilisateurId(?User $utilisateur_id): static
    {
        $this->utilisateur_id = $utilisateur_id;

        return $this;
    }

    public function getDefiId(): ?Defi
    {
        return $this->defi_id;
    }

    public function setDefiId(?Defi $defi_id): static
    {
        $this->defi_id = $defi_id;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getProgression(): ?int
    {
        return $this->progression;
    }

    public function setProgression(?int $progression): static
    {
        $this->progression = $progression;

        return $this;
    }

    public function getDateCompletion(): ?\DateTimeInterface
    {
        return $this->date_completion;
    }

    public function setDateCompletion(?\DateTimeInterface $date_completion): static
    {
        $this->date_completion = $date_completion;

        return $this;
    }
}
