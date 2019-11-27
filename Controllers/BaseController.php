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

	private $view;

	/**
	 * @var array
	 */
	protected $data = [];


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
	 * @param string $url
	 */

	protected function redirect(string $url): void
	{
		// todo snad funguje dobre...
		echo "<script>
				window.location.href='/$url';
			</script>";
	}

	protected function alert($message): void
	{
		echo "<script>
				alert('$message');
			</script>";
	}

	/**
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getView(): ?string
	{
		return $this->view;
	}

	/**
	 * @return ModelFactory
	 */
	public function getModelFactory(): ModelFactory
	{
		if(!$this->modelFactory)
		{
			$this->modelFactory = $this->container->getModelFactory();
		}
		return $this->modelFactory;
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

	/**
	 * @param $cls
	 * @return bool
	 */
	private function isCalledClassRouter($cls): bool
	{
		return $cls === 'Router\Router';
	}

	private function blame(): void
	{
		echo '<pre>', var_dump('Tuhle metodu nemas co volat kamo'), '</pre>';
		exit();
	}

	/**
	 * @return ViewFactory
	 */
	private function setViewFactory(): ViewFactory
	{
		if(!$this->viewFactory)
		{
			$this->viewFactory = $this->container->getViewFactory();
		}
		return $this->viewFactory;
	}

	/**
	 * @param string $view
	 */
	protected function loadView(string $view): void
	{
		$this->view = $view;
	}

	protected function loadData($index, array $data)
	{
		$this->data[ $index ] = $data;
	}
}