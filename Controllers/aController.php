<?php


namespace Controllers;




use Views\View;


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
	 */
	public function __construct()
	{
//		$this->db = $db;
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
		header("Connection: close");
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