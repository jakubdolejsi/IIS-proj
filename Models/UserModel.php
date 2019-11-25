<?php


namespace Models;


use Authentication\Roles\Cashier;
use Exceptions\AlreadyOccupiedSeatException;
use Exceptions\DuplicateUser;
use Exceptions\InvalidPasswordException;
use Exceptions\InvalidRequestException;
use Exceptions\LoggedUserException;
use Exceptions\NoUserException;
use Exceptions\PasswordsAreNotSameException;
use Exceptions\ReservationSuccessException;
use Exceptions\SqlSomethingGoneWrongException;
use Exceptions\UpdateException;
use Exceptions\UpdateSuccess;


class UserModel extends baseModel
{


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

	public function default()
	{

	}


	/**
	 * @throws PasswordsAreNotSameException
	 * @throws UpdateException
	 * @throws UpdateSuccess
	 */
	public function editPassword(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleBySessionID();
			$role->editPassword();
		}
	}

	/**
	 * @throws UpdateException
	 * @throws UpdateSuccess
	 */
	public function editProfile(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleBySessionID();
			$role->editProfile();
		}
	}

	public function eventAction($action)
	{
		if (isset($action[1], $action[2])) {

		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleBySessionID();
			switch ($action) {
				case 'add':
					$role->addHall();
					break;
				case 'remove':
					$role->removeHallbyId();
					break;
				case 'edit':
					$role->editHallbyId();
					break;
			}
		}
	}

	public function getRole()
	{
		return $this->auth->role()->getRoleBySessionID();
	}

	/**
	 * @return UserDetail
	 */
	public function getUserInfo(): UserDetail
	{
		$userRole = $this->auth->role()->getRoleBySessionID();

		return $userRole->getUserBySessionID();
	}

	public function hallAction($action, HallModel $halls)
	{
		$view = '';
		if (!isset($action[2])) {
			return ['editorHalls', $halls->getAllHalls()];
		}
		$role = $this->auth->role()->getRoleBySessionID();
		switch ($action[2]) {
			case 'new':
				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					$role->addHall($this->getPostDataAndValidate());
				}
				$data = '';
				$view = 'newHall';
				break;
			case 'edit':
				if (isset($action[3])) {
					if ($_SERVER['REQUEST_METHOD'] === 'POST') {
						// uprav dany sal
						$data = $role->editHallbyId($this->getPostDataAndValidate(), $action[3]);
					} else {
						$data = $role->getHallById($action[3]);
						$view = 'editHall';
					}
				} else {
					$view = 'error404';
				}
				break;
			case 'remove':
				if (isset($action[3])) {
					$role->removeHallbyId($action[3]);
				}
				$view = 'removeHall';
				$role->removeHallbyId();
				break;
			default:
				$view = 'editorHalls';
				break;
		}

		return [$view, $data];
	}

	public function hasPermission($obj)
	{
		$testClass = Cashier::class;
		$objectClass = get_class($obj);

		return $objectClass === $testClass || is_subclass_of($objectClass, $testClass);
	}

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



}