<?php

namespace App\Entity;

use App\Repository\ProgressionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProgressionRepository::class)]
class Progression
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'progressions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Defi::class, inversedBy: 'progressions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Defi $defi = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The status cannot be empty.")]
    private ?string $statut = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: "The progression must be a positive number or zero.")]
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

        // Automatically set the completion date if the status is "completed"
        if ($statut === 'completed' && $this->date_completion === null) {
            $this->date_completion = new \DateTime();
        }

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

    // Automatically update the completion date when the status changes to "completed"
    #[ORM\PreUpdate]
    public function updateCompletionDate(): void
    {
        if ($this->statut === 'completed' && $this->date_completion === null) {
            $this->date_completion = new \DateTime();
        }
    }
}