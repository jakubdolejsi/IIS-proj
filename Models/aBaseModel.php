<?php


namespace Models;

/**
 * Class aBaseModel
 * @package Models
 */
abstract class aBaseModel
{
	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

}