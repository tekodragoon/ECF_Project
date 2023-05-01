<?php

namespace App\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class Service
{
    /**
     * @var bool
     */
    private bool $noon;
    /**
     * @var ReservedTable|ArrayCollection
     */
    private ReservedTable|ArrayCollection $reservedTables;
    /**
     * @var DateTime
     */
    private DateTime $date;

    public function __construct()
    {
        $this->reservedTables = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isNoon(): bool
    {
        return $this->noon;
    }

    /**
     * @param bool $noon
     * @return Service
     */
    public function setNoon(bool $noon): Service
    {
        $this->noon = $noon;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAllReservedTables(): ArrayCollection
    {
        return $this->reservedTables;
    }

    /**
     * @return ArrayCollection
     */
    public function getNonReservedTables(): ArrayCollection
    {
        return $this->reservedTables->filter(fn(ReservedTable $reservedTable) => !$reservedTable->isReserved());
    }

    /**
     * @return array
     */
    public function getNonReservedTablesId(): array
    {
        $nonReservedTables = $this->getNonReservedTables();
        $ids = [];
        foreach ($nonReservedTables as $nonReservedTable) {
            $ids[] = $nonReservedTable->getTable()->getId();
        }
        return $ids;
    }

    public function getMaxSeatsAvailable(): int
    {
        $nonReservedTables = $this->getNonReservedTables();
        $count = 0;
        foreach ($nonReservedTables as $nonReservedTable) {
            $count += $nonReservedTable->getTable()->getSeats();
        }
        return $count;
    }

    /**
     * @return ArrayCollection
     */
    public function getReservedTables(): ArrayCollection
    {
        return $this->reservedTables->filter(fn(ReservedTable $reservedTable) => $reservedTable->isReserved());
    }

    /**
     * @param ReservedTable $reservedTable
     * @return $this
     */
    public function addReservedTable(ReservedTable $reservedTable): Service
    {
        $this->reservedTables[] = $reservedTable;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Service
     */
    public function setDate(DateTime $date): Service
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param int $guest
     * @return array|null
     */
    public function getTableFor(int $guest): ?array
    {
        $nonReservedTables = $this->getNonReservedTables();
        // si plus de table disponible
        if ($nonReservedTables->count() === 0) {
            return null;
        }
        // liste des couples de table possibles
        $couple = [];
        if ($guest <= 4) {
            $couple = [[4]];
        } elseif ($guest <= 6) {
            $couple = [[6], [4, 4]];
        } elseif ($guest <= 8) {
            $couple = [[8], [6, 4], [4, 4]];
        } elseif ($guest <= 10) {
            $couple = [[10], [8, 4], [6, 4], [4, 4]];
        } elseif ($guest <= 12) {
            $couple = [[12], [10, 4], [8, 4], [6, 6]];
        }
        // on cherche une solution pour chaque couple
        for ($i = 0; $i < count($couple); $i++) {
            if (count($couple[$i]) === 1) {
                $table = $nonReservedTables->filter(fn(ReservedTable $reservedTable) => $reservedTable->getTable()->getSeats() === $couple[$i][0])->first();
                if ($table) {
                    return [$table->getTable()->getId()];
                }
            } else {
                $table1 = $nonReservedTables->filter(fn(ReservedTable $reservedTable) => $reservedTable->getTable()->getSeats() === $couple[$i][0])->first();
                $table2 = $nonReservedTables->filter(fn(ReservedTable $reservedTable) => $reservedTable->getTable()->getSeats() === $couple[$i][1])->first();
                if ($table1 && $table2) {
                    return [$table1->getTable()->getId(), $table2->getTable()->getId()];
                }
            }
        }
        // si aucune solution
        return null;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function reserveTable(int $id): bool
    {
        $table = $this->getNonReservedTables()->filter(fn(ReservedTable $reservedTable) => $reservedTable->getTable()->getId() === $id)->first();
        if ($table) {
            $table->setReserved(true);
            return true;
        }
        return false;
    }
}