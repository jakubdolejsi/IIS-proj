<?php


namespace Controllers;


/**
 * Class HomeController
 * @package Controllers
 */
class HomeController extends BaseController
{

	public function actionDefault(): void
	{
		$this->loadView('home');
	}
}
