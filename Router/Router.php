<?php /** @noinspection ALL */

namespace Router;

use Controllers\baseController;
use Exceptions\ViewLoadException;
use ViewRenderer\ViewRenderer;


/**
 * Class Router
 * @package Router
 */
final class Router extends baseController
{
	/**
	 * @var ViewRenderer
	 */
	private $viewRenderer;
	/**
	 * @var baseController
	 */
	protected $controller;


	/**
	 * @param array $params
	 */
	public function process($params): void
	{
		$this->viewRenderer = $this->getViewFactory()->getViewRenderer();
		$url = $this->parseUrl($params);
		$this->controller = $this->loadClass($this->getControllerClass($url));
		$this->controller->process($url);

		try {
			$this->initView();
		}
		catch (ViewLoadException $exception) {
			print_r($exception->errorMessage());
			exit();
//			$this->redirect('error');
		}
	}

	/**
	 * @param array $url
	 * @return string
	 */
	private function getControllerClass(array $url)
	{
		return (ucwords($url[0]) . 'Controller');
	}


	/**
	 * @param string $url
	 * @return array
	 */
	private function parseUrl(string $url)
	{
		$url = explode('/', trim(ltrim(parse_url($url)['path'], '/')));
		if (empty($url[0])) {
			$this->redirect('home');
		}

		return ($url);
	}

	/**
	 * @param string $class
	 * @return baseController
	 */
	private function loadClass(string $class): baseController
	{
		$cls = $class . '.php';
		// TODO: recursive search...
		$path = getcwd() . '\\Controllers\\' . $cls;
		if (!file_exists($path)) {
			$this->redirect('error');
		}
		$class = 'Controllers\\' . $class;

		return new $class($this->getContainer());
	}

	public function getViewRenderer()
	{
		return $this->viewRenderer;
	}

	/**
	 * Set view properties
	 */
	private function initView(): void
	{
		$this->viewRenderer->loadControllerView($this->controller->getView());
		$this->viewRenderer->loadBaseView('BaseLayout');
		$this->viewRenderer->loadData($this->controller->getData());
	}

}