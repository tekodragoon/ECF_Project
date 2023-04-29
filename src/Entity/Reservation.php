<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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
    private ?bool $noonService = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?SimpleUser $simpleUser = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $reservedTables = [];

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?DateTimeImmutable $time = null;

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

    public function isNoonService(): ?bool
    {
        return $this->noonService;
    }

    public function setNoonService(bool $noonService): self
    {
        $this->noonService = $noonService;

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

    public function getReservedTables(): array
    {
        return $this->reservedTables;
    }

    public function addReservedTables(int $reservedTable): self
    {
        if (!in_array($reservedTable, $this->reservedTables, true)) {
            $this->reservedTables[] = $reservedTable;
        }

        return $this;
    }

    public function getTime(): ?DateTimeImmutable
    {
        return $this->time;
    }

    public function setTime(DateTimeImmutable $time): self
    {
        $this->time = $time;

        return $this;
    }
}
