<?php


namespace Controllers;


class AdminController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'admin';
	}
}