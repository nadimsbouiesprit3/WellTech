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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'progressions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null; // Corrected property name and type

    #[ORM\ManyToOne(targetEntity: Defi::class, inversedBy: 'progressions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Defi $defi = null; // Corrected property name

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDefi(): ?Defi
    {
        return $this->defi;
    }

    public function setDefi(?Defi $defi): static
    {
        $this->defi = $defi;

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