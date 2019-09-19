<?php

use Router\Router;

require_once 'autoloader.php';

mb_internal_encoding("UTF-8");

$router = new Router();
$router->process($_SERVER['REQUEST_URI']);

$router->createView()->renderBase();

