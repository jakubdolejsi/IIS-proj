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
		$properties = ['email', 'oldPassword', 'newPassword', 'newPasswordRetype'];
		foreach ($properties as $property) {
			$this->{$property} = $user[ $property ];
		}
	}
}