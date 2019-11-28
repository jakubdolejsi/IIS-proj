<?php


namespace Controllers;


class AuthController extends BaseController
{

	public function actionDefault(): void
	{
		$this->hasPermission('admin', 'editor', 'cashier');

		$user = $this->getModelFactory()->createUserModel();

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