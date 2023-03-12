<?php

namespace App\Model;

use DateTime;

class TimeData
{
    /**
     * @var DateTime
     */
    public DateTime $time;

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     * @return TimeData
     */
    public function setTime(DateTime $time): TimeData
    {
        $this->time = $time;
        return $this;
    }
}