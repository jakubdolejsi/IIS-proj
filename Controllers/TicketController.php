<?php


namespace Controllers;


use Exceptions\InvalidRequestException;
use Exceptions\PaymentException;


class TicketController extends BaseController
{
	public function actionConfirm($params)
	{
		$this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');
		$ticket = $this->getModelFactory()->createTicketManager();
		$userId = $this->getModelFactory()->createUserModel()->getUserInfo()->getId();
		if ($ticket->checkURL($params)) {
			try {
				$price = $ticket->getPrice($params, $userId);
			}
			catch (InvalidRequestException $exception) {
				$this->alert($exception->getMessage());
				$this->redirect('error');
			}
			$this->data['price'] = $price;
			$this->loadView('ticketConfirm');
			try {
				if ($ticket->confirmPayment($params, $price)) {
					$this->alert('Částka uhrazena!');
					$this->redirect('ticket');
				}
			}
			catch (PaymentException $exception) {
				$this->alert($exception->getMessage());
			}
		} else {
			$this->redirect('error');
		}
	}

	//TODO uhradu i storno pravdepodobne muze proves i clovek, kteremu nepatri listek (pokud by vlozil do URL spravne ID)

	public function actionDefault(): void
	{
		$this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');

		$user = $this->getModelFactory()->createUserModel();

		$email = $user->getUserInfo()->getEmail();
		$ticketManager = $this->getModelFactory()->createTicketManager();

		$ticket = $ticketManager->getTicketByEmail($email);
		$this->data['ticket'] = $ticket;
		$this->data['currentTime'] = date('H-i-s');
		$this->data['todayDate'] = date('Y-m-d');
		$this->loadView('ticket');
	}

	public function actionStorno($params)
	{
		$this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');
		$ticket = $this->getModelFactory()->createTicketManager();
		$userId = $this->getModelFactory()->createUserModel()->getUserInfo()->getId();
		if ($ticket->checkURL($params)) {
			try {
				$ticket->validateTicket($params, $userId);
			}
			catch (InvalidRequestException $exception) {
				$this->alert($exception->getMessage());
				$this->redirect('error');
			}
			$ticket->stornoReservation($params[0]);

			$this->alert('Rezervace stornována!');
			$this->redirect('ticket');
		} else {
			$this->redirect('error');
		}
	}
}