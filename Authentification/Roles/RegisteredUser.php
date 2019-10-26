<?php


namespace Authentification\Roles;


use Authentification\PostDataValidator;
use Database\Db;
use Exceptions\DuplicateUser;
use Exceptions\NoUserException;


class RegisteredUser extends PostDataValidator
{

	protected $db;


	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	public function register(): void
	{
		$userData = ($this->getPostDataAndValidate());
		if ($this->userExists($userData)) {
			throw new DuplicateUser('User already exists');
		}
		$this->processPassword($userData);
		$query = 'INSERT INTO theatre.users(firstName, lastName, email, password) VALUES (?,?,?,?)';
		$this->db->run($query, $userData);
		session_start();
		$_SESSION['user_id'] = $this->db->lastInsertId();
	}

	private function userExists($userData)
	{
		$query = 'select * from theatre.users where email=?';
		$res = $this->db->run($query, array($userData['email']))->fetch();

		return (empty($res) ? '' : $res);
	}


	protected function processPassword(&$userData)
	{
		// overi heslo...
		unset($userData['password2']);
		$userData = array_values($userData);
	}

	public function login(): void
	{
		$userData = ($this->getPostDataAndValidate());
		$user = $this->userExists($userData);
		if (empty($user)) {
			throw new NoUserException('User does not exists');
		}
		$_SESSION['user_id'] = $user['id'];
		var_dump('Vitej '.$user['firstName']);
	}

	public function logout(): void
	{
		unset($_SESSION['user_id']);
		session_destroy();
	}

	public function isLogged()
	{

	}

	public function getUserByID($id): array
	{
		$query = 'select * from users where id=?';
		$res = $this->db->run($query, array($id))->fetchAll();

		return $res;
	}
}