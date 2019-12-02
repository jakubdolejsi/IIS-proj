<?php /** @noinspection ALL */

namespace Router;

use Controllers\BaseController;
use Exceptions\ViewLoadException;
use ViewRenderer\ViewRenderer;


/**
 * Class Router
 * @package Router
 */
final class Router extends BaseController
{
	/**
	 * @var BaseController
	 */
	protected $controller;
	/**
	 * @var ViewRenderer
	 */
	private $viewRenderer;

	public function actionDefault(): void
	{
		// TODO: Implement actionDefault() method.
	}

	public function getViewRenderer()
	{
		return $this->viewRenderer;
	}

	/**
	 * @param array $params
	 */
	public function process($params): void
	{
		$this->viewRenderer = $this->getViewFactory()->getViewRenderer();
		$url = $this->parseUrl($params);
		$this->controller = $this->loadClass($this->getControllerClass($url));

		$method = 'action' . ucwords($url[1] ?? 'default');
		unset($url[0], $url[1]);
		method_exists($this->controller, $method) ? $this->controller->$method(array_values($url)) : $this->redirect('error');
		//		$this->controller->process($url);

		try {
			$this->initView();
		}
		catch (ViewLoadException $exception) {
			print_r($exception->errorMessage());
			exit();
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

	private function getMethod()
	{
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

	/**
	 * @param string $class
	 * @return BaseController
	 */
	private function loadClass(string $class): BaseController
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
}