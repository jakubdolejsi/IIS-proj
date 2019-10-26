<?php


namespace Models;

use Exception;
use Exceptions\AlreadyLoggedUserException;
use Exceptions\NoUserException;


class UserModel extends aBaseModel
{
	public function isLogged(): bool
	{
		return TRUE;
	}

	/**
	 * @throws AlreadyLoggedUserException
	 * @throws NoUserException
	 */
	public function login(): bool
	{
		if (isset($_SESSION['user_id'])) {
			throw new AlreadyLoggedUserException('You are already logged in');
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleByEmailPOST();
			$role->login();

			return TRUE;
		}

		return FALSE;
	}

	public function logout()
	{
		$role = $this->auth->role()->getRoleBySessionID();
		$role->logout();
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

	public function createReservation()
	{

	}

	public function getReservationByID()
	{

	}

}