<?php


namespace Models;


class UserProfileEditModel
{
	private $email;

	private $oldPassword;

	private $newPassword;

	private $newPasswordRetype;


	public function __construct(array $user)
	{
		$this->initProperties($user);
	}

	public function comparePassword(): bool
	{
		return $this->newPassword === $this->newPasswordRetype;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getNewPassword()
	{
		return $this->newPassword;
	}

	public function getNewPasswordRetype()
	{
		return $this->newPasswordRetype;
	}

	public function getOldPassword()
	{
		return $this->oldPassword;
	}

	private function initProperties(array $user): void
	{
		while ($property = current($user)) {
			$this->{key($user)} = $property;
			next($user);
		}
	}
}