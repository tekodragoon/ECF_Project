<?php

namespace App\Model;

use App\Entity\Table;

class ReservedTable
{
    /**
     * @var Table
     */
    private Table $table;

    /**
     * @var bool
     */
    private bool $reserved;

    public function __construct()
    {
        $this->reserved = false;
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    /**
     * @param Table $table
     * @return ReservedTable
     */
    public function setTable(Table $table): ReservedTable
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReserved(): bool
    {
        return $this->reserved;
    }

    /**
     * @param bool $reserved
     * @return ReservedTable
     */
    public function setReserved(bool $reserved): ReservedTable
    {
        $this->reserved = $reserved;
        return $this;
    }


}