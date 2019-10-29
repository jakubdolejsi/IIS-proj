<?php


namespace Models;

use Authentication\Auth;
use Database\Db;
use Helpers\Sessions\Session;


/**
 * Class baseModel
 * @package Models
 */
abstract class BaseModel
{
	protected $auth;

	protected $session;

	protected $db;

	public function __construct(Auth $auth, Db $db, Session $session)
	{
		$this->auth = $auth;
		$this->db = $db;
		$this->session = $session;

	}

}