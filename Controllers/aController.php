<?php


namespace Controllers;




use Views\View;


abstract class aController extends aView
{

	protected $data = [];

	protected $db;

	public function __construct()
	{
//		$this->db = $db;
	}

	abstract protected function process($params);

	protected function redirect($url)
	{
		header("Location: /$url");
		header("Connection: close");
		exit;
	}
}