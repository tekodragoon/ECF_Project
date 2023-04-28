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

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Table::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $tables;

    public function __construct()
    {
        $this->openHours = new ArrayCollection();
        $this->openDays = new ArrayCollection();
        $this->tables = new ArrayCollection();
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

    public function today(): int
    {
        date_default_timezone_set('Europe/Paris');
        return date('N');
    }

    public function todayOpenDay(int $index = null): ?OpeningDays
    {
        if (!$index) {
            $index = $this->today();
        }
        $index--;
        return $this->openDays->filter(fn(OpeningDays $day) => $day->getDayOfWeek() === $index)->first();
    }

    public function todayOpenHour(int $index = null): ?OpeningHours
    {
        if (!$index) {
            $index = $this->today();
        }
        $index--;
        return $this->openHours->filter(fn(OpeningHours $hour) => $hour->getDayOfWeek() === $index)->first();
    }

    public function isOpenToday(): bool
    {
        return $this->todayOpenDay()->isOpen();
    }

    public function isNoonServiceToday(): bool
    {
        return $this->todayOpenDay()->isNoonService();
    }

    public function getStartNoonServiceSchedule(): ?string
    {
        $noonStart = $this->todayOpenHour()->getNoonStart();
        return $noonStart?->format('H\hi');
    }

    public function getEndNoonServiceSchedule(): ?string
    {
        $noonEnd = $this->todayOpenHour()->getNoonEnd();
        return $noonEnd?->format('H\hi');
    }

    public function isEveningServiceToday(): bool
    {
        return $this->todayOpenDay()->isEveningService();
    }

    public function getStartEveningServiceSchedule(): ?string
    {
        $eveningStart = $this->todayOpenHour()->getEveningStart();
        return $eveningStart?->format('H\hi');
    }

    public function getEndEveningServiceSchedule(): ?string
    {
        $eveningEnd = $this->todayOpenHour()->getEveningEnd();
        return $eveningEnd?->format('H\hi');
    }

    /**
     * @return Collection<int, Table>
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): self
    {
        if (!$this->tables->contains($table)) {
            $this->tables->add($table);
            $table->setRestaurant($this);
        }

        return $this;
    }

    public function removeTable(Table $table): self
    {
        if ($this->tables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getRestaurant() === $this) {
                $table->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getTableKeyValue(): array
    {
        $result = array();

        foreach ($this->tables as $table) {
            $key = $table->getSeats();
            if (array_key_exists($key, $result)) {
                $result[$key] += 1;
            } else {
                $result[$key] = 1;
            }
        }

        return $result;
    }
}
