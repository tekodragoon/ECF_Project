<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: OpeningHours::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $openHours;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: OpeningDays::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $openDays;

    public function __construct()
    {
        $this->openHours = new ArrayCollection();
        $this->openDays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, OpeningHours>
     */
    public function getOpenHours(): Collection
    {
        return $this->openHours;
    }

    public function addOpenHour(OpeningHours $openHour): self
    {
        if (!$this->openHours->contains($openHour)) {
            $this->openHours->add($openHour);
            $openHour->setRestaurant($this);
        }

        return $this;
    }

    public function removeOpenHour(OpeningHours $openHour): self
    {
        if ($this->openHours->removeElement($openHour)) {
            // set the owning side to null (unless already changed)
            if ($openHour->getRestaurant() === $this) {
                $openHour->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OpeningDays>
     */
    public function getOpenDays(): Collection
    {
        return $this->openDays;
    }

    public function addOpenDay(OpeningDays $openDay): self
    {
        if (!$this->openDays->contains($openDay)) {
            $this->openDays->add($openDay);
            $openDay->setRestaurant($this);
        }

        return $this;
    }

    public function removeOpenDay(OpeningDays $openDay): self
    {
        if ($this->openDays->removeElement($openDay)) {
            // set the owning side to null (unless already changed)
            if ($openDay->getRestaurant() === $this) {
                $openDay->setRestaurant(null);
            }
        }

        return $this;
    }
}
