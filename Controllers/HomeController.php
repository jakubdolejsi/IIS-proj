<?php


namespace Controllers;


/**
 * Class HomeController
 * @package Controllers
 */
class HomeController extends aController
{
	protected $modelFactory;
	/**
	 * @param $params
	 * @return mixed|void
	 */
	public function process($params): void
	{
		$this->view = 'home';
		$this->data['data'] = 'mojekurvaData';
	}


}
