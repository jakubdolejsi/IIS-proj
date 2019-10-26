<?php


namespace Authentification\Roles;


use Authentification\PostDataValidator;
use Database\Db;
use Exceptions\DuplicateUser;
use Exceptions\InvalidPasswordException;
use Exceptions\NoUserException;
use Exceptions\PasswordsAreNotSameException;


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
		$userData = ($this->getPostDataAndValidate());
		if ($this->userExists($userData)) {
			throw new DuplicateUser('User already exists');
		}
		$this->processRegistrationPassword($userData);
		$query = 'INSERT INTO theatre.users(firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, ?)';
		$this->db->run($query, $userData);
		$_SESSION['user_id'] = $this->db->lastInsertId();
	}

	private function userExists($userData)
	{
		$query = 'select * from theatre.users where email = ?';
		$res = $this->db->run($query, array($userData['email']))->fetch();

		return (empty($res) ? '' : $res);
	}


	/**
	 * @param $userData
	 * @throws PasswordsAreNotSameException
	 */
	protected function processRegistrationPassword(&$userData): void
	{
		$password = $userData['password'];
		$controlPassword = $userData['password2'];
		if ($password !== $controlPassword) {
			throw new PasswordsAreNotSameException('Passwords are not same!');
		}

		$userData['password'] = $this->hashPassword($password);
		unset($userData['password2']);

		$userData['role'] = 'registeredUser';
		$userData = array_values($userData);
	}

	/**
	 * @throws InvalidPasswordException
	 * @throws NoUserException
	 */
	public function login(): void
	{
		$userData = ($this->getPostDataAndValidate());
		$user = $this->userExists($userData);
		if (empty($user)) {
			throw new NoUserException('User does not exists');
		}
		$password = $userData['password'];
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