<?php

use Ihorrud\Counter\CommandController;

require dirname(__DIR__) . '/vendor/autoload.php';

try {
    (new CommandController())->handle();
} catch (Exception $e) {
    echo $e->getMessage();
}
