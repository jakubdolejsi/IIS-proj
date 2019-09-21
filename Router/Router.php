<?php /** @noinspection ALL */

namespace Router;

use Controllers\aController;
use DI\Container;
use DI\ModelFactory;
use Views\ViewRenderer\ViewRenderer;


/**
 * Class Router
 * @package Router
 */
final class Router extends aController
{
	/**
	 * @var ViewRenderer
	 */
	private $viewRenderer;
	/**
	 * @var aController
	 */
	protected $controller;


	/**
	 * @param string $params
	 * @throws \Exceptions\aBaseException
	 */
	public function process($params): void
	{
		$this->viewRenderer = $this->getViewFactory()->getViewRenderer();
		$url = $this->parseUrl($params);
		$this->controller = $this->loadClass($this->getControllerClass($url));
		$this->controller->process($url);

		$this->initView();
	}

	/**
	 * @param array $url
	 * @return string
	 */
	private function getControllerClass(array $url)
	{
		 return (ucwords($url[0]). 'Controller');
	}


	/**
	 * @param string $url
	 * @return array
	 */
	private function parseUrl(string $url)
	{
		$url = explode('/', trim(ltrim(parse_url($url)['path'], '/')));
		if(empty($url[0])){
			$this->redirect('home');
		}
		return ($url);
	}

	/**
	 * @param string $class
	 * @return aController
	 */
	private function loadClass(string $class): aController
	{
		$cls = $class . '.php';
		// TODO: recursive search...
		$path = getcwd() . '\\Controllers\\'. $cls;
		if (!file_exists($path))
		{
			$this->redirect('error');
		}
		$class = 'Controllers\\'.$class;

		return new $class($this->getContainer());
	}

	/**
	 * @return ViewRenderer
	 */
	public function getViewRenderer(): ViewRenderer
	{
		return $this->viewRenderer;
	}

	/**
	 * Set view properties
	 */
	private function initView(): void
	{
		$this->viewRenderer->loadBaseView('BaseLayout');
		$this->viewRenderer->loadControllerView($this->controller->getView());
		$this->viewRenderer->loadData($this->controller->getData());
	}

}