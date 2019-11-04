<?php


namespace Controllers;


class PaymentCOntroller extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		// TODO: Implement process() method.
		$this->view = 'payment';
	}
}