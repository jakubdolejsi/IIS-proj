<?php


namespace Controllers;




use Views\View;


abstract class aController
{

	/**
	 * @var View
	 */
	protected $view;

	protected $data = [];

	protected $db;

	public function __construct()
	{
//		$this->db = $db;
		$this->view = new View;
	}

	abstract protected function process($params);

	protected function redirect($url)
	{
		header("Location: /$url");
		header("Connection: close");
		exit;
	}

	public function getData()
	{
		return ($this->data);
	}

	public function getView()
	{
		return ($this->view);
	}

}