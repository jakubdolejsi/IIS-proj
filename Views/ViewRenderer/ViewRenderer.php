<?php /** @noinspection ALL */


namespace Views\ViewRenderer;


use Controllers\aController;
use Exceptions\aBaseException;


/**
 * Class View
 * @package Views
 */
class ViewRenderer implements IViewable
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


	/**
	 * Render view passed by controller
	 */
	public function render(): void
	{
		if ($this->controllerView) {
			extract($this->controller->getData());
			// tato metoda pouze includne pohled, o validaci se bude starat nekdo jiny
			require ($this->controllerView);
		}
	}


	/**
	 * Render base View like menu, navigation bar
	 */
	public function renderBase(): void
	{
		if ($this->baseView) {
			require_once ($this->baseView);
		}
	}

	/**
	 * @param aController $controller
	 */
	public function loadController(aController $controller): void
	{
		$this->controller = $controller;
	}


	/**
	 * @param string $view
	 * @throws aBaseException
	 */
	public function loadBaseView($view): void
	{
		$validView = $this->validateView($view);
		if($validView) {
			$this->baseView = $validView;
		}
	}

	/**
	 * @param string $view
	 * @throws aBaseException
	 */
	public function loadControllerView(string $view): void
	{
		$validView = $this->validateView($view);
		if($validView) {
			$this->controllerView = $validView;
		}
	}

	/**
	 * @param $view
	 * @return string
	 * @throws aBaseException
	 */
	private function validateView($view): string
	{
		$folder = 'Views/';
		$path = $folder . $view . '.phtml';
		if (!is_file($path)) {
			// jaka vyjimka se vyhodi
			throw new aBaseException();
		}
		return $path;
	}

	private function xssProtection(){}

}

