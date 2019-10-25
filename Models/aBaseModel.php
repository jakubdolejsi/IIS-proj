<?php


namespace Models;

use Authentification\Auth;
use Database\Db;
use Helpers\Sessions\Session;


/**
 * Class aBaseModel
 * @package Models
 */
abstract class aBaseModel
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