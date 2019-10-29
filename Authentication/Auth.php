<?php


namespace Authentication;


use Authentication\Roles\RegisteredUser;
use Database\Db;


class Auth
{
	protected $db;

	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	/**
	 * @return Role
	 */
	public function role(): Role
	{
		return new Role($this->db);
	}

	/** @return RegisteredUser
	 * @internal Vola se pouze pri registraci
	 */
	public function registeredUser(): RegisteredUser
	{
		return new RegisteredUser($this->db);
	}

}