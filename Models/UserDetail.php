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

	protected $hash;

	protected $id;

	public function getId()
    {
	    return $this->id;
    }

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

	public function setEmail($email): UserDetail
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

	public function getHash():bool
    {
        return $this->hash;
    }


	private function initProperties(array $user): void
	{
	    foreach ($user as $key=>$value){
	        $this->$key = $value;
        }
	}
}