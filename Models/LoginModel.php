<?php


namespace Models;


use Exceptions\NoUserException;


class LoginModel extends aBaseModel
{
	public function login(): bool
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$this->auth->registeredUser()->login();
			}
			catch (NoUserException $e) {
				var_dump($e->getMessage());
				return false;
			}
		}
		return true;
	}
}