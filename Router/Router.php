<?php

namespace Router;


class Router extends aRouter
{

	public function process($params)
	{
		$url = $this->parseUrl($params);
		$this->controller = $this->loadClass($this->getControllerClass($url));
//		$this->controller->process();
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
		$class = $class . '.php';
		// TODO: recursive search...
		if (file_exists($class))
		{
//			$controller = new $class();
//			return $controller;
		} else
		{
			$this->redirect('error');
		}
	}

}