<?php


namespace Controllers;


use Database\Db;
use Views\ViewRenderer\ViewRenderer;


abstract class aController
{

	protected $view;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var
	 */
	protected $db;

	/**
	 * aController constructor.
	 * @param Db $db
	 */
	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	abstract protected function process(string $params): void;

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

}