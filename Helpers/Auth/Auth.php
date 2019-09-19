<?php


namespace Auth;


use Database\Db;


final class Auth
{
	/**
	 * @var Db
	 */
	private $db;

	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	public function login(){}

	public function register(){}

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

	public function admin(){}

	public function cashier(){}

	public function editor(){}

	public function registeredUser(){}


}