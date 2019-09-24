<?php


namespace Helpers\Auth;



use Auth\Roles\Admin;


class Auth
{
	protected $db;
	public function __construct($db)
	{
		$this->db = $db;
	}

	public function login(){}

	public function register(): void
	{}

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

	public function admin(){
		return new Admin($this->db);
	}

	public function cashier(){}

	public function editor(){}

	public function registeredUser(){}


}