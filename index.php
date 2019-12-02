<?php

use DI\Container;
use Enviroment\Enviroment;
use Router\Router;


require_once 'autoloader.php';

Enviroment::setVersion(Enviroment::VERSION['PRODUCTION']);
Enviroment::setEncoding();
Enviroment::setErrorNotification();
Enviroment::setSessions();

$container = new Container;
$router = new Router($container);
$router->process($_SERVER['REQUEST_URI']);

$router->getViewRenderer()->renderBase();
