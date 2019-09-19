<?php


namespace Models;


abstract class aBaseModel
{
	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

}