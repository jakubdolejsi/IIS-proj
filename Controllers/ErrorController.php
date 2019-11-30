<?php


namespace Controllers;


class ErrorController extends BaseController
{

	public function actionDefault(): void
	{
		$this->loadView('Error404');
		header('HTTP/1.0 404 Not Found');
	}
}