<?php


namespace Controllers;


use Exceptions\InvalidPasswordException;
use Exceptions\LoggedUserException;
use Exceptions\NoUserException;


class LoginController extends BaseController
{

	/**
	 * @param array $params
	 */
	public function process(array $params): void
	{
		$this->loadView('login');
		$user = $this->getModelFactory()->createUserModel();

		if ($user->isLogged()) {
			$this->redirect('home');
		}
		try {
			$user->login();
		}
		catch (InvalidPasswordException $exception) {
			$this->alert($exception->getMessage());
		}
		catch (NoUserException $exception) {
			$this->alert($exception->getMessage());
		}
		catch (LoggedUserException $exception) {
			$this->redirect('home');
		}
	}
}