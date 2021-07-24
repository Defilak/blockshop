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


return new Cooldown(time());

//ставим кулдаун на действия
/*$time = time();
if ($args_count > 1) { //аргумент может быть только один
    $_SESSION['buytime'] = $time + 60;
    responses\badly("Фриз тебе на одну минуту за такие дела!");
}

if (empty($_SESSION['buytime'])) {
    $_SESSION['buytime'] = 0;
}


function uptime($s1)
{
    global $_SESSION;
    $_SESSION['buytime'] = time() + $s1;
}

function check()
{
    if ($_SESSION['buytime'] > $time) {
        $tm = skl($_SESSION['buytime'] - $time, array('секунду', 'секунды', 'секунд'));
        responses\badly("До следующей операции подождите <b>{$tm}</b>!");
    }
}*/
