<?php


namespace Controllers;


class AuthController extends aController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'auth';
		if(!isset($_SESSION['user_id']))
		{
			$this->redirect('login');
		}
		$user = $this->getModelFactory()->createUserModel();
		$userInfo = $user->getUserInfo();
		$this->data['firstName'] = $userInfo['firstName'];
	}
}