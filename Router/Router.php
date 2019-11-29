<?php /** @noinspection ALL */

namespace Router;

use Controllers\BaseController;
use Controllers\HomeController;
use Exceptions\InvalidRequestException;
use Exceptions\ViewLoadException;
use ViewRenderer\ViewRenderer;


/**
 * Class Router
 * @package Router
 */
final class Router extends BaseController
{
	/**
	 * @var ViewRenderer
	 */
	private $viewRenderer;
	/**
	 * @var BaseController
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

		$method = 'action'. ucwords($url[1] ?? 'default');
		unset($url[0],$url[1]);
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
//        throw new InvalidRequestException('zprava vole: trida: ' .$url[0] .$url[1]. ' requst uri: '. $_SERVER['REQUEST_URI']);
	    unset($url[0]);
	    unset($url[1]);
	    $url = array_values($url);

//        throw new InvalidRequestException('zprava vole: trida: ' .$url[0] .$url[1]. ' requst uri: ' .$url . $_SERVER['REQUEST_URI']);

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
	 * @return BaseController
	 */
	private function loadClass(string $class): BaseController
	{

	    $ok = $class;
//	    throw new InvalidRequestException('zprava vole: trida: ' .$class .' requst uri: '. $_SERVER['REQUEST_URI']);
		$cls = $class . '.php';
		// TODO: recursive search...
//		DIRECTORY_SEPARATOR.'~xsvera04' . DIRECTORY_SEPARATOR . 'WWW' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . 'IIS' . DIRECTORY_SEPARATOR .

        $path =  'Controllers' . DIRECTORY_SEPARATOR . $cls;
//        throw new InvalidRequestException('zprava vole: cesta: ' .$path .' requst uri: '. $_SERVER['REQUEST_URI']);
		if (!file_exists($path)) {
			$this->redirect('error');
		}
		$class = 'Controllers' . DIRECTORY_SEPARATOR . $class;
		$finalcls = '/homes/eva/xs/xsvera04/WWW/IIS/' . $class;
//        throw new InvalidRequestException('zprava vole: trida: ' .$class .' requst uri: '. $_SERVER['REQUEST_URI']);
        $prefix = '/homes/eva/xs/xsvera04/WWW/IIS/'.$class . '.php';
        require_once ($prefix);
        echo '<br>';
        var_dump($prefix);
        echo '<br>';
        var_dump($class);
        echo '<br>';
//        $x = new HomeController($this->getContainer());
		return new $ok($this->getContainer());
	}


	private function getMethod()
	{
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

	public function actionDefault(): void
	{
		// TODO: Implement actionDefault() method.
	}
}