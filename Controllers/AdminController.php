<?php


namespace Controllers;


class AdminController extends BaseController
{

	public function actionDefault(): void
	{
		$this->loadView('admin');
	}
}