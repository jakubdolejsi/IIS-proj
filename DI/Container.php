<?php


namespace DI;


use Database\Db;
use Helpers\Auth\Auth;
use Helpers\Sessions\Session;


/**
 * Holds and creates dependencies
 * Class Container
 * @package DI
 */
final class Container
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
	 * @return Auth
	 */
	private function getAuth(): Auth
	{
		if (!$this->auth) {
			$this->auth = new Auth($this->getDb());
		}

		return $this->auth;
	}

	/**
	 * @return Db
	 */
	private function getDb(): Db
	{
		if (!$this->db) {
			$this->db = new Db;
		}

		return $this->db;
	}

	/**
	 * @return Session
	 */
	private function getSession(): Session
	{
		if (!$this->session) {
			$this->session = new Session;
		}

		return $this->session;
	}

	/**
	 * @return ModelFactory
	 */
	public function getModelFactory(): ModelFactory
	{
		return new ModelFactory($this->getAuth(), $this->getDb(), $this->getSession());
	}

	/**
	 * @return ViewFactory
	 */
	public function getViewFactory(): ViewFactory
	{
		return new ViewFactory;
	}
}