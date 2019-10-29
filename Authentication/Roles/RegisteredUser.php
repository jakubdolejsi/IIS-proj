<?php


namespace Authentication\Roles;


use Authentication\Validator;
use Database\Db;
use Exceptions\{DuplicateUser,
	InvalidPasswordException,
	NoUserException,
	PasswordsAreNotSameException,
	UpdateProfileException,
	UpdateProfileSuccess};
use Models\UserDetail;
use PDO;


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
		$query = 'INSERT INTO theatre.user(firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, ?)';
		$this->db->run($query, $userDetail->getAllProperties());
		$_SESSION['user_id'] = $this->db->lastInsertId();
	}

	/**
	 * @param $email
	 * @return mixed|string
	 */
	private function getUserByEmail($email)
	{
		$query = 'select * from theatre.user where email = ?';
		$res = $this->db->run($query, $email)->fetch(PDO::FETCH_ASSOC);

		return (empty($res) ? '' : $res);
	}


	/**
	 * @param $userDetail
	 * @throws PasswordsAreNotSameException
	 */
	protected function processRegistrationPassword(UserDetail $userDetail): void
	{
		if (!$userDetail->compareActualPassword()) {
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
		$user = $this->getUserByEmail($userDetail->getEmail());
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

	/**
	 *
	 */
	public function logout(): void
	{
		unset($_SESSION['user_id']);
		session_destroy();
	}


	/**
	 * @return UserDetail
	 */
	public function getUserBySessionID(): UserDetail
	{
		$id = $_SESSION['user_id'];
		$query = 'select * from theatre.user where id=?';

		return new UserDetail($this->db->run($query, [$id])->fetchAll()[0]);
	}

	/**
	 * @throws UpdateProfileException
	 * @throws UpdateProfileSuccess
	 */
	public function editProfile(): void
	{
		$newEmail = $this->getPostDataAndValidate()['email'];
		$actualEmail = $this->getUserBySessionID()->getEmail();
		$query = 'update theatre.user set email = ? where email = ?';
		$res = $this->db->run($query, [$newEmail, $actualEmail]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateProfileException('Updating profile was not successfully completed!');
		}
		throw new UpdateProfileSuccess('Your email was successfully updated to ' . $newEmail);
	}

	/**
	 * @throws PasswordsAreNotSameException
	 * @throws UpdateProfileException
	 * @throws UpdateProfileSuccess
	 */
	public function editPassword(): void
	{
		$user = new UserDetail($this->getPostDataAndValidate());
		if (!$user->compareNewPassword()) {
			throw new PasswordsAreNotSameException('Passwords does not match!');
		}
		$query = 'select password from theatre.user where email = ?';
		$oldHash = $this->db->run($query, $this->getUserBySessionID()->getEmail())->fetch(PDO::FETCH_ASSOC);
		if (!$this->verifyHashPassword($user->getPassword(), $oldHash['password'])) {
			throw new PasswordsAreNotSameException('Actual password is not correct!');
		}
		$hash = $this->hashPassword($user->getNewPassword());
		$user->setPassword($hash);
		$this->updatePassword($user);
	}

	/**
	 * @param UserDetail $userDetail
	 * @throws UpdateProfileException
	 * @throws UpdateProfileSuccess
	 */
	private function updatePassword(UserDetail $userDetail): void
	{
		$query = 'update theatre.user set password = ? where email = ?';
		$password = $userDetail->getPassword();
		$email = $this->getUserBySessionID()->getEmail();

		$res = $this->db->run($query, [$password, $email]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateProfileException('Updating profile was not successfully completed!');
		}
		throw new UpdateProfileSuccess('Your password was successfully updated');
	}
}