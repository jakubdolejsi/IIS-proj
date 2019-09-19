<?php

use Database\Db;
use Enviroment\Enviroment;
use Router\Router;

require_once 'autoloader.php';


$VERSION = Enviroment::DEVEL;
Enviroment::setEncoding();
Enviroment::setErrorNotification();


$router = new Router(new Db);
$router->process($_SERVER['REQUEST_URI']);

$router->loadControllerToView()->renderBase();

