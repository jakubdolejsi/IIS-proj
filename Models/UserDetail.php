<?php


namespace Models;


class UserDetail
{
	protected $firstName;

	protected $lastName;

	protected $email;

	protected $password;

	protected $controlPassword;

	protected $newPassword;

	protected $newPasswordRetype;

	protected $role;

	public function __construct(array $user)
	{
		$this->initProperties($user);
	}

	public function getAllProperties(): array
	{
		$arr = [];
		foreach ($this as $key => $value) {
			if (isset($value)) {
				$arr[] = $value;
			}
		}
		return $arr;
	}

	public function getControlPassword()
	{
		return $this->controlPassword;
	}

	public function getNewPassword()
	{
		return $this->newPassword;
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

	public function setNewPassword($passwordHash): UserDetail
	{
		$this->newPassword = $passwordHash;

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

	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}


	public function unsetControlPassword(): UserDetail
	{
		unset($this->controlPassword);
		return $this;
	}

	public function compareActualPassword(): bool
	{
		return $this->password === $this->controlPassword;
	}

	public function compareNewPassword(): bool
	{
		return $this->newPassword === $this->newPasswordRetype;
	}

	private function initProperties(array $user): void
	{
		while ($property = current($user)) {
			$this->{key($user)} = $property;
			next($user);
		}
	}



}