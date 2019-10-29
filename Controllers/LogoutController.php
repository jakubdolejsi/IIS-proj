<?php


namespace Controllers;


class LogoutController extends baseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$user = $this->getModelFactory()->createUserModel();
		if (!$user->isLogged()) {
			$this->redirect('error');
		}
		$user->logout();
		$this->redirect('home');
	}
}