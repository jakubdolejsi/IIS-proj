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
	protected function process($params): void
	{
		$this->view = 'home';
		$this->data['data'] = 'mojekurvaData';
	}


}
