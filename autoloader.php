<?php

$functions = spl_autoload_functions() ? : array();

foreach ($functions as $function) {
    spl_autoload_unregister($function);
}

function classLoader($className) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
    if (file_exists($classPath)) {
        include_once $classPath;
    } else {
        echo 'Nie można załadować klasy: '.$classPath;
    }
}

spl_autoload_register('classLoader');
