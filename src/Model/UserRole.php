<?php

namespace App\Model;

class UserRole
{
    /**
     * @var string
     */
    private string $role;

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return UserRole
     */
    public function setRole(string $role): UserRole
    {
        $this->role = $role;
        return $this;
    }

}