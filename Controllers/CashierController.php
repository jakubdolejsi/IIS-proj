<?php


namespace Controllers;


class CashierController extends BaseController
{


	public function process(array $params): void
	{
		$updateOK = FALSE;
		$ticket = $this->getModelFactory()->createTicketManager();
		if (count($params) === 5) {

			$ticketsToShow = $ticket->processUpdate($params, $updateOK);
			if (isset($updateOK)) {
				//				$this->alert('OK');
			}
			$this->loadView('cashierEdit');

		} else {
			$ticketsToShow = $ticket->getTicket();
			$this->loadView('cashier');

		}
		$this->data['tickets'] = $ticketsToShow;
	}
}