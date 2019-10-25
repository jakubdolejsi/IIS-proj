<?php


namespace Controllers;


class AboutController extends aController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'about';
	}
}