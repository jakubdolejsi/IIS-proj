<?php


namespace Controllers;


use Exceptions\UpdateProfileSuccess;


class CashierController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$ticket = $this->getModelFactory()->createTicketManager();
		if (count($params) === 5) {
			try {
				$ticketsToShow = $ticket->processUpdate($params);
			}
			catch (UpdateProfileSuccess $e) {
				$this->alert($e->getMessage());
				$this->redirect('cashier');
			}
			$this->view = 'cashierEdit';
		} else {
			$ticketsToShow = $ticket->getTicket();
			$this->view = 'cashier';
		}
		$this->data['tickets'] = $ticketsToShow;
	}
}