<?php


namespace Models;


use Authentication\Auth;
use Authentication\Roles\Cashier;
use Database\Db;
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
use Exceptions\CompleteRegistrationException;
use Helpers\Sessions\Session;


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
		$role = $this->auth->role()->getRoleFromeSession();
		$role->logout();
	}

    /**
     * @return bool
     * @throws DuplicateUser
     * @throws PasswordsAreNotSameException
     * @throws CompleteRegistrationException
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
     * @return bool
     */
    public function oneTimeRegister(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->auth->notRegisteredUser()->oneTimeRegister();

            return TRUE;
        }

        return FALSE;
    }

    public function getHashCode():string
    {
        return $this->auth->notRegisteredUser()->generateHash();
    }

	/**
	 * @return UserDetail
	 */
	public function getUserInfo(): UserDetail
	{
		$userRole = $this->auth->role()->getRoleFromeSession();

		return $userRole->getUserBySessionID();
	}

	/**
	 * @throws UpdateProfileException
	 * @throws UpdateProfileSuccess
	 */
	public function editProfile(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleFromeSession();
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
			$role = $this->auth->role()->getRoleFromeSession();
			$role->editPassword();
		}
	}


    /**
     * @param $params
     * @return string|void
     * @throws AlreadyOccupiedSeatException
     * @throws InvalidRequestException
     * @throws ReservationSuccessException
     * @throws SqlSomethingGoneWrongException
     */
	public function createReservation($params)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleFromeSession();
			return $role->createNewReservation($params);
		}
	}

	public function checkVerificationCode($params)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $this->auth->role()->getRoleFromeSession();
            return $role->verifyHash($params);
        }
    }


    public function hasPermission($obj)
	{
		$testClass = Cashier::class;
		$objectClass = get_class($obj);

		return $objectClass === $testClass || is_subclass_of($objectClass, $testClass);
	}


	public function getRole()
	{
		return $this->auth->role()->getRoleFromeSession();
	}

	public function default()
	{

	}

}