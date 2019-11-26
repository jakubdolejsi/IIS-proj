<?php


namespace DI;


use Authentication\Auth;
use Database\Db;
use Helpers\Sessions\Session;
use Models\AuthModel;
use Models\CultureWork;
use Models\HallModel;
use Models\SearchModel;
use Models\TicketManager;
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

	public function createTicketManager(): TicketManager
	{
		return new TicketManager($this->auth, $this->db, $this->session);
	}

	public function createSearchModel(): SearchModel
	{
		return new SearchModel($this->auth, $this->db, $this->session);
	}

	public function createCultureWork(): CultureWork
	{
		return new CultureWork($this->auth, $this->db, $this->session);
	}

	public function createAuthModel(): AuthModel
	{
		return new AuthModel($this->auth, $this->db, $this->session);
	}

	public function createHallModel(): HallModel
	{
		return new HallModel($this->auth, $this->db, $this->session);
	}
}