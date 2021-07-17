<?php

namespace core\exception_handler;

/**
 * Просто выводит ошибку в тегах, делая ее читабельнее.
 *
 * @param [type] $exception
 * @return void
 */
function handle_exception($exception) {
    exit("<pre>$exception</pre>");
}

set_exception_handler('core\exception_handler\handle_exception');