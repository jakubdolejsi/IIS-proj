<?php


namespace Controllers;


use Exceptions\CompleteRegistrationException;
use Exceptions\DuplicateUser;
use Exceptions\PasswordsAreNotSameException;


class AdminController extends BaseController
{

	public function actionAdd($params)
	{
		$this->hasPermission('admin');

		$user = $this->getModelFactory()->createUserModel();
		try {
			$user->register();
			$user->verifiyUser(); //TODO SNAD FUNKCNI
		}
		catch (PasswordsAreNotSameException $e) {
			$this->alert($e->getMessage());
		}
		catch (DuplicateUser $e) {
			$this->alert($e->getMessage());
		}
		catch (CompleteRegistrationException $e) {
			$this->alert('Hotovo!');
		}
		$this->loadView('adminAddUser');
	}

	public function actionDefault(): void
	{
		$this->hasPermission('admin');
		$admin = $this->getModelFactory()->createAdminModel();
		$users = $admin->process();
		$this->loadView('admin');
		$this->data['users'] = $users;
	}

	public function actionDelete($params)
	{
		$this->hasPermission('admin');

		$admin = $this->getModelFactory()->createAdminModel();
		$admin->deleteUserByID($params);

		$this->data['users'] = $admin->getAllUsers();
		$this->redirect('admin');
	}

	public function actionEdit($params)
	{
		$this->hasPermission('admin');

		$admin = $this->getModelFactory()->createAdminModel();
		$this->data['user'] = $admin->processEdit($params);
		$this->loadView('adminEdit');
	}

}