<?php

namespace App\Model;

class BasicBooking
{
    private int $numGuests;
    private \DateTime $hour;

    public function __construct()
    {
        $this->numGuests = 1;
    }

    public function getNumGuests(): int
    {
        return $this->numGuests;
    }

    public function setNumGuests(int $numGuests): void
    {
        $this->numGuests = $numGuests;
    }

    public function getHour(): string
    {
        return $this->hour->format('H:i');
    }

    public function setHour(\DateTime $hour): void
    {
        $this->hour = $hour;
    }
}