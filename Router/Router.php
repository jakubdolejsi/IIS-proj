<?php /** @noinspection ALL */

namespace Router;

use Controllers\aController;
use Views\ViewRenderer\View;


/**
 * Class Router
 * @package Router
 */
class Router extends aController
{
	/**
	 * @var aController
	 */
	protected $controller;

	/**
	 * @param $params
	 * @return mixed|void
	 */
	public function process($params)
	{
		$url = $this->parseUrl($params);
		$this->controller = $this->loadClass($this->getControllerClass($url));
		$this->controller->process($url);

		$this->view->loadBaseView('BaseLayout');

	}

	/**
	 * @param $controller
	 * @return string
	 */
	private function getControllerClass($controller)
	{
		 return (ucwords($controller[0]). 'Controller');
	}

	/**
	 * @param string $url
	 * @return array
	 */
	private function parseUrl($url)
	{
		$url = explode('/', trim(ltrim(parse_url($url)['path'], '/')));
		if(empty($url[0])){
			$this->redirect('home');
		}
		return ($url);
	}

	/**
	 * @param $class
	 * @return mixed
	 */
	private function loadClass($class)
	{
		$cls = $class . '.php';
		// TODO: recursive search...
		$path = getcwd() . '\\Controllers\\'. $cls;
		if (file_exists($path))
		{
			$class = 'Controllers\\'.$class;

			return new $class('db');
		}

		$this->redirect('error');
	}

	/**
	 * @return View
	 */
	public function loadControllerToView()
	{
		$this->view->loadController($this->controller);

		return $this->view;
	}

}