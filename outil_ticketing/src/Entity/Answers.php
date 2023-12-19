<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswersRepository::class)]
class Answers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $resolved_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $docLink = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Tickets $ticket = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getResolveddAt(): ?\DateTimeImmutable
    {
        return $this->resolved_at;
    }

    public function setResolveddAt(\DateTimeImmutable $resolved_at): static
    {
        $this->resolved_at = $resolved_at;

        return $this;
    }

    public function getDocLink(): ?string
    {
        return $this->docLink;
    }

    public function setDocLink(?string $docLink): static
    {
        $this->docLink = $docLink;

        return $this;
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

    public function getTicket(): ?Tickets
    {
        return $this->ticket;
    }

    public function setTicket(?Tickets $ticket): static
    {
        $this->ticket = $ticket;

        return $this;
    }
    public function __construct()
    {
      
        $this->resolved_at = new \DateTimeImmutable();
    }
}
