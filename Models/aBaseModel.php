<?php


namespace Models;

use Database\Db;


/**
 * Class aBaseModel
 * @package Models
 */
abstract class aBaseModel
{
	/**
	 * @var Db
	 */
	protected $db;


	public function __construct(Db $db)
	{
		$this->db = $db;
	}

}