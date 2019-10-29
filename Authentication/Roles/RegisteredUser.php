<?php


namespace Authentication\Roles;


use Authentication\Validator;
use Database\Db;
use Exceptions\{DuplicateUser,
	InvalidPasswordException,
	NoUserException,
	PasswordsAreNotSameException,
	UpdateProfileException};
use Models\UserDetail;


class RegisteredUser extends Validator
{

	protected $db;


	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	/**
	 * @throws PasswordsAreNotSameException
	 * @throws DuplicateUser
	 */
	public function register(): void
	{
		$userDetail = new UserDetail($this->getPostDataAndValidate());
		$user = $this->getUserByEmail($userDetail->getEmail());
		if ($user) {
			throw new DuplicateUser('User already exists');
		}
		$this->processRegistrationPassword($userDetail);
		$query = 'INSERT INTO theatre.users(firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, ?)';
		$this->db->run($query, $userDetail->getAllProperties());
		$_SESSION['user_id'] = $this->db->lastInsertId();
	}

	private function getUserByEmail($email)
	{
		$query = 'select * from theatre.users where email = ?';
		$res = $this->db->run($query, $email)->fetch();

		return (empty($res) ? '' : $res);
	}


	/**
	 * @param $userDetail
	 * @throws PasswordsAreNotSameException
	 */
	protected function processRegistrationPassword(UserDetail $userDetail): void
	{
		if (!$userDetail->comparePassword()) {
			throw new PasswordsAreNotSameException('Passwords are not same!');
		}
		$password = $userDetail->getPassword();
		$userDetail
			->setPassword($this->hashPassword($password))
			->unsetControlPassword()
			->setRole('registeredUser');
	}

	/**
	 * @throws InvalidPasswordException
	 * @throws NoUserException
	 */
	public function login(): void
	{
		$userDetail = new UserDetail($this->getPostDataAndValidate());
		$user = $this->getUserByEmail($userDetail);
		if (empty($user)) {
			throw new NoUserException('User does not exists');
		}
		$password = $userDetail->getPassword();
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


	public function getUserBySessionID(): UserDetail
	{
		$id = $_SESSION['user_id'];
		$query = 'select * from theatre.users where id=?';

		return new UserDetail($this->db->run($query, [$id])->fetchAll()[0]);
	}

	public function editProfile(): void
	{
		$newEmail = $this->getPostDataAndValidate()['email'];
		$actualEmail = $this->getUserBySessionID()->getEmail();
		$query = 'update theatre.users set email = ? where email = ?';
		$res = $this->db->run($query, [$newEmail, $actualEmail]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateProfileException('Updating profile was not successfully completed!');
		}
	}

	public function editPassword()
	{

	}
}