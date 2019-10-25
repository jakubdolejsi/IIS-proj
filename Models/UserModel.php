<?php


namespace Models;


use Exception;
use Exceptions\NoUserException;


class UserModel extends aBaseModel
{
	public function isLogged()
	{

	}

	public function login(): bool
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$this->auth->registeredUser()->login();
			}
			catch (NoUserException $e) {
				var_dump($e->getMessage());

				return FALSE;
			}
		}

		return TRUE;
	}

	public function logout()
	{

	}

	public function register(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$this->auth->registeredUser()->register();
			}
			catch (Exception $exception) {
				var_dump($exception->getMessage());
			}
		}
	}

}