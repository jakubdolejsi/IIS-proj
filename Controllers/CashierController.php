<?php


namespace Controllers;


class CashierController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'cashier';
	}
}