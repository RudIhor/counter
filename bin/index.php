<?php

use Ihorrud\Counter\CommandController;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/autoload_webmozart.php';
require dirname(__DIR__) . '/vendor/autoload_bramus.php';

try {
    (new CommandController())->handle();
} catch (Exception $e) {
    echo $e->getMessage();
}
