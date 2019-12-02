<?php

use DI\Container;
use Enviroment\Enviroment;
use Router\Router;


require_once 'autoloader.php';
Enviroment::setSessions();
Enviroment::setVersion(Enviroment::VERSION['PRODUCTION']);
Enviroment::setEncoding();
Enviroment::setErrorNotification();


$container = new Container;
$router = new Router($container);
$router->process($_SERVER['REQUEST_URI']);

$router->getViewRenderer()->renderBase();
