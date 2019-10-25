<?php


namespace Controllers;


class AuthController extends aController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		if(!isset($_SESSION['user_id']))
		{
			$this->redirect('login');
		}
		$this->view = 'auth';
		var_dump('Nyni jsi prihlasen');
	}
}