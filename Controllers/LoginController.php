<?php


namespace Controllers;


class LoginController extends aController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'login';
		$login = $this->getModelFactory()->createLoginModel();
		$loginOk = $login->login();
		if($loginOk)
		{
			$this->redirect('auth');
		}
		var_dump('Spatne prihlaseni');
	}
}