<?php


namespace Controllers;


use DI\Container;
use DI\ModelFactory;
use DI\ViewFactory;


/**
 * Class baseController
 * @package Controllers
 */
abstract class BaseController
{

	/**
	 * @var array
	 */
	protected $data = [];
	private $view;
	/**
	 * @var ModelFactory
	 */
	private $modelFactory;

	/**
	 * @var ViewFactory
	 */
	private $viewFactory;

	/**
	 * @var Container
	 */
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	abstract public function actionDefault(): void;

	/**
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @return ModelFactory
	 */
	public function getModelFactory(): ModelFactory
	{
		if (!$this->modelFactory) {
			$this->modelFactory = $this->container->getModelFactory();
		}

		return $this->modelFactory;
	}

	/**
	 * @return string
	 */
	public function getView(): ?string
	{
		return $this->view;
	}

	/**
	 * @return ViewFactory
	 */
	public function getViewFactory(): ViewFactory
	{
		if ($this->isCalledClassRouter(static::class)) {
			return $this->setViewFactory();
		}
		$this->blame();
	}

	protected function alert($message): void
	{
		echo "<script>
				alert('$message');
			</script>";
	}

	/**
	 * @return Container
	 */
	protected function getContainer(): Container
	{
		if ($this->isCalledClassRouter(static::class)) {
			return $this->container;
		}
		$this->blame();
	}

	protected function hasPermission(...$permission)
	{
		$hasPermission = FALSE;
		foreach ($permission as $item) {
			if ($_SESSION['role'] === $item) {
				$hasPermission = TRUE;
			}
		}
		if (!$hasPermission) {
			$this->alert('Nemáte oprávnění!');
			$this->redirect('home');
		}
	}

	protected function loadData($index, array $data)
	{
		$this->data[ $index ] = $data;
	}

	/**
	 * @param string $view
	 */
	protected function loadView(string $view): void
	{
		$this->view = $view;
	}

	/**
	 * @param string $url
	 */

	protected function redirect(string $url): void
	{
		// todo snad funguje dobre...
		echo "<script>
				window.location.href='/$url';
			</script>";
		exit();
	}

	private function blame(): void
	{
		echo '<pre>', var_dump('Nedostatečné oprávnění!'), '</pre>';
		exit();
	}

	/**
	 * @param $cls
	 * @return bool
	 */
	private function isCalledClassRouter($cls): bool
	{
		return $cls === 'Router\Router';
	}

	/**
	 * @return ViewFactory
	 */
	private function setViewFactory(): ViewFactory
	{
		if (!$this->viewFactory) {
			$this->viewFactory = $this->container->getViewFactory();
		}

		return $this->viewFactory;
	}
}