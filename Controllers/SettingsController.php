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
		$this->data['firstName'] = $userInformation['firstName'];
		$this->data['lastName'] = $userInformation['lastName'];
		$this->data['email'] = $userInformation['email'];
	}
}