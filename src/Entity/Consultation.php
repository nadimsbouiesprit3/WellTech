<?php
namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $psychiatrist = null;


    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

public function getPsychiatrist(): ?User
{
    return $this->psychiatrist;
}

public function setPsychiatrist(?User $psychiatrist): self
{
    $this->psychiatrist = $psychiatrist;

    return $this;
}

public function getCreatedAt(): ?\DateTimeInterface
{
    return $this->createdAt;
}

public function setCreatedAt(\DateTimeInterface $createdAt): self
{
    $this->createdAt = $createdAt;

    return $this;
}
public function getId(): ?int
{
    return $this->id;
}
}