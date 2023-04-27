<?php

namespace App\Model;

use App\Entity\SimpleGuest;
use App\Entity\SimpleUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ReservationGroup
{
    /**
     * @var SimpleUser
     */
    private SimpleUser $simpleUser;

    /**
     * @var SimpleGuest|Collection
     */
    private SimpleGuest|Collection $simpleGuests;

    public function __construct()
    {
        $this->simpleGuests = new ArrayCollection();
    }

    /**
     * @return SimpleUser
     */
    public function getSimpleUser(): SimpleUser
    {
        return $this->simpleUser;
    }

    /**
     * @param SimpleUser $simpleUser
     * @return ReservationGroup
     */
    public function setSimpleUser(SimpleUser $simpleUser): ReservationGroup
    {
        $this->simpleUser = $simpleUser;
        return $this;
    }

    /**
     * @return SimpleGuest|Collection
     */
    public function getSimpleGuests(): Collection|SimpleGuest
    {
        return $this->simpleGuests;
    }

    /**
     * @param SimpleGuest $simpleGuests
     * @return ReservationGroup
     */
    public function addSimpleGuests(SimpleGuest $simpleGuests): ReservationGroup
    {
        if (!$this->simpleGuests->contains($simpleGuests)) {
            $this->simpleGuests->add($simpleGuests);
        }
        return $this;
    }

    /**
     * @param SimpleGuest|Collection $simpleGuests
     * @return $this
     */
    public function setSimpleGuests(SimpleGuest|Collection $simpleGuests): ReservationGroup
    {
        $this->simpleGuests = $simpleGuests;
        return $this;
    }

    public function getNumGuests(): int
    {
        return $this->simpleGuests->count() + 1;
    }
}