<?php


namespace Controllers;


class AuthController extends baseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'auth';
		$user = $this->getModelFactory()->createUserModel();
		if (!$user->isLogged()) {
			$this->alert('Permission denied');
			$this->redirect('login');
		}
		$userInfo = $user->getUserInfo();
		$this->data['firstName'] = $userInfo->getFirstName();
	}
}