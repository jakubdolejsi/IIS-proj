<?php


namespace Models;


use Database\Db;


class RegisterModel extends aBaseModel
{
	private $user;

	public function register(): void
	{
		$this->user = $this->newUser();
	}

	public function newUser()
	{
		return $this->auth->admin()->createNewUser();
	}

	public function getDb(): Db
	{
		return $this->db;
	}
}