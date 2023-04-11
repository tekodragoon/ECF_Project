<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?bool $service = null;

    #[ORM\OneToOne(targetEntity: Table::class)]
    private ?Table $reservedTable = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?SimpleUser $simpleUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function isService(): ?bool
    {
        return $this->service;
    }

    public function setService(bool $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getReservedTable(): ?Table
    {
        return $this->reservedTable;
    }

    public function setReservedTable(?Table $reservedTable): self
    {
        $this->reservedTable = $reservedTable;

        return $this;
    }

    public function getSimpleUser(): ?SimpleUser
    {
        return $this->simpleUser;
    }

    public function setSimpleUser(SimpleUser $simpleUser): self
    {
        $this->simpleUser = $simpleUser;

        return $this;
    }
}
