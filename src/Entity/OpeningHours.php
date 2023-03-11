<?php

namespace App\Entity;

use App\Repository\OpeningHoursRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpeningHoursRepository::class)]
class OpeningHours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $noonStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $noonEnd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $eveningStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $eveningEnd = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $dayOfWeek = null;

    #[ORM\ManyToOne(inversedBy: 'openHours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoonStart(): ?DateTimeInterface
    {
        return $this->noonStart;
    }

    public function setNoonStart(DateTimeInterface $noonStart): self
    {
        $this->noonStart = $noonStart;

        return $this;
    }

    public function getNoonEnd(): ?DateTimeInterface
    {
        return $this->noonEnd;
    }

    public function setNoonEnd(DateTimeInterface $noonEnd): self
    {
        $this->noonEnd = $noonEnd;

        return $this;
    }

    public function getEveningStart(): ?DateTimeInterface
    {
        return $this->eveningStart;
    }

    public function setEveningStart(DateTimeInterface $eveningStart): self
    {
        $this->eveningStart = $eveningStart;

        return $this;
    }

    public function getEveningEnd(): ?DateTimeInterface
    {
        return $this->eveningEnd;
    }

    public function setEveningEnd(DateTimeInterface $eveningEnd): self
    {
        $this->eveningEnd = $eveningEnd;

        return $this;
    }

    public function getDayOfWeek(): ?int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
