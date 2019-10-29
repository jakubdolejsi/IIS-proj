<?php


namespace Controllers;


class AboutController extends baseController
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