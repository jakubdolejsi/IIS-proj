<?php


namespace Controllers;


class RegistrationController extends baseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'registration';
		$registrationModel = $this->getModelFactory()->createUserModel();
		$registeredOK = $registrationModel->register();
		if ($registeredOK) {
			$this->redirect('auth');
		}
	}
}