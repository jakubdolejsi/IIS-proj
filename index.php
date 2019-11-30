<?php

require_once 'autoloader.php';

use DI\Container;
use Enviroment\Enviroment;
use Router\Router;


Enviroment::setVersion(Enviroment::VERSION['DEVEL']);
Enviroment::setEncoding();
Enviroment::setErrorNotification();
Enviroment::setSessions();

$container = new Container;
$router = new Router($container);
$router->process($_SERVER['REQUEST_URI']);

$router->getViewRenderer()->renderBase();
