<?php


namespace Controllers;


use Exceptions\UpdateProfileException;


class SettingsController extends baseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$action = '';
		$user = $this->getModelFactory()->createUserModel();
		[$this->view, $action] = $this->selectAction($params);

		try {
			$user->{$action}();
		}
		catch (UpdateProfileException $exception) {
			$this->alert($exception->getMessage());
		}

		$userInformation = $user->getUserInfo();
		$this->data['firstName'] = $userInformation->getFirstName();
		$this->data['lastName'] = $userInformation->getLastName();
		$this->data['email'] = $userInformation->getEmail();
	}


	private function selectAction(array $params): array
	{
		$view = '';
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