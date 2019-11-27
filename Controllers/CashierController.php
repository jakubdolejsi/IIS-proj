<?php


namespace Controllers;


class CashierController extends BaseController
{
    public function actionAdd($patams):void
    {

    }

	public function actionEdit($params): void
	{
		$updateOK = FALSE;
		$ticket = $this->getModelFactory()->createTicketManager();

		$ticketsToShow = $ticket->processUpdate($params, $updateOK);
		if (isset($updateOK)) {
			//				$this->alert('OK');
		}
		$this->loadView('cashierEdit');
		$this->data['tickets'] = $ticketsToShow;
	}

	public function actionDefault(): void
	{
		$ticket = $this->getModelFactory()->createTicketManager();
		$ticketsToShow = $ticket->getTicket();
		$this->loadView('cashier');
		$this->data['tickets'] = $ticketsToShow;
	}
}