<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ActiveMenu
{
    #[Assert\NotBlank]
    private string $name;

    private bool $active;

    private int $menuId;

    public function __construct()
    {
        $this->active = false;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ActiveMenu
     */
    public function setName(string $name): ActiveMenu
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return ActiveMenu
     */
    public function setActive(bool $active): ActiveMenu
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return int
     */
    public function getMenuId(): int
    {
        return $this->menuId;
    }

    /**
     * @param int $menuId
     * @return ActiveMenu
     */
    public function setMenuId(int $menuId): ActiveMenu
    {
        $this->menuId = $menuId;
        return $this;
    }
}