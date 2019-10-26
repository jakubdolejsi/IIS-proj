<?php


namespace Models;


class UserInformation
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

	public function setPassword($passwordHash): void
	{
		$this->password = $passwordHash;
	}

	public function getRole()
	{
		return $this->role;
	}

	public function setRole($role): void
	{
		$this->role = $role;
	}

	public function unsetControlPassword(): void
	{
		unset($this->controlPassword);
	}

	private function setLoginDataOnly(array $user): void
	{
		$this->email = $user['email'];
		$this->password = $user['password'];
	}

	private function setRegistrationData(array $user): void
	{
		$this->firstName = $user['firstName'];
		$this->lastName = $user['lastName'];
		$this->controlPassword = $user['password2'];
	}
}