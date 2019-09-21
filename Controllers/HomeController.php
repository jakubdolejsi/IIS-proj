<?php


namespace Controllers;


/**
 * Class HomeController
 * @package Controllers
 */
class HomeController extends aController
{
	/**
	 * @param string $params
	 */
	public function process($params): void
	{
		$this->view = 'home';
		$this->data['data'] = 'mojekurvaData';
	}


}
