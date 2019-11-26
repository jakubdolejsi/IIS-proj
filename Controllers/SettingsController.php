<?php


namespace Controllers;


use Exceptions\PasswordsAreNotSameException;
use Exceptions\UpdateException;
use Exceptions\UpdateSuccess;
use Models\UserModel;


class SettingsController extends BaseController
{

	public function actionEdit(): void
	{
		$user = $this->getModelFactory()->createUserModel();

		if (!$user->isLogged()) {
			$this->redirect('auth');
		}
		$view = 'settingsEdit';
		$action = 'editProfile';
		try {
			$user->editProfile();
		} catch (UpdateSuccess $e) {
			$this->alert($e->getMessage());
		}

		$this->loadView('settingsEdit');
		$this->setData($user);
	}

	public function actionPassword(): void
	{
		$user = $this->getModelFactory()->createUserModel();

		if (!$user->isLogged()) {
			$this->redirect('auth');
		}
		try {
			$user->editPassword();
		}
		catch (PasswordsAreNotSameException $e) {
			$this->alert($e->getMessage());
		}
		catch (UpdateSuccess $e) {
			$this->alert($e->getMessage());
		}

		$this->loadView('settingsPassword');
		$this->setData($user);
	}

	public function actionDefault(): void
	{
		$user = $this->getModelFactory()->createUserModel();

		if (!$user->isLogged()) {
			$this->redirect('auth');
		}
		$this->loadView('settings');
		$this->setData($user);

	}


	private function setData(UserModel $user): void
	{
		$userInformation = $user->getUserInfo();
		$this->data['firstName'] = $userInformation->getFirstName();
		$this->data['lastName'] = $userInformation->getLastName();
		$this->data['email'] = $userInformation->getEmail();
	}
}