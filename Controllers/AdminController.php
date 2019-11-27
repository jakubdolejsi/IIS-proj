<?php


namespace Controllers;


use Exceptions\CompleteRegistrationException;
use Exceptions\DuplicateUser;
use Exceptions\PasswordsAreNotSameException;


class AdminController extends BaseController
{

	public function actionDefault(): void
	{
		$admin = $this->getModelFactory()->createAdminModel();
		$users = $admin->process();
		$this->loadView('admin');
		$this->data['users'] = $users;
	}


	public function actionAdd($params)
	{
		$user = $this->getModelFactory()->createUserModel();
		try {
			$user->register();
		}
		catch (PasswordsAreNotSameException $e) {
			$this->alert($e->getMessage());
		}
		catch (DuplicateUser $e) {
			$this->alert($e->getMessage());
		}
		catch (CompleteRegistrationException $e) {
			$this->alert("");
		}
		$this->loadView('adminAddUser');
	}


	public function actionEdit($params)
	{
		$this->loadView('adminEditUser');
	}


	public function actionDelete($params)
	{

	}

}