<?php


namespace DI;


use Authentification\Auth;
use Database\Db;
use Helpers\Sessions\Session;
use Models\LoginModel;
use Models\RegisterModel;
use Models\UserModel;


final class ModelFactory
{
	/**
	 * @var Auth
	 */
	private $auth;
	/**
	 * @var Db
	 */
	private $db;
	/**
	 * @var Session
	 */
	private $session;


	/**
	 * ModelFactory constructor.
	 * @param Auth    $auth
	 * @param Db      $db
	 * @param Session $session
	 */
	public function __construct(Auth $auth, Db $db, Session $session)
	{
		$this->auth = $auth;
		$this->db = $db;
		$this->session = $session;
	}

	/**
	 * @return UserModel
	 */
	public function createUserModel(): UserModel
	{
		return new UserModel($this->auth, $this->db, $this->session);
	}
}