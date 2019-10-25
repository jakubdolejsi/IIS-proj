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
		$registrationModel = $this->getModelFactory()->createUserModel();
		$registrationModel->register();
		$this->view = 'Registration';
	}
}