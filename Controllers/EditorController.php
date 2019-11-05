<?php


namespace Controllers;


class EditorController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->view = 'editor';
	}
}