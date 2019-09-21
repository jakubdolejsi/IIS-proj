<?php


namespace DI;



use Database\Db;
use Helpers\Auth\Auth;
use Helpers\Cookies\Cookies;
use Helpers\Sessions\Session;
use Views\ViewRenderer\ViewFactory;
use Views\ViewRenderer\ViewRenderer;


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


	private function getAuth(): Auth
	{
		if(!$this->auth){
			$this->auth = new Auth($this->getDb());
		}
		return $this->auth;
	}

	private function getDb(): Db
	{
		if(!$this->db){
			$this->db = new Db();
		}
		return $this->db;
	}

	private function getSession(): Session
	{
		if(!$this->session){
			$this->session = new Session;
		}
		return $this->session;
	}

	public function getModelFactory(): ModelFactory
	{
		return new ModelFactory($this->getAuth(), $this->getDb(), $this->getSession());
	}

	public function getViewFactory(): ViewFactory
	{
		return new ViewFactory;
	}
}