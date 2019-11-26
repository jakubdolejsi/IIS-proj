<?php


namespace Controllers;


use Exceptions\PasswordsAreNotSameException;
use Exceptions\UpdateException;
use Exceptions\UpdateSuccess;


class SettingsController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$user = $this->getModelFactory()->createUserModel();

		if (!$user->isLogged()) {
			$this->redirect('auth');
		}

		[$this->view, $action] = $this->selectAction($params);

		try {
			$user->{$action}();
		}
		catch (UpdateSuccess $exception) {
			$this->alert($exception->getMessage());
			$this->redirect('settings');
		}
		catch (UpdateException $exception) {
			$this->alert($exception->getMessage());
			$this->redirect('settings');
		}
		catch (PasswordsAreNotSameException $exception) {
			$this->alert($exception->getMessage());
		}

		$userInformation = $user->getUserInfo();
		$this->data['firstName'] = $userInformation->getFirstName();
		$this->data['lastName'] = $userInformation->getLastName();
		$this->data['email'] = $userInformation->getEmail();
	}


	private function selectAction(array $params): array
	{
		$action = ($params[1] ?? NULL);
		switch ($action) {
			case 'edit':
				$view = 'settingsEdit';
				$action = 'editProfile';
				break;
			case 'password':
				$view = 'settingsPassword';
				$action = 'editPassword';
				break;
			default:
				$view = 'settings';
				$action = 'default';
				break;
		}

		return [$view, $action];
	}
}