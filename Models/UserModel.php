<?php


namespace Models;

use Exception;
use Exceptions\InvalidPasswordException;
use Exceptions\LoggedUserException;
use Exceptions\NoUserException;
use Exceptions\PasswordsAreNotSameException;
use Exceptions\UpdateProfileException;
use Exceptions\UpdateProfileSuccess;


class UserModel extends baseModel
{
	public function isLogged(): bool
	{
		return isset($_SESSION['user_id']);
	}

	/**
	 * @throws NoUserException
	 * @throws InvalidPasswordException
	 * @throws LoggedUserException
	 */
	public function login(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleByEmailPOST();
			if (!isset($role)) {
				throw new NoUserException('User does not exists!');
			}
			$role->login();
			throw new LoggedUserException('');
		}
	}

	public function logout(): void
	{
		$role = $this->auth->role()->getRoleBySessionID();
		$role->logout();
	}

	public function register(): bool
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$this->auth->registeredUser()->register();
				return TRUE;
			}
			catch (Exception $exception) {
				var_dump($exception->getMessage());
			}
		}

		return FALSE;
	}

	public function getUserInfo(): UserDetail
	{
		$userRole = $this->auth->role()->getRoleBySessionID();
		return $userRole->getUserBySessionID();
	}

	public function editProfile(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleBySessionID();
			$role->editProfile();
		}
	}

	/**
	 * @throws PasswordsAreNotSameException
	 * @throws UpdateProfileException
	 * @throws UpdateProfileSuccess
	 */
	public function editPassword(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleBySessionID();
			$role->editPassword();
		}
	}


	public function createReservation()
	{

	}

	public function getReservationByID()
	{

	}

	public function default(): void
	{
		return;
	}

}