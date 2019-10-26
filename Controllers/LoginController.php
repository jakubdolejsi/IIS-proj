<?php


namespace Controllers;


use Exceptions\InvalidPasswordException;
use Exceptions\LoggedUserException;
use Exceptions\NoUserException;


class LoginController extends aController
{

	/**
	 * @param array $params
	 */
	public function process(array $params): void
	{
		$this->view = 'login';
		$user = $this->getModelFactory()->createUserModel();

		if ($user->isLogged()) {
			$this->redirect('auth');
		}
		try {
			$user->login();
		}
		catch (InvalidPasswordException $exception) {
			var_dump($exception->getMessage());
		}
		catch (NoUserException $exception) {
			var_dump($exception->getMessage());
		}
		catch (LoggedUserException $exception) {
			$this->redirect('auth');
		}
	}
}