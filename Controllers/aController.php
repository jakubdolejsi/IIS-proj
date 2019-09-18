<?php


namespace Controllers;


abstract class aController
{
	protected $view = "";

	protected $data = [];

	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}
}