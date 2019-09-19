<?php

use Database\Db;
use Router\Router;

require_once 'autoloader.php';

mb_internal_encoding('UTF-8');

$router = new Router(new Db);
$router->process($_SERVER['REQUEST_URI']);

$router->loadControllerToView()->renderBase();

