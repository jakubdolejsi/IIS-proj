<?php


namespace Controllers;


class LogoutController extends BaseController
{

	public function actionDefault(): void
	{
		$user = $this->getModelFactory()->createUserModel();
		if (!$user->isLogged()) {
			$this->redirect('error');
		}
		$user->logout();
		$this->redirect('home');
	}
}