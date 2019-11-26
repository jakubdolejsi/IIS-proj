<?php


namespace Controllers;


class AuthController extends BaseController
{

	public function actionDefault(): void
	{
		$user = $this->getModelFactory()->createUserModel();

		if (!$user->isLogged() || !$user->hasPermission($user->getRole())) {
			$this->alert('Permission denied');
			$this->redirect('login');
		}
		$role = $user->getUserInfo()->getRole();
		switch ($role) {
			case 'admin':
				$this->redirect('admin');
				break;
			case 'editor':
				$this->redirect('editor');
				break;
			case 'cashier':
				$this->redirect('cashier');
				break;
		}
	}
}