<?php


namespace Controllers;


class ReservationController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$user = $this->getModelFactory()->createUserModel();
		if (!$user->isLogged()) {
			$this->view = 'reservationUnauthorized';
		} else {
			$this->view = 'reservation';
		}
	}
}