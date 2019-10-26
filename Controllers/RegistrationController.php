<?php


namespace Controllers;


class RegistrationController extends aController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'Registration';
		$registrationModel = $this->getModelFactory()->createUserModel();
		$registeredOK = $registrationModel->register();
		if ($registeredOK) {
			$this->redirect('auth');
		}
	}
}