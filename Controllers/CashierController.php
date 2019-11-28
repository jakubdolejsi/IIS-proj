<?php


namespace Controllers;


use Exceptions\InvalidRequestException;


class CashierController extends BaseController
{
    public function actionAdd($params):void
    {
    	$this->hasPermission('cashier', 'admin');
        $search = $this->getModelFactory()->createSearchModel();
        $events = $search->process();
        $this->loadView('cashierAdd');
        $this->data['events'] = $events;
    }

    public function actionConfirm($params):void
    {
	    $this->hasPermission('cashier', 'admin');
        $cashier = $this->getModelFactory()->createCashierModel();

        $cashier->createReservationCashier($params)

        $this->loadView('cashierConfirm');
    }

	public function actionEdit($params): void
	{
		$this->hasPermission('cashier', 'admin');
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
		$this->hasPermission('cashier', 'admin');
		$this->loadView('cashier');
	}
}