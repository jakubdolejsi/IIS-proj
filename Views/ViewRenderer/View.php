<?php /** @noinspection ALL */


namespace Views\ViewRenderer;


use Controllers\aController;


/**
 * Class View
 * @package Views
 */
class View implements IViewable
{
	/**
	 * @var string
	 */
	private $baseView;

	/**
	 * @var string
	 */
	private $controllerView;

	/**
	 * @var aController
	 */
	private $controller;


	public function render()
	{
		$this->controllerView = $this->controller->getView()->controllerView;

		if ($this->controllerView) {
			extract($this->controller->getData());
//			var_dump($tuska);
			// tato metoda pouze includne pohled, o validaci se bude starat nekdo jiny
			require ($this->controllerView);
		}
	}



	public function renderBase()
	{
		if ($this->baseView) {
			require_once ($this->baseView);
		}
	}

	public function loadController($controller)
	{
		$this->controller = $controller;
	}


	private function requireControllerView()
	{
		require_once ($this->controller->getView());
	}

	public function loadBaseView($view)
	{
		$validView = $this->validateView($view);
		if($validView) {
			$this->baseView = $validView;
		}
	}

	public function loadControllerView($view)
	{
		$validView = $this->validateView($view);
		if($validView) {
			$this->controllerView = $validView;
		}
	}

	private function validateView($view)
	{
		$folder = 'Views/';
		$path = $folder . $view . '.phtml';
		if (is_file($path)) {
			return $path;
		} else {
			return false;
		}
	}

	public function getControllerView()
	{
		return $this->controllerView;
	}

	private function xssProtection(){}
}

