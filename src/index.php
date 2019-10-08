<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Container;

$container = new Container();

$container->parse("[89][98]");