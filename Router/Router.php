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

//		var_dump( $url);
        unset($url[0],$url[1], $url[2]);
        $url = array_values($url);
        $method = 'action'. ucwords($url[0] ?? 'default');
        unset($url[0]);
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
        unset($url[0]);
        unset($url[1]);
        $url = array_values($url);
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

    private function loadClass(string $class): BaseController
    {
        $cls = $class . '.php';
        // TODO: recursive search...
        $path = getcwd() . DIRECTORY_SEPARATOR .'Controllers'. DIRECTORY_SEPARATOR . $cls;
        if (!file_exists($path)) {
            $this->redirect('error');
        }
        $class = 'Controllers\\' . $class;
        return new $class($this->getContainer());
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