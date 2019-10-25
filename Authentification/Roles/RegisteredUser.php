<?php


namespace Authentification\Roles;


use Database\Db;
use Exceptions\DuplicateUser;
use Exceptions\NoUserException;


class RegisteredUser
{

	protected $db;


	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	public function register(): void
	{
		$userData = ($this->getPostDataAndValidate());
		if($this->userExists($userData))
		{
			throw new DuplicateUser('User already exists');
		}
		$this->processPassword($userData);
		$query = 'INSERT INTO users(firstName, lastName, email, password) VALUES (?,?,?,?)';
		$this->db->run($query, $userData);
		session_start();
		$_SESSION['user_id'] = $this->db->lastInsertId();
	}

	private function userExists($userData)
	{
		$query = 'select * from users where email=?';
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
		if(empty($user))
		{
			throw new NoUserException('User does not exists');
		}
		$_SESSION['user_id'] = $user['id'];
		var_dump('Vitej '.$user['firstName']);
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

	// ********************* patri do jiny tridy ********************************

	private function parseInput($data): string
	{
		return (htmlspecialchars(stripslashes(trim($data))));
	}

	protected function getPostDataAndValidate(): array
	{
		return (array_map([$this, 'parseInput'], $_POST));
	}
}