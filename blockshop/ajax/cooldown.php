<?php

namespace cooldown;

use responses;

function set_timer() {

}

//ставим кулдаун на действия
$time = time();
if ($args_count > 1) {
    $_SESSION['buytime'] = $time + 60;
    responses\badly("Фриз тебе на одну минуту за такие дела!");
}

if (empty($_SESSION['buytime'])) {
    $_SESSION['buytime'] = 0;
}
