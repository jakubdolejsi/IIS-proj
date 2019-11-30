<?php


namespace Controllers;


use Exceptions\InvalidPasswordException;
use Exceptions\LoggedUserException;
use Exceptions\NoUserException;
use Exceptions\UserNotVerifiedException;


class LoginController extends BaseController
{

	public function actionDefault(): void
	{
		$this->loadView('Login');
		$user = $this->getModelFactory()->createUserModel();

		if ($user->isLogged()) {
			$this->redirect('Home');
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
		catch (UserNotVerifiedException $exception){
		    $this->alert($exception->getMessage());
        }
		catch (LoggedUserException $exception) {
			$this->redirect('Home');
		}
	}
}