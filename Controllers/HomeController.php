<?php


namespace Controllers;


/**
 * Class HomeController
 * @package Controllers
 */
class HomeController extends BaseController
{
	/**
	 * @param array $params
	 */
	public function process(array $params): void
	{
		$this->loadView('home');
	}
}
