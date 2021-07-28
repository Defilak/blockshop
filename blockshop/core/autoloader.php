<?php
namespace autoloader;

/**
 * Скрипт использует функцию из config.php
 * Пытается найти указанный класс в папках class, core, ajax.
 */
function load_class($class_name) {
    $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);

    $class_path = blockshop_root("class/$class_name.php");
    if(file_exists($class_path)) {
        include_once $class_path;
    }

    $class_path = blockshop_root("core/$class_name.php");
    if(file_exists($class_path)) {
        include_once $class_path;
    }

    $class_path = blockshop_root("ajax/$class_name.php");
    if(file_exists($class_path)) {
        include_once $class_path;
    }
}

spl_autoload_register('autoloader\load_class');