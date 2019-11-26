<?php


namespace Controllers;


class AuthController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
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
		//		var_dump($role);
		//		$this->data['firstName'] = $user->getUserInfo()->getFirstName();
		//		$this->view = 'auth';
	}

}