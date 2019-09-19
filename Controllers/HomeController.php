<?php


namespace Controllers;

/**
 * Class HomeController
 * @package Controllers
 */
class HomeController extends aController
{
	/**
	 * @param $params
	 * @return mixed|void
	 */
	protected function process($params)
	{
		$this->view->loadControllerView('home');
		$this->data['tuska'] = "mojekurvaData";
	}

}