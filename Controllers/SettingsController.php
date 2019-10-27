<?php


namespace Controllers;


class SettingsController extends aController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{

		$user = $this->getModelFactory()->createUserModel();
		$userInformation = $user->getUserInfo();

		$this->view = 'settings';
		$this->data['firstName'] = $userInformation->getFirstName();
		$this->data['lastName'] = $userInformation->getLastName();
		$this->data['email'] = $userInformation->getEmail();
	}
}