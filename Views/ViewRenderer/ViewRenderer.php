<?php /** @noinspection ALL */


namespace Views\ViewRenderer;


use Controllers\aController;
use Exceptions\ViewLoadException;


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
	 * @var array
	 */
	private $data;

	/**
	 * Render view passed by controller
	 */
	public function render(): void
	{
		if ($this->controllerView) {
			extract($this->data);
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
	 * @param array $data
	 */
	public function loadData(array $data): void
	{
		$this->data = $data;
	}


	/**
	 * @param $view
	 * @return string
	 * @throws ViewLoadException
	 */
	private function validateView($view): string
	{
		$folder = 'Views/';
		$path = $folder . $view . '.phtml';
		if (!is_file($path)) {
			// jaka vyjimka se vyhodi??
			throw new ViewLoadException();
		}
		return $path;
	}


	private function xssProtection(){}

}

