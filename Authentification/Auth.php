<?php


namespace Authentification;



use Authentification\Roles\Admin;
use Authentification\Roles\Cashier;
use Authentification\Roles\Editor;
use Authentification\Roles\RegisteredUser;


class Auth
{
	protected $db;
	public function __construct($db)
	{
		$this->db = $db;
	}

	public function logout(){}

	public function isLoggedIn(){}

	public function isRegistered(){}

	public function getUserId(){}

	public function getUserFirstName(){}

	public function getUserLastName(){}

	public function getUserEmail(){}

	public function getUserBirthday(){}

	public function getUserPhoneNumber(){}

	public function setRoleById(){}

	public function getRoleById(){}

	public function admin()
	{
		return new Admin($this->db);
	}

	public function cashier(): Cashier
	{
		return new Cashier($this->db);
	}

	public function editor(): Editor
	{
		return new Editor($this->db);
	}

	public function registeredUser(): RegisteredUser
	{
		return new RegisteredUser($this->db);
	}


}