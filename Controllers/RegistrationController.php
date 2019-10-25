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
		$registrationModel = $this->getModelFactory()->createRegistrationModel();
		$registrationModel->register();
		$this->view = 'Registration';
	}
}