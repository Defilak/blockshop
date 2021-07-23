<?php

function config($key, $value = null)
{
    $arr = explode('.', $key);
    if(count($arr) == 2) {
        [$file, $key] = $arr;
    } else {
        [$file] = $arr;
        $key = null;
    }

    $config = include "$file.php";
    return empty($key) ? $config : $config[$key];
}
