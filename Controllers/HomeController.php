<?php


namespace Controllers;


/**
 * Class HomeController
 * @package Controllers
 */
class HomeController extends aController
{
	/**
	 * @param array $params
	 */
	public function process(array $params): void
	{
		$this->view = 'home';
		$this->data['data'] = 'mojekurvaData';
//		$model->setX(10)->setY(10);
	}

}
