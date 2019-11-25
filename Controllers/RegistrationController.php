<?php


namespace Controllers;


use Exceptions\DuplicateUser;
use Exceptions\PasswordsAreNotSameException;


class RegistrationController extends BaseController
{

	/**
	 * @param array $params
	 * @return mixed
	 * @throws DuplicateUser
	 * @throws PasswordsAreNotSameException
	 */
	public function process(array $params): void
	{
		$this->loadView('registration');
		$registrationModel = $this->getModelFactory()->createUserModel();
		$registeredOK = $registrationModel->register();
		if ($registeredOK) {
			$this->redirect('home');
		}
	}
}