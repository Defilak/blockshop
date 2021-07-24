<?php

class Cooldown
{
    public $currentTime;

    public function __construct($time)
    {
        $this->currentTime = $time;
        if (empty($_SESSION['buytime']))
            $_SESSION['buytime'] = 0;
    }

    public function update($addition_time): void
    {
        $_SESSION['buytime'] = $this->currentTime + $addition_time;
    }

    /**
     * @return boolean true if on cooldown
     */
    public function check(): bool
    {
        return $_SESSION['buytime'] > $this->currentTime;
    }

    public function remaining(): int
    {
        return $_SESSION['buytime'] - $this->currentTime;
    }
}
