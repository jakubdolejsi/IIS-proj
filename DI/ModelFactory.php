<?php


namespace DI;


use Authentification\Auth;
use Database\Db;
use Helpers\Sessions\Session;
use Models\LoginModel;
use Models\RegisterModel;


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
	 * @return RegisterModel
	 */
	public function createRegistrationModel(): RegisterModel
	{
		return new RegisterModel($this->auth, $this->db, $this->session);
	}

	public function createLoginModel(): LoginModel
	{
		return new LoginModel($this->auth, $this->db, $this->session);
	}


}