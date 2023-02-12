<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ActiveMenuGroup
{
    public ActiveMenu|Collection $activeMenus;

    public function __construct()
    {
        $this->activeMenus = new ArrayCollection();
    }

    /**
     * @return ActiveMenu|array
     */
    public function getActiveMenus(): ActiveMenu|Collection
    {
        return $this->activeMenus;
    }

    /**
     * @param ActiveMenu $activeMenu
     * @return ActiveMenuGroup
     */
    public function addActiveMenus(ActiveMenu $activeMenu): self
    {
        if (!$this->activeMenus->contains($activeMenu)) {
            $this->activeMenus->add($activeMenu);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getActivesCount(): int
    {
        $count = 0;
        foreach ($this->activeMenus as $menu) {
            if ($menu->isActive()) {
                $count++;
            }
        }
        return $count;
    }
}