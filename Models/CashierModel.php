<?php


namespace Models;


use Exceptions\AlreadyOccupiedSeatException;
use Exceptions\InvalidRequestException;
use Exceptions\UpdateException;


class CashierModel extends BaseModel
{
	public function checkSearchParameters()
	{
		$role = $this->auth->role()->getRoleFromSession();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = $this->loadPOST();

			if (!empty($data['id'])) {
				return $role->getTicketByIdPOST();
			} elseif (!empty($data['email'])) {
				return $role->getFutureTicketByEmailPOST();
			} elseif (!empty($data['hall'])) {
				return $role->getTicketsByHallLabel();
			} elseif (!empty($data['event'])) {
				return $role->getTicketsByEventName();
			} else {
				return $role->getAllTickets();
			}
		}

		return $role->getAllTickets();
	}

	public function checkURLParamsSearch($params)
	{
		$role = $this->auth->role()->getRoleFromSession();

		if (isset($params[0])) {
			if ($params[0] !== '') {
				if ($params[0] === 'confirm') {
					$role->confirmPayment($params[1]);

					return TRUE;
				} elseif ($params[0] === 'remove') {
					$role->removeReservation($params[1]);

					return TRUE;
				} else {
					throw new InvalidRequestException;
				}
			}
		}

		return FALSE;
	}

	public function confirmReservation($params)
	{
		$ticket = $this->loadPOST();
	}

	/**
	 * @param $params
	 * @throws AlreadyOccupiedSeatException
	 * @throws UpdateException
	 */
	public function createReservationCashier($params)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = $this->loadPOST();
			$role = $this->auth->role()->getRoleFromSession();
			if (!$role->isSeatFree($params, $data)) {
				throw new AlreadyOccupiedSeatException('Sedadlo je již obsazeno!');
			}

			$role->newReservation($params, $data);
		}
	}
}