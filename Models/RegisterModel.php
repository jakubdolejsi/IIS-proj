<?php


namespace Models;


class RegisterModel extends aBaseModel
{
	public function register(): void
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$this->auth->registeredUser()->register();
			} catch (\Exception $exception) {
				var_dump($exception->getMessage());
			}
		}
	}
}