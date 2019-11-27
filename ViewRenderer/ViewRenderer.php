<?php /** @noinspection ALL */


namespace ViewRenderer;


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
			require($this->controllerView);
		}
	}


	/**
	 *
	 */
	public function renderBase(): void
	{
		if ($this->baseView) {
			require_once($this->baseView);
		}
	}


	/**
	 * @param string $view
	 * @throws aBaseException
	 */
	public function loadBaseView($view): void
	{
		$validView = $this->validateView($view);
		if (!$validView) {
			throw new ViewLoadException('View is not setted!');
		}
		$this->baseView = $validView;
	}

	/**
	 * @param string $view
	 * @throws aBaseException
	 */
	public function loadControllerView(?string $view): void
	{
		$validView = $this->validateView($view);
		$this->controllerView = $validView;
	}

	/**
	 * @param array $data
	 */
	public function loadData(array $data): void
	{
		//		$x = $this->langMapper($data);
		$this->data = $data;
	}

	private function countdim($array)
	{
		if (is_array(reset($array))) {
			$return = $this->countdim(reset($array)) + 1;
		} else {
			$return = 1;
		}

		return $return;
	}

	private function langMapper($data)
	{

		$langDict = [
			'firstName'       => 'jmeno',
			'lastName'        => 'prijmeni',
			'password'        => 'heslo',
			'controlPassword' => 'kontrolniHeslo',
			'type'            => 'typ',
			'date'            => 'datum',
			'genre'           => 'žánr',
			'begin'           => 'začátek',
			'end'             => 'konec',
			'price'           => 'cena',
			'name'            => 'jméno',
		];

		$roleDict = [
			'registeredUser' => 'registrovaný uživatel',
			'cashier'        => 'pokladní',
			'editor'         => 'redaktor',
		];

		if (array_key_exists($data, $langDict)) {
			$data = $langDict[ $data ];
		}

		return $data;
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
			throw new ViewLoadException('View is not setted!');
		}

		return $path;
	}

	private function xssProtection()
	{
	}
}

