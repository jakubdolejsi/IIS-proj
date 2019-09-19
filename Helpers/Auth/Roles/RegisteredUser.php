<?php


namespace Auth\Roles;


use Database\Db;


class RegisteredUser
{

	protected $db;


	public function __construct(Db $db)
	{
		$this->db = $db;
	}
}