<?php

namespace App\Entity;

use App\Repository\OpeningDaysRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpeningDaysRepository::class)]
class OpeningDays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $open = null;

    #[ORM\Column]
    private ?bool $noonService = null;

    #[ORM\Column]
    private ?bool $eveningService = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $dayOfWeek = null;

    #[ORM\ManyToOne(inversedBy: 'openDays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    public function __construct()
    {
        $this->open = false;
        $this->noonService = false;
        $this->eveningService= false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function isNoonService(): ?bool
    {
        return $this->noonService;
    }

    public function setNoonService(?bool $noonService): self
    {
        $this->noonService = $noonService;

        return $this;
    }

    public function isEveningService(): ?bool
    {
        return $this->eveningService;
    }

    public function setEveningService(?bool $eveningService): self
    {
        $this->eveningService = $eveningService;

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
