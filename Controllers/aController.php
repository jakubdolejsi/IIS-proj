<?php


namespace Controllers;


use Database\Db;
use Views\ViewRenderer\View;


abstract class aController
{

	/**
	 * @var View
	 */
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
		$this->view = new View;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	abstract protected function process($params);

	/**
	 * @param $url
	 */
	protected function redirect($url)
	{
		header("Location: /$url");
		header('Connection: close');
		exit;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return ($this->data);
	}

	/**
	 * @return View
	 */
	public function getView()
	{
		return ($this->view);
	}

}