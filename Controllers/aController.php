<?php


namespace Controllers;


use DI\Container;
use DI\ModelFactory;
use DI\ViewFactory;


/**
 * Class aController
 * @package Controllers
 */
abstract class aController
{

	protected $view;

	/**
	 * @var array
	 */
	protected $data = [];


	/**
	 * @var ModelFactory
	 */
	protected $modelFactory;

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
		$this->modelFactory = $container->getModelFactory();
		$this->viewFactory = $container->getViewFactory();
		$this->container = $container;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	abstract public function process(string $params): void;

	/**
	 * @param $url
	 */
	protected function redirect(string $url): void
	{
		header("Location: /$url");
		header('Connection: close');
		exit;
	}

	/**
	 * @return array
	 */
	public function getData(): array
	{
		return ($this->data);
	}

	/**
	 * @return string
	 */
	public function getView(): string
	{
		return ($this->view);
	}


	/**
	 * @return ViewFactory
	 */
	public function getViewFactory(): ViewFactory
	{
		if ($this->isCalledClassRouter(static::class)) {
			return $this->viewFactory;
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
}