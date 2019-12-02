<?php


namespace Models;

use Authentication\Auth;
use Authentication\Validator;
use Database\Db;
use Helpers\Sessions\Session;


/**
 * Class BaseModel
 * @package Models
 */
abstract class BaseModel extends Validator
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