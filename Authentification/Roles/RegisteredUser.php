<?php


namespace Authentification\Roles;


use Authentification\PostDataValidator;
use Database\Db;
use Exceptions\DuplicateUser;
use Exceptions\InvalidPasswordException;
use Exceptions\NoUserException;
use Exceptions\PasswordsAreNotSameException;
use Models\UserInformation;


class RegisteredUser extends PostDataValidator
{

	protected $db;


	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	/**
	 * @throws DuplicateUser
	 * @throws PasswordsAreNotSameException
	 */
	public function register(): void
	{
		$userData = new UserInformation($this->getPostDataAndValidate());
		if ($this->userExists($userData)) {
			throw new DuplicateUser('User already exists');
		}
		$this->processRegistrationPassword($userData);
		$query = 'INSERT INTO theatre.users(firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, ?)';
		$this->db->run($query, $userData->getAllProperties());
		$_SESSION['user_id'] = $this->db->lastInsertId();
	}

	private function userExists(UserInformation $userData)
	{
		$query = 'select * from theatre.users where email = ?';
		$res = $this->db->run($query, $userData->getEmail())->fetch();

		return (empty($res) ? '' : $res);
	}


	/**
	 * @param $userData
	 * @throws PasswordsAreNotSameException
	 */
	protected function processRegistrationPassword(UserInformation $userData): void
	{
		$password = $userData->getPassword();
		$controlPassword = $userData->getControlPassword();
		if ($password !== $controlPassword) {
			throw new PasswordsAreNotSameException('Passwords are not same!');
		}

		$userData->setPassword($this->hashPassword($password));
		$userData->unsetControlPassword();
		$userData->setRole('registeredUser');
	}

	/**
	 * @throws InvalidPasswordException
	 * @throws NoUserException
	 */
	public function login(): void
	{
		$userData = new UserInformation($this->getPostDataAndValidate());
		$user = $this->userExists($userData);
		if (empty($user)) {
			throw new NoUserException('User does not exists');
		}
		$password = $userData->getPassword();
		$hash = $user['password'];
		if (!$this->verifyHashPassword($password, $hash)) {
			throw new InvalidPasswordException('Invalid password');
		}

		$_SESSION['user_id'] = $user['id'];
	}

	public function logout(): void
	{
		unset($_SESSION['user_id']);
		session_destroy();
	}


	public function getUserBySessionID(): array
	{
		$id = $_SESSION['user_id'];
		$query = 'select * from users where id=?';

		return $this->db->run($query, [$id])->fetchAll()[0];
	}
}