<?php


namespace Controllers;


use Exceptions\AlreadyLoggedUserException;
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
		$logged = FALSE;
		try {
			$logged = $user->login();
		}
		catch (AlreadyLoggedUserException $exception) {
			$exception->getMessage();
			$this->redirect('auth');
		}
		catch (NoUserException $exception) {
			$exception->getMessage();
		}
		if ($logged) {
			$this->redirect('auth');
		}
	}
}