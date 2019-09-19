<?php

namespace Router;

use Controllers\aController;


class Router extends aController
{
	protected $controller;

	public function process($params)
	{
		$url = $this->parseUrl($params);
		$this->controller = $this->loadClass($this->getControllerClass($url));
		$this->controller->process($url);

//		$this->view = 'BaseLayout';
		$this->view->loadBaseView('BaseLayout');

	}

	private function getControllerClass($controller)
	{
		 return (ucwords($controller[0]). 'Controller');
	}

	private function parseUrl($url)
	{
		$url = explode("/", trim(ltrim(parse_url($url)["path"], "/")));
		if(empty($url[0])){
			$this->redirect('home');
		}
		return ($url);
	}

	private function loadClass($class)
	{
		$cls = $class . '.php';
		// TODO: recursive search...
		$path = getcwd() . '\\Controllers\\'. $cls;
		if (file_exists($path))
		{
			$class = 'Controllers\\'.$class;
			$controller = new $class("db");
			return $controller;
		} else
		{
			$this->redirect('error');
		}
	}

	public function createView()
	{

		$this->view->loadController($this->controller);
		return $this->view;

	}

}