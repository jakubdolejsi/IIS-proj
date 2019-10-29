<?php


namespace Models;


class UserDetail
{
	protected $firstName;

	protected $lastName;

	protected $email;

	protected $password;

	protected $controlPassword;

	protected $role;

	public function __construct(array $user)
	{
		if (count($user) === 2) {
			$this->setLoginDataOnly($user);
		} else {
			$this->setRegistrationData($user);
		}
	}

	public function getAllProperties(): array
	{
		$arr = [];
		foreach ($this as $key => $value) {
			$arr[] = $value;
		}

		return $arr;
	}

	public function getControlPassword()
	{
		return $this->controlPassword;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($passwordHash): UserDetail
	{
		$this->password = $passwordHash;

		return $this;
	}

	public function getRole()
	{
		return $this->role;
	}

	public function setRole($role): UserDetail
	{
		$this->role = $role;

		return $this;
	}

	public function unsetControlPassword(): UserDetail
	{
		unset($this->controlPassword);

		return $this;
	}

	private function setLoginDataOnly(array $user): UserDetail
	{
		$this->email = $user['email'];
		$this->password = $user['password'];

		return $this;
	}

	/** TODO: Predelat, je to hrozne staticky..
	 * @param array $user
	 */
	private function setRegistrationData(array $user): void
	{
		$this->firstName = $user['firstName'];
		$this->lastName = $user['lastName'];
		$this->email = $user['email'];
		$this->password = $user['password'];
		if (isset($user['password2'])) {
			$this->controlPassword = $user['password2'];
		}
	}

	public function comparePassword(): bool
	{
		return $this->password === $this->controlPassword;
	}

}