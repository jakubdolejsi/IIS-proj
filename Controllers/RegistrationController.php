<?php


namespace Controllers;


use Exceptions\DuplicateUser;
use Exceptions\PasswordsAreNotSameException;


class RegistrationController extends baseController
{

	/**
	 * @param array $params
	 * @return mixed
	 * @throws DuplicateUser
	 * @throws PasswordsAreNotSameException
	 */
	public function process(array $params): void
	{
		$this->view = 'registration';
		$registrationModel = $this->getModelFactory()->createUserModel();
		$registeredOK = $registrationModel->register();
		if ($registeredOK) {
			$this->redirect('home');
		}
	}
}