<?php


namespace Models;


use Exceptions\AlreadyOccupiedSeatException;
use Exceptions\DuplicateUser;
use Exceptions\InvalidPasswordException;
use Exceptions\InvalidRequestException;
use Exceptions\LoggedUserException;
use Exceptions\NoUserException;
use Exceptions\PasswordsAreNotSameException;
use Exceptions\ReservationSuccessException;
use Exceptions\SqlSomethingGoneWrongException;
use Exceptions\UpdateProfileException;
use Exceptions\UpdateProfileSuccess;


class UserModel extends baseModel
{
	public function isLogged(): bool
	{
		return isset($_SESSION['user_id']);
	}


	/**
	 * @throws InvalidPasswordException
	 * @throws LoggedUserException
	 * @throws NoUserException
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

	/**
	 * @return bool
	 * @throws DuplicateUser
	 * @throws PasswordsAreNotSameException
	 */
	public function register(): bool
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->auth->registeredUser()->register();

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * @return UserDetail
	 */
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


	/**
	 * @param $params
	 * @throws AlreadyOccupiedSeatException
	 * @throws InvalidRequestException
	 * @throws ReservationSuccessException
	 * @throws SqlSomethingGoneWrongException
	 */
	public function createReservation($params): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleBySessionID();
			$role->createNewReservation($params);
		}
	}

	public function getReservationByEmail($email)
	{

	}

	public function default(): void
	{
		return;
	}

}