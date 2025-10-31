<?php

use Ihorrud\Counter\CommandController;

require dirname(__DIR__) . '/vendor/autoload.php';

// Manual autoloaders for packages that may not be fully installed by composer
// These can be removed once composer install completes successfully
if (file_exists(dirname(__DIR__) . '/vendor/autoload_webmozart.php')) {
    require dirname(__DIR__) . '/vendor/autoload_webmozart.php';
}
if (file_exists(dirname(__DIR__) . '/vendor/autoload_bramus.php')) {
    require dirname(__DIR__) . '/vendor/autoload_bramus.php';
}

try {
    (new CommandController())->handle();
} catch (Exception $e) {
    echo $e->getMessage();
}
